<?php
namespace RCare\Patients\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
use RCare\TaskManagement\Models\UserPatients;
use RCare\Org\OrgPackages\QCTemplates\src\Models\ContentTemplate;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate;
use RCare\Ccm\Models\QuestionnaireTemplatesUsageHistory;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use RCare\Org\OrgPackages\Users\src\Models\OrgUserRole;
use RCare\Org\OrgPackages\Roles\src\Models\Roles; 
use RCare\Org\OrgPackages\Practices\src\Models\Practices; 
use RCare\Org\OrgPackages\Providers\src\Models\Providers;
use RCare\Rpm\Models\PatientTimeRecordPatients;
use RCare\Ccm\Models\PatientCareplanCarertool;
use RCare\Ccm\Models\PatientConditionCaretool;
use RCare\Ccm\Models\CallStatus;
use RCare\Patients\Models\PatientDevices;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientDiagnosis;
use RCare\Patients\Models\PatientMedication;
use RCare\Patients\Models\PatientOrders;
use RCare\Patients\Models\PatientPartResearchStudy;
use RCare\Patients\Models\PatientPersonalNotes;
use RCare\Patients\Models\PatientDemographics;
use RCare\Patients\Models\PatientAddress;
use RCare\Patients\Models\PatientFamily; 
use RCare\Patients\Models\PatientPoa;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientInsurance;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\PatientContactTime;
use RCare\Patients\Models\PatientTimeRecords;
use RCare\Patients\Models\PatientIdEditHistory;
use RCare\Patients\Models\PatientActiveDeactiveHistory;
use RCare\Patients\Http\Requests\PatientPersonalNotesAddRequest;
use RCare\Patients\Http\Requests\PatientResearchstudyAddRequest;
use RCare\Patients\Http\Requests\PatientAddRequest;
use RCare\Patients\Http\Requests\PatientEditRequest;
use RCare\Patients\Http\Requests\PatientProfileImage;
use RCare\Patients\Http\Requests\ActiveDeactiveAddRequest;
use RCare\Patients\Http\Requests\ActiveExtendDeactiveAddRequest;
use Session;
use Hash;
use Validator,Redirect,Response;
use DataTables;
use Illuminate\Support\Facades\Log; 
use Carbon\Carbon;
use File,DB;
use Inertia\Inertia;

class AssignPatientController extends Controller
{

    public function assignedPatients(Request $request) 
    {   //change by priya 8th jun 22 for pcp practices
        $active_pracs = Practices::activePcpPractices();//activePractices();
        $inative_pracs = Practices::InactivePcpPractices();//InactivePractices();
        return view('Patients::patient.assigned-patient-list',compact('active_pracs','inative_pracs'));      
    }

    
    public function newassignedPatients(Request $request)
    {
        $active_pracs = Practices::activePcpPractices();
        $inactive_pracs = Practices::InactivePcpPractices();
           return Inertia::render('Patients/PatientsAssignment', [
            'active_pracs' => $active_pracs,
            'inactive_pracs' => $inactive_pracs,
        ]);
    }
       
    public function assignedPatientsSearch(Request $request) 
    {
     
            $practices = sanitizeVariable($request->route('practiceid')); 
            $provider = sanitizeVariable($request->route('providerid')); 
            $time  = sanitizeVariable($request->route('time')); 
            $care_manager_id  =sanitizeVariable($request->route('care_manager_id')); 
            $patient_status=sanitizeVariable($request->route('patientstatus'));
            $timeoption=sanitizeVariable($request->route('timeoption'));  
            // $currentdate=sanitizeVariable(date('Y-m-d HH:mm:ss'));
            // $year = date('Y', strtotime($currentdate));
            // $month = date('m', strtotime($currentdate));   
            $monthly = Carbon::now();
            $year = date('Y', strtotime($monthly));
            $month = date('m', strtotime($monthly));
            $configTZ = config('app.timezone');
            $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
           
            $p;
            $pt;
            $totime; 
            $status;
            $c;
              
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

              if( $care_manager_id!='null'){
                if(  $care_manager_id==0){
                  $c = 0;  
                }
                else{
                  $c = $care_manager_id;
                }    
              }
              else{
                $c = 'null';
              }
                
             if($time=='null' || $time==''){
                $timeoption="1";
                $totime = '00:20:00';       
             }
             else{
               $totime = $time;
             } 

             if($provider!="null"){ 
              $pr = $provider; 
             }
             else{
                 $pr = "null";
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

             if($patient_status=="null"){ 
                //$status=1;
                $status='null';
            }
            else{
               $status=$patient_status;     
            }

            $query = "select * from patients.sp_assigned_patients_report($p,$pr,$month, $year,$timeoption,'".$totime."',$status, '".$configTZ ."','".$userTZ."',$c)";        
               // dd($query);   
            $data = DB::select($query);
              //$careManager = DB::table('ren_core.users')->where('role', '=', 5)->get();
              // dd($data);  
               return Datatables::of($data)
             ->addIndexColumn() 
             ->addColumn('patient_count', function($row){
                if($row->ppracticeid =='' or $row->ppracticeid == null)  {
                  $practice_cond=  " and (pp.practice_id is null )";
                }else{
                  $practice_cond=  " and pp.practice_id = $row->ppracticeid";
                }
                if ($row->userid ) {
                  $patientcount = "select count(us.patient_id) as patient_count, pp.practice_id, us.user_id 
                  from task_management.user_patients us
                  LEFT JOIN (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                  from patients.patient_providers pp1
                  inner join (select patient_id, max(id) as created_date 
                  from patients.patient_providers  where provider_type_id = 1 and is_active=1
                  group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.created_date
                  and pp1.provider_type_id = 1 and is_active=1) pp                 
                  on us.patient_id = pp.patient_id  
                  where us.status=1 and user_id = $row->userid   $practice_cond
                  GROUP BY us.user_id, pp.practice_id";
    
                  $patient_count = DB::select(($patientcount) );
                  if(!empty($patient_count)) {
                    return $patient_count[0]->patient_count;
                }else{
                    return 0;
                }
                } else {
                  return 0;
                }
    
              })
             ->addColumn('action', function($row){
              if($row->ppracticeid == ''){
                $careManager =   DB::table('ren_core.users')
            ->where('role', '=', 5)
            ->orWhere('role', '=', 8)
            ->get();
             }else{
                $careManager =   DB::table('ren_core.users')
            ->where('role', '=', 5)
            ->orWhere('role', '=', 8)
            ->whereIn('id', DB::table('ren_core.user_practices')->where('practice_id', $row->ppracticeid)->pluck('user_id'))
            ->get();
             }
                $btn='';
                $cm='';
                if($row->practicestatus=='1')
                {
                $cm = "<input type='hidden' name='patient[]' value='".$row->pid."'><select  onchange='assignPatient(".$row->pid.", this.value)'><option>select CM</option>
                        <option value=''>None</option>";
                foreach($careManager as $key=>$value){ 
                    if($value->id == $row->userid){ $select = 'selected';}else{ $select = '';}         
                    $cm .= "<option value='".$value->id."' ".$select.">".$value->f_name.' '.$value->l_name."</option>";
                }
                $cm .= "</select>";
              }
    
                $btn .= $cm;              
                return $btn;
            })
           ->rawColumns(['action','patient_count'])    
          ->make(true); 
    }

    public function getActiveUsers(Request $request){

        $careManager =   DB::table('ren_core.users')
        ->whereIN('role', array(5,8))
        ->where('status',1)
        ->get();
        return response()->json($careManager);
    
        }

    public function SavePatientUser(Request $request){
      if (empty($request->selectedOptionManager)) {
        return response()->json(['error' => 'Selected option manager is required.'], 400);
    }
        $user = sanitizeVariable($request->selectedOptionManager);
        $patientIds = sanitizeVariable($request->selectedRows);
        $data = [];
        $now = Carbon::now();
        $updateData = ['status' => 0];
        foreach($patientIds as $patientId){
          $data[] = [ 
              'user_id' => sanitizeVariable($user),
              'patient_id' => $patientId,
              'created_by' => session()->get('userid'),
              'updated_by' => session()->get('userid'), 
              'created_at' => $now,
              'status' => 1
          ];
      }
  
      if(UserPatients::whereIn('patient_id', $patientIds)->exists()){
        UserPatients::whereIn('patient_id', $patientIds)->update($updateData);
         }
  
    if(!empty($data)){
        UserPatients::insert($data);
    }
    return response()->json(['success' => 'Data saved successfully.'], 200);

    }
}  