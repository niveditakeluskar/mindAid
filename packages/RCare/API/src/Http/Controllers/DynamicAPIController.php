<?php
namespace RCare\API\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RCare\Patients\Models\PatientOrders;
use RCare\Patients\Models\PatientReading;
use RCare\Org\OrgPackages\Threshold\src\Models\GroupThreshold;
use RCare\Org\OrgPackages\Practices\src\Models\OrgThreshold;
use RCare\Patients\Models\Patients;
// use RCare\Patients\Models\Practices;
use RCare\Org\OrgPackages\Practices\src\Models\Practices;
use RCare\Patients\Models\PatientDevices;
use RCare\Patients\Models\PatientAlert;
use RCare\Rpm\Models\Device_Order;
use RCare\Rpm\Models\Observation_BP;
use RCare\Rpm\Models\Observation_Oxymeter;  
use RCare\Rpm\Models\Observation_Heartrate;  
use RCare\Rpm\Models\Observation_Weight;
use RCare\Rpm\Models\Observation_Glucose;
use RCare\Rpm\Models\Observation_Spirometer;
use RCare\API\Models\Partner;
use RCare\API\Http\Requests\AddPartnerRequest;
use RCare\API\Models\Webhook;
use RCare\API\Models\WebhookAlert;
use RCare\API\Models\AlertLog;
use RCare\API\Models\WebhookObservation;
use RCare\API\Models\WebhookOrders;
use RCare\API\Models\ObservationLog; 
use RCare\API\Models\OrderLog; 
use RCare\API\Models\ApiException; 
use RCare\API\Models\OfficeMst; 
use RCare\Rpm\Models\Devices;
use RCare\Rpm\Models\testschedular;
use RCare\Org\OrgPackages\Practices\src\Models\PracticeThreshold; 
use RCare\Patients\Models\PatientThreshold;    
use RCare\Patients\Models\PatientProvider;
use Session;
use Hash;
use Validator,Redirect,Response;
use DataTables;
use Illuminate\Support\Facades\Log; 
use Carbon\Carbon;
use File,DB;
use Illuminate\Support\Str;
use Config;
use Exception;
use RCare\API\Models\ApiErrorLog;
use RCare\Patients\Models\PatientServices;


class DynamicAPIController extends Controller {
   
   //for testing purpose session in schedular
     public function testcurlfunction()
    {
      Session::put('testname', 'testsession');
      testschedular::create(['name'=>session()->get('testname')]);
    }

  
  public static function getAuthorization()
  {
    
    $ecgcredetials=ApiECGCredeintials(); 
    $partner_id = $ecgcredetials[0]->partner_id;
    $data='{
            "username": "'.$ecgcredetials[0]->username.'",
            "password": "'.$ecgcredetials[0]->password.'"
          }';
   
    // $data=json_encode(Config::get('global.ECGCredential')); 
        $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $ecgcredetials[0]->url.'auth');
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
              curl_setopt($ch, CURLOPT_HTTPHEADER, [
               "Content-Type: application/json" ]             
             );

               curl_setopt($ch, CURLOPT_POST, 1);
               
               curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
              
                $response =  curl_exec($ch);
               
              //  dd( $response);

                 $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                 if($errno = curl_errno($ch)) {
                  $error_message = curl_strerror($errno);
                  return "cURL error ({$errno}):\n {$error_message}";
                  die();
              }

              if($resultStatus==200)
              {
                 $reftoken=json_decode($response);
                 $tokenid=$reftoken->IdToken;      
                 $refreshtoken=$reftoken->RefreshToken;
                 Session::put('TokenId', $tokenid);   
                 Session::put('RefreshToken', $refreshtoken);    

                 return json_encode("Authorized successfully");                         
              }
              else
              {
                // return $response;
                $reftoken=json_decode($response); 
                $message = $reftoken->message;
                $a = array('partner_id'=> $partner_id,
                            'message'=> $message,
                            'status'=>0,
                            'api_url'=>$ecgcredetials[0]->url.'auth'
                          );
                ApiErrorLog::create($a);



              }
               curl_close($ch);
                       
  }


   public static function RefreshToken()
   {    
          $ecgcredetials=ApiECGCredeintials(); 
        $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $ecgcredetials[0]->url.'refresh');         
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
              curl_setopt($ch, CURLOPT_HTTPHEADER, [
               "Content-Type: application/json",
                "refreshtoken:".session()->get('RefreshToken')]
             );
               
             $response = curl_exec($ch);
             $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
             if($resultStatus==200)
             {
                $reftoken=json_decode($response);
                 $tokenid=$reftoken->IdToken;  
                 Session::put('TokenId', $tokenid); 
             }
             else
             {
               return  $response;
             }   
              curl_close($ch);            
   }

 //not in use
  public function getPatientDetails(Request $request)
  {
    $patient_id=$request->route('patientid');
    $patientdata=Patients::getpatient($patient_id)->population();
    $patientdemo        = PatientDemographics::where('patient_id',$patientId)->first();
    $patientAddresss    = PatientAddress::where('patient_id',$patientId)->first();
     $result['device_order_form']=$patientdata;
    
     return $result;
  }

   public function SavePlaceOrder(Request $request)
   {   
       $patient_fname=sanitizeVariable($request->fname);
       $patient_lname=sanitizeVariable($request->lname);
       $patient_dob=sanitizeVariable($request->dob);
       $patient_phone=sanitizeVariable(preg_replace("/[^0-9]/", "", $request->mob));
       $patient_address=sanitizeVariable($request->add_1);
       $patient_email=sanitizeVariable($request->email);
       $patient_city=sanitizeVariable($request->city);
       $patient_zip=sanitizeVariable($request->zipcode);
       $patient_state=sanitizeVariable($request->state);
       $patient_gender=sanitizeVariable($request->Gender);
       if($patient_gender=='0')
       {
          $patient_gender='Male';
       }
       else
       {
          $patient_gender='Female';
       }
       $patient_practice_id=sanitizeVariable($request->practice_id);
       $patient_provider_id=sanitizeVariable($request->provider_id);  

       $family_fname=sanitizeVariable($request->family_fname);
       $family_lname=sanitizeVariable($request->family_lname);
       $family_mob=sanitizeVariable(preg_replace("/[^0-9]/", "", $request->family_mob));
       $phone_type=sanitizeVariable($request->phone_type);
       $family_add=sanitizeVariable($request->family_add);
       $email=sanitizeVariable($request->email);
       $Relationship=sanitizeVariable($request->Relationship);

       $emr_no=sanitizeVariable($request->practice_emr);   
       $device_type=sanitizeVariable($request->device_type); 
       $device_size=sanitizeVariable($request->size);    
       $device_weight=sanitizeVariable($request->weight);
   
       
       $billing=sanitizeVariable($request->billing); 
      
       if($billing==false)
       {
           $billing_fname=sanitizeVariable($request->billing_fname);
           $billing_lname=sanitizeVariable($request->billing_lname);
           $billing_mob=sanitizeVariable(preg_replace("/[^0-9]/", "", $request->billing_mob));
           $billing_add=sanitizeVariable($request->billing_add);
           $billing_email=sanitizeVariable($request->billing_email);
           $billing_city=sanitizeVariable($request->billing_city);
           $billing_state=sanitizeVariable($request->billing_state);
           $billing_zipcode=sanitizeVariable($request->billing_zipcode);
       }
       else
       {
          $billing_fname=$patient_fname;
          $billing_lname=$patient_fname;
          $billing_mob=$patient_phone;
          $billing_add=$patient_address;
          $billing_email=$patient_email;
          $billing_city=$patient_city;
          $billing_state=$patient_state;
          $billing_zipcode=$patient_zip;     
      }
       
       

      $shipping=sanitizeVariable($request->shipping); 
       if($shipping==false)
       {
          $shipping_fname=sanitizeVariable($request->shipping_fname);
          $shipping_lname=sanitizeVariable($request->shipping_lname);
          $shipping_mob=sanitizeVariable(preg_replace("/[^0-9]/", "", $request->shipping_mob));
          $shipping_add=sanitizeVariable($request->shipping_add);
          $shipping_email=sanitizeVariable($request->shipping_email);
          $shipping_city=sanitizeVariable($request->shipping_city);
          $shipping_state=sanitizeVariable($request->shipping_state);
          $shipping_zipcode=sanitizeVariable($request->shipping_zipcode);
          $shipping_option=sanitizeVariable($request->shipping_option);
       }
       else
       {
          $shipping_fname=$patient_fname;
          $shipping_lname=$patient_fname;
          $shipping_mob=$patient_phone;
          $shipping_add=$patient_address;
          $shipping_email=$patient_email;
          $shipping_city=$patient_city;
          $shipping_state=$patient_state;
          $shipping_zipcode=$patient_zip;
          $shipping_option='1 day';
      }

      $dobreplace=str_replace('-', '/', $patient_dob);
      $newdob = date("d/m/Y", strtotime($dobreplace));
     
  $devicejson=array();
$val=array_filter($device_type);
foreach($val as $key=>$value)
{   
      $devicejson[]=array("type"=>$key);
      foreach($devicejson as $keydevice=>$valuedevice)
      {        
        if($valuedevice['type'] == 'ECG_PROHEALTH_BP')
        {
          $sizejson=array("option"=>$device_size);         
          $devicejson[$keydevice]['option'] =  $device_size;         
        }
      }     
  }
  $devicedata=json_encode($devicejson);

  // comments
  //user_id must be in LSM99999 format
            $data='{
               "system_type": "ECG_PROHEALTH_PACKAGE",
               "sytem_id": "ANZ1111",
               "devices":'.$devicedata.', 
               "group_code": "ECG12",
               "action_plan": "1234",
               "user_id": "LSM12345",              
               "customer": {
                 "first_name": "'.$patient_fname.'",
                 "last_name": "'.$patient_lname.'",
                 "phone": "'.$patient_phone.'",
                 "address": "'.$patient_address.'",
                 "city": "'.$patient_city.'",
                 "zip": "'.$patient_zip.'",
                 "state": "'.$patient_state.'",
                 "email": "'.$patient_email.'"
               },
               "billing": {
                 "first_name":"'.$billing_fname.'",
                 "last_name":"'.$billing_lname.'",
                 "phone":"'.$billing_mob.'",
                 "address":"'.$billing_add.'",
                 "city":"'.$billing_city.'",
                 "zip":"'.$billing_zipcode.'",
                 "state":"'.$billing_state.'",
                 "email":"'.$billing_email.'"
               },
               "shipping": {
                 "first_name":"'.$shipping_fname.'",
                 "last_name":"'.$shipping_lname.'",
                 "phone":"'.$shipping_mob.'",
                 "address":"'.$shipping_add.'",
                 "city":"'.$shipping_city.'",
                 "zip":"'.$shipping_zipcode.'",
                 "state":"'.$shipping_state.'",
                 "email":"'.$shipping_email.'",
                 "option":"'.$shipping_option.'"
               },
               "medical": {
                 "date_of_birth": "'.$newdob.'",                 
                 "doctor_name": "'.$patient_provider_id.'",                 
                 "gender": "'.$patient_gender.'",                
                 "office_location": "Emergency Contact",
                 "provider": "provider"                
               },
               "responsible_party": [
                 {
                   "first_name": "'.$family_fname.'",
                   "last_name": "'.$family_lname.'",
                   "phone": "'.$family_mob.'",
                   "address": "'.$family_add.'",                   
                   "email": "'.$email.'",
                   "phone_type": "'.$phone_type.'",
                   "relationship": "Emergency Contact"
                 }
               ],
               "med_reminder": [
                 {
                   "time": "08:00",
                   "am_pm": "AM",
                   "message": "Med Time"
                 }
               ],
               "inactivity": {
                 "start_time": "09:30",
                 "end_time": "10:15"
               }
             }';


              $ecgcredetials=ApiECGCredeintials();  
              $this->getAuthorization();
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $ecgcredetials[0]->url.'orders');
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
              curl_setopt($ch, CURLOPT_HTTPHEADER, [
               "Content-Type: application/json",
                "Authorization: Bearer ".session()->get('TokenId')]
             );


               curl_setopt($ch, CURLOPT_POST, 1);
               
               curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
               
               // Send the request & save response to $resp
               
             $response =  curl_exec($ch);
              $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
             curl_close($ch);
             $sourceid='';
             if($resultStatus==200)
              {                
                 $getresponse=json_decode($response);
                 $sourceid=$getresponse->sourceId; 
              }
             
             

      $insertdata=array('devices' =>$devicedata,      
      'group_code' =>"ECG12",
      'action_plan' =>'1234',
      'userid' =>session()->get('userid'),      
      'custfname' =>$patient_fname,
      'custlastname' =>$patient_lname,
      'custphone' =>$patient_phone,
      'custaddress' =>$patient_address,
      'custcity' =>$patient_city,
      'custzip' =>$patient_zip,
      'custstate' =>$patient_state,
      'custemail' =>$patient_email,
      'bllingname' =>$billing_fname,
      'billinglastname' =>$billing_lname,
      'billingphone' =>$billing_mob,
      'billingaddress' =>$billing_add,
      'billingcustcity' =>$billing_city,
      'billingstate' =>$billing_state,
      'billingzip' =>$billing_zipcode,
      'billingemail' =>$billing_email,
      'shippingname' =>$shipping_fname,
      'shippinglastname' =>$shipping_lname,
      'shippingphone' =>$shipping_mob,
      'shippingaddress' =>$shipping_add,
      'shippingcustcity' =>$shipping_city,
      'shippingzip' =>$shipping_zipcode,
      'shippingstate' =>$shipping_state,
      'shippingemail' =>$shipping_email,
      'shippingoption' =>$shipping_option,     
      'dob' => $patient_dob,      
      'doctor_name' =>$patient_provider_id,      
      'gender' =>$patient_gender,     
      'mrn' =>$emr_no,
      'office_loc' =>$patient_practice_id,
      'provider' =>$patient_provider_id,        
      'respname' =>$family_fname,
      'resplastname' =>$family_lname,
      'respphone' =>$family_mob,
      'respaddress' =>$family_add,
      'respcustcity' =>$patient_city,
      'respzip' =>$patient_zip,
      'respstate' =>$patient_state,
      'respemail' =>$email,
      'relationship'=>$Relationship,
      'sourceid' => $sourceid,   
      'created_by' =>session()->get('userid')  
    );

       Device_Order::create($insertdata);

           return json_encode($response);
       
   }


   //http://rcareproto2.d-insights.global/ecg-place-order
       
  //{"message":"Order submitted successfully.","sourceId":"a0e56e53-5c70-4073-a7b3-71ee60268810"}
   public function PlaceOrder(Request $request){      
       $data='{
               "system_type": "ECG_PROHEALTH_PACKAGE",
               "sytem_id": "ANZ1111",
               "devices": [{
                 "type": "ECG_PROHEALTH_BP",
                 "option": "Small"
               },
               {
                 "type": "ECG_PROHEALTH_WEIGHT",
                 "option": "150kg"
               },
               {
                 "type": "ECG_PROHEALTH_TEMP"
               }
             ],
               "wrist_pendant": "None",
               "neck_pendant": true,
               "group_code": "ECG1",
               "action_plan": "1234",
               "user_id": "LSM12345",
               "user": {
                 "first_name": "Cyrus",
                 "last_name": "Baca",
                 "phone": "5745555555",
                 "address": "123 Main St",
                 "city": "Las Cruces",
                 "zip": "88001",
                 "state": "NM",
                 "email": "cbaca@ecg-hq.com"
               },
               "customer": {
                 "first_name": "Andrew",
                 "last_name": "Baker",
                 "phone": "5745555555",
                 "address": "321 Main st",
                 "city": "Las Cyrus",
                 "zip": "88001",
                 "state": "NM",
                 "email": "abaker@ecg-hq.com"
               },
               "billing": {
                 "first_name": "Juseung",
                 "last_name": "Noel",
                 "phone": "5745555555",
                 "address": "222 Main St",
                 "city": "Las Cruces",
                 "zip": "88005",
                 "state": "NM",
                 "email": "jnoel@ecg-hq.com"
               },
               "shipping": {
                 "first_name": "David",
                 "last_name": "Park",
                 "phone": "5745555555",
                 "address": "231 Main St",
                 "city": "Las Cruces",
                 "zip": "88001",
                 "state": "NM",
                 "email": "dpark@ecg-hq.com",
                 "option": "1 day"
               },
               "medical": {
                 "date_of_birth": "11/11/1950",
                 "time_zone": "Central",
                 "sms_notification": true,
                 "doctor_name": "Dr Michael Evans",
                 "doctor_phone": "5745555555",
                 "preferred_hospital": "MMC",
                 "gender": "MALE",
                 "blood_type": "A+",
                 "mrn": "123456",
                 "office_location": "ECG",
                 "provider": "pro vider",
                 "height_feet": "5",
                 "height_inches": "11",
                 "weight": "185"
               },
               "responsible_party": [
                 {
                   "first_name": "Ivan",
                   "last_name": "Ortiz",
                   "phone": "5745555555",
                   "address": "456 Main St",
                   "city": "Las Cruces",
                   "zip": "88005",
                   "state": "NM",
                   "email": "iortiz@ecg-hq.com",
                   "phone_type": "Home",
                   "relationship": "Uncle"
                 }
               ],
               "med_reminder": [
                 {
                   "time": "08:00",
                   "am_pm": "AM",
                   "message": "Med Time"
                 }
               ],
               "inactivity": {
                 "start_time": "09:30",
                 "end_time": "10:15"
               }
             }';

        $ecgcredetials=ApiECGCredeintials(); 
        $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL,  $ecgcredetials[0]->url.'orders');
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
              curl_setopt($ch, CURLOPT_HTTPHEADER, [
               "Content-Type: application/json",
                "Authorization: Bearer ".session()->get('TokenId')]
             );

               curl_setopt($ch, CURLOPT_POST, 1);
               
               curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
               
               // Send the request & save response to $resp
               
             $response =  curl_exec($ch);
             curl_close($ch);

             return  json_encode($response);
        }



//get alert details by radha
   public function GetDeviceAlertdata(Request $request)
   {

          $getdata= WebhookAlert::where('status',1)->where('fetch_status',0)->get();         
          $d =count($getdata);     
          $this->getAuthorization();
          $ecgcredetials=ApiECGCredeintials(); 
          for($i=0;$i<$d;$i++)
          {

             if(!empty($getdata[$i]->content))
             {
             $datajson=json_decode($getdata[$i]->content, true);
            
              if(isset($datajson['xmit_id']))
              {
               $deviceid=$datajson['xmit_id']; 
            
             $id=$getdata[$i]->id;
             $datajson=json_decode($getdata[$i]->content);
              $deviceid=$datajson->xmit_id;  
              $timestamp=$datajson->timestamp;
               
                $patdata = PatientDevices::where('device_code',$deviceid)->where('status',1)->get();
                 $patientid =0;
                 $partner_device_id =null;
                 $mrn_no = null;
                 if($patdata!="[]")
                 { 
                    $patientid = $patdata[0]->patient_id;      
                    $partner_device_id = $patdata[0]->partner_device_id;
                    $mrn_no = $patdata[0]->mrn_no;
                  }
              
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $ecgcredetials[0]->url.'devices/'.$deviceid.'/alerts/'.$timestamp);  
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
              curl_setopt($ch, CURLOPT_HTTPHEADER, [
               "Content-Type: application/json",
               "Authorization: Bearer ".session()->get('TokenId')]
             );
               
             $response = curl_exec($ch);      
             $jsonresponse=json_encode($response);
           if(!empty($response))
           {
            $getdataresponse=[];
               $getdataresponse=json_decode($response, true);    
                  
           if(array_key_exists('message', $getdataresponse))
             {              
             }
             else
             {     
                $t = isset($getdataresponse["timestamp"]) ?  $getdataresponse["timestamp"] : null;
             $at = isset($getdataresponse["addressedTime"]) ?  $getdataresponse["addressedTime"] : null;
             $rt = isset($getdataresponse["readingTimestamp"]) ?  $getdataresponse["readingTimestamp"] : null;
              
             $addressedTime = date('Y-m-d H:i:s', strtotime($at));
             if($rt=="")
             {
                 $readingTimestamp = null; 
             }
             else
             {
                 $readingTimestamp =  date('Y-m-d H:i:s', strtotime($rt)); 
             }
             if($t=="")
             {
                 $timestamp1 = ""; 
             }
             else
             {
                 $timestamp1 = date('Y-m-d H:i:s', strtotime($t));
             }
            
             $dcode=isset($getdataresponse["xmit_id"])?$getdataresponse["xmit_id"]:null;
              $vtype =isset($getdataresponse["type"])?$getdataresponse["type"]:null; 
              $match_status=0;
              $rcare_alert_status=0;
              $obrvtnid="";
              if($dcode != null && $rt != null)
              {
                 if($vtype=="Diastolic")
                 {
                     $vtype=="Systolic";
                 }
                $checkdataexist =  \DB::select(DB::raw("select * from rpm.patient_rpm_observations p                 
                inner join patients.partner_patient_alerts ppa on p.effdatetime = '".$readingTimestamp."' and p.device_id = '".$dcode."' and vitals_type='".$vtype."'"));
                 if(!empty($checkdataexist))
                 {
                    $match_status=1;
                    $rcare_alert_status=$checkdataexist[0]->alert_status;
                    $obrvtnid=$checkdataexist[0]->observation_id;
                 }

              }           
               
           $insertdata=array(              
              'threshold' =>isset($getdataresponse["threshold"])?$getdataresponse["threshold"]:null,
              'timestamp' =>$timestamp1,
              'addressedby' =>isset($getdataresponse["addressedBy"])?$getdataresponse["addressedBy"]:null,
              'careplanid' =>isset($getdataresponse["carePlanId"])?$getdataresponse["carePlanId"]:null,
              'expiretime' =>isset($getdataresponse["expireTime"])?$getdataresponse["expireTime"]:null,
              'limitover' =>isset($getdataresponse["limitOver"])?$getdataresponse["limitOver"]:null,
              'flag' =>isset($getdataresponse["flag"])?$getdataresponse["flag"]:null,
              'device_code' =>isset($getdataresponse["xmit_id"])?$getdataresponse["xmit_id"]:null,
              'addressedtime' =>$addressedTime,
              'notes' =>isset($getdataresponse["notes"])?$getdataresponse["notes"]:null,
              'observation_id' =>isset($getdataresponse["observation_id"])?$getdataresponse["observation_id"]:null,
              'addressed' =>isset($getdataresponse["addressed"])?$getdataresponse["addressed"]:null,
              'readingtimestamp' =>$readingTimestamp,
              'officeid' =>isset($getdataresponse["officeId"])?$getdataresponse["officeId"]:null,
              'measuredat' =>isset($getdataresponse["measuredAt"])?$getdataresponse["measuredAt"]:null,
              'type' =>isset($getdataresponse["type"])?$getdataresponse["type"]:null,
              'match_status'=>$match_status,
              'alert_status' =>$rcare_alert_status,
              'webhook_observation_id'  => $obrvtnid,
              'patient_id'=>$patientid           
            ); 
       //    dd($insertdata);

             $pa= PatientAlert::create($insertdata); 
              $updatewebhookalert= WebhookAlert::where('id',$id)->update(["fetch_status"=>1]); 
              
              $alertarray = array(  
                'patient_id'=>isset($patientid)?$patientid:0,
                'partner_id'=>isset($partner_device_id)?$partner_device_id:0,
                'observation_id'=>isset($getdataresponse["observation_id"])?$getdataresponse["observation_id"]:0,
                'record_timestamp'=>$timestamp1,
                'device_code'=>isset($deviceid)?$deviceid:null, 
                'content'=>$response,
                'mrn'=>isset($mrn_no)?$mrn_no:null,
                'status'=>1
               // 'created_by'=>$uid,
                //'updated_by'=>$uid
               );

                  $wa= AlertLog::create($alertarray); 
                 
               }  
              }
             }
           }
           else
           {
            echo "failed <br>";
           }
         }
       
            return response()->json("Data inserted successfully!");
         }

  

 //created by ashvini 12may2021  --------to be deleted
  // public function saveAlertDeviceid(Request $request)
  // {      
  //   $getdata= WebhookAlert::where('status',1)->where('fetch_status',0)->get();
  
  //   for($i=0;$i<count(array($getdata));$i++)
  //   {
     
  //     if(!empty($getdata[$i]->content))
  //       {
  //       $datajson=json_decode($getdata[$i]->content, true);
            
  //        if(isset($datajson['xmit_id']))
  //        {
  //              $deviceid=$datajson['xmit_id']; 
  //                 $id=$getdata[$i]->id;
  //       //$datajson=json_decode($getdata[$i]->content);
  //    //  $deviceid=isset($datajson->xmit_id) ? $datajson->xmit_id : null;  
  //       $webhookalerttimestamp=isset($datajson['timestamp']) ? $datajson['timestamp'] : null;
   
  //      $patdata = PatientDevices::where('device_code',$deviceid)->where('status',1)->get();
  //      $patientid ="";
  //      $partner_device_id =null;
  //      $mrn_no = null;
  //      if($patdata!="[]")
  //      { 
  //         $patientid = $patdata[0]->patient_id;      
  //         $partner_device_id = $patdata[0]->partner_device_id;
  //         $mrn_no = $patdata[0]->mrn_no;
  //       }

  //        $ecgcredetials=ApiECGCredeintials(); 
  //        $this->getAuthorization();
  //      //$deviceid='ANZ5877';
  //       $ch = curl_init();
  //       curl_setopt($ch, CURLOPT_URL,  $ecgcredetials[0]->url.'devices/'.$deviceid.'/alerts');         
  //       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
  //       curl_setopt($ch, CURLOPT_HTTPHEADER, [
  //        "Content-Type: application/json",
  //        "Authorization: Bearer ".session()->get('TokenId')]
  //      );
         
  //      $response = curl_exec($ch);
  //      curl_close($ch);
     
  //      $responsegetdata=json_decode($response);
  //      if(array_key_exists('message', $responsegetdata))
  //      {
        
  //       echo "not found ";
         
  //      }
  //      else
  //      {       
  //      foreach($responsegetdata as $d){
  //       //  dd($d);    
  //        foreach($d as $dd)
  //        {
  //       //  dd($dd->observation_id);
  //            $observationid = isset($dd->observation_id) ? $dd->observation_id : null ;                
  //            $jsonencodedata = json_encode($dd); 
  //            $t = isset($dd->timestamp) ?  $dd->timestamp : '';
  //            $at = isset($dd->addressedTime) ?  $dd->addressedTime : '';
  //            $rt = isset($dd->readingTimestamp) ?  $dd->readingTimestamp : '';
  //            $timestamp = date('Y-m-d H:i:s', strtotime($t)); 
  //            $addressedTime = date('Y-m-d H:i:s', strtotime($at));
  //            $readingTimestamp =  date('Y-m-d H:i:s', strtotime($rt));  
           
  //            $insertdata=array(              
  //             'threshold' =>isset($dd->threshold) ? $dd->threshold : null,
  //             'timestamp' =>$timestamp,
  //             'addressedby' =>isset($dd->addressedBy) ? $dd->addressedBy : null,
  //             'careplanid' =>isset($dd->carePlanId) ? $dd->carePlanId : null,
  //             'expiretime' =>isset($dd->expireTime) ? $dd->expireTime : null,
  //             'limitover' =>isset($dd->limitOver) ? $dd->limitOver : null,
  //             'flag' =>isset($dd->flag) ? $dd->flag : null,
  //             'device_code' =>isset($dd->xmit_id) ? $dd->xmit_id : null,
  //             'addressedtime' =>$addressedTime,
  //             'notes' =>isset($dd->notes) ? $dd->notes : null,
  //             'observation_id' =>$observationid,
  //             'addressed' =>isset($dd->addressed) ? $dd->addressed : null,
  //             'readingtimestamp' =>$readingTimestamp,
  //             'officeid' =>isset($dd->officeId) ? $dd->officeId : null,
  //             'measuredat' =>isset($dd->measuredAt) ? $dd->measuredAt : null,
  //             'type' =>isset($dd->type) ? $dd->type : null                     
  //              );    
      
  //             //  dd($insertdata);  
               
  //            $pa= PatientAlert::create($insertdata); 
  //              //record_timestamp mei kya jayega

  //              $alertarray = array(  
  //               'patient_id'=>$patientid,
  //               'partner_id'=>$partner_device_id,
  //               'observation_id'=>$observationid,
  //               'record_timestamp'=>$timestamp,
  //               'device_code'=>$deviceid, 
  //              // 'content'=>$jsonencodedata,
  //               'mrn'=>$mrn_no,
  //               'status'=>1,
  //               'created_by'=>'Symtech',
  //               'updated_by'=>'Symtech'
  //              );
       

  //              $wa= AlertLog::create($alertarray); 
              
  //        }
  //      }
  //       $updatestatus = WebhookAlert::where('id',$id)->update(['fetch_status'=>1]);  
  //       }
     
  //      // } catch(Exception $e) {
  //      //    return response()->json("Failed");
  //      //   }
  //    } 
  //     return response()->json("Data inserted successfully!"); 

  // }  

//created by radha
  public function GetOfficeDetails(Request $request){ 
           $ecgcredetials=ApiECGCredeintials(); 
           $this->getAuthorization();
            $groupname= config('global.GroupNameInECG');
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $ecgcredetials[0]->url.'groups/'.$groupname.'/offices');         
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
              curl_setopt($ch, CURLOPT_HTTPHEADER, [
               "Content-Type: application/json",
               "Authorization: Bearer ".session()->get('TokenId')]
             );
               
             $response = curl_exec($ch);
             curl_close($ch);
     
            $getdata=json_decode($response);           
            for($i=0;$i<count($getdata->data);$i++)
            {
              $phyarray=$getdata->data[$i]->physicians;  
              $physicians=implode(', ', $phyarray);
             
                $insertdata=array(                    
                    'name'=>$getdata->data[$i]->name,
                    'group_code'=>$getdata->data[$i]->groupName,
                    'description'=> $getdata->data[$i]->description,
                    'billing_phone'=>$getdata->data[$i]->billingPhone,
                    'billing_fname'=>$getdata->data[$i]->billingFirst,
                    'billing_lname'=>$getdata->data[$i]->billingLast,
                    'billing_address'=>$getdata->data[$i]->billingAddress,
                    'billing_city'=>$getdata->data[$i]->billingCity,
                    'billing_state'=>$getdata->data[$i]->billingState,
                    'billing_zip'=>$getdata->data[$i]->billingZip,
                    'billing_email'=>$getdata->data[$i]->billingEmail,
                    'alternate_phone'=>$getdata->data[$i]->alternatePhone,
                    'physicians'=>$physicians,
                    'created_by'=>session()->get('userid')
                );
                OfficeMst::create($insertdata);

            }
         
             return  response()->json("Data Saved successfully!");
           } 

           //  public function GetObservationDetails(Request $request){ 
           //  $tokenid= $this->RefreshToken();
           //  $reftoken=json_decode($tokenid);       
           //  $refreshtoken=$reftoken->IdToken;
            
           //  $groupname='RenovaHealthLLC';
           //    $ch = curl_init();
           //    curl_setopt($ch, CURLOPT_URL, 'https://dev.ecg-api.com/groups/'.$groupname.'/offices');         
           //    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
           //    curl_setopt($ch, CURLOPT_HTTPHEADER, [
           //     "Content-Type: application/json",
           //     "Authorization: Bearer ".$refreshtoken]
           //   );
               
           //   $response = curl_exec($ch);
           //   curl_close($ch);
         
           //   return  $response;
           // } 

    // created by Ashvini (15/04/2021) 
   public function saveDevicedataObservationLog(Request $request)
   {   
   
       $getdata =  \DB::select(DB::raw("select distinct content->>'xmit_id' as xmit_id from api.webhook_observation where content->>'xmit_id'  is not  null"));  
      //  dd($getdata);     
      foreach($getdata as $getdatadeviceid)
       {
        try {
          // dd($getdatadeviceid); 
          $deviceid = $getdatadeviceid->xmit_id;
          $patdata = PatientDevices::where('device_code',$deviceid)->where('status',1)->get();
          $patientid = $patdata[0]->patient_id;
          $partner_device_id = $patdata[0]->partner_device_id;
          $mrn_no = $patdata[0]->mrn_no;
           
           $ecgcredetials=ApiECGCredeintials();            
          $this->getAuthorization();
          //$deviceid='ANZ5877';
           $ch = curl_init();
           curl_setopt($ch, CURLOPT_URL, $ecgcredetials[0]->url.'devices/'.$deviceid.'/observations');         
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
           curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer ".session()->get('TokenId')]
          );  
          $response = curl_exec($ch);
          curl_close($ch);

          $newgetdata=json_decode($response);
          // dd($response);       
          foreach($newgetdata as $value){ 
            // dd($value);               
            foreach($value as $key=>$v){ 
              // dd($v);
                    $idarrays = [];
                    foreach($v as $o){
                      array_push($idarrays,$o->id); 
                    }  
                    $observationid = $key;
               foreach($v as $vkey=>$v_value){
                $this->saveRPMReadings($idarrays,$v_value,$patientid,$deviceid,$mrn_no,$observationid);
                      $v_data = json_encode($v_value);
                      $v_value->effectiveDateTime;
                      $d = date('Y-m-d H:i:s', strtotime($v_value->effectiveDateTime));
                      $insertdata=array(
                                      'patient_id' =>$patientid,
                                      'partner_id' =>$partner_device_id,
                                      'observation_id' =>$observationid,
                                      'record_timestamp' =>$d,
                                      'device_code' =>$deviceid,
                                      'content' =>$v_data,
                                      'content_format' =>'FHIR',
                                      'mrn' =>$mrn_no,
                                      'status' =>1,
                                      'created_by' =>'Symtech',
                                      'updated_by' =>'Symtech'
                                                    
                         );  
                         $oa= ObservationLog::create($insertdata); 
                         $updateprocessedflaginLog = ObservationLog::where('id',$oa->id)->update(['processed_flag'=>1]) ; 
                         $wa  = \DB::select(DB::raw("select id from api.webhook_observation where content->>'xmit_id' =  '".$deviceid."' and content->>'observation_id' = '".$observationid."' "));
                          
                         foreach($wa as $a)
                         {
                          $wa= WebhookObservation::where("id",$a->id)->update(['fetch_status'=>1]);
                          // dd($wa);
                         }
                        
                        
                          
                
               }                 
            }
          }
          return response()->json("Data inserted successfully!");
        }catch(Exception $e) {
          return response()->json("Failed");
         }
       
   }
  }

   //created and modified by ashvini
 public function saveObservationdataObservationLog()         
 {    
    
  // $getdata = \DB::select(DB::raw ("select id,content->>'observation_id' as observation_id,
  //             content->>'xmit_id' as xmit_id,
  //             content->>'mrn' as mrn,
  //             content->>'timestamp'  as timestamp from api.webhook_observation wo where 1=1
  //             and fetch_status =1 and 
  //             content ->>'xmit_id'
  //             not in (select distinct observation_id from rpm.patient_cons_observations) offset 0 limit 10"));

   $getdata = \DB::select(DB::raw("select id,content->>'observation_id' as observation_id,
   content->>'xmit_id' as xmit_id,
   content->>'mrn' as mrn,
    content->>'timestamp'  as timestamp    
   from api.webhook_observation where fetch_status = 0 offset 0 limit 100" ));  


  //  $getdata = \DB::select(DB::raw("select id,content->>'observation_id' as observation_id,
  //   content->>'xmit_id' as xmit_id,
  //   content->>'mrn' as mrn,
  //   content->>'timestamp' as timestamp    
  //   from api.webhook_observation where fetch_status = 0 and content->>'timestamp' >= '2021-11-01 00:00:00'
  //   and content->>'timestamp' < '2021-11-30 23:59:59' " )); 


  //  dd($getdata);    
   
   //  $getdata = \DB::select(DB::raw("select id,content->>'observation_id' as observation_id,content->>'xmit_id' as xmit_id,content->>'mrn' as mrn     
   //             from api.webhook_observation where id = 7267 " )); //oxymeter k liy

   //  $getdata = \DB::select(DB::raw("select id,content->>'observation_id' as observation_id,content->>'xmit_id' as xmit_id,content->>'mrn' as mrn     
   //             from api.webhook_observation where id in (7053,7054) " )); //spirometer k liy


   //  $getdata = \DB::select(DB::raw("select id,content->>'observation_id' as observation_id,content->>'xmit_id' as xmit_id,content->>'mrn' as mrn     
   //             from api.webhook_observation where id in (2761,7377) " )); //bp k liy

  //  $getdata = \DB::select(DB::raw("select id,content->>'observation_id' as observation_id,content->>'xmit_id' as xmit_id,content->>'mrn' as mrn     
  //                                 from api.webhook_observation where id in (1021,775,1044,671,850,963)" )); //oxy wid no practiceid in patientprovider scenario k liy
   
  // $getdata = \DB::select(DB::raw("select id,
  //               content->>'observation_id' as observation_id,
  //               content->>'xmit_id' as xmit_id,
  //               content->>'mrn' as mrn,
  //               content->>'timestamp' as timestamp      
  //               from api.webhook_observation where id = 6484" )); 

                // $getdata = \DB::select(DB::raw("select id,
                //                content->>'observation_id' as observation_id,
                //                content->>'xmit_id' as xmit_id,
                //               content->>'mrn' as mrn,
                //               content->>'timestamp' as timestamp      
                //               from api.webhook_observation where id = 9326" ));   //checking glucose reading 
      
    //dd($getdata);         
    // $yourDate = $getdata[0]->timestamp;
    // $a = date('Y-m-d h:i:s', strtotime($yourDate));
    // dd($a);
    

   // try{
   foreach($getdata as $g)
   {

    // dd($g);
     
     $observationid = $g->observation_id;
     // $observationid = '8bff0981-98f8-4b59-8ba9-fa124f4624e2'; //for bloodpressur abd heartrate
     // $observationid= 'e1989fab-da37-4081-8877-73e754b6509d'; //for saturationo2 
     // $observationid = '6774ffc6-e2dc-432e-b5d9-020ff4073de8';
           
     $deviceid = $g->xmit_id;
     $mrn = $g->mrn;
     $id = $g->id;
     $t = $g->timestamp;
     $time = date('Y-m-d h:i:s', strtotime($t));
   
    
     $patdata = PatientDevices::where('device_code',$deviceid)->where('status',1)->get();
     // dd($patdata); 
     
     if($patdata->isEmpty()){

       $patientid = null;
       if($patientid==null){
        
         $updatedata= WebhookObservation::where('id',$id)->update(['fetch_status'=>2]); 
         $msg = 'Empty patientid record';  
         $p = explode(" ",$msg);
         $parameter = $p[0]."-".$p[1]."-".$p[2];
         $a = array('api'=>(Config::get('global.authurl')).'observations/{id}',
         'parameter'=>$parameter,'exception_type'=>'alert','incident'=>'missing','webhook_id'=>$id,
         'mrn'=>$mrn,'patient_id'=>null,'observation_id'=>$observationid, 'device_code'=>$deviceid );
         $exception = ApiException::create($a);

      //    if($mrn==null || $mrn=="" || $mrn=='' ){
          
      //      $updatedata= WebhookObservation::where('id',$id)->update(['fetch_status'=>2]);           
      //      $msg = 'Empty patientid record'; 
      //      $p = explode(" ",$msg);
      //      $parameter = $p[0]."-".$p[1]."-".$p[2];
      //      $a = array('action_taken'=>'Report to Admin',
      //      'mrn'=>null,'patient_id'=>null,'observation_id'=>$observationid, 'device_code'=>$deviceid );         
      //      $b = ApiException::where('id',$exception->id)->update($a);

      //      $partner_device_id = null;
      //      $mrn_no = null; 
      //    }
      //    else{

      //    $patproviderdata = PatientProvider::where('practice_emr',$mrn)->where('is_active',1)->get();   
                  
         
      //   if(count($patproviderdata)>0){
      //    if($patproviderdata[0]->patient_id==null || $patproviderdata[0]->patient_id=="" || $patproviderdata[0]->patient_id==''){
      //      $updatedata= WebhookObservation::where('id',$id)->update(['fetch_status'=>2]);  
      //      //throw new Exception('Empty patientid-mrnid record');
      //      $msg = 'Empty patientid record'; 
      //      $p = explode(" ",$msg);
      //      $parameter = $p[0]."-".$p[1]."-".$p[2];
      //      $a = array('action_taken'=>'Report to Admin','mrn'=>null,'patient_id'=>null,'observation_id'=>$observationid, 
      //      'device_code'=>$deviceid);
      //      ApiException::where('id',$exception->id)->update($a); 

      //      $partner_device_id = null;
      //      $mrn_no = null;
      //      continue;

      //    }
      //    else{
          
      //         $patientid = $patproviderdata[0]->patient_id;
      //         $partner_device_id = null;
      //         $mrn_no = $mrn; 

      //         $msg = 'Empty patientid record'; 
      //         $p = explode(" ",$msg);
      //         $parameter = $p[0]."-".$p[1]."-".$p[2];
      //         $a = array('action_taken'=>'Patientid fetched from Patientproviders','exception_type'=>'rectified',
      //         'mrn'=>$mrn_no,'patient_id'=>$patientid,'observation_id'=>$observationid, 'device_code'=>$deviceid);  
      //         ApiException::where('id',$exception->id)->update($a);

              
      //         $patarray = array('patient_id'=>$patientid,'partner_device_id'=>$partner_device_id,'device_code'=>$deviceid,
      //         'activation_date'=>null,'device_attr'=>null,'status'=>1,'order_id'=>null,'mrn_no'=>$mrn_no);
      //         PatientDevices::create($patarray);
          
      //      }
      //   }
      //   else{ 

        
      //    ($patientid==null || $patientid=='' || $patientid=="") ? ($patientid=null) : ($patientid);
        
      //    $updatedata= WebhookObservation::where('id',$id)->update(['fetch_status'=>2]);  
      //    $a = array('action_taken'=>'Report to Admin','mrn'=>null,
      //    'patient_id'=>$patientid,'observation_id'=>$observationid, 'device_code'=>$deviceid);
      //    ApiException::where('id',$exception->id)->update($a); 


      //    $partner_device_id = null;
      //    $mrn_no = null;
      //    continue; 
      //   }  //uncomment later
        

      //  }


       }


     }   
     else{
     
       $patientid = $patdata[0]->patient_id;
       $partner_device_id = $patdata[0]->partner_device_id;
       $mrn_no = $patdata[0]->mrn_no; 
       
        

     } 

     // var_dump($patientid);

   if($patientid!=null && $mrn_no!= null ){

      // dd($patientid,$mrn_no); 

     $checkpatientservicesinccm =  PatientServices::where('patient_id',$patientid)->where('module_id',3)->get(); 
     $patientservices = PatientServices::where('patient_id',$patientid)->where('module_id',2)->get(); 
     // dd($patientservices); 
     if((!$checkpatientservicesinccm->isEmpty()) && ($patientservices->isEmpty()) ){
       
           $updatedata= WebhookObservation::where('id',$id)->update(['fetch_status'=>2]);     
           $msg = 'Patient not enrolled in RPM';  
           $p = explode(" ",$msg);
           $parameter = $p[0]."-".$p[1]."-".$p[2]."-".$p[3]."-".$p[4]; 
           $a = array('api'=>(Config::get('global.authurl')).'observations/{id}',
           'parameter'=>$parameter,'exception_type'=>'alert','incident'=>'nonenrolledRPM','webhook_id'=>$id,        
           'mrn'=>$mrn_no,'patient_id'=>$patientid,'observation_id'=>$observationid, 'device_code'=>$deviceid);
           $exception = ApiException::create($a);
     }


    //  dd($patientid);  
    //  dd($patientservices);

     if($patientservices->isEmpty())
     {
        
     }
     else{

     
     $patientstatus = $patientservices[0]->status;    
     $suspended_from = $patientservices[0]->suspended_from;   
     $suspended_to = $patientservices[0]->suspended_to;

    //  if($patientstatus == 2){

         
    //      $updatedata= WebhookObservation::where('id',$id)->update(['fetch_status'=>2]); 
    //      $msg = 'Patient is Deactivated';  
    //      $p = explode(" ",$msg);
    //      $parameter = $p[0]."-".$p[1]."-".$p[2];
    //      $a = array('api'=>(Config::get('global.authurl')).'observations/{id}',
    //      'parameter'=>$parameter,'exception_type'=>'alert','incident'=>'deactive','webhook_id'=>$id,        
    //      'mrn'=>$mrn_no,'patient_id'=>$patientid,'observation_id'=>$observationid, 'device_code'=>$devdiceid);
    //      $exception = ApiException::create($a);


    //  }
    //  else if($patientstatus == 3){

    //    $updatedata= WebhookObservation::where('id',$id)->update(['fetch_status'=>3]); 
    //    $msg = 'Patient is Deceased';  
    //    $p = explode(" ",$msg);
    //    $parameter = $p[0]."-".$p[1]."-".$p[2];
    //    $a = array('api'=>(Config::get('global.authurl')).'observations/{id}',
    //    'parameter'=>$parameter,'exception_type'=>'alert','incident'=>'deactive','webhook_id'=>$id,
    //    'mrn'=>$mrn_no,'patient_id'=>$patientid,'observation_id'=>$observationid, 'device_code'=>$devdiceid);
    //    $exception = ApiException::create($a);
    //  }
    //  else{

      if($time > $suspended_from  &&  $time < $suspended_to &&  $patientstatus== 2 ){
           
         $updatedata= WebhookObservation::where('id',$id)->update(['fetch_status'=>2]); 
         $msg = 'Patient is Suspended from'.$suspended_from.'to'.$suspended_to;  
         $p = explode(" ",$msg);
         $parameter = $p[0]."-".$p[1]."-".$p[2];
         $a = array('api'=>(Config::get('global.authurl')).'observations/{id}',
         'parameter'=>$parameter,'exception_type'=>'alert','incident'=>'deactive','webhook_id'=>$id,        
         'mrn'=>$mrn_no,'patient_id'=>$patientid,'observation_id'=>$observationid, 'device_code'=>$devdiceid);
         $exception = ApiException::create($a);
      }
      else if($time > $suspended_from && $patientstatus== 3){

        $updatedata= WebhookObservation::where('id',$id)->update(['fetch_status'=>3]); 
        $msg = 'Patient is Deceased';  
        $p = explode(" ",$msg);
        $parameter = $p[0]."-".$p[1]."-".$p[2];
        $a = array('api'=>(Config::get('global.authurl')).'observations/{id}',
        'parameter'=>$parameter,'exception_type'=>'alert','incident'=>'deactive','webhook_id'=>$id,
        'mrn'=>$mrn_no,'patient_id'=>$patientid,'observation_id'=>$observationid, 'device_code'=>$devdiceid);
        $exception = ApiException::create($a);

      }
      else{
// dd("hlo");
      

                  $ecgcredetials=ApiECGCredeintials(); 
                  $partner_id = $ecgcredetials[0]->partner_id;
                  $auth = $this->getAuthorization();
                
                  
                  $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL,  $ecgcredetials[0]->url.'observations/'.$observationid);         
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
                  curl_setopt($ch, CURLOPT_HTTPHEADER, [
                  "Content-Type: application/json",
                  "Authorization: Bearer ".session()->get('TokenId')    
                  
                  ]
                );
                  
                $response = curl_exec($ch);  
                curl_close($ch);

                $newgetdata=json_decode($response); 
                // dd($newgetdata) ;
                
                if (array_key_exists('message', $newgetdata)){

                  $a = array('partner_id'=> $partner_id,
                              'message'=> $newgetdata->message,
                              'status'=>0,
                              'api_url'=>$ecgcredetials[0]->url.'observations/'.$observationid
                            );
                  ApiErrorLog::create($a);
                  
                }
                else{

                $a = array('partner_id'=> $partner_id,
                          'message'=> 'Running successfully',
                          'status'=>1,
                          'api_url'=>$ecgcredetials[0]->url.'observations/'.$observationid
                          );
                ApiErrorLog::create($a);       
                $idarrays = [];

                      
                foreach($newgetdata as $o){
                  array_push($idarrays,$o->id); 
                }
                
                // $idarrays = ['weight-scale']; 
                \Log::info("before insertion");

                foreach($newgetdata as $ob)  
                {
                //  dd($newgetdata);
                  $recorddate = date('Y-m-d H:i:s', strtotime($ob->effectiveDateTime));      
                  $apiurl=$ecgcredetials[0]->url.'observations/{id}';
                  $jsonencodedata_ob = json_encode($ob);
                  $insertdata=array(
                                  'patient_id' => $patientid,
                                  'partner_id' =>$partner_device_id,
                                  'observation_id' =>$observationid,
                                  'record_timestamp' =>$recorddate,
                                  'device_code' =>$deviceid,
                                  'content' =>$jsonencodedata_ob,
                                  'content_format' =>'FHIR',
                                  'mrn' =>$mrn_no,
                                  'status' =>1,
                                  'created_by' =>'Symtech',
                                  'updated_by' =>'Symtech',
                                  'processed_flag' =>0,
                                  'api_url' =>  $apiurl              
                                  ); 
                                      
                    $observationinsert= ObservationLog::create($insertdata); 

                    $status=WebhookObservation::where('id',$id)->value('fetch_status');
                    \Log::info($id."fetch status is ".$status);  

                    $updatedata= WebhookObservation::where('id',$id)->update(['fetch_status'=>1]);

                    $afterstatus=WebhookObservation::where('id',$id)->value('fetch_status');   
                    \Log::info($id."after updation fetch status is ".$afterstatus);  


                    $checkpatientprovider = PatientProvider::where('patient_id',$patientid)->where('is_active',1)->where('provider_type_id',1)->first();
                  
                    if(is_null($checkpatientprovider)){
              
                    $updatedata= WebhookObservation::where('id',$id)->update(['fetch_status'=>2]); 
                    $msg = 'No practiceid for this patientid in PatientProvider table';  
                    //  $p = explode(" ",$msg);
                    $parameter = $msg;
                    $a = array('api'=>(Config::get('global.authurl')).'observations/{id}',
                    'parameter'=>$parameter,'exception_type'=>'alert','incident'=>'missing','webhook_id'=>$id,
                    'mrn'=>$mrn_no,'patient_id'=>$patientid,'observation_id'=>$observationid, 'device_code'=>$deviceid);
                    $exception = ApiException::create($a); 
              
                    }
                    else{
                      $this->saveRPMReadings($idarrays,$ob,$patientid,$deviceid,$mrn_no,$observationid);   
                    }
                    
                    

                    $updateprocessedflaginLog = ObservationLog::where('id',$observationinsert->id)->update(['processed_flag'=>1]) ;          
                                  
                          
                  
                  
                   } 
                  }

        }               
  
  // }//else block

 }//patient status else
}
 
 //else 
 }
   return response()->json("Data1 inserted successfully!"); 
 // }
 // catch(Exception $e){ 
 //   $msg  = $e->getMessage(); 

 //   $alerterrors = array("Empty observation record", "Observation id null or blank alert", "Observation id key missing alert",
 //                 "Xmit id null or blank alert", "Xmit id key missing alert", "Observation json response alert",
 //                 "Observation EffectiveDateTime key missing alert", "Observation EffectiveDateTime null or blank", 
 //                 "Observation Id is null or blank","Observation Id key missing alert",
 //                 "Weight-Scale ValueQuantity is null or blank alert", "Weight-Scale unit of ValueQuantity is null or blank alert", 
 //                 "Weight-Scale ValueQuantity key missing alert",
 //                 "Blood-pressure value null or blank alert", "Blood-pressure value key missing alert", "Blood-pressure unit null or blank alert",
 //                 "Blood-pressure unit key missing alert", "Blood-pressure value null or blank alert", "Blood-pressure value key missing alert",
 //                 "Blood-pressure display key missing alert", "Blood-pressure component key missing alert","Blood-pressure component null or blank alert",
 //                 "Saturation-O2 value null or blank alert","Saturation-O2 value key missing alert","Saturation-O2 unit key missing alert",
 //                 "Saturation-O2 unit key missing alert","Saturation-O2 display key missing alert","Saturation-O2 display null or blank alert",
 //                 "Heartrate value null or blank alert","Heartrate value key missing alert","Heartrate unit null or blank alert",
 //                 "Heartrate unit key missing alert", "Heartrate display key missing alert",
 //                 "Empty patientid record"
 //                 );

 //     $infoerrors = array("Blood-pressure code null or blank","Blood-pressure code key missing",
 //                         "Saturation-O2 code null or blank","Saturation-O2 code key missing",
 //                         "Heartrate code null or blank","Heartrate code key missing") ;

 //                         if(in_array($msg, $alerterrors)){
     
 //                           $p = explode(" ",$msg);
 //                           $parameter = $p[0]."-".$p[1]."-".$p[2];
 //                           // $parameter = $p[1];
 //                           $a = array('api'=>'https://dev.ecg-api.com/observations/{id}','parameter'=>$parameter,'exception_type'=>'alert','incident'=>'missing');
 //                           ApiException::create($a);
                          
 //                         }
 //                         else if(in_array($msg, $infoerrors)){
 //                           $p = explode(" ",$msg);
 //                           $parameter = $p[0]."-".$p[1]."-".$p[2];  
 //                           // $parameter = $p[1];
 //                           $a = array('api'=>'https://dev.ecg-api.com/observations/{id}','parameter'=>$parameter,'exception_type'=>'info','incident'=>'missing');
 //                           ApiException::create($a);
                           
 //                         }
 //                         else{
                          
 //                         }

                       
 // }
   
  
 }   
    

     
 //created and modified by ashvini
 public function saveRPMReadings($idarrays,$obs,$patientid,$deviceid,$mrn_no,$observationid)
 {
     
    // dd($idarrays,$obs,$patientid,$deviceid,$mrn_no,$observationid);
   $id = $obs->id;
 
   $recorddate = date('Y-m-d H:i:s', strtotime($obs->effectiveDateTime)); 

   if (in_array("blood-pressure", $idarrays)){  
  
     $vitaldevice = Devices::where('device_name','Blood Pressure Cuff')->where('status',1)->first();
     $vitaldeviceid = $vitaldevice->id; 


       $insertdata = array(
                       'careplan_no' => null,
                       'device_id' => $deviceid,
                       'effdatetime'=>$recorddate,
                       'systolic_qty'=>null,
                       'systolic_unit'=> null,
                       'systolic_code'=> null,
                       'diastolic_qty'=>null,
                       'diastolic_unit'=>null,
                       'diastolic_code'=>null,
                       'resting_heartrate'=>null,
                       'resting_heartrate_unit'=>null,
                       'resting_heartrate_code'=>null,
                       'mrn'=>$mrn_no,
                       'created_by'=>'Symtech',
                       'updated_by'=>'Symtech',
                       'patient_id'=>$patientid,
                       'reviewed_flag'=>0,
                       'reviewed_date'=>null,
                       'observation_id'=>$observationid,
                       'billing'=>0,
                       'vital_device_id'=>$vitaldeviceid,
                       'threshold'=>null,
                       'threshold_type'=>null,
                       'threshold_id'=>null,
                       'systolic_threshold'=>null,
                       'diastolic_threshold'=>null
                       );
                       $check = Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                       ->where('observation_id',$observationid)
                       ->where('effdatetime',$recorddate)
                       ->get();     
                       if(count($check)==0){ 
                         
                         $o = Observation_BP::create($insertdata);
                        
                        
                       }
                         
   }
   
   else if(in_array("satO2",$idarrays)){ 

     $vitaldevice = Devices::where('device_name','Pulse Oximeter')->where('status',1)->first();
     $vitaldeviceid = $vitaldevice->id;

     $insertdatasaturation = array(
                                 'careplan_no' => null,
                                 'device_id' => $deviceid,
                                 'effdatetime'=> $recorddate,
                                 'oxy_qty'=>null,
                                 'oxy_unit'=> null,
                                 'oxy_code'=> null,
                                 'resting_heartrate'=>null,
                                 'resting_heartrate_unit'=>null,
                                 'resting_heartrate_code'=>null,
                                 'mrn'=>$mrn_no,
                                 'created_by'=>'Symtech',
                                 'updated_by'=>'Symtech',
                                 'patient_id'=>$patientid,
                                 'reviewed_flag'=>0,
                                 'reviewed_date'=>null,
                                 'observation_id'=>$observationid,
                                 'billing'=>0,
                                 'vital_device_id'=>$vitaldeviceid,
                                 'threshold'=>null   
                                 );
                                 $check = Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)
                                 ->where('mrn',$mrn_no)->where('effdatetime',$recorddate)
                                 ->where('observation_id',$observationid)
                                 ->get();     
                                 if(count($check)==0){
                                   $o = Observation_Oxymeter::create($insertdatasaturation); 
                                 }    
   }
   else{

   }

    

   switch($id){   

     case 'spirometer';
   
     
     $vitaldevice = Devices::where('device_name','Spirometer')->where('status',1)->first();
     $vitaldeviceid = $vitaldevice->id;


     $a = $obs->valueQuantity;
     $fev_value = $a->fevValue;
     $fevunit = $a->unit;
     $fevcode = $a->code;
     $pef_value = $a->value;
     $pef_unit = $fevunit.$fevcode;
     $pef_code = $fevunit.$fevcode;
     

     $insertdataspiro = array(
                     'careplan_no' => null,
                     'device_id' => $deviceid,
                     'effdatetime'=> $recorddate,
                     'mrn'=>$mrn_no,
                     'created_by'=>'Symtech',
                     'updated_by'=>'Symtech',
                     'patient_id'=>$patientid,
                     'reviewed_flag'=>0,
                     'reviewed_date'=>null,
                     'observation_id'=>$observationid,
                     'billing'=>0,
                     'alert_status'=>0,
                     'addressed'=>0,
                     'effdatetime'=> $recorddate,
                     'fev_value'=>$fev_value,
                     'fev_unit'=> $fevunit,
                     'fev_code'=>$fevcode,
                     'pef_value'=>$pef_value,
                     'pef_unit'=>$pef_unit,
                     'pef_code'=>$pef_code,
                     'threshold'=>null,
                     'threshold_type'=>null,
                     'threshold_id'=>null,
                     'vital_device_id'=>$vitaldeviceid,
                     'pef_threshold'=>null,
                     'fev_threshold'=>null  
                     ); 

                     // dd($upspirodata,$deviceid,$patientid,$mrn_no,$recorddate); 

                     $check = Observation_Spirometer::where('device_id',$deviceid)->where('patient_id',$patientid)
                     ->where('mrn',$mrn_no)->where('effdatetime',$recorddate)->where('observation_id',$observationid)->get(); 
                  
                     if(count($check)==0){
                       $og = Observation_Spirometer::create($insertdataspiro); 
                     } 

                     



//-------------------------------------------------------------------------threshold and alert status together---------------------------------------------------------------------------------

           
         $checkpatientthreshold = PatientThreshold::where('patient_id',$patientid)
                                 ->whereNotNull('spirometerfevlow')
                                 ->whereNotNull('spirometerfevhigh')
                                 ->whereNotNull('spirometerpefhigh')
                                 ->whereNotNull('spirometerpeflow')
                                 ->orderBy('created_at', 'desc')->get();
        


         if($checkpatientthreshold->isEmpty())
         {
           $checkpractice = PatientProvider::where('patient_id',$patientid)->where('is_active',1)->where('provider_type_id',1)->first();
           // dd($checkpractice);
           
           if(!is_null($checkpractice)){
           
             $checkinpracticethreshold = PracticeThreshold::where('practice_id',$checkpractice->practice_id)
                                       ->whereNotNull('spirometerfevlow')
                                       ->whereNotNull('spirometerfevhigh')
                                       ->whereNotNull('spirometerpefhigh')
                                       ->whereNotNull('spirometerpeflow')
                                       ->orderBy('created_at', 'desc')->get();
             
           } 
          
           
               if($checkpractice==null || $checkinpracticethreshold->isEmpty()){
                 
                 
                 $orgid = Practices::where('id',$checkpractice->practice_id)->get(); //$orgid[0]->practice_group//4
               

                 // dd($orgid[0]['practice_group'],$orgid[0]->practice_group); 
                 if(!is_null($orgid)){
               
                   $orgcheck = OrgThreshold::get();

                   if(!$orgcheck->isEmpty() ){
                     $org = OrgThreshold::where('org_id',$orgid[0]['practice_group'])
                           ->whereNotNull('spirometerfevlow')
                           ->whereNotNull('spirometerfevhigh')
                           ->whereNotNull('spirometerpefhigh')
                           ->whereNotNull('spirometerpeflow')
                           ->get();

                           // dd($org );
                           
                   
              
                   if(!$org->isEmpty() ){
                                           
                    
                     
                     $orgthreshold = $org[0]->spirometerfevlow."-".$org[0]->spirometerfevhigh."/".$org[0]->spirometerpeflow."-".$org[0]->spirometerpefhigh;
                      
                     $fev_threshold = $org[0]->spirometerfevlow."-".$org[0]->spirometerfevhigh;
                     $pef_threshold = $org[0]->spirometerpeflow."-".$org[0]->spirometerpefhigh;

                     ($orgthreshold == null || $orgthreshold == 'null' || $orgthreshold == '-/-' || $orgthreshold == "-/-" ) ? $orgthreshold = '' : $orgthreshold;

                     ($orgthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'O';
                     ($orgthreshold=='' ) ? $thresholdid = null : $thresholdid = $org[0]->id; 

                     Observation_Spirometer::where('device_id',$deviceid)
                     ->where('patient_id',$patientid)
                     ->where('mrn',$mrn_no)
                     ->where('effdatetime',$recorddate)
                     ->where('observation_id',$observationid)
                     ->update(['threshold'=>$orgthreshold,
                     'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid,
                     'fev_threshold'=>$fev_threshold,'pef_threshold'=>$pef_threshold]);

                     if($orgthreshold=="" || $orgthreshold=='' || $orgthreshold==null){                         
                     }
                     else{

                      if(($fev_value > $org[0]->spirometerfevhigh) || ($fev_value < $org[0]->spirometerfevlow ) || ( $pef_value > $org[0]->spirometerpefhigh ) || ($pef_value < $org[0]->spirometerpeflow))
                      {
                        Observation_Spirometer::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                          ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                      }

                     }

                     

                   }
                   else{

                     //checking in groupthreshold  
                    
                     // dd("else");
                     
                        $gt =     GroupThreshold::whereNotNull('spirometerfevlow')                               
                                  ->whereNotNull('spirometerfevhigh')
                                  ->whereNotNull('spirometerpefhigh')
                                  ->whereNotNull('spirometerpeflow')
                                  ->orderBy('created_at', 'desc')->get(); 
                      

                        $grpthreshold = $gt[0]->spirometerfevlow."-".$gt[0]->spirometerfevhigh."/".$gt[0]->spirometerpeflow."-".$gt[0]->spirometerpefhigh;

                        $fev_threshold = $gt[0]->spirometerfevlow."-".$gt[0]->spirometerfevhigh;
                        $pef_threshold = $gt[0]->spirometerpeflow."-".$gt[0]->spirometerpefhigh;


                        ($grpthreshold == null || $grpthreshold == 'null' || $grpthreshold == '-/-' || $grpthreshold == "-/-" ) ? $grpthreshold = '' : $grpthreshold;
  
  
                        ($grpthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'R';
                        ($grpthreshold=='' ) ? $thresholdid = null : $thresholdid = $gt[0]->id; 

                        Observation_Spirometer::where('device_id',$deviceid)
                          ->where('patient_id',$patientid)
                          ->where('mrn',$mrn_no)
                          ->where('effdatetime',$recorddate)
                          ->where('observation_id',$observationid)
                          ->update(['threshold'=>$grpthreshold,'threshold_type'=>$thresholdtype,
                          'threshold_id'=>$thresholdid,'fev_threshold'=>$fev_threshold, 'pef_threshold'=>$pef_threshold]);

                          if($grpthreshold=="" || $grpthreshold=='' || $grpthreshold==null){  
                          }
                          else{
                          if(($fev_value > $gt[0]->spirometerfevhigh) || ($fev_value < $gt[0]->spirometerfevlow ) || ( $pef_value > $gt[0]->spirometerpefhigh ) || ($pef_value < $gt[0]->spirometerpeflow))
                          {
                            Observation_Spirometer::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                              ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                          }

                          }
  
                          
  
                   }

                 }else{
                         $gt =     GroupThreshold::whereNotNull('spirometerfevlow')                               
                                    ->whereNotNull('spirometerfevhigh')
                                    ->whereNotNull('spirometerpefhigh')
                                    ->whereNotNull('spirometerpeflow')
                                    ->orderBy('created_at', 'desc')->get(); 
                 

                   $grpthreshold = $gt[0]->spirometerfevlow."-".$gt[0]->spirometerfevhigh."/".$gt[0]->spirometerpeflow."-".$gt[0]->spirometerpefhigh;

                   $fev_threshold = $gt[0]->spirometerfevlow."-".$gt[0]->spirometerfevhigh;
                   $pef_threshold = $gt[0]->spirometerpeflow."-".$gt[0]->spirometerpefhigh;

                   ($grpthreshold == null || $grpthreshold == 'null' || $grpthreshold == '-/-' || $grpthreshold == "-/-" ) ? $grpthreshold = '' : $grpthreshold;
                   
                   ($grpthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'R';
                   ($grpthreshold=='' ) ? $thresholdid = null : $thresholdid = $gt[0]->id;



                   Observation_Spirometer::where('device_id',$deviceid)
                     ->where('patient_id',$patientid)
                     ->where('mrn',$mrn_no)
                     ->where('effdatetime',$recorddate)
                     ->where('observation_id',$observationid)
                     ->update(['threshold'=>$grpthreshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid,
                                'fev_threshold'=>$fev_threshold,'pef_threshold'=>$pef_threshold]);

                 if($grpthreshold=="" || $grpthreshold=='' || $grpthreshold==null){  
                 }
                 else
                 {
                     
                  if(($fev_value > $gt[0]->spirometerfevhigh) || ($fev_value < $gt[0]->spirometerfevlow ) || ( $pef_value > $gt[0]->spirometerpefhigh ) || ($pef_value < $gt[0]->spirometerpeflow))
                  {
                    Observation_Spirometer::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                      ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                  }
                }      


                 }
                   

                 }
                 else{

                   $gt =     GroupThreshold::whereNotNull('spirometerfevlow')                               
                             ->whereNotNull('spirometerfevhigh')
                             ->whereNotNull('spirometerpefhigh')
                             ->whereNotNull('spirometerpeflow')
                             ->orderBy('created_at', 'desc')->get(); 
                      

                        $grpthreshold = $gt[0]->spirometerfevlow."-".$gt[0]->spirometerfevhigh."/".$gt[0]->spirometerpeflow."-".$gt[0]->spirometerpefhigh;

                        $fev_threshold = $gt[0]->spirometerfevlow."-".$gt[0]->spirometerfevhigh;
                        $pef_threshold = $gt[0]->spirometerpeflow."-".$gt[0]->spirometerpefhigh;
                        
                        ($grpthreshold == null || $grpthreshold == 'null' || $grpthreshold == '-/-' || $grpthreshold == "-/-" ) ? $grpthreshold = '' : $grpthreshold;

                        
                         ($grpthreshold=='' ) ? $thresholdtype = '' : $thresholdtype = 'R';
                         ($grpthreshold=='' ) ? $thresholdid = '' : $thresholdid = $gt[0]->id;
  
  
                        Observation_Spirometer::where('device_id',$deviceid)
                          ->where('patient_id',$patientid)
                          ->where('mrn',$mrn_no)
                          ->where('effdatetime',$recorddate)
                          ->where('observation_id',$observationid)
                          ->update(['threshold'=>$grpthreshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid,
                                    'fev_threshold'=>$fev_threshold,'pef_threshold'=>$pef_threshold  ]);
                                   
  
                         if($grpthreshold=="" || $grpthreshold=='' || $grpthreshold==null){  
                         } 
                         else{
                              if(($fev_value > $gt[0]->spirometerfevhigh) || ($fev_value < $gt[0]->spirometerfevlow ) || ( $pef_value > $gt[0]->spirometerpefhigh ) || ($pef_value < $gt[0]->spirometerpeflow))
                              {
                                Observation_Spirometer::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                                  ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                              }

                         }         
                          


                 }

                  
                
               }
               else{

                 $practicethreshold = $checkinpracticethreshold[0]->spirometerfevlow."-".$checkinpracticethreshold[0]->spirometerfevhigh."/".$checkinpracticethreshold[0]->spirometerpeflow."-".$checkinpracticethreshold[0]->spirometerpefhigh;
           
                 $fev_threshold = $checkinpracticethreshold[0]->spirometerfevlow."-".$checkinpracticethreshold[0]->spirometerfevhigh;
                 $pef_threshold = $checkinpracticethreshold[0]->spirometerpeflow."-".$checkinpracticethreshold[0]->spirometerpefhigh;

                 ($practicethreshold == null || $practicethreshold == 'null' || $practicethreshold == '-/-' || $practicethreshold == "-/-" ) ? $practicethreshold = '' : $practicethreshold;

                 ($practicethreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'Pr';
                 ($practicethreshold=='' ) ? $thresholdid = null : $thresholdid = $checkinpracticethreshold[0]->id;

                  Observation_Spirometer::where('device_id',$deviceid)
                  ->where('patient_id',$patientid)
                  ->where('mrn',$mrn_no)
                  ->where('effdatetime',$recorddate)
                  ->where('observation_id',$observationid)
                  ->update(['threshold'=>$practicethreshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid,
                            'fev_threshold'=>$fev_threshold,'pef_threshold'=>$pef_threshold]);

                  if($grpthreshold=="" || $grpthreshold=='' || $grpthreshold==null){  
                  }
                 else{
                      if(($fev_value > $practicethreshold[0]->spirometerfevhigh) || ($fev_value < $practicethreshold[0]->spirometerfevlow ) || ( $pef_value > $practicethreshold[0]->spirometerpefhigh ) || ($pef_value < $practicethreshold[0]->spirometerpeflow))
                      {
                        Observation_Spirometer::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                       ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                      }
                  }

                 
                    
               } 
         }
         else{

           $patientthreshold = $checkpatientthreshold[0]->spirometerfevlow."-".$checkpatientthreshold[0]->spirometerfevhigh."/".$checkpatientthreshold[0]->spirometerpeflow."-".$checkpatientthreshold[0]->spirometerpefhigh;
           
           $fev_threshold = $checkpatientthreshold[0]->spirometerfevlow."-".$checkpatientthreshold[0]->spirometerfevhigh;
           $pef_threshold = $checkpatientthreshold[0]->spirometerpeflow."-".$checkpatientthreshold[0]->spirometerpefhigh;

           ($patientthreshold == null || $patientthreshold == 'null' || $patientthreshold == '-/-' || $patientthreshold == "-/-" ) ? $patientthreshold = '' : $patientthreshold;


           ($practicethreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'P';
           ($practicethreshold=='' ) ? $thresholdid = null : $thresholdid = $checkpatientthreshold[0]->id;

              Observation_Spirometer::where('device_id',$deviceid)
              ->where('patient_id',$patientid)
              ->where('mrn',$mrn_no)
              ->where('effdatetime',$recorddate)
              ->where('observation_id',$observationid)
              ->update(['threshold'=>$patientthreshold,
              'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid,
              'fev_threshold'=>$fev_threshold,'pef_threshold'=>$pef_threshold
            ]); 

            if($patientthreshold=="" || $patientthreshold=='' || $patientthreshold==null){  
            }
           else{
                if(($fev_value > $checkpatientthreshold[0]->spirometerfevhigh) || ($fev_value < $checkpatientthreshold[0]->spirometerfevlow ) || ( $pef_value > $checkpatientthreshold[0]->spirometerpefhigh ) || ($pef_value < $patientthreshold[0]->spirometerpeflow))
                {
                  Observation_Spirometer::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                    ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                }
            }
              
              
           
          
         }   
//--------------------------------------------------------------------threshold and alert status together range end------------------------------------------------------------------


   


     break;

     case 'f001'; 
     
     $vitaldevice = Devices::where('device_name','Glucose Monitor With Monthly Refills')->where('status',1)->first();
     // dd($vitaldevice);
     $vitaldeviceid = $vitaldevice->id;
    

     $a = $obs->valueQuantity;
     $value = $a->value;
     $unit = $a->unit;
     // dd($value,$unit);
     $insertdatagulcose = array(
                           'effdatetime'=> $recorddate,
                           'value'=>$value,
                           'unit'=> $unit,
                           'careplan_no' => null,
                           'device_id' => $deviceid,
                           'effdatetime'=> $recorddate,
                           'mrn'=>$mrn_no,
                           'value'=>$value ,
                           'unit'=>$unit,
                           'created_by'=>'Symtech',
                           'updated_by'=>'Symtech',
                           'patient_id'=>$patientid,
                           'reviewed_flag'=>0,
                           'reviewed_date'=>null,
                           'observation_id'=>$observationid,
                           'billing'=>0,
                           'alert_status'=>0,
                           'addressed'=>0,
                           'vital_device_id'=>$vitaldeviceid,
                           'threshold'=>null,
                           'threshold_type'=>null,
                           'threshold_id'=>null
                            );

                            $check = Observation_Glucose::where('device_id',$deviceid)->where('patient_id',$patientid)
                                     ->where('mrn',$mrn_no)->where('effdatetime',$recorddate)->where('observation_id',$observationid)->get(); 
                                       
                             if(count($check)==0){
                               $og = Observation_Glucose::create($insertdatagulcose); 
                             } 

           
  //  ----------------------------  threshold and alert status code------------------------------------------------------------------------------

        $checkpatientthreshold = PatientThreshold::where('patient_id',$patientid)
                                 ->whereNotNull('glucosehigh')
                                 ->whereNotNull('glucoselow')
                                 ->orderBy('created_at', 'desc')->get();         
     

        if($checkpatientthreshold->isEmpty())
        {
          

          $checkpractice = PatientProvider::where('patient_id',$patientid)->where('is_active',1)->where('provider_type_id',1)->first();
          if(!is_null($checkpractice)){
             $checkinpracticethreshold = PracticeThreshold::where('practice_id',$checkpractice->practice_id)
                                         ->whereNotNull('glucosehigh')
                                         ->whereNotNull('glucoselow')
                                         ->orderBy('created_at', 'desc')->get();
           } 
           
          
         
          
              if($checkpractice==null || $checkinpracticethreshold->isEmpty()){
                
                $orgid = Practices::where('id',$checkpractice->practice_id)->get(); //$orgid[0]->practice_group

                   if(!is_null($orgid)){

                     

                     $org = OrgThreshold::where('org_id',$orgid[0]['practice_group'])
                             ->whereNotNull('glucosehigh')
                             ->whereNotNull('glucoselow')
                             ->get();

                     if(!$org->isEmpty() ){
                       $orgthreshold = $org[0]->glucoselow."-".$org[0]->glucosehigh;
                       ($orgthreshold == null || $orgthreshold == 'null' || $orgthreshold == '-' || $orgthreshold == "-" ) ? $orgthreshold = '' : $orgthreshold;

                       ($orgthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'O';
                       ($orgthreshold=='' ) ? $thresholdid = null : $thresholdid = $org[0]->id;

                       Observation_Glucose::where('device_id',$deviceid)
                       ->where('patient_id',$patientid)
                       ->where('mrn',$mrn_no)
                       ->where('effdatetime',$recorddate)
                       ->where('observation_id',$observationid)
                       ->update(['threshold'=>$orgthreshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);

                       if(($value > $org[0]->glucosehigh) || ($value < $org[0]->glucoselow ))
                       {
                         Observation_Glucose::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                         ->where('effdatetime',$recorddate)->update(['alert_status'=>1]);
                       }
                   }
                   else{

                     $gt = GroupThreshold::orderBy('created_at', 'desc')
                           ->whereNotNull('glucosehigh')
                           ->whereNotNull('glucoselow')
                           ->get();

                     if(!$gt->isEmpty() ){

                       $glucose_group_threshold = $gt[0]->glucoselow."-".$gt[0]->glucosehigh;

                       ($glucose_group_threshold == null || $glucose_group_threshold == 'null' || $glucose_group_threshold == '-' || $glucose_group_threshold == "-" ) ? $glucose_group_threshold = '' : $glucose_group_threshold;

                       ($glucose_group_threshold=='' ) ? $thresholdtype = null : $thresholdtype = 'R';
                       ($glucose_group_threshold=='' ) ? $thresholdid = null : $thresholdid = $gt[0]->id;


                       Observation_Glucose::where('device_id',$deviceid)
                       ->where('patient_id',$patientid)
                       ->where('mrn',$mrn_no)
                       ->where('effdatetime',$recorddate)
                       ->where('observation_id',$observationid)
                       ->update(['threshold'=>$glucose_group_threshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);

                       //checking in groupthreshold  

                       // if($orgthreshold=="" || $orgthreshold=='' || $orgthreshold==null){ 
                       if($glucose_group_threshold=="" || $glucose_group_threshold=='' || $glucose_group_threshold==null){ 
                       }
                       else{
                            if(($value > $gt[0]->glucosehigh) || ($value < $gt[0]->glucoselow ))
                            {
                            Observation_Glucose::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                            ->where('effdatetime',$recorddate)->update(['alert_status'=>1]);
                            } 
                       }

                       




                     }   
                    

                   }


                   }
                   else{

                           $gt = GroupThreshold::orderBy('created_at', 'desc')
                           ->whereNotNull('glucosehigh')
                           ->whereNotNull('glucoselow')
                           ->get();

                     if(!$gt->isEmpty() ){

                       $glucose_group_threshold = $gt[0]->glucoselow."-".$gt[0]->glucosehigh;

                       ($glucose_group_threshold == null || $glucose_group_threshold == 'null' || $glucose_group_threshold == '-' || $glucose_group_threshold == "-" ) ? $glucose_group_threshold = '' : $glucose_group_threshold;
                      
                       ($glucose_group_threshold=='' ) ? $thresholdtype = null : $thresholdtype = 'R';
                       ($glucose_group_threshold=='' ) ? $thresholdid = null : $thresholdid = $gt[0]->id;



                       Observation_Glucose::where('device_id',$deviceid)
                       ->where('patient_id',$patientid)
                       ->where('mrn',$mrn_no)
                       ->where('effdatetime',$recorddate)
                       ->where('observation_id',$observationid)
                       ->update(['threshold'=>$glucose_group_threshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);

                       //checking in groupthreshold  
                       


                      if($orgthreshold=="" || $orgthreshold=='' || $orgthreshold==null){ 
                      }
                      else{
                        if(($value > $gt[0]->glucosehigh) || ($value < $gt[0]->glucoselow ))
                        {
                        Observation_Glucose::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                        ->where('effdatetime',$recorddate)->update(['alert_status'=>1]);
                        } 
                      }



                     }  

                   }

                  
             }
             else{
                     //checking in practicethreshold
                     $glucose_practice_threshold = $checkinpracticethreshold[0]->glucoselow."-".$checkinpracticethreshold[0]->glucosehigh;

                     ($glucose_practice_threshold == null || $glucose_practice_threshold == 'null' || $glucose_practice_threshold == '-' || $glucose_practice_threshold == "-" )   ? $glucose_practice_threshold = '' : $glucose_practice_threshold;                 
                     
                     // ($glucose_group_threshold=='' ) ? $thresholdtype = '' : $thresholdtype = 'Pr';
                     // ($glucose_group_threshold=='' ) ? $thresholdid = '' : $thresholdid = $checkinpracticethreshold[0]->id;
                     ($glucose_practice_threshold=='' ) ? $thresholdtype = '' : $thresholdtype = 'Pr';                       
                     ($glucose_practice_threshold=='' ) ? $thresholdid = '' : $thresholdid = $checkinpracticethreshold[0]->id;

                                                
                                                 Observation_Glucose::where('device_id',$deviceid)
                                                 ->where('patient_id',$patientid)
                                                 ->where('mrn',$mrn_no)
                                                 ->where('effdatetime',$recorddate)
                                                 ->where('observation_id',$observationid)
                                                 ->update(['threshold'=>$glucose_practice_threshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);
     
                    

                    if($glucose_practice_threshold=="" || $glucose_practice_threshold=='' || $glucose_practice_threshold==null){ 
                    }
                    else{
                      if(($value > $checkinpracticethreshold[0]->glucosehigh) || ($value < $checkinpracticethreshold[0]->glucoselow ))
                      {
                          Observation_Glucose::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                          ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                      } 
                    }



                 } 


              }
         else{       
        
               $glucose_patient_threshold = $checkpatientthreshold[0]->glucoselow."-".$checkpatientthreshold[0]->glucosehigh;
               ($glucose_patient_threshold == null || $glucose_patient_threshold == 'null' || $glucose_patient_threshold == '-' || $glucose_patient_threshold == "-" ) ? $glucose_patient_threshold = '' : $glucose_patient_threshold;

               // ($glucose_group_threshold=='' ) ? $thresholdtype = null : $thresholdtype = 'P';
               // ($glucose_group_threshold=='' ) ? $thresholdid = null : $thresholdid = $checkinpracticethreshold[0]->id;

               ($glucose_patient_threshold=='' ) ? $thresholdtype = null : $thresholdtype = 'P';
               ($glucose_patient_threshold=='' ) ? $thresholdid = null : $thresholdid = $checkpatientthreshold[0]->id;

                                             Observation_Glucose::where('device_id',$deviceid)
                                             ->where('patient_id',$patientid)
                                             ->where('mrn',$mrn_no)
                                             ->where('effdatetime',$recorddate)
                                             ->where('observation_id',$observationid)
                                             ->update(['threshold'=>$glucose_patient_threshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);
                
              if($glucose_patient_threshold=="" || $glucose_patient_threshold=='' || $glucose_patient_threshold==null){ 
              }
              else{
                  //checking in patient threshold
                  if(($value > $checkpatientthreshold[0]->glucosehigh) || ($value < $checkpatientthreshold[0]->glucoselow ))
                  {
                          Observation_Glucose::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                          ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                  }
              }
                
                



          }  
        
//  ----------------------------  threshold and alert status code end------------------------------------------------------------------------------  
        
     
     break;

     case 'weight-scale' : 
       // dd($obs);   
       $vitaldevice = Devices::where('device_name','Weighing Scale')->where('status',1)->first();
       $vitaldeviceid = $vitaldevice->id;

       $a = $obs->valueQuantity;
       $value = $a->value;
       $unit = $a->unit;
       $insertdataweightscale = array(
                           'effdatetime'=> $recorddate,
                           'weight'=>$value,
                           'unit'=> $unit,
                           'careplan_no' => null,
                           'device_id' => $deviceid,
                           'effdatetime'=> $recorddate,
                           'weight'=>$value,
                           'mrn'=>$mrn_no,
                           'unit'=> $unit,
                           'created_by'=>'Symtech',
                           'updated_by'=>'Symtech',
                           'patient_id'=>$patientid,
                           'reviewed_flag'=>0,
                           'reviewed_date'=>null,
                           'observation_id'=>$observationid,
                           'billing'=>0,
                           'vital_device_id'=>$vitaldeviceid,
                           'threshold'=>null,
                           'threshold_type'=>null,
                           'threshold_id'=>null
                           );

                           $check = Observation_Weight::where('device_id',$deviceid)->where('patient_id',$patientid)
                                    ->where('mrn',$mrn_no)->where('effdatetime',$recorddate)->where('observation_id',$observationid)->get(); 
                                                                  
                                     if(count($check)==0){
                                       $o = Observation_Weight::create($insertdataweightscale); 
                                     }  

         //-------------------------------------------------------------------------threshold ---------------------------------------------------------------------------------

         $checkpatientthreshold = PatientThreshold::where('patient_id',$patientid)
                                 ->whereNotNull('weightlow')
                                 ->whereNotNull('weighthigh')
                                 ->orderBy('created_at', 'desc')
                                 ->get();
                                 
         if($checkpatientthreshold->isEmpty())
         {
           $checkpractice = PatientProvider::where('patient_id',$patientid)->where('is_active',1)->where('provider_type_id',1)->first();
           
               if(!is_null($checkpractice)){
               
                 $checkinpracticethreshold = PracticeThreshold::where('practice_id',$checkpractice->practice_id)
                                             ->whereNotNull('weightlow')
                                             ->whereNotNull('weighthigh')
                                             ->orderBy('created_at', 'desc')->get();
               } 
          
           
               if($checkpractice==null || $checkinpracticethreshold->isEmpty()){
                 
                 $orgid = Practices::where('id',$checkpractice->practice_id)->get(); //$orgid[0]->practice_group

                 if(!is_null($orgid)){

                     $orgcheck = OrgThreshold::get();
                     if(!$orgcheck->isEmpty()){
                           $org = OrgThreshold::where('org_id',$orgid[0]['practice_group'])
                           ->whereNotNull('weightlow')
                           ->whereNotNull('weighthigh')
                           ->get();
                       

                     if(!$org->isEmpty() ){

                     $orgthreshold = $org[0]->weightlow."-".$org[0]->weighthigh;
                     ($orgthreshold == null || $orgthreshold == 'null' || $orgthreshold == '-' || $orgthreshold == "-" ) ? $orgthreshold = '' : $orgthreshold;


                     ($orgthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'O';
                     ($orgthreshold=='' ) ? $thresholdid = null : $thresholdid = $org[0]->id;


                     Observation_Weight::where('device_id',$deviceid)
                     ->where('patient_id',$patientid)
                     ->where('mrn',$mrn_no)
                     ->where('effdatetime',$recorddate)
                     ->where('observation_id',$observationid)
                     ->update(['threshold'=>$orgthreshold,'threshold_type'=>$orgthreshold,'threshold_id'=>$thresholdid]);

                    if($orgthreshold=="" || $orgthreshold=='' || $orgthreshold==null){                         
                    }
                    else{
                      if(($value > $org[0]->weighthigh) || ($value < $org[0]->weightlow ) )
                      {
                        Observation_Weight::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                          ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                      }
                    }


                    

                     }
                     else{
                       $gt = GroupThreshold::whereNotNull('weightlow')->whereNotNull('weighthigh')->orderBy('created_at', 'desc')->get();
                     
                     if(!$gt->isEmpty() ){
                     $grpthreshold = $gt[0]->weightlow."-".$gt[0]->weighthigh; 
                     ($grpthreshold == null || $grpthreshold == 'null' || $grpthreshold == '-' || $grpthreshold == "-" ) ? $grpthreshold = '' : $grpthreshold;

                     ($grpthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'R';
                     ($grpthreshold=='' ) ? $thresholdid = null : $thresholdid = $gt[0]->id;
               
                     Observation_Weight::where('device_id',$deviceid)
                     ->where('patient_id',$patientid)
                     ->where('mrn',$mrn_no)
                     ->where('effdatetime',$recorddate)
                     ->where('observation_id',$observationid)
                     ->update(['threshold'=>$grpthreshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid ]);

                    if($grpthreshold=="" || $grpthreshold=='' || $grpthreshold==null){                         
                    }
                    else{
                      if(($value > $gt[0]->weighthigh) || ($value < $gt[0]->weightlow ) )
                      {
                        Observation_Weight::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                          ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                      }
                    }

                    

                   }
                     }

                   } 



                   if($orgid==null || $orgid->isEmpty() ){

                     $gt = GroupThreshold::whereNotNull('weightlow')->whereNotNull('weighthigh')->orderBy('created_at', 'desc')->get();
                     
                     if(!$gt->isEmpty() ){
                     $grpthreshold = $gt[0]->weightlow."-".$gt[0]->weighthigh; 

                     ($grpthreshold == null || $grpthreshold == 'null' || $grpthreshold == '-' || $grpthreshold == "-" ) ? $grpthreshold = '' : $grpthreshold;
               
                     ($grpthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'R';
                     ($grpthreshold=='' ) ? $thresholdid = null : $thresholdid = $gt[0]->id;
                     
                     Observation_Weight::where('device_id',$deviceid)
                     ->where('patient_id',$patientid)
                     ->where('mrn',$mrn_no)
                     ->where('effdatetime',$recorddate)
                     ->where('observation_id',$observationid)
                     ->update(['threshold'=>$grpthreshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid ]);

                     

                    if($grpthreshold=="" || $grpthreshold=='' || $grpthreshold==null){                         
                    }
                    else{
                      if(($value > $gt[0]->weighthigh) || ($value < $gt[0]->weightlow ) )
                     {
                       Observation_Weight::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                         ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                     }
                    }


                   }

                   }


                 

                 //checking in groupthreshold  
                
               }
               else{

                 $practicethreshold = $checkinpracticethreshold[0]->weightlow."-".$checkinpracticethreshold[0]->weighthigh;
                 ($practicethreshold == null || $practicethreshold == 'null' || $practicethreshold == '-' || $practicethreshold == "-" ) ? $practicethreshold = '' : $practicethreshold;

                 ($practicethreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'Pr';
                 ($practicethreshold=='' ) ? $thresholdid = null : $thresholdid = $checkinpracticethreshold[0]->id;


                 Observation_Weight::where('device_id',$deviceid)
                  ->where('patient_id',$patientid)
                  ->where('mrn',$mrn_no)
                  ->where('effdatetime',$recorddate)
                  ->where('observation_id',$observationid)
                  ->update(['threshold'=>$practicethreshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);

                     

                    if($practicethreshold=="" || $practicethreshold=='' || $practicethreshold==null){                         
                    }
                    else{
                      if(($value > $checkinpracticethreshold[0]->weighthigh) || ($value < $checkinpracticethreshold[0]->weightlow ) )
                      {
                        Observation_Weight::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                        ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                      }
                    }
                    
               } 
         
             }
       }
         else{  

           $patientthreshold = $checkpatientthreshold[0]->weightlow."-".$checkpatientthreshold[0]->weighthigh;
           
           ($patientthreshold == null || $patientthreshold == 'null' || $patientthreshold == '-' || $patientthreshold == "-" ) ? $patientthreshold = '' : $patientthreshold;

           ($patientthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'P';
           ($patientthreshold=='' ) ? $thresholdid = null : $thresholdid = $checkpatientthreshold[0]->id;

              Observation_Weight::where('device_id',$deviceid)
              ->where('patient_id',$patientid)
              ->where('mrn',$mrn_no)
              ->where('effdatetime',$recorddate)
              ->where('observation_id',$observationid)
              ->update(['threshold'=>$patientthreshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);
              
              

              if($patientthreshold=="" || $patientthreshold=='' || $patientthreshold==null){                         
              }
              else{
              if(($value > $checkpatientthreshold[0]->weighthigh) || ($value < $checkpatientthreshold[0]->weightlow ) )
               {
                 Observation_Weight::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                 ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
               }
              }
           
          
         } 
//--------------------------------------------------------------------threshold and alert status range end------------------------------------------------------------------

           
           $check = Observation_Weight::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                   ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->get(); 

           if($check->isEmpty()){
           }
           else{
             if($check[0]->weight==null || $check[0]->unit==''){ 
               $obsweight =  Observation_Weight::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
               ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update($upweightdata);
               
              } 
           }

         
     break;

     case 'blood-pressure' :   
       // dd('blood prs');
       $a = $obs->component;
       $recorddate = date('Y-m-d H:i:s', strtotime($obs->effectiveDateTime)); 
       foreach($a as $akey=>$avalue){
         $code = $avalue->code;
         $coding =$code->coding[0];
         $dis = $coding->display;

//-------------------------------------------------------------------------only threshold code --------------------------------------------------------------------------------

         $checkpatientthreshold = PatientThreshold::where('patient_id',$patientid)
                                 ->whereNotNull('systoliclow')
                                 ->whereNotNull('systolichigh')
                                 ->whereNotNull('diastoliclow')
                                 ->whereNotNull('diastolichigh')  
                                 ->orderBy('created_at', 'desc')
                                 ->get();

                                 // dd($patientid,$checkpatientthreshold);


         if($checkpatientthreshold->isEmpty())
         {
           // echo "if patienthreshold is empty";
           $checkpractice = PatientProvider::where('patient_id',$patientid)->where('is_active',1)->where('provider_type_id',1)->first();
           
           if(!is_null($checkpractice)){
           
             $checkinpracticethreshold = PracticeThreshold::where('practice_id',$checkpractice->practice_id)
                                       ->whereNotNull('systoliclow')
                                       ->whereNotNull('systolichigh')
                                       ->whereNotNull('diastoliclow')
                                       ->whereNotNull('diastolichigh')  
                                       ->orderBy('created_at', 'desc')
                                       ->get();
           } 
          
           
               if($checkpractice==null || $checkinpracticethreshold->isEmpty()){
                 
                 

                 $orgid = Practices::where('id',$checkpractice->practice_id)->get(); //$orgid[0]['practice_group']

                 if(!is_null($orgid)){

                     $org = OrgThreshold::where('org_id',$orgid[0]['practice_group'])->get();

                     

                     if(!$org->isEmpty() ){
                     $orgthreshold = $org[0]->systoliclow."-".$org[0]->systolichigh."/".$org[0]->diastoliclow."-".$org[0]->diastolichigh;

                     $systolic_threshold = $org[0]->systoliclow."-".$org[0]->systolichigh;
                     $diastolic_threshold = $org[0]->diastoliclow."-".$org[0]->diastolichigh;

                     ($orgthreshold == null || $orgthreshold == 'null' || $orgthreshold == '-/-' || $orgthreshold == "-/-" ) ? $orgthreshold = '' : $orgthreshold;

                     ($orgthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'O';
                     ($orgthreshold=='' ) ? $thresholdid = null : $thresholdid = $org[0]->id;

                     Observation_BP::where('device_id',$deviceid)
                     ->where('patient_id',$patientid)
                     ->where('mrn',$mrn_no)
                     ->where('effdatetime',$recorddate)
                     ->where('observation_id',$observationid)
                     ->update(['threshold'=>$orgthreshold,'threshold_type'=>$thresholdtype,
                     'threshold_id'=>$thresholdid,'systolic_threshold'=>$systolic_threshold,'diastolic_threshold'=>$diastolic_threshold ]);

                     }
                     else{


                       $gt = GroupThreshold::whereNotNull('systoliclow')
                             ->whereNotNull('systolichigh')
                             ->whereNotNull('diastoliclow')
                             ->whereNotNull('diastolichigh')
                             ->orderBy('created_at', 'desc')->get(); 

                     if(!$gt->isEmpty() ){
                       
                       $orgthreshold = $gt[0]->systoliclow."-".$gt[0]->systolichigh."/".$gt[0]->diastoliclow."-".$gt[0]->diastolichigh;

                       $systolic_threshold = $gt[0]->systoliclow."-".$gt[0]->systolichigh;
                       $diastolic_threshold = $gt[0]->diastoliclow."-".$gt[0]->diastolichigh;

                       ($orgthreshold == null || $orgthreshold == 'null' || $orgthreshold == '-/-' || $orgthreshold == "-/-" ) ? $orgthreshold = '' : $orgthreshold;
 
                      ($orgthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'R';
                      ($orgthreshold=='' ) ? $thresholdid = null : $thresholdid = $gt[0]->id;

                       Observation_BP::where('device_id',$deviceid)
                         ->where('patient_id',$patientid)
                         ->where('mrn',$mrn_no)
                         ->where('effdatetime',$recorddate)
                         ->where('observation_id',$observationid)
                         ->update(['threshold'=>$orgthreshold,'threshold_type'=>$thresholdtype,
                         'threshold_id'=>$thresholdid,'systolic_threshold'=>$systolic_threshold,'diastolic_threshold'=>$diastolic_threshold ]); 

                       }

                     }
                     // if(($fev_value > $org[0]->spirometerfevhigh) || ($fev_value < $org[0]->spirometerfevlow ) || ( $pef_value > $org[0]->spirometerpefhigh ) || ($pef_value < $org[0]->spirometerpeflow))
                     // {
                     //   Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                     //     ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                     // }



                 }

                 // if($orgid==null || $orgid->isEmpty() ){

                  

                     // if(($fev_value > $gt[0]->spirometerfevhigh) || ($fev_value < $gt[0]->spirometerfevlow ) || ( $pef_value > $gt[0]->spirometerpefhigh ) || ($pef_value < $gt[0]->spirometerpeflow))
                     // {
                     //   Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                     //     ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                     // }

                 // }

                 //checking in groupthreshold  
                
               }
               else{

                 $practicethreshold = $checkinpracticethreshold[0]->systoliclow."-".$checkinpracticethreshold[0]->systolichigh."/".$checkinpracticethreshold[0]->diastoliclow."-".$checkinpracticethreshold[0]->diastolichigh;
                 
                 $systolic_threshold = $checkinpracticethreshold[0]->systoliclow."-".$checkinpracticethreshold[0]->systolichigh;
                 $diastolic_threshold = $checkinpracticethreshold[0]->diastoliclow."-".$checkinpracticethreshold[0]->diastolichigh;
           
                 ($practicethreshold == null || $practicethreshold == 'null' || $practicethreshold == '-/-' || $practicethreshold == "-/-" ) ? $practicethreshold = '' : $practicethreshold;
                 ($practicethreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'Pr';
                 ($practicethreshold=='' ) ? $thresholdid = null : $thresholdid = $checkinpracticethreshold[0]->id;


                 Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                  ->where('effdatetime',$recorddate)->where('observation_id',$observationid)
                  ->update(['threshold'=>$practicethreshold,
                  'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid,
                  'systolic_threshold'=>$systolic_threshold,'diastolic_threshold'=>$diastolic_threshold
                ]);
                    
               } 
         }
         else{

           // echo "else systoliclow";

           $patientthreshold = $checkpatientthreshold[0]->systoliclow."-".$checkpatientthreshold[0]->systolichigh."/".$checkpatientthreshold[0]->diastoliclow."-".$checkpatientthreshold[0]->diastolichigh;
           
           $systolic_threshold = $checkpatientthreshold[0]->systoliclow."-".$checkpatientthreshold[0]->systolichigh;
           $diastolic_threshold = $checkpatientthreshold[0]->diastoliclow."-".$checkpatientthreshold[0]->diastolichigh; 

           ($patientthreshold == null || $patientthreshold == 'null' || $patientthreshold == '-/-' || $patientthreshold == "-/-" ) ? $patientthreshold = '' : $patientthreshold;
           ($patientthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'P';
           ($patientthreshold=='' ) ? $thresholdid = null : $thresholdid = $checkpatientthreshold[0]->id;

           Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
             ->where('effdatetime',$recorddate)->where('observation_id',$observationid)
             ->update(['threshold'=>$patientthreshold,'threshold_type'=>$thresholdtype,
             'threshold_id'=>$thresholdid,'systolic_threshold'=>$systolic_threshold,'diastolic_threshold'=>$diastolic_threshold ]);
           
          
         } 

//-------------------------------------------------------------------------------threshold end---------------------------------------------------------------------------

         
         if($dis == 'Systolic blood pressure'){
           // dd('Systolic blood pressure');
             $v = $avalue->valueQuantity;
             $systolicvalue = $v->value;
             $systolicunit  = $v->unit;
             $systoliccode  = $v->code; 
             //after inserting update processedflag = 1
             $upsysdata = array(
                                 'effdatetime'=> $recorddate,
                                 'systolic_qty'=>$systolicvalue,
                                 'systolic_unit'=> $systolicunit,
                                 'systolic_code'=> $systoliccode,
                                  );
                                 //  dd($upsysdata);  
            
             

             $checkbp = Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
             ->where('effdatetime',$recorddate)->get();
             // dd( $checkbp);
             if($checkbp->isEmpty()){
             }
             else{
               if($checkbp[0]->systolic_qty==null || $checkbp[0]->systolic_qty==''){ 
                 $obsys =  Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                 ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update($upsysdata);
               
                } 
             }


//  ----------------------------  alert status code------------------------------------------------------------------------------

             $checkpatientthreshold = PatientThreshold::where('patient_id',$patientid)
             ->whereNotNull('systoliclow')
             ->whereNotNull('systolichigh')
             // ->whereNotNull('diastoliclow')
             // ->whereNotNull('diastolichigh')
             ->orderBy('created_at', 'desc')
             ->get();



             if($checkpatientthreshold->isEmpty())
             {
               $checkpractice = PatientProvider::where('patient_id',$patientid)->where('is_active',1)->where('provider_type_id',1)->first();
               
               if(!is_null($checkpractice)){
               
                 $checkinpracticethreshold = PracticeThreshold::where('practice_id',$checkpractice->practice_id)
                                             ->whereNotNull('systoliclow')
                                             ->whereNotNull('systolichigh')
                                             ->orderBy('created_at', 'desc')
                                             ->get();
               } 
              
               
               
               
                   if($checkpractice==null || $checkinpracticethreshold->isEmpty()){

                     $orgid = Practices::where('id',$checkpractice->practice_id)->get(); //$orgid[0]['practice_group']

                     if(!is_null($orgid)){

                       $orgcheck = OrgThreshold::get();

                       if(!$orgcheck->isEmpty() ){

                       $org = OrgThreshold::where('org_id',$orgid[0]['practice_group'])
                             ->whereNotNull('systoliclow')
                             ->whereNotNull('systolichigh')->get();
                       

                               if(!$org->isEmpty() ){
                                 
                                 if(($systolicvalue > $org[0]->systolichigh) || ($systolicvalue < $org[0]->systoliclow ))
                                 {
                                   Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                                   ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                                 }

                                
                               }
                               else{

                                 $gt = GroupThreshold::whereNotNull('systoliclow')
                                       ->whereNotNull('systolichigh')
                                       ->orderBy('created_at', 'desc')
                                       ->get();

                                         //checking in groupthreshold  
                                       if(!$gt->isEmpty() ){
                                         if(($systolicvalue > $gt[0]->systolichigh) || ($systolicvalue < $gt[0]->systoliclow ))
                                         {
                 
                                           Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                                         ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                                         }
                                       }
                               
                               


                               }
                             }

                     }

                     // if($orgid==null || $orgid->isEmpty() ){

                       
                     // }
                     
                     
                     


                   }
                   else{
                    
                    if($checkinpracticethreshold[0]->systolichigh=="" || $checkinpracticethreshold[0]->systolichigh==''
                     || $checkinpracticethreshold[0]->systolichigh==null || $checkinpracticethreshold[0]->systoliclow == "" ||
                      $checkinpracticethreshold[0]->systoliclow == '' || $checkinpracticethreshold[0]->systoliclow ==null){
                    }
                    else{

                      //checking in practicethreshold
                      if(($systolicvalue > $checkinpracticethreshold[0]->systolichigh) || ($systolicvalue < $checkinpracticethreshold[0]->systoliclow ))
                      {
                        Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                      ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                      }

                    }
                         
                   } 
             }
             else{

               $a = $checkpatientthreshold[0]->systoliclow."-".$checkpatientthreshold[0]->systolichigh;

               $systolic_threshold = $checkpatientthreshold[0]->systoliclow."-".$checkpatientthreshold[0]->systolichigh;
               $diastolic_threshold = $checkpatientthreshold[0]->diastoliclow."-".$checkpatientthreshold[0]->diastolichigh; 

               ($a == null || $a == 'null' || $a == '-/-' || $a == "-/-" ) ? $a = '' : $a;
               ($a=='') ? $thresholdtype = null : $thresholdtype = 'P';
               ($a=='') ? $thresholdid = null : $thresholdid = $checkpatientthreshold[0]->id;
              
               
               Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                ->where('effdatetime',$recorddate)->where('observation_id',$observationid)
                ->update(['threshold'=>$a,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid,
                'systolic_threshold'=>$systolic_threshold,'diastolic_threshold'=>$diastolic_threshold]);
               

                if($a == null || $a == 'null' || $a == '' || $a == ""){                    
                }
                else{
                    //checking in patient threshold
                    if(($systolicvalue > $checkpatientthreshold[0]->systolichigh) || ($systolicvalue < $checkpatientthreshold[0]->systoliclow ))
                    {
                      Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                      ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                    }
                }  
               
                


             }    

//  ----------------------------  alert status code------------------------------------------------------------------------------            
            
         

         }
         else{
           $v = $avalue->valueQuantity;
           $diastolicvalue = $v->value;
           $diastolicunit  = $v->unit;
           $diastoliccode  = $v->code; 
            //after inserting update processedflag = 1
            $updiasdata = array(
             'effdatetime'=> $recorddate,
              'diastolic_qty'=>$diastolicvalue,
              'diastolic_unit'=>$diastolicunit,
              'diastolic_code'=> $diastoliccode,
             );
            $checkbp = Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
            ->where('observation_id',$observationid)
            ->where('effdatetime',$recorddate)->get();
            
              if($checkbp->isEmpty()){
              }
              else{
               if($checkbp[0]->diastolic_qty==null || $checkbp[0]->diastolic_qty==''){
                 $obdia =  Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)
                 ->where('mrn',$mrn_no)
                 ->where('effdatetime',$recorddate)
                 ->where('observation_id',$observationid)
                 ->update($updiasdata);  
                }
              }  
             



  //  ----------------------------  alert status code------------------------------------------------------------------------------

        $checkpatientthreshold = PatientThreshold::where('patient_id',$patientid)
                                 ->whereNotNull('diastoliclow')
                                 ->whereNotNull('diastolichigh')
                                 ->orderBy('created_at', 'desc')->get();


        if($checkpatientthreshold->isEmpty())
        {
               $checkpractice = PatientProvider::where('patient_id',$patientid)->where('is_active',1)->where('provider_type_id',1)->first();

               if(!is_null($checkpractice)){
                   $checkinpracticethreshold = PracticeThreshold::where('practice_id',$checkpractice->practice_id)
                                               ->whereNotNull('diastoliclow')
                                               ->whereNotNull('diastolichigh')
                                               ->orderBy('created_at', 'desc')
                                               ->get();
                 } 
          
         
          
              if($checkpractice==null || $checkinpracticethreshold->isEmpty()){

                   $orgid = Practices::where('id',$checkpractice->practice_id)->get(); //$orgid[0]->practice_group
                   // dd($orgid);
                   if(!is_null($orgid)){


                     $orgcheck = OrgThreshold::get();

                     if(!$orgcheck->isEmpty() ){
                     $org = OrgThreshold::where('org_id',$orgid[0]->practice_group)
                             ->whereNotNull('diastoliclow')
                             ->whereNotNull('diastolichigh')
                             ->get();
                     

                     if(!$org->isEmpty() ){

                      if($org[0]->diastolichigh == null || $org[0]->diastolichigh == 'null' || $org[0]->diastolichigh == '' ||
                        $org[0]->diastolichigh == "" || $org[0]->diastoliclow == 'null' 
                        || $org[0]->diastoliclow == "" || $org[0]->diastoliclow == ''){                    
                      }
                      else{
                        if(($diastolicvalue > $org[0]->diastolichigh) || ($diastolicvalue < $org[0]->diastoliclow ))
                        {
                            Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                            ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                        }
                      }
                       


                     }
                     else{

                       $gt = GroupThreshold::whereNotNull('diastoliclow')->whereNotNull('diastolichigh')->orderBy('created_at', 'desc')->get();
                       

                       if($gt[0]->diastolichigh==null || $gt[0]->diastolichigh=='' || $gt[0]->diastolichigh=="" || 
                         $gt[0]->diastoliclow==null  || $gt[0]->diastoliclow=='' || $gt[0]->diastoliclow==""){

                         }
                       else{
                         //checking in groupthreshold  
                         if(($diastolicvalue > $gt[0]->diastolichigh) || ($diastolicvalue < $gt[0]->diastoliclow ))
                         {
                           Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                           ->where('effdatetime',$recorddate)
                           ->where('observation_id',$observationid)
                           ->update(['alert_status'=>1]);
                         }
                       } 

                     }
                   }

                   }

                   // if($orgid==null || $orgid->isEmpty() ){

                     

                   // }
                   
              }
              else{

                if($checkinpracticethreshold[0]->diastolichigh==null || $checkinpracticethreshold[0]->diastolichigh==''
                 || $checkinpracticethreshold[0]->diastolichigh=="" || 
                $checkinpracticethreshold[0]->diastoliclow==null  || $checkinpracticethreshold[0]->diastoliclow=='' || 
                $checkinpracticethreshold[0]->diastoliclow==""){
                  
                }else{
                   //checking in practicethreshold
                   if(($diastolicvalue > $checkinpracticethreshold[0]->diastolichigh) || ($diastolicvalue < $checkinpracticethreshold[0]->diastoliclow ))
                   {
                     Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                      ->where('effdatetime',$recorddate)
                      ->where('observation_id',$observationid)
                      ->update(['alert_status'=>1]);
                   }
                }
                   
              } 
        }
        else{

          if($checkpatientthreshold[0]->diastolichigh==null || $checkpatientthreshold[0]->diastolichigh==''
                 || $checkpatientthreshold[0]->diastolichigh=="" || 
                $checkpatientthreshold[0]->diastoliclow==null  || $checkpatientthreshold[0]->diastoliclow=='' || 
                $checkpatientthreshold[0]->diastoliclow==""){                    
                }else{
                   
                    //checking in patient threshold
                  if(($diastolicvalue > $checkpatientthreshold[0]->diastolichigh) || ($diastolicvalue < $checkpatientthreshold[0]->diastoliclow ))
                  {
                    Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                      ->where('effdatetime',$recorddate)
                      ->where('observation_id',$observationid)
                      ->update(['alert_status'=>1]);
                  }
                }



           
        }  
        
//  ----------------------------  alert status code------------------------------------------------------------------------------  
        
      
       } 
     }

     break;  


     case'satO2' :
         $recorddate = date('Y-m-d H:i:s', strtotime($obs->effectiveDateTime)); 
         $code = $obs->code;
         $coding =$code->coding[0];
         $dis = $coding->display;
         if($dis == 'Hemoglobin saturation with oxygen'){
           $v = $obs->valueQuantity;
           $o2value = $v->value;
           $o2unit  = $v->unit;
           $o2code  = $v->code; 
            //after inserting update processedflag = 1
            $checko2 = Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
            ->where('effdatetime',$recorddate)->get();
            $updiasdata = array(
             'effdatetime'=> $recorddate,
              'oxy_qty'=>$o2value,
              'oxy_unit'=>$o2unit,
              'oxy_code'=> $o2code,
             );
           
             if($checko2->isEmpty()){
             }
             else{
               if($checko2[0]->oxy_qty==null || $checko2[0]->oxy_qty==''){
                 $obdia =  Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)
                 ->where('mrn',$mrn_no)
                 ->where('effdatetime',$recorddate)
                 ->where('observation_id',$observationid)
                 ->update($updiasdata);  
                 // dd($obdia);    
                }
             }
          
        
  //  ----------------------------  alert status code------------------------------------------------------------------------------

        // dd($patientid);
       $checkpatientthreshold = PatientThreshold::where('patient_id',$patientid)
                                 ->whereNotNull('oxsathigh')
                                 ->whereNotNull('oxsatlow')
                                 ->orderBy('created_at', 'desc')->get();
        //  dd($checkpatientthreshold);


       if($checkpatientthreshold->isEmpty()) 
       {
        //  echo "in practice threshold else"; 

         $checkpractice = PatientProvider::where('patient_id',$patientid)->where('is_active',1)->where('provider_type_id',1)->first();

        //  dd($checkpractice);

             if(!is_null($checkpractice)){

               $checkinpracticethreshold = PracticeThreshold::where('practice_id',$checkpractice->practice_id)
               ->whereNotNull('oxsathigh')
               ->whereNotNull('oxsatlow')
               ->orderBy('created_at', 'desc')
               ->get();

             } 
            
             
         
            
              if($checkpractice==null || $checkinpracticethreshold->isEmpty()){
                // echo "in practice threshold";
                
                  $orgid = Practices::where('id',$checkpractice->practice_id)->get(); //$orgid[0]->practice_group
                
                
               

             

               if(!is_null($orgid)){

                //  echo "in org threshold else";

                 $orgcheck = OrgThreshold::get();
                 // dd($orgcheck);

                 if(!$orgcheck->isEmpty() ){
                   // echo "not empty orgcheck";

                 $org = OrgThreshold::where('org_id',$orgid[0]->practice_group)
                       ->whereNotNull('oxsathigh')
                       ->whereNotNull('oxsatlow')
                       ->get();
                 
                       if(!$org->isEmpty() ){
                         $orgthreshold = $org[0]->oxsatlow."-".$org[0]->oxsathigh;
                         
                        
                         ($orgthreshold == null || $orgthreshold == 'null' || $orgthreshold == '-' || $orgthreshold == "-" ) ? $orgthreshold = '' : $orgthreshold;

                         ( $orgthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'O';
                         ( $orgthreshold=='' ) ? $thresholdid = null : $thresholdid = $org[0]->id;

                         Observation_Oxymeter::where('device_id',$deviceid)
                         ->where('patient_id',$patientid)
                         ->where('mrn',$mrn_no)
                         ->where('effdatetime',$recorddate)
                         ->where('observation_id',$observationid)
                         ->update(['threshold'=>$orgthreshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);
 
                         if(($o2value > $org[0]->oxsathigh) || ($o2value < $org[0]->oxsatlow ) )
                         {
                           Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                             ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                         }

                         if($orgthreshold=="" || $orgthreshold=='' || $orgthreshold==null){                         
                        }
                        else{
 
                            if(($fev_value > $org[0]->spirometerfevhigh) || ($fev_value < $org[0]->spirometerfevlow ) || ( $pef_value > $org[0]->spirometerpefhigh ) || ($pef_value < $org[0]->spirometerpeflow))
                            {
                              Observation_Spirometer::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                                ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                            }
             
                        }


                       }
                       else{

                         // echo "ander wala grp threshold else";
                        
                         $gt = GroupThreshold::whereNotNull('oxsathigh')
                               ->whereNotNull('oxsatlow')
                               ->orderBy('created_at', 'desc')
                               ->get();



                         if(!$gt->isEmpty() ){
                             $oxy_group_threshold = $gt[0]->oxsatlow."-".$gt[0]->oxsathigh;
                             ($oxy_group_threshold == null || $oxy_group_threshold == 'null' || $oxy_group_threshold == '-' || $oxy_group_threshold == "-" ) ? $oxy_group_threshold = '' : $oxy_group_threshold;

                             ( $oxy_group_threshold=='' ) ? $thresholdtype = null : $thresholdtype = 'R';
                             ( $oxy_group_threshold=='' ) ? $thresholdid = null : $thresholdid = $gt[0]->id;

                                                   Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)
                                                   ->where('mrn',$mrn_no)
                                                   ->where('effdatetime',$recorddate)
                                                   ->where('observation_id',$observationid)
                                                   ->update(['threshold'=>$oxy_group_threshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);
           
                             if($oxy_group_threshold=="" || $oxy_group_threshold=='' || $oxy_group_threshold==null){                         
                              }
                              else{
                                  //checking in groupthreshold  
                                  if(($o2value > $gt[0]->oxsathigh) || ($o2value < $gt[0]->oxsatlow ))
                                  {
                                    Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                                  ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                                  }
                              }
                           
                         } 


                         


                       }

                     }
                     else{

                       // echo "bahar wala grpthreshold emptyorgcheck wala else";
            
                       $gt = GroupThreshold::whereNotNull('oxsathigh')
                               ->whereNotNull('oxsatlow')
                               ->orderBy('created_at', 'desc')
                               ->get();

                         if(!$gt->isEmpty() ){
                             $oxy_group_threshold = $gt[0]->oxsatlow."-".$gt[0]->oxsathigh;
                             ($oxy_group_threshold == null || $oxy_group_threshold == 'null' || $oxy_group_threshold == '-' || $oxy_group_threshold == "-" ) ? $oxy_group_threshold = '' : $oxy_group_threshold;

                             ( $oxy_group_threshold=='' ) ? $thresholdtype = null : $thresholdtype = 'R';
                             ( $oxy_group_threshold=='' ) ? $thresholdid = null : $thresholdid = $gt[0]->id;

                                                   Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)
                                                   ->where('mrn',$mrn_no)
                                                   ->where('effdatetime',$recorddate)
                                                   ->where('observation_id',$observationid)
                                                   ->update(['threshold'=>$oxy_group_threshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid ]);
           
                           
                             if($oxy_group_threshold=="" || $oxy_group_threshold=='' || $oxy_group_threshold==null){                         
                             }
                             else{
                                  //checking in groupthreshold  
                                  if(($o2value > $gt[0]->oxsathigh) || ($o2value < $gt[0]->oxsatlow ))
                                  {
                                    Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                                  ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                                  } 
                             }
                          
                         } 
                     }

                
               }
               else{
                 // echo "bahar wala else";
            
                 $gt = GroupThreshold::whereNotNull('oxsathigh')
                               ->whereNotNull('oxsatlow')
                               ->orderBy('created_at', 'desc')
                               ->get();

                         if(!$gt->isEmpty() ){
                             $oxy_group_threshold = $gt[0]->oxsatlow."-".$gt[0]->oxsathigh;
                             ($oxy_group_threshold == null || $oxy_group_threshold == 'null' || $oxy_group_threshold == '-' || $oxy_group_threshold == "-" ) ? $oxy_group_threshold = '' : $oxy_group_threshold;

                             ( $oxy_group_threshold=='' ) ? $thresholdtype = null : $thresholdtype = 'R';
                             ( $oxy_group_threshold=='' ) ? $thresholdid = null : $thresholdid = $gt[0]->id;

                                 Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)
                                 ->where('mrn',$mrn_no)
                                 ->where('effdatetime',$recorddate)
                                 ->where('observation_id',$observationid)
                                 ->update(['threshold'=>$oxy_group_threshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);
           
                                 if($oxy_group_threshold=="" || $oxy_group_threshold=='' || $oxy_group_threshold==null){                         
                                }
                                else{
                                     //checking in groupthreshold  
                                      
                                    if(($o2value > $gt[0]->oxsathigh) || ($o2value < $gt[0]->oxsatlow ))
                                    {
                                      Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                                    ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                                    } 
                                }
                          
                         } 
               }

              

               
             }
             else{

               // echo "bahar practice threshold else";
            
                   $oxy_practice_threshold = $checkinpracticethreshold[0]->oxsatlow."-".$checkinpracticethreshold[0]->oxsathigh;
                   ($oxy_practice_threshold == null || $oxy_practice_threshold == 'null' || $oxy_practice_threshold == '-' || $oxy_practice_threshold == "-" ) ? $oxy_practice_threshold = '' : $oxy_practice_threshold;

                   ( $oxy_practice_threshold=='' ) ? $thresholdtype = null : $thresholdtype = 'Pr';
                   ( $oxy_practice_threshold=='' ) ? $thresholdid = null : $thresholdid = $checkinpracticethreshold[0]->id;

                     Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)
                     ->where('mrn',$mrn_no)
                     ->where('effdatetime',$recorddate)
                     ->where('observation_id',$observationid)
                     ->update(['threshold'=>$oxy_practice_threshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid ]);

                   //checking in practicethreshold
                   if(($o2value > $checkinpracticethreshold[0]->oxsathigh) || ($o2value < $checkinpracticethreshold[0]->oxsatlow ))
                   {
                     Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                   ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                   }

                   if($oxy_practice_threshold=="" || $oxy_practice_threshold=='' || $oxy_practice_threshold==null){                         
                  }
                  else{
                       //checking in groupthreshold  
                        
                      if(($o2value > $gt[0]->oxsathigh) || ($o2value < $gt[0]->oxsatlow ))
                      {
                        Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                      ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                      } 
                  }


             } 
       }
       else{

       
               
           //checking in patient threshold
         $oxy_patient_threshold = $checkpatientthreshold[0]->oxsatlow."-".$checkpatientthreshold[0]->oxsathigh;

         ($oxy_patient_threshold == null || $oxy_patient_threshold == 'null' || $oxy_patient_threshold == '-' || $oxy_patient_threshold == "-" ) ? $oxy_patient_threshold = '' : $oxy_patient_threshold;

         ( $oxy_patient_threshold=='' ) ? $thresholdtype = null : $thresholdtype = 'P';
         ( $oxy_patient_threshold=='' ) ? $thresholdid = null : $thresholdid = $checkpatientthreshold[0]->id;
               
         
         Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)
               ->where('mrn',$mrn_no)
               ->where('effdatetime',$recorddate)
               ->where('observation_id',$observationid)
               ->update(['threshold'=>$oxy_patient_threshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);


               if($thresholdtype=="" || $thresholdtype=='' || $thresholdtype==null){                         
                }
              else{
                   //checking in groupthreshold  
                    
                   if(($o2value > $checkpatientthreshold[0]->oxsathigh) || ($o2value < $checkpatientthreshold[0]->oxsatlow ))
                   {
                     Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                     ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                   }
              }


        
       }  
  
//  ----------------------------  alert status code------------------------------------------------------------------------------ 
         


         }




     break;


     case'heart-rate':
     // dd('heart-rate');
     // dd($idarrays,$obs,$patientid,$deviceid,$mrn_no,$observationid);  
       $recorddate = date('Y-m-d H:i:s', strtotime($obs->effectiveDateTime)); 
       $code = $obs->code;
       $coding =$code->coding[0];
       $dis = $coding->display;
       $vitaldeviceid = 0; 
// dd($dis); 
       if($dis == 'Resting heart rate')
       {
           // dd($dis);
           $v = $obs->valueQuantity;
           $heartratevalue = $v->value;
           $heartrateunit  = $v->unit;
           $heartratecode  = $v->code; 
            //after inserting update processedflag = 1
            $upheartratedata = array(
             'effdatetime'=> $recorddate,
             'resting_heartrate'=>$heartratevalue,
             'resting_heartrate_unit'=>$heartrateunit,
             'resting_heartrate_code'=>$heartratecode
            );
            if (in_array("blood-pressure", $idarrays)){ 
                 //  dd("blood");
                 $checkbp = Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)
                 ->where('mrn',$mrn_no)
                 ->where('observation_id',$observationid)->get();
                 // dd($checkbp);
   
                 if($checkbp->isEmpty()){
   
                 }
                 else{
                       if($checkbp[0]->resting_heartrate==null || $checkbp[0]->resting_heartrate==''){ 
                         $obhrtrate =  Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                                       ->where('effdatetime',$recorddate)
                                       ->where('observation_id',$observationid)
                                       ->update($upheartratedata);
                         $vitaldevice = Devices::where('device_name','Blood Pressure Cuff')->where('status',1)->first();
                         $vitaldeviceid = $vitaldevice->id; 
                         // dd($vitaldeviceid,$obhrtrate);  
                       }
                     
                   
                     }
             }else if((in_array("satO2", $idarrays))){

                 $checkhrt = Observation_Oxymeter::where('device_id',$deviceid)
                 ->where('patient_id',$patientid)
                 ->where('mrn',$mrn_no)
                 ->where('observation_id',$observationid)->get();
                   if($checkhrt->isEmpty()){  
                   }
                   else{
                     if($checkhrt[0]->resting_heartrate==null || $checkhrt[0]->resting_heartrate==''){ 
                       $obhrtrate =  Observation_Oxymeter::where('device_id',$deviceid)
                       ->where('patient_id',$patientid)
                       ->where('mrn',$mrn_no)
                       ->where('effdatetime',$recorddate)
                       ->where('observation_id',$observationid)
                       ->update($upheartratedata);
                       $vitaldevice = Devices::where('device_name','Pulse Oximeter')->where('status',1)->first();
                       $vitaldeviceid = $vitaldevice->id; 
                        
                     }
                   }


             }
             else{
               // $vitaldeviceid = 0;   
               
               $bpvital = Observation_BP::where('observation_id',$observationid)
               ->where('device_id',$deviceid)
               ->where('patient_id',$patientid)
               ->where('mrn',$mrn_no)
               ->where('effdatetime',$recorddate)
               ->where('observation_id',$observationid)
               ->get();
               // dd($bpvital); 
               //$vitaldeviceid = $v->vital_device_id;
               if($bpvital->isEmpty()){
                 $vitaldeviceid = $bpvital->vital_device_id; 
               }
               else{
                 $oxyvital = Observation_Oxymeter::where('observation_id',$observationid)->where('device_id',$deviceid)
                 ->where('patient_id',$patientid)->where('mrn',$mrn_no)->where('effdatetime',$recorddate)->where('observation_id',$observationid)->get();
                 $vitaldeviceid = $oxyvital->vital_device_id;  
               }
               

             }  
             // dd($vitaldeviceid);  


            $checkhrtrate = Observation_Heartrate::where('device_id',$deviceid)
            ->where('patient_id',$patientid)
           //  ->where('mrn',$mrn_no)
            ->where('observation_id',$observationid)
            ->where('effdatetime',$recorddate)
            ->get();

           //  dd($checkhrtrate,$deviceid,$patientid,$mrn_no,$recorddate); 

            if(count($checkhrtrate)==0){
             
             $upheartratedata = array(
               'effdatetime'=> $recorddate,
               'resting_heartrate'=>$heartratevalue,
               'resting_heartrate_unit'=>$heartrateunit,
               'resting_heartrate_code'=>$heartratecode,
               'careplan_no' => null,
               'device_id' => $deviceid,
               'effdatetime'=>$recorddate,
               'mrn'=>$mrn_no,
               'created_by'=>'Symtech',
               'updated_by'=>'Symtech',
               'patient_id'=>$patientid,
               'reviewed_flag'=>0,
               'reviewed_date'=>null,
               'observation_id'=>$observationid,
               'billing'=>0,
               'vital_device_id'=>$vitaldeviceid,
               'threshold'=>null,
               'threshold_type'=>null,
               'threshold_id'=>null
              );
             $obhrtrate =  Observation_Heartrate::create($upheartratedata);
             // dd($vitaldeviceid,$obhrtrate); 
            }
           

   //  ----------------------------  threshold and alert status code------------------------------------------------------------------------------

       $checkpatientthreshold = PatientThreshold::where('patient_id',$patientid)
                               ->whereNotNull('bpmlow')
                               ->whereNotNull('bpmhigh')
                               ->orderBy('created_at', 'desc')
                               ->get();


       if($checkpatientthreshold->isEmpty())
       {
         $checkpractice = PatientProvider::where('patient_id',$patientid)->where('is_active',1)->first();

         if(!is_null($checkpractice)){

           $checkinpracticethreshold = PracticeThreshold::where('practice_id',$checkpractice->practice_id)
                                       ->whereNotNull('bpmlow')
                                       ->whereNotNull('bpmhigh')
                                       ->orderBy('created_at', 'desc')->get();
         } 
         
         
             if($checkpractice==null || $checkinpracticethreshold->isEmpty()){

               $orgid = Practices::where('id',$checkpractice->practice_id)->get(); //$orgid[0]->practice_group

               if(!is_null($orgid)){

                 $orgcheck = OrgThreshold::get();
                 

                 if(!$orgcheck->isEmpty() ){
                 $org = OrgThreshold::where('org_id',$orgid[0]['practice_group'])
                 ->whereNotNull('bpmhigh')
                 ->whereNotNull('bpmlow')
                 ->get();
                 


                 if(!$org->isEmpty() ){
                 $hrt_org_threshold = $org[0]->bpmlow."-".$org[0]->bpmhigh;
                 ($hrt_org_threshold == null || $hrt_org_threshold == 'null' || $hrt_org_threshold == '-' || $hrt_org_threshold == "-" ) ? $hrt_org_threshold = '' : $hrt_org_threshold;                   
                
                 ( $hrt_org_threshold=='' ) ? $thresholdtype = null : $thresholdtype = 'O';
                 ( $hrt_org_threshold=='' ) ? $thresholdid = null : $thresholdid = $org[0]->id;

                 Observation_Heartrate::where('device_id',$deviceid)
                 ->where('patient_id',$patientid)
                 ->where('mrn',$mrn_no)
                 ->where('effdatetime',$recorddate)
                 ->where('observation_id',$observationid)
                 ->update(['threshold'=>$hrt_org_threshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);

                 if($hrt_org_threshold=="" || $hrt_org_threshold=='' || $hrt_org_threshold==null){ 
                 }
                 else{
                  if(($heartratevalue > $org[0]->bpmhigh) || ($heartratevalue < $org[0]->bpmlow ))
                  {
                    Observation_Heartrate::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                  ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                  }
                 }

                  

               }
               else{

                 $gt = GroupThreshold::whereNotNull('bpmlow')->whereNotNull('bpmhigh')->orderBy('created_at', 'desc')->get();

                 if(!$gt->isEmpty() ){

                   $hrt_group_threshold = $gt[0]->bpmlow."-".$gt[0]->bpmhigh;
                   ($hrt_group_threshold == null || $hrt_group_threshold == 'null' || $hrt_group_threshold == '-' || $hrt_group_threshold == "-" ) ? $hrt_group_threshold = '' : $hrt_group_threshold; 

                   ( $hrt_group_threshold=='' ) ? $thresholdtype = null : $thresholdtype = 'R';
                   ( $hrt_group_threshold=='' ) ? $thresholdid = null : $thresholdid = $gt[0]->id;

                   Observation_Heartrate::where('device_id',$deviceid)
                   ->where('patient_id',$patientid)
                   ->where('mrn',$mrn_no)
                   ->where('effdatetime',$recorddate)
                   ->where('observation_id',$observationid)
                   ->update(['threshold'=>$hrt_group_threshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);

                   if($hrt_group_threshold=="" || $hrt_group_threshold=='' || $hrt_group_threshold==null){ 
                  }
                  else{
                      //checking in groupthreshold  
                      if(($heartratevalue > $gt[0]->bpmhigh) || ($heartratevalue < $gt[0]->bpmlow ))
                      {
                      Observation_Heartrate::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                      ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                      } 
                  }



                     

                 }
                

               }


             }else{

               $gt = GroupThreshold::whereNotNull('bpmlow')->whereNotNull('bpmhigh')->orderBy('created_at', 'desc')->get();

                 if(!$gt->isEmpty() ){

                   $hrt_group_threshold = $gt[0]->bpmlow."-".$gt[0]->bpmhigh;
                   ($hrt_group_threshold == null || $hrt_group_threshold == 'null' || $hrt_group_threshold == '-' || $hrt_group_threshold == "-" ) ? $hrt_group_threshold = '' : $hrt_group_threshold; 
                   ( $hrt_group_threshold=='' ) ? $thresholdtype = null : $thresholdtype = 'R';
                   ( $hrt_group_threshold=='' ) ? $thresholdid = null : $thresholdid = $gt[0]->id;

                   Observation_Heartrate::where('device_id',$deviceid)
                   ->where('patient_id',$patientid)
                   ->where('mrn',$mrn_no)
                   ->where('effdatetime',$recorddate)
                   ->where('observation_id',$observationid)
                   ->update(['threshold'=>$hrt_group_threshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);

                  if($hrt_group_threshold=="" || $hrt_group_threshold=='' || $hrt_group_threshold==null){ 
                  }
                  else{
                     //checking in groupthreshold  
                     if(($heartratevalue > $gt[0]->bpmhigh) || ($heartratevalue < $gt[0]->bpmlow ))
                     {
                     Observation_Heartrate::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                     ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                     } 
                  }
                         

                 }

             }


               }else{

                 $gt = GroupThreshold::whereNotNull('bpmlow')->whereNotNull('bpmhigh')->orderBy('created_at', 'desc')->get();

                 if(!$gt->isEmpty() ){

                   $hrt_group_threshold = $gt[0]->bpmlow."-".$gt[0]->bpmhigh;
                   ($hrt_group_threshold == null || $hrt_group_threshold == 'null' || $hrt_group_threshold == '-' || $hrt_group_threshold == "-" ) ? $hrt_group_threshold = '' : $hrt_group_threshold; 

                   ( $hrt_group_threshold=='' ) ? $thresholdtype = null : $thresholdtype = 'R';
                   ( $hrt_group_threshold=='' ) ? $thresholdid = null : $thresholdid = $gt[0]->id;

                   Observation_Heartrate::where('device_id',$deviceid)
                   ->where('patient_id',$patientid)
                   ->where('mrn',$mrn_no)
                   ->where('effdatetime',$recorddate)
                   ->where('observation_id',$observationid)
                   ->update(['threshold'=>$hrt_group_threshold,'threshold_type'=>$hrt_group_threshold,'threshold_id'=>$thresholdid]);

                  if($hrt_group_threshold=="" || $hrt_group_threshold=='' || $hrt_group_threshold==null){ 
                  }
                  else{
                      //checking in groupthreshold  
                      if(($heartratevalue > $gt[0]->bpmhigh) || ($heartratevalue < $gt[0]->bpmlow ))
                      {
                      Observation_Heartrate::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                      ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                      } 
                  }


                       

                 }


               }

             
               
             }
             else{

               $hrt_practice_threshold = $checkinpracticethreshold[0]->bpmlow."-".$checkinpracticethreshold[0]->bpmhigh;
               ($hrt_practice_threshold == null || $hrt_practice_threshold == 'null' || $hrt_practice_threshold == '-' || $hrt_practice_threshold == "-" ) ? $hrt_practice_threshold = '' : $hrt_practice_threshold; 

               ( $hrt_practice_threshold=='' ) ? $thresholdtype = null : $thresholdtype = 'Pr';
               ( $hrt_practice_threshold=='' ) ? $thresholdid = null : $thresholdid = $checkinpracticethreshold[0]->id;

                 Observation_Heartrate::where('device_id',$deviceid)
                 ->where('patient_id',$patientid)
                 ->where('mrn',$mrn_no)
                 ->where('effdatetime',$recorddate)
                 ->where('observation_id',$observationid)
                 ->update(['threshold'=>$hrt_practice_threshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid ]);

                if($hrt_practice_threshold=="" || $hrt_practice_threshold=='' || $hrt_practice_threshold==null){ 
                }
                else{
                    
                    //checking in practicethreshold
                    if(($heartratevalue > $checkinpracticethreshold[0]->bpmhigh) || ($heartratevalue < $checkinpracticethreshold[0]->bpmlow ))
                    {
                      Observation_Heartrate::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                    ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                    }
                }


                  
             } 
       }
       else{
           //checking in patient threshold

           $hrt_patient_threshold = $checkpatientthreshold[0]->bpmlow."-".$checkpatientthreshold[0]->bpmhigh;

           ($hrt_patient_threshold == null || $hrt_patient_threshold == 'null' || $hrt_patient_threshold == '-' || $hrt_patient_threshold == "-" ) ? $hrt_patient_threshold = '' : $hrt_patient_threshold; 

           ( $hrt_patient_threshold=='' ) ? $thresholdtype = null : $thresholdtype = 'P';
           ( $hrt_patient_threshold=='' ) ? $thresholdid = null : $thresholdid = $checkpatientthreshold[0]->id;

                                   Observation_Heartrate::where('device_id',$deviceid)
                                   ->where('patient_id',$patientid)
                                   ->where('mrn',$mrn_no)
                                   ->where('effdatetime',$recorddate)
                                   ->where('observation_id',$observationid)
                                   ->update(['threshold'=>$hrt_patient_threshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid ]);  

                                   if($hrt_patient_threshold=="" || $hrt_patient_threshold=='' || $hrt_patient_threshold==null){ 
                                  }
                                  else{
                                      
                                      //checking in practicethreshold
                                                              
                                      if(($heartratevalue > $checkpatientthreshold[0]->bpmhigh) || ($heartratevalue < $checkpatientthreshold[0]->bpmlow ))
                                      {
                                        Observation_Heartrate::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                                        ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);   
                                      }
                                  } 
                                  
            
       }         
  
//  ----------------------------threshold and  alert status code------------------------------------------------------------------------------ 

       }  
      
     break;  
    

      
  
 }  //end switch   
}     




  //created and modified by ashvini
  public function saveObservationdataObservationLog_widexception()    
  {    
    try{
              $getdata =  \DB::select(DB::raw("select id,content->>'observation_id' as observation_id,content->>'xmit_id' as xmit_id
              from api.webhook_observation 
              where content->>'observation_id' is not null and fetch_status = 0 offset 0 limit 50 ")); //total 160 data done              
             
              // dd($getdata);//2128,2731  


              if(empty($getdata)){
                throw new Exception('Empty observation record'); 
              }
              else{

                foreach($getdata as $g)
                {
                   // $observationid = '8bff0981-98f8-4b59-8ba9-fa124f4624e2'; //for bloodpressur abd heartrate
                   // $observationid= 'e1989fab-da37-4081-8877-73e754b6509d'; //for saturationo2

                  if (array_key_exists("observation_id",$g)){
                    $observationid = $g->observation_id;
                    if($observationid==null || $observationid==''){
                      throw new Exception('Observation id null or blank alert'); //alert condition
                    }
                  }
                  else{
                    throw new Exception('Observation id key missing alert'); //alert condition
                  }

                  
                  if(array_key_exists("xmit_id",$g)){
                    $deviceid = $g->xmit_id;
                    if($deviceid==null || $deviceid==''){
                      throw new Exception('Xmit id null or blank alert'); //alert condition
                    }
                  }
                  else{
                    throw new Exception('Xmit id key missing alert'); //alert condition
                  }  
                 

                  $id = $g->id;
                  $patdata = PatientDevices::where('device_code',$deviceid)->where('status',1)->get();
                 
                  if($patdata->isEmpty()){
                  
                    $patientid = null;
                    $partner_device_id = null;
                    $mrn_no = null;

                  }
                  else{
                  
                    $patientid = $patdata[0]->patient_id;
                    $partner_device_id = $patdata[0]->partner_device_id;
                    $mrn_no = $patdata[0]->mrn_no;

                  }
              
                  $ecgcredetials=ApiECGCredeintials();
                  $this->getAuthorization();
                 
                  $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL, $ecgcredetials[0]->url.'observations/'.$observationid);         
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
                  curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Content-Type: application/json",
                    "Authorization: Bearer ".session()->get('TokenId')]
                  );
                    
                  $response = curl_exec($ch);  
                  curl_close($ch);
                  

                  // $response= '{"resourceType":"Observation","id":"weight-scale",
                  //   "identifier":[{"use":"official","system":"urn:oid:2.16.840.1.113883.4.969.1",
                  //     "value":"aa9f947d-b692-4c48-951c-b4b2b227ca5d"}],
                  //     "status":"final",
                  //     "category":[{"coding":[{"system":"http:\/\/terminology.hl7.org\/CodeSystem\/observation-category",
                  //       "code":"vital-signs","display":"Vital Signs"}],"text":"Vital Signs"}],
                  //       "code":{"coding":[{"system":"http:\/\/snomed.info\/sct","code":"27113001","display":"Body weight"}],
                  //       "text":"Blood pressure systolic and diastolic"},
                  //       "subject":[{"identifier":{"system":"urn:oid:2.16.840.1.113883.4.969.2","value":"ANZ7290"}}],
                  //       "effectiveDateTime":"2021-03-20T12:01:47.000Z",
                  //       "valueQuantity":{"value":150,"unit":"lbs","system":"http:\/\/unitsofmeasure.org",
                  //       "code":"[lb_av]"},"device":[{"identifier":{"system":"urn:oid:2.16.840.1.113883.4.969.7","value":"89011703278360214780"}}]}';


                        // $response1 = '{"resourceType":"Observation","id":"blood-pressure",
                        //   "identifier":[{"use":"official","system":"urn:oid:2.16.840.1.113883.4.969.1",
                        //     "value":"7cdc18ad-b285-4b75-93c1-b696a0b6c59d"}],"status":"final",
                        //     "category":[{"coding":[{"system":"http:\/\/terminology.hl7.org\/CodeSystem\/observation-category",
                        //       "code":"vital-signs","display":"Vital Signs"}],"text":"Vital Signs"}],
                        //       "code":{"coding":[{"system":"http:\/\/snomed.info\/sct","code":"75367002",
                        //         "display":"Blood pressure systolic and diastolic"}],
                        //         "text":"Blood pressure systolic and diastolic"},
                        //         "subject":[{"identifier":{"system":"urn:oid:2.16.840.1.113883.4.969.2","value":"ANZ7831"}}],
                        //         "effectiveDateTime":"2021-03-13T13:57:16.000Z",
                        //         "component":[{"code":{"coding":[{"system":"http:\/\/snomed.info\/sct","code":"271649006",
                        //           "display":"Systolic blood pressure"}]},
                        //           "valueQuantity":{"value":123,"unit":"mmHg","system":"http:\/\/unitsofmeasure.org","code":"mm[Hg]"}},  
                        //           {"code":{"coding":[{"system":"http:\/\/snomed.info\/sct","code":"271650006","display":"Diastolic blood pressure"}]},
                        //           "valueQuantity":{"value":112,"unit":"mmHg","system":"http:\/\/unitsofmeasure.org","code":"mm[Hg]"}}],
                        //           "device":[{"identifier":{"system":"urn:oid:2.16.840.1.113883.4.969.7","value":"89011703278360207503"}}]}';

                  // $response2 = '{"resourceType":"Observation","id":"satO2",
                  //   "identifier":[{"use":"official","system":"urn:oid:2.16.840.1.113883.4.969.8",
                  //   "value":"e04450b9-ac2a-4fc3-a2db-0e617e85a435"}],"status":"final",
                  //   "category":[{"coding":[{"system":"http:\/\/terminology.hl7.org\/CodeSystem\/observation-category",
                  //   "code":"vital-signs","display":"Vital Signs"}],"text":"Vital Signs"}],
                  //   "code":{"coding":[{"system":"http:\/\/snomed.info\/sct","code":"103228002",
                  //   "display":"Hemoglobin saturation with oxygen"}],"text":"Hemoglobin saturation with oxygen"},
                  //   "subject":[{"identifier":{"system":"urn:oid:2.16.840.1.113883.4.969.2","value":"ANZ7256"}}],
                  //   "effectiveDateTime":"2021-06-10T17:38:47.000Z",
                  //   "valueQuantity":{"value":94,"unit":"%","system":"http:\/\/unitsofmeasure.org","code":"%"},
                  //   "device":[{"identifier":{"system":"urn:oid:2.16.840.1.113883.4.969.7","value":"89011703278360202066"}}]}' ;               
                  
                  $newgetdata=json_decode($response2);
                  // dd($newgetdata);


                  if(empty($newgetdata)){
                    throw new Exception('Observation json response alert'); 
                  }else{

                  $idarrays = [];
                  foreach($newgetdata as $o){
                    array_push($idarrays,$o->id); 
                  }  //uncomnt dis


                  // array_push($idarrays,$newgetdata->id); //remove dis later
                  // $ob = $newgetdata; //remove dis later

                  foreach($newgetdata as $ob)
                  {
                    if (array_key_exists("effectiveDateTime",$ob)){                     
                        if($ob->effectiveDateTime==null || $ob->effectiveDateTime==''){
                          throw new Exception('Observation EffectiveDateTime null or blank');
                        }
                        else{
                          $recorddate = date('Y-m-d H:i:s', strtotime($ob->effectiveDateTime));
                        }
                    }
                    else{
                        throw new Exception('Observation EffectiveDateTime key missing alert'); //alert condition
                    }
                    
                     

                    $jsonencodedata_ob = json_encode($ob);
                    $insertdata=array(
                                    'patient_id' => $patientid,
                                    'partner_id' =>$partner_device_id,
                                    'observation_id' =>$observationid,
                                    'record_timestamp' =>$recorddate,
                                    'device_code' =>$deviceid,
                                    'content' =>$jsonencodedata_ob,
                                    'content_format' =>'FHIR',
                                    'mrn' =>$mrn_no,
                                    'status' =>1,
                                    'created_by' =>'Symtech',
                                    'updated_by' =>'Symtech',
                                    'processed_flag' =>0,
                                    'api_url' => 'https://dev.ecg-api.com/observations/{id}'               
                                    ); 

                        //  dd($insertdata);          
                      $observationinsert= ObservationLog::create($insertdata);   
                      $updatedata= WebhookObservation::where('id',$id)->update(['fetch_status'=>1]);       
                      $this->saveRPMReadings_widexception($idarrays,$ob,$patientid,$deviceid,$mrn_no,$observationid); 

                      $updateprocessedflaginLog = ObservationLog::where('id',$observationinsert->id)->update(['processed_flag'=>1]) ;           
                                  
                            
                    
                    
                    } //uncomnt afterward for foreach loop  
                  }
                } 
            } 
            return response()->json("Data1 inserted successfully!");
  }//rollback
  catch(Exception $e){
    
    $msg  = $e->getMessage(); 
    // dd($msg);
    
    $alerterrors = array("Empty observation record", "Observation id null or blank alert", "Observation id key missing alert",
                  "Xmit id null or blank alert", "Xmit id key missing alert", "Observation json response alert",
                  "Observation EffectiveDateTime key missing alert", "Observation EffectiveDateTime null or blank", 
                  "Observation Id is null or blank","Observation Id key missing alert",
                  "Weight-Scale ValueQuantity is null or blank alert", "Weight-Scale unit of ValueQuantity is null or blank alert", 
                  "Weight-Scale ValueQuantity key missing alert",
                  "Blood-pressure value null or blank alert", "Blood-pressure value key missing alert", "Blood-pressure unit null or blank alert",
                  "Blood-pressure unit key missing alert", "Blood-pressure value null or blank alert", "Blood-pressure value key missing alert",
                  "Blood-pressure display key missing alert", "Blood-pressure component key missing alert","Blood-pressure component null or blank alert",
                  "Saturation-O2 value null or blank alert","Saturation-O2 value key missing alert","Saturation-O2 unit key missing alert",
                  "Saturation-O2 unit key missing alert","Saturation-O2 display key missing alert","Saturation-O2 display null or blank alert",
                  "Heartrate value null or blank alert","Heartrate value key missing alert","Heartrate unit null or blank alert",
                  "Heartrate unit key missing alert", "Heartrate display key missing alert"
                  );

      $infoerrors = array("Blood-pressure code null or blank","Blood-pressure code key missing",
                          "Saturation-O2 code null or blank","Saturation-O2 code key missing",
                          "Heartrate code null or blank","Heartrate code key missing") ;

    if(in_array($msg, $alerterrors)){
      
      $p = explode(" ",$msg);
      $parameter = $p[0]."-".$p[1]."-".$p[2];
      // $parameter = $p[1];
      $a = array('api'=>$ecgcredetials[0]->url.'observations/{id}','parameter'=>$parameter,'exception_type'=>'alert','incident'=>'missing');
      ApiException::create($a);
     
    }
    else if(in_array($msg, $infoerrors)){
      $p = explode(" ",$msg);
      $parameter = $p[0]."-".$p[1]."-".$p[2];  
      // $parameter = $p[1];
      $a = array('api'=>$ecgcredetials[0]->url.'observations/{id}','parameter'=>$parameter,'exception_type'=>'info','incident'=>'missing');
      ApiException::create($a);
      
    }
    else{
     
    }
   
    return response()->json("Exception occurred!");  
  }
   
   
  }





  //created and modified by ashvini
  public function saveRPMReadings_widexception($idarrays,$obs,$patientid,$deviceid,$mrn_no,$observationid)
  {
    // dd($idarrays,$obs,$patientid,$deviceid,$mrn_no,$observationid);     
   
    if (array_key_exists("effectiveDateTime",$obs)){                     
      if($obs->effectiveDateTime==null || $obs->effectiveDateTime==''){
        throw new Exception('Obseravtion EffectiveDateTime null or blank');
      }
      else{
        $recorddate = date('Y-m-d H:i:s', strtotime($obs->effectiveDateTime));
      }
  }
  else{
      throw new Exception('Observation EffectiveDateTime key missing alert'); //alert condition
  }



    if (array_key_exists("id",$obs)){       
      if($obs->id==null || $obs->id==''){
        throw new Exception('Observation Id null or blank');
      }
      else{
        $id = $obs->id;
      }  
    }
    else{
      throw new Exception('Observation Id key missing alert'); //alert condition
    }

    


   

    if (in_array("blood-pressure", $idarrays)){  
      // dd('blood-pressure');
        $insertdata = array(
                        'careplan_no' => null,
                        'device_id' => $deviceid,
                        'effdatetime'=>$recorddate,
                        'systolic_qty'=>null,
                        'systolic_unit'=> null,
                        'systolic_code'=> null,
                        'diastolic_qty'=>null,
                        'diastolic_unit'=>null,
                        'diastolic_code'=>null,
                        'resting_heartrate'=>null,
                        'resting_heartrate_unit'=>null,
                        'resting_heartrate_code'=>null,
                        'mrn'=>$mrn_no,
                        'created_by'=>'Symtech',
                        'updated_by'=>'Symtech',
                        'patient_id'=>$patientid,
                        'reviewed_flag'=>0,
                        'reviewed_date'=>null,
                        'observation_id'=>$observationid,
                        'billing'=>0  
                        );
                        $check = Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                        ->where('effdatetime',$recorddate)
                        ->get();     
                        if(count($check)==0){
                          $o = Observation_BP::create($insertdata);
                         
                        }  
    }
    else if(in_array("weight-scale", $idarrays)){
      
        $insertdataweightscale = array( 'careplan_no' => null,
                                        'device_id' => $deviceid,
                                        'effdatetime'=> $recorddate,
                                        'weight'=>null,
                                        'mrn'=>$mrn_no,
                                        'unit'=>null,
                                        'created_by'=>'Symtech',
                                        'updated_by'=>'Symtech',
                                        'patient_id'=>$patientid,
                                        'reviewed_flag'=>0,
                                        'reviewed_date'=>null,
                                        'observation_id'=>$observationid,
                                        'billing'=>0  
                                       ); 
                                       
                                        $check = Observation_Weight::where('device_id',$deviceid)->where('patient_id',$patientid)
                                        ->where('mrn',$mrn_no)->where('effdatetime',$recorddate)->get(); 
                                        // dd($check) ;      
                                        if(count($check)==0){
                                          $o = Observation_Weight::create($insertdataweightscale); 
                                        }  
                                      
                                       
                                        
    }
    else{ 
      $insertdatasaturation = array(
                                  'careplan_no' => null,
                                  'device_id' => $deviceid,
                                  'effdatetime'=> $recorddate,
                                  'oxy_qty'=>null,
                                  'oxy_unit'=> null,
                                  'oxy_code'=> null,
                                  'resting_heartrate'=>null,
                                  'resting_heartrate_unit'=>null,
                                  'resting_heartrate_code'=>null,
                                  'mrn'=>$mrn_no,
                                  'created_by'=>'Symtech',
                                  'updated_by'=>'Symtech',
                                  'patient_id'=>$patientid,
                                  'reviewed_flag'=>0,
                                  'reviewed_date'=>null,
                                  'observation_id'=>$observationid,
                                  'billing'=>0  
                                  );
                                  $check = Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)
                                  ->where('mrn',$mrn_no)->where('effdatetime',$recorddate)->get();     
                                  if(count($check)==0){
                                    $o = Observation_Oxymeter::create($insertdatasaturation); 
                                  }    
    }

     

    switch($id){


      case 'weight-scale' : 
        if (array_key_exists("valueQuantity",$obs)){   
          $a = $obs->valueQuantity;
          if($a==null || $a== ''){
            throw new Exception('Weight-Scale ValueQuantity is null or blank alert');
          }
          else{
            if (array_key_exists("value",$a)){ 
              $value = $a->value; 
            }
            else{
              throw new Exception('Weight-Scale Value of ValueQuantity is null or blank alert');
            }

            if (array_key_exists("unit",$a)){ 
              $unit = $a->unit;
            }
            else{
              throw new Exception('Weight-Scale unit of ValueQuantity is null or blank alert');
            }
           
          //even if it is null it will go.....inside the table need to review wid mam
                $upweightdata = array(
                  'effdatetime'=> $recorddate,
                  'weight'=>$value,
                  'unit'=> $unit
                  );
        
                    
                    $check = Observation_Weight::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                    ->where('effdatetime',$recorddate)->get();
                    if($check->isEmpty()){
                    }
                    else{
                      if($check[0]->weight==null || $check[0]->unit==''){ 
                        $obsweight =  Observation_Weight::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                        ->where('effdatetime',$recorddate)->update($upweightdata);
                        
                      } 
                    }
          }
         
        }
        else{
          throw new Exception('Weight-Scale ValueQuantity key missing alert');
        }     
       
          
      break;

      case 'blood-pressure' :
        // dd("blood"); 
        if (array_key_exists("effectiveDateTime",$obs)){                     
          if($obs->effectiveDateTime==null || $obs->effectiveDateTime==''){
            throw new Exception('Observation EffectiveDateTime null or blank');
          }
          else{
            $recorddate = date('Y-m-d H:i:s', strtotime($obs->effectiveDateTime));
          }
        }
        else{
            throw new Exception('Observation EffectiveDateTime key missing alert'); //alert condition
        }


        if (array_key_exists("component",$obs)){ 
        
        $a = $obs->component;  
          
        if($a==null || $a==''){
          
          throw new Exception('Blood-pressure component null or blank alert');
        } 
        else{
         
        foreach($a as $akey=>$avalue)
        {
          $code = $avalue->code;
          $coding =$code->coding[0];
          if (array_key_exists("display",$coding)){ 
              
            $dis = $coding->display;


            if($dis == 'Systolic blood pressure'){
                        $v = $avalue->valueQuantity;

                        if (array_key_exists("value",$v)  )
                        {
                              $systolicvalue = $v->value;
                              if($systolicvalue==null || $systolicvalue=="" ){
                                throw new Exception('Blood-pressure value null or blank alert'); //alert condition
                              }
                              
                        }else{
                              throw new Exception('Blood-pressure value key missing alert'); //alert condition
                        }

                        if (array_key_exists("unit",$v)  )
                        {
                                $systolicunit  = $v->unit;
                                if($systolicunit==null || $systolicunit=="" ){
                                  throw new Exception('Blood-pressure unit null or blank alert'); //alert condition
                                }
                                
                        }else{
                                throw new Exception('Blood-pressure unit key missing alert'); //alert condition
                        }

                        if (array_key_exists("code",$v)  )
                        {
                                $systoliccode  = $v->code;
                                if($systoliccode==null || $systoliccode=="" ){
                                  throw new Exception('Blood-pressure code null or blank'); //alert condition
                                }
                               
                        }else{
                                throw new Exception('Blood-pressure code key missing'); //alert condition   
                        }

                          
                          //after inserting update processedflag = 1
                          $upsysdata = array(
                                              'effdatetime'=> $recorddate,
                                              'systolic_qty'=>$systolicvalue,
                                              'systolic_unit'=> $systolicunit,
                                              'systolic_code'=> $systoliccode,
                                              );
                        
                          
  
                          $checkbp = Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                          ->where('effdatetime',$recorddate)->get();
                          if($checkbp->isEmpty()){
                          }
                          else{
                            if($checkbp[0]->systolic_qty==null || $checkbp[0]->systolic_qty==''){ 
                              $obsys =  Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                              ->where('effdatetime',$recorddate)->update($upsysdata);
                              
                            } 
                          }
    
  
            //  ----------------------------  alert status code------------------------------------------------------------------------------
  
                          $checkpatientthreshold = PatientThreshold::where('patient_id',$patientid)->get();
                          if($checkpatientthreshold->isEmpty())
                          {
                            $checkpractice = PatientProvider::where('patient_id',$patientid)->where('is_active',1)->where('provider_type_id',1)->first();
                            
                            if(!is_null($checkpractice)){
                            
                              $checkinpracticethreshold = PracticeThreshold::where('practice_id',$checkpractice->practice_id)->get();
                            } 
                          
                            
                            
                            
                                if($checkpractice==null || $checkinpracticethreshold->isEmpty()){
                                  $gt = GroupThreshold::get();
  
                                  //checking in groupthreshold  
                                  if(($systolicvalue > $gt[0]->systolichigh) || ($systolicvalue < $gt[0]->systoliclow ))
                                  {
                                    Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                                  ->where('effdatetime',$recorddate)->update(['alert_status'=>1]);
                                  } 
                                }
                                else{
                                      //checking in practicethreshold
                                      if(($systolicvalue > $checkinpracticethreshold[0]->systolichigh) || ($systolicvalue < $checkinpracticethreshold[0]->systoliclow ))
                                      {
                                        Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                                      ->where('effdatetime',$recorddate)->update(['alert_status'=>1]);
                                      }
                                } 
                          }
                          else{
                              //checking in patient threshold
                            if(($systolicvalue > $checkpatientthreshold[0]->systolichigh) || ($systolicvalue < $checkpatientthreshold[0]->systoliclow ))
                            {
                              Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                            ->where('effdatetime',$recorddate)->update(['alert_status'=>1]);
                            }
                          }
                       
                           

          //  ----------------------------  alert status code------------------------------------------------------------------------------            
                 
                }
                else{ 

                  $v = $avalue->valueQuantity;
                  if (array_key_exists("value",$v)  )
                  {
                        $diastolicvalue = $v->value;
                        if($diastolicvalue==null || $diastolicvalue=="" ){ 
                          throw new Exception('Blood-pressure value null or blank alert'); //alert condition
                        }
                        
                  }else{
                        throw new Exception('Blood-pressure value key missing alert'); //alert condition
                  }

                  if (array_key_exists("unit",$v)  )
                  {
                          $diastolicunit  = $v->unit;
                          if($diastolicunit==null || $diastolicunit=="" ){
                            throw new Exception('Blood-pressure unit null or blank alert'); //alert condition
                          }
                          
                  }else{
                          throw new Exception('Blood-pressure unit key missing alert'); //alert condition
                  }

                  if (array_key_exists("code",$v)  )
                  {
                          $diastoliccode  = $v->code; 
                          if($diastoliccode==null || $diastoliccode=="" ){
                            throw new Exception('Blood-pressure code null or blank'); //alert condition
                          }
                         
                  }else{
                          throw new Exception('Blood-pressure code key missing'); //alert condition   
                  }
   
                 
                 
                  
                 
                  //after inserting update processedflag = 1
                  $updiasdata = array(
                    'effdatetime'=> $recorddate,
                    'diastolic_qty'=>$diastolicvalue,
                    'diastolic_unit'=>$diastolicunit,
                    'diastolic_code'=> $diastoliccode,
                    );
                  $checkbp = Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                  ->where('effdatetime',$recorddate)->get();
                  
                    if($checkbp->isEmpty()){
                    }
                    else{
                      if($checkbp[0]->diastolic_qty==null || $checkbp[0]->diastolic_qty==''){
                        $obdia =  Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                        ->where('effdatetime',$recorddate)->update($updiasdata);  
                      }
                    }  
                    



        //  ----------------------------  alert status code------------------------------------------------------------------------------

              $checkpatientthreshold = PatientThreshold::where('patient_id',$patientid)->get();
              if($checkpatientthreshold->isEmpty())
              {
                $checkpractice = PatientProvider::where('patient_id',$patientid)->where('is_active',1)->where('provider_type_id',1)->first();
                if(!is_null($checkpractice)){
                    $checkinpracticethreshold = PracticeThreshold::where('practice_id',$checkpractice->practice_id)->get();
                  } 
                
                
                
                    if($checkpractice==null || $checkinpracticethreshold->isEmpty()){
                      $gt = GroupThreshold::get();

                      //checking in groupthreshold  
                      if(($diastolicvalue > $gt[0]->diastolichigh) || ($diastolicvalue < $gt[0]->diastoliclow ))
                      {
                        Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                      ->where('effdatetime',$recorddate)->update(['alert_status'=>1]);
                      } 
                    }
                    else{
                          //checking in practicethreshold
                          if(($diastolicvalue > $checkinpracticethreshold[0]->diastolichigh) || ($diastolicvalue < $checkinpracticethreshold[0]->diastoliclow ))
                          {
                            Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                          ->where('effdatetime',$recorddate)->update(['alert_status'=>1]);
                          }
                    } 
              }
              else{
                  //checking in patient threshold
                if(($diastolicvalue > $checkpatientthreshold[0]->diastolichigh) || ($diastolicvalue < $checkpatientthreshold[0]->diastoliclow ))
                {
                  Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                  ->where('effdatetime',$recorddate)->update(['alert_status'=>1]);
                }
              }  
              
      //  ----------------------------  alert status code------------------------------------------------------------------------------  
              
            
              } 
      }
      else{
       
        throw new Exception('Blood-pressure display key missing alert');
      }
         
          
         
       }//foreach
      }
    }
    else{
     
    throw new Exception('Blood-pressure component key missing alert'); //alert condition
    }

    break;  


      case'satO2' :
          // $recorddate = date('Y-m-d H:i:s', strtotime($obs->effectiveDateTime)); 
          if (array_key_exists("effectiveDateTime",$obs)){                     
            if($obs->effectiveDateTime==null || $obs->effectiveDateTime==''){
              throw new Exception('Observation EffectiveDateTime null or blank');
            }
            else{
              $recorddate = date('Y-m-d H:i:s', strtotime($obs->effectiveDateTime));
            }
          }
          else{
              throw new Exception('Observation EffectiveDateTime key missing alert'); //alert condition
          }


          $code = $obs->code;
          $coding =$code->coding[0];

          if (array_key_exists("display",$coding)){
            $dis = $coding->display;

          if($dis==null || $dis==""){
            throw new Exception('Saturation-O2 display null or blank alert');
          }
          else{
          if($dis == 'Hemoglobin saturation with oxygen'){
            $v = $obs->valueQuantity;

            if (array_key_exists("value",$v)  )
            {
                  $o2value = $v->value;
                  if($o2value==null || $o2value=="" ){
                    throw new Exception('Saturation-O2 value null or blank alert'); //alert condition
                  }
                  
            }else{
                  throw new Exception('Saturation-O2 value key missing alert'); //alert condition
            }

            if (array_key_exists("unit",$v)  )
            {
                    $o2unit  = $v->unit;
                    if($o2unit==null || $o2unit=="" ){
                      throw new Exception('Saturation-O2 unit null or blank alert'); //alert condition
                    }
                    
            }else{
                    throw new Exception('Saturation-O2 unit key missing alert'); //alert condition
            }

            if (array_key_exists("code",$v)  )
            {
                    $o2code  = $v->code; 
                    if($o2code==null || $o2code=="" ){
                      throw new Exception('Saturation-O2 code null or blank'); //alert condition
                    }
                   
            }else{
                    throw new Exception('Saturation-O2 code key missing'); //alert condition   
            }

           

             //after inserting update processedflag = 1
             $checko2 = Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
             ->where('effdatetime',$recorddate)->get();
             $updiasdata = array(
              'effdatetime'=> $recorddate,
               'oxy_qty'=>$o2value,
               'oxy_unit'=>$o2unit,
               'oxy_code'=> $o2code,
              );
            
              if($checko2->isEmpty()){
              }
              else{
                if($checko2[0]->oxy_qty==null || $checko2[0]->oxy_qty==''){
                  $obdia =  Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                  ->where('effdatetime',$recorddate)->update($updiasdata);  
                  // dd($obdia);    
                 }
              }
           
         
   //  ----------------------------  alert status code------------------------------------------------------------------------------

        $checkpatientthreshold = PatientThreshold::where('patient_id',$patientid)->get();
        if($checkpatientthreshold->isEmpty())
        {
          $checkpractice = PatientProvider::where('patient_id',$patientid)->where('is_active',1)->where('provider_type_id',1)->first();
              if(!is_null($checkpractice)){
                $checkinpracticethreshold = PracticeThreshold::where('practice_id',$checkpractice->practice_id)->get();
              } 
              
          
              if($checkpractice==null || $checkinpracticethreshold->isEmpty()){
                $gt = GroupThreshold::get();

                //checking in groupthreshold  
                if(($o2value > $gt[0]->oxsathigh) || ($o2value < $gt[0]->oxsatlow ))
                {
                  Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                ->where('effdatetime',$recorddate)->update(['alert_status'=>1]);
                } 
              }
              else{
                    //checking in practicethreshold
                    if(($o2value > $checkinpracticethreshold[0]->oxsathigh) || ($o2value < $checkinpracticethreshold[0]->oxsatlow ))
                    {
                      Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                    ->where('effdatetime',$recorddate)->update(['alert_status'=>1]);
                    }
              } 
        }
        else{
            //checking in patient threshold
          if(($o2value > $checkpatientthreshold[0]->oxsathigh) || ($o2value < $checkpatientthreshold[0]->oxsatlow ))
          {
            Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
            ->where('effdatetime',$recorddate)->update(['alert_status'=>1]);
          }
        }  
   
//  ----------------------------  alert status code------------------------------------------------------------------------------ 
          


          }// if loop
        
          
        }
      
        }
        else{
          throw new Exception('Saturation-O2 display key missing alert'); //alert condition  
        }



      break;


      case'heart-rate':

        $vitaldeviceid = 0;
        

        if (array_key_exists("effectiveDateTime",$obs)){                     
          if($obs->effectiveDateTime==null || $obs->effectiveDateTime==''){
            throw new Exception('Observation EffectiveDateTime null or blank');
          }
          else{
            $recorddate = date('Y-m-d H:i:s', strtotime($obs->effectiveDateTime));
          }
        }
        else{
            throw new Exception('Observation EffectiveDateTime key missing alert'); //alert condition
        }


        $code = $obs->code;
        $coding =$code->coding[0];
        $dis = $coding->display;

        if (array_key_exists("display",$coding)){ 
          $dis = $coding->display;
       

        if($dis == 'Resting heart rate')
        {
            // dd($dis);
            $v = $obs->valueQuantity;
            if (array_key_exists("value",$v)  )
            {
                  $heartratevalue = $v->value;
                  if($heartratevalue==null || $heartratevalue=="" ){
                    throw new Exception('Heartrate value null or blank alert'); //alert condition
                  }
                  
            }else{
                  throw new Exception('Heartrate value key missing alert'); //alert condition
            }

            if (array_key_exists("unit",$v)  )
            {
                    $heartrateunit  = $v->unit;
                    if($heartrateunit==null || $heartrateunit=="" ){
                      throw new Exception('Heartrate unit null or blank alert'); //alert condition
                    }
                    
            }else{
                    throw new Exception('Heartrate unit key missing alert'); //alert condition
            }

            if (array_key_exists("code",$v)  )
            {
                    $heartratecode  = $v->code;  
                    if($heartratecode==null || $heartratecode=="" ){
                      throw new Exception('Heartrate code null or blank'); //alert condition
                    }
                   
            }else{
                    throw new Exception('Heartrate code key missing'); //alert condition   
            }
              
           
           
             //after inserting update processedflag = 1
             $upheartratedata = array(
              'effdatetime'=> $recorddate,
              'resting_heartrate'=>$heartratevalue,
              'resting_heartrate_unit'=>$heartrateunit,
              'resting_heartrate_code'=>$heartratecode
             );
             if (in_array("blood-pressure", $idarrays)){ 
              $checkbp = Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)->get();
             

              if($checkbp->isEmpty()){

              }
              else{
                    if($checkbp[0]->resting_heartrate==null || $checkbp[0]->resting_heartrate==''){
                      $obhrtrate =  Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)->where('effdatetime',$recorddate)->update($upheartratedata);
                      $vitaldevice = Devices::where('device_name','Blood Pressure Cuff')->where('status',1)->first();
                      $vitaldeviceid = $vitaldevice->id;   
                    }
                  
                   }
              }else if((in_array("satO2", $idarrays))){
                $checkhrt = Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)->get();
                  if($checkhrt->isEmpty()){  
                  }
                  else{
                    if($checkhrt[0]->resting_heartrate==null || $checkhrt[0]->resting_heartrate==''){
                      $obhrtrate =  Observation_Oxymeter::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)->where('effdatetime',$recorddate)->update($upheartratedata);
                      $vitaldevice = Devices::where('device_name','Pulse Oximeter')->where('status',1)->first();
                      $vitaldeviceid = $vitaldevice->id;
                          
                    }
                  }
              }
              else{
                $vitaldeviceid = 0;
              }
              


             $checkhrtrate = Observation_Heartrate::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)->where('effdatetime',$recorddate)->get();
             if(count($checkhrtrate)==0){
              $upheartratedata = array(
                'effdatetime'=> $recorddate,
                'resting_heartrate'=>$heartratevalue,
                'resting_heartrate_unit'=>$heartrateunit,
                'resting_heartrate_code'=>$heartratecode,
                'careplan_no' => null,
                'device_id' => $deviceid,
                'effdatetime'=>$recorddate,
                'mrn'=>$mrn_no,
                'created_by'=>'Symtech',
                'updated_by'=>'Symtech',
                'patient_id'=>$patientid,
                'reviewed_flag'=>0,
                'reviewed_date'=>null,
                'observation_id'=>$observationid,
                'billing'=>0,
                'vital_device_id'=>$vitaldeviceid
               );
              $obhrtrate =  Observation_Heartrate::create($upheartratedata);
              // dd($obhrtrate);
             }

    //  ----------------------------  alert status code------------------------------------------------------------------------------

        $checkpatientthreshold = PatientThreshold::where('patient_id',$patientid)->get();
        if($checkpatientthreshold->isEmpty())
        {
          $checkpractice = PatientProvider::where('patient_id',$patientid)->where('is_active',1)->first();
          if(!is_null($checkpractice)){
            $checkinpracticethreshold = PracticeThreshold::where('practice_id',$checkpractice->practice_id)->get();
          } 
          
          
              if($checkpractice==null || $checkinpracticethreshold->isEmpty()){
                $gt = GroupThreshold::get();

                //checking in groupthreshold  
                if(($heartratevalue > $gt[0]->bpmhigh) || ($heartratevalue < $gt[0]->bpmlow ))
                {
                  Observation_Heartrate::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                ->where('effdatetime',$recorddate)->update(['alert_status'=>1]);
                } 
              }
              else{
                    //checking in practicethreshold
                    if(($heartratevalue > $checkinpracticethreshold[0]->bpmhigh) || ($heartratevalue < $checkinpracticethreshold[0]->bpmlow ))
                    {
                      Observation_Heartrate::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
                    ->where('effdatetime',$recorddate)->update(['alert_status'=>1]);
                    }
              } 
        }
        else{
            //checking in patient threshold
          if(($heartratevalue > $checkpatientthreshold[0]->bpmhigh) || ($heartratevalue < $checkpatientthreshold[0]->bpmlow ))
          {
            Observation_Heartrate::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
            ->where('effdatetime',$recorddate)->update(['alert_status'=>1]);  
          }  
        }       
   
//  ----------------------------  alert status code------------------------------------------------------------------------------ 

        }  
      }   
      else{
        throw new Exception('Heartrate display key missing alert'); //alert condition  
      }
      break;    
     

       
   
  }  //end switch 
}


   //created by radha 16aug2021 (get data from api and save in order log)
  public function saveOrderLog(Request $request)
  {
         
    $getdatawebhook= WebhookOrders::where('status','1')->where('fetch_status','0')->get();
    for($i=0;$i<count($getdatawebhook);$i++)
    {
     try {
         $source_id='';
         $orderid='';
        $id=$getdatawebhook[$i]->id;
        $datajson=json_decode($getdatawebhook[$i]->content);
         if (array_key_exists("order_id",$datajson)) {
             $orderid=$datajson->order_id;  
         }
          if (array_key_exists("source_id",$datajson)) {
            $source_id=$datajson->source_id; 
         }
         if($source_id=='' && $orderid!='')
         {
              $source_id=$orderid;
         }
        
       if($orderid!='' && $source_id!='')
         {
         $ecgcredetials=ApiECGCredeintials(); 
         $this->getAuthorization();
     
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,  $ecgcredetials[0]->url.'groups/'.$ecgcredetials[0]->group_name.'/orders/'.$source_id);         
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
         "Content-Type: application/json",
         "Authorization: Bearer ".session()->get('TokenId')]
       );
         
       $response = curl_exec($ch);
       curl_close($ch);

       $getdata=json_decode($response);
      if(array_key_exists("message", $getdata)) {

         }
        else
        {
        $devicecode=$getdata->device_id;
        
        $patdata = PatientDevices::where('device_code',$devicecode)->where('source_id',$source_id)->where('status',1)->get();
       $patientid = $patdata[0]->patient_id;
       $partner_device_id = $patdata[0]->partner_device_id;
       $mrn_no = $patdata[0]->mrn_no;
      
           $dataorder=array( 'order_id'=>$getdata->order_id,
              'source_id'=>$getdata->source_id, 
              'mrn'=>$getdata->mrn,
              'status'=>1,
              'created_by'=>session()->get('userid'),
              'processed_flag'=>1,
              'api_url'=>$ecgcredetials[0]->url.'groups/'.$$ecgcredetials[0]->group_name.'/orders/'.$source_id,
              'patient_id'=>$patientid,
              'partner_id'=>$partner_device_id,
              'device_code'=>$getdata->device_id,
              'order_date'=>$getdata->order_date,
              'order_status'=>$getdata->order_status,
              'active_date'=>$getdata->active_date);
                  
                  OrderLog::create($dataorder);
     
        $updatestatus = WebhookOrders::where('id',$id)->update(['fetch_status'=>1]);  
        
       }
     }
       } catch(Exception $e) {
          return response()->json("Failed");
         }
     }  
     return response()->json("Data inserted successfully!");
  }  


 

  public function savePatientThreshold(){


    $ecgcredetials=ApiECGCredeintials();   
    $this->getAuthorization();   
    $groupname= $ecgcredetials[0]->group_name;     
    $patdata = PatientDevices::where('status',1)
    // ->where('id',17)
    // ->take(10)
    // ->skip(0)
    ->get();  
    // dd($patdata); //remaing 22 donw wid 27
    
    foreach($patdata as $p){           
      $deviceid =  $p->device_code;       
      $patientid = $p->patient_id;
     
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $ecgcredetials[0]->url.'groups/'.$groupname.'/devices/'.$deviceid.'/thresholds');         
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Content-Type: application/json",
      "Authorization: Bearer ".session()->get('TokenId')]
      );
      
      $response = curl_exec($ch); 
     
      curl_close($ch); 
      $getdata=json_decode($response);
      // dd($getdata);  
      
          // dd($gd);
          $p=array('bpmhigh'=>null,
                    'bpmlow'=>null,
                    'diastolichigh'=>null,
                    'diastoliclow'=>null,
                    'glucosehigh'=>null,
                    'glucoselow'=>null,
                    'oxsathigh'=>null,
                    'oxsatlow'=>null,
                    'systolichigh'=>null,
                    'systoliclow'=>null,
                    'temperaturehigh'=>null,
                    'temperaturelow'=>null,
                    'spirometerfevhigh'=>null,
                    'spirometerfevlow'=>null,
                    'spirometerpefhigh'=>null,
                    'spirometerpeflow'=>null,
                    'weighthigh'=>null,
                    'weightlow'=>null,
                    'patient_id'=>$patientid,
                    'eff_date'=>null


                  );

          PatientThreshold::create($p); 


      foreach($getdata as $gd){      // dd($gd->vitalType);
          if(isset($gd->vitalType)){

          
          if($gd->vitalType=="BPM"){           
             $bpmhigh = $gd->maximum;
             $bpmlow =  $gd->minimum;
             $b = array('bpmhigh'=>$bpmhigh,'bpmlow'=>$bpmlow);
             $v= PatientThreshold::where('patient_id',$patientid)->update($b);
           
          }
          else if($gd->vitalType=="Diastolic"){
            $diastolichigh = $gd->maximum;
            $diastoliclow = $gd->minimum;
            $b = array('diastolichigh'=>$bpmhigh,'diastoliclow'=>$bpmlow);
            PatientThreshold::where('patient_id',$patientid)->update($b);

          }
          else if($gd->vitalType=="Systolic"){
            $systolichigh = $gd->maximum;
            $systoliclow = $gd->minimum;
            $b = array('systolichigh'=>$systolichigh,'systoliclow'=>$systoliclow);
            PatientThreshold::where('patient_id',$patientid)->update($b);

          }
          else if($gd->vitalType=="Weight"){           
            $weighthigh = $gd->maximum;
            $weightlow = $gd->minimum;   
            $b = array('weighthigh'=>$weighthigh,'weightlow'=>$weightlow);
            $a = PatientThreshold::where('patient_id',$patientid)->update($b);
           
          }
          else if($gd->vitalType=="Oxygen"){
           $oxsathigh = $gd->maximum;
           $oxsatlow = $gd->minimum; 
           $b = array('oxsatlow'=>$oxsatlow, 'oxsathigh'=> $oxsathigh);
           $a = PatientThreshold::where('patient_id',$patientid)->update($b); 

          }
          else{
            // dd($p,$gd); 
            return response()->json("Data with different vitals apart from BPM,Diastolic,Systolic,Weight,Oxygen !");
          }
          
        }
        else{
          // dd($gd);
          // "No thresholds found for group name: ANS1953"
        }

      }
      

    }

    return response()->json("Data inserted successfully!");
  }
  
  public function saveGroupThreshold(){

    $ecgcredetials=ApiECGCredeintials();   
    $this->getAuthorization();   
    $groupname= $ecgcredetials[0]->group_name;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ecgcredetials[0]->url.'groups/'.$groupname.'/thresholds');          
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer ".session()->get('TokenId')]
    );
    
    $response = curl_exec($ch); 
   
    curl_close($ch); 
    $getdata=json_decode($response);

    // dd($getdata);
    $group=array('bpmhigh'=>$getdata->BPMHigh,
              'bpmlow'=>$getdata->BPMLow,
              'diastolichigh'=>$getdata->DiastolicHigh,
              'diastoliclow'=>$getdata->DiastolicLow,
              'glucosehigh'=>$getdata->GlucoseHigh,
              'glucoselow'=>$getdata->GlucoseLow,
              'oxsatlow'=>$getdata->OxSatLow,
              'oxsathigh'=>$getdata->OxSatHigh,
              'systoliclow'=>$getdata->SystolicLow,
              'systolichigh'=>$getdata->SystolicHigh,
              'temperaturelow'=>$getdata->TemperatureLow,
              'temperaturehigh'=>$getdata->TemperatureHigh,
              'spirometerfevhigh'=>null,
              'spirometerfevlow'=>null,
              'spirometerpefhigh'=>null,
              'spirometerpeflow'=>null,
              'weighthigh'=>null,
              'weightlow'=>null,
              'group_code'=>$getdata->GroupID
              );
                 
              // dd($group);

      GroupThreshold::create($group);  


  }

  public function updateRPMPatientAlert(Request $request){  

    $getdata =  \DB::select(DB::raw("select p.* from rpm.patient_rpm_observations p 
                inner join patients.partner_patient_alerts ppa on p.effdatetime = ppa.readingtimestamp and p.device_id = ppa.device_code
                where ppa.match_status is null ")); 
               
                

   
                // dd($getdata);         
                // dd($getdata[0]->device_code); 
                
                foreach($getdata as $gdata)
                {

                  // dd($gdata);
                  // dd($gdata->alert_status);
                  // dd($gdata->device_code,$gdata->readingtimestamp );
                     
                  $partnerpatientdata = \DB::table('patients.partner_patient_alerts')
                                        ->where('device_code',$gdata->device_id)
                                        ->where('readingtimestamp',$gdata->effdatetime)
                                        ->get();  

                   if(count($partnerpatientdata)>0)  
                   {

                    // dd($partnerpatientdata[0]->id);

                    $update = \DB::table('patients.partner_patient_alerts')
                              ->where('id',$partnerpatientdata[0]->id)
                              ->update(['match_status'=>1,
                                        'webhook_observation_id'=>$gdata->observation_id]);   

                            if($gdata->alert_status =='1')
                            {

                              $updatealert = \DB::table('patients.partner_patient_alerts')
                                              ->where('id',$partnerpatientdata[0]->id)
                                              ->update([ 'alert_status'=>1 ]);
                                                          
                            }



                   }         

                            
                  
                }

                return response()->json("Data with match status !");

   

  }

  //createdby radha(29oct2021)
    public static function alertAddressedBy($devicecode,$timestamp)
    {
        $deviceid=$devicecode;
        $timestamp=$timestamp;
        $userid=session()->get('userid');
        $userfname=session()->get('f_name');
        $userlname=session()->get('l_name');
        $username=$userfname." ".$userlname;
        $data='{
                  "addressedBy": "'.$username.'"
                }';
              $ecgcredetials=ApiECGCredeintials();  
              self::getAuthorization();
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $ecgcredetials[0]->url.'devices/'.$deviceid.'/alerts/'.$timestamp.'/address');
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            
              curl_setopt($ch, CURLOPT_HTTPHEADER, [
               "Content-Type: application/json",
                "Authorization: Bearer ".session()->get('TokenId')]
             );


               curl_setopt($ch, CURLOPT_POST, 1);
               
               curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
               
               // Send the request & save response to $resp
               
             $response =  curl_exec($ch);
              $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
             curl_close($ch);
            return $response;
    }

    //createdby ashvini(22nov2021)
    public static function otherAlerts()
    {

       $getdata =  \DB::select(DB::raw("INSERT INTO api.other_alerts (
    patient_id, 
    mrn_no, 
    reviewed_flag,
    reviewed_date,
    alert_status,
    addressed,
    addressed_date, 
    created_by,
    updated_by,
    created_at,
    updated_at,
    device_id
    )
      select  
      pt.id, 
      pp.practice_emr,
      0,
     null,
      0,
      0,
      null,
      1,
      1,
      current_timestamp, 
      current_timestamp,
      vpd.vitaldevice_id
   from patients.patient pt 
        inner join patients.patient_services ps
         on   pt.id = ps.patient_id  and ps.module_id in (2, 8)
--         inner join rpm.observations_bp bp 
--         on pt.id = bp.patient_id 
         inner join patients.view_patient_devices vpd 
         on pt.id = vpd.patient_id and vitaldevice_id = 3
         
         inner join patients.patient_providers pp
          on  pt.status = 1 
          and pt.id = pp.patient_id 
          and pp.provider_type_id = 1
          and pp.is_active = 1
              
              
         where  (pt.id) not in 
         
        (select distinct p.id
--        p.fname,p.lname,p.email,ob.effdatetime
        from patients.patient p 
        inner join patients.patient_services ps
         on   p.id = ps.patient_id  and ps.module_id in (2, 8)
         inner join rpm.observations_bp ob 
         on p.id = ob.patient_id 
         and ob.effdatetime > current_timestamp - interval '3 day');
      --end blood pressure
      "));


        
 $getdata1 =  \DB::select(DB::raw("INSERT INTO api.other_alerts (   
    patient_id, 
    mrn_no, 
    reviewed_flag,
    reviewed_date,
    alert_status,
    addressed,
    addressed_date, 
    created_by,
    updated_by,
    created_at,
    updated_at,
    device_id
    )
      select
      pt.id, 
      pp.practice_emr,
      0,
     null,
      0,
      0,
      null,
      1,
      1,
      current_timestamp, 
      current_timestamp,
      vpd.vitaldevice_id
   from patients.patient pt 
        inner join patients.patient_services ps
         on   pt.id = ps.patient_id  and ps.module_id in (2, 8)
--         inner join rpm.observations_bp bp 
--         on pt.id = bp.patient_id 
         inner join patients.view_patient_devices vpd 
         on pt.id = vpd.patient_id and vitaldevice_id = 2
         
         inner join patients.patient_providers pp
          on  pt.status = 1 
          and pt.id = pp.patient_id 
          and pp.provider_type_id = 1
          and pp.is_active = 1
              
              
         where  (pt.id) not in 
         
        (select distinct p.id
--        p.fname,p.lname,p.email,ob.effdatetime
        from patients.patient p 
        inner join patients.patient_services ps
         on   p.id = ps.patient_id  and ps.module_id in (2, 8)
         inner join rpm.observations_oxymeter oo
         on p.id = oo.patient_id 
         and oo.effdatetime > current_timestamp - interval '3 day');
        ----end oxymeter
        "));
        
        
        
        
   $getdata2 =  \DB::select(DB::raw("INSERT INTO api.other_alerts (
    patient_id, 
    mrn_no, 
    reviewed_flag,
    reviewed_date,
    alert_status,
    addressed,
    addressed_date, 
    created_by,
    updated_by,
    created_at,
    updated_at,
    device_id
    )
      select 
      pt.id, 
      pp.practice_emr,
      0,
     null,
      0,
      0,
      null,
      1,
      1,
      current_timestamp, 
      current_timestamp,
      vpd.vitaldevice_id
   from patients.patient pt 
        inner join patients.patient_services ps
         on   pt.id = ps.patient_id  and ps.module_id in (2, 8)
--         inner join rpm.observations_bp bp 
--         on pt.id = bp.patient_id 
         inner join patients.view_patient_devices vpd 
         on pt.id = vpd.patient_id and vitaldevice_id = 6
         
         inner join patients.patient_providers pp
          on  pt.status = 1 
          and pt.id = pp.patient_id 
          and pp.provider_type_id = 1
          and pp.is_active = 1
              
              
         where  (pt.id) not in 
         
        (select distinct p.id
--        p.fname,p.lname,p.email,ob.effdatetime
        from patients.patient p 
        inner join patients.patient_services ps
         on   p.id = ps.patient_id  and ps.module_id in (2, 8)
         inner join rpm.observations_glucose og 
         on p.id = og.patient_id 
         and og.effdatetime > current_timestamp - interval '3 day');
        ----end glucose
        "));
        
        
        
    $getdata3 =  \DB::select(DB::raw("INSERT INTO api.other_alerts (  
    patient_id, 
    mrn_no, 
    reviewed_flag,
    reviewed_date,
    alert_status,
    addressed,
    addressed_date, 
    created_by,
    updated_by,
    created_at,
    updated_at,
    device_id
    )
      select 
      pt.id, 
      pp.practice_emr,
      0,
     null,
      0,
      0,
      null,
      1,
      1,
      current_timestamp, 
      current_timestamp,
      vpd.vitaldevice_id
   from patients.patient pt 
        inner join patients.patient_services ps
         on   pt.id = ps.patient_id  and ps.module_id in (2, 8)
--         inner join rpm.observations_bp bp 
--         on pt.id = bp.patient_id 
         inner join patients.view_patient_devices vpd 
         on pt.id = vpd.patient_id and vitaldevice_id = 5
         
         inner join patients.patient_providers pp
          on  pt.status = 1 
          and pt.id = pp.patient_id 
          and pp.provider_type_id = 1
          and pp.is_active = 1
              
              
         where  (pt.id) not in 
         
        (select distinct p.id
--        p.fname,p.lname,p.email,ob.effdatetime
        from patients.patient p 
        inner join patients.patient_services ps
         on   p.id = ps.patient_id  and ps.module_id in (2, 8)
         inner join rpm.observations_spirometer os
         on p.id = os.patient_id 
         and os.effdatetime > current_timestamp - interval '3 day'); 
        --end spirometer
        "));
        
    $getdata4 =  \DB::select(DB::raw("INSERT INTO api.other_alerts ( 
    patient_id, 
    mrn_no, 
    reviewed_flag,
    reviewed_date,
    alert_status,
    addressed,
    addressed_date, 
    created_by,
    updated_by,
    created_at,
    updated_at,
    device_id
    )
      select 
      pt.id, 
      pp.practice_emr,
      0,
     null,
      0,
      0,
      null,
      1,
      1,
      current_timestamp, 
      current_timestamp,
      vpd.vitaldevice_id
   from patients.patient pt 
        inner join patients.patient_services ps
         on   pt.id = ps.patient_id  and ps.module_id in (2, 8)
--         inner join rpm.observations_bp bp 
--         on pt.id = bp.patient_id 
         inner join patients.view_patient_devices vpd 
         on pt.id = vpd.patient_id and vitaldevice_id = 1
         
         inner join patients.patient_providers pp
          on  pt.status = 1 
          and pt.id = pp.patient_id 
          and pp.provider_type_id = 1
          and pp.is_active = 1
              
              
         where  (pt.id) not in 
         
        (select distinct p.id
--        p.fname,p.lname,p.email,ob.effdatetime
        from patients.patient p 
        inner join patients.patient_services ps
         on   p.id = ps.patient_id  and ps.module_id in (2, 8)
         inner join rpm.observations_weight ow
         on p.id = ow.patient_id 
         and ow.effdatetime > current_timestamp - interval '3 day');
        --end weight 
        ")); 


       
    }





}