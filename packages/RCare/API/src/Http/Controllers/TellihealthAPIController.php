<?php
namespace RCare\API\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RCare\Patients\Models\PatientDevices;
use RCare\API\Models\Partner;

use RCare\API\Models\ApiTellihealth; 
use RCare\Rpm\Models\Observation_BP;
use RCare\Rpm\Models\Observation_Oxymeter;  
use RCare\Rpm\Models\Observation_Heartrate;  
use RCare\Rpm\Models\Observation_Weight;
use RCare\Rpm\Models\Observation_Glucose;
use RCare\Rpm\Models\Observation_Spirometer;
use RCare\Rpm\Models\Observation_Temp;


use RCare\Patients\Models\PatientServices;
use RCare\Org\OrgPackages\Practices\src\Models\Practices;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\PatientThreshold;
use RCare\Org\OrgPackages\Threshold\src\Models\GroupThreshold;
use RCare\Org\OrgPackages\Practices\src\Models\PracticeThreshold;
use RCare\Org\OrgPackages\Practices\src\Models\OrgThreshold;

use RCare\API\Models\ApiException;
use RCare\Rpm\Models\Devices;
use Carbon\Carbon;
use DB;
use Config;
use Exception;
use DataTables;
use Session;
use Illuminate\Support\Str;

class TellihealthAPIController extends Controller {
    public function test_tellihealth_demo_data(){
        return view('API::test');
        // $content = json_encode('hello Api');
      }
      public function tellihealth_webhook_observation(Request $request){  
        $content=$request->all();  
        $newcontent=json_encode($content);      
        $data=array(
            'content'=>$newcontent,
            'partner'=>'tellihealth',
            'status' =>'0',            
        );
         $result= ApiTellihealth::create($data);
          if($result)
          {
            return response()->json("Data inserted successfully!");
          }
          else
          {
            return "failed";
          }
      }
    
  
    public function process_webhook_observation(){
      $getTellihealthData = ApiTellihealth::where('status',0)->orderBy('id', 'DESC')->get();
      $d = count($getTellihealthData);
	  //dd($d);
      $i=1;
      foreach($getTellihealthData as $value){
        $datajson = json_decode($value->content, true);
        // print_r($datajson);
        $id = $value['id'];
        $device_id = $datajson['deviceId']; 
        $decodeContent = $datajson['data'];
        $deviceName = $datajson['readingType'];
        $time = $datajson['time'];
        $recorddate = date('Y-m-d H:i:s', ($datajson['time'])/1000); 
        $checkDeviceExist= PatientDevices::where('device_code',$device_id)->where ('status', 1)->exists();
        $get_checkDeviceExist= PatientDevices::where('device_code',$device_id)->where ('status', 1)->select('patient_id')->get();
        if(isset($get_checkDeviceExist[0])){
           //echo "Me yha pr hu?";
            $patient_id = $get_checkDeviceExist[0]->patient_id;
            $partner_id = $get_checkDeviceExist[0]->partner_id;
            $observationid = $patient_id.'-'.$time; 
            if($patient_id!=null){
              if(PatientDevices::where('device_code',$device_id)->exists()){
                print_r($patient_id); echo"-".$deviceName.'<br>';
                if($deviceName == 'BloodPressure'){ 
                  $vitaldevice = Devices::where('device_name','Blood Pressure Cuff')->where('status',1)->first();
                  $vitaldeviceid = $vitaldevice->id;
                  $systolic = $decodeContent['systolic'];
                  $diastolic = $decodeContent['diastolic'];
                  $pulse = $decodeContent['pulse'];
                    // $arrhythmia = $decodeContent['arrhythmia'];
                  $insert_array =array( 
                    'patient_id' =>$patient_id, 
                    'device_id' =>$device_id,
                    'effdatetime'=>$recorddate,
                    'systolic_qty'=>$systolic,
                    'diastolic_qty'=>$diastolic,
                    'resting_heartrate'=>$pulse, 
                    'systolic_unit' =>'mmHg',
                    'diastolic_unit' =>'mmHg',
                    'resting_heartrate_unit'=>'beats/minute',
                    'systolic_code' =>'mm[Hg]',
                    'diastolic_code' =>'mm[Hg]',
                    'resting_heartrate_code'=>'/min',
                    'vital_device_id' =>$vitaldeviceid,
                    'observation_id'=>$observationid,
                    'billing'=>0,
                    'alert_status'=>0,
                    'addressed'=>0,
                    'reviewed_flag'=>0,
                    'reviewed_date'=>null, 
                    'created_by'=>'symtech',
                    'updated_by'=>'symtech'
                  //  'arrhythmia'=>$arrhythmia, //arrhythmia – Will display 1 if an irregular heartbeat is detected, or 0 if not detected
                  );
                   print_r($insert_array);          
                  // $check = \DB::select(\DB::raw("select * from  api.tellihealth t
                  // inner join rpm.observations_bp ob
                  // on t.content->>'deviceId'=ob.device_id 
                  // and(to_timestamp((content->>'time')::bigint/1000))
                  // at time zone '".$configTZ."' at time zone '".$userTZ."'= ob.effdatetime 
                  // and t.status =0
                  // order by t.id desc"));
 
                  $check = \DB::select(\DB::raw("select * from  rpm.observations_bp ob
                  where device_id = '".$device_id."' and (effdatetime at time zone 'UTC' at time zone 'CST') = '".$recorddate."'"));
                      if(empty($check)){
                         $o = Observation_BP::create($insert_array);
                         $update_threshold = Observation_BP::where('device_id',$device_id)
                         ->where('patient_id',$patient_id)//->where('mrn',$mrn_no)->where('observation_id',$observationid)
                         ->where('effdatetime',$recorddate)
                         ->get();     
                        if($update_threshold!=''){
                          $this->saveThresholdReadings($deviceName,$recorddate,$patient_id,$partner_id,$device_id,$observationid);
                          // ApiTellihealth::where('id',$id)->update(['status'=>1]);
						  echo "update status section ";
                          \DB::select(\DB::raw("update api.tellihealth t set status=1
                          where  id= '".$id."' and content->>'deviceId'='".$device_id."' "));
                        }
                      }
                }else if($deviceName == 'SpO2'){
                  $vitaldevice = Devices::where('device_name','Pulse Oximeter')->where('status',1)->first();
                  $vitaldeviceid = $vitaldevice->id;
                  
                  $oxy_qty = $decodeContent['spo2'];
                  $pulse = $decodeContent['pulse'];
                  
                  $insert_array =array( 
                    'patient_id' =>$patient_id,
                    'device_id' =>$device_id,
                    'effdatetime'=>$recorddate,
                    'oxy_qty'=>$oxy_qty,
                    'resting_heartrate'=>$pulse, 
                    'oxy_unit' =>'%',
                    'resting_heartrate_unit'=>'beats/minute',
                    'oxy_code' =>'%',
                    'resting_heartrate_code'=>'/min',
                    'vital_device_id' => $vitaldeviceid,
                    'observation_id'=>$observationid,
                    'billing'=>0,
                    'alert_status'=>0,
                    'addressed'=>0,
                    'reviewed_flag'=>0,
                    'reviewed_date'=>null,
                    'created_by'=>'symtech',
                    'updated_by'=>'symtech'
                  );
                  // dd($insert_array);
                  $check = \DB::select(\DB::raw("select * from  api.tellihealth t
                  inner join rpm.observations_oxymeter ox 
                  on t.content->>'deviceId'=ox.device_id 
                  and to_timestamp((content->>'time')::bigint/1000) = ox.effdatetime 
                  and t.status =0
                  order by t.id desc"));
                  if(isset($check)){
                      $o = Observation_Oxymeter::create($insert_array); 
                      $update_threshold = Observation_Oxymeter::where('device_id',$device_id)
                      ->where('patient_id',$patient_id)
                      ->where('observation_id',$observationid)
                      ->where('effdatetime',$recorddate)
                      ->get();   
                      if($update_threshold!=''){
                        $this->saveThresholdReadings($deviceName,$recorddate,$patient_id,$partner_id,$device_id,$observationid);
                        // ApiTellihealth::where('id',$id)->update(['status'=>1]);
                        \DB::select(\DB::raw("update api.tellihealth t set status=1 
                        where  id = '".$id."' and content->>'deviceId'='".$device_id."' "));
                      } 
                    }
                }else if($deviceName == 'BloodGlucose'){
                  $vitaldevice = Devices::where('device_name','Glucometer N')->where('status',1)->first();
                  $vitaldeviceid = $vitaldevice->id;           
                  $value = $decodeContent['glucose'];
                  // dd($value);
                  // reading_period column need to be add
                  $insert_array =array( 
                    'patient_id' =>$patient_id,
                    'device_id' =>$device_id,
                    'effdatetime'=>$recorddate,
                    'value'=>$value,
                    'unit' =>'mg/dl',
                    'vital_device_id' => $vitaldeviceid,
                    'observation_id'=>$observationid,
                    'billing'=>0,
                    'alert_status'=>0,
                    'addressed'=>0,
                    'reviewed_flag'=>0,
                    'reviewed_date'=>null,
                    'created_by'=>'symtech',
                    'updated_by'=>'symtech'
                  );
                 
                  $check = \DB::select(\DB::raw("select * from  api.tellihealth t
                  inner join rpm.observations_glucose og 
                  on t.content->>'deviceId'=og.device_id 
                  and to_timestamp((content->>'time')::bigint/1000) = og.effdatetime 
                  and t.status =0
                  order by t.id desc"));
                  if(isset($check)){
                    $o = Observation_Glucose::create($insert_array);
                  $update_threshold = Observation_Glucose::where('device_id',$device_id)
                    ->where('patient_id',$patient_id)
                    ->where('observation_id',$observationid)
                    ->where('effdatetime',$recorddate)->get(); 
                    if($update_threshold!=''){
                      $this->saveThresholdReadings($deviceName,$recorddate,$patient_id,$partner_id,$device_id,$observationid);
                      // ApiTellihealth::where('id',$id)->update(['status'=>1]);
                      \DB::select(\DB::raw("update api.tellihealth t set status=1
                      where  id= '".$id."' and content->>'deviceId'='".$device_id."' "));
                    }
                  }
                }else if($deviceName == 'Weight'){
                  $vitaldevice = Devices::where('device_name','Weighing Scale')->where('status',1)->first();
                  // dd($vitaldevice);
                  $vitaldeviceid = $vitaldevice->id;
                  $weight = $decodeContent['weight'];
                  $unit = $decodeContent['unit'];
                  $insert_array =array( 
                    'patient_id' =>$patient_id,
                    'device_id' =>$device_id,
                    'effdatetime'=>$recorddate,
                    'weight'=>$weight,
                    'unit' =>$unit,
                    'vital_device_id' => $vitaldeviceid,
                    'observation_id'=>$observationid,
                    'billing'=>0,
                    'alert_status'=>0,
                    'addressed'=>0,
                    'reviewed_flag'=>0,
                    'reviewed_date'=>null,
                    'created_by'=>'symtech',
                    'updated_by'=>'symtech'
                  );
                  
                  $check = \DB::select(\DB::raw("select * from  api.tellihealth t
                  inner join rpm.observations_weight ow 
                  on t.content->>'deviceId'=ow.device_id 
                  and to_timestamp((content->>'time')::bigint/1000) = ow.effdatetime 
                  and t.status =0
                  order by t.id desc"));
                  if(isset($check)){
                      $o = Observation_Weight::create($insert_array);
                      $update_threshold = Observation_Weight::where('device_id',$device_id)
                      ->where('patient_id',$patient_id)//->where('mrn',$mrn_no)->where('observation_id',$observationid)
                      ->where('effdatetime',$recorddate)
                      ->get();     
                    if($update_threshold!=''){
                      $this->saveThresholdReadings($deviceName,$recorddate,$patient_id,$partner_id,$device_id,$observationid);
                      // ApiTellihealth::where('id',$id)->update(['status'=>1]);
                      \DB::select(\DB::raw("update api.tellihealth t set status=1
                      where  id= '".$id."' and content->>'deviceId'='".$device_id."' "));
                    }
                  } 
                }
                //else if($deviceName == 'Temperature'){
                //   $temperature = $decodeContent['temperature'];
                //   $unit = $decodeContent['unit'];
                //   $insert_array =array( 
                //     'patient_id' =>$patient_id,
                //     'device_id' =>$device_id,
                //     'effdatetime'=>$recorddate,
                //     'bodytemp'=>$temperature, 
                //     'unit' =>$unit,
                //   );
                //   $check = Observation_temp::where('device_id',$device_id)
                //   ->where('patient_id',$patient_id)
                //   // ->where('mrn',$mrn_no)->where('observation_id',$observationid)
                //   ->where('effdatetime',$recorddate)->get(); 
                  // if(count($check)==0){
                    //    $o = Observation_temp::create($insert_array); 
                    //    ApiTellihealth::where('id',$id)->update(['status'=>1]);
                  // }
              }
            }else{
                // $this->Api_exception();
            } 
          }
        } 
    }        
    public function Api_exception(){ 
        $getTellihealthData = ApiTellihealth::where('status',0)->orderBy('id', 'DESC')->get();
        $d = count($getTellihealthData);
        $i=1;
        foreach($getTellihealthData as $value){
          $datajson = json_decode($value->content, true);
          $id = $value['id'];
          $device_id = $datajson['deviceId']; 
          $decodeContent = $datajson['data'];
          $deviceName = $datajson['readingType'];
          $time = $datajson['time'];
          $recorddate = date('Y-m-d H:i:s', ($datajson['time'])/1000); 
          $mrn ='';
          $getdata = \DB::select(DB::raw("select id,content->>'deviceId' as deviceId from api.tellihealth where status = 0"));
          $deviceid = $getdata[0]->deviceid;
          $checkDeviceExist= PatientDevices::where('device_code',$deviceid)->exists();
          $get_checkDeviceExist= PatientDevices::where('device_code',$deviceid)->get();
          if($checkDeviceExist==true){
            $patientid = $get_checkDeviceExist[0]->patient_id;
            $partner_id = $get_checkDeviceExist[0]->partner_id;
            $observationid = $patientid.'-'.$time;
            $checkpatientservicesinccm =  PatientServices::where('patient_id',$patientid)->where('module_id',3)->get(); 
            $patientservices = PatientServices::where('patient_id',$patientid)->where('module_id',2)->get();
            if((!$checkpatientservicesinccm->isEmpty()) && ($patientservices->isEmpty()) ){
              //echo"patient enrolled nai hai RPM me";
              $updatedata = ApiTellihealth::where('id',$id)->update(['fetch_status'=>2]);     
              $msg = 'Patient not enrolled in CCM & RPM';  
              $p = explode(" ",$msg);
              $parameter = $p[0]."-".$p[1]."-".$p[2]."-".$p[3]."-".$p[4]; 
              $a = array('api'=>(Config::get('global.tellihealthurl')).'observations/{id}',
              'parameter'=>$parameter,'exception_type'=>'alert','incident'=>'nonenrolledRPM','webhook_id'=>$id,        
              'mrn'=>$mrn,'patient_id'=>$patientid,'partner_id'=> $partner_id,'observation_id'=>$observationid, 'device_code'=>$deviceid);
              $exception = ApiException::create($a);
            }else if($patientservices->isEmpty()){
              $updatedata = ApiTellihealth::where('id',$id)->update(['fetch_status'=>2]);     
              $msg = 'Patient not enrolled in RPM';  
              $p = explode(" ",$msg);
              $parameter = $p[0]."-".$p[1]."-".$p[2]."-".$p[3]."-".$p[4]; 
              $a = array('api'=>(Config::get('global.tellihealthurl')).'observations/{id}',
              'parameter'=>$parameter,'exception_type'=>'alert','incident'=>'nonenrolledRPM','webhook_id'=>$id,        
              'mrn'=>$mrn,'patient_id'=>$patientid,'partner_id'=> $partner_id,'observation_id'=>$observationid, 'device_code'=>$deviceid);
              $exception = ApiException::create($a);
            }
            else{ 
              // echo"patinet services me suspended hai";
              $patientstatus = $patientservices[0]->status;    
              $suspended_from = $patientservices[0]->suspended_from;   
              // print_r($time);
              $suspended_to = $patientservices[0]->suspended_to;
              if($recorddate> $suspended_from  &&  $recorddate < $suspended_to &&  $patientstatus== 2 ){
                // echo"patinet services me suspended hai -1st if";
                $updatedata= ApiTellihealth::where('id',$id)->update(['fetch_status'=>2]); 
                $msg = 'Patient is Suspended from'.$suspended_from.'to'.$suspended_to;  
                $p = explode(" ",$msg);
                $parameter = $p[0]."-".$p[1]."-".$p[2];
                $a = array('api'=>(Config::get('global.tellihealthurl')).'observations/{id}',
                'parameter'=>$parameter,'exception_type'=>'alert','incident'=>'deactive','webhook_id'=>$id,        
                'mrn'=>$mrn,'patient_id'=>$patientid,'partner_id'=> $partner_id,'observation_id'=>$observationid, 'device_code'=>$devdiceid);
                $exception = ApiException::create($a);
              }
              else if($recorddate> $suspended_from && $patientstatus== 3){
                // echo"patinet services me deceased hai -2nd if";
              $updatedata= ApiTellihealth::where('id',$id)->update(['fetch_status'=>3]); 
              $msg = 'Patient is Deceased';  
              $p = explode(" ",$msg);
              $parameter = $p[0]."-".$p[1]."-".$p[2];
              $a = array('api'=>(Config::get('global.tellihealthurl')).'observations/{id}',
              'parameter'=>$parameter,'exception_type'=>'alert','incident'=>'deactive','webhook_id'=>$id,
              'mrn'=>$mrn,'patient_id'=>$patientid,'partner_id'=> $partner_id,'observation_id'=>$observationid, 'device_code'=>$devdiceid);
              $exception = ApiException::create($a);
              }else{

              }  
            }
          }else {
              $updatedata= ApiTellihealth::where('id',$id)->update(['status'=>2]); 
              $msg = 'Empty patientid record';  
              $patient_id = null;
              $p = explode(" ",$msg);
              $parameter = $p[0]."-".$p[1]."-".$p[2];
              $a = array('api'=>(Config::get('global.tellihealthurl')),
              'parameter'=>$parameter,'exception_type'=>'alert','incident'=>'missing','webhook_id'=>$id,
              'mrn'=>$mrn,
              'patient_id'=>null,'observation_id'=>null, 'partner_id'=> $partner_id,'device_code'=>$device_id);
              $exception = ApiException::create($a);  //add partner_id in apiEXception
          }
        }
      }
         
    // public function process_webhook_observation_withException(){
    //   // dd('heeelll000000000000');
    //     // $device_id ='463dff97-a829-49a0-b914-7d346ca5e273';
    //     // $patient_id ='1037448314';
    //     $getTellihealthData = ApiTellihealth::where('status',0)->orderBy('id', 'DESC')->get();
    //     $d = count($getTellihealthData);
    //     $i=1;
    //     foreach($getTellihealthData as $value){
    //       $datajson = json_decode($value->content, true);
    //       // print_r($datajson);
    //       $id = $value['id'];
    //       $device_id = $datajson['deviceId']; 
    //       $decodeContent = $datajson['data'];
    //       $deviceName = $datajson['readingType'];

    //       // print_r($deviceName); echo"<br>";
    //       $time = $datajson['time'];
    //       $recorddate = date('Y-m-d H:i:s', ($datajson['time'])/1000); 
    //       // print_r($recorddate);
    //       $mrn ='';
    //       // $observationid = '';
    //         // print_r($datajson['data']['weight']);
    //       $checkDeviceExist= PatientDevices::where('device_code',$device_id)->exists();
    //       $get_checkDeviceExist= PatientDevices::where('device_code',$device_id)->select('patient_id')->get();
    //       //->where('patient_id',$patient_id)
    //       // print_r($);
		//         // $patient_id = null;
          
    //       // print_r($get_checkDeviceExist); echo"<br>";
    //       if(isset($get_checkDeviceExist[0])){
    //         // echo "Me yha pr hu?";
    //           $patient_id = $get_checkDeviceExist[0]->patient_id;
    //           $partner_id = $get_checkDeviceExist[0]->partner_id;
    //           $observationid = $patient_id.'-'.$time;
    //         if($patient_id==null){
    //           // echo "patient id nai hai";
    //           $updatedata= ApiTellihealth::where('id',$id)->update(['status'=>2]); 
    //           $msg = 'Empty patientid record';  
    //           $patient_id = null;
    //           $p = explode(" ",$msg);
    //           $parameter = $p[0]."-".$p[1]."-".$p[2];
    //           $a = array('api'=>(Config::get('global.tellihealthurl')),
    //           'parameter'=>$parameter,'exception_type'=>'alert','incident'=>'missing','webhook_id'=>$id,
    //           'mrn'=>$mrn,
    //           'patient_id'=>null,'observation_id'=>null, 'partner_id'=> $partner_id,'device_code'=>$device_id);
    //           $exception = ApiException::create($a);  //add partner_id in apiEXception
    //         }else{
    //           // echo "patient id hai";
    //           $patientid = $get_checkDeviceExist[0]->patient_id;
    //           $checkpatientservicesinccm =  PatientServices::where('patient_id',$patientid)->where('module_id',3)->get(); 
    //           $patientservices = PatientServices::where('patient_id',$patientid)->where('module_id',2)->get(); 
    //           // dd($patientservices); 
    //           // print_r($patientid);echo'<br>';
    //           if((!$checkpatientservicesinccm->isEmpty()) && ($patientservices->isEmpty()) ){
    //             //  echo"patient enrolled nai hai RPM me";
    //                 $updatedata= ApiTellihealth::where('id',$id)->update(['fetch_status'=>2]);     
    //                 $msg = 'Patient not enrolled in RPM';  
    //                 $p = explode(" ",$msg);
    //                 $parameter = $p[0]."-".$p[1]."-".$p[2]."-".$p[3]."-".$p[4]; 
    //                 $a = array('api'=>(Config::get('global.tellihealthurl')).'observations/{id}',
    //                 'parameter'=>$parameter,'exception_type'=>'alert','incident'=>'nonenrolledRPM','webhook_id'=>$id,        
    //                 'mrn'=>$mrn,'patient_id'=>$patientid,'partner_id'=> $partner_id,'observation_id'=>$observationid, 'device_code'=>$deviceid);
    //                 $exception = ApiException::create($a);
    //           }
    //           if($patientservices->isEmpty()){
    //             print_r($patientid);echo'<br>';echo "services is Empty";echo'<br>';
    //           }
    //           else{ 
    //           // echo"patinet services me suspended hai";
    //           $patientstatus = $patientservices[0]->status;    
    //           $suspended_from = $patientservices[0]->suspended_from;   
    //           // print_r($time);
    //           $suspended_to = $patientservices[0]->suspended_to;
    //           if($recorddate> $suspended_from  &&  $recorddate < $suspended_to &&  $patientstatus== 2 ){
    //             // echo"patinet services me suspended hai -1st if";
    //             $updatedata= ApiTellihealth::where('id',$id)->update(['fetch_status'=>2]); 
    //             $msg = 'Patient is Suspended from'.$suspended_from.'to'.$suspended_to;  
    //             $p = explode(" ",$msg);
    //             $parameter = $p[0]."-".$p[1]."-".$p[2];
    //             $a = array('api'=>(Config::get('global.tellihealthurl')).'observations/{id}',
    //             'parameter'=>$parameter,'exception_type'=>'alert','incident'=>'deactive','webhook_id'=>$id,        
    //             'mrn'=>$mrn,'patient_id'=>$patientid,'partner_id'=> $partner_id,'observation_id'=>$observationid, 'device_code'=>$devdiceid);
    //             $exception = ApiException::create($a);
    //           }
    //           else if($recorddate> $suspended_from && $patientstatus== 3){
    //             // echo"patinet services me deceased hai -2nd if";
    //            $updatedata= ApiTellihealth::where('id',$id)->update(['fetch_status'=>3]); 
    //            $msg = 'Patient is Deceased';  
    //            $p = explode(" ",$msg);
    //            $parameter = $p[0]."-".$p[1]."-".$p[2];
    //            $a = array('api'=>(Config::get('global.tellihealthurl')).'observations/{id}',
    //            'parameter'=>$parameter,'exception_type'=>'alert','incident'=>'deactive','webhook_id'=>$id,
    //            'mrn'=>$mrn,'patient_id'=>$patientid,'partner_id'=> $partner_id,'observation_id'=>$observationid, 'device_code'=>$devdiceid);
    //            $exception = ApiException::create($a);
    //           }
    //           else{
    //             if(PatientDevices::where('device_code',$device_id)->exists()){
    //               // print_r($patient_id); echo"-".$deviceName.'<br>';
    //               if($deviceName == 'BloodPressure'){ 
    //                 $vitaldevice = Devices::where('device_name','Blood Pressure Cuff')->where('status',1)->first();
    //                 $vitaldeviceid = $vitaldevice->id;
    //                 $systolic = $decodeContent['systolic'];
    //                 $diastolic = $decodeContent['diastolic'];
    //                 $pulse = $decodeContent['pulse'];
    //                   // $arrhythmia = $decodeContent['arrhythmia'];
    //                 $insert_array =array( 
    //                   'patient_id' =>$patient_id, 
    //                   'device_id' =>$device_id,
    //                   'effdatetime'=>$recorddate,
    //                   'systolic_qty'=>$systolic,
    //                   'diastolic_qty'=>$diastolic,
    //                   'resting_heartrate'=>$pulse, 
    //                   'systolic_unit' =>'mmHg',
    //                   'diastolic_unit' =>'mmHg',
    //                   'resting_heartrate_unit'=>'beats/minute',
    //                   'systolic_code' =>'mm[Hg]',
    //                   'diastolic_code' =>'mm[Hg]',
    //                   'resting_heartrate_code'=>'/min',
    //                   'vital_device_id' =>$vitaldeviceid,
    //                   'observation_id'=>$observationid,
    //                   'billing'=>0,
    //                   'alert_status'=>0,
    //                   'addressed'=>0,
    //                   'reviewed_flag'=>0,
    //                   'reviewed_date'=>null,
    //                   'created_by'=>'symtech',
    //                   'updated_by'=>'symtech'
    //                 //  'arrhythmia'=>$arrhythmia, //arrhythmia – Will display 1 if an irregular heartbeat is detected, or 0 if not detected
    //                 );
    //                 // dd($insert_array);
    //                   $check = Observation_BP::where('device_id',$device_id)
    //                   ->where('patient_id',$patient_id)//->where('mrn',$mrn_no)->where('observation_id',$observationid)
    //                   ->where('effdatetime',$recorddate)
    //                   ->get();     
    //                     if(count($check)==0){
    //                       $o = Observation_BP::create($insert_array);
    //                       $this->saveThresholdReadings($deviceName,$recorddate,$patientid,$partner_id,$device_id,$observationid);
    //                       ApiTellihealth::where('id',$id)->update(['status'=>1]);
    //                   }
    //               }else if($deviceName == 'SpO2'){
                     
    //                 $vitaldevice = Devices::where('device_name','Pulse Oximeter')->where('status',1)->first();
    //                 $vitaldeviceid = $vitaldevice->id;
                    
    //                 $oxy_qty = $decodeContent['spo2'];
    //                 $pulse = $decodeContent['pulse'];
                    
    //                 $insert_array =array( 
    //                   'patient_id' =>$patient_id,
    //                   'device_id' =>$device_id,
    //                   'effdatetime'=>$recorddate,
    //                   'oxy_qty'=>$oxy_qty,
    //                   'resting_heartrate'=>$pulse, 
    //                   'oxy_unit' =>'%',
    //                   'resting_heartrate_unit'=>'beats/minute',
    //                   'oxy_code' =>'%',
    //                   'resting_heartrate_code'=>'/min',
    //                   'vital_device_id' => $vitaldeviceid,
    //                   'observation_id'=>$observationid,
    //                   'billing'=>0,
    //                   'alert_status'=>0,
    //                   'addressed'=>0,
    //                   'reviewed_flag'=>0,
    //                   'reviewed_date'=>null,
    //                   'created_by'=>'symtech',
    //                   'updated_by'=>'symtech'
    //                 );
    //                 // dd($insert_array);
    //                 $check = Observation_Oxymeter::where('device_id',$device_id)
    //                 ->where('patient_id',$patient_id)
    //                   // ->where('mrn',$mrn_no)
    //                   ->where('observation_id',$observationid)
    //                   ->where('effdatetime',$recorddate)
    //                   ->get();   
    //                   // dd(count($check));  
    //                   if(count($check)==0){
    //                     $o = Observation_Oxymeter::create($insert_array); 
    //                     $this->saveThresholdReadings($deviceName,$recorddate,$patientid,$partner_id,$device_id,$observationid);                        ApiTellihealth::where('id',$id)->update(['status'=>1]);
    //                     ApiTellihealth::where('id',$id)->update(['status'=>1]);
    //                   }
    //               }else if($deviceName == 'BloodGlucose'){
    //                 // echo "BloodGlucose ===== ";
    //                 // echo $i++; echo"<br>";
    //                 $vitaldevice = Devices::where('device_name','Glucometer N')->where('status',1)->first();
    //                 $vitaldeviceid = $vitaldevice->id;           
    //                 $value = $decodeContent['glucose'];
    //                 // dd($value);
    //                 // reading_period column need to be add
    //                 $insert_array =array( 
    //                   'patient_id' =>$patient_id,
    //                   'device_id' =>$device_id,
    //                   'effdatetime'=>$recorddate,
    //                   'value'=>$value,
    //                   'unit' =>'mg/dl',
    //                   'vital_device_id' => $vitaldeviceid,
    //                   'observation_id'=>$observationid,
    //                   'billing'=>0,
    //                   'alert_status'=>0,
    //                   'addressed'=>0,
    //                   'reviewed_flag'=>0,
    //                   'reviewed_date'=>null,
    //                   'created_by'=>'symtech',
    //                   'updated_by'=>'symtech'
    //                 );
    //                 $check = Observation_Glucose::where('device_id',$device_id)
    //                 ->where('patient_id',$patient_id)
    //                 // ->where('mrn',$mrn_no)
    //                 ->where('observation_id',$observationid)
    //                 ->where('effdatetime',$recorddate)->get(); 
    //                 // dd(count($check));
    //                 if(count($check)==0){
    //                   $o = Observation_Glucose::create($insert_array);
    //                   $this->saveThresholdReadings($deviceName,$recorddate,$patientid,$partner_id,$device_id,$observationid);                      ApiTellihealth::where('id',$id)->update(['status'=>1]); 
    //                   ApiTellihealth::where('id',$id)->update(['status'=>1]);
    //                   }
    //               }else if($deviceName == 'Weight'){
    //                 $vitaldevice = Devices::where('device_name','Weighing Scale')->where('status',1)->first();
    //                 // dd($vitaldevice);
    //                 $vitaldeviceid = $vitaldevice->id;
    //                 $weight = $decodeContent['weight'];
    //                 $unit = $decodeContent['unit'];
    //                 $insert_array =array( 
    //                   'patient_id' =>$patient_id,
    //                   'device_id' =>$device_id,
    //                   'effdatetime'=>$recorddate,
    //                   'weight'=>$weight,
    //                   'unit' =>$unit,
    //                   'vital_device_id' => $vitaldeviceid,
    //                   'observation_id'=>$observationid,
    //                   'billing'=>0,
    //                   'alert_status'=>0,
    //                   'addressed'=>0,
    //                   'reviewed_flag'=>0,
    //                   'reviewed_date'=>null,
    //                   'created_by'=>'symtech',
    //                   'updated_by'=>'symtech'
    //                 );
    //                 $check = Observation_Weight::where('device_id',$device_id)
    //                 ->where('patient_id',$patient_id)
    //                 // ->where('mrn',$mrn_no)->where('observation_id',$observationid)
    //                 ->where('effdatetime',$recorddate)->get(); 
    //                 if(count($check)==0){
    //                   $o = Observation_Weight::create($insert_array); 
    //                   $this->saveThresholdReadings($deviceName,$recorddate,$patientid,$partner_id,$device_id,$observationid);
    //                   ApiTellihealth::where('id',$id)->update(['status'=>1]);
                       
    //                   } 
    //               }
    //               //else if($deviceName == 'Temperature'){
    //               //   $temperature = $decodeContent['temperature'];
    //               //   $unit = $decodeContent['unit'];
    //               //   $insert_array =array( 
    //               //     'patient_id' =>$patient_id,
    //               //     'device_id' =>$device_id,
    //               //     'effdatetime'=>$recorddate,
    //               //     'bodytemp'=>$temperature, 
    //               //     'unit' =>$unit,
    //               //   );
    //               //   $check = Observation_temp::where('device_id',$device_id)
    //               //   ->where('patient_id',$patient_id)
    //               //   // ->where('mrn',$mrn_no)->where('observation_id',$observationid)
    //               //   ->where('effdatetime',$recorddate)->get(); 
    //                 // if(count($check)==0){
    //                   //    $o = Observation_temp::create($insert_array); 
    //                   //    ApiTellihealth::where('id',$id)->update(['status'=>1]);
                    // }
                // }else{
                    // dd('adasd');
                // }
              // }
            // }
          // } 
    //     }
               
    //   }

    // }
  public function saveThresholdReadings($deviceName,$recorddate,$patient_id,$partner_id,$device_id,$observationid){ // $idarrays,$obs,$mrn_no
    $patientid = $patient_id;
    // $checkexceptions = ApiException::where('patient_id',$patientid)                 
    //   // ->where('mrn',$mrn_no)
    //   ->where('device_code',$device_id)
    //   ->where('observation_id',$observationid)
    //   ->where('exception_type','alert')
    //   ->get();
    //   // dd($checkexceptions);//[0]->webhook_id);
    //   if(count($checkexceptions)>0){
    //     // echo 'edher hai kya????????????';
    //     ApiException::where('patient_id',$patientid)
    //     // ->where('mrn',$mrn_no)
    //     ->where('device_code',$device_id)
    //     ->where('observation_id',$observationid)
    //     ->where('exception_type','alert')
    //     ->where('partner_id',$partner_id)
    //     ->update(['reprocess'=>1]);
    //     ApiTellihealth::where('id',$checkexceptions[0]->webhook_id)
    //     ->update(['status'=>0]); 
    //   }

      // dd($deviceName);
      if($deviceName =='BloodPressure'){
        // alert threshold
        $observation_data = Observation_BP::where('device_id',$device_id)
        ->where('patient_id',$patientid)
        ->where('effdatetime',$recorddate)
        ->where('observation_id',$observationid)
        ->orderBy('created_at', 'desc')->get();

        if(isset($observation_data) || $observation_data!='' || $observation_data!=null){ 
          $systolic_value = $observation_data[0]->systolic_qty;
          $diastolic_value = $observation_data[0]->diastolic_qty;
        }else{
          $systolic_value = 0;
          $diastolic_value = 0;
        }
        
        $checkpatientthreshold = PatientThreshold::where('patient_id',$patientid)
        ->whereNotNull('systolichigh')
        ->whereNotNull('systoliclow')
        ->whereNotNull('diastoliclow')
        ->whereNotNull('diastolichigh')  
        ->orderBy('created_at', 'desc')
        ->get(); 

        if($checkpatientthreshold->isEmpty())
        {
          $checkinpracticethreshold = \DB::select(\DB::raw("select * from patients.patient_providers pp 
          inner join patients.practice_threshold pt on pp.practice_id =pt.practice_id 
          and pp.provider_type_id =1 and pp.is_active =1 and pp.patient_id = '".$patientid."'
          where  pt.systolichigh is not null and  pt.systoliclow is not null
          and pt.diastolichigh  is not null and pt.diastoliclow is not null order by pt.created_at desc
          "));
          if($checkinpracticethreshold='' || empty($checkinpracticethreshold)){         
              $org = \DB::select(\DB::raw("select ot.* from patients.patient_providers pp
                inner join patients.practice_threshold pt on pp.practice_id =pt.practice_id 
                inner join ren_core.practices p on pt.practice_id  =p.id
                inner join ren_core.org_threshold ot on p.practice_group =ot.org_id 
                and pp.provider_type_id =1 and pp.is_active =1 and pp.patient_id ='".$patientid."'
                where ot.systolichigh is not null and  ot.systoliclow is not null
                and ot.diastolichigh  is not null and ot.diastoliclow is not null 
                order by pt.created_by desc"));
                // dd($org);
                if(!empty($org)){
                  $orgthreshold = $org[0]->systoliclow."-".$org[0]->systolichigh."/".$org[0]->diastoliclow."-".$org[0]->diastolichigh;
                  // dd($orgthreshold);
                  $systolic_threshold = $org[0]->systoliclow."-".$org[0]->systolichigh;
                  $diastolic_threshold = $org[0]->diastoliclow."-".$org[0]->diastolichigh;

                  ($orgthreshold == null || $orgthreshold == 'null' || $orgthreshold == '-/-' || $orgthreshold == "-/-" ) ? $orgthreshold = '' : $orgthreshold;

                  ($orgthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'O';
                  ($orgthreshold=='' ) ? $thresholdid = null : $thresholdid = $org[0]->id;
                  
                  Observation_BP::where('device_id',$device_id)
                  ->where('patient_id',$patientid)
                  ->where('effdatetime',$recorddate)
                  ->where('observation_id',$observationid)
                  ->update(['threshold'=>$orgthreshold,'threshold_type'=>$thresholdtype,
                  'threshold_id'=>$thresholdid,
                  'systolic_threshold'=>$systolic_threshold,'diastolic_threshold'=>$diastolic_threshold]);
                  if($org[0]->diastolichigh == null || $org[0]->diastolichigh == 'null' || $org[0]->diastolichigh == '' ||
                  $org[0]->diastolichigh == "" || $org[0]->diastoliclow == 'null' 
                  || $org[0]->diastoliclow == "" || $org[0]->diastoliclow == ''){                    
                  }else{
                    if(($diastolic_value > $org[0]->diastolichigh) || ($diastolic_value < $org[0]->diastoliclow ))
                    {
                      Observation_BP::where('device_id',$device_id)->where('patient_id',$patientid)
                      ->where('effdatetime',$recorddate)
                      ->where('observation_id',$observationid)
                      ->update(['alert_status'=>1]);
                    }
                  }
                  if($org[0]->systolichigh == null || $org[0]->systolichigh == 'null' || $org[0]->systolichigh == '' ||
                  $org[0]->systolichigh == "" || $org[0]->systoliclow == 'null' 
                  || $org[0]->systoliclow == "" || $org[0]->systoliclow == ''){                    
                  }else{
                    if(($systolic_value > $org[0]->systolichigh) && ($systolic_value > $org[0]->systoliclow)){
                      Observation_BP::where('device_id',$device_id)->where('patient_id',$patientid)
                      ->where('effdatetime',$recorddate)
                      ->where('observation_id',$observationid)
                      ->update(['alert_status'=>1]);
                    }
                  }
                }else{
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
    
                    Observation_BP::where('device_id',$device_id)
                      ->where('patient_id',$patientid)
                      ->where('effdatetime',$recorddate)
                      ->where('observation_id',$observationid)
                      ->update(['threshold'=>$orgthreshold,'threshold_type'=>$thresholdtype,
                      'threshold_id'=>$thresholdid,'systolic_threshold'=>$systolic_threshold,'diastolic_threshold'=>$diastolic_threshold]); 
                      if($gt[0]->diastolichigh == null || $gt[0]->diastolichigh == 'null' || $gt[0]->diastolichigh == '' ||
                      $gt[0]->diastolichigh == "" || $gt[0]->diastoliclow == 'null' 
                      || $gt[0]->diastoliclow == "" || $gt[0]->diastoliclow == ''){                    
                      }else{
                        if(($diastolic_value > $gt[0]->diastolichigh) || ($diastolic_value < $gt[0]->diastoliclow ))
                        {
                          Observation_BP::where('device_id',$device_id)->where('patient_id',$patientid)
                          ->where('effdatetime',$recorddate)
                          ->where('observation_id',$observationid)
                          ->update(['alert_status'=>1]);
                        }
                      }
                      if($gt[0]->systolichigh == null || $gt[0]->systolichigh == 'null' || $gt[0]->systolichigh == '' ||
                      $gt[0]->systolichigh == "" || $gt[0]->systoliclow == 'null' 
                      || $gt[0]->systoliclow == "" || $gt[0]->systoliclow == ''){                    
                      }else{
                        if(($systolic_value > $gt[0]->systolichigh) && ($systolic_value > $gt[0]->systoliclow)){
                          Observation_BP::where('device_id',$device_id)->where('patient_id',$patientid)
                          ->where('effdatetime',$recorddate)
                          ->where('observation_id',$observationid)
                          ->update(['alert_status'=>1]);
                        }
                      }
                  }
                }
          }else{
              $Practicethreshold = $checkinpracticethreshold[0]->systoliclow."-".$checkinpracticethreshold[0]->systolichigh."/".$checkinpracticethreshold[0]->diastoliclow."-".$checkinpracticethreshold[0]->diastolichigh;
              $systolic_threshold = $checkinpracticethreshold[0]->systoliclow."-".$checkinpracticethreshold[0]->systolichigh;
              $diastolic_threshold = $checkinpracticethreshold[0]->diastoliclow."-".$checkinpracticethreshold[0]->diastolichigh;
                ($Practicethreshold == null || $Practicethreshold == 'null' || $Practicethreshold == '-/-' || $Practicethreshold == "-/-" ) ? $Practicethreshold = '' : $Practicethreshold;
                ($Practicethreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'Pr';
                ($Practicethreshold=='' ) ? $thresholdid = null : $thresholdid = $checkinpracticethreshold[0]->id;

              Observation_BP::where('device_id',$device_id)
                ->where('patient_id',$patientid)
                // ->where('mrn',$mrn_no)
                // ->where('partner_id',$partner_id)
                ->where('effdatetime',$recorddate)
                ->where('observation_id',$observationid)
                ->update(['threshold'=>$Practicethreshold,'threshold_type'=>$thresholdtype,
                'threshold_id'=>$thresholdid,'systolic_threshold'=>$systolic_threshold,'diastolic_threshold'=>$diastolic_threshold]); 
              
              if($Practicethreshold=="" || $Practicethreshold=='' || $Practicethreshold==null){  
              }else{
                if($checkinpracticethreshold[0]->diastolichigh == null || $checkinpracticethreshold[0]->diastolichigh == 'null' || $checkinpracticethreshold[0]->diastolichigh == '' ||
                  $checkinpracticethreshold[0]->diastolichigh == "" || $checkinpracticethreshold[0]->diastoliclow == 'null' 
                  || $checkinpracticethreshold[0]->diastoliclow == "" || $checkinpracticethreshold[0]->diastoliclow == ''){                    
                }else{
                    if(($diastolic_value > $checkinpracticethreshold[0]->diastolichigh) || ($diastolic_value < $checkinpracticethreshold[0]->diastoliclow ))
                    {
                      Observation_BP::where('device_id',$device_id)->where('patient_id',$patientid)
                      ->where('effdatetime',$recorddate)
                      ->where('observation_id',$observationid)
                      ->update(['alert_status'=>1]);
                    }
                }
                if($checkinpracticethreshold[0]->systolichigh == null || $checkinpracticethreshold[0]->systolichigh == 'null' || $checkinpracticethreshold[0]->systolichigh == '' ||
                  $checkinpracticethreshold[0]->systolichigh == "" || $checkinpracticethreshold[0]->systoliclow == 'null' 
                  || $checkinpracticethreshold[0]->systoliclow == "" || $checkinpracticethreshold[0]->systoliclow == ''){                    
                }else{
                    if(($systolic_value > $checkinpracticethreshold[0]->systolichigh) && ($systolic_value > $checkinpracticethreshold[0]->systoliclow)){
                      Observation_BP::where('device_id',$device_id)->where('patient_id',$patientid)
                      ->where('effdatetime',$recorddate)
                      ->where('observation_id',$observationid)
                      ->update(['alert_status'=>1]);
                    }
                }
              }            
          }
        }else{
          $patientthreshold = $checkpatientthreshold[0]->systoliclow."-".$checkpatientthreshold[0]->systolichigh."/".$checkpatientthreshold[0]->diastoliclow."-".$checkpatientthreshold[0]->diastolichigh;
          $systolic_threshold = $checkpatientthreshold[0]->systoliclow."-".$checkpatientthreshold[0]->systolichigh;
          $diastolic_threshold = $checkpatientthreshold[0]->diastoliclow."-".$checkpatientthreshold[0]->diastolichigh; 

          ($patientthreshold == null || $patientthreshold == 'null' || $patientthreshold == '-/-' || $patientthreshold == "-/-" ) ? $patientthreshold = '' : $patientthreshold;
          ($patientthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'P';
          ($patientthreshold=='' ) ? $thresholdid = null : $thresholdid = $checkpatientthreshold[0]->id;

          Observation_BP::where('device_id',$deviceid)->where('patient_id',$patientid)->where('mrn',$mrn_no)
            ->where('effdatetime',$recorddate)->where('observation_id',$observationid)
            ->update(['threshold'=>$patientthreshold,'threshold_type'=>$thresholdtype,
            'threshold_id'=>$thresholdid,'systolic_threshold'=>$systolic_threshold,
            'diastolic_threshold'=>$diastolic_threshold ]);
          if($patientthreshold=="" || $patientthreshold=='' || $patientthreshold==null){  
          }else{
            if($checkpatientthreshold[0]->diastolichigh == null || $checkpatientthreshold[0]->diastolichigh == 'null' || $checkpatientthreshold[0]->diastolichigh == '' ||
            $checkpatientthreshold[0]->diastolichigh == "" || $checkpatientthreshold[0]->diastoliclow == 'null' 
            || $checkpatientthreshold[0]->diastoliclow == "" || $checkpatientthreshold[0]->diastoliclow == ''){                    
            }else{
              if(($diastolic_value > $checkpatientthreshold[0]->diastolichigh) || ($diastolic_value < $checkpatientthreshold[0]->diastoliclow ))
              {
                Observation_BP::where('device_id',$device_id)->where('patient_id',$patientid)
                ->where('effdatetime',$recorddate)
                ->where('observation_id',$observationid)
                ->update(['alert_status'=>1]);
              }
            }
            if($checkpatientthreshold[0]->systolichigh == null || $checkpatientthreshold[0]->systolichigh == 'null' || $checkpatientthreshold[0]->systolichigh == '' ||
            $checkpatientthreshold[0]->systolichigh == "" || $checkpatientthreshold[0]->systoliclow == 'null' 
            || $checkpatientthreshold[0]->systoliclow == "" || $checkpatientthreshold[0]->systoliclow == ''){                    
            }else{
              if(($systolic_value > $checkpatientthreshold[0]->systolichigh) && ($systolic_value > $checkpatientthreshold[0]->systoliclow)){
                Observation_BP::where('device_id',$device_id)->where('patient_id',$patientid)
                ->where('effdatetime',$recorddate)
                ->where('observation_id',$observationid)
                ->update(['alert_status'=>1]);
              }
            }
          }
        }
      }

      if($deviceName=='SpO2'){
          // echo"dasdsadas";
        $observation_data = Observation_Oxymeter::where('device_id',$device_id)
          ->where('patient_id',$patientid)
          ->where('effdatetime',$recorddate)
          ->where('observation_id',$observationid)
          ->orderBy('created_at', 'desc')->get();
  
          if(isset($observation_data) || $observation_data!='' || $observation_data!=null){ 
            $oxy_value = $observation_data[0]->oxy_qty;
          }else{
            $oxy_value = 0;
          }
          
          $checkpatientthreshold = PatientThreshold::where('patient_id',$patientid)
          ->whereNotNull('oxsathigh')
          ->whereNotNull('oxsatlow')  
          ->orderBy('created_at', 'desc')
          ->get(); 
  
      if($checkpatientthreshold->isEmpty())
      {    
        $checkinpracticethreshold = \DB::select(\DB::raw("select * from patients.patient_providers pp 
          inner join patients.practice_threshold pt on pp.practice_id =pt.practice_id 
          and pp.provider_type_id =1 and pp.is_active =1 and pp.patient_id = '".$patientid."'
          where  pt.oxsathigh is not null and  pt.oxsatlow is not null order by pt.created_at desc
          ")); 
          if($checkinpracticethreshold='' || empty($checkinpracticethreshold)){         
            $org = \DB::select(\DB::raw("select ot.* from patients.patient_providers pp
              inner join patients.practice_threshold pt on pp.practice_id =pt.practice_id 
              inner join ren_core.practices p on pt.practice_id  =p.id
              inner join ren_core.org_threshold ot on p.practice_group =ot.org_id 
              and pp.provider_type_id =1 and pp.is_active =1 and pp.patient_id ='".$patientid."'
              where ot.oxsathigh is not null and  ot.oxsatlow is not null 
              order by pt.created_by desc"));
            if(!empty($org)){
                $orgthreshold = $org[0]->oxsatlow."-".$org[0]->oxsathigh;
                $oxy_threshold = $org[0]->oxsatlow."-".$org[0]->oxsathigh;
                ($orgthreshold == null || $orgthreshold == 'null' || $orgthreshold == '-/-' || $orgthreshold == "-/-" ) ? $orgthreshold = '' : $orgthreshold;
                ($orgthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'O';
                ($orgthreshold=='' ) ? $thresholdid = null : $thresholdid = $org[0]->id; 
                Observation_Spirometer::where('device_id',$device_id)
                ->where('patient_id',$patientid)
                // ->where('mrn',$mrn_no)
                ->where('effdatetime',$recorddate)
                ->where('observation_id',$observationid)
                //->where('partner_id',$partner_id)
                ->update(['threshold'=>$orgthreshold,
                'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid,
                'threshold'=>$oxy_threshold]);
                if($orgthreshold=="" || $orgthreshold=='' || $orgthreshold==null){                         
                }
                else{
                  if(($oxy_value > $org[0]->oxsathigh) || ($oxy_value < $org[0]->oxsatlow ))
                  {
                    Observation_Oxymeter::where('device_id',$device_id)->where('patient_id',$patientid)
                      //  ->where('mrn',$mrn_no) 
                      //->where('partner_id',$partner_id)
                      ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                  }

                }
            }else{ //checking in groupthreshold  
                $gt =  GroupThreshold::whereNotNull('oxsatlow')                               
                  ->whereNotNull('oxsathigh')
                  ->orderBy('created_at', 'desc')->get(); 
                $grpthreshold = $gt[0]->oxsatlow."-".$gt[0]->oxsathigh;
                $oxy_threshold = $gt[0]->oxsatlow."-".$gt[0]->oxsathigh;

                ($grpthreshold == null || $grpthreshold == 'null' || $grpthreshold == '-/-' || $grpthreshold == "-/-" ) ? $grpthreshold = '' : $grpthreshold;

                ($grpthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'R';
                ($grpthreshold=='' ) ? $thresholdid = null : $thresholdid = $gt[0]->id; 
                Observation_Oxymeter::where('device_id',$device_id)
                  ->where('patient_id',$patientid)
                  //  ->where('mrn',$mrn_no)
                  ->where('effdatetime',$recorddate)
                  ->where('observation_id',$observationid)
                  // ->where('partner_id',$partner_id)
                  ->update(['threshold'=>$grpthreshold,'threshold_type'=>$thresholdtype,
                  'threshold_id'=>$thresholdid,'threshold'=>$oxy_threshold]);
                  if($grpthreshold=="" || $grpthreshold=='' || $grpthreshold==null){  
                  }
                  else{
                    if(($oxy_value > $gt[0]->oxsathigh) || ($oxy_value < $gt[0]->oxsatlow ))
                      {
                          Observation_Oxymeter::where('device_id',$device_id)->where('patient_id',$patientid)
                          // ->where('mrn',$mrn_no)
                          // ->where('partner_id',$partner_id)
                          ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                      }
                  }
            }     
          }else{
            $practicethreshold = $checkinpracticethreshold[0]->oxsatlow."-".$checkinpracticethreshold[0]->oxsathigh;
            $oxy_threshold = $checkinpracticethreshold[0]->oxsatlow."-".$checkinpracticethreshold[0]->oxsathigh;
            ($practicethreshold == null || $practicethreshold == 'null' || $practicethreshold == '-/-' || $practicethreshold == "-/-" ) ? $practicethreshold = '' : $practicethreshold;
            ($practicethreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'Pr';
            ($practicethreshold=='' ) ? $thresholdid = null : $thresholdid = $checkinpracticethreshold[0]->id;

             Observation_Oxymeter::where('device_id',$device_id)
             ->where('patient_id',$patientid)
            //  ->where('mrn',$mrn_no)
            // ->where('partner_id',$partner_id)
             ->where('effdatetime',$recorddate)
             ->where('observation_id',$observationid)
             ->update(['threshold'=>$practicethreshold,'threshold_type'=>$thresholdtype,
             'threshold_id'=>$thresholdid,'threshold'=>$oxy_threshold]);
              if($grpthreshold=="" || $grpthreshold=='' || $grpthreshold==null){  
              }
              else{
                  if(($oxy_value > $practicethreshold[0]->oxsathigh) || ($oxy_value < $practicethreshold[0]->oxsatlow ))
                  {
                    Observation_Spirometer::where('device_id',$device_id)->where('patient_id',$patientid)
                    // ->where('partner_id',$partner_id)//->where('mrn',$mrn_no)
                    ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                  }
              }
          } 
      }else{
          $patientthreshold = $checkpatientthreshold[0]->oxsatlow."-".$checkpatientthreshold[0]->oxsathigh;
          $oxy_threshold = $checkpatientthreshold[0]->oxsatlow."-".$checkpatientthreshold[0]->oxsathigh;
          ($patientthreshold == null || $patientthreshold == 'null' || $patientthreshold == '-/-' || $patientthreshold == "-/-" ) ? $patientthreshold = '' : $patientthreshold;
          ($practicethreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'P';
          ($practicethreshold=='' ) ? $thresholdid = null : $thresholdid = $checkpatientthreshold[0]->id;
          Observation_Oxymeter::where('device_id',$device_id)
            ->where('patient_id',$patientid)
            // ->where('mrn',$mrn_no)
            // ->where('partner_id',$partner_id)
            ->where('effdatetime',$recorddate)
            ->where('observation_id',$observationid)
            ->update(['threshold'=>$patientthreshold,
            'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid,
            'threshold'=>$oxy_threshold
          ]); 
          if($patientthreshold=="" || $patientthreshold=='' || $patientthreshold==null){  
          }
          else{
              if(($oxy_value > $checkpatientthreshold[0]->oxsathigh) || ($oxy_value < $checkpatientthreshold[0]->oxsatlow ))
              {
                Observation_Oxymeter::where('device_id',$device_id)->where('patient_id',$patientid)
                // ->where('partner_id',$partner_id)// ->where('mrn',$mrn_no)
                ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
              }
          }
      }
    }
    if($deviceName =='BloodGlucose'){
        // dd("BloodGlucose - device name");
        $observation_data = Observation_Glucose::where('device_id',$device_id)->where('patient_id',$patientid)
        ->where('effdatetime',$recorddate)->orderBy('created_at', 'desc')->get(); 
        if($observation_data->isEmpty()){
          $glucose_value = 0; 
        }else{ 
          $glucose_value = $observation_data[0]->value; 
        }
        $checkpatientthreshold = PatientThreshold::where('patient_id',$patientid)
        ->whereNotNull('glucosehigh')
        ->whereNotNull('glucoselow')
        ->orderBy('created_at', 'desc')->get();         
        if($checkpatientthreshold->isEmpty())
        {
          $checkinpracticethreshold = \DB::select(\DB::raw("select * from patients.patient_providers pp 
          inner join patients.practice_threshold pt on pp.practice_id =pt.practice_id 
          and pp.provider_type_id =1 and pp.is_active =1 and pp.patient_id = '".$patientid."'
          where  pt.glucosehigh is not null and  pt.glucoselow is not null
          order by pt.created_at desc
          "));
            if($checkinpracticethreshold='' || empty($checkinpracticethreshold)){
             $org = \DB::select(\DB::raw("select ot.* from patients.patient_providers pp
                inner join patients.practice_threshold pt on pp.practice_id =pt.practice_id 
                inner join ren_core.practices p on pt.practice_id  =p.id
                inner join ren_core.org_threshold ot on p.practice_group =ot.org_id 
                and pp.provider_type_id =1 and pp.is_active =1 and pp.patient_id ='".$patientid."'
                where ot.glucosehigh is not null and  ot.glucoselow is not null
                order by pt.created_by desc"));
              
              if(!empty($org)){
                $orgthreshold = $org[0]->glucoselow."-".$org[0]->glucosehigh;
                ($orgthreshold == null || $orgthreshold == 'null' || $orgthreshold == '-' || $orgthreshold == "-" ) ? $orgthreshold = '' : $orgthreshold;

                ($orgthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'O';
                ($orgthreshold=='' ) ? $thresholdid = null : $thresholdid = $org[0]->id;

                Observation_Glucose::where('device_id',$device_id)
                ->where('patient_id',$patientid)
                ->where('effdatetime',$recorddate)
                ->where('observation_id',$observationid)
                ->update(['threshold'=>$orgthreshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);

                if(($value > $org[0]->glucosehigh) || ($value < $org[0]->glucoselow ))
                {
                Observation_Glucose::where('device_id',$device_id)->where('patient_id',$patientid)
                ->where('partner_id',$partner_id)//->where('mrn',$mrn_no)
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

                  Observation_Glucose::where('device_id',$device_id)
                  ->where('patient_id',$patientid)
                  ->where('effdatetime',$recorddate)
                  ->where('observation_id',$observationid)
                  ->update(['threshold'=>$glucose_group_threshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);
                  //checking in groupthreshold  
                  if($glucose_group_threshold=="" || $glucose_group_threshold=='' || $glucose_group_threshold==null){ 
                  }
                  else{
                    if(($glucose_value > $gt[0]->glucosehigh) || ($glucose_value < $gt[0]->glucoselow ))
                    {
                    Observation_Glucose::where('device_id',$device_id)->where('patient_id',$patientid)
                    // ->where('partner_id',$partner_id)//->where('mrn',$mrn_no)
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
                ($glucose_practice_threshold=='' ) ? $thresholdtype = '' : $thresholdtype = 'Pr';                       
                ($glucose_practice_threshold=='' ) ? $thresholdid = '' : $thresholdid = $checkinpracticethreshold[0]->id;                                
                  Observation_Glucose::where('device_id',$device_id)
                  ->where('patient_id',$patientid)
                  ->where('effdatetime',$recorddate)
                  ->where('observation_id',$observationid)
                  ->update(['threshold'=>$glucose_practice_threshold,
                  'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);

              if($glucose_practice_threshold=="" || $glucose_practice_threshold=='' || $glucose_practice_threshold==null){ 
              }
              else{
              if(($glucose_value > $checkinpracticethreshold[0]->glucosehigh) || ($glucose_value < $checkinpracticethreshold[0]->glucoselow ))
                {
                Observation_Glucose::where('device_id',$device_id)->where('patient_id',$patientid)
                // ->where('partner_id',$partner_id)//->where('mrn',$mrn_no)
                ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                } 
              }
            } 
        }else{       
          $glucose_patient_threshold = $checkpatientthreshold[0]->glucoselow."-".$checkpatientthreshold[0]->glucosehigh;
          ($glucose_patient_threshold == null || $glucose_patient_threshold == 'null' || $glucose_patient_threshold == '-' || $glucose_patient_threshold == "-" ) ? $glucose_patient_threshold = '' : $glucose_patient_threshold;
          ($glucose_patient_threshold=='' ) ? $thresholdtype = null : $thresholdtype = 'P';
          ($glucose_patient_threshold=='' ) ? $thresholdid = null : $thresholdid = $checkpatientthreshold[0]->id;

            Observation_Glucose::where('device_id',$device_id)
            ->where('patient_id',$patientid)
            // ->where('mrn',$mrn_no)
            ->where('effdatetime',$recorddate)
            ->where('observation_id',$observationid)
            ->update(['threshold'=>$glucose_patient_threshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);
            if($glucose_patient_threshold=="" || $glucose_patient_threshold=='' || $glucose_patient_threshold==null){ 
            }
            else{
            //alert threshold
              if(($glucose_value > $checkpatientthreshold[0]->glucosehigh) || ($glucose_value < $checkpatientthreshold[0]->glucoselow ))
              {
                Observation_Glucose::where('device_id',$device_id)->where('patient_id',$patientid)
                // ->where('partner_id',$partner_id)//->where('mrn',$mrn_no)
                ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
              }
            }
        } 
    }
    if($deviceName='Weight'){
        $observation_data = Observation_Weight::where('device_id',$device_id)->where('patient_id',$patientid)
        ->where('effdatetime',$recorddate)->orderBy('created_at', 'desc')->get(); 
        // ($observation_data);
        if($observation_data->isEmpty()){
          $weight_value = 0; 
        }else{ 
          $weight_value = $observation_data[0]->weight;
        }
         
        //------------------------------threshold------------------------------------
        $checkpatientthreshold = PatientThreshold::where('patient_id',$patientid)
        ->whereNotNull('weightlow')
        ->whereNotNull('weighthigh')
        ->orderBy('created_at', 'desc')
        ->get();
          if($checkpatientthreshold->isEmpty())
          {  
            $checkinpracticethreshold = \DB::select(\DB::raw("select * from patients.patient_providers pp 
            inner join patients.practice_threshold pt on pp.practice_id =pt.practice_id 
            and pp.provider_type_id =1 and pp.is_active =1 and pp.patient_id = '".$patientid."'
            where  pt.systolichigh is not null and  pt.systoliclow is not null
            and pt.diastolichigh  is not null and pt.diastoliclow is not null order by pt.created_at desc
            "));
              if($checkinpracticethreshold='' || empty($checkinpracticethreshold)){
                $org = \DB::select(\DB::raw("select ot.* from patients.patient_providers pp
                  inner join patients.practice_threshold pt on pp.practice_id =pt.practice_id 
                  inner join ren_core.practices p on pt.practice_id  =p.id
                  inner join ren_core.org_threshold ot on p.practice_group =ot.org_id 
                  and pp.provider_type_id =1 and pp.is_active =1 and pp.patient_id ='".$patientid."'
                  where ot.systolichigh is not null and  ot.systoliclow is not null
                  and ot.diastolichigh  is not null and ot.diastoliclow is not null 
                  order by pt.created_by desc"));
                
                  if(!empty($org)){
                      $orgthreshold = $org[0]->weightlow."-".$org[0]->weighthigh;
                      ($orgthreshold == null || $orgthreshold == 'null' || $orgthreshold == '-' || $orgthreshold == "-" ) ? $orgthreshold = '' : $orgthreshold;
                      ($orgthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'O';
                      ($orgthreshold=='' ) ? $thresholdid = null : $thresholdid = $org[0]->id;
                      Observation_Weight::where('device_id',$device_id)
                      ->where('patient_id',$patientid)
                      // ->where('mrn',$mrn_no)
                      ->where('effdatetime',$recorddate)
                      ->where('observation_id',$observationid)
                      ->update(['threshold'=>$orgthreshold,'threshold_type'=>$orgthreshold,'threshold_id'=>$thresholdid]);
                      if($orgthreshold=="" || $orgthreshold=='' || $orgthreshold==null){                         
                      }
                      else{
                        if(($value > $org[0]->weighthigh) || ($value < $org[0]->weightlow ) )
                        {
                        Observation_Weight::where('device_id',$device_id)->where('patient_id',$patientid)
                        // ->where('mrn',$mrn_no)
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

                      Observation_Weight::where('device_id',$device_id)
                      ->where('patient_id',$patientid)
                      // ->where('mrn',$mrn_no)
                      ->where('effdatetime',$recorddate)
                      ->where('observation_id',$observationid)
                      ->update(['threshold'=>$grpthreshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid ]);

                        if($grpthreshold=="" || $grpthreshold=='' || $grpthreshold==null){                         
                        }
                        else{
                          if((weight_value > $gt[0]->weighthigh) || (weight_value < $gt[0]->weightlow ) )
                          {
                          Observation_Weight::where('device_id',$device_id)->where('patient_id',$patientid)
                          //->where('mrn',$mrn_no)
                          ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                          }
                        }
                    }
                  }
              } //checking in groupthreshold  
              else{
                $practicethreshold = $checkinpracticethreshold[0]->weightlow."-".$checkinpracticethreshold[0]->weighthigh;
                ($practicethreshold == null || $practicethreshold == 'null' || $practicethreshold == '-' || $practicethreshold == "-" ) ? $practicethreshold = '' : $practicethreshold;
                ($practicethreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'Pr';
                ($practicethreshold=='' ) ? $thresholdid = null : $thresholdid = $checkinpracticethreshold[0]->id;
                Observation_Weight::where('device_id',$device_id)
                ->where('patient_id',$patientid)
                // ->where('mrn',$mrn_no)
                ->where('effdatetime',$recorddate)
                ->where('observation_id',$observationid)
                ->update(['threshold'=>$practicethreshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);
                  if($practicethreshold=="" || $practicethreshold=='' || $practicethreshold==null){                         
                  }
                  else{
                    if((weight_value > $checkinpracticethreshold[0]->weighthigh) || (weight_value < $checkinpracticethreshold[0]->weightlow ) )
                    {
                      Observation_Weight::where('device_id',$device_id)->where('patient_id',$patientid)
                      //->where('mrn',$mrn_no)
                      ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
                    }
                  }
              } 
          }
          else{  
            $patientthreshold = $checkpatientthreshold[0]->weightlow."-".$checkpatientthreshold[0]->weighthigh;
            ($patientthreshold == null || $patientthreshold == 'null' || $patientthreshold == '-' || $patientthreshold == "-" ) ? $patientthreshold = '' : $patientthreshold;
            ($patientthreshold=='' ) ? $thresholdtype = null : $thresholdtype = 'P';
            ($patientthreshold=='' ) ? $thresholdid = null : $thresholdid = $checkpatientthreshold[0]->id;
            Observation_Weight::where('device_id',$device_id)
            ->where('patient_id',$patientid)
            // ->where('mrn',$mrn_no)
            ->where('effdatetime',$recorddate)
            ->where('observation_id',$observationid)
            ->update(['threshold'=>$patientthreshold,'threshold_type'=>$thresholdtype,'threshold_id'=>$thresholdid]);
            if($patientthreshold=="" || $patientthreshold=='' || $patientthreshold==null){                         
            }
            else{
              if((weight_value > $checkpatientthreshold[0]->weighthigh) || (weight_value < $checkpatientthreshold[0]->weightlow ) )
              {
                Observation_Weight::where('device_id',$device_id)->where('patient_id',$patientid)
                //->where('mrn',$mrn_no)
                ->where('effdatetime',$recorddate)->where('observation_id',$observationid)->update(['alert_status'=>1]);
              }
            }
          } 
    }//end weight
  }

  // public function abc(){
  //   $thresholds = PatientThreshold::where('patient_id',$patientid)->orderBy('created_at', 'desc')
  //       ->get(); //patient threshold
       
  //     $checkpractice = PatientProvider::where('patient_id',$patientid)
  //       ->where('is_active',1)->where('provider_type_id',1)->first();
       
  //     if($thresholds->isEmpty()){
  //       if(!empty($checkpractice)){
  //         $thresholds = PracticeThreshold::where('practice_id',$checkpractice->practice_id)->orderBy('created_at', 'desc')
  //         ->get(); //practice threshold
  //         dd($thresholds);
  //         if($thresholds->isEmpty() && $thresholds!=){
  //             $orgid = Practices::where('id',$checkpractice->practice_id)->get(); //$orgid[0]['practice_group']
  //             $thresholds = OrgThreshold::where('org_id',$orgid[0]['practice_group'])->get();
  //             print_r($thresholds);
  //         }
  //         else{
  //           $thresholds = GroupThreshold::orderBy('created_at', 'desc')->get();
            
  //         }
  //       }
  //     }
  // }
 
      
}