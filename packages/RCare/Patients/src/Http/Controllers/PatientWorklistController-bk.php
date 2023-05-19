<?php
namespace RCare\Patients\Http\Controllers;
use RCare\Patients\Models\Patients;
use RCare\TaskManagement\Models\UserPatients;
use RCare\TaskManagement\Models\PatientActivity;   
use RCare\Org\OrgPackages\Activity\src\Models\Activity;
use RCare\Org\OrgPackages\Activity\src\Models\PracticeActivity;
use RCare\Org\OrgPackages\Users\src\Models\UserFilters; 
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
use RCare\Org\OrgPackages\Modules\src\Models\Module;
    
class PatientWorklistController-bk extends Controller { 
   

    public function getUserListData(Request $request) {
      $cid = session()->get('userid');
      $usersdetails = Users::where('id',$cid)->get();
      $roleid = $usersdetails[0]->role;
        return view('Patients::patient-allocation.work-list',compact('roleid'));    
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
 

    //created by ashvini and modified on 9thnov2020  ()
    public function getUserListDataAll(Request $request) 
    {
        if ($request->ajax()) {
            $pagemodule_id = getPageModuleName();
            $pagesubmodule_id =getPageSubModuleName();
  

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
                $status="null";
            }
            else{
               $status=$activedeactivestatus;  
            }   

            if($module=="null"){
              $module_id = "null";
            }else{ 
              $module_id = $module;
            }
            //$query = "select * from patients.worklist($p,$pt,$month, $year,$timeoption,'".$totime."',$roleid, $cid,'".$configTZ ."','".$userTZ."',$status)";      
            $query = "select pid, pfname, plname, pmname, pprofileimg, pdob, pppracticeemr,ppracticeid, pracpracticename,pfromdate,ptodate,to_char(csslastdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csslastdate  , pstatus, ptrtotaltime from 
            patients.worklist($p,$pt,$month, $year,$timeoption,'".$totime."',$roleid, $cid,'".$configTZ ."','".$userTZ."',$status,$module_id)";     
           // dd($query);  
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
                 $status = $row->pstatus;
                 $pid = $row->pid;
                if($row->pstatus == 1 && $row->pstatus!=0 && $row->pstatus!=2 && $row->pstatus!=3){
                $btn ='<a href="javascript:void(0)" class="ActiveDeactiveClass" onClick ="ccmcpdcommonJS.onActiveDeactiveClick('.$pid.','.$status.')" data-toggle="modal"
                data-id="'.$row->pid.'/'.$row->pstatus.'" data-target="#active-deactive"  id="active_deactive">             
                <i class="i-Yess i-Yes"  title="Patient Status"></i></a>';
               }
              else
              {
                $btn ='<a href="javascript:void(0)" class="ActiveDeactiveClass" onClick ="ccmcpdcommonJS.onActiveDeactiveClick('.$pid.','.$status.')" data-toggle="modal"
                data-id="'.$row->pid.'/'.$row->pstatus.'" data-target="#active-deactive"  id="active_deactive"> 
                <i class="i-Closee i-Close" title="Patient Status"></i></a>'; 
                // <i class="i-Yess i-Yes"  title="Activate OR Extend Deactivate"></i></a>'
                //  data-target="#Extend-deactive"  id="active_extend_deactive"> 
                // <i class="i-Closee i-Close" title="Patient Status">;
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

    public function getUserFilters(Request $request)  
    {
        $cid = session()->get('userid');  
        $check =  UserFilters::where('user_id',$cid)->select('filters')->get('filters'); 
        $d = []; 
        // dd($check); 

        if(count($check)>0)
        {
     
          $filter = $check[0]->filters;
          $decodedfilters = json_decode($filter); 
          $practice = $decodedfilters->practice;
          $timeoption =  $decodedfilters->timeoption;
          $time = $decodedfilters->time;
          $patientstatus = $decodedfilters->patientstatus;
          $patient = $decodedfilters->patient;
          $data=array('practice'=>$practice,'patient'=>$patient,'timeoption'=>$timeoption,'time'=> $time,
                    'patientstatus'=>$patientstatus);   
                   
                    $data = json_encode($data);
          return $data ;
        }
        else{
          $d = json_encode($d);
          return $d;
        }  
        
    }

    public function saveUserFilters(Request $request)
    {
        if ($request->ajax()) {
            $pagemodule_id = getPageModuleName();
            $pagesubmodule_id =getPageSubModuleName();
            $filters = [];

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

             if($activedeactivestatus=="null"){ 
                $status=1;
            }
            else{
               $status=$activedeactivestatus;   
            }


            $filters['practice'] = $p;
            $filters['patient'] = $pt; 
            $filters['timeoption'] = $timeoption;
            $filters['time'] = $totime;
            $filters['patientstatus'] = $status; 
            
             
            $data = array(
                'user_id'         =>  $cid,
                'module_id'       =>  $pagemodule_id,
                'submodule_id'    =>  $pagesubmodule_id,
                'filters'         =>  json_encode($filters),
            );
            $check =  UserFilters::where('user_id',$cid)->get();
           
            if(count($check)>0){ 
               
                $update = UserFilters::where('user_id',$cid)->update($data);
            }
            else{
           
                $insert = UserFilters::create($data);  
            }
              
        

        }
   
    }
    

}