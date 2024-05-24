<?php
namespace RCare\API\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RCare\Patients\Models\PatientDevices;
use RCare\API\Models\Partner;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
use RCare\API\Models\ApiTellihealth; 
use RCare\API\Models\MioWebhook; 

use RCare\Rpm\Models\Observation_BP;
use RCare\Rpm\Models\Observation_Oxymeter;  
use RCare\Rpm\Models\Observation_Heartrate;  
use RCare\Rpm\Models\Observation_Weight;
use RCare\Rpm\Models\Observation_Glucose;
use RCare\Rpm\Models\Observation_Spirometer;
use RCare\Rpm\Models\Observation_Temp;
use Illuminate\Support\Facades\Http;


use RCare\Patients\Models\PatientServices;
use RCare\Org\OrgPackages\Practices\src\Models\Practices;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\PatientThreshold;
use RCare\Org\OrgPackages\Threshold\src\Models\GroupThreshold;
use RCare\Org\OrgPackages\Practices\src\Models\PracticeThreshold;
use RCare\Org\OrgPackages\Practices\src\Models\OrgThreshold;
use RCare\Org\OrgPackages\DomainFeatures\src\Models\DomainFeatures;

use RCare\API\Models\ApiException;
use RCare\Rpm\Models\Devices;
use Carbon\Carbon;
use DB;
use Config;
use Exception;
use DataTables;
use Session;
use Illuminate\Support\Str;
use URL;

class MioDeviceController extends Controller {



    public function test_miowebhook_demo_data(){
        return view('API::test');
        // $content = json_encode('hello Api');
      }


      public function test_mio_webhook_observation(Request $request){  

        $content=$request->all(); 
		// dd($content); 
        $newcontent=json_encode($content);  
		$currenturl = url()->full();		

		if($currenturl == 'https://api.medhtech.com/API/data_from_device'){
            $response = Http::post('https://rcareconnect.com/API/data_from_device', $content);
            if ($response->getStatusCode() == 200) {
                $data=array(
                    'content'=>$newcontent,
                    'partner'=>'miowebhook',
                    'status' =>'0',  
                    'url'  =>  $currenturl,
                    'rconnect_transfer_flag' => 1
                );
    
                $result= MioWebhook::create($data);
                if($result)
                { 
                  return response()->json("Data inserted successfully!");
                }
                else
                {
                  return "failed";
                }
            }else{
                $data=array(
                    'content'=>$newcontent,
                    'partner'=>'miowebhook',
                    'status' =>'0',  
                    'url'  =>  $currenturl,
                    'rconnect_transfer_flag' => 0
                );
    
                $result= MioWebhook::create($data);
                if($result)
                { 
                  return response()->json("Data inserted successfully!");
                }
                else
                {
                  return "failed";
                }
            }
        }else{
            $data=array(
                'content'=>$newcontent,
                'partner'=>'miowebhook',
                'status' =>'0',  
                'url'  =>  $currenturl,
                'rconnect_transfer_flag' => 0
            );

            $result= MioWebhook::create($data);
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

      public function mio_webhook_observation(Request $request){  
        $content=$request->all(); 
        $deviceid = $request->route('id');
        // dd($deviceid); 
         $currenturl = url()->full();
		// $strArray = explode('/',$currenturl);
		// $lastElement = end(explode('-', $strArray));
		// dd($lastElement);
        $newcontent=json_encode($content);
        if($currenturl == 'https://api.medhtech.com/API/data_from_device/'.$deviceid){
            $response = Http::post('https://rcareconnect.com/API/data_from_device/'.$deviceid, $content);
            if ($response->getStatusCode() == 200) {
                $data=array(
                    'content'=>$newcontent,
                    'partner'=>'miowebhook',
                    'status' =>'0', 
                    'device_id' => $deviceid,
                    'url'  =>  $currenturl,
                    'rconnect_transfer_flag' => 1          
                );
            }else{
                $data=array(
                    'content'=>$newcontent,
                    'partner'=>'miowebhook',
                    'status' =>'0', 
                    'device_id' => $deviceid,
                    'url'  =>  $currenturl,
                    'rconnect_transfer_flag' => 0          
                );
            }
             $result= MioWebhook::create($data);
              if($result)
              {
                return response()->json("Data inserted successfully!");
              }
              else
              {
                return "failed";
              }
        }else{
            $data=array(
                'content'=>$newcontent,
                'partner'=>'miowebhook',
                'status' =>'0', 
                'device_id' => $deviceid,
                'url'  =>  $currenturl,
                'rconnect_transfer_flag' => 0          
            );
             $result= MioWebhook::create($data);
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
    
  
    public function process_mio_webhook_observation(){
      $getData = MioWebhook::where('status',0)->where(function($query){
		  $query->whereNotNull('device_id')->orWhere('content->messageType','=','telemetry');
	  })->orderBy('id', 'DESC')->get();
      $d = count($getData);
      $i=1;
      foreach($getData as $value)
      {
        $decodeContent = json_decode($value->content, true);
        // if(array_key_exists('deviceId', $decodeContent )) {
      
        //     //fetch device details
        //     $deviceId = $decodeContent['deviceId'];
        //     $status = $decodeContent['status'];
        //     $imei = $status['imei'];
        //     $modelNumber = $decodeContent['modelNumber'];
        // }else{
            $recorddate = '';	
        $id = $value['id'];
        $time = '';
        $device_id = "";
        $Chkdevice_id = $value['device_id'];

		if(!empty($Chkdevice_id)){
            $device_id = $value['device_id'];
            $systolic = $decodeContent['sys'];
            $diastolic = $decodeContent['dia'];
			$time = $decodeContent['ts'];
            $pulse = $decodeContent['pul'];
		}else{
            $device_id = $decodeContent['deviceId'];
			$systolic = $decodeContent['data']['sys'];
			$diastolic = $decodeContent['data']['dia'];
			$time = $decodeContent['data']['ts'];		
            $pulse = $decodeContent['data']['pul'];
 			
		}

              if(!empty($time)){
            $recorddate = date('Y-m-d H:i:s', $time); 
        }
        // $decodeContent = $datajson['data'];
        // $recorddate = date('Y-m-d H:i:s', ($time)/1000); 
        // $deviceName = $datajson['readingType'];
        //$checkDeviceExist= PatientDevices::where('device_code',$device_id)->whereNotNull('device_code')->exists();
        $get_checkDeviceExist= PatientDevices::where('device_code',$device_id)->whereNotNull('device_code')->select('patient_id')->get();

        $devicecount = $get_checkDeviceExist->count(); 
        
        if($devicecount > 0){
        $patient_id = $get_checkDeviceExist[0]->patient_id;
        $partner_id = $get_checkDeviceExist[0]->partner_id;
        $observationid = $patient_id.'-'.$time;

       
        
            if($patient_id!=null){

            // if($deviceName == 'BloodPressure'){ 
                $vitaldevice = Devices::where('device_name','Blood Pressure Cuff')->where('status',1)->first();
                $vitaldeviceid = $vitaldevice->id;
              
                // $arrhythmia = $decodeContent['arrhythmia'];
                $insert_array =array( 
                'patient_id' =>$patient_id, 
                'device_id' => $device_id ,
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
                      
                // $check = \DB::select(\DB::raw("select * from  api.tellihealth t
                // inner join rpm.observations_bp ob
                // on t.content->>'deviceId'=ob.device_id 
                // and(to_timestamp((content->>'time')::bigint/1000))
                // at time zone '".$configTZ."' at time zone '".$userTZ."'= ob.effdatetime 
                // and t.status =0
                // order by t.id desc"));

                $check = \DB::select(\DB::raw("select * from  rpm.observations_bp ob
                where device_id = '".$device_id."' and (effdatetime at time zone 'UTC' at time zone 'CST') = '".$recorddate."'"));
                
                // dd($check);
                    if(empty($check)){
                        
                        $o = Observation_BP::create($insert_array);
                        $url = strtolower(URL::to('/').'/rcare-login'); 
						if($o && DomainFeatures::where(DB::raw('lower(url)'), $url)->where('rpm_messages',1)->exists()){
                            
                            $ccmSubModule = ModuleComponents::where('components',"Monthly Monitoring")->where('module_id',2)->where('status',1)->get('id');
                            $SID          = getFormStageId(2, $ccmSubModule[0]->id, 'Reading Message');
                            $enroll_msg = CommonFunctionController::sentSchedulMessage(2,$patient_id,$SID);
                            $count = Observation_BP::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
                            if($count == 16){
                                $SID1          = getFormStageId(2, $ccmSubModule[0]->id, 'Sixteen Reading');
                                $enroll_msg = CommonFunctionController::sentSchedulMessage(2,$patient_id,$SID1);
                            }
                        }
                        $update_threshold = Observation_BP::where('device_id',$device_id)
                        ->where('patient_id',$patient_id)//->where('mrn',$mrn_no)->where('observation_id',$observationid)
                        ->where('effdatetime',$recorddate)
                        ->get();  
                        
                        // dd($update_threshold );
                        if($update_threshold!=''){

                            $this->saveThresholdReadingOfMioWebhook('BloodPressure',$recorddate,$patient_id,$partner_id,$device_id,$observationid);
                            // $this->saveThresholdReadings($deviceName,$recorddate,$patient_id,$partner_id,$device_id,$observationid);
                            // ApiTellihealth::where('id',$id)->update(['status'=>1]);
                            \DB::select(\DB::raw("update api.mio_webhook t set status=1
                            where  id= '".$id."' "));
                        }
                    }

                    
            // }
       
            }else{
                
            } 

         }

        //}  
     

        } 
    }     

    public function saveThresholdReadingOfMioWebhook($deviceName,$recorddate,$patient_id,$partner_id,$device_id,$observationid)
    {
            // dd($deviceName);
            $patientid = $patient_id;
            if($deviceName =='BloodPressure')
            {
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

    
      
    
    }
    
    
    
      
}