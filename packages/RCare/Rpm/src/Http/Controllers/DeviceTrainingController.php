<?php
namespace RCare\Rpm\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use Illuminate\Http\Request;
use RCare\Rpm\Models\Patient;
// use RCare\Rpm\Models\MailTemplate;
use RCare\Org\OrgPackages\QCTemplates\src\Models\ContentTemplate;
use RCare\Rpm\Models\Template;
use RCare\Rpm\Models\RcareServices;
use RCare\Rpm\Models\RcareSubServices;
use RCare\Rpm\Models\Questionnaire;
use RCare\Rpm\Models\PatientEnrollment;
use RCare\Rpm\Models\Devices;
use RCare\Rpm\Models\DeviceTraining;
use RCare\Rpm\Models\PatientTimeRecord;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientAddress; 
use RCare\Patients\Models\PatientThreshold;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Patients\Models\PatientDemographics;
use RCare\Patients\Models\PatientDevices;
use RCare\Rpm\Http\Requests\DeviceTraningRequest;
use RCare\Patients\Models\PatientPartResearchStudy;
use RCare\Patients\Models\PatientPersonalNotes;
use DB;
use Hash;
use Validator,Redirect,Response;
use DataTables;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DeviceTrainingController extends Controller
{
    public function index()
    {
        return view('Rpm::patient.patient-list');
    }

     public function patientEnroll(){
       
        return view('Rpm::patient.enroll-patient');        
    } 

   

    public function fetchPatients(Request $request)
    {
        if ($request->ajax()) {
            $data = Patients::latest()->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                
                $btn ='<a href="traning-checklist/'.$row->id.'" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('patients');
    }

    //  public function fetchPatientsEnroll(Request $request){
    //     if ($request->ajax()) {
    //         $data = Patients::latest()->get();
    //         return Datatables::of($data)
    //         ->addIndexColumn()
    //         ->addColumn('action', function($row){
                
    //             $btn ='<a href="enroll-patient-checklist/'.$row->id.'" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
    //             return $btn;
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);
    //     }
    //     return view('patient_enroll');
    // }

    // public function enrollTraningChecklist($id)
    // {  
    //     $id = sanitizeVariable($id);
    //     $data =ContentTemplate::all();
    //     $type = Template::all();
    //     $service = RcareServices::all();
    //     $patient = Patients::where('id',$id)->get();
    //     $PatientAddress = PatientAddress::where('uid',$patient[0]->uid);
    //     $callScripts = ContentTemplate::where('template_type_id',3)->get();
    //     $contact_number =Patients::where('id',$id)->select('phone_primary','phone_secondary','other_contact_phone')->get();
    //     $contact_email= Patients::where('id',$id)->select('email','poa_email','other_contact_email')->get(); 
    //     $questionSet1 = Questionnaire::find(62);
    //     $questionSet2 = Questionnaire::find(61);
    //     return view('Rpm::patient.patient-enrollment',['patient'=>$patient], compact('data','type','contact_number','contact_email','callScripts','questionSet1','questionSet2','PatientAddress'));
    // }

    // public function fetchEmailContent(Request $request){
    //     $template_type_id = sanitizeVariable($request->template_type_id);
    //     $type = Template::all();
    //         $content_name =ContentTemplate::where('template_type_id',$template_type_id)->get();
    //         echo '<option value="">Choose Content Title</option>';
    //              foreach($content_name as $value){
    //          echo '<option value="'.$value->id .'">'.$value->content_title.' </option>';
    //         }
     
    // }


    // public function fetchContent(Request $request){
    //      $data =ContentTemplate::all();
    //      $content_title = isset($request->content_title) ? sanitizeVariable($request->content_title) : '';
    //      $query =ContentTemplate::where('id',$content_title)->get();
    //      return $query;
    // }


    // public function getCallScriptsById(Request $request){
    //     $id = sanitizeVariable($request->id);
    //     $scripts = ContentTemplate::where('id',$id)->get(); //
    //     $callScripts = ContentTemplate::where('template_type_id',3)->get(); //call script only
    //     $data = ContentTemplate::all();
    //     $type = Template::all();
    //     $service = RcareServices::all();
    //     return $scripts;
    // }

    // public function traningChecklist($id)
    // {   
    //     $id = sanitizeVariable($id);
    //     $patient = Patients::where('id',$id)->get();
    //     return view('Rpm::patient.traning-checklist',['patient'=>$patient]);
    // }

     public function deviceTraning($id)//patientinfo
    {   
        
        $id = sanitizeVariable($id);
        $usage  = ContentTemplate:: where('id',123)->where('status', 1)->get();
        $traning  = ContentTemplate:: where('id',124)->where('status', 1)->get();
        $patient = Patients::where('id',$id)->get();
        
        $devices = Devices::all();
        $module_id = getPageModuleName();
        $PatientAddress = PatientAddress::where('uid',$patient[0]->uid);

        $personal_notes = (PatientPersonalNotes::latest($id,'patient_id') ? PatientPersonalNotes::latest($id,'patient_id')->population() : "");
        $research_study = (PatientPartResearchStudy::latest($id,'patient_id') ? PatientPartResearchStudy::latest($id,'patient_id')->population() : "");
        $patient_threshold = (PatientThreshold::latest($id,'patient_id') ? PatientThreshold::latest($id,'patient_id')->population() : "");
        $patient_providers = PatientProvider::where('patient_id', $id)
       ->with('practice')->with('provider')->with('users')->where('provider_type_id',1)->orderby('id','desc')->first();   
        $patient_enroll_date = PatientServices::latest_module($id, $module_id);
        $last_time_spend = CommonFunctionController::getNetTimeBasedOnModule($id, $module_id);
        $services = Module::where('patients_service',1)->where('status',1)->get();
        $patient_demographics = PatientDemographics::where('patient_id', $id)->latest()->first();
        $PatientAddress = PatientAddress::where('patient_id', $id)->latest()->first();
        $PatientDevices = PatientDevices::where('patient_id',$id)->orderby('id','desc')->get();
        //dd($PatientDevices); 
        return view('Rpm::deviceTraning.device-traning', compact( 'usage', 'traning', 'patient', 'devices', 'PatientAddress', 'patient_providers', 'patient_enroll_date', 'last_time_spend', 'services', 'patient_demographics', 'personal_notes', 'research_study','patient_threshold','PatientDevices'));
    }

    public function toDolist()
    {
        return view('Rpm::stepsbreadcum.to-do-list');
    }

    //Device Traning viewsiew

    public function rpmDevicelist()
    {
        return view('Rpm::deviceTraning.patient-device-list');
    }


    public function rpmfetchPatients(Request $request)
    {
        if ($request->ajax()) {
            $data = Patients::latest()->where('device_training_completed', 0)->get();
            
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
               
                $btn ='<a href="device-training/'.$row->id.'" title="Start" ><i class="text-15 i-Next1" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })

             ->addColumn('device', function($row){


           
                        if($row->device_training_completed == 1){
                         $btn1 = '<i class="i-Yess i-Yes"  style="color: green;" title="Completed"></i>

                         ';
                       }

                    
                       else
                         {
                        $btn1 = '<i class="i-Closee i-Close"  style="color: red;" title="Incomplete"></i>
                         ';
                       }
            return $btn1;
        })
        ->rawColumns(['action','device'])
         
            ->make(true);
        }
        return view('deviceTraning/device');
    }

    // public function practicePatients($id)
	// {
    //     $id = sanitizeVariable($id);
    //     $patients = [];
    //     $patients = Patients::all()->where("practice_id", $id);
    //     return response()->json($patients); 

	// }

    public function fetchPatientEnrollDetails($id)
	{
        $id = sanitizeVariable($id);
        $data = Patients::all()->where("id", $id);
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
               
                $btn ='<a href="enroll-patient-checklist/'.$row->id.'" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function fetchPatientDeviceDetails($id)
    {
            $id = sanitizeVariable($id);   
            $data = Patients::all()->where("id", $id);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
               
                $btn ='<a href="device-training/'.$row->id.'" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                return $btn; 
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // public function patientEnrollmentInsert(Request $request){
    //      $UID =   sanitizeVariable($request->input('hidden_id'));  
    //      $contact_number = sanitizeVariable($request->input('contact_number'));
    // }  

//     public function getDevices(Request $request){
//         $devices = Devices::all();
//         return view('Rpm::deviceTraning.device-traning-sub-steps.patient-traning-info-checklist', compact('devices'));
//    }

   public function getContent(Request $request){
        $p = sanitizeVariable($request->patient_id); 
        $d = sanitizeVariable($request->device_id);
        $softwaredownload = sanitizeVariable($request->software_download_protocol_id_stage_id);
        $softwareusage = sanitizeVariable($request->software_usage_instruction_id_stage_id); 
        $softwaretraining = sanitizeVariable($request->software_training_id_stage_id);


        $patient = Patients::where('id',$p)->get();   
        $stage1_content = ContentTemplate::where('device_id',$d)
                                        ->where('stage_id', $softwaredownload)->where('status', 1)->get();
                                        // dd($stage1_content);
                                       
        $stage2_content = ContentTemplate::where('device_id',$d)
                                        ->where('stage_id',$softwareusage)->where('status', 1)->get();
                                        //dd($stage2_content);  

        $stage3_content = ContentTemplate::where('device_id',$d)
                                        ->where('stage_id',$softwaretraining)->where('status', 1)->get(); 

            if( count($stage1_content) == 0)  
            {
            $stage1msg = 1;
            $stage1_content = $stage1msg ;
            }  
            else {
            $stage1_content =  $stage1_content;    
            } 
            
            if( count($stage2_content) == 0) 
            {
            $stage2msg = 1;
            $stage2_content = $stage2msg ;
            }  
            else {
            $stage2_content =  $stage2_content;  
            }  
            
            if( count($stage3_content) == 0) 
            {
            $stage3msg = 1;
            $stage3_content = $stage3msg ;
            }  
            else {
            $stage3_content =  $stage3_content;  
            } 
         

        return json_encode(array("stage1_content"=>$stage1_content,"stage2_content"=>$stage2_content,"stage3_content"=>$stage3_content));
    }

    public function saveDeviceTraining(Request $request){

       $practice_id = NULL;
       $click = sanitizeVariable($request->click);
       $content1_id = isset($request->content1_id) ? sanitizeVariable($request->content1_id) : ''; 
       $content2_id = isset($request->content2_id) ? sanitizeVariable($request->content2_id) : '';
       $content3_id = isset($request->content3_id) ? sanitizeVariable($request->content3_id) : '';
       $stage_id1 = isset($request->stage_id1) ? sanitizeVariable($request->stage_id1) : ''; 
       $stage_id2 = isset($request->stage_id2) ? sanitizeVariable($request->stage_id2) : '';
       $stage_id3 = isset($request->stage_id3) ? sanitizeVariable($request->stage_id3) : '';  
       $patient_id = isset($request->patient_id) ? sanitizeVariable($request->patient_id) : ''; 
       $time_start = isset($request->time_start) ? sanitizeVariable($request->time_start) : ''; 
       $device_id =  isset($request->device_id) ? sanitizeVariable($request->device_id) : ''; 
       $time_stop = isset($request->time_stop) ? sanitizeVariable($request->time_stop) : '';
       $time_start = isset($request->time_start) ? sanitizeVariable($request->time_start) : '';
       $net_time = isset($request->net_time) ? sanitizeVariable($request->net_time) : '';
       $module_id = isset($request->module_id) ? sanitizeVariable($request->module_id) : '';
       $component_id = isset($request->component_id) ? sanitizeVariable($request->component_id) : '';
       
       


       if($click == 1){
            $content1_id = $content1_id;
            $content2_id = NULL;
            $content3_id = NULL;
            $time_start =  "00:00:00";
            $stage_id =  $stage_id1;
             // $time_stop = $request->time_stop;
       }
       if($click == 2){
        $content1_id = $content1_id;
        $content2_id = $content2_id;
        $content3_id = NULL;
        $stage_id =  $stage_id2;
        $time_on = PatientTimeRecord::select('timer_off')
                    ->where('UID', $patient_id)
                    ->where('stage_id', $stage_id1)
                    ->get();
        $time_start = $time_on[0]->timer_off;
        // $time_start=  $request->time_start;

       
        }
        if($click == 3){
            $content1_id = $content1_id;
            $content2_id = $content2_id;
            $content3_id = $content3_id;
            $time_start=  $time_start;
            $stage_id =  $stage_id3;
            $time_on = PatientTimeRecord::select('timer_off')
            ->where('UID', $patient_id)
            ->where('stage_id', $stage_id2)
            ->get();
            $time_start = $time_on[0]->timer_off;
        }
        $start = strtotime($time_start); 

        $end = strtotime($time_stop); 

        $totaltime = ($end - $start)  ; 

        $hours = intval($totaltime / 3600);   
        $seconds_remain = ($totaltime - ($hours * 3600)); 

        $minutes = intval($seconds_remain / 60);   
        $seconds = ($seconds_remain - ($minutes * 60)); 
        
        $net_time =  abs($hours) .':'. abs($minutes) .':'. abs($seconds);

       $data = array(
       'practice_id' => $practice_id,
       'UID' => $patient_id,
       'device_id' => $device_id,
       'download_protocol_completed' => $content1_id,
       'usage_instruction_completed' => $content2_id,
       'device_training_completed' => $content3_id,
       );

       $time_data = array(
        'UID' => $patient_id,
        'record_date' => date("Y/m/d"),
        'module_id' => $module_id,
        'component_id' => $component_id,
        'stage_id' => $stage_id,
        'timer_on' => $time_start,
        'timer_off' => $time_stop,
        'net_time' => $net_time,
        'billable' => 1,
        'net_time' => $net_time,
        'stage_id' => $stage_id
        );

       if($click == 1){ 

        $content1 = DeviceTraining::create($data);
        $time = PatientTimeRecord::create($time_data);
       }
       if($click == 2){

        DeviceTraining::where('UID',$request->patient_id)->update($data);
        $time = PatientTimeRecord::create($time_data);
       }
       if($click == 3){
        DeviceTraining::where('UID',$request->patient_id)->update($data);
        Patients::where('id',$request->patient_id)->update(['device_training_completed' => 1]);
        $time = PatientTimeRecord::create($time_data);
        return "Device Training Completed";
       }   
       
   }

//    public function getTimerData(Request $request)
//    {   
//     //  Log::info('---------timer data----------');
//     //  Log::info($request);
//        $patient_id = sanitizeVariable($request->patient_id);
//        $module_id = sanitizeVariable($request->module_id);
//        $component_id = sanitizeVariable($request->component_id);
       
    
//         $stage_id = PatientTimeRecord::select(DB::raw('MAX(stage_id)'))
//                                     ->where('UID', $patient_id)
//                                     ->get();


//        $created_at = PatientTimeRecord::select(DB::raw('MAX(created_at)'))
//                          ->where('module_id', $module_id)
//                          ->where('component_id', $component_id)
//                          ->where('UID', $patient_id)
//                          ->get();


//         $max_created_at = $created_at[0]->max; 
                       
//         $timer_stop= PatientTimeRecord::select(DB::raw('timer_off'))
//                     ->where('UID', $patient_id)
//                     ->where('created_at', $max_created_at)
//                     ->get();
                                    

//     $sub_module_timer_off = $timer_stop[0]->timer_off;
//     // Log::info('sub_module_timer_off');
  
//     // Log::info($sub_module_timer_off);
        
  
//         $device = DeviceTraining::select('device_id', 'download_protocol_completed', 'usage_instruction_completed', 'device_training_completed')
//                                     ->where('UID', $patient_id)
//                                     ->get();
       
//         $max_stage_id = $stage_id[0]->max;
//         // Log::info($max_stage_id);
       

//         if($max_stage_id){

            
//             $device_id = $device[0]->device_id;
//             $download_protocol_completed = $device[0]->download_protocol_completed;
//             $usage_instruction_completed = $device[0]->usage_instruction_completed;
//             $device_training_completed = $device[0]->device_training_completed;
//             return   json_encode(array("max_stage_id"=>$max_stage_id, "device_id"=>$device_id, "download_protocol_completed"=>$download_protocol_completed,
//                                        "usage_instruction_completed"=>$usage_instruction_completed, "device_training_completed"=>$device_training_completed,"sub_module_timer_off"=> $sub_module_timer_off));
            
//        }
//        if($max_stage_id == null){
//         $max_stage_id = 0;
//         return $max_stage_id;
      
//        } 
      
//    }

   public function SavePatientTraning(DeviceTraningRequest $request){
    $practice_id = NULL;
    $patientid= sanitizeVariable($request->patient_id);
    $uid = sanitizeVariable($request->uid);
    $deviceid = sanitizeVariable($request->devices);
    $c_patient =  sanitizeVariable($request->content_id_patient);
    $c_softwareusage = sanitizeVariable($request->content_id_softwear_usages);
    $c_devicetraining = sanitizeVariable($request->content_id_device_traning); 
    $starttime = sanitizeVariable($request->starttime);
    $endtime = sanitizeVariable($request->endtime);
    $moduleid = sanitizeVariable($request->module_id);
    $componentid = sanitizeVariable($request->component_id);
    $stageid = sanitizeVariable($request->stage_id);
    

    if($request->step == 1){
        $content_id_patient= $c_patient;
        $content_id_softwear_usages = NULL;
        $content_id_device_traning = NULL; 
    }else if($request->step == 2){
        $content_id_patient= NULL;
        $content_id_softwear_usages = $c_softwareusage;
        $content_id_device_traning = NULL;
    }else{
        $content_id_patient= NULL;
        $content_id_softwear_usages = NULL;
        $content_id_device_traning = $c_devicetraining;   
    }
    
    $data = array(
        'practice_id' => $practice_id,
        'UID' =>$patientid,
        'device_id' =>$deviceid ,
        'download_protocol_completed' =>  $c_patient,
        'usage_instruction_completed' =>  $c_softwareusage,
        'device_training_completed' =>  $c_devicetraining,
        'patient_id' => $patientid,
        'uid' =>  $uid 
        );
        if($request->step == 1){
            DeviceTraining::create($data);
        }else{
            DeviceTraining::where('UID',$patientid)->update($data); 
        }
        
        $start_time   = $starttime;
        $end_time     = $endtime;
        $patient_id   = $patientid;
        $module_id    = $moduleid;
        $component_id = $componentid;
        $stage_id     = $stageid;
        $step_id      = 0;
        $billable     = 1;
        $uid          = $patientid;
        $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable,$uid);
   }    

   public function getPatientData(Request $request){ 
  
    $patient_id = sanitizeVariable($request->patient_id);
    if (DeviceTraining::where('UID', $patient_id)->exists()) {
        $patientData = DeviceTraining::where('UID',$patient_id)->get();
        $device_id = $patientData[0]->device_id;
     }
     else{
        $device_id = 0;
     }
     
    
    return $device_id;       
}
}