<?php
namespace RCare\Org\OrgPackages\Partner\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Partner\src\Http\Requests\PartnerRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\Partner\src\Models\Partner;
use RCare\Org\OrgPackages\Partner\src\Models\PartnerDevices;
use RCare\Org\OrgPackages\Partner\src\Models\PartnerApiConfiguration;
//use RCare\Org\OrgPackages\Medication\src\Models\Providers;
use DataTables;
use Hash;
use Redirect,Response;
use Session;
use File,DB;
use Illuminate\Validation\Rule;
use Validator;

class PartnerController extends Controller {
   
    //created by radha 20july2021
    public function addPartner(PartnerRequest $request) {
        // dd($request->all()); 
        $username = sanitizeVariable($request->username);
        $url = sanitizeVariable($request->url);
        $password = sanitizeVariable($request->password);
        $status = sanitizeVariable($request->status);
        $env = sanitizeVariable($request->env);
        $devices = sanitizeVariable($request->devices);
        $partner_device_name = sanitizeVariable($request->partner_device_name);

        // dd($url,$status);
        $name = sanitizeVariable($request->name);
        $email = sanitizeVariable($request->email);
        $phone = sanitizeVariable($request->phone);
        $add1 = sanitizeVariable($request->add1);
        $add2 = sanitizeVariable($request->add2);
        $city = sanitizeVariable($request->city);
        $state = sanitizeVariable($request->state);
        $zipcode = sanitizeVariable($request->zip);
        $contact_person = sanitizeVariable($request->contact_person);
        $contact_person_phone = sanitizeVariable($request->contact_person_phone);
        $contact_person_email = sanitizeVariable($request->contact_person_email);
        $created_by  = session()->get('userid');
        $updated_by  = session()->get('userid');
        
        $status = '1';
        $partner_array = array(
            'name'=>$name,
            'add1'=>$add1,
            'add2'=>$add2,
            'email'=>$email,     
            'city'=>$city,
            'state'=>$state,
            'zip'=>$zipcode,  
            'phone'=>$phone,     
            'contact_person'=>$contact_person,
            'contact_person_phone'=>$contact_person_phone,
            'contact_person_email'=>$contact_person_email,
            'created_by'=>$created_by,    
            'updated_by'=>$created_by,         
            'status'=>$status,
            'category'=>1
        );
        $insert_partner = Partner::create($partner_array);
        $partner_id = $insert_partner->id;
        // $partner_id = 1;
        // dd($partner_id);

        foreach($partner_device_name as $key=>$value)
        {
            $partnerdevices_array = array(
                'partner_id'=>$partner_id,
                'device_name_api'=>$value,
                'device_id'=>$devices[$key],
                'device_attr'=>null,
                'status'=>1,     
                'created_by'=>$created_by,
                'updated_by'=>$created_by,
                'device_order_seq'=>1
                
            );
            // dd($partner_array);
            $insert_partnerdevices = PartnerDevices::create($partnerdevices_array);     
        }


         foreach($url as $key=>$value)
         {
            // var_dump($key,$value);
            // $newstatus = $status[$key];
            
            // $numstatus = (int)$newstatus;
            // var_dump($numstatus);
            
             $partnerapiconfig = array(
                'url'=>$value,
                'username'=>$username[$key],
                'password'=>$password[$key],
                'status'=>1,     
                'env'=>$env[$key],
                'partner_id'=>$partner_id
                
            );
            // dd($partner_array);
            $insert_partnerapiconfig = PartnerApiConfiguration::create($partnerapiconfig);
          
         }     
       
        
    }

    //showing list
    public function PartnerList(Request $request) {
        if ($request->ajax()) {
        $data = Partner::with('users')->latest()->get();
      
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($row){
        $btn =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
        if($row->status == 1){           
            $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_partnerstatus_active"><i class="i-Yess i-Yes" title="Active"></i></a>';
        } else {         
            $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_partnerstatus_deactive"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
        }
        return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
        }
     
    }

    public function populatePartner(Request $request) {   

        $id = sanitizeVariable($request->id);
        $org_partner = (Partner::self($id) ? Partner::self($id)->population() : "");
        $paramdata    = PartnerDevices::where('partner_id',$id)->where('status',1)->get()->toArray(); 
        $partnerapiparamdata = PartnerApiConfiguration::where('partner_id',$id)->where('status',1)->get()->toArray(); 

        if($paramdata){ 
            $partnerdeviceparamdata      = array('paramdata'=>$paramdata);
            $org_partner['static'] = array_merge($org_partner['static'], $partnerdeviceparamdata);
        }
        if($partnerapiparamdata){ 
            $newpartnerapiparamdata      = array('partnerapiparamdata'=>$partnerapiparamdata);
            $org_partner['static'] = array_merge($org_partner['static'], $newpartnerapiparamdata);
        }


        $result['editPartnerForm'] = $org_partner;  
        // dd($result);
        
        
        return $result;
    }

   
    public function updatePartner(PartnerRequest $request) {
        // dd($request->all());  

        $username = sanitizeVariable($request->username);
        $url = sanitizeVariable($request->url);
        $password = sanitizeVariable($request->password);
        $status = sanitizeVariable($request->status);
        $env = sanitizeVariable($request->env);
        $devices = sanitizeVariable($request->devices);
        $partner_device_name = sanitizeVariable($request->partner_device_name);


        $id= sanitizeVariable($request->id);        
        $name = sanitizeVariable($request->name);
        $email = sanitizeVariable($request->email);
        $phone = sanitizeVariable($request->phone);
        $add1 = sanitizeVariable($request->add1);
        $add2 = sanitizeVariable($request->add2);
        $city = sanitizeVariable($request->city);
        $state = sanitizeVariable($request->state);
        $zipcode = sanitizeVariable($request->zip);
        $contact_person = sanitizeVariable($request->contact_person);
        $contact_person_phone = sanitizeVariable($request->contact_person_phone);
        $contact_person_email = sanitizeVariable($request->contact_person_email);
        $updated_by  = session()->get('userid');
        $created_by  = session()->get('userid');


        $update = array(
            'name'=>$name,
            'add1'=>$add1,
            'add2'=>$add2,
            'email'=>$email,     
            'city'=>$city,
            'state'=>$state,
            'zip'=>$zipcode,  
            'phone'=>$phone,      
            'contact_person'=>$contact_person,
            'contact_person_phone'=>$contact_person_phone,
            'contact_person_email'=>$contact_person_email,                    
            'status'=>1,
            'updated_by'=>$updated_by
        );
        $update_partner = Partner::where('id',$id)->update($update);
        $partner_id = $id;



        $deleteoldvaluespartnerdevices = PartnerDevices::where('partner_id',$partner_id)->delete();
        $partner_device_name = array_filter($partner_device_name);
        $devices = array_filter($devices);

        
        foreach($partner_device_name as $key=>$value)
        {

            if($value==null && $devices[$key]==null){              
            }
            else{                
                $partnerdevices_array = array(
                    'partner_id'=>$partner_id,
                    'device_name_api'=>$value,
                    'device_id'=>$devices[$key],
                    'device_attr'=>null,
                    'status'=>1,     
                    'created_by'=>$updated_by,
                    'updated_by'=>$updated_by,
                    'device_order_seq'=>1
                    
                );
                // $insert_partnerdevices = PartnerDevices::create($partnerdevices_array);  
            $data = PartnerDevices::where('partner_id',$partner_id)->where('device_name_api',$value)->where('device_id',$devices[$key])->get();
            
            if(count($data)>0){
                $insert_partnerdevices = PartnerDevices::where('partner_id',$partner_id)->where('device_id',$devices[$key])->update($partnerdevices_array);  
            }else{
                $insert_partnerdevices = PartnerDevices::create($partnerdevices_array);  
            } 

            } 
           

           
              
        }


        $deleteoldvaluespartnerapiconfiguration = PartnerApiConfiguration::where('partner_id',$partner_id)->delete();

        $url = array_filter($url);
        $username = array_filter($username);
        $password = array_filter($password);
        

        foreach($url as $key=>$value)
        {
            if($value==null && $username[$key]==null && $password[$key]==null ){              
            }else{
                $partnerapiconfig = array(
                    'url'=>$value,
                    'username'=>$username[$key],
                    'password'=>$password[$key],
                    'status'=>1,     
                    'env'=>$env[$key],
                    'partner_id'=>$partner_id
                    
                );
                
                $datapartnerapi = PartnerApiConfiguration::where('url',$value)
                                    ->where('username',$username[$key])
                                    ->where('password',$password[$key])
                                    ->where('status',1)
                                    ->where('env',$env[$key])
                                    ->where('partner_id',$partner_id)
                                    ->get();

                if(count($datapartnerapi)>0){
                    $update_datapartnerapi = PartnerDevices::where('partner_id',$partner_id)
                                            ->where('username',$username[$key])
                                            ->where('password',$password[$key])
                                            ->where('status',1)
                                            ->where('env',$env[$key])
                                            ->where('url',$value)
                                            ->update($datapartnerapi);  
                }else{
                    $insert_partnerapiconfig = PartnerApiConfiguration::create($partnerapiconfig); 
                }        
                // $insert_partnerapiconfig = PartnerApiConfiguration::create($partnerapiconfig);
            }
            
         
        }   
      
    }   

    public function changePartnerStatus(Request $request) {
        $id = sanitizeVariable($request->id);
        
        $data = Partner::where('id',$id)->get();
        //dd($data);
        $status =$data[0]->status;
        if($status==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = Partner::where('id',$id)->orderBy('id', 'desc')->update($status);
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = Partner::where('id',$id)->orderBy('id', 'desc')->update($status);
        }
    } 
    
} 