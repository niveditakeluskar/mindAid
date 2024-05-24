<?php
namespace RCare\API\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RCare\Patients\Models\PatientOrders;
use RCare\Patients\Models\PatientReading;
use RCare\Patients\Models\PatientDevices;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\Patients;
use RCare\API\Models\Partner;
use RCare\API\Http\Requests\AddPartnerRequest;
use RCare\API\Models\Webhook;
use RCare\API\Models\WebhookAlert;
use RCare\API\Models\WebhookObservation;
use RCare\API\Models\WebhookOrders;
use Carbon\Carbon;
use DB;
use DataTables;
use Session;
use Illuminate\Support\Str;
use Exception;
use RCare\API\Models\ApiException; 
use Illuminate\Support\Facades\Http;

class APIController extends Controller {
  
  public function InsertPartner(AddPartnerRequest $request){       
       $number=1;
       $getpartnerdata=Partner::orderBy('updated_at','desc')->first();      
       if(!empty($getpartnerdata))
       {
        $split=explode('0', $getpartnerdata->user_id);  
        $number=intval($split[1])+1;        
       }
       else
       {
        $number=$number;
       }
       
       $name=sanitizeVariable($request->partner_name);
       $email=sanitizeVariable($request->email);
       $phone=sanitizeVariable($request->phone);
       $location=sanitizeVariable($request->location);     
       $user_key = Str::uuid()->toString();      
       $user1 = strtoupper(substr($name, 0, 1));
       $userid=$user1."0".$number;

         $insertdata=array(
            'user_id'=>$userid, 
            'user_key'=> $user_key,
            'partner_name'=>$name,
            'location'=>$location,
            'email'=>$email,                     
            'phone'=>$phone          
        );  

        Partner::create($insertdata);  
    }



   public function PatientDevices(Request $request)
   {
         $user_id=sanitizeVariable($request->userId); 
         $token=sanitizeVariable($request->TokenId); 
         $patient_id=sanitizeVariable($request->REN_MRN); 
         $orderid=sanitizeVariable($request->order_id);
         $device_id=sanitizeVariable($request->Devices['ID']);       
         $partner_mrn=sanitizeVariable($request->MSP_MRN);     

       $partertoken= Partner::where('user_key',$token)->get();     
       if(!empty($partertoken))
       {
           $parteruser_id=Partner::where('user_id',$user_id)->get();           
           if(!empty($parteruser_id))
           { 
            $patientexistcheck= PatientProvider::where('practice_emr',$partner_mrn)->get();            
           if(!empty($patientexistcheck))
           { 
            $checkDevceExist= PatientDevices::where('device_id',$device_id)->where('order_id',$orderid)->exists();
            if($checkDevceExist==false){
     
         $mrn=sanitizeVariable($request->MSP_MRN);        
         $devicename=sanitizeVariable($request->Devices['name']); 
         $Hub=sanitizeVariable($request->Hub);   
         $orderdate=date('Y-m-d h:i:s');

        $insertdata=array(
            'patient_id'=>$patient_id, 
            'device_id'=>$device_id,
            'device_type'=>$devicename,            
            'uid'=>$patient_id,                     
            'hub'=>$Hub,
            'status'=>1,
            'partner_mrn'=>$partner_mrn, 
            'order_id'=>$orderid
           
        );

         $Orderdata=array(
            'patient_id'=>$patient_id, 
            'order_date'=>$orderdate,
            'partner_mrn'=>$partner_mrn,
            'device'=>$devicename,                                 
            'hub'=>$Hub,          
            'order_id'=>$orderid
        );
         
       //for device  
       $existsDevice=PatientDevices::where('device_id',$device_id)->exists();
    
       if($existsDevice==true)
       {
         PatientDevices::where('device_id',$device_id)->update($insertdata);
       }
       else
       {        
         PatientDevices::create($insertdata);
       }
        //for order 
        $existsOrder=PatientOrders::where('order_id',$orderid)->exists();
        if($existsOrder==true)
       {
         PatientOrders::where('order_id',$orderid)->update($Orderdata);
          $response=array("Code"=>"201","Description"=>"Order updated successfully!");
          $res=json_encode($response);
           return $res;
       }
       else
       {
        
        PatientOrders::create($Orderdata);
          $response=array("Code"=>"201","Description"=>"Order created successfully!");
          $res=json_encode($response);
           return $res;
       }
       }
       else
       {
           $response=array("Code"=>"400","Description"=>"This Order already exists!");
           $res=json_encode($response);
           return $res;
       }
     }
     else
     {
       $response=array("Code"=>"400","Description"=>"Invalid Patient MRN!");
      $res=json_encode($response);
      return $res;
     }
   }
   else
   {
       $response=array("Code"=>"400","Description"=>"Invalid userID!");
      $res=json_encode($response);
      return $res;
   }
}
else
{
    $response=array("Code"=>"401","Description"=>"User Authetication Failed!");
   $res=json_encode($response);
 return $res; 
}
   }

    public function PatientOrderPost(Request $request)
    {   

       $user_id=sanitizeVariable($request->userId); 
         $token=sanitizeVariable($request->TokenId); 
         $patient_id=sanitizeVariable($request->REN_MRN); 
       $partertoken=  Partner::where('user_key',$token)->get();
      
       if(!empty($partertoken))
       {
             $parteruser_id=  Partner::where('user_id',$user_id)->get();               
           if(!empty($parteruser_id))
           { 
             $patientexistcheck= Patients::where('id',$patient_id)->get();
             
           if(!empty($patientexistcheck))
           { 
     
         $mrn=sanitizeVariable($request->MSP_MRN);
        // $patient_id=$request->REN_MRN; 
         $devicename=sanitizeVariable($request->Devices['name']); 
         $Hub=sanitizeVariable($request->Hub);
         $shipped=sanitizeVariable($request->shipping_details['shipped']); 
         $carrier_name=sanitizeVariable($request->shipping_details['carrier-name']); 
         $trackingno=sanitizeVariable($request->shipping_details['tracking_no']); 
         $orderid=sanitizeVariable($request->order_id); 

        if($shipped=='Y')
        {
            $shipped=1;
        }
        else
        {
            $shipped=0;
        }
        $orderdate=date('Y-m-d h:i:s');


        $insertdata=array(
            'patient_id'=>$patient_id, 
            'order_date'=>$orderdate,
            'partner_mrn'=>$mrn,
            'device'=>$devicename,
            'shipped'=>$shipped,                     
            'hub'=>$Hub,
            'carrier_name'=>$carrier_name,
            'tracking_no'=>$trackingno,
            'order_id'=>$orderid
        );



       $exists=PatientOrders::where('order_id',$orderid)->exists();
      
       if($exists==true)
       {
         PatientOrders::where('order_id')->update($insertdata);

          $response=array("Code"=>"201","Description"=>"Order updated successfully!");
          $res=json_encode($response);
           return $res;
       }
       else
       {
        
        PatientOrders::create($insertdata);

          $response=array("Code"=>"201","Description"=>"Order created successfully!");
          $res=json_encode($response);
           return $res;
       }
     }
     else
     {
       $response=array("Code"=>"400","Description"=>"Invalid Patient Id!");
      $res=json_encode($response);
      return $res;
     }
   }
   else
   {
       $response=array("Code"=>"400","Description"=>"Invalid userID!");
      $res=json_encode($response);
      return $res;
   }
}
else
{
    $response=array("Code"=>"401","Description"=>"User Authetication Failed!");
   $res=json_encode($response);
 return $res;

 
}
}

    public function getOrderList(Request $request)
    {
            $configTZ = config('app.timezone');
            $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');

        $patientsdata = DB::select( DB::raw("select pt.*,p.fname,p.mname,p.lname,p.dob,p.profile_img,to_char(pt.order_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') 
                    as order_date from patients.patient_orders pt left join patients.patient p on p.id=pt.patient_id"));
   
            return Datatables::of($patientsdata)
            ->addIndexColumn()           
            ->make(true);
         
    }
    public function OrderList(Request $request)
    {
          return  view('API::patient-order-list');     
         
    }

    public function PatientReadingPost(Request $request)
    {       
        $user_id=sanitizeVariable($request->userId); 
        $token=sanitizeVariable($request->TokenId); 
        $patient_id=sanitizeVariable($request->ren_mrn); 
        $mrn=sanitizeVariable($request->msp_mrn);
       $partertoken=Partner::where('user_key',$token)->get();       
       if(!empty($partertoken))
       {
          $parteruser_id= Partner::where('user_id',$user_id)->get();             
           if(!empty($parteruser_id))
           {            
               $patientexistcheck= Patients::where('id',$patient_id)->get();              
           if(!empty($patientexistcheck))
           { 

         $orderid=sanitizeVariable($request->order_id);         
         
        
         $reading=sanitizeVariable($request->readings); 
         $device_id=sanitizeVariable($request->device_id);
         $reading_id=sanitizeVariable($request->reading_id); 
         $readingdate=date('Y-m-d');

         $redingdata=json_encode($reading);

        $insertdata=array(
            'patient_id'=>$patient_id, 
            'partner_mrn'=>$mrn,
            'device_id'=>$device_id,         
            'reading'=>$redingdata,
            'reading_date'=>$readingdate,
            'reading_id'=>$reading_id
           );
         $exists=PatientReading::where('reading_id',$reading_id)->exists();
      
         if($exists==true)
         {
            PatientReading::where('reading_id',$orderid)->update($insertdata);

              $response=array("Code"=>"201","Description"=>"Reading updated successfully!");
          $res=json_encode($response);
           return $res;
         }
         else
         {        
     
         PatientReading::create($insertdata);

          $response=array("Code"=>"201","Description"=>"Reading created successfully!");
          $res=json_encode($response);
           return $res;
      }
        }
        else
        {
           $response=array("Code"=>"400","Description"=>"Invalid patient MRN!");
             $res=json_encode($response);
             return $res;
        }
           }
             else
             {
                 $response=array("Code"=>"400","Description"=>"Invalid UserId!");
             $res=json_encode($response);
             return $res;
             }
          }
          else
          {
              $response=array("Code"=>"401","Description"=>"User Authetication Failed!");
             $res=json_encode($response);
             return $res;
          }
        }


   public function orders(Request $request){
        
        $content=$request->all();  
        $newcontent=json_encode($content);      
        $data=array(
            'content'=>$newcontent            
        );
       
         $result= WebhookOrders::create($data);
          if($result)
          {
            return response()->json("Data inserted successfully!");
          }
          else
          {
            return "failed";
          }
    }

   public function observations(Request $request)
   {
    // try{
    
     $content=$request->all(); 
     $newcontent=json_encode($content);
     $decodecontent=json_decode($newcontent);
     $currenturl = url()->full();	
     if($currenturl == 'https://rcare.d-insights.global/API/observations'){
        $response = Http::post('https://rcareconnect.com/API/observations',  $content);
        if($response->getStatusCode() == 200) {
            $data=array(
              'content'=>$newcontent, 
              'rconnect_transfer_flag' => 1           
            );
        }else{
          $data=array(
            'content'=>$newcontent,
            'rconnect_transfer_flag' => 0           
          );
        }
     }else{
      $data=array(
        'content'=>$newcontent,
        'rconnect_transfer_flag' => 0           
      );
     }
     
     $result= WebhookObservation::create($data);
     $lastId = $result->id;
     $ecgcredetials=ApiECGCredeintials(); 

     if (array_key_exists("timestamp",$decodecontent)  )
     {
        $timestamp = $decodecontent->timestamp;
        if($timestamp==null || $timestamp==""){
          // throw new Exception('Timestamp null or blank.');
          $msg = 'Timestamp null or blank.';
          $p = explode(" ",$msg);
          $parameter = $p[0];
          $a = array('api'=>$ecgcredetials[0]->url.'API/observations','parameter'=>$parameter,
          'exception_type'=>'alert','incident'=>'missing','webhook_id'=>$lastId,
          'mrn'=>null,'patient_id'=>null,'observation_id'=>null, 'device_code'=>null);
          ApiException::create($a);
        }
     }
     else{
       $msg = 'Timestamp key is missing.';
      // throw new Exception('Timestamp key is missing.');
       $p = explode(" ",$msg);
        $parameter = $p[0];
        $a = array('api'=>$ecgcredetials[0]->url.'API/observations','parameter'=>$parameter,
        'exception_type'=>'alert','incident'=>'missing','webhook_id'=>$lastId,
        'mrn'=>null,'patient_id'=>null,'observation_id'=>null, 'device_code'=>null);
        ApiException::create($a);

     }

     if (array_key_exists("xmit_id",$decodecontent)  )
     {
        $xmit_id = $decodecontent->xmit_id;
        if($xmit_id==null || $xmit_id==""){
          // throw new Exception('Xmitid null or blank.');
          $msg = 'Xmitid null or blank.';
          $p = explode(" ",$msg);
        $parameter = $p[0];
        $a = array('api'=>$ecgcredetials[0]->url.'API/observations','parameter'=>$parameter,
        'exception_type'=>'alert','incident'=>'missing','webhook_id'=>$lastId,
        'mrn'=>null,'patient_id'=>null,'observation_id'=>null, 'device_code'=>null);
        ApiException::create($a);
        }
     }
     else{
      // throw new Exception('Xmitid key is missing.');
      $msg = 'Xmitid key is missing.';
      $p = explode(" ",$msg);
        $parameter = $p[0];
        $a = array('api'=>$ecgcredetials[0]->url.'API/observations','parameter'=>$parameter,
        'exception_type'=>'alert','incident'=>'missing','webhook_id'=>$lastId,
        'mrn'=>null,'patient_id'=>null,'observation_id'=>null, 'device_code'=>null);
        ApiException::create($a);
     }


     if (array_key_exists("observation_id",$decodecontent)  )
     {
        $observation_id = $decodecontent->observation_id;
        if($observation_id==null || $observation_id==""){
          // throw new Exception('Observationid null or blank.');
          $msg = 'Observationid null or blank.';
        
          $p = explode(" ",$msg);
            $parameter = $p[0];
            $a = array('api'=>$ecgcredetials[0]->url.'API/observations','parameter'=>$parameter,
            'exception_type'=>'alert','incident'=>'missing','webhook_id'=>$lastId,
            'mrn'=>null,'patient_id'=>null,'observation_id'=>null, 'device_code'=>null);
            ApiException::create($a);
        }
     }
     else{
      // throw new Exception('Observationid key is missing.');
      $msg = 'Observationid key is missing.';
    
      $p = explode(" ",$msg);
        $parameter = $p[0];
        $a = array('api'=>$ecgcredetials[0]->url.'API/observations','parameter'=>$parameter,
        'exception_type'=>'alert','incident'=>'missing','webhook_id'=>$lastId,
        'mrn'=>null,'patient_id'=>null,'observation_id'=>null, 'device_code'=>null);
        ApiException::create($a);
     }

     if (array_key_exists("mrn",$decodecontent)  )
     {
        $mrn = $decodecontent->mrn;
        if($mrn==null || $mrn==""){
          // throw new Exception('Mrn null or blank.');
          $msg = 'Mrn null or blank.';
        
          $p = explode(" ",$msg);
            $parameter = $p[0];
            $a = array('api'=>$ecgcredetials[0]->url.'API/observations','parameter'=>$parameter,
            'exception_type'=>'info','incident'=>'missing','webhook_id'=>$lastId,
            'mrn'=>null,'patient_id'=>null,'observation_id'=>null, 'device_code'=>null);
            ApiException::create($a);
        }
     }
     else{
      // throw new Exception('Mrn key is missing.');
      $msg = 'Mrn key is missing.';
      $p = explode(" ",$msg);
      $parameter = $p[0];
      $a = array('api'=>$ecgcredetials[0]->url.'API/observations','parameter'=>$parameter,
      'exception_type'=>'info','incident'=>'missing','webhook_id'=>$lastId,
      'mrn'=>null,'patient_id'=>null,'observation_id'=>null, 'device_code'=>null);
      ApiException::create($a);
     }

     if (array_key_exists("carePlanId",$decodecontent)  )
     {
        $carePlanId = $decodecontent->carePlanId;
        if($carePlanId==null || $carePlanId==""){
          // throw new Exception('CarePlanId null or blank.');
          $msg = 'CarePlanId null or blank.';
          $p = explode(" ",$msg);
          $parameter = $p[0];
          $a = array('api'=>$ecgcredetials[0]->url.'API/observations','parameter'=>$parameter,
          'exception_type'=>'info','incident'=>'missing','webhook_id'=>$lastId,
          'mrn'=>null,'patient_id'=>null,'observation_id'=>null, 'device_code'=>null);
          ApiException::create($a);
        }
     }
     else{
      // throw new Exception('CarePlanId key is missing.');
      $msg = 'CarePlanId key is missing.';
      $p = explode(" ",$msg);
      $parameter = $p[0];
      $a = array('api'=>$ecgcredetials[0]->url.'API/observations','parameter'=>$parameter,
      'exception_type'=>'info','incident'=>'missing','webhook_id'=>$lastId,
      'mrn'=>null,'patient_id'=>null,'observation_id'=>null, 'device_code'=>null);
      ApiException::create($a);
     }

     if (array_key_exists("sourceId",$decodecontent)  )
     {
        $sourceId = $decodecontent->sourceId;
        if($sourceId==null || $sourceId==""){
          // throw new Exception('SourceId null or blank.');
          $msg = 'SourceId null or blank.';
          $p = explode(" ",$msg);
          $parameter = $p[0];
          $a = array('api'=>$ecgcredetials[0]->url.'API/observations','parameter'=>$parameter,
          'exception_type'=>'info','incident'=>'missing','webhook_id'=>$lastId,
          'mrn'=>null,'patient_id'=>null,'observation_id'=>null, 'device_code'=>null);
          ApiException::create($a);
        }
     }
     else{
      // throw new Exception('SourceId key is missing.');
      $msg = 'SourceId key is missing.';
      $p = explode(" ",$msg);
      $parameter = $p[0];
      $a = array('api'=>$ecgcredetials[0]->url.'API/observations','parameter'=>$parameter,
      'exception_type'=>'info','incident'=>'missing','webhook_id'=>$lastId,
      'mrn'=>null,'patient_id'=>null,'observation_id'=>null, 'device_code'=>null);
      ApiException::create($a);
     }


     
    //  $timestamp = $decodecontent->timestamp;
    //  $xmit_id = $decodecontent->xmit_id;
    //  $mrn = $decodecontent->mrn;
    //  $observation_id = $decodecontent->observation_id;
    //  $carePlanId = $decodecontent->carePlanId;
    //  $sourceID = $decodecontent->sourceId;

   
      if($result)
      {
        return response()->json("Data inserted successfully!");
      }
      else
      {
        return "failed";
      }

    // } 

  //   catch(Exception $e){
  //     $msg = $e->getMessage();
  //     if($msg=="SourceId key is missing" || $msg=="SourceId null or blank" || $msg=="CarePlanId null or blank" ||  $msg=="CarePlanId key is missing" || $msg=="Mrn null or blank" || $msg=="'Mrn key is missing"){
  //       $p = explode(" ",$msg);
  //       $parameter = $p[0];
  //       $a = array('api'=>'https://dev.ecg-api.com/API/observations','parameter'=>$parameter,'exception_type'=>'info','incident'=>'missing');
  //       ApiException::create($a);
  //     }
  //     else if($msg=="Timestamp null or blank" || $msg=="Timestamp key is missing" || $msg=="Xmitid null or blank" || $msg=="Xmitid key is missing" || $msg=="Observationid null or blank" || $msg=="Observationid key is missing" )
  //       $p = explode(" ",$msg);
  //       $parameter = $p[0];
  //       $a = array('api'=>'https://dev.ecg-api.com/API/observations','parameter'=>$parameter,'exception_type'=>'alert','incident'=>'missing');
  //       ApiException::create($a);
  //   }     
  //  }
  }
  
   public function alerts(Request $request){
    
       $content=$request->all(); 
        $newcontent=json_encode($content); 
        $data=array(
            'content'=>$newcontent,
            'fetch_status'=>'0'           
        );
        $result= WebhookAlert::create($data);
          if($result)
          {
            return response()->json("Data inserted successfully!");
          }
          else
          {
            return "failed";
          }
   }
}  
