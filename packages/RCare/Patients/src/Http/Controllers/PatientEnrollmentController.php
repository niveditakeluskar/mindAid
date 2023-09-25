<?php
namespace RCare\Patients\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientAddress;
use RCare\Patients\Models\PatientDemographics;
use RCare\Org\OrgPackages\QCTemplates\src\Models\ContentTemplate;
use RCare\Patients\Models\ContentTemplateUsageHistory;
use RCare\Patients\Models\QuestionnaireTemplateUsageHistory;
use RCare\Patients\Models\PatientEnrollment;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientDevices;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\PatientContactTime;
use RCare\Patients\Models\PatientPartResearchStudy;
use RCare\Patients\Models\PatientPersonalNotes;
use RCare\Patients\Models\PatientThreshold;
use RCare\Patients\Models\PatientFinNumber;
use RCare\TaskManagement\Models\ToDoList;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use Illuminate\Http\Request;
use RCare\Patients\Http\Requests\TextAddRequest;
use RCare\Patients\Http\Requests\EmailAddRequest;
use RCare\Patients\Http\Requests\CallAddRequest;
use RCare\Patients\Http\Requests\EnrollmentStatusAddRequest;
use RCare\Patients\Http\Requests\EnrollmentServicesAddRequest;
use RCare\Patients\Http\Requests\CallStep2AddRequest;
use RCare\Patients\Http\Requests\CallSatusFinalAddRequest;
use RCare\Patients\Http\Requests\CallFinalisedChecklistRequest;
use RCare\Patients\Models\PatientActiveDeactiveHistory;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
use Illuminate\Support\Facades\DB;
use DataTables;
use Carbon\Carbon;
use RCare\Org\OrgPackages\Users\src\Models\Users;     

class PatientEnrollmentController extends Controller
{
    //all function sanitized by ashvini (15dec2020)
    public function listPatientEnrollmentPatients(Request $request) {
		if ($request->ajax()) {
            $module_id = getPageModuleName();
            $active_modules = DB::table('ren_core.modules')
                            ->where('patients_service', '=', 1)
							->where('status', '=', 1)
                            ->get();
             $data = Patients::with('patientServices', 'patientServices.module')
                     ->orderby('patient.id', 'DESC')->get();
                     // dd($data);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($active_modules){
                $btn='';
                $modules = "<select name='module' onchange='enrollModule(".$row->id.", this.value)'><option>select service</option>";
                foreach($active_modules as $key=>$value){          
                    $modules .= "<option value='".$value->id."'>".$value->module."</option>";
                }
                $modules .= "</select>";
                $btn .= $modules;              
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Patients::patient-enrollment.patient-list');
    }

    public function listPatientEnrollmentPatientsSearch(Request $request) {
        $patient_id   = sanitizeVariable($request->route('id'));
        if ($request->ajax()) {
            $module_id = getPageModuleName();
            
            $active_modules = DB::table('ren_core.modules')
                            ->where('patients_service', '=', 1)
							->where('status', '=', 1)
                            ->get();
                          
             $data = Patients::with('patientServices', 'patientServices.module')
                     ->where('patient.id', '=', $patient_id)
                     ->orderby('patient.id', 'DESC')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) use ($active_modules){
               $btn='';
               // $btn ='<a href="/patients/patient-enrollment/'.$row->id.'" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
               //$row->module;
			   $active_modules1 = DB::table('ren_core.modules')
                            ->where('patients_service', '=', 1)
                            ->where('status', '=', 1)
                            ->whereNotIn('id', DB::table('patients.patient_services')
                            ->where('patient_id', $row->id)
                            ->where('status','!=',2)
                            ->pluck('module_id'))
                            ->get();
							
                $modules = "<select name='module' class='newtimeslots' onchange='enrollModule(".$row->id.", this.value)'><option>select service</option>";
                foreach($active_modules1 as $key=>$value){
                    //echo $value->id;
                    //echo $value->module;                  
                    $modules .= "<option value='".$value->id."'>".$value->module."</option>";
                }
                $modules .= "</select>";
                $btn .= $modules;              
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Patients::patient-enrollment.patient-list');
    }

    public function fetchPatientEnrollmentPatientDetails(Request $request) {
     
        $patient_id   = sanitizeVariable($request->route('id'));       
        $uid   = sanitizeVariable($request->route('id'));
        $enroll_module_id   = sanitizeVariable($request->route('module_id'));
        $module_id    = getPageModuleName();
        $component_id = getPageSubModuleName();

        $cid = session()->get('userid');
        $usersdetails = Users::where('id',$cid)->get();
        $roleid = $usersdetails[0]->role; 

        $getpractice_id = PatientProvider::where('patient_id',$patient_id)->where('is_active',1)->first();
        // dd($getpractice_id->practice_id);

        // $fin_number = sanitizeVariable($request->fin_number);
        // dd($fin_number);
      
        if($enroll_module_id !=0 && $enroll_module_id !='' && is_numeric($enroll_module_id) ){
            $enrollServiceNameQ = Module::where('id',$enroll_module_id)->get('module');
            $enrollServiceName = $enrollServiceNameQ[0]->module;
            $stage_id = getFormStageId($module_id , $component_id, $enrollServiceName);
            $step_id = getFormStepId($module_id, $component_id, $stage_id, $step_name = 'Introduction');
            
            $contentTemplatedata = ContentTemplate::where('module_id',$module_id)->where('component_id',$component_id)->where('stage_id',$stage_id)->where('stage_code',$step_id)->where('status', 1)->get()->last();

            
            $introductionScript =($contentTemplatedata ?$contentTemplatedata->content :'');
            $introductionScriptId = ($contentTemplatedata ?$contentTemplatedata->id :'');
           
            $patient =  Patients::with('patientServices', 'patientServices.module')->where('id',$patient_id)->get();
            $patient_providers = PatientProvider::where('patient_id', $patient_id) 
                               ->with(['practice','provider','users'])->where('provider_type_id',1)->orderby('id','desc')->first(); 
 
           // $patient_enroll_date = PatientServices::latest_module($patient_id, $module_id);
            $last_time_spend = CommonFunctionController::getNetTimeBasedOnModule($patient_id, $module_id);
            $fin_number = Patients::where('id',$patient_id)->first();


            //$services = Module::where('patients_service',1)->where('status',1)->get();
          
            //$patient_demographics = PatientDemographics::where('patient_id', $patient_id)->latest()->first();
            // $PatientAddress = PatientAddress::where('patient_id', $patient_id)->latest()->first();
            // $personal_notes = (PatientPersonalNotes::latest($patient_id,'patient_id') ? PatientPersonalNotes::latest($patient_id,'patient_id')->population() : "");
            // $research_study = (PatientPartResearchStudy::latest($patient_id,'patient_id') ? PatientPartResearchStudy::latest($patient_id,'patient_id')->population() : "");
            // $patient_threshold = (PatientThreshold::latest($uid,'patient_id') ? PatientThreshold::latest($uid,'patient_id')->population() : "");
            // $PatientDevices = PatientDevices::where('patient_id', $patient_id)->orderby('id','desc')->get(); 
            return view('Patients::patient-enrollment.patient-details',['patient'=>$patient],compact('uid','roleid','last_time_spend',
                //'services','patient_enroll_date','PatientAddress', 'patient_demographics',
                'patient_providers','introductionScript','introductionScriptId', 'enroll_module_id','enrollServiceName','fin_number'
                //'personal_notes', 'research_study','patient_threshold',PatientDevices'
                ));
        }   
    }  

    public function saveText(TextAddRequest $request) {
        $contact_via        = "Text";
        $patient_id         = sanitizeVariable($request->patient_id);
        $uid                = sanitizeVariable($request->uid);
        $module_id          = sanitizeVariable($request->module_id);
        $component_id       = sanitizeVariable($request->component_id);
        $template_type_id   = sanitizeVariable($request->template_type_id);
        $template_id        = sanitizeVariable($request->template);
        $stage_id           = sanitizeVariable($request->stage_id);
        $step_id            = sanitizeVariable($request->step_id);
        $form_name          = sanitizeVariable($request->form_name);
        $message            = sanitizeVariable($request->message);
        $content_title      = sanitizeVariable($request->content_title);
        $contact_no         = sanitizeVariable($request->contact_no);
        $start_time         = sanitizeVariable($request->start_time);
        $end_time           = sanitizeVariable($request->end_time);
        $billable           = 1;
        DB::beginTransaction();
        try {
            $template = array(
                'content'            => $message, 
                'content_title'      => $content_title,
                'phone_no'           => $contact_no
            );

            $contenthistory = array(
                'contact_via'   => $contact_via,
                'patient_id'    => $patient_id,
                'uid'           => $uid,
                'template_id'   => $template_id,
                'module_id'     => $module_id,
                'component_id'  => $component_id,
                'template_type' => $template_type_id,
                'template'      => json_encode($template),
                'stage_id'      => $stage_id,
                'created_by'    => session()->get('userid')
            );
            $insert_content = ContentTemplateUsageHistory::create($contenthistory);

            $history_id      = $insert_content->id;
            $action_template = array('template_id' => $template_id, 'history_id' => $history_id);
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name);

            $data = array(
                        
                            'patient_id'              => $patient_id,
                            'uid'                     => $uid,
                            'action'                  => $contact_via,
                            'action_template'         => json_encode($action_template),
                            'module_id'               => $module_id,
                            'component_id'            => $component_id,
                            'stage_id'                => $stage_id,
                            'created_by'              => session()->get('userid')
                        );
            $enrollment    = PatientEnrollment::create($data);
            DB::commit();
        } catch(\Exception $ex) {
            DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function saveEmail(EmailAddRequest $request) {
        $contact_via        = "Email";
        $patient_id         = sanitizeVariable($request->patient_id);
        $uid                = sanitizeVariable($request->uid);
        $module_id          = sanitizeVariable($request->module_id);
        $component_id       = sanitizeVariable($request->component_id);
        $template_type_id   = sanitizeVariable($request->template_type_id);
        $template_id        = sanitizeVariable($request->email_template);
        $stage_id           = sanitizeVariable($request->stage_id);
        $step_id            = sanitizeVariable($request->step_id);
        $form_name          = sanitizeVariable($request->form_name);
        $message            = sanitizeVariable($request->message);
        $content_title      = sanitizeVariable($request->content_title);
        $contact_email      = sanitizeVariable($request->contact_email);
        $subject            = sanitizeVariable($request->subject);
        //record time
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $billable     = 1;

        DB::beginTransaction();
        try {
            $template = array(
                'content'            => $message, 
                'content_title'      => $content_title,
                'email_to'           => $contact_email,
                'email_subject'      => $subject
            );

            $contenthistory = array(
                'contact_via'   => $contact_via,
                'patient_id'    => $patient_id,
                'uid'           => $uid,
                'template_id'   => $template_id,
                'module_id'     => $module_id,
                'component_id'  => $component_id,
                'template_type' => $template_type_id,
                'template'      => json_encode($template),
                'stage_id'      => $stage_id,
                'created_by'    => session()->get('userid')
            );            
            $insert_content = ContentTemplateUsageHistory::create($contenthistory);

            $history_id      = $insert_content->patient_id;
            $action_template = array('template_id' => $template_id, 'history_id' => $history_id);
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name);

            $data = array(
                    // 'practice_id'             => $practice_id, 
                    'patient_id'              => $patient_id,
                    'uid'                     => $uid,
                    'action'                  => $contact_via,
                    'action_template'         => json_encode($action_template),
                    'module_id'               => $module_id,
                    'component_id'            => $component_id,
                    'stage_id'                => $stage_id,
                    'created_by'              => session()->get('userid')
                );
            PatientEnrollment::create($data);
            DB::commit();
        } catch(\Exception $ex) {
            DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function saveCallSatusFinal(CallSatusFinalAddRequest $request) {  //step4
        $enroll_id           = sanitizeVariable($request->enrollment_id);
        $patient_id          = sanitizeVariable($request->patient_id);
        $enroll_status       = sanitizeVariable($request->enroll_status);
        //record time
        $start_time          = sanitizeVariable($request->start_time);
        $end_time            = sanitizeVariable($request->end_time);
        $module_id           = sanitizeVariable($request->module_id);
        $component_id        = sanitizeVariable($request->component_id);
        if($enroll_status==2){
            $step_id            = sanitizeVariable($request->ask_callback_step_id);
        }
        else{
            $step_id            = sanitizeVariable($request->refused_step_id);
        }
        $stage_id            = sanitizeVariable($request->stage_id);
        $form_name           = sanitizeVariable($request->form_name);
        $enrolled_service_id = sanitizeVariable($request->enrolled_service_id);
        $billable            = 1;
        $time_rec_module  = sanitizeVariable($request->time_rec_module);

        DB::beginTransaction();
        try {
            $data=array(
                'enrollment_response' => $enroll_status,
                'updated_by'          => session()->get('userid')
            );

            $call_back_date = date("Y-m-d H:i:s", strtotime($request->call_back_date));
            $call_back_time = date("H:i:s",strtotime($call_back_date));

            if($enroll_status == 3) {
                $reason                     = sanitizeVariable($request->enrl_refuse_reason);
                $data['enrl_refuse_reason'] = sanitizeVariable($reason);
                $patient_services_data =array(           
                        'patient_id'            => $patient_id, 
                        'suspended_from'        => date('Y-m-d h:i:s'),
                        'status'                => '2',
                        'module_id'             => $enrolled_service_id,
                        'date_enrolled'         => date('Y-m-d H:i:s'),
                        'status_value'          => 'Deactivate',                
                        'deactivation_reason'   => 'Refused to Enroll',
                        'created_by'            => session()->get('userid'),
                        'updated_by'            => session()->get('userid')         
                );
                $activedataInsert   = array(            
                    'patient_id'        => $patient_id,
                    'from_date'         => date('Y-m-d h:i:s'),
                    // 'to_date'           => $todate,
                    // 'permanent'         => $permanently,
                    'module_id'         => $enrolled_service_id, 
                    'comments'          => 'Refused to Enroll',
                    'activation_status' => '2',
                    'created_by'        => session()->get('userid'),
                    'updated_by'        => session()->get('userid')                     
                );    
                $check = PatientServices::where('patient_id',$patient_id)->where('module_id',$enrolled_service_id)->exists();
                    if($check==true){ 
                        $update = PatientServices::where('patient_id',$patient_id)->where('module_id',$enrolled_service_id)->update($patient_services_data);            
                        // PatientActiveDeactiveHistory::create($activedataInsert);
                    }else{
                        $insert = PatientServices::create($patient_services_data); 
                        PatientActiveDeactiveHistory::create($activedataInsert); 
                    }          
            } elseif($enroll_status == 2) { 
                $data['callback_date'] = $call_back_date;
            

                $to_do = array(
                    'uid'                         => $patient_id,
                    'module_id'                   => $module_id,
                    'component_id'                => $component_id,
                    'stage_id'                    => $stage_id,
                    'step_id'                     => $step_id,
                    'task_notes'                  => "Enrollment call back",
                    'task_date'                   => $call_back_date,
                    'task_time'                   => $call_back_time,
                    'assigned_to'                 => session()->get('userid'),
                    'assigned_on'                 => Carbon::now(),
                    'status'                      => 'Pending',
                    'status_flag'                 => 0, 
                    'created_by'                  => session()->get('userid'),
                    'patient_id'                  => $patient_id,
                    'enrolled_service_id'         => $enrolled_service_id
                );
                // dd($to_do);
                $insert = ToDoList::create($to_do);
            }
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $time_rec_module, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name);
            $enrollment = PatientEnrollment::where('id',$enroll_id)->update($data);
            DB::commit();
        } catch(\Exception $ex) {
            DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }
    
    public function saveCallSatus(CallAddRequest $request) { // step1
        // dd($request->all());
        $patient_id                  = sanitizeVariable($request->patient_id);
        $uid                         = sanitizeVariable($request->uid);
        $contact_via                 = "Call";
        $call_status                 = sanitizeVariable($request->call_status);
        $template_type_id            = sanitizeVariable($request->template_type_id);
        $call_continue_status        = sanitizeVariable($request->call_continue_status);
        $call_continue_followup_date = sanitizeVariable($request->call_continue_followup_date);
        $voice_mail                  = sanitizeVariable($request->voice_mail);
        $call_followup_date          = sanitizeVariable($request->call_followup_date);
        $type                        = "content";
        $content_title               = sanitizeVariable($request->content_title);
        $enrolled_service_id         = sanitizeVariable($request->enrolled_service_id);
        $start_time                  = sanitizeVariable($request->start_time);
        $end_time                    = sanitizeVariable($request->end_time);
        $module_id                   = sanitizeVariable($request->module_id);
        $component_id                = sanitizeVariable($request->component_id);
        $stage_id                    = sanitizeVariable($request->stage_id);
        $step_id                     = sanitizeVariable($request->step_id);
        $form_name                   = sanitizeVariable($request->form_name);
        $billable                    = 1;
        $fin_number                  = sanitizeVariable($request->fin_number);
        $time_rec_module             = sanitizeVariable($request->time_rec_module);
		$currentMonth                  = date('m');
        $currentYear                   = date('Y');
        // dd($fin_number);

        DB::beginTransaction();
        try {
            if ($call_status == '1') {
                $template_id                  = sanitizeVariable($request->call_answer_template_id);
                $content                      = sanitizeVariable($request->call_answer_template);
                $phone_no                     = "";
            } else if ($call_status == '2') {
                $template_id                  = sanitizeVariable($request->call_not_answer_template_id);
                $content                      = sanitizeVariable($request->text_msg);
                $phone_no                     = sanitizeVariable($request->phone_no);
            }

            $template = array(
                'content'            => $content, 
                'content_title'      => $content_title,
                'phone_no'           => $phone_no
            );

            $contenthistory = array(
                'contact_via'   => $contact_via,
                'patient_id'    => $patient_id,
                'uid'           => $uid,
                'template_id'   => $template_id,
                'module_id'     => $module_id,
                'component_id'  => $component_id,
                'template_type' => $template_type_id,
                'template'      => json_encode($template),
                'stage_id'      => $stage_id,
                'created_by'    => session()->get('userid')
            );
            $insert_content = ContentTemplateUsageHistory::create($contenthistory);

            $history_id      = $insert_content->id;
            $action_template = array('template_id' => $template_id, 'history_id' => $history_id);

            $data = array(
                // 'practice_id'             => $practice_id, 
                'patient_id'              => $patient_id,
                'call_status'             => $call_status,
                'voice_mail'              => $voice_mail,
                'call_continue_status'    => $call_continue_status,
                'uid'                     => $uid,
                'action'                  => $contact_via,
                'action_template'         => json_encode($action_template),
                'module_id'               => $module_id,
                'component_id'            => $component_id,
                'stage_id'                => $stage_id,
                'enrolled_service_id'     => $enrolled_service_id,
                'created_by'              => session()->get('userid')
            );
			if($fin_number != null || $fin_number != ''){
            $updatepatient = Patients::where('id',$patient_id)->update(['fin_number'=>$fin_number]);
			$patientfin = array(
                    'patient_id'    => $patient_id, 
                    'status'        => '1',
                    'fin_number'    => $fin_number
            );

            $check_exist_for_month         = PatientFinNumber::where('patient_id', $patient_id)->whereMonth('updated_at', $currentMonth)->whereYear('updated_at', $currentYear)->exists();

            if ($check_exist_for_month == true) {
                $patientfin['updated_at']= Carbon::now();
                $patientfin['updated_by']= session()->get('userid');
                $update_query = PatientFinNumber::where('patient_id', $patient_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->orderBy('id', 'desc')->first()->update($patientfin);
            } else {
                $patientfin['created_at']= Carbon::now();
                $patientfin['created_by']= session()->get('userid');
                $insert_query = PatientFinNumber::create($patientfin);
            }
			}
			
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $time_rec_module, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name);

            $enrollment = PatientEnrollment::create($data);
            $enrollment_id = $enrollment->id;
            DB::commit();
            return $enrollment_id;
        } 
        catch(\Exception $ex) {
            DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }  
    }

    function saveEnrollmentStatus(EnrollmentStatusAddRequest $request) {
        $patient_id                    = sanitizeVariable($request->patient_id);
        $uid                           = sanitizeVariable($request->uid);
        $contact_via                   = "Call";
        $enroll_status                  = sanitizeVariable($request->enroll_status);
        $type                          = "content";
        $template_type_id              = sanitizeVariable($request->template_type_id);
        $content_title                 = sanitizeVariable($request->content_title);
        $template_id                   = sanitizeVariable($request->enrollment_status_template_id);
        $enrollment_status_script      = sanitizeVariable($request->enrollment_status_script);
        $call_back_date                = sanitizeVariable($request->call_back_date);
        $enrollment_id                 = sanitizeVariable($request->enrollment_id);

        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $module_id    = sanitizeVariable($request->module_id);
        $component_id = sanitizeVariable($request->component_id);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = sanitizeVariable($request->step_id);
        $form_name    = ""; //sanitizeVariable($request->form_name);
        $billable     = 1;

        DB::beginTransaction();
        try {
            $template = array(
                'content'            => $enrollment_status_script, 
                'content_title'      => $content_title,
                'phone_no'           => ""
            );

            $contenthistory = array(
                'contact_via'   => $contact_via,
                'patient_id'    => $patient_id,
                'uid'           => $uid,
                'template_id'   => $template_id,
                'module_id'     => $module_id,
                'component_id'  => $component_id,
                'template_type' => $template_type_id,
                'template'      => json_encode($template),
                'stage_id'      => $stage_id,
                'created_by'    => session()->get('userid')
            );
            $insert_content = ContentTemplateUsageHistory::create($contenthistory);

            $history_id      = $insert_content->id;
            $action_template = array('template_id' => $template_id, 'history_id' => $history_id);

            $data = array(
                // 'practice_id'             => $practice_id, 
                'patient_id'              => $patient_id,
                'uid'                     => $uid,
                'enrollment_response'     => $enroll_status,
                'action'                  => $contact_via,
                'action_template'         => json_encode($action_template),
                'module_id'               => $module_id,
                'component_id'            => $component_id,
                'stage_id'                => $stage_id,
                'callback_date'           => $call_back_date,
            // 'callback_time'           => $call_back_time
            );
            if(isset($enrollment_id) && ( $enrollment_id !="") && ($enrollment_id != null)) {
                $data['updated_by'] = session()->get('userid');
                $insert_query = PatientEnrollment::where('id', $enrollment_id)->update($data);
            } else {
                $data['created_by'] = session()->get('userid');
                $insert_query = PatientEnrollment::create($data);
            }
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name);

            if(isset($call_back_date) && ($call_back_date != "") && ($call_back_date != null)) {
                $to_do = array(
                    'uid'                         => $uid,
                    'module_id'                   => $module_id,
                    'component_id'                => $component_id,
                    'stage_id'                    => $stage_id,
                    'step_id'                     => $step_id,
                    'task_notes'                  => "Enrollment call back",
                    'task_date'                   => $call_back_date,
                //  'task_time'                   => $call_back_time,
                    'assigned_to'                 => session()->get('userid'),
                    'assigned_on'                 => Carbon::now(),
                    'status'                      => 'Pending',
                    'status_flag'                 => 0,
                    'created_by'                  => session()->get('userid'),
                    'patient_id'                  => $patient_id
                );
                $insert = ToDoList::create($to_do);
            }
            DB::commit();
        } catch(\Exception $ex) {
            DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    function saveEnrollServices(EnrollmentServicesAddRequest $request) {
        $patient_id                    = sanitizeVariable($request->patient_id);
        $uid                           = sanitizeVariable($request->uid);
        $contact_via                   = "Call";
        $enroll_status                  = sanitizeVariable($request->enroll_status);
        $type                          = "content";
        $template_type_id              = sanitizeVariable($request->template_type_id);
        $content_title                 = sanitizeVariable($request->content_title);
        $template_id                   = sanitizeVariable($request->enrollment_status_template_id);
        $enrollment_status_script      = sanitizeVariable($request->enrollment_status_script);

        //record time
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $module_id    = sanitizeVariable($request->module_id);
        $component_id = sanitizeVariable($request->component_id);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = sanitizeVariable($request->step_id);
        $form_name    = ""; // sanitizeVariable($request->form_name);
        $billable     = 1;

        DB::beginTransaction();
        try {
            $enrollarr = sanitizeVariable($request->enroll);
            $j = sizeof(sanitizeVariable($request->enroll));
            $patient_id = sanitizeVariable($request->patient_id);
            foreach($enrollarr as $key =>$value){
                if($value){
                    $patient_enroll_data = array(
                        'uid'               => $uid,
                        'module_id'         =>  $key,
                        'date_enrolled'     => Carbon::now(),
                        'created_by'        => session()->get('userid')
                    );
                    $patientServiceExist = PatientServices::where('patient_id',$patient_id)->where('module_id',$key)->where('status',1)->get();
                    if($patientServiceExist->count()==0){
                        $patient_enroll_data['patient_id'] = $request->patient_id;  
                        $patient_enroll_data['created_by'] = session()->get('userid');
                        $patient_enroll = PatientServices::create($patient_enroll_data);
                        continue;
                    }
                } else {
                    $patientServiceExist = PatientServices::where('patient_id',$request->patient_id)->where('module_id',$key)->get();
                    if($patientServiceExist){
                        $patient_enroll_data = array(
                            'status'               => 0,
                            'updated_by' => session()->get('userid')
                        );
                        $patientServiceExist = PatientServices::where('patient_id',$request->patient_id)->where('module_id',$key)->update($patient_enroll_data);
                        continue;
                    }            
                }
            }
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name);
            DB::commit();
        } catch(\Exception $ex) {
            DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    function saveChecklist(CallStep2AddRequest $request) {  //step 2
        $patient_id                    = sanitizeVariable($request->patient_id);
        $uid                           = sanitizeVariable($request->uid);
        $contact_via                   = "Call";
        $type                          = "Questionnaire";
        $template_id                   = sanitizeVariable($request->template_id);
        $template_type_id              = sanitizeVariable($request->template_type_id);
        $content_title                 = sanitizeVariable($request->content_title);
        $question                      = sanitizeVariable($request->question);
        $enrollment_id                 = sanitizeVariable($request->enrollment_id);
        $enroll_status                 = sanitizeVariable($request->patient_reviews_and_signs);
        $start_time                    = sanitizeVariable($request->start_time);
        $end_time                      = sanitizeVariable($request->end_time); 
        $module_id                     = sanitizeVariable($request->module_id);
        $component_id                  = sanitizeVariable($request->component_id);
        $stage_id                      = sanitizeVariable($request->stage_id);
        $step_id                       = sanitizeVariable($request->step_id);
        $form_name                     = sanitizeVariable($request->form_name);
        $billable                      = 1;
        $time_rec_module             = sanitizeVariable($request->time_rec_module);

        DB::beginTransaction();
        try {
            $template = array(
                'content'            => $question, 
                'content_title'      => $content_title,
                'phone_no'           => ""
            );

            $questionnairehistory = array(
                'contact_via'   => $contact_via,
                'patient_id'    => $patient_id,
                'uid'           => $uid,
                'template_id'   => $template_id,
                'module_id'     => $module_id,
                'component_id'  => $component_id,
                'template_type' => $template_type_id,
                'template'      => json_encode($template),
                'stage_id'      => $stage_id,
                'created_by'    => session()->get('userid')
            );

            //    dd($questionnairehistory);
            $insert_content = QuestionnaireTemplateUsageHistory::create($questionnairehistory);
        
            $history_id      = $insert_content->id;
            $action_template = array('template_id' => $template_id, 'history_id' => $history_id);

            $data = array(
                // 'practice_id'             => $practice_id, 
                'patient_id'              => $patient_id,
                'uid'                     => $uid,
                'action'                  => $contact_via,
                'enrollment_checklist'    => json_encode($action_template),
                'module_id'               => $module_id,
                'component_id'            => $component_id,
                'stage_id'                => $stage_id,
                'enrollment_response'     => $enroll_status
                // 'created_by'              => session()->get('userid')
            );
            if(isset($enrollment_id) && ( $enrollment_id !="") && ($enrollment_id != null)) {
                $data['updated_by'] = session()->get('userid');
                $insert_query = PatientEnrollment::where('id', $enrollment_id)->update($data);
            } else {
                $data['created_by'] = session()->get('userid');
                $insert_query = PatientEnrollment::create($data);
            }
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $time_rec_module, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name);
            DB::commit();
            return $history_id;
        } catch(\Exception $ex) {
            DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    function saveFinalisedChecklist(CallFinalisedChecklistRequest $request) { // step3
        $patient_id             = sanitizeVariable($request->patient_id);
        $uid                    = sanitizeVariable($request->uid);
        $address                = sanitizeVariable($request->address);
        $cell_phone             = sanitizeVariable($request->cell_phone);
        $mob                    = sanitizeVariable($request->mob);
        $is_this_cell_phone     = sanitizeVariable($request->is_this_cell_phone);
        $enroll_id              = sanitizeVariable($request->enroll_id);

        DB::beginTransaction();
        try {
            if($is_this_cell_phone == 'Yes') { 
                if(isset($mob) && ( $mob !="") && ($mob != null)) {
                    $data['mob'] = $request->mob;
                }
            } else {
                if(isset($cell_phone) && ( $cell_phone !="") && ($cell_phone != null)) {
                    $data['mob'] = $cell_phone;
                }

                if(isset($mob) && ( $mob !="") && ($mob != null)) {
                    $data['home_number'] = $mob;
                }
            }
            $data['updated_by'] = session()->get('userid');
            $insert_query = Patients::where('id', $patient_id)->update($data);

            if(isset($address) && ( $address !="") && ($address != null)) {
                $add_data['add_1'] = $address;
                $add_data['updated_by'] = session()->get('userid');
                $insert_query = PatientAddress::where('patient_id', $patient_id)->update($add_data);
            }

            $mon_0                 = sanitizeVariable($request->mon_0);
            $mon_1                 = sanitizeVariable($request->mon_1);
            $mon_2                 = sanitizeVariable($request->mon_2);
            $mon_3                 = sanitizeVariable($request->mon_3);
            $mon_any               = sanitizeVariable($request->mon_any);
            $tue_0                 = sanitizeVariable($request->tue_0);
            $tue_1                 = sanitizeVariable($request->tue_1);
            $tue_2                 = sanitizeVariable($request->tue_2);
            $tue_3                 = sanitizeVariable($request->tue_3);
            $tue_any               = sanitizeVariable($request->tue_any);
            $wed_0                 = sanitizeVariable($request->wed_0);
            $wed_1                 = sanitizeVariable($request->wed_1);
            $wed_2                 = sanitizeVariable($request->wed_2);
            $wed_3                 = sanitizeVariable($request->wed_3);
            $wed_any               = sanitizeVariable($request->wed_any);
            $thu_0                 = sanitizeVariable($request->thu_0);
            $thu_1                 = sanitizeVariable($request->thu_1); 
            $thu_2                 = sanitizeVariable($request->thu_2) ;
            $thu_3                 = sanitizeVariable($request->thu_3) ;
            $thu_any               = sanitizeVariable($request->thu_any) ;
            $fri_0                 = sanitizeVariable($request->fri_0 );
            $fri_1                 = sanitizeVariable($request->fri_1);
            $fri_2                 = sanitizeVariable($request->fri_2);
            $fri_3                 = sanitizeVariable($request->fri_3);
            $fri_any               = sanitizeVariable($request->fri_any);

            $patient_contact_time_data = array( 
                'mon_0'                 => $mon_0,
                'mon_1'                 => $mon_1,
                'mon_2'                 => $mon_2,
                'mon_3'                 => $mon_3,
                'mon_any'               => $mon_any,
                'tue_0'                 => $tue_0,
                'tue_1'                 => $tue_1,
                'tue_2'                 => $tue_2,
                'tue_3'                 => $tue_3,
                'tue_any'               => $tue_any,
                'wed_0'                 => $wed_0,
                'wed_1'                 => $wed_1,
                'wed_2'                 => $wed_2,
                'wed_3'                 => $wed_3,
                'wed_any'               => $wed_any,
                'thu_0'                 => $thu_0,
                'thu_1'                 => $thu_1,
                'thu_2'                 => $thu_2,
                'thu_3'                 => $thu_3,
                'thu_any'               => $thu_any,
                'fri_0'                 => $fri_0,
                'fri_1'                 => $fri_1,
                'fri_2'                 => $fri_2,
                'fri_3'                 => $fri_3,
                'fri_any'               => $fri_any,
                'uid'                   => $uid,
                'patient_id'            => $patient_id,
                'created_by'            => session()->get('userid')
            );   

            $check_exist_for_month  = PatientContactTime::where('patient_id', $patient_id)->exists();
            // dd( $check_exist_for_month); 
            if ($check_exist_for_month == true) {
                $patient_contact_time_data['updated_by'] = session()->get('userid');
                PatientContactTime::where('patient_id',$patient_id)->update($patient_contact_time_data);
            } else {
                $patient_contact_time_data['created_by'] = session()->get('userid');
                PatientContactTime::create($patient_contact_time_data);
            }
            
            //add patient service

            $patient_enroll_data = array(
                'patient_id'        => $patient_id,
                'uid'               => $uid,
                'module_id'         => $enroll_id,
                'date_enrolled'     => Carbon::now(),
                'status'            => 1,
                'status_value'      => 'Active',
                'created_by'        => session()->get('userid') 
            );
        // dd($patient_enroll_data);  
            $activedataInsert   = array(            
                'patient_id'        => $patient_id,
                'from_date'         => Carbon::now(),
                'module_id'         => $enroll_id, 
                'activation_status' => 1,
                'created_by'        => session()->get('userid'),
                'updated_by'        => session()->get('userid')                     
            );
            $patientActiveServiceExist = PatientServices::where('patient_id',$patient_id)
            ->where('module_id',$enroll_id)->where('status',1)->get();
            // dd($patientActiveServiceExist->count()); 
            if($patientActiveServiceExist->count()==0){ 
                $check_exist = PatientServices::where('patient_id',$patient_id)->where('module_id',$enroll_id)->exists();
                if($check_exist ==true){  
                    $patient_enroll_data['updated_by'] = session()->get('userid');
                    $patient_enroll = PatientServices::where('patient_id',$patient_id)
                    ->where('module_id',$enroll_id)->update($patient_enroll_data);
                    $PatientActiveDeactiveHistory = PatientActiveDeactiveHistory::create($activedataInsert);
                    $patientServiceCount = PatientServices::where('patient_id',$patient_id)->whereIn('status',[0, 1])->count();
                    $patient_data['service_count']= $patientServiceCount;
                    $master_patient = Patients::where('id',$patient_id)->update($patient_data);
                }else{
                    $patient_enroll = PatientServices::create($patient_enroll_data);
                    $PatientActiveDeactiveHistory = PatientActiveDeactiveHistory::create($activedataInsert);
                    $patientServiceCount = PatientServices::where('patient_id',$patient_id)->whereIn('status',[0, 1])->count();
                    $patient_data['service_count']= $patientServiceCount;
                    $master_patient = Patients::where('id',$request->patient_id)->update($patient_data);
                } 
            }
                    
            //record time
            $start_time   = sanitizeVariable($request->start_time);
            $end_time     = sanitizeVariable($request->end_time);
            $module_id    = sanitizeVariable($request->module_id);
            $time_rec_module  = sanitizeVariable($request->time_rec_module);
            $component_id = sanitizeVariable($request->component_id);
            $stage_id     = sanitizeVariable($request->stage_id);
            $step_id      = sanitizeVariable($request->step_id);
            $form_name    = sanitizeVariable($request->form_name);
            $billable     = 1; 

            // $insert_query = PatientEnrollment::create($data);
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $time_rec_module, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name);
            if($module_id == 2){
                $ccmSubModule = ModuleComponents::where('components',"Monthly Monitoring")->where('module_id',$module_id)->where('status',1)->get('id');
                $SID          = getFormStageId($module_id, $ccmSubModule[0]->id, 'Enroll In RPM');
                $enroll_msg = CommonFunctionController::sentSchedulMessage($module_id,$patient_id,$SID);
               }
        
            DB::commit();
        } catch(\Exception $ex) {
            DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    function saveChecklistStatus(Request $request) {
        $patient_id                    = sanitizeVariable($request->patient_id);
        $uid                           = sanitizeVariable($request->uid);
        $contact_via                   = "Call";
        $type                          = "Questionnaire";
        $template_id                   = sanitizeVariable($request->template_id);
        $template_type_id              = sanitizeVariable($request->template_type_id);
        $content_title                 = sanitizeVariable($request->content_title);
        $question                      = sanitizeVariable($request->question);
        $fquestion                      = sanitizeVariable($request->fquestion);
        //dd('record time');
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $module_id    = sanitizeVariable($request->module_id);
        $component_id = sanitizeVariable($request->component_id);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = sanitizeVariable($request->step_id);
        $form_name    = sanitizeVariable($request->form_name);
        $billable     = 1;
        DB::beginTransaction();
        try {
            $template = array(
                'content'            => $question, 
                'content_title'      => $content_title,
                'phone_no'           => ""
            );

            $ftemplate = array(
                'content'            => $fquestion, 
                'content_title'      => $content_title,
                'phone_no'           => ""
            );

            QuestionnaireTemplateUsageHistory::where('id',$request->check_hidden)->update(['template' => json_encode($template)]);
            QuestionnaireTemplateUsageHistory::where('id',$request->final_hidden)->update(['template' => json_encode($ftemplate)]);

            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name);
            DB::commit();
        } catch(\Exception $ex) {
            DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function populate($patientId) {
        $patientId                   = $patientId;
                
        $checklist_form              = ""; //(CallPreparation::latest($patientId, $month) ? CallPreparation::latest($patientId, $month)->population() : "");
        $finalization_checklist_form = ""; //(CallHipaaVerification::latest($patientId) ? CallHipaaVerification::latest($patientId)->population() : "");
        $checklist_status_form       = ""; //(CallHomeServiceVerification::latest($patientId) ? CallHomeServiceVerification::latest($patientId)->population() : "");
        $final = "";
        $patient = Patients::find($patientId);
        $patientContactTime = PatientContactTime::where('patient_id',$patientId)->first();
        // dd($patientContactTime); 
        
        $patient = $patient->population();

        if(!empty($patientContactTime)){			
            $patientContactTime = $patientContactTime->population();
            if(!empty($final['static'])){ 
              
                $final['static'] = array_merge($final['static'], $patientContactTime['static']);
               	
           }
           else{
            $final = []; 
            $final['static'] = $patientContactTime['static'];   
           }
			
        }

        $result['checklist_form'] = $checklist_form; 
        $result['finalization_checklist_form'] = $final;
        $result['finalization_checklist_form_questionnaire'] = $final;
        $result['checklist_status_form'] = $checklist_status_form;  
        // return $result;
        $result['enroll_services_form'] = $patient;
        return $result; 

    }
}