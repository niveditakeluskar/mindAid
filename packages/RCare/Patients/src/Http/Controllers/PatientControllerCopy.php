<?php
namespace RCare\Patients\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RCare\Rpm\Models\Patient;
use RCare\Rpm\Models\MailTemplate;
use RCare\Rpm\Models\Template;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
// use RCare\Rpm\Models\RcareServices;
// use RCare\Rpm\Models\RcareSubServices;
use RCare\Rpm\Models\Questionnaire;
use RCare\Rpm\Models\PatientEnrollment;
use RCare\Rpm\Models\ContentTemplateUsageHistory;
use RCare\Rpm\Models\QuestionnaireTemplateUsageHistory;
use RCare\Rpm\Models\PatientTimeRecordPatients;
use RCare\Ccm\Models\Allergy;
use RCare\Ccm\Models\HealthServices;   
use RCare\Ccm\Models\Pets;
use RCare\Ccm\Models\Hobbies;
use RCare\Ccm\Models\Travel;
use RCare\Ccm\Models\CallPreparation;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientDemographics;
use RCare\Patients\Models\PatientAddress;
use RCare\Patients\Models\PatientPoa;
use RCare\Patients\Models\PatientServices;
use RCare\Rpm\Models\Providers;
use RCare\Patients\Models\PatientInsurance;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\PatientContactTime;
use RCare\Patients\Models\PatientTimeRecords;

use RCare\Patients\Http\Requests\PatientAddRequest;

use Session;
use Hash;
use Validator,Redirect,Response;
use DataTables;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use File,DB;

class PatientControllerCopy extends Controller
{
    public function index()
    {
        return view('Patients::patient.patient-list');
    }

     public function patientEnroll(){
       
        return view('Patients::patient.enroll-patient');
    } 

    // public function patientEnrollment(){
    //     $data = Template::all();
    //     $service = RcareServices::all();
    //     $subModule =  RcareSubServices::where('services_id',2)->get();
    //     // return view('Patients::patient.patient-enrollment',compact('data','subModule'));
    //     return view('Patients::mail.mail',compact('data','subModule'));
    // }

    public function fetchPatients(Request $request)
    {
        if ($request->ajax()) {
            $data = Patients::latest()->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                /*
                $btn =    '<a href="edit/'.$row->id.'" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>';*/
                $btn ='<a href="traning-checklist/'.$row->id.'" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('patients');
    }

     public function fetchPatientsEnroll(Request $request){
        if ($request->ajax()) {
            $data = Patients::orderby('id', 'DESC')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                /*
                $btn =    '<a href="edit/'.$row->id.'" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>';*/
                $btn ='<a href="enroll-patient-checklist/'.$row->id.'" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('patient_enroll');
    }

    public function enrollTraningChecklist($id)
    {
        $data =MailTemplate::all();
        $type = Template::all();
        $service = Module::all();
        $patient = Patients::where('id',$id)->get();
        $callScripts = MailTemplate::all();
        $questionSet1 = Questionnaire::find(62);
        $questionSet2 = Questionnaire::find(61);
        if(PatientTimeRecords::where('UID',$id)->where('module_id',3)->exists()){
            $EnrollStatus = 1;
        }else{
            $EnrollStatus = 0;
        }
        // $patient_enrollment = PatientEnrollment::where("UID",$id)->latest() ? PatientEnrollment::where("UID",$id)->latest()->body : null;
        // $questionnaire_template_usage_history = QuestionnaireTemplateUsageHistory::where('UID',$id)->latest()->first();
        // $hist_id = '31'; //$questionnaire_template_usage_history->id;
        // $patient_enrollment = PatientEnrollment::where('UID',$id)->whereJsonContains("enrollment_checklist->history_id",$hist_id)->latest()->first();
        // $patient_enrollment = PatientEnrollment::where("UID",$id)
        //         ->join('patients.questionnaire_template_usage_history',function($join) use($id) {
        //             $join->on('questionnaire_template_usage_history.id', '=', "patient_enrollment.enrollment_checklist.history_id")
        //                 ->on('questionnaire_template_usage_history.UID', '=', $id);
        //         })->select('questionnaire_template_usage_history.id', 'patient_enrollment.id')->first();
                
        // return $questionnaire_template_usage_history;
        $questionnaire_template_usage_history = DB::select( DB::raw('select * from patients.questionnaire_template_usage_history as q, patients.patient_enrollment as p  where "q"."UID" = \''.$id.'\' and q.id =  cast ( p.enrollment_checklist ->> \'history_id\' as INTEGER) and q.template_id = \'62\'order by q.id DESC limit 1 '));
        $questionnaire_template_usage_history2 = DB::select( DB::raw('select * from patients.questionnaire_template_usage_history as q, patients.patient_enrollment as p  where "q"."UID" = \''.$id.'\' and q.id =  cast ( p.finalization_checklist ->> \'history_id\' as INTEGER) and q.template_id = \'62\'order by q.id DESC limit 1 '));
        return view('Patients::patient.patient-enrollment',['patient'=>$patient], compact('data','type','callScripts','questionSet1','questionSet2', 'questionnaire_template_usage_history', 'questionnaire_template_usage_history2','EnrollStatus')); 
    }

    // public function enrollTraningChecklist($id)
    // {   // $data = Template::all();
    //     $data =MailTemplate::all();
    //     // dd($data);            
    //     $type = Template::all();
    //     $service = RcareServices::all();
    //     $patient = Patients::where('id',$id)->get();
    //     //$callScripts = MailTemplate::where('template_type_id',3)->get();
    //     $callScripts = MailTemplate::all();
    //     $contact_number =Patients::where('id',$id)->select('phone_primary','phone_secondary','other_contact_phone')->get();
    //     //dd($contact_number);
       
    //     $contact_email= Patients::where('id',$id)->select('email','poa_email','other_contact_email')->get();
    //     $questionSet1 = Questionnaire::find(62);
    //     $questionSet2 = Questionnaire::find(61);
    //     //$PatientServices = PatientServices::where('uid',$id)->get();
    //     return view('Patients::patient.patient-enrollment',['patient'=>$patient], compact('data','type','contact_number','contact_email','callScripts','questionSet1','questionSet2')); 
    // }

    public function fetchEmailContent(Request $request){
        $template_type_id = $request->template_type_id;
        $type = Template::all();
            $content_name =MailTemplate::where('template_type_id',$template_type_id)->get();
            echo '<option value="">Choose Content Title</option>';
                 foreach($content_name as $value){
             echo '<option value="'.$value->id .'">'.$value->content_title.' </option>';
            }
        // $subModule =  RcareSubServices::where(
    }
    public function fetchContent(Request $request){
         $data =MailTemplate::all();
         $content_title = $request->content_title;
         $query =MailTemplate::where('id',$content_title)->get();
         return $query;
    }

    public function getCallScriptsById(Request $request){
        $id = $request->id;
        $scripts = MailTemplate::where('id',$id)->get(); //
        $callScripts = MailTemplate::where('template_type_id',3)->get(); //call script only
        $data = MailTemplate::all();
        // dd($data);
        $type = Template::all();
        $service = Module::all();
        return $scripts;
    }

    public function traningChecklist($id)
    {   
        /* $license =$_GET['org_id'];*/
        // $license = RcareOrgs:: where('id',$user)->get();
        $patient = Patients::where('id',$id)->get();
        return view('Patients::patient.traning-checklist',['patient'=>$patient]);
    }

     public function deviceTraning($id)
    {   
        /* $license =$_GET['org_id'];*/
        $softwarep = MailTemplate:: where('id',122)->get();
        $usage  = MailTemplate:: where('id',123)->get();
        $traning  = MailTemplate:: where('id',124)->get();
        $patient = Patients::where('id',$id)->get();
        return view('Patients::deviceTraning.device-traning', compact('softwarep', 'usage', 'traning', 'patient'));
    }

    public function toDolist()
    {
        return view('Patients::stepsbreadcum.to-do-list');
    }

    //Device Traning viewsiew

    public function rpmDevicelist()
    {
        return view('Patients::deviceTraning.patient-device-list');
    }


    public function rpmfetchPatients(Request $request)
    {
        if ($request->ajax()) {
            $data = Patients::latest()->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                /*
                $btn =    '<a href="edit/'.$row->id.'" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>';*/
                // $btn ='<a href="device-traning/'.$row->id.'" title="Start" ><i class="text-15 i-Start-2" style="color: #2cb8ea;"></i></a>';
                $btn ='<a href="device-traning/'.$row->id.'" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
 
        }

        return view('deviceTraning/device');
    }

    // public function practicePatients($id)
	// {
    //     $patients = [];
    //     $patients = Patients::all()->where("practice_id", $id);
    //     return response()->json($patients);
    // }
    
    public function practicePatients($id)
	{
        $patients = [];
        $patients = DB::select( DB::raw('select p.id,p.fname,p.lname,p.mname,p.dob from patients.patient as p join patients.patient_providers as q  on "p"."id" = "q"."patient_id" where "q"."practice_id" = \''.$id.'\' '));
        
        //echo "hi";select * from patients.patient as p join patients.patient_providers as q  on "p"."id" = "q"."patient_id" where "q"."practice_id" = '2';
        //dd($patients);
        return response()->json($patients);

        // return "abcd";
		// $patients = [];
		// foreach ($practice->patients as $patients) {
		// 	$patients[$patient->id] = $patient->json();
		// }
		// return response()->json($patients);
	}

    public function fetchPatientEnrollDetails($id)
	{
        $data = Patients::all()->where("id", $id);
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                /*
                $btn =    '<a href="edit/'.$row->id.'" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>';*/
                // $btn ='<a href="enroll-patient-checklist/'.$row->id.'" title="Start" ><i class="text-20 i-Start-2" style="color: #2cb8ea;"></i></a>';
                $btn ='<a href="enroll-patient-checklist/'.$row->id.'" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function fetchPatientDeviceDetails($id)
    {
            $data = Patients::all()->where("id", $id);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                /*
                $btn =    '<a href="edit/'.$row->id.'" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>';*/
                // $btn ='<a href="device-traning/'.$row->id.'" title="Start" ><i class="text-15 i-Start-2" style="color: #2cb8ea;"></i></a>';
                $btn ='<a href="device-traning/'.$row->id.'" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function content_template_usage_history(Request $request){
        $contact_via       = $request->input('title');
        $hidden_id         = $request->input('hidden_id');
        $template_type     = $request->input('template_type');
        $UID               = $request->input('hidden_id');
        $module_id         = $request->input('module_id');
        $component_id      = $request->input('component_id');
        $template_id       = $request->input('template_id');
        $content_area_msg  = $request->input('content_area_msg');
        $stage_id          = $request->input('stage_id');
        $created_by        =  session()->get('userid');

       
    }

    public function patientEnrollmentService(Request $request){

        $enroll_module = $request->input('enroll_module');
        $uid = $request->input('hidden_id');
        foreach($enroll_module as $key => $value){
            //dd($key);
               $patient_enroll_data = array(
                    'patient_id'        => $uid,
                    'uid'               => $uid,
                    'module_id'         => $value,
                    'date_enrolled'     => date("Y-m-d"),
                    // 'date_expired'      => "00-00-00",
                    'created_by'        => session()->get('userid')
                );
                if (PatientServices::where('uid', $uid)->exists()) {
                    $patient_poa = PatientServices::where('uid', $uid)->update($patient_enroll_data);
                }else{
                    $patient_poa = PatientServices::create($patient_enroll_data);    
                }
            }
            echo  $uid;
    }

    public function patientEnrollmentInsert(Request $request){
         $form_id               = $request->input('form_id');
         $UID                   = $request->input('hidden_id');
         $practice_id           = $request->input('practice_id');
         $title                 = $request->input('title');
         $contact_number        = $request->input('contact_number');
         $contact_email         = $request->input('contact_email');
         $content_title         = $request->input('content_title');
         $template_type_id      = $request->input('template_type_id');
         $template_id           = $request->input('template_id');
         $contact_email         = $request->input('contact_email');
         $subject               = $request->input('subject');
         $content_area_msg      = $request->input('content_area_msg');
         $content               = strip_tags($request->input('content'));
         $module_id             = $request->input('module_id');
         $component_id          = $request->input('component_id');
         // $template_id           = $request->input('template_id');
         $template_type         = $request->input('template_type');
         $stage_id              = $request->input('stage_id');
         $created_by            =  session()->get('userid');
         // call
         $call_status           = $request->input('call_status');
         $enrollment_response   = $request->input('enrollment_response');
         $callback_time         = $request->input('time');
         $calback_date          = $request->input('date');
         $voice_mail            = $request->input('voice_mail');
         $script_stage_id       = $request->input('script_stage_id');
         $script_module_id      = $request->input('script_module_id');
         $script_component_id   = $request->input('script_component_id');
         $script_template_id    = $request->input('script_template_id');
         $script_template_type_id = $request->input('script_template_type_id');
         $script_content        = $request->input('script_content');
         $formData        		= $request->all();

       //echo 'id'.$module_id;
        //dd($formData);

        
            // pass json_array Email/Text
           
            if ($title=='Text'){
                $time_start =  "00:00:00";
                $start      = strtotime($time_start); 
                $end        = strtotime($request->time_stop); 
                $totaltime  = ($end - $start)  ; 
                $hours      = intval($totaltime / 3600);   
                $seconds_remain = ($totaltime - ($hours * 3600)); 
                $minutes    = intval($seconds_remain / 60);   
                $seconds    = ($seconds_remain - ($minutes * 60)); 
                $net_time   =  abs($hours) .':'. abs($minutes) .':'. abs($seconds);

                $stage_id =12;
                $contact = array('content_area_msg' => $content_area_msg, 'contact_number'=>$contact_number);
            	$data = array('contact_via'     => $title,
                                'template_type' => $template_type,
                                'UID'           => $UID,
                                'module_id'     => $module_id,
                                'component_id'  => $component_id,
                                'template_id'   => $template_id,
                                'template'      => json_encode($contact),
                                'stage_id'      => $stage_id,
                                'created_by'    => $created_by
                            );
       			
        		$history_id = ContentTemplateUsageHistory::create($data);
        	
                 $action = array ('type'    => 'content',
                       'template_id'    => $template_id, 
                       'history_id'     => $history_id->id,
                        'subject'       => $subject
                    );


                $action_template = json_encode($action);
                $data1 = array(
                            'practice_id'             => $practice_id, 
                            'UID'                     => $UID,
                            'action'                  => $title,
                            'action_template'         => $action_template,
                            'module_id'               => $module_id,
                            'component_id'            => $component_id,
                            'stage_id'                => $stage_id,
                            'created_by'              => $created_by
                        );
                // dd($data1);
                	 $save= PatientEnrollment::create($data1);
                   

                $time_data = array(
                    'UID' => $UID,
                    'record_date' => date("Y/m/d"),
                    'module_id' => $module_id,
                    'component_id' => $component_id,
                    'stage_id' => $stage_id,
                    'timer_on' =>  $time_start,
                    'timer_off' => $request->time_stop,
                    'net_time' => $net_time,
                    'billable' => 1,
                    'net_time' => $net_time,
                    'stage_id' => $stage_id
                );
            // dd($time_data);
             // $time = PatientTimeRecordPatients::create($time_data);
            //dd($time);  
           // dd($save); 
           echo $form_id;  
            }else if ($title=='Email') {
                $time_start =  "00:00:00";
                $start      = strtotime($time_start); 
                $end        = strtotime($request->time_stop); 
                $totaltime  = ($end - $start)  ; 
                $hours      = intval($totaltime / 3600);   
                $seconds_remain = ($totaltime - ($hours * 3600)); 
                $minutes    = intval($seconds_remain / 60);   
                $seconds    = ($seconds_remain - ($minutes * 60)); 
                $net_time   =  abs($hours) .':'. abs($minutes) .':'. abs($seconds);

                $stage_id =13;
                        $contact = array('content' => $content, 'contact_email'=>$contact_email);
            			$data =  array('contact_via'  => $request->input('title'),
                                                        'template_type'   => $template_type,
                                                        'UID'   => $UID,
                                                        'module_id'   => $module_id,
                                                        'component_id'   => $component_id,
                                                        'template_id'   => $template_id,
                                                        'template'   => json_encode($contact),
                                                        'stage_id'   => $stage_id,
                                                        'created_by'   => $created_by
                                                    );
				       // $history_id = $data->save(); 
                        $history_id = ContentTemplateUsageHistory::create($data);
				        $hid=$history_id->id;

                    $action = array ('type'    => 'content',
                       'template_id'    => $template_id, 
                       'history_id'     => $history_id->id,
                        'subject'       => $subject);

                    $action_template = json_encode($action);

                $data2 =array(
                            'practice_id'             => $practice_id, 
                            'UID'                     => $UID,
                            'action'                  => $title,
                            'action_template'         => $action_template,
                            'subject'                 => $subject,
                            'content'                 => $content,
                            'created_by'              => $created_by
                        );
                 // dd($data2);
                $save = PatientEnrollment::create($data2);
                $time_data = array(
                    'UID' => $UID,
                    'record_date' => date("Y/m/d"),
                    'module_id' => $module_id,
                    'component_id' => $component_id,
                    'stage_id' => $stage_id,
                    'timer_on' =>  $time_start,
                    'timer_off' => $request->time_stop,
                    'net_time' => $net_time,
                    'billable' => 1,
                    'net_time' => $net_time,
                    'stage_id' => $stage_id
                );
            // dd($time_data);
             // $time = PatientTimeRecordPatients::create($time_data);
               // dd($time);
                //dd($save);
                echo $form_id;
               
            }else if($title=='Call Attempt'){
                        $script_stage_id = 9;
                        $template_type = 'content';
            	        // dd($call_status);
                         $time_start =  "00:00:00";
                         $start      = strtotime($time_start); 
                         $end        = strtotime($request->time_stop); 
                         $totaltime  = ($end - $start)  ; 
                         $hours      = intval($totaltime / 3600);   
                         $seconds_remain = ($totaltime - ($hours * 3600)); 
                         $minutes    = intval($seconds_remain / 60);   
                         $seconds    = ($seconds_remain - ($minutes * 60)); 
                         $net_time   =  abs($hours) .':'. abs($minutes) .':'. abs($seconds);

                            if ($call_status =='1') {
                                $data =  array('contact_via' => 'Call',
                                        'template_type'   => $template_type,
                                        'UID'             => $UID,
                                        'module_id'       => $script_module_id,
                                        'component_id'    => $script_component_id,
                                        'template_id'     => $script_template_id,
                                        'template'        => json_encode($script_content),
                                        'stage_id'        => $script_stage_id,
                                        'created_by'      => $created_by
                                    );
                                // dd($data);
                                $history_id = ContentTemplateUsageHistory::create($data);
                                // dd($data); 
                                    //pass json_array call
                                     $action1 = array ('type'    => 'content',
                                                   'template_id'    => $script_template_id, 
                                                   'history_id'     => $history_id->id);


                                     $action_template1 = json_encode($action1);
                                    // dd($action_template1);
                                

                                 $data1 = array('call_status' => $call_status ,
                                 	'practice_id'             => $practice_id, 
                            		'UID'                     => $UID,
                                    'action'                  => 'Call',
                                    'enrollment_response'     => $enrollment_response,
                                    'action_template'         => $action_template1,
                                    'module_id'               => $script_module_id,
                                    'component_id'            => $script_component_id,
                                    'stage_id'                => $script_stage_id,
                                    'callback_time'           => $callback_time,
                                    'callback_date'           => $calback_date,
                                    'created_by'              => $created_by);
                                 //dd($data1);
                                $save = PatientEnrollment::create($data1);
                                echo $call_id = $save->id;
                                  $time_data = array(
                                    'UID' => $UID,
                                    'record_date' => date("Y/m/d"),
                                    'module_id' => $script_module_id,
                                    'component_id' => $script_component_id,
                                    'stage_id' =>   $script_stage_id,
                                    'timer_on' =>  $time_start,
                                    'timer_off' => $request->time_stop,
                                    'net_time' => $net_time,
                                    'billable' => 1,
                                    'net_time' => $net_time
                                );
                                //echo "done";
                            // dd($time_data);
                             // $time = PatientTimeRecordPatients::create($time_data);
                            // $time->timer_off;

                            }else{

                                if($voice_mail==''){
                                    //echo "voice_mail is blank";

                                }else{

                                    $data = array('call_status' => $call_status ,
                                    'practice_id'             => $practice_id, 
                                    'UID'                     => $UID,
                                    'action'                  => 'CAll',
                                    'voice_mail'              => $voice_mail,
                                    'created_by'              => $created_by );
                                    //dd($data);
                                   $save = PatientEnrollment::create($data);
                                     echo $call_id = $save->id;
                                       $time_data = array(
                                        'UID' => $UID,
                                        'record_date' => date("Y/m/d"),
                                        'module_id' => $script_module_id,
                                        'component_id' => $script_component_id,
                                        'stage_id' => $script_stage_id,
                                        'timer_on' =>  $time_start,
                                        'timer_off' => $request->time_stop,
                                        'net_time' => $net_time,
                                        'billable' => 1,
                                        'net_time' => $net_time
                                    );
                                // dd($time_data);
                                 // $time = PatientTimeRecordPatients::create($time_data);
                                    //dd($time);
                                }                         
                            }
                            
            }else if($title=='Patient Enrollment checklist'){
                $formData['stage_id'] =2;
                $data =  array('contact_via' 	=> 'Call',
                                'template_type' => $template_type,
                                'UID'   		=> $UID,
                                'module_id'   	=> $formData['module_id'],
                                'component_id'  => $formData['component_id'],
                                'template_id'   => $formData['template_id'],
                                'template'   	=> json_encode($formData['questionnaire']),
                                'stage_id'   	=> $stage_id,
                                'created_by'   	=> $created_by
                        );
                	//dd($data);
                    $history_id = QuestionnaireTemplateUsageHistory::create($data); 
                     //pass json_array question
         			$action2 = array ('template_type' => $template_type,
                      				  'template_id'    => $template_id, 
                       				  'history_id'     => $history_id->id);
         			$checklist_enrollment = json_encode($action2); 
         			// dd($checklist);
                      $data3 =  array('call_status'           => '1' , 
                      				'practice_id'             => $practice_id, 
                                    'UID'                     => $UID,
                                    'action'                  => 'Call',
                                    'enrollment_checklist'    => $checklist_enrollment,
                                    'module_id'               => $formData['module_id'],
                                    'component_id'            => $formData['component_id'],
                                    'stage_id'                => $formData['stage_id'],
                                    'created_by'              => $created_by);
                     // echo "id"; 
                      //dd($form_id);
                      $update = PatientEnrollment::where('id',$form_id)->update($data3);
                      echo $form_id;
                      // dd($update);

                    // PatientEnrollment::create($data3);

            }else if ($title =='Patient Enrollment Finalization Checklist') {
                    //$formData['stage_id']=3;
                    $data =  array('contact_via'    => 'Call',
                                'template_type' => $template_type,
                                'UID'           => $UID,
                                'module_id'     => $formData['module_id'],
                                'component_id'  => $formData['component_id'],
                                'template_id'   => $formData['template_id'],
                                'template'      => json_encode($formData['Fquestionnaire']),
                                'stage_id'      => $stage_id,
                                'created_by'    => $created_by
                        );
                    // dd($data);
                    $history_id = QuestionnaireTemplateUsageHistory::create($data); 
                     //pass json_array question
                    $action2 = array ('template_type' => $template_type,
                                      'template_id'    => $template_id, 
                                      'history_id'     => $history_id->id);
                    $checklist_finalize = json_encode($action2); 
                    // dd($checklist);
                      $data3 =  array('call_status'           => '1' , 
                                    'practice_id'             => $practice_id, 
                                    'UID'                     => $UID,
                                    'action'                  => 'Call',
                                    'finalization_checklist'  => $checklist_finalize,
                                    'module_id'               => $formData['module_id'],
                                    'component_id'            => $formData['component_id'],
                                    'stage_id'                => $formData['stage_id'],
                                    'created_by'              => $created_by);
                        // PatientEnrollment::create($data3);
                          $update = PatientEnrollment::where('id',$form_id)->update($data3);
                           echo $form_id;
                          dd($update);
                         
            }else if($title =='Patient Enrollment Finalization Checklist Update'){
                $data =  array('contact_via'    => 'Call',
                                'template_type' => $template_type,
                                'UID'           => $UID,
                                'module_id'     => $formData['module_id'],
                                'component_id'  => $formData['component_id'],
                                'template_id'   => $formData['template_id'],
                                'template'      => json_encode($formData['squestionnaire']),
                                'stage_id'      => $stage_id,
                                'created_by'    => $created_by
                        );
                        $history_id = QuestionnaireTemplateUsageHistory::create($data); 
                     //pass json_array question
                    $action2 = array ('template_type' => $template_type,
                                      'template_id'    => $template_id, 
                                      'history_id'     => $history_id->id);
                    $checklist_finalize = json_encode($action2); 
                    // dd($checklist);
                      $data3 =  array('call_status'           => '1' , 
                                    'practice_id'             => $practice_id, 
                                    'UID'                     => $UID,
                                    'action'                  => 'Call',
                                    'finalization_checklist'  => $checklist_finalize,
                                    'module_id'               => $formData['module_id'],
                                    'component_id'            => $formData['component_id'],
                                    'stage_id'                => $formData['stage_id'],
                                    'created_by'              => $created_by);
                        // PatientEnrollment::create($data3);
                          $update = PatientEnrollment::where('id',$form_id)->update($data3);

                        $data1 =  array('contact_via'    => 'Call',
                        'template_type' => $template_type,
                        'UID'           => $UID,
                        'module_id'     => $formData['module_id'],
                        'component_id'  => $formData['component_id'],
                        'template_id'   => $formData['template_id'],
                        'template'      => json_encode($formData['sFquestionnaire']),
                        'stage_id'      => $stage_id,
                        'created_by'    => $created_by
                         );        

                         $history_id = QuestionnaireTemplateUsageHistory::create($data1); 
                     //pass json_array question
         			$action3 = array ('template_type' => $template_type,
                      				  'template_id'    => $template_id, 
                       				  'history_id'     => $history_id->id);
         			$checklist_enrollment1 = json_encode($action3); 
         			// dd($checklist);
                      $data4 =  array('call_status'           => '1' , 
                      				'practice_id'             => $practice_id, 
                                    'UID'                     => $UID,
                                    'action'                  => 'Call',
                                    'enrollment_checklist'    => $checklist_enrollment1,
                                    'module_id'               => $formData['module_id'],
                                    'component_id'            => $formData['component_id'],
                                    'stage_id'                => $formData['stage_id'],
                                    'created_by'              => $created_by);
                     // echo "id"; 
                      //dd($form_id);
                      $update = PatientEnrollment::where('id',$form_id)->update($data4);
                     
            }
            
            else{
            		//  echo "kuch nai hone wala";
            }
         


    }

    public function saveAllergy(Request $request){
        $data = array(
        'patient_id'    => $request->patient_id,
        'allergy_type'       => $request->allergy_type,
        'type_of_reactions'  => $request->type_of_reaction,
        'severity'   => $request->severity,
        'course_of_treatment'      => $request->course_of_treatment,
        'notes'      => $request->additional_notes,
        'specify'       => $request->specify,
    );
        $insert_query = Allergy::create($data);
        return "Allergy added successfully";
    }

    public function saveHealthServices(Request $request){
        $replace = str_replace('-', '_', $request->service_type);
        $section_id = strtolower($replace);
        $health = DB::table('ren_core.health_services')
        ->select("id")
        ->where("alias", $section_id)
        ->get();
        $health_id = $health[0]->id;
   
        $data = array(
        'patient_id'    => $request->patient_id,
        'hid'    => $health_id,
        'type'       => $request->type,
        'from_whom' => $request->from_whom,
        'from_where' => $request->from_where,
        'frequency' => $request->frequency,
        'duration' => $request->duration,
        'brand'  => $request->brand,
        'purpose'   => $request->purpose,
        'specify'   => $request->specify,
        'notes'      => $request->additional_notes,
    );
        $insert_query = HealthServices::create($data);
        return "Service added successfully";
    }

    public function savePets(Request $request){
        $data = array(
        'uid'    => $request->patient_id,
        'description'       => $request->description,
        'notes'  => $request->notes,
    );
        $insert_query = Pets::create($data);
        return "Pets added successfully";
    }

    public function hobbies(Request $request){
        $data = array(
        'uid'    => $request->patient_id,
        'description'       => $request->description,
        'location'       => $request->location,
        'frequency'       => $request->frequency,
        'with_whom'       => $request->with_whom,
        'notes'  => $request->notes,
    );
        $insert_query = Hobbies::create($data);
        return "Pets added successfully";
    }

    public function travel(Request $request){
        $data = array(
        'uid'    => $request->patient_id,
        'location'       => $request->location,
        'type_of_travel'       => $request->type_of_travel,
        'frequency'       => $request->frequency,
        'with_whom'       => $request->with_whom,
        'upcoming_trips'       => $request->upcoming_trips,
        'notes'  => $request->notes,
    );
        $insert_query = Travel::create($data);
        return "Pets added successfully";
    }

    public function addAllergies($id, $sectionId) {
        $allergiesData = DB::table('patients.patient_allergy')
                ->select("specify", "type_of_reactions", "severity", "course_of_treatment", "notes")
                ->where('patient_id', $id)
                ->where('allergy_type', $sectionId)
                ->orderBy('created_at', 'DESC')
                ->get();
        return Datatables::of($allergiesData)
        ->addIndexColumn()
        ->make(true);
    }

    public function healthServices($id, $sectionId) {
        $replace = str_replace('-', '_', $sectionId);
        $section_id = strtolower($replace);
        $health = DB::table('ren_core.health_services')
                        ->select("id")
                        ->where("alias", $section_id)
                        ->get();
       $health_id = $health[0]->id;
        $servicesData = DB::table('patients.patient_healthcare_services')
                ->select("specify", "from_whom", "from_where", "purpose", "frequency", "duration", "notes")
                ->where('patient_id', $id)
                ->where('hid', $health_id)
                ->orderBy('created_at', 'DESC')
                ->get();
        return Datatables::of($servicesData)
        ->addIndexColumn()
        ->make(true);
    }

    public function healthServices1($id, $sectionId) {
        $replace = str_replace('-', '_', $sectionId);
        $section_id = strtolower($replace);
        $health = DB::table('ren_core.health_services')
                        ->select("id")
                        ->where("alias", $section_id)
                        ->get();
       $health_id = $health[0]->id;
        $servicesData = DB::table('patients.patient_healthcare_services')
                ->select("specify", "type", "brand", "purpose", "notes")
                ->where('patient_id', $id)
                ->where('hid', $health_id)
                ->orderBy('created_at', 'DESC')
                ->get();
        return Datatables::of($servicesData)
        ->addIndexColumn()
        ->make(true);
    }

    public function getPets($id, $sectionId) {
        $replace = str_replace('-', '_', $sectionId);
        $section_id = strtolower($replace);
        $servicesData = DB::table('ccm.patient_pets')
                ->select("description", "notes")
                ->where('uid', $id)
                ->orderBy('created_at', 'DESC')
                ->get();
        return Datatables::of($servicesData)
        ->addIndexColumn()
        ->make(true);
    }

    public function getHobbies($id, $sectionId) {
        $replace = str_replace('-', '_', $sectionId);
        $section_id = strtolower($replace);
        $servicesData = DB::table('ccm.patient_hobbies')
                ->select("description", "location", "frequency", "with_whom", "notes")
                ->where('uid', $id)
                ->orderBy('created_at', 'DESC')
                ->get();
        return Datatables::of($servicesData)
        ->addIndexColumn()
        ->make(true);
    }

    public function getTravel($id, $sectionId) {
        $replace = str_replace('-', '_', $sectionId);
        $section_id = strtolower($replace);
        $servicesData = DB::table('ccm.patient_travel')
                ->select("travel_type", "location", "frequency", "with_whom", "notes",  "upcoming_trips")
                ->where('uid', $id)
                ->orderBy('created_at', 'DESC')
                ->get();
        return Datatables::of($servicesData)
        ->addIndexColumn()
        ->make(true);
    }
	
	public function patientRegistration(PatientAddRequest $request) {
		 $review = "0";
		 $uid = "0";
		$patient_data = array(  'fname'         => $request->fname,
                                'mname'         => $request->mname,
                                'lname'         => $request->lname,
                                'email'         => $request->email,
                                'uid'           => $uid,
                                'home_number'   => $request->phone_secondary,
                                'mob'           => $request->phone_primary,
                                'dob'           => $request->dob,
                                'review'        => $review, 
                                'org_id'        => session()->get('org_id'),
                                'created_by'    => session()->get('userid'),
                                'contact_preference_calling' => $request->calling_preference,
                                'contact_preference_sms' => $request->sms_preference,
                                'contact_preference_email' => $request->email_preference,
                                'contact_preference_letter' => $request->letter_preference,
                                'age' => $request->age,
                                'no_email' => $request->no_email,
                                'preferred_contact' => $request->preferred_contact,
                            );
		if ($request->has('edit')){
            Patients::where('id',$request->patient_id)->update($patient_data);
        }
        else{
            $patient = Patients::createFromRequest($patient_data);
            // $patient = Patients::create($patient_data);
        }
		
		//////////////// Demographics//////////////////////////
		
		$patient_demographics_data = array( 
                                            'gender'            => $request->gender,
                                            'marital_status'    => $request->marital_status,
                                            'education'         => $request->education,
                                            'ethnicity'         => $request->ethnicity1,
                                            'occupation'        => $request->occupation_status,
                                            'height'            => '',
                                            'weight'            => '',
                                            'employer'          => '',
                                            'occupation_description' => $request->occupation_description,
                                            'other_contact_name' => $request->other_contact_name,
                                            'other_contact_relationship' => $request->other_contact_relationship, 
                                            'other_contact_phone_number' => $request->other_contact_phone, 
                                            'other_contact_email' => $request->other_contact_email, 
                                            'military_status' => $request->military,
                                            'ethnicity_2' => $request->ethnicity2,
                                        );                    
        if ($request->has('edit')){
            PatientDemographics::where('patient_id',$request->patient_id)->update($patient_demographics_data);
        }
        else{
            $patient_demographics_data['patient_id'] = $patient->id;
            // $patient_demographics = PatientDemographics::createFromRequest($patient_demographics_data);
            $patient_demographics = PatientDemographics::create($patient_demographics_data);
        }
		
		$patient_address_data = array( 
            'add_1'              => $request->add_1,
            'add_2'              => $request->add_2,
            'state'              => $request->state,
            'zipcode'            => $request->zipcode,
            'city'               => $request->city,
        );                    

        if ($request->has('edit')){
            PatientAddress::where('patient_id',$request->patient_id)->update($patient_address_data);
        }
        else{
            $patient_address_data['patient_id'] = $patient->id;
            // $patient_address = PatientAddress::createFromRequest($patient_address_data);
            $patient_address = PatientAddress::create($patient_address_data);
        }
		
		$patient_poa_data = array( 
            'poa_first_name'        => $request->poa_first_name,
            'poa_last_name'         => $request->poa_last_name,
            'poa_age'               => 0,
            'poa_relationship'      => $request->poa_relationship,
            'poa_mobile'            => '0000',
            'poa_phone_2'           => $request->poa_phone_2,
            'poa_email'             => $request->poa_email,
            'poa'               => $request->poa,            
        );                    

        if ($request->has('edit')){
            PatientPoa::where('patient_id',$request->patient_id)->update($patient_poa_data);
        }
        else{
            $patient_poa_data['patient_id'] = $patient->id;
            // $patient_poa = PatientPoa::createFromRequest($patient_poa_data);
             $patient_poa = PatientPoa::create($patient_poa_data);
        }
		
		
		
		$enroll_modules = array(
			0 => $request->enroll_ccm,
			1 => $request->enroll_awv,
			2 => $request->enroll_rpm,
			3 => $request->enroll_tcm,
		);

            //Log::info($enroll_modules);
            $services_id = $request->services_table_id;
        foreach($enroll_modules as $key => $value){
        	   $patient_enroll_data = array(
                    'uid'               => $uid,
					'module_id'         =>  $value,
					'date_enrolled'     => date("Y-m-d"),
                    'created_by'        => session()->get('userid')
                );
                if ($request->has('edit')){
                   PatientServices::where('patient_id',$request->patient_id)->where('id',$services_id[$key])->update($patient_enroll_data);
                }
                else{
                    $patient_enroll_data['patient_id'] = $patient->id;
                    // $patient_enroll = PatientServices::createFromRequest($patient_enroll_data);
                    $patient_enroll = PatientServices::create($patient_enroll_data);
                }
            }
			
			
		foreach($insurance_type as $key => $value){
            $patient_insurance_data = array( 
                'code'          => $request->emr,
                'ins_type'      => $value,
                'ins_id'        => $insurance_id[$key],
                'ins_provider'  => "",
                'ins_plan'      => "",                
            );      

			    
            if ($request->has('edit')){ 
                    PatientInsurance::where('patient_id',$request->patient_id)->where('id',$ins_id[$key])->update($patient_insurance_data);
               
               
            }
            else{
                $patient_insurance_data['patient_id'] = $patient->id;  
                // $patient_poa = PatientInsurance::createFromRequest($patient_insurance_data);                  
                $patient_insurance = PatientInsurance::create($patient_insurance_data);
            }
        }
		
		
		 $patient_physician_data = array( 
            'provider_id'               => $request->physician_id,
            'provider_subtype_id'       => NULL,
            'practice_id'               => $request->practice_id,
            'address'                   => NULL,
            'phone_no'                  => '',
            'last_visit_date'           => NULL,
            'review'                    => NULL,
            'provider_name'             => NULL,
        );                    

        if ($request->has('edit')){
            PatientProvider::where('patient_id',$request->patient_id)->update($patient_physician_data);
        }
        else{
            $patient_physician_data['patient_id'] = $patient->id;
            // $patient_physician = PatientProvider::createFromRequest($patient_physician_data);
            $patient_physician = PatientProvider::create($patient_physician_data);
        }
		
		$patient_contact_time_data = array( 
            'mon_0'                 => $request->contact_time_mon_0 == null ? 0 : $request->contact_time_mon_0,
            'mon_1'                 => $request->contact_time_mon_1 == null ? 0 : $request->contact_time_mon_1,
            'mon_2'                 => $request->contact_time_mon_2 == null ? 0 : $request->contact_time_mon_2,
            'mon_3'                 => $request->contact_time_mon_3 == null ? 0 : $request->contact_time_mon_3,
            'mon_any'               => $request->contact_time_mon_any == null ? 0 : $request->contact_time_mon_any,
            'tue_0'                 => $request->contact_time_tue_0 == null ? 0 : $request->contact_time_tue_0,
            'tue_1'                 => $request->contact_time_tue_1 == null ? 0 : $request->contact_time_tue_1,
            'tue_2'                 => $request->contact_time_tue_2 == null ? 0 : $request->contact_time_tue_2,
            'tue_3'                 => $request->contact_time_tue_3 == null ? 0 : $request->contact_time_tue_3,
            'tue_any'               => $request->contact_time_tue_any == null ? 0 : $request->contact_time_tue_any,
            'wed_0'                 => $request->contact_time_wed_0 == null ? 0 : $request->contact_time_wed_0,
            'wed_1'                 => $request->contact_time_wed_1 == null ? 0 : $request->contact_time_wed_1,
            'wed_2'                 => $request->contact_time_wed_2 == null ? 0 : $request->contact_time_wed_2,
            'wed_3'                 => $request->contact_time_wed_3 == null ? 0 : $request->contact_time_wed_3,
            'wed_any'               => $request->contact_time_wed_any == null  ? 0 : $request->contact_time_wed_any,
            'thu_0'                 => $request->contact_time_thu_0 == null ? 0 : $request->contact_time_thu_0,
            'thu_1'                 =>$request->contact_time_thu_1 == null ? 0 : $request->contact_time_thu_1,
            'thu_2'                 => $request->contact_time_thu_2 == null ? 0 : $request->contact_time_thu_2,
            'thu_3'                 => $request->contact_time_thu_3 == null ? 0 : $request->contact_time_thu_3,
            'thu_any'               => $request->contact_time_thu_any == null ? 0 : $request->contact_time_thu_any,
            'fri_0'                 => $request->contact_time_fri_0 == null ? 0 : $request->contact_time_fri_0,
            'fri_1'                 => $request->contact_time_fri_1 == null ? 0 : $request->contact_time_fri_1,
            'fri_2'                 => $request->contact_time_fri_2 == null ? 0 : $request->contact_time_fri_2,
            'fri_3'                 => $request->contact_time_fri_3 == null ? 0 : $request->contact_time_fri_3,
            'fri_any'               => $request->contact_time_fri_any == null ? 0 : $request->contact_time_fri_any,
        );  

        if ($request->has('edit')){
            PatientContactTime::where('patient_id',$request->patient_id)->update($patient_contact_time_data);
        }
        else{
            $patient_contact_time_data['patient_id'] = $patient->id;
            // PatientContactTime::createFromRequest($patient_contact_time_data);
            PatientContactTime::create($patient_contact_time_data);
        }
        echo "Save";
		
		
	}

    public function patientRegistration_old(PatientAddRequest $request) {
        // Log::info('----daaad---');
        // Log::info($request);
        $uid = '12';
        $review = "1";
        $height = "5.6";
        $weight = "77";
        $employer = "123";
        $age = "67";
        $enroll_module = $request->enroll_module;
        $insurance_type = $request->insurance_type;
        $insurance_id = $request->insurance_id;
        $patient_data = array(  'fname'         => $request->fname,
                                'mname'         => $request->mname,
                                'lname'         => $request->lname,
                                'email'         => $request->email,
                                'uid'           => $uid,
                                'home_number'   => $request->phone_secondary,
                                'mob'           => $request->phone_primary,
                                'dob'           => $request->dob,
                                'review'        => $review, 
                                'org_id'        => session()->get('org_id'),
                                'created_by'    => session()->get('userid'),
                                'contact_preference_calling' => $request->calling_preference,
                                'contact_preference_sms' => $request->sms_preference,
                                'contact_preference_email' => $request->email_preference,
                                'contact_preference_letter' => $request->letter_preference,
                                'age' => $request->age,
                                'no_email' => $request->no_email,
                                'preferred_contact' => $request->preferred_contact,
                            );
 
        if ($request->has('edit')){
            Patients::where('id',$request->patient_id)->update($patient_data);
        }
        else{
            $patient = Patients::createFromRequest($patient_data);
            // $patient = Patients::create($patient_data);
        }

        $patient_demographics_data = array( 
                                            'gender'            => $request->gender,
                                            'marital_status'    => $request->marital_status,
                                            'education'         => $request->education,
                                            'ethnicity'         => $request->ethnicity1,
                                            'occupation'        => $request->occupation_status,
                                            'height'            => $height,
                                            'weight'            => $weight,
                                            'employer'          => $employer,
                                            'occupation_description' => $request->occupation_description,
                                            'other_contact_name' => $request->other_contact_name,
                                            'other_contact_relationship' => $request->other_contact_relationship, 
                                            'other_contact_phone_number' => $request->other_contact_phone, 
                                            'other_contact_email' => $request->other_contact_email, 
                                            'military_status' => $request->military,
                                            'ethnicity_2' => $request->ethnicity2,
                                        );                    
        if ($request->has('edit')){
            PatientDemographics::where('patient_id',$request->patient_id)->update($patient_demographics_data);
        }
        else{
            $patient_demographics_data['patient_id'] = $patient->id;
            // $patient_demographics = PatientDemographics::createFromRequest($patient_demographics_data);
            $patient_demographics = PatientDemographics::create($patient_demographics_data);
        }
        

        $patient_address_data = array( 
            'add_1'              => $request->add_1,
            'add_2'              => $request->addr2,
            'state'              => $request->state,
            'zipcode'            => $request->zip,
            'city'               => $request->city,
        );                    

        if ($request->has('edit')){
            PatientAddress::where('patient_id',$request->patient_id)->update($patient_address_data);
        }
        else{
            $patient_address_data['patient_id'] = $patient->id;
            // $patient_address = PatientAddress::createFromRequest($patient_address_data);
            $patient_address = PatientAddress::create($patient_address_data);
        }

        $patient_poa_data = array( 
            'poa_first_name'        => $request->poa_name,
            'last_name'         => $request->poa_name,
            'age'               => $request->age,
            'relationship'      => $request->poa_relationship,
            'mobile'            => $request->poa_phone,
            'phone_2'           => $request->poa_phone,
            'email'             => $request->poa_email,
            'poa'               => $request->poa,
            
        );                    

        if ($request->has('edit')){
            PatientPoa::where('patient_id',$request->patient_id)->update($patient_poa_data);
        }
        else{
            $patient_poa_data['patient_id'] = $patient->id;
            // $patient_poa = PatientPoa::createFromRequest($patient_poa_data);
             $patient_poa = PatientPoa::create($patient_poa_data);
        }


		$enroll_modules = array(
			0 => $request->enroll_ccm,
			1 => $request->enroll_awv,
			2 => $request->enroll_rpm,
			3 => $request->enroll_tcm,
		);

            //Log::info($enroll_modules);
            $services_id = $request->services_table_id;
        foreach($enroll_modules as $key => $value){
        	   $patient_enroll_data = array(
                    'uid'               => $uid,
					'module_id'         =>  $value,
					'date_enrolled'     => date("Y-m-d"),
                    'created_by'        => session()->get('userid')
                );
                if ($request->has('edit')){
                   PatientServices::where('patient_id',$request->patient_id)->where('id',$services_id[$key])->update($patient_enroll_data);
                }
                else{
                    $patient_enroll_data['patient_id'] = $patient->id;
                    // $patient_enroll = PatientServices::createFromRequest($patient_enroll_data);
                    $patient_enroll = PatientServices::create($patient_enroll_data);
                }
            }

            // $abc = PatientInsurance::select('id')->where('patient_id', $request->patient_id)->get();
            // Log::info('poi');
            // Log::info($abc[0]->id);
            $ins_id = $request->insurance_table_id;
        foreach($insurance_type as $key => $value){
            $patient_insurance_data = array( 
                'code'          => $request->emr,
                'ins_type'      => $value,
                'ins_id'        => $insurance_id[$key],
                'ins_provider'  => "",
                'ins_plan'      => "",                
            );      

			    
            if ($request->has('edit')){ 
                    PatientInsurance::where('patient_id',$request->patient_id)->where('id',$ins_id[$key])->update($patient_insurance_data);
               
               
            }
            else{
                $patient_insurance_data['patient_id'] = $patient->id;  
                // $patient_poa = PatientInsurance::createFromRequest($patient_insurance_data);                  
                $patient_insurance = PatientInsurance::create($patient_insurance_data);
            }
        }

        $patient_physician_data = array( 
            'provider_id'               => $request->physician_id,
            'provider_subtype_id'       => NULL,
            'practice_id'               => $request->practice_id,
            'address'                   => NULL,
            'phone_no'                  => '',
            'last_visit_date'           => NULL,
            'review'                    => NULL,
            'provider_name'             => NULL,
        );                    

        if ($request->has('edit')){
            PatientProvider::where('patient_id',$request->patient_id)->update($patient_physician_data);
        }
        else{
            $patient_physician_data['patient_id'] = $patient->id;
            // $patient_physician = PatientProvider::createFromRequest($patient_physician_data);
            $patient_physician = PatientProvider::create($patient_physician_data);
        }

        $patient_contact_time_data = array( 
            'mon_0'                 => $request->contact_time_mon_0 == null ? 0 : $request->contact_time_mon_0,
            'mon_1'                 => $request->contact_time_mon_1 == null ? 0 : $request->contact_time_mon_1,
            'mon_2'                 => $request->contact_time_mon_2 == null ? 0 : $request->contact_time_mon_2,
            'mon_3'                 => $request->contact_time_mon_3 == null ? 0 : $request->contact_time_mon_3,
            'mon_any'               => $request->contact_time_mon_any == null ? 0 : $request->contact_time_mon_any,
            'tue_0'                 => $request->contact_time_tue_0 == null ? 0 : $request->contact_time_tue_0,
            'tue_1'                 => $request->contact_time_tue_1 == null ? 0 : $request->contact_time_tue_1,
            'tue_2'                 => $request->contact_time_tue_2 == null ? 0 : $request->contact_time_tue_2,
            'tue_3'                 => $request->contact_time_tue_3 == null ? 0 : $request->contact_time_tue_3,
            'tue_any'               => $request->contact_time_tue_any == null ? 0 : $request->contact_time_tue_any,
            'wed_0'                 => $request->contact_time_wed_0 == null ? 0 : $request->contact_time_wed_0,
            'wed_1'                 => $request->contact_time_wed_1 == null ? 0 : $request->contact_time_wed_1,
            'wed_2'                 => $request->contact_time_wed_2 == null ? 0 : $request->contact_time_wed_2,
            'wed_3'                 => $request->contact_time_wed_3 == null ? 0 : $request->contact_time_wed_3,
            'wed_any'               => $request->contact_time_wed_any == null  ? 0 : $request->contact_time_wed_any,
            'thu_0'                 => $request->contact_time_thu_0 == null ? 0 : $request->contact_time_thu_0,
            'thu_1'                 =>$request->contact_time_thu_1 == null ? 0 : $request->contact_time_thu_1,
            'thu_2'                 => $request->contact_time_thu_2 == null ? 0 : $request->contact_time_thu_2,
            'thu_3'                 => $request->contact_time_thu_3 == null ? 0 : $request->contact_time_thu_3,
            'thu_any'               => $request->contact_time_thu_any == null ? 0 : $request->contact_time_thu_any,
            'fri_0'                 => $request->contact_time_fri_0 == null ? 0 : $request->contact_time_fri_0,
            'fri_1'                 => $request->contact_time_fri_1 == null ? 0 : $request->contact_time_fri_1,
            'fri_2'                 => $request->contact_time_fri_2 == null ? 0 : $request->contact_time_fri_2,
            'fri_3'                 => $request->contact_time_fri_3 == null ? 0 : $request->contact_time_fri_3,
            'fri_any'               => $request->contact_time_fri_any == null ? 0 : $request->contact_time_fri_any,
        );  

        if ($request->has('edit')){
            PatientContactTime::where('patient_id',$request->patient_id)->update($patient_contact_time_data);
        }
        else{
            $patient_contact_time_data['patient_id'] = $patient->id;
            // PatientContactTime::createFromRequest($patient_contact_time_data);
            PatientContactTime::create($patient_contact_time_data);
        }
        echo "Save";
    }

	
    public function physician($practice)
	{
        $physicians = [];
        $physicians = Providers::all()->where("practice_id", $practice);
        return response()->json($physicians);
    }

    public function registeredPatients(){
        return view('Patients::patient.registered-patient-list');
    } 
    
    public function fetchRegisteredPatients(Request $request)
    {
        //Log::info("--------lkj-----------");
        if ($request->ajax()) {
            $data = Patients::latest()->get();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="registerd-patient-edit/'.$row->id.'" title="Edit" ><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('patients-registered-patients');
    }



    public function populatePatientData($patientId)
    {
		//$pinfo = Patients::where('id', $patientId)->orderBy('created_at', 'desc')->first();
        //$patient = Patients::patientDetails($patientId)->populate();
		$patient = Patients::find($patientId);
		$patientdemo = PatientDemographics::where('patient_id',$patientId)->first();
		$patientAddresss = PatientAddress::where('patient_id',$patientId)->first();
		$patientInsurance = PatientInsurance::where('patient_id',$patientId)->first();
		$patientPoa = PatientPoa::where('patient_id',$patientId)->first();
		$patientProvider = PatientProvider::where('patient_id',$patientId)->first();
		$patientContactTime = PatientContactTime::where('patient_id',$patientId)->first();
		
		//dd($patientdemo);
        $patient = $patient->population();
		
		if($patientdemo){			
			$pdemo = $patientdemo->population();
			$patient['static'] = array_merge($patient['static'], $pdemo['static']);			
		}
		if($patientAddresss){			
			$paddress = $patientAddresss->population();
			$patient['static'] = array_merge($patient['static'], $paddress['static']);			
		}
		if($patientInsurance){			
			$patientInsurance = $patientInsurance->population();
			$patient['static'] = array_merge($patient['static'], $patientInsurance['static']);			
		}
		if($patientPoa){			
			$patientPoa = $patientPoa->population();
			$patient['static'] = array_merge($patient['static'], $patientPoa['static']);			
		}
		if($patientProvider){			
			$patientProvider = $patientProvider->population();
			$patient['static'] = array_merge($patient['static'], $patientProvider['static']);			
		}
		if($patientContactTime){			
			$patientContactTime = $patientContactTime->population();
			$patient['static'] = array_merge($patient['static'], $patientContactTime['static']);			
		}
        $result['edit_patient_registration_form'] = $patient;
        return $result;
    }

	
    public function patientRegisteration($id){
        $patient = Patients::where('id',$id)->get();
        $patientAddress = PatientAddress::where('patient_id',$id)->get();
        $patientDemographics = PatientDemographics::where('patient_id',$id)->get();
        $patientInsurance = PatientInsurance::where('patient_id',$id)->get();
        $patientPoa = PatientPoa::where('patient_id',$id)->get();
        $patientProvider = PatientProvider::where('patient_id',$id)->get();
        $patientContactTime = PatientContactTime::where('patient_id',$id)->get();
        $patientServices = PatientServices::where('patient_id',$id)->orderBy('id', 'DESC')->get();
        $services = Module::where('patients_service',1)->get();
        // Log::info($patient);
        return view('Patients::patient.edit-patient-registration',compact('patient','patientAddress', 'patientDemographics', 'patientInsurance', 'patientPoa', 'patientProvider', 'patientContactTime', 'patientServices', 'services'));
    }     

    public function patientContactTime($id){
        $patientContactTime = PatientContactTime::where('patient_id',$id)->get();
        return $patientContactTime;
    }     
}
