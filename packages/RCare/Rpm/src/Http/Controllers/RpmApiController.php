<?php
namespace RCare\Rpm\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\API\Http\Controllers\ECGAPIController;
use Illuminate\Http\Request;
use RCare\Rpm\Models\MonthlyService;
use RCare\Rpm\Models\Patient;
// use RCare\Rpm\Models\MailTemplate;
use RCare\Rpm\Models\Template;
use RCare\Org\OrgPackages\QCTemplates\src\Models\ContentTemplate;
// use RCare\Rpm\Models\RcareServices; //to be deleted file
// use RCare\Rpm\Models\RcareSubServices; //to be deleted file
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
use RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate;
// use RCare\Rpm\Models\Questionnaire;
use RCare\Rpm\Models\QuestionnaireTemplateUsageHistoryrpm;
use RCare\Rpm\Models\ContentTemplateUsageHistoryrpm;
// use RCare\Rpm\Models\PatientTimeRecord;
use RCare\Rpm\Http\Requests\MonthlyServicesRequest;
use RCare\Rpm\Http\Requests\DeviceOrderPlaceRequest;
use RCare\Patients\Models\PatientPersonalNotes;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientAddress; 
use RCare\Patients\Models\PatientMedication;
use  RCare\Org\OrgPackages\Medication\src\Models\Medication;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\PatientServices;
use RCare\Rpm\Models\Device_Order;
use RCare\API\Models\OfficeMst;
use RCare\Rpm\Models\Devices;
use RCare\Rpm\Models\Partner_Devices;
use RCare\Patients\Models\PatientDevices;
use RCare\Org\OrgPackages\RPMBillingConfiguration\src\Models\RPMBilling;
use RCare\Org\OrgPackages\Partner\src\Models\PartnerDevices;
use Hash;
use DB;
use Validator,Redirect,Response;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RpmApiController extends Controller
{
    public function index()
    {
        return view('Rpm::monthly-service.patient-list');
    }
    
     public function ViewDeviceOrder()
    {       
       $devicesdata=Devices::where('status','1')->get();      
        return view('Rpm::patient.device-order', compact('devicesdata'));
    }
    
    public function getOfficedata()
    {
       $ecgcredetials=ApiECGCredeintials();     
       $groupname= $ecgcredetials[0]->group_name; 
       $officedata['shipping_details']['static']= RPMBilling::where('status','1')->first();
       // $officedata['shipping_details']['static'] = OfficeMst::where('name', 'bRenova')->where('group_code', $groupname)->first();
      return  $officedata;
    }
    // created by radha (25/03/2021) render device order from database
    public function renderDeviceOrderList()
    {
       $data=Device_Order::orderBy('created_at', 'DESC')->get();
       if(!empty($data))
       {
       $dev = $data[0]->devices;
       $devv = explode(",", $dev);
           $tracknum;
           $shipp_partner;
           $shipp_dt;
           $orderid;
           $deviceid;

        for($i=0;$i<count($data);$i++)
        {
           $device_data=$data[$i]->devices;
           $devicedata=[];
           $d;

           foreach(json_decode($device_data) as $key=>$value)
           {
            $devicename=Partner_Devices::where('device_name_api',$value->type)->with('device')->get();         
            $d=$devicename[0]['device']->device_name;
              $devicedata[]=$d; 
              if(isset($value->option))
              {
                 $devicedata[$key]=$d." (".$value->option.")";               
              }                        
           }

           $finaldata=implode(", \n", $devicedata);           
           $data[$i]->devicefinal = $finaldata;          
        }
      }
       
        return Datatables::of($data)
            ->addIndexColumn()  
             ->addColumn('details', function($row){              
            $btn = '<a href="order-details/'.$row->id.'" class="reviewdetailsclick" target="_blank" ><i data-toggle="tooltip" data-placement="top" class="plus-icons i-Eye" data-original-title="View Details" ></i></a>';
              return $btn;
            }) 
        ->rawColumns(['details'])            
            ->make(true); 
    }

    public function getOrderDetails($id)
    {
      $id =sanitizeVariable($id);
      $data=Device_Order::self($id);
     $order_data = (Device_Order::self($id) ? Device_Order::self($id)->population() : "");  
     $statecode=$data->pstate; 
     $respstatecode=$data->respstate; 
     $shippingstate=$data->shippingstate; 
     $billingstate=$data->billingstate; 
    
      $devicedata=json_decode($data->devices);
      //dd($devicedata);
      for($i=0;$i<count($devicedata);$i++)
      {        
        $devicenm=$devicedata[$i]->type;
        $deviceprtner=Partner_Devices::where('device_name_api',$devicenm)->with('device')->get();  
       
        if (array_key_exists("option", $devicedata[$i])) {
               $deviceoptn="(".$devicedata[$i]->option.")";              
            } 
            else
            {
              $deviceoptn="";
            }
             $devicename[]= $deviceprtner[0]->device['device_name']."".$deviceoptn;
        
      }
      $finaldevicename = implode (", ", $devicename);
      $order_data['static']['devicename']=$finaldevicename;  
     
     if(!empty($statecode))
     {
       foreach(config("form.states") as $name => $label)
       {
          if($name == $statecode)
          {           
            $order_data['static']['statename']=$label;            
          }
           if($name == $respstatecode)
          {           
            $order_data['static']['respstate']=$label;            
          }
           if($name == $shippingstate)
          {           
            $order_data['static']['shippingstate']=$label;            
          }
          if($name == $billingstate)
          {           
            $order_data['static']['billingstate']=$label;            
          }
        }               
     }    
    $result['order_details'] = $order_data;
   
    return $result;
    }

     // created by radha (25/03/2021) render device order from given API based on group name (RenovaHealthLLC)
     public function renderDeviceOrderDetailsFromAPI()
    {
        $ecgcredetials=ApiECGCredeintials(); 
       ECGAPIController::getAuthorization();
         $groupname= $ecgcredetials[0]->group_name; 
        $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL,  $ecgcredetials[0]->url.'groups/'.$groupname.'/orders');  
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
              curl_setopt($ch, CURLOPT_HTTPHEADER, [
               "Content-Type: application/json",
                "Authorization: Bearer ".session()->get('TokenId')]
             );               
               // Send the request & save response to $resp
               
             $response =  curl_exec($ch);
             $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
             curl_close($ch);             
             $arraydata=json_decode($response); 
             $data=[];           
             if (array_key_exists("items", $arraydata)) {
                 $data=$arraydata->items;
              }
          
        return Datatables::of($data)
            ->addIndexColumn()              
            ->make(true); 
    }

    //for address location created by ashvini (01/04/2021)
    public function getAddresslocationFromOrderDetails()
    {  
      $ecgcredetials=ApiECGCredeintials();
      ECGAPIController::getAuthorization();
        $groupname=  $ecgcredetials[0]->group_name; 
        $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $ecgcredetials[0]->url.'groups/'.$groupname.'/orders');  
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
              curl_setopt($ch, CURLOPT_HTTPHEADER, [
               "Content-Type: application/json",
                "Authorization: Bearer ".session()->get('TokenId')]
             );               
               // Send the request & save response to $resp
            // dd("test");  
             $response =  curl_exec($ch);
             $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if($errno = curl_errno($ch)) {
                  $error_message = curl_strerror($errno);
                  return "cURL error ({$errno}):\n {$error_message}";
              }
             curl_close($ch);             
             $arraydata=json_decode($response); 
             $data1="";
             if(!empty($arraydata->items))
             {
              $data1=$arraydata->items;
             }
             

              $devices=Partner_Devices::where('status','1')->with('device')->orderBy('device_order_seq')->get();
             
            return view('Rpm::patient.device-order',compact('data1','devices'));     
    }

    // created by ashvini (5/04/2021) 
    public function renderObservationsListFromTable()
    {
       $webhookdata = \DB::table('staging.webhook')->get();
                
        return Datatables::of($webhookdata)
            ->addIndexColumn()              
            ->make(true);   
    }
      //For order place created by Radha (24/03/2021)   DeviceOrderPlaceRequest 
    public function SavePlaceOrder(DeviceOrderPlaceRequest $request)
    {        
      // dd($request->all());
        $patient_id=sanitizeVariable($request->patient_id);
        $patient_fname = sanitizeVariable($request->fname);
        $patient_lname = sanitizeVariable($request->lname);
        $patient_dob = sanitizeVariable($request->dob);
        $patient_phone = sanitizeVariable(preg_replace("/[^0-9]/", "", $request->mob));
        $patient_address = sanitizeVariable($request->add_1);
        $patient_email = sanitizeVariable($request->email);
        $patient_city = sanitizeVariable($request->city);
        $patient_zip = sanitizeVariable($request->zipcode);
        $patient_state = sanitizeVariable($request->state);
        $patient_gender = sanitizeVariable($request->Gender);
        $systemid = sanitizeVariable($request->device_code);  
        $partnerid = sanitizeVariable($request->partnerid);
        $created_by  = session()->get('userid');
        $updated_by  = session()->get('userid');


        // $table        = explode(",",sanitizeVariable($request->table));
        $formname     = explode(",",sanitizeVariable($request->form_name));
        // $formname1     = sanitizeVariable($request->form_name);

        $time         = RPMBilling::get();
        $nettime      = $time[0]->vital_review_time;
        $module_id    = getPageModuleName();
        $component_id = sanitizeVariable($request->cid);
        $componentid  = (int)$component_id;
        $uid = session()->get('userid');
        $usersdetails = Users::where('id',$uid)->get();
        $roleid       = $usersdetails[0]->role; 
        $billable     = 1;
        $start_time   = sanitizeVariable($request->hd_timer_start);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = sanitizeVariable($request->step_id);

        // dd($componentid);

        $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patient_id, $module_id,
                                     $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);

      //  dd($formname[0] , $formname1  ) ;                             
        


       // dd( $patient_phone); 
        if ($patient_gender == '0')
        {
            $patient_gender = 'Male';
        }
        else
        {
            $patient_gender = 'Female';
        }
         if ($patient_email == "")
        {
            $patient_email = 'hco_support@ecg-hq.com';
        }
        $patient_practice_id = sanitizeVariable($request->practiceid);
        $patient_provider_id = sanitizeVariable($request->provider_id);
        if($patient_provider_id=="")
        {
            $patient_provider_id="provider";
        }
        $family_fname = sanitizeVariable($request->family_fname);
        $family_lname = sanitizeVariable($request->family_lname);
        $family_mob = sanitizeVariable(preg_replace("/[^0-9]/", "", $request->family_mob));
        $phone_type = sanitizeVariable($request->phone_type);
        $family_add = sanitizeVariable($request->family_add);
        $respstate = sanitizeVariable($request->respstate);
        $respcity = sanitizeVariable($request->respcity);
        $respzip = sanitizeVariable($request->respzip);
        $email = $patient_email;
        $Relationship = sanitizeVariable($request->Relationship);
        $emr_no = sanitizeVariable($request->practice_emr);
        $ordertype = sanitizeVariable($request->ordertype);
        $device_type = sanitizeVariable($request->device_type); 
        
        // dd($device_type);
      
        



        $device_size = sanitizeVariable($request->size);
        $device_weight = sanitizeVariable($request->weight);
        $medtime= sanitizeVariable($request->time);
        $msg=sanitizeVariable($request->message);
        $meddata=array();
        $med_reminder="";

         $shipping_fname=sanitizeVariable($request->shipping_fname);
         $shipping_lname=sanitizeVariable($request->shipping_lname);
         $shipping_mob=sanitizeVariable(preg_replace("/[^0-9]/", "", $request->shipping_mob));
         $shipping_add=sanitizeVariable($request->shipping_add);
         $shipping_email=sanitizeVariable($request->shipping_email);
         $shipping_city=sanitizeVariable($request->shipping_city);
         $shipping_state=sanitizeVariable($request->shipping_state);
         $shipping_zipcode=sanitizeVariable($request->shipping_zipcode);
         $shipping_option = sanitizeVariable($request->shipping_option);
                
           $shippingarray=array("first_name"=> $shipping_fname,
                 "last_name"=> $shipping_lname,
                 "phone"=> $shipping_mob,
                 "address"=> $shipping_add,
                 "city"=> $shipping_city,
                 "zip"=> $shipping_zipcode,
                 "state"=> $shipping_state,
                // "email"=> $shipping_email,
                 "option"=> $shipping_option);  

          if($shipping_email !='' || $shipping_email != null)
          {            
              $shippingarray['email']=$shipping_email;       
          }  
                  
        $shippingdata=json_encode($shippingarray);
    
        for($i=0;$i<count($medtime);$i++)
        {   
            if($medtime[$i] != "")   
            {
            $timeconvert= date("h:i A", strtotime($medtime[$i]));   
            $strtime= explode(" ",$timeconvert);
           $meddata[]=array("time"=>$strtime[0],"am_pm"=>$strtime[1],"message"=>$msg[$i]);
           }
        }
        $medfinaldata= json_encode($meddata); 
        if($medfinaldata!="[]")
        { 
        $med_reminder=', "med_reminder" :'.$medfinaldata;
        }
        $officedataloc = OfficeMst::where('name', 'Renova')->first();
        $officedata = RPMBilling::where('status','1')->first();
        //dd($officedata->billing_fname );
        
        if (!empty($officedata))
        { 
            $billing_fname =isset($officedata->billing_fname) ? $officedata->billing_fname : null;
            $billing_lname =isset($officedata->billing_lname) ? $officedata->billing_lname : null; 
            $billing_mob = isset($officedata->billing_lname) ? preg_replace("/[^0-9]/", "", $officedata->billing_phone) : null;
            $billing_add = isset($officedata->billing_address) ? $officedata->billing_address : null;
            $billing_email = isset($officedata->billing_email) ? $officedata->billing_email : null;
            $billing_city = isset($officedata->billing_city) ? $officedata->billing_city : null;
            $billing_state =isset($officedata->billing_state) ? $officedata->billing_state : null; 
            $billing_zipcode =isset($officedata->billing_zip) ? $officedata->billing_zip : null; 
            $office_loc= isset($officedataloc->office_id) ? $officedataloc->office_id : null;


             $billingarray=array("first_name"=> $billing_fname,
                 "last_name"=> $billing_lname,
                 "phone"=> $billing_mob,
                 "address"=> $billing_add,
                 "city"=> $billing_city,
                 "zip"=> $billing_zipcode,
                 "state"=> $billing_state,                
                 "option"=> $shipping_option);  

          if($billing_email !='' || $billing_email!=null)
          {            
              $billingarray['email']=$billing_email;            
          }           
            $billingdata=json_encode($billingarray);            
            $dobreplace = str_replace('-', '/', $patient_dob);
            $newdob = date("d/m/Y", strtotime($dobreplace));          
            $devicejson = array();
            $devieattr=array();
            $deviesize=array();
            $devieweight=array();
            $vitalsdevice=array();
            $vitaldeviceid=array();
            $val = array_filter($device_type);
            foreach ($val as $key => $value)
            {
              $devicesplit1=explode("-", $key);
                $devicejson[] = array(
                    "type" => $devicesplit1[0]
                );
                array_push($vitaldeviceid,$devicesplit1[1]);
                $vitalsdevice[]=array('vid'=>$devicesplit1[1],'pid'=>$devicesplit1[3],'pdid'=>$devicesplit1[2]);
                foreach ($devicejson as $keydevice => $valuedevice)
                {
                  $devicesplit=explode("-", $valuedevice['type']);
                    if ($devicesplit[0] == 'ECG_PROHEALTH_BP')
                    {
                        $sizejson = array(
                            "option" => $device_size
                        );
                        $devicejson[$keydevice]['option'] = $device_size;
                        $deviesize=["size"=>$device_size];
                        //dd($deviesize);
                    }
                    if ($devicesplit[0] == 'ECG_PROHEALTH_WEIGHT')
                    {
                        $sizejson = array(
                            "option" => $device_weight
                        );
                        $devicejson[$keydevice]['option'] = $device_weight;
                        $devieweight=["weight"=>$device_weight];

                    }
                     $devieattr=array($deviesize, $devieweight);                   
                }
            }
            $deviceid=json_encode($vitaldeviceid);
            $devicedata = json_encode($devicejson);
           $deviceattrdata=json_encode($devieattr);
           $vitalsdevicedata=json_encode($vitalsdevice);
           
          $responsiblepartydata=array("first_name"=>$family_fname,"last_name"=>$family_lname,"phone"=>$family_mob,"email"=>$email,"phone_type"=>$phone_type,"relationship"=>$Relationship);
            
             if($family_add  !="")
             {               
              $addr=array("address"=>$family_add);  
              $d[]= array_merge($responsiblepartydata, $addr);               
             }
             else{
              $d[]= $responsiblepartydata;
             }
              if($respzip!="")
              {
                 $reszip=array("zip"=>$respzip);  
                $d[]= array_merge($responsiblepartydata, $reszip); 
              }
              else{
              $d[]= $responsiblepartydata;
             }
              if($respstate!="")
              {
                 $resstate=array("state"=>$respstate);  
                $d[]= array_merge($responsiblepartydata, $resstate); 
              }
              else{
              $d[]= $responsiblepartydata;
             }
              if($respcity!="")
              {
                 $rescity=array("city"=>$respcity);  
                $d[]= array_merge($responsiblepartydata, $rescity); 
              }
             else
             {
              $d[]= $responsiblepartydata;
             }
             
            
             $rpdata=json_encode($d);

            $medical=array("date_of_birth"=>$newdob,"gender"=>$patient_gender,"office_location"=>$office_loc,"provider"=>$patient_provider_id);
            if($emr_no!="")
            {
              $medical['mrn']=$emr_no;
            }

            $mrnnojson=json_encode($medical);
            $data = '{
               "system_type": "ECG_PROHEALTH_PACKAGE",
               "sytem_id": "'.$systemid.'",
               "devices":' . $devicedata . ', 
               "group_code": "' . $ordertype . '",
               "action_plan": "9904",
               "user_id": "LSM55188",              
               "customer": {
                 "first_name": "' . $patient_fname . '",
                 "last_name": "' . $patient_lname . '",
                 "phone": "' . $patient_phone . '",
                 "address": "' . $patient_address . '",
                 "city": "' . $patient_city . '",
                 "zip": "' . $patient_zip . '",
                 "state": "' . $patient_state . '",
                 "email": "' . $patient_email . '"
               },
               "billing":'.$billingdata.',
               "shipping":'.$shippingdata.',
               "medical":'.$mrnnojson.',
               "responsible_party": '.$rpdata.'
               '.$med_reminder.'
             }';
              //dd($data);
            $ecgcredetials=ApiECGCredeintials(); 
            ECGAPIController::getAuthorization();
            if($partnerid == 1){

              $url = $ecgcredetials[0]->url;
            }        
            $ch = curl_init();            
            curl_setopt($ch, CURLOPT_URL, $url.'orders');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "Authorization: Bearer " . session()->get('TokenId') ]);

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            // Send the request & save response to $resp
            $response = curl_exec($ch);
            $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          
               
            $sourceid = '';
             curl_close($ch);          
            if ($resultStatus == 200)
            {               
                $getresponse = json_decode($response);
                $sourceid = $getresponse->sourceId;
                  $insertdata = array(
                'devices' => $devicedata,
                'group_code' => $ordertype,
                'action_plan' => '9904',
                'userid' => 'LSM55188',
                'pfname' => $patient_fname,
                'plastname' => $patient_lname,
                'pphone' => $patient_phone,
                'paddress' => $patient_address,
                'pcity' => $patient_city,
                'pzip' => $patient_zip,
                'pstate' => $patient_state,
                'pemail' => $patient_email,
                'billingname' => $billing_fname,
                'billinglastname' => $billing_lname,
                'billingphone' => $billing_mob,
                'billingaddress' => $billing_add,
                'billingcustcity' => $billing_city,
                'billingstate' => $billing_state,
                'billingzip' => $billing_zipcode,
                'billingemail' => $billing_email,
                'shippingname' => $shipping_fname,
                'shippinglastname' => $shipping_lname,
                'shippingphone' => $shipping_mob,
                'shippingaddress' => $shipping_add,
                'shippingcustcity' => $shipping_city,
                'shippingzip' => $shipping_zipcode,
                'shippingstate' => $shipping_state,
                'shippingemail' => $shipping_email,
                'shippingoption' => $shipping_option,
                'dob' => $patient_dob,
                'doctor_name' => $patient_provider_id,
                'gender' => $patient_gender,
                'mrn' => $emr_no,
                'office_loc' => $patient_practice_id,
                'provider' => $patient_provider_id,
                'respname' => $family_fname,
                'resplastname' => $family_lname,
                'respphone' => $family_mob,
                'respaddress' => $family_add,
                'respcustcity' => $respcity,
                'respzip' => $respzip,
                'respstate' => $respstate,
                'respemail' => $email,
                'relationship' => $Relationship,
                'sourceid' => $sourceid,
                'med_reminder'=>$medfinaldata,
                'system_id'=>$systemid,
                'fetch_status'=>'0',
                'practice_id'=>$patient_practice_id,
                'provider_id'=>$patient_provider_id, 
                'created_by' => session()->get('userid'),
                'device_id'=>$deviceid
            );
                  
                  Device_Order::create($insertdata);
                  $patientdevicedata=array(
                   'patient_id'=>$patient_id,
                   'partner_device_id'=>1,                   
                   'status' =>'0',                  
                   'device_attr'=>$deviceattrdata,
                   'created_by'=>session()->get('userid'),
                   'vital_devices' =>$vitalsdevicedata,
                   'source_id'=>$sourceid
                  );

                  PatientDevices::create($patientdevicedata);

                  
                  $newdevice_type = array_unique($device_type);                 
                  foreach($newdevice_type  as $key=>$value)
                  {
          
                    // dd($key);
                    $device_name_api_array =  (explode("-",$key));          
                    $partnerdevices_array = array(
                      'partner_id'=>$partnerid,
                      'device_name_api'=>$device_name_api_array[0],
                      'device_id'=>$device_name_api_array[1],
                      'device_attr'=>null,
                      'status'=>1,     
                      'created_by'=>$updated_by,
                      'updated_by'=>$updated_by,
                      'device_order_seq'=>1
                      
                  );
          
                  $insert_partnerdevices = PartnerDevices::create($partnerdevices_array); 
          
                 }






                return json_encode($response);
                //Insert into Time Record Table -----Priya 26th nov 21
                  $start_time   = 0;
                  $nettime      = 0;
                  $form_name    = sanitizeVariable($request->form_name);
                  $patientid    = 0;
                  $billable     = 1;
                  $module_id    = sanitizeVariable($request->mid);
                  $componentid  = sanitizeVariable($request->cid);
                  $stage_id     = sanitizeVariable($request->stage_id);
                  $step_id      = sanitizeVariable($request->step_id);
                  $record_time  = CommonFunctionController::recordTimeSpent(
                  $start_time, $nettime, $patientid, $module_id,$componentid,  $stage_id, $billable, $patientid, $step_id,$form_name);
            }
            else
            {
              return $response;
            }          
         
        }
        else
        {
            return "exist";
        }

    }



     //For order place created by Radha (24/03/2021)   DeviceOrderPlaceRequest  
     public function SavePlaceOrderold(DeviceOrderPlaceRequest $request)
    {        

    
        $patient_id=sanitizeVariable($request->patient_id);
        $patient_fname = sanitizeVariable($request->fname);
        $patient_lname = sanitizeVariable($request->lname);
        $patient_dob = sanitizeVariable($request->dob);
        $patient_phone = sanitizeVariable(preg_replace("/[^0-9]/", "", $request->mob));
        $patient_address = sanitizeVariable($request->add_1);
        $patient_email = sanitizeVariable($request->email);
        $patient_city = sanitizeVariable($request->city);
        $patient_zip = sanitizeVariable($request->zipcode);
        $patient_state = sanitizeVariable($request->state);
        $patient_gender = sanitizeVariable($request->Gender);
        $systemid = sanitizeVariable($request->device_code);
       
        if ($patient_gender == '0')
        {
            $patient_gender = 'Male';
        }
        else
        {
            $patient_gender = 'Female';
        }
         if ($patient_email == "")
        {
            $patient_email = 'hco_support@ecg-hq.com';
        }
        
        $patient_practice_id = sanitizeVariable($request->practiceid);
        $patient_provider_id = sanitizeVariable($request->provider_id);
        if($patient_provider_id=="")
        {
            $patient_provider_id="provider";
        }
        $family_fname = sanitizeVariable($request->family_fname);
        $family_lname = sanitizeVariable($request->family_lname);
        $family_mob = sanitizeVariable(preg_replace("/[^0-9]/", "", $request->family_mob));
        $phone_type = sanitizeVariable($request->phone_type);
        $family_add = sanitizeVariable($request->family_add);
        $email = sanitizeVariable($request->email);
        $Relationship = sanitizeVariable($request->Relationship);

        $emr_no = sanitizeVariable($request->practice_emr);
        $ordertype = sanitizeVariable($request->ordertype);
        $device_type = sanitizeVariable($request->device_type);

  

        $device_size = sanitizeVariable($request->size);
        $device_weight = sanitizeVariable($request->weight);
        $medtime= sanitizeVariable($request->time);
        $msg=sanitizeVariable($request->message);
        $meddata=array();
        $med_reminder="";

        //dd($device_size);
         
        for($i=0;$i<count($medtime);$i++)
        {   
            if($medtime[$i] != "")   
            {
            $timeconvert= date("h:i A", strtotime($medtime[$i]));   
            $strtime= explode(" ",$timeconvert);
           $meddata[]=array("time"=>$strtime[0],"am_pm"=>$strtime[1],"message"=>$msg[$i]);
           }
        }
        $medfinaldata= json_encode($meddata); 
        if($medfinaldata!="[]")
        { 
        $med_reminder=', "med_reminder" :'.$medfinaldata;
        }
     
        $officedata = OfficeMst::where('name', 'Renova')->first();

        if(!empty($officedata))
        {
            $billing_fname = $officedata->billing_fname;
            $billing_lname = $officedata->billing_lname;
            $billing_mob = str_replace('.', '', $officedata->billing_phone);
            $billing_add = $officedata->billing_address;
            $billing_email = $officedata->billing_email;
            $billing_city = $officedata->billing_city;
            $billing_state = $officedata->billing_state;
            $billing_zipcode = $officedata->billing_zip;
            $office_loc= $officedata->office_id;

            $shipping_option = sanitizeVariable($request->shipping_option);

            $dobreplace = str_replace('-', '/', $patient_dob);
            $newdob = date("d/m/Y", strtotime($dobreplace));
          
            $devicejson = array();
            $devieattr=array();
            $deviesize=array();
            $devieweight=array();
            $vitalsdevice=array();
            $val = array_filter($device_type);
            foreach ($val as $key => $value)
            {
              $devicesplit1=explode("-", $key);
                $devicejson[] = array(
                    "type" => $devicesplit1[0]
                );
                $vitalsdevice[]=array('vid'=>$devicesplit1[1],'pid'=>$devicesplit1[3],'pdid'=>$devicesplit1[2]);
                foreach ($devicejson as $keydevice => $valuedevice)
                {
                  $devicesplit=explode("-", $valuedevice['type']);
                    if ($devicesplit[0] == 'ECG_PROHEALTH_BP')
                    {
                        $sizejson = array(
                            "option" => $device_size
                        );
                        $devicejson[$keydevice]['option'] = $device_size;
                        $deviesize=["size"=>$device_size];
                        //dd($deviesize);
                    }
                    if ($devicesplit[0] == 'ECG_PROHEALTH_WEIGHT')
                    {
                        $sizejson = array(
                            "option" => $device_weight
                        );
                        $devicejson[$keydevice]['option'] = $device_weight;
                        $devieweight=["weight"=>$device_weight];

                    }
                     $devieattr=array($deviesize, $devieweight);
                   
                }
            }
            $devicedata = json_encode($devicejson);
           $deviceattrdata=json_encode($devieattr);
           $vitalsdevicedata=json_encode($vitalsdevice);
           
             $responsiblepartydata=array("first_name"=>$family_fname,"last_name"=>$family_lname,"phone"=>$family_mob,"email"=>$email,"phone_type"=>$phone_type,"relationship"=>$Relationship);

             if($family_add  !="")
             {               
              $addr=array("address"=>$family_add);  
              $d[]= array_merge($responsiblepartydata, $addr);               
             }
            
             $rpdata=json_encode($d);
           
            $medical=array("date_of_birth"=>$newdob,"gender"=>$patient_gender,"office_location"=>$office_loc,"provider"=>$patient_provider_id);
            if($emr_no!="")
            {
              $medical['mrn']=$emr_no;
            }

            $mrnnojson=json_encode($medical);
            
           // dd($devicedata);

            $data = '{
               "system_type": "ECG_PROHEALTH_PACKAGE",
               "sytem_id": "'.$systemid.'",
               "devices":' . $devicedata . ', 
               "group_code": "' . $ordertype . '",
               "action_plan": "9904",
               "user_id": "LSM55188",              
               "customer": {
                 "first_name": "' . $patient_fname . '",
                 "last_name": "' . $patient_lname . '",
                 "phone": "' . $patient_phone . '",
                 "address": "' . $patient_address . '",
                 "city": "' . $patient_city . '",
                 "zip": "' . $patient_zip . '",
                 "state": "' . $patient_state . '",
                 "email": "' . $patient_email . '"
               },
               "billing": {
                 "first_name":"' . $billing_fname . '",
                 "last_name":"' . $billing_lname . '",
                 "phone":"' . $billing_mob . '",
                 "address":"' . $billing_add . '",
                 "city":"' . $billing_city . '",
                 "zip":"' . $billing_zipcode . '",
                 "state":"' . $billing_state . '",
                 "email":"' . $billing_email . '"
               },
               "shipping": {
                 "first_name":"' . $shipping_fname . '",
                 "last_name":"' . $shipping_lname . '",
                 "phone":"' . $shipping_mob . '",
                 "address":"' . $shipping_add . '",
                 "city":"' . $shipping_city . '",
                 "zip":"' . $shipping_zipcode . '",
                 "state":"' . $shipping_state . '",
                 "email":"' . $shipping_email . '",
                 "option":"' . $shipping_option . '"
               },
               "medical":'.$mrnnojson.',
               "responsible_party": '.$rpdata.',
               '.$med_reminder.'
             }';

           //  dd($data);
               $ecgcredetials=ApiECGCredeintials(); 
            ECGAPIController::getAuthorization();
                       
            $ch = curl_init();
     
            curl_setopt($ch, CURLOPT_URL, $ecgcredetials[0]->url.'orders');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "Authorization: Bearer " . session()->get('TokenId') ]);

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            // Send the request & save response to $resp
            $response = curl_exec($ch);
            $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          
               
            $sourceid = '';
             curl_close($ch);
           //dd($resultStatus);
            if ($resultStatus == 200)
            {
               // dd($devicedata);
                $getresponse = json_decode($response);
                $sourceid = $getresponse->sourceId;

                  $insertdata = array(
                'devices' => $devicedata,
                'group_code' => $ordertype,
                'action_plan' => '9904',
                'userid' => 'LSM55188',
                'pfname' => $patient_fname,
                'plastname' => $patient_lname,
                'pphone' => $patient_phone,
                'paddress' => $patient_address,
                'pcity' => $patient_city,
                'pzip' => $patient_zip,
                'pstate' => $patient_state,
                'pemail' => $patient_email,
                'billingname' => $billing_fname,
                'billinglastname' => $billing_lname,
                'billingphone' => $billing_mob,
                'billingaddress' => $billing_add,
                'billingcustcity' => $billing_city,
                'billingstate' => $billing_state,
                'billingzip' => $billing_zipcode,
                'billingemail' => $billing_email,
                'shippingname' => $billing_fname,
                'shippinglastname' => $billing_lname,
                'shippingphone' => $billing_mob,
                'shippingaddress' => $billing_add,
                'shippingcustcity' => $billing_city,
                'shippingzip' => $billing_zipcode,
                'shippingstate' => $billing_state,
                'shippingemail' => $billing_email,
                'shippingoption' => $shipping_option,
                'dob' => $patient_dob,
                'doctor_name' => $patient_provider_id,
                'gender' => $patient_gender,
                'mrn' => $emr_no,
                'office_loc' => $patient_practice_id,
                'provider' => $patient_provider_id,
                'respname' => $family_fname,
                'resplastname' => $family_lname,
                'respphone' => $family_mob,
                'respaddress' => $family_add,
                'respcustcity' => $patient_city,
                'respzip' => $patient_zip,
                'respstate' => $patient_state,
                'respemail' => $email,
                'relationship' => $Relationship,
                'sourceid' => $sourceid,
                'med_reminder'=>$medfinaldata,
                'system_id'=>$systemid,
                'fetch_status'=>'0',
                'practice_id'=>$patient_practice_id,
                'provider_id'=>$patient_provider_id, 
                'created_by' => session()->get('userid')
            );
                  

                  Device_Order::create($insertdata);


                  $patientdevicedata=array(
                   'patient_id'=>$patient_id,
                   'partner_device_id'=>1,                   
                   'status' =>'0',                  
                   'device_attr'=>$deviceattrdata,
                   'created_by'=>session()->get('userid'),
                   'vital_devices' =>$vitalsdevicedata,
                   'source_id'=>$sourceid
                  );

                  PatientDevices::create($patientdevicedata);

                  return json_encode($response);
            }
            else
            {
              return $response;
            }          
          
        }
        else
        {
            return response()->json("Billing details not found!");
        }

    }


      //created by radha(21june2021) -get placed order details
    public function getPlaceOrderDetails()
    {  
      $PatientDevicedata=Device_Order::where('fetch_status','0')->get();
      $count=count($PatientDevicedata);     
      if($count>0)   
      {   
      for($i=0;$i<$count;$i++)
      {
         $sourceid=isset($PatientDevicedata[$i]->sourceid)?$PatientDevicedata[$i]->sourceid:'';
        if($sourceid!='')
        {

      ECGAPIController::getAuthorization();
       $ecgcredetials=ApiECGCredeintials(); 
       $groupname= $ecgcredetials[0]->group_name;   
        $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $ecgcredetials[0]->url.'groups/'.$groupname.'/orders/'.$sourceid);  
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
              curl_setopt($ch, CURLOPT_HTTPHEADER, [
               "Content-Type: application/json",
                "Authorization: Bearer ".session()->get('TokenId')]
             );               
               // Send the request & save response to $resp           
             $response =  curl_exec($ch);
             $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if($errno = curl_errno($ch)) {
                  $error_message = curl_strerror($errno);
                  return "cURL error ({$errno}):\n {$error_message}";
              }
             curl_close($ch);             
             $arraydata=json_decode($response); 
              if(array_key_exists("order_id",$arraydata))  
               {  
                 $activatndate=$arraydata->active_date;
                  
                  $DOdata=array(
                        'order_id'=>$arraydata->order_id,
                        'device_code'=>$arraydata->device_id,
                        'device_status'=>isset($arraydata->device_status)?$arraydata->order_status:'',
                        'order_date'=>$arraydata->order_date,
                        'active_date'=>date('Y-m-d h:i:s', strtotime($activatndate)),      
                       'office_provider'=>isset($arraydata->office_provider)?$arraydata->office_provider:'',
                        'office_id'=>isset($arraydata->office_id)?$arraydata->office_id:'',
                        'tracking_num'=>isset($arraydata->tracking_num)?$arraydata->tracking_num:'',
                        'date_shipped'=>date('Y-m-d 00:00:00', strtotime($arraydata->date_shipped)),
                        'fetch_status'=>'1'
                  );

                  $deviceorderdata=Device_Order::where('sourceid',$arraydata->source_id)->update($DOdata);

                  $PDdata=array('device_code'=>$arraydata->device_id,
                                'activation_date'=>date('Y-m-d h:i:s', strtotime($activatndate)),
                                'order_id'=>$arraydata->order_id                               
                      );      
                      //dd($PDdata);         
                 $PatientDevicedata=PatientDevices::where('source_id',$arraydata->source_id)->update($PDdata);
               }   
             }
             else
             {
              return "Source Id doesn't exist!";
             }
           }
         }
           else
             {
              return "No data found!";
             }
   }

}