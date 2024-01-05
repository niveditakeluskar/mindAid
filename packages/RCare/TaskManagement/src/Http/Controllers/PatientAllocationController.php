<?php

namespace RCare\TaskManagement\Http\Controllers;
use RCare\Patients\Models\Patients;
use RCare\TaskManagement\Models\UserPatients;
use RCare\TaskManagement\Models\PatientActivity;
use RCare\Org\OrgPackages\Activity\src\Models\Activity;
use RCare\Org\OrgPackages\Activity\src\Models\PracticeActivity;
use RCare\Ccm\Models\CallWrap;
use RCare\TaskManagement\Http\Requests\PatientActivityAddRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log;
use DataTables;
use Carbon\Carbon;
use Session;
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use RCare\Org\OrgPackages\Users\src\Models\OrgUserRole;
use RCare\Org\OrgPackages\Roles\src\Models\Roles;

    
class PatientAllocationController extends Controller { 
    public function getPatientListData(Request $request)
    {
        return view('TaskManagement::patient-allocation.patient-list');

    }

    
    public function get_activitytime(Request $request){
        $id = sanitizeVariable($request->route('id'));
        $practice_id = sanitizeVariable($request->route('practice_id'));
        $query =Activity::where('id',$id)->where('status',1)->get();
        if($query[0]->timer_type==1){
            $query =Activity::where('id',$id)->get();
        }else{
            $check = PracticeActivity::where('activity_id',$id)->where('practice_id',$practice_id)->exists();
                if($check==true){
                    $query = PracticeActivity::where('activity_id',$id)->where('practice_id',$practice_id)->select('time_required')->get();                
                }else{
                    $query =Activity::where('id',$id)->select('default_time')->get();
                }
        }
        return $query;
    }
 
        public function savePatientActivity(PatientActivityAddRequest $request){
        $start= sanitizeVariable($request->timer_on);
        $end = sanitizeVariable($request->net_time);
        $today =date('m/d'); 
        // dd($today);
        $time_off = date("H:i:s",strtotime($start) + strtotime($end));
   
         $sum = strtotime('00:00:00'); 
           $totaltime = 0; 
            // Converting the time into seconds 
            $timeinsecend = strtotime($end) - $sum; 
            $timeinsecstart = strtotime($start) - $sum;
            // Sum the time with previous value 
            $totaltime =  abs($timeinsecstart + $timeinsecend);  
            
            $timeinsec = strtotime('24:00:00') - $sum;
            
        if($totaltime > $timeinsec){
            $msg = 'time is not more than 24 hours';
            return $msg;
        }else{
                //Insert Data in callwrapUp
                $sequence   = 6;
                $last_sub_sequence = CallWrap::where('patient_id',sanitizeVariable($request->patient_id))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', $sequence)->max('sub_sequence');
                // dd($last_sub_sequence);
                $timesplit=explode(':',sanitizeVariable($request->net_time));
                $min=($timesplit[0]*60)+($timesplit[1])+($timesplit[2]>30?1:0);
                $new_sub_sequence = $last_sub_sequence + 1;
                $callwrapUp = array(
                            'uid'                 => sanitizeVariable($request->patient_id),
                            'record_date'         => Carbon::now(),
                            'topic'               => sanitizeVariable($request->activity),
                            'notes'               => $today.' '.$min.' Minutes  '.sanitizeVariable($request->notes),
                            'action_taken'        => '',
                            'emr_entry_completed' => null,
                            'emr_monthly_summary' => null,
                            'created_by'          => session()->get('userid') ,
                            'patient_id'          => sanitizeVariable($request->patient_id),
                            'sequence'            => $sequence,
                            'sub_sequence'        => $new_sub_sequence
                      );
            $save = CallWrap::create($callwrapUp);
            $take_id =$save->id;

                $data = array('patient_id' => sanitizeVariable($request->patient_id),
                      'timer_on'   => sanitizeVariable($request->timer_on),
                      'net_time'   => sanitizeVariable($request->net_time), 
                      'uid'        => sanitizeVariable($request->patient_id),
                      'timer_off'  => $time_off,
                      'module_id'  => sanitizeVariable($request->module_id),
                      'component_id'=> sanitizeVariable($request->component_id),
                      'record_date' => Carbon::now(),
                      'billable'    => 1,
                      'comment'     => sanitizeVariable($request->notes),
                      'callwrap_id' => $take_id,
                      'activity'    => sanitizeVariable($request->activity),
                      'activity_id' => sanitizeVariable($request->activity_id),
                      'created_by'  =>session()->get('userid'), 
                      'updated_by'  =>session()->get('userid'),
                      'stage_id'    => 0 
                    ); 
                // dd($data);
                    $insert_data = PatientActivity::create($data);
        }
              
    }
    public function listPatients(Request $request) {
        //dd('here');
        if ($request->ajax()) {
            $data = Patients::all();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){ 
                $btn ='<a href="/task-management/patient-details/'.$row->id.'" title="Start" ><label class="checkbox"><input type="checkbox" id=""><span></span><span class="checkmark"></span></label></a>';//<i class="text-20 i-Next1" style="color: #2cb8ea;"></i>
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('TaskManagement::patient-allocation.patient-list');
    }

    //created by ashvini and modified on 9thnov2020  ()
    public function getUserListDataAll(Request $request) 
    {
        if ($request->ajax()) {
            $module_id = getPageModuleName();
            $submodule_id =getPageSubModuleName();
            dd($module_id, $submodule_id);  

            $cid = session()->get('userid');
            $practices = sanitizeVariable($request->route('practice_id'));
            $patient = sanitizeVariable($request->route('patient_id'));
            $module   = sanitizeVariable($request->route('module_id'));
            $timeoption = sanitizeVariable($request->route('timeoption'));
            $time = sanitizeVariable($request->route('time'));
            $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus')); 
            $usersdetails = Users::where('id',$cid)->get();
            $roleid = $usersdetails[0]->role;
            $monthly = Carbon::now();
            $monthlyto = Carbon::now();
            $year = date('Y', strtotime($monthly));
            $month = date('m', strtotime($monthly));
            $configTZ = config('app.timezone');
            $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
            $p;
            $pt;
            $totime; 
            $status;
              
             if( $practices!='null'){
                if( $practices==0){
                  $p = 0;  
                }
                else{
                  $p = $practices;
                }    
              }
              else{
                $p = 'null';
              }

              if($patient!="null"){ 
                $pt = $patient; 
               }
               else{
                   $pt = "null";
               }

                
             if($time=='null' || $time==''){
                $timeoption="1";
                $totime = '00:20:00';     
             }
             else{
               $totime = $time;
             } 
                
    
             if($time!="null" && $time!="00:00:00"){ 
                $totime = $time;
             }
             
             if($timeoption=="3" && $time=="00:00:00"){
                $timeoption="5";
             } 

             if($timeoption=="2" && $time=='00:00:00'){
               $timeoption = "6"; 
             }

             if($activedeactivestatus=="null"){ 
                $status=1;
            }
            else{
               $status=$activedeactivestatus;  
            }

            $query = "select * from patients.worklist($p,$pt,$month, $year,$timeoption,'".$totime."',$roleid, $cid,'".$configTZ ."','".$userTZ."',$status)";      
            // $data = DB::select( DB::raw($query) );
            
            if($roleid  == 2){
                if($module!="null" || $p!="null" || $pt!="null"){   
                    $data = DB::select(DB::raw($query));
                }
                else{
                $data = []; 
                }
            } 
            else{
            $data = DB::select(DB::raw($query));    
            } 
             
            return Datatables::of($data)
            ->addIndexColumn()

            ->addColumn('action', function($row){
            $check = DB::table('patients.patient_diagnosis_codes')
            ->where('patient_id',$row->pid)
            ->first();
            if($check == "" || $check == null ){ 
                $btn ='<a href="/ccm/care-plan-development/'.$row->pid.'" title="Action" >Care Plan</a>'; //cpd
            }else{
                $btn ='<a href="/ccm/monthly-monitoring/'.$row->pid.'" title="Action" >CCM</a>'; //monthly
            }
            return $btn;  
            })

             ->addColumn('activedeactive', function($row){ 
                     if($row->pstatus == 1){
                $btn ='<a href="javascript:void(0)" class="ActiveDeactiveClass" data-toggle="modal"
                data-id="'.$row->pid.'" data-target="#active-deactive"  id="active_deactive"><i class="i-Closee i-Close" title="Deactivate Patient"></i></a>';//<i class="text-20 i-Next1" style="color: #2cb8ea;"></i> onclick=getWorklistPatientStatusData('.$row->id.')

              }
              else
              {
                 $btn ='<a href="javascript:void(0)" class="ActiveDeactiveClass" data-toggle="modal"
                data-id="'.$row->pid.'" data-target="#Extend-deactive"  id="active_extend_deactive"><i class="i-Yess i-Yes"  title="Activate OR Extend Deactivate"></i></a>';
              }
                return $btn;
            })

            ->addColumn('addaction', function($row){
            $btn ='<a href="javascript:void(0)"  data-toggle="modal" data-id="'.$row->pid.'/'.$row->ptrtotaltime.'/'.$row->ppracticeid.'" data-target="#add-activities" id="add-activity"
            data-original-title="Patient Activity" class="patient_activity" title="Patient Activity"><i class="text-20 i-Stopwatch" style="color: #2cb8ea;"></i></a>';
            return $btn; 
            }) 
         
            ->rawColumns(['action','activedeactive','addaction'])  
            ->make(true);

        }
    }
    public function getUserListData(Request $request) {
      
        return view('TaskManagement::patient-allocation.work-list');
    }

     public function listPatientsSearch(Request $request) {
        
        $practise_id = sanitizeVariable($request->practice_id);
        $caremanager_id = sanitizeVariable($request->caremanager_id);
    
        if ($request->ajax()) {
            $data = 
            $patients = DB::select( DB::raw("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob , pa.add_1, u.f_name,u.l_name,up.user_id as cm
            from patients.patient p left join task_management.user_patients up on p.id = up.patient_id and up.status = 1 and up.user_id = '".$caremanager_id."' left join 
            ren_core.users as u on p.updated_by =u.id left join 
            patients.patient_addresss as pa on p.id = pa.patient_id ,
            patients.patient_services ps,
            patients.patient_providers as q 
            where p.id = ps.patient_id and ps.status = 1
            and p.id = q.patient_id  and 
            q.practice_id = '".$practise_id."' 
            and ps.patient_id not in (select distinct patient_id from task_management.user_patients where user_id != '".$caremanager_id."' and status=1)
            order by p.fname") );
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                if($row->cm != ''){
                    $btn ='<a href="/task-management/patient-details/'.$row->id.'" title="Start" ><label class="checkbox"><input type="checkbox" name="patient_id['.$row->id.']"  checked><span></span><span class="checkmark"></span></label></a>';
                }else{
                    $btn ='<a href="/task-management/patient-details/'.$row->id.'" title="Start" ><label class="checkbox"><input type="checkbox" name="patient_id['.$row->id.']" ><span></span><span class="checkmark"></span></label></a>';
                }
                
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('TaskManagement::patient-allocation.patient-list');
    }

    public function caremanagerlist($practiceId){
        $practiceId = sanitizeVariable($practiceId);
        $cm = DB::table('ren_core.user_practices')
                ->select("users.id","users.f_name", "users.l_name")
                ->join("ren_core.users", "users.id", "=", "user_practices.user_id")
                ->where("users.role",5)
                ->where('user_practices.practice_id', $practiceId)
                ->get();
       return $cm; 
    }
    public function practicePatients($practice,$caremanagerId)
    {   
        $practice = sanitizeVariable($practice);
        $caremanagerId = sanitizeVariable($caremanagerId);
        $patients = [];
        $caremanagerId = Role::where('id',$caremanagerId)->count();
        if ( $caremanagerId > 0 ) {
            $patients = DB::select( DB::raw("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  from patients.patient p, patients.patient_services ps, patients.patient_providers as q where p.id = ps.patient_id and ps.module_id = '".$moduleId."' and p.id = q.patient_id  and q.practice_id = '".$practice."' order by p.fname ") );
            
            // select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  from patients.patient p, 
            // patients.patient_services ps, patients.patient_providers as q where p.id = ps.patient_id
            // and ps.module_id = '".$caremanagerId."' 
            // and p.id = q.patient_id  and q.practice_id = '".$practice."' order by p.fname 
        } else {
            $patients = DB::select( DB::raw("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  from patients.patient p, patients.patient_providers as q where  p.id = q.patient_id and q.practice_id = '".$practice."' order by p.fname ") );
            // $patients = DB::select( DB::raw("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  from patients.patient p, patients.patient_services ps, patients.patient_providers as q where p.id = ps.patient_id and p.id = q.patient_id and q.practice_id = '".$practice."' ") );
        }
        return response()->json($patients);
    }
    public function fetchPatientDetails(Request $request) {
        $uid          = sanitizeVariable($request->route('id'));
        $module_id    = sanitizeVariable(getPageModuleName());
        $component_id = sanitizeVariable(getPageSubModuleName());
        $questionnaire_template_type_id = 5;
        $relation_stage_id = 15;
        $introStageCode = 1;
        $SID = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'General Question');
        //dd($uid);
        // $patient = Patients::where('id',$uid)->get();
        $patient =  Patients::with('patientServices', 'patientServices.module')->where('id',$uid)->get();
        $patient_demographics = PatientDemographics::where('patient_id', $uid)->latest()->first();
        $PatientAddress = PatientAddress::where('patient_id', $uid)->latest()->first();
        //dd($patient);
        $services = Module::where('patients_service',1)->get();
        $previous_month_time_spend = CommonFunctionController::getCcmPreviousMonthNetTime($uid, $module_id);
        $last_time_spend = CommonFunctionController::getCcmNetTime($uid, $module_id);
        $decisionTree = QuestionnaireTemplate::where('template_type_id',6)->get();
        $data1 = QuestionnaireTemplate::where('module_id', $module_id)->where('template_type_id', $questionnaire_template_type_id)->where('stage_id', $relation_stage_id)->where('stage_code', $introStageCode)->get();
        
        $personal_notes = (PatientPersonalNotes::where('patient_id',$uid) ? PatientPersonalNotes::where('patient_id',$uid)->get() : "");
        $current_month = CallPreparation::where('patient_id', $uid)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->latest()->first();
        $previous_month = CallPreparation::where('patient_id', $uid)->whereMonth('updated_at', date('m')-1)->whereYear('updated_at', date('Y'))->latest()->first();
        $research_study = (PatientPartResearchStudy::where('patient_id',$uid) ? PatientPartResearchStudy::where('patient_id',$uid)->get() : "");
        $generalq = [];
        $dtsteps = StageCode::where('stage_id',$SID)->get();
        $decisionTree = QuestionnaireTemplate::where('module_id', $module_id )->where('status',1)->where('stage_id',$SID)->where('template_type_id', 6)->orderBy('stage_code', 'ASC')->orderBy('sequence','ASC')->get()->toArray();
        //dd($decisionTree);  
        $stepWiseDecisionTree =[];
        $i = -1;
        foreach($decisionTree as $key=>$value){
            //echo $value['stage_code'].",";
            if(array_key_exists($value['stage_code'],$stepWiseDecisionTree)){
                $i++;
            }else{
                $i=0;
            }
            $stepWiseDecisionTree[$value['stage_code']][$i] = $value;           
            
        }
        //dd($stepWiseDecisionTree);
        
        //$decisionTree = QuestionnaireTemplate::where('module_id', $module_id )->where('status',1)->where('stage_code',getFormStepId($module_id,$component_id,$SID,'General Question'))->where('template_type_id', 6)->get();
        //$decisionTree1 = QuestionnaireTemplate::where('module_id', $module_id )->where('status',1)->where('stage_code',getFormStepId($module_id,$component_id,$SID,'Disease Related Question'))->where('template_type_id', 6)->get();
        //$decisionTree2 = QuestionnaireTemplate::where('module_id', $module_id )->where('status',1)->where('stage_code',getFormStepId($module_id,$component_id,$SID,'Grant Related Question'))->where('template_type_id', 6)->get();
        
        $genQuestion = QuestionnaireTemplatesUsageHistory::where('patient_id', $uid)->where('module_id', $module_id)->where('contact_via', 'decisiontree')->where('step_id',0)->get();  
        $patient_providers = (PatientProvider::getPatientPCPProvider($uid)? PatientProvider::getPatientPCPProvider($uid) : ""); //PatientProvider::where('patient_id', $uid)->get();
        
       // dd($patient_providers);
        $followup_dropdown = ToDoList::where('patient_id',$uid)->where('status_flag',0)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->get();
        $patient_enroll_date = PatientServices::latest_module($uid, $module_id);
        return view('Ccm::monthly-monitoring.patient-details',['patient'=>$patient], compact('current_month', 'previous_month','previous_month_time_spend','last_time_spend', 'generalq', 'genQuestion', 'data1', 'services', 'patient_providers', 'patient_enroll_date','followup_dropdown','PatientAddress','stepWiseDecisionTree','dtsteps', 'patient_demographics','personal_notes','research_study'));        
    }

    public function ProductivitySummary(Request $request)
    { 
        // $query="select count(*) from patients.patient ";  
        // $totalPatient=DB::select( DB::raw($query) );
        $totalPatient= \DB::table('patients.patient')->count();

        $totalCaremanagers = \DB::table('ren_core.users')->where('role',5)->where('status',1)->count();

        $totalPatientWorkedon = \DB::table('patients.patient_time_records')->distinct('patient_id')->count();

        $totalBillablepatient = \DB::table('patients.caremanager_monthwise_total_time_spent')->distinct('patient_id')
                                ->where('billable',1)->count(); 

        $totalpractices = \DB::table('ren_core.practices')->count();  
        
        $totalpracticebillablepatient =  \DB::table('patients.caremanager_monthwise_total_time_spent as c')
        ->join('patients.patient_providers as pp','pp.patient_id','=','c.patient_id')
        ->select('pp.practice_id')
        ->where('c.billable',1)
        ->distinct('pp.practice_id')
        ->get();
        $total = count($totalpracticebillablepatient);
       
        $data=array('Totalpatient'=>$totalPatient,'TotalCareManager'=>$totalCaremanagers,'TotalPatientWorkedon'=> $totalPatientWorkedon,
                    'TotalBillablepatient'=>$totalBillablepatient,'Totalpractices'=>$totalpractices,
                    'TotalPracticesBillablePatient'=>$total); 
       return $data;

    }

}