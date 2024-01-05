<?php

namespace RCare\Org\OrgPackages\RPMBillingConfiguration\src\Http\Controllers;  
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\RPMBillingConfiguration\src\Http\Requests\RPMBillingAddRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\RPMBillingConfiguration\src\Models\RPMBilling; 
use DataTables;
use Session; 
use Illuminate\Support\Facades\Log;

class RPMBillingController extends Controller {  


    public function populateRPMBillingConfiguration() {   
	
        //$id = sanitizeVariable($request->id);
        $billing = (RPMBilling::self() ? RPMBilling::self()->population() : "");
        $result['rpm_billing_form'] = $billing;  
        return $result;

   } 
  
    public function saveRPMBillingConfiguration(RPMBillingAddRequest $request)
    {
       
        $vitalreviewtime = sanitizeVariable($request->vital_review_time); 
        $required_billing_days = sanitizeVariable($request->required_billing_days);
        $billing_phone   = sanitizeVariable($request->billing_phone);
        $billing_fname   = sanitizeVariable($request->billing_fname);
        $billing_lname   = sanitizeVariable($request->billing_lname);
        $billing_address = sanitizeVariable($request->billing_address);
        $billing_city    = sanitizeVariable($request->billing_city);
        $billing_state   = sanitizeVariable($request->billing_state);
        $billing_zip     = sanitizeVariable($request->billing_zip);
        $billing_email   = sanitizeVariable($request->billing_email);     
        $headquaters_phone   = sanitizeVariable($request->headquaters_phone);
        $headquaters_fname   = sanitizeVariable($request->headquaters_fname);
        $headquaters_lname   = sanitizeVariable($request->headquaters_lname);
        $headquaters_address = sanitizeVariable($request->headquaters_address);
        $headquaters_city    = sanitizeVariable($request->headquaters_city);
        $headquaters_state   = sanitizeVariable($request->headquaters_state);
        $headquaters_zip     = sanitizeVariable($request->headquaters_zip);
        $headquaters_email   = sanitizeVariable($request->headquaters_email);
        $headqflag=sanitizeVariable($request->headqadd);       
        // if($headqflag=='true')
        // {
        //     $headquaters_phone   =  $billing_phone ;
        //     $headquaters_fname   =  $billing_fname ;
        //     $headquaters_lname   =  $billing_lname ;
        //     $headquaters_address =  $billing_address;
        //     $headquaters_city    =  $billing_city  ;
        //     $headquaters_state   =  $billing_state  ;
        //     $headquaters_zip     =  $billing_zip    ;
        //     $headquaters_email   =  $billing_email  ;
        // }

        // if($vitalreviewtime=='00:00:00' || $vitalreviewtime=='24:00:00'|| $vitalreviewtime=='23:59:59' ){
        //     $msg = 'time is not valid';
        //     return $msg;
        // }

        $data =array('vital_review_time'=> $vitalreviewtime ,
                    'required_billing_days'=> $required_billing_days,
                    'billing_phone'=> $billing_phone,
                    'billing_fname'=>$billing_fname,
                    'billing_lname'=>$billing_lname,
                    'billing_address' =>$billing_address,
                    'billing_city' =>$billing_city,
                    'billing_state' =>$billing_state,
                    'billing_zip' =>$billing_zip,
                    'billing_email' =>$billing_email,                   
                    'headquaters_phone' =>$headquaters_phone,
                    'headquaters_fname' =>$headquaters_fname,
                    'headquaters_lname' =>$headquaters_lname,
                    'headquaters_address' =>$headquaters_address,
                    'headquaters_city' =>$headquaters_city,
                    'headquaters_state' =>$headquaters_state,
                    'headquaters_zip' =>$headquaters_zip,
                    'headquaters_email'=>$headquaters_email
                    );
           
        $checkrpmbilling = RPMBilling::exists();
        if($checkrpmbilling==true){    
           $rpm = RPMBilling::get();
            $update_bill = RPMBilling::where('id',$rpm[0]->id)->update($data); 
        }else{          
            $insert_practice = RPMBilling::create($data);  
        }
         
        
    }

   

} 