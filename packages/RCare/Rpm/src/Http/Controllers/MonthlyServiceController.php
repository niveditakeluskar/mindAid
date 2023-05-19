<?php
namespace RCare\Rpm\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use RCare\System\Http\Controllers\CommonFunctionController;
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
use RCare\Patients\Models\PatientPersonalNotes;
use RCare\Patients\Models\PatientPartResearchStudy;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientAddress; 
use RCare\Patients\Models\PatientMedication;
use  RCare\Org\OrgPackages\Medication\src\Models\Medication;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientDevices;
use RCare\Patients\Models\PatientThreshold;
use Hash;
use DB;
use Validator,Redirect,Response;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MonthlyServiceController extends Controller
{
    public function index()
    {
        return view('Rpm::monthly-service.patient-list');
    }

	 

    public function fetchPatientsMonthlyService(Request $request)
    {
        if ($request->ajax()) {
            $module_id = getPageModuleName();
            $submodule_id =getPageSubModuleName();
            // $data = Patients::latest()->get();
            $data = DB::select(DB::raw("SELECT t1.*, ptr1.created_at, ptr1.created_by as created_by_id, ptr1.f_name || ptr1.l_name as created_by FROM 
                (select p.id, p.fname, p.lname, p.mname, p.profile_img, p.dob, p.mob, p.home_number, p.pid from patients.patient p, patients.patient_services ps  
                where p.id = ps.patient_id  and ps.module_id = '".$module_id."') t1
                left JOIN (select r.created_at , r.created_by , u.f_name , u.l_name, r.patient_id , r.id, r.module_id , r.component_id 
                from patients.patient_time_records r, ren_core.users u where r.created_by = u.id ) ptr1
                ON (t1.id = ptr1.patient_id and ptr1.module_id ='".$module_id."' and ptr1.component_id ='".$submodule_id."')
                LEFT OUTER JOIN patients.patient_time_records ptr2
                ON (t1.id = ptr2.patient_id AND ptr1.id < ptr2.id and ptr2.module_id ='".$module_id."' and ptr2.component_id ='".$submodule_id."')
                WHERE ptr2.id IS NULL"));
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="/rpm/monthly-service/'.$row->id.'" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Rpm::monthly-service.patient-list');
    }

 
    public function monthlyService($id, Request $request)
    {   
        
        $id  = sanitizeVariable($id);
        $module_id    = getPageModuleName();
        $questionSet1 = QuestionnaireTemplate::find(62);
        $questionSet2 = QuestionnaireTemplate::find(61);

        $cid = session()->get('userid');
        $usersdetails = Users::where('id',$cid)->get();
        $roleid = $usersdetails[0]->role; 
       // $fetchComponent = ContentTemplate::get();
        $scripts = ContentTemplate::where('id',$id)->where('status', 1)->get();
        $data = ContentTemplate::all();
        $contact_number =Patients::where('patients.patient.id',$id)->select('patients.patient.mob','patients.patient.home_number','patients.patient_demographics.other_contact_phone_number')
                                                    ->join('patients.patient_demographics', 'patients.patient.id', '=', 'patients.patient_demographics.patient_id')
                                                    ->get();
        // dd($contact_number);
        $contact_email= Patients::where('patients.patient.id',$id)->select('patients.patient.email','patients.patient_poa.poa_email','patients.patient_demographics.other_contact_email')
                                                     ->join('patients.patient_demographics', 'patients.patient.id', '=', 'patients.patient_demographics.patient_id')
                                                    ->join('patients.patient_poa', 'patients.patient.id', '=', 'patients.patient_poa.patient_id')
                                                    ->get(); 
        $callScripts = ContentTemplate::where('template_type_id',3)->where('status', 1)->get(); //call script only
        $type = Template::all();
        // $service = RcareServices::all();
        // $subModule =  RcareSubServices::where('services_id',2)->get();
        $service = Module::all();
        $services = Module::where('patients_service',1)->where('status',1)->get();
        $patient =  Patients::with('patientServices', 'patientServices.module')->where('id',$id)->get();
        $subModule = ModuleComponents::all();
        $patient = Patients::where('id',$id)->get();
        //dd($patient[0]->UID);
        // $PatientAddress = PatientAddress::where('uid',$patient[0]->UID);
        $PatientAddress = PatientAddress::where('patient_id', $id)->latest()->first();
        $patient_enroll_date = PatientServices::latest_module($id, $module_id);
        $currentMonth = date('m');
        // $time = PatientTimeRecord::where('UID',$id)->whereMonth('created_at', $currentMonth)->sum('net_time');
        $time = CommonFunctionController::getCcmNetTime($id, $module_id); 
        $last_time_spend = CommonFunctionController::getNetTimeBasedOnModule($id, $module_id);
        $personal_notes = (PatientPersonalNotes::where('patient_id',$id) ? PatientPersonalNotes::where('patient_id',$id)->get() : "");
        $research_study = (PatientPartResearchStudy::latest($id,'patient_id') ? PatientPartResearchStudy::latest($id,'patient_id')->population() : "");
        $patient_threshold = (PatientThreshold::latest($id,'patient_id') ? PatientThreshold::latest($id,'patient_id')->population() : "");
        $patient_providers = PatientProvider::where('patient_id', $id)
                             ->with('practice')->with('provider')->with('users')->where('provider_type_id',1)->orderby('id','desc')->first();  
        $PatientDevices = PatientDevices::where('patient_id',$id)->orderby('id','desc')->get();
        // $time = DB::table('rpm.patient_time_records')->where('UID',$id)->sum('net_time');
        return view('Rpm::monthly-service.monthly-service-steps',['patient'=>$patient], compact('roleid','patient','patient_enroll_date','type','contact_number','contact_email','questionSet1','questionSet2','data','subModule','callScripts', 'scripts','time','PatientAddress','personal_notes','research_study','patient_threshold','patient_providers','last_time_spend','PatientDevices'));
    }
    
    // public function monthlyService2($id)
    // {   
       
    //     $questionSet1 = QuestionnaireTemplate::find(62);
    //     $questionSet2 = QuestionnaireTemplate::find(61);
    //     $scripts = ContentTemplate::where('id',$id)->get();
    //     $data = ContentTemplate::where('id',$id)->get();
    //     $contact_number =Patients::where('id',$id)->select('phone_primary','phone_secondary','other_contact_phone')->get();
    //     $contact_email= Patients::where('id',$id)->select('email','poa_email','other_contact_email')->get(); 
    //     $callScripts = ContentTemplate::where('template_type_id',3)->get(); //call script only
    //     $type = Template::all();
    //     $service = RcareServices::all();
    //     $subModule =  RcareSubServices::where('services_id',2)->get();
    //     $patient = Patients::where('id',$id)->get();
    //     return view('Rpm::monthly-service.monthly-service-steps2',['patient'=>$patient], compact('type','contact_number','contact_email','questionSet1','questionSet2','data','subModule','callScripts', 'scripts'));
    // }

//      public function fetchContent(Request $request){
//         $data =ContentTemplate::all();
//         $content_title = sanitizeVariable($request->content_title)  ;
//         $query =ContentTemplate::where('id',$content_title)->get();
//         return $query;
//    }

   public function fetchComponentid(Request $request){
       $content_title = sanitizeVariable($request->content_title);
       $query =ContentTemplate::where('id',$content_title)->where('status', 1)->get();
        return $query;   
  }

//    public function fetchEmailContent(Request $request){
//     $template_type_id = sanitizeVariable($request->template_type_id);
//     $type = Template::all();
//         $content_name =ContentTemplate::where('template_type_id',$template_type_id)->get();
//         echo '<option value="">Choose Content Title</option>';
//              foreach($content_name as $value){
//          echo '<option value="'.$value->id .'">'.$value->content_title.' </option>';
//         }
//         echo '<option value="0">Custom</option>';

// }

   

    public function saveMonthlyService(MonthlyServicesRequest $request){     
        // dd($request->all()); 
        $reviewData = sanitizeVariable($request->review_data);
        $phone_primary = sanitizeVariable($request->patient_contect_number);
        $uid = sanitizeVariable($request->input('patient_id'));
        $module_id = sanitizeVariable($request->input('module_id'));
        $template_type_id = sanitizeVariable($request->input('template_type_id'));
        $component_id = sanitizeVariable($request->component_id);
        $stage_id = sanitizeVariable($request->stage_id);
        $created_by = session()->get('userid');  
        $practice_id = NULL;
        $voice_mail = 0;
        $withinguideline_left_msg_in_emr=0;      
        $patient_condition=0;
        $call_status=0; 
        $contact_via = sanitizeVariable($request->contact_via);

        $template  = isset($request->text_content_area) ? sanitizeVariable($request->text_content_area) : '';  //forwithinguidelines and text and call
        $tid = isset($request->text_content_title) ? sanitizeVariable($request->text_content_title) : ''; //forwithinguidelines and text and call
        $c = isset($request->text_contact_number) ? sanitizeVariable($request->text_contact_number) : '';
        $cs = isset($request->call_status) ? sanitizeVariable($request->call_status) : '';
        $answer = isset($request->answer) ?  sanitizeVariable($request->answer) : '';
        $san_templateid = isset($request->template_id) ? sanitizeVariable($request->template_id) : '';
        $san_templatetypeid = isset($request->template_type_id) ? sanitizeVariable($request->template_type_id) : '';  
        $san_leave_message_in_emr = isset($request->leave_message_in_emr) ? sanitizeVariable($request->leave_message_in_emr) : '';
        $san_within_guidelines_contact_number = isset($request->within_guidelines_contact_number) ? sanitizeVariable($request->within_guidelines_contact_number) : '';
        $san_patientcondition = isset($request->patient_condition) ? sanitizeVariable($request->patient_condition) : '';
        $san_out_guidelines_contact_number = isset($request->out_guidelines_contact_number) ? sanitizeVariable($request->out_guidelines_contact_number) : '';
        $q = isset($request->out_guidelines_contact_number) ? sanitizeVariable($request->question) : '';   
          

        if($reviewData==1){

            $contact_number = $c ;
            $template_id = $tid;
            $templatetypeid = $san_templatetypeid;

            if($contact_via == 'text') {
                sendTextMessage($c, $template);
                // $call_status=0;
            } else {
                $call_status = $cs ; 
                if($call_status==2) {
                    $voice_mail = $answer;
                   
                }
            }
        }
        if($reviewData==2){
            
            // $template_id =  $san_templateid ;
            // $templatetypeid =  $san_templatetypeid;
            $template_id = $tid; 
            $templatetypeid = $san_templatetypeid;
            $withinguideline_left_msg_in_emr =  $san_leave_message_in_emr;
            $contact_number = $san_within_guidelines_contact_number ;
            sendTextMessage($contact_number, $template);
           
        }
        if($reviewData==3){
          
            $patient_condition =  $san_patientcondition;
            $contact_number = $san_out_guidelines_contact_number;
            $template_id = $san_templateid;
            $templatetypeid = $san_templatetypeid;

            if($patient_condition == 1){ 
                $template = $q;  
            } else if($patient_condition == 2){
                $template = $q; 
            }
        }
        $data = array(
                        'contact_via'   => $contact_via,
                        'uid'           => $uid,
                        'patient_id'    => $uid,
                        'module_id'     => $module_id,
                        'template_id'   => $template_id,
                        'template_type' => $templatetypeid,
                        'component_id'  => $component_id, 
                        'template'      => json_encode($template),
                        'stage_id'      => $stage_id,
                        'created_by'    => $created_by  
                    );

                   
        $history_id = QuestionnaireTemplateUsageHistoryrpm::create($data);
        // dd($history_id); 
        $hid=$history_id->id;

        $action = array(
                            'type'           => $contact_via,
                            'template_id'    =>  $template_id , //'20', 
                            'history_id'     => $history_id->id,
                            'template_type'  => $templatetypeid
                        );
        $action_template = json_encode($action);
        // dd($action_template); 
        if($reviewData==1){
            $not_recorded_action_template = $action_template;
        } else {
            $not_recorded_action_template = null;
        }
        if($reviewData==2){

            $withinguideline_msg_template = $action_template;
            // dd($withinguideline_msg_template);
        } else {
            $withinguideline_msg_template = null; 
        }
        if($reviewData==3){
            $out_of_guideline_patient_condition_template = $action_template;
        } else {
            $out_of_guideline_patient_condition_template = null; 
        }

        $data2 = array (
                            'patient_id'                                  => $uid, 
                            'practice_id'                                 => $practice_id, 
                            'phone_no'                                    => $contact_number,  
                            'uid'                                         => $uid,
                            'not_recorded_action'                         => $contact_via,
                            'not_recorded_action_template'                => $not_recorded_action_template,
                            'call_status'                                 => $call_status,
                            'voice_mail'                                  => $voice_mail,
                            'withinguideline_left_msg_in_emr'             => $withinguideline_left_msg_in_emr,
                            'withinguideline_msg_template'                => $withinguideline_msg_template,
                            'patient_data_status'                         => $reviewData,
                            'out_of_guideline_patient_condition'          => $patient_condition,
                            'out_of_guideline_patient_condition_template' => $out_of_guideline_patient_condition_template,
                            'created_by'                                  => $created_by
                           
                        ); 
        
        $history_id = MonthlyService::create($data2); 
        // dd($data2); 
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $patient_id   = sanitizeVariable($request->patient_id);
        $module_id    = sanitizeVariable($request->module_id);
        $component_id = sanitizeVariable($request->component_id);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = 0;
        $billable     = 1;
        $uid          = sanitizeVariable($request->patient_id); 
        $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable,$uid);
    }

   

    // public function getQuestionnaire(Request $request)
    // {   
    //     $id = sanitizeVariable($request->id);
    //     $questionSet1 = QuestionnaireTemplate::find(62);
    //     $questionSet2 = QuestionnaireTemplate::find(61);
    //     $data = ContentTemplate::where('template_type_id',$id)->get();
    //     $subModule =  RcareSubServices::where('services_id',2)->get();
    //     return view('Rpm::monthly-service.monthlyService', compact('questionSet1','questionSet2','data','subModule'));
    // }

    // public function getCallScripts(Request $request)
    // {   
    //     $callScripts = ContentTemplate::where('template_type_id',3)->get(); //call script only
    //     return $callScripts;
       
    // }

    // public function getCallScriptsById(Request $request)
    // {   
    //     $id = sanitizeVariable($request->id);
    //     $scripts = ContentTemplate::where('id',$id)->get(); //
    //     $callScripts = ContentTemplate::where('template_type_id',3)->get(); //call script only
    //     $questionSet1 = QuestionnaireTemplate::find(62);
    //     $questionSet2 = QuestionnaireTemplate::find(61);
    //     $data = ContentTemplate::all();
    //     $type = Template::all();
    //     $service = RcareServices::all();
    //     $subModule =  RcareSubServices::where('services_id',2)->get();
    //     return $scripts;
    // }
    public function fetchPatientDetails($id)
    {       
            $id = sanitizeVariable($id); 
            $data = Patients::all()->where("id", $id);
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
               
                $btn ='<a href="/rpm/monthly-service/'.$row->id.'" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])    
            ->make(true); 
    } 

       
    

}