<?php
 
namespace RCare\Org\OrgPackages\Scheduler\src\Http\Controllers;

// use RCare\RCareAdmin\AdminPackages\Users\src\Http\Requests\UserAddRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;  
use Illuminate\Http\Request;
use RCare\RCareAdmin\AdminPackages\Users\src\Models\User;
// use RCare\RCareAdmin\AdminPackages\Users\src\Models\UserRole;
use RCare\Org\OrgPackages\Scheduler\src\Models\Scheduler;  
use RCare\Org\OrgPackages\Scheduler\src\Models\SchedulerLogHistory; 
use RCare\Org\OrgPackages\Scheduler\src\Http\Requests\SchedulerAddRequest;
use RCare\Org\OrgPackages\Scheduler\src\Http\Requests\ReportSchedulerAddRequest;   
use RCare\Org\OrgPackages\Activity\src\Models\Activity;
use RCare\Org\OrgPackages\Activity\src\Models\PracticeActivity;
use RCare\Org\OrgPackages\Practices\src\Models\practices;
use RCare\System\Traits\DatesTimezoneConversion; 
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str; 
use Session;
use DB;
use File;
use DataTables; 
use Validator,Redirect,Response;
use Carbon\Carbon;
use \DateTime;


class SchedulerController extends Controller {   

    //created and modified by ashvini 24thmarch2021
    public function index() {

        $modules = \DB::table('ren_core.modules')->where('patients_service',1)->get();
        $all_users = \DB::table('ren_core.users')->where('status',1)->orderBy('f_name', 'asc')->get(); 
        return view('Scheduler::scheduler',compact('modules','all_users'));  
    }


    public function addDeductTime($id){
         
      \Log::info("scheduler procedure called ");     
      $schedulerId = sanitizeVariable($id);
      $data = Scheduler::where('id',$schedulerId)->get()->toArray();
      $componentid = 0;
      $activityid = $data[0]['activity_id'];
      $operation  = $data[0]['operation']; 
      $date_of_execution = $data[0]['date_of_execution'];
      $day_of_execution  = $data[0]['day_of_execution'];
      $time_of_execution = $data[0]['time_of_execution'];  
      $comments = $data[0]['comments'];
      $module_id  = $data[0]['module_id'];
      $m = json_decode( $module_id );
      $arraymodules  = json_decode(json_encode($m), true);
      $countmodule = count($arraymodules);
      $finalmodulesarray = array();          
      $finalmodulesarray  = array_keys($arraymodules);
      $s=implode(',', $finalmodulesarray);
    
      if(count($finalmodulesarray)==1){
         $finalmodules = '{'.$s.',0}';
      }else{
         $finalmodules = '{'.$s.'}';
      }

      $paramdata    = PracticeActivity::where('activity_id',$activityid)->where('status',1)->distinct('practice_id')
      ->pluck('practice_id')->toArray();
      
      foreach($paramdata as $key=>$p)     
      { 
        $practicegrp = \DB::table('ren_core.practices')->where('id',$p)->where('is_active',1)->value('practice_group');

  
        if($countmodule==2){
          $query = "select count(distinct pp.patient_id) from patients.patient_providers pp 
          inner join patients.patient_services ps
          on ps.patient_id = pp.patient_id and
          ps.module_id in (2,3) 
          where pp.is_active = 1
          and pp.practice_id = '".$p."'
          group by pp.patient_id,ps.patient_id having count(*) = 2";

          $data = \DB::select(($query) );
          $patientscount = $data[0]->count;
          $newmodule_id = 3;
        }
        else{
          $patientscount = \DB::table('patients.patient_providers')->where('practice_id',$p)->where('is_active',1)->count();           
          $newmodule_id  = $finalmodulesarray[0];
          
         }

        $practicetime = PracticeActivity::where('activity_id',$activityid)->where('status',1)->where('practice_id',$p)
                        ->value('time_required');

        if($practicetime == "00:00:00"){
            $practicetime = Activity::where('id',$activityid)->where('status',1)->value('default_time');
        }

        $currentdatetimeforschedulerrecorddate = date("Y-m-d h:i:s");
        $schedulerrecorddate = DatesTimezoneConversion::userToConfigTimeStamp($currentdatetimeforschedulerrecorddate); 

      

        $scharray = array('scheduler_id'       =>  $schedulerId,  
                        'activity_id'          =>  $activityid,
                        'module_id'            =>  $newmodule_id ,
                        'operation'            =>  $operation,
                        'start_date'           =>  $date_of_execution,
                        'comments'             =>  $comments,
                        'practice_id'          =>  $p,
                        'practice_group'       =>  $practicegrp,  
                        'execution_status'     =>  0,
                        'patients_count'       =>  0,
                        'exception_comments'   =>  null,
                        'schedulerrecord_date' =>  $schedulerrecorddate   
                        );

                        
        $insertscheduler = SchedulerLogHistory::create($scharray);     
        $schedulerlogrunid =  $insertscheduler->id;  
    
         
        try{      
          
          $operation=='add' ? $convertedoperation=1 : $convertedoperation=0;      
          
        
         //changes made by ashvini arumugam 5th dec 2022
         $query = "select * from patients.sp_newarrayscheduleradditionaltime('".$finalmodules."',$practicegrp,$p,$componentid,'".$practicetime."','".$comments."',$schedulerId,$activityid,$schedulerlogrunid,$convertedoperation)";                                
         $insert =   DB::select($query);
           
         
         \Log::info("scheduler procedure called patients.sp_newarrayscheduleradditionaltime"); 
         
        
          $schupdatearray = array('execution_status'=>  1, 'patients_count'=> $patientscount); 
         
          $updatescheduler = SchedulerLogHistory::where('id',$schedulerlogrunid)->update($schupdatearray);
        
                    
          }
        catch(\Exception $e){    
          \Log::info("exception catched");
          \Log::info($e->getMessage());
          $exceptionmsg = $e->getMessage();       
          $schupdatearray = array('execution_status'=>  0, 'exception_comments'=> $exceptionmsg);  
          $updatescheduler = SchedulerLogHistory::where('id',$schedulerlogrunid)->update($schupdatearray);
        }
         
      }   
      
      
    }

    public function saveReportScheduler(ReportSchedulerAddRequest $request) //ReportSchedulerAddRequest
    {
     
      // dd($request->all());
      $id                    = sanitizeVariable($request->id);
      $configTZ              = config('app.timezone');
      $userTZ                = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');  
      $report_id             = sanitizeVariable($request->report_id);  
      $report_format         = sanitizeVariable($request->report_format);
      $frequency             = sanitizeVariable($request->frequency);
      $executionday          = sanitizeVariable($request->day_of_execution);
      $executionweek         = sanitizeVariable($request->week_of_execution);
      $executionmonth        = sanitizeVariable($request->month_of_execution);
      $executiondate         = sanitizeVariable($request->date_of_execution);
      $executiontime         = sanitizeVariable($request->report_time_of_execution); 
      $comments              = sanitizeVariable($request->comments);
      $created_by            = session()->get('userid'); 
      $executionyear         = 0; 
    
      $scheduler_date = $executiondate." ".$executiontime; 
      $orderdate = explode('-', $executiondate);
     

      $year    = $orderdate[0];
      $month   = $orderdate[1];
      $day     = $orderdate[2];

      if($frequency == 'weekly' || $frequency == 'daily'  ){      
         $executionday =  $day;
      }else if($frequency == 'yearly'){
         $executionyear = $year ;
      }
      
      
      

      if($executionday <10){
         // $a = 0;
         // $newday = $a.$executionday;
         $newday = sprintf("%02d", $executionday);
      }else{  
         $newday = $executionday;
      }
      
      $new_orderdate = $year.'-'.$month.'-'.$newday; 
      $scheduler_date = $new_orderdate." ".$executiontime; 
 
      $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($scheduler_date);

      $dtime = explode(" ",$dt1);
      $execution_time_utc = $dtime[1]; 
       $usresidarray = array();
       foreach ($request->user_id as $key => $value) {
            if($value==1){
               $usresidarray[] = $key;
            } 
        }
      // print_r($usresidarray);
       $users_id = json_encode($usresidarray);  
 
         if(isset($id)){
            $final_schedulerdate = $dt1;
         }else{
            $final_schedulerdate = $scheduler_date;
         }



       $data = array(
                     'report_id'            => $report_id,
                     'user_id'              => $users_id, 
                     'report_format'        => $report_format,
                     'day_of_execution'     => $executionday,
                     'week_of_execution'    => $executionweek,
                     'month_of_execution'   => $executionmonth, 
                     'year_of_execution'    => $executionyear, 
                     'date_of_execution'    => $executiondate,
                     'frequency'            => $frequency,
                     // 'time_of_execution'    => $executiontime,//$execution_time_utc,  commented by ashvini on 9th aug 2022
                     'time_of_execution'    => $execution_time_utc,
                     'created_by'           => $created_by,
                     'updated_by'           => $created_by,
                     'status'               => 1,
                     'scheduler_status'     => 1,
                     //'scheduler_date'       => $dt1, // commented by ashvini on 9th aug 2022 bcz it is getting converted 2 times ...once using line no #79 and secondly using getter nd setter function in traits
                     'scheduler_date'       => $final_schedulerdate,
                     'scheduler_type'       => 'report_scheduler',
                     'comments'             => $comments
             );

               // DB::enableQueryLog();

               $check_samereport_samefrequency = Scheduler::where('report_id',$report_id)->where('frequency',$frequency)
                                                ->where('scheduler_status',1)->where('status',1)->exists(); //true;
               if($check_samereport_samefrequency){
                  //donothing;
                  Scheduler::where('report_id',$report_id)->where('frequency',$frequency)->update(['scheduler_status'=>0, 'status'=>0]);
               } 

               if(isset($id)){
                  $schedule1 = Scheduler::where('id',$id)->update($data);
               }else{    
                               
                  $createscheduler = Scheduler::create($data); 
               }
              
               
    } 
    
    public function reportSchedulersameFrequencyCheck($id){

      $id  = sanitizeVariable($id);   
      $data = Scheduler::where('id',$id)->get();
      $check_samereport_samefrequency_count = Scheduler::where('report_id',$data[0]->report_id)->where('frequency',$data[0]->frequency)
                                       ->where('scheduler_status',1)->where('status',1)->count();

     return $check_samereport_samefrequency_count;

    }

    public function reportschedulerDeactiveOld($id){    
      $id  = sanitizeVariable($id); 
      $data = Scheduler::where('id',$id)->get();
      $check_and_update_samereport_samefrequency = Scheduler::where('report_id',$data[0]->report_id)->where('frequency',$data[0]->frequency)
                                             ->where('scheduler_status',1)->where('status',1)->update([ 'scheduler_status'=> 0, 'status'=> 0, 'updated_by' => session()->get('userid') ]);
   
      $active = Scheduler::where('id',$id)->update([ 'scheduler_status'=> 1, 'status'=> 1, 'updated_by' => session()->get('userid') ]);   
               
      
      return $active;

    }

        
    public function populateReportSchedulerData($id)
    {
        $id   = sanitizeVariable($id); 
        $configTZ             = config('app.timezone');
        $userTZ               = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
      //   $userTZ 	  = 'Asia/Calcutta'; //Session::get('timezone') not working  
      //   $schedulerdata = Scheduler::self($id)->population();
      
        $schedulerdata = \DB::table('ren_core.scheduler as s')
        ->leftjoin('ren_core.reports_master as a','s.report_id','=','a.id')
        ->leftjoin('ren_core.users as u','u.id','=','s.updated_by')
        ->where('s.report_id','!=',0)
        ->where('s.status',1)
        ->where('s.id',$id)
        ->select(DB::raw("to_char(s.scheduler_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'DD') as sdate_of_execution"),//MM-DD-YYYY
                 DB::raw("to_char(s.scheduler_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'HH24:MI:SS') as stime_of_execution"),
                 's.*','a.report_name','a.display_name','u.f_name','u.l_name')
        ->distinct('s.report_id','s.scheduler_date')
        ->get();

      //   dd($schedulerdata );        
    
        $result['report_scheduler_form'] = $schedulerdata; 
        return $result;  
    }


    public function saveScheduler(SchedulerAddRequest $request)
    {
      //   dd($request->all());   
       
      $configTZ             = config('app.timezone');
      $userTZ               = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');  
      $activity             = sanitizeVariable($request->activity_id); 
      $executionday         = sanitizeVariable($request->day_of_execution);
      $operation            = sanitizeVariable($request->operation);
      $comments             = sanitizeVariable($request->comments);
      $modules              = sanitizeVariable($request->module_id);
      $executiondate        = sanitizeVariable($request->date_of_execution);
      $executiontime        = sanitizeVariable($request->time_of_execution);  
      $created_by           = session()->get('userid');
      $operation == '1' ? $operation='add': $operation='subtract'; 
      $scheduler_date = $executiondate." ".$executiontime;   
      $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($scheduler_date); 
      $updatemodulesonly   =  0;

      // dd($scheduler_date , $dt1);  
     
      foreach($modules as $key=>$value) 
      {
         if($value!="1")
         {
               unset($modules[$key]);
         }else{
            $checkexistingschedulerforsamedateandtime =  Scheduler::where('activity_id',$activity)->where('scheduler_date',$dt1)->get();
            //   dd($checkexistingschedulerforsamedateandtime);

               if(count($checkexistingschedulerforsamedateandtime)>0)
               {
                  $a=$checkexistingschedulerforsamedateandtime[0]->module_id;
                  $exsitingmodulesarray = json_decode($a, true);

                  // dd($key,$a,$array);

                  
                     if (array_key_exists($key,$exsitingmodulesarray)){
                     // dd("if");
                     unset($modules[$key]);
                    

                  }else{
                     // dd("else");
                     $updatemodulesonly =  1;
                  }
               }
         }
      }

     

      if(count($modules)>0){
   
         if($updatemodulesonly==1){

            $checkexistingschedulerforsamedateandtime =  Scheduler::where('activity_id',$activity)->where('scheduler_date',$dt1)->get();
            $schedulerid = $checkexistingschedulerforsamedateandtime[0]->id;
            $a=$checkexistingschedulerforsamedateandtime[0]->module_id;

            $b=json_decode($a, true);
           
            foreach($modules as $key=>$value){
               array_push($b,$key);
               $b[$key]="1";

            }
            $modulesjson = json_encode($b);
            $modulesdata = array('module_id' =>  $modulesjson );
            
            Scheduler::where('id',$schedulerid)
            ->update($modulesdata);

         }else{

            $modulesjson = json_encode($modules);

             $data = array(
               'activity_id'          =>  $activity,
               'module_id'            =>  $modulesjson,
               'operation'            =>  $operation,
               'day_of_execution'     =>  $executionday,
               'comments'             =>  $comments,
               'created_by'           =>  $created_by,
               'updated_by'           =>  $created_by,
               'status'               =>  1,
               'scheduler_status'     =>  1,
               // 'scheduler_date'       =>  $dt1, 
               'scheduler_date'       => $scheduler_date,   //new changes ashvini 02 dec 2022 
               'scheduler_type'       => 'patient_time'
            );
            // dd($data,$dt1);  

            $insert= Scheduler::create($data); 
            
            $schedulerid = $insert->id;
            $oldschedulerscheduler_date = Scheduler::where('id',$schedulerid)->value('scheduler_date');
            $dtime = explode(" ",$oldschedulerscheduler_date);
            $executiondate = $dtime[0];
            $executiontime = $dtime[1];

            $newdtime = explode(" ",$dt1);  //new changes ashvini 02 dec 2022 
            $newexecutiondate = $newdtime[0];
            $newexecutime =  $newdtime[1];
            $newarrayName = array(
                                'date_of_execution'     =>  $executiondate,
                                'time_of_execution'     =>  $newexecutime, //new changes ashvini 02 dec 2022                  
                                );

            Scheduler::where('id',$schedulerid)
            ->update($newarrayName);
         }

      }else{
       
          $msg = 'already-exsits';
          return $msg;
      }

      // foreach($modules as $key=>$value) 
      // {
      //    // dd($value); 
      //     if($value=="1")
      //     {
            
      //       // array_push($key);

      //       $data = array(
      //           'activity_id'          =>  $activity,
      //           'module_id'            =>  $key,
      //           'operation'            =>  $operation,
      //           'day_of_execution'     =>  $executionday,
      //           'comments'             =>  $comments,
      //           'created_by'           =>  $created_by,
      //           'updated_by'           =>  $created_by,
      //           'status'               =>  1,
      //           'scheduler_status'     =>  1,
      //           'scheduler_date'       =>  $dt1,
      //           'scheduler_type'       => 'patient_time'
      //        );

      //       //  dd($data);
            
      //       $checkexistingschedulerforsamedateandtime =  Scheduler::where('activity_id',$activity)->where('module_id',$key)->where('scheduler_date',$dt1)->get();
      //       // dd(count($checkexistingschedulerforsamedateandtime)); 
      //       if(count($checkexistingschedulerforsamedateandtime)>0)
      //       {
      //           $msg = 'already-exsits';
      //           return $msg;
      //       }
      //       else{
      //           $insert= Scheduler::create($data); 
      //           $schedulerid = $insert->id;
      //           $oldschedulerscheduler_date = Scheduler::where('id',$schedulerid)->value('scheduler_date');
      //           $dtime = explode(" ",$oldschedulerscheduler_date);
      //           $executiondate = $dtime[0];
      //           $executiontime = $dtime[1];
      //           $newarrayName = array(
      //                               'date_of_execution'     =>  $executiondate,
      //                               'time_of_execution'    =>  $executiontime,                 
      //                               );

      //           Scheduler::where('id',$schedulerid)
      //           ->update($newarrayName);
      //       }
      //     }  
           
      // } 
      

      

    }  

    public function ReportSchedulerList(Request $request)    
    { 
      $moduleidarray = [];
        if($request->ajax()){  
            $configTZ = config('app.timezone');
            $userTZ   = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
            // $userTZ 	  = 'Asia/Calcutta'; //Session::get('timezone') not working  
            $data = \DB::table('ren_core.scheduler as s')
                    ->leftjoin('ren_core.reports_master as a','s.report_id','=','a.id')
                    ->leftjoin('ren_core.users as u','u.id','=','s.updated_by')
                    ->leftjoin('ren_core.scheduler_log as sl','sl.scheduler_id','=','s.id')
                    // ->leftjoin('ren_core.practicegroup as pg','pg.id','=','sl.practice_group')   
                    ->where('s.report_id','!=',0)
                  //   ->where('s.status',1)
                    ->select(DB::raw("to_char(s.scheduler_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY') as sdate_of_execution"),
                             DB::raw("to_char(s.scheduler_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'HH24:MI:SS') as stime_of_execution"),
                             's.*','a.report_name','a.display_name','u.f_name','u.l_name', 's.updated_at', 's.created_at')//,'sl.practice_group','pg.practice_name') 
                  //   ->distinct('s.report_id','s.scheduler_date')
                  //   ->groupBy('s.activity_id') 
                  ->orderBy('s.id','desc')       
                    ->get();  

                   

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){    
                $btn ='<a href="javascript:void(0)"data-id="'.$row->id.'" id="editReportScheduler" data-original-title="Edit" class="editReportScheduler" title="Edit"><i class=" editform i-Pen-4"></i></a>';
               if($row->scheduler_status == 1){
                  $btn = $btn. 
                  '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_reportscheduler_status_active" id="active"><i class="i-Yess i-Yes" title="Active"></i></a>';
               } else {
                  $btn = $btn.
                  '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_reportscheduler_status_deactive" id="deactive"><i class="i-Closee i-Close"  title="Deactive"></i></a>';
               }
               return $btn;  
            })
            ->rawColumns(['action']) 
            ->make(true); 
        }
        return view('Scheduler::reportScheduler');

    }

    //allpatientlis
    public function Allpatientstablelist(Request $request)
    {
       if($request->ajax()) 
       {            
           $practid = sanitizeVariable($request->practice);

             $query = "select * from patients.sp_totalpatients($practid)";  
           
           $data = DB::select($query);
         
           return Datatables::of($data)
           ->addIndexColumn()            
           ->make(true);
       }  
     }

     //activepatientlist
     public function Activepateintlist(Request $request)
    {
       if($request->ajax()) 
       {            
           $practid = sanitizeVariable($request->practice);

         //   $query = "select * from patients.sp_total_patient_deatils_of_assign_patient($practid)";  
         
         $query = "select * from patients.patient_details($practid)";  
           
           $data = DB::select($query);
         
           return Datatables::of($data)
           ->addIndexColumn()            
           ->make(true);
       }  
     }

     //assign task patient list
     public function Assignedpatientstablekist(Request $request)
    {
       if($request->ajax()) 
       {            
           $practid = sanitizeVariable($request->practice);

            //  $query = "select * from patients.sp_assignedtask_activepatient($practid)";
             $query = "select * from patients.sp_assigned_patients_details($practid)";    
           
           $data = DB::select($query);
         
           return Datatables::of($data)
           ->addIndexColumn()            
           ->make(true);
       }  
     }

     //non assign task patient list
     public function Nonassignedpatientslist(Request $request)
    {
       if($request->ajax()) 
       {            
           $practid = sanitizeVariable($request->practice);

            //  $query = "select * from patients.sp_nonassigntask_activepatient($practid)"; 
            $query = "select * from patients.sp_non_assigned_patients_details($practid)";  
 
           
           $data = DB::select($query);
         
           return Datatables::of($data)
           ->addIndexColumn()            
           ->make(true);
       }  
     }

  

    public function SchedulerList(Request $request)    
    {
      $moduleidarray = [];
        if($request->ajax()){  
            $configTZ = config('app.timezone');
            $userTZ   = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');  
            // $userTZ 	  = 'Asia/Calcutta'; //Session::get('timezone') not working
           
        $data = \DB::table('ren_core.scheduler as s')
                    ->leftjoin('ren_core.activities as a','s.activity_id','=','a.id')
                    ->leftjoin('ren_core.users as u','u.id','=','s.updated_by')
                    ->leftjoin('ren_core.scheduler_log as sl','sl.scheduler_id','=','s.id')
                    ->leftjoin('ren_core.practicegroup as pg','pg.id','=','sl.practice_group')   
                    ->where('s.report_id',0) 
                    ->select(DB::raw("to_char(s.scheduler_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY') as sdate_of_execution"),
                             DB::raw("to_char(s.scheduler_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'HH24:MI:SS') as stime_of_execution"),
                             's.*','a.activity','u.f_name','u.l_name','sl.practice_group','pg.practice_name') 
                    ->distinct('s.activity_id','s.scheduler_date')
                  //   ->groupBy('s.activity_id')        
                    ->get();  
                    // dd($data);



            foreach($data as $d)  
            {  
                
                $a = $d->activity_id;
                $mod  = $d->module_id;               
                $m1 = json_decode($mod);
               
      
                foreach($m1 as $mid=>$value){
               
                   $m = \DB::table('ren_core.modules as m')
                           ->where('m.id',$mid)
                           ->distinct('m.id')
                           ->pluck('m.module');
                  
                 
                  array_push($moduleidarray,$m[0]);     
              
                }
               //  dd($moduleidarray);
                $d->modules = $moduleidarray; 
                $moduleidarray = [];
              
              
            }                
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){    
                //$btn ='<a href="javascript:void(0)"data-id="'.$row->id.'" id="edit-scheduler123" data-original-title="Edit" class="editScheduler123" title="Edit"><i class=" editform i-Pen-4"></i></a>';
                //$btn ='';
               if($row->scheduler_status == 1){
                  $btn = //$btn. 
                  '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_scheduler_status_active" id="active"><i class="i-Yess i-Yes" title="Active"></i></a>';
               } else {
                  $btn = //$btn.
                  '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_scheduler_status_deactive" id="deactive"><i class="i-Closee i-Close"  title="Deactive"></i></a>';
               }
               return $btn;  
            })
            ->rawColumns(['action']) 
            ->make(true); 
        }
        return view('Scheduler::scheduler');

    }
        

   public function populateActivityData($activityId) {   
      //  dd($activityId);
      $activityId   = sanitizeVariable($activityId); 
      $activitydata = (Activity::self($activityId) ? Activity::self($activityId)->population() : "");  
      $paramdata    = PracticeActivity::with('practicegroup')->where('activity_id',$activityId)->where('status',1)->distinct('practice_group')->get()->toArray(); 
    
      foreach($paramdata as $key=>$p)  
      {
         $pra_id_array = DB::table('ren_core.practice_activity_time_required as rpt')
         ->join('ren_core.practices as rp', 'rp.id','=','rpt.practice_id')
         ->where('rpt.practice_group',$p['practice_group'])
         ->where('activity_id',$activityId)->where('status',1)
         ->distinct('practice_id')->pluck('name')
         ->toArray();
         $paramdata[$key]['practice_id_array'] = $pra_id_array; 

      }
      if($paramdata){ 
         $activityparamdata      = array('paramdata'=>$paramdata);
         $activitydata['static'] = array_merge($activitydata['static'], $activityparamdata);
      } 
      //dd($activitydata);
      return $activitydata; 
   
   
   }
    public function populateSchedulerData($schedulerId)
    {
        $schedulerId   = sanitizeVariable($schedulerId);  
        $schedulerdata = Scheduler::self($schedulerId)->population(); 
        $paramdata    = Scheduler::with('activity')->where('id',$schedulerId)->get();
        $modules      = Scheduler::where('activity_id',$paramdata[0]['activity_id'])->where('status',1)->pluck('module_id');
        
        $paramdata[0]['modules'] = $modules;
        if($paramdata){ 
           $activityparamdata      = array('paramdata'=>$paramdata);
           $schedulerdata['static'] = array_merge($schedulerdata['static'], $activityparamdata);
        }
        $result['edit_scheduler_form'] = $schedulerdata; 
        return $result;  
    }

    public function updateScheduler(Request $request) 
     { 
      //   dd($request->all());  
        $schedulerId   = sanitizeVariable($request->scheduler_id);        
        $activity      = sanitizeVariable($request->editactivity);
        $executiondate = sanitizeVariable($request->day_of_execution);
        $executiontime = sanitizeVariable($request->time_of_execution);
        $operation     = sanitizeVariable($request->operation);
        $modules       = sanitizeVariable($request->modules);
        $comments      = sanitizeVariable($request->comments);  
        $created_by    = session()->get('userid');
        $operation == '1' ? $operation='add': $operation='subtract';
        
     

        $oldactivityid =       Scheduler::where('id',$schedulerId)->value('activity_id');  
      
        $oldschedulermoduleid = Scheduler::where('activity_id',$oldactivityid)->pluck('module_id')->toArray();

        foreach($oldschedulermoduleid as $old)
        {
            $array = array(
                'updated_by' =>$created_by,
                'status'  =>  0,
                
             );
            Scheduler::where('activity_id',$oldactivityid)->where('module_id',$old)->update($array);
        } 

        foreach($modules as $key=>$value) 
        {
            if($value=='1'){
           
            if(in_array($value,$oldschedulermoduleid))    
            {
                $newarrayName = array(
                            'activity_id'          =>  $activity,
                            'module_id'            =>  $key,
                            'operation'            =>  $operation,
                            'day_of_execution'     =>  $executiondate,
                            'time_of_execution'    =>  $executiontime,
                            'comments'             =>  $comments,
                            'created_by'           =>  $created_by,
                            'updated_by'           =>  $created_by,
                            'status'               =>  1,
                            'scheduler_status'     =>1
                         );

                         Scheduler::where('id',$schedulerId)
                         ->where('module_id',$value)
                         ->update($newarrayName);
            }
            else{

            $data = array(
                'activity_id'          =>  $activity,
                'module_id'            =>  $key,
                'operation'            =>  $operation,
                'day_of_execution'     =>  $executiondate,
                'time_of_execution'    =>  $executiontime,
                'comments'             =>  $comments,
                'created_by'           =>  $created_by,
                'updated_by'           =>  $created_by,
                'status'               =>  1,
                'scheduler_status'     =>  1
             );
 
             Scheduler::create($data);  
            } 
        }
        } 
      
  
     }





     public function changeSchedulerStatus(Request $request)
     {
         // dd($request->id);  
        $id     = sanitizeVariable($request->id); 
        $data   = Scheduler::where('id',$id)->get(); 
    
        $scheduler_status = $data[0]->scheduler_status;
   

        if($scheduler_status==1){
       
           $statusdata =array('scheduler_status'=>0,'status'=>0, 'updated_by' =>session()->get('userid'));
           $update_query = Scheduler::where('id',$id)->orderBy('id', 'desc')->update([ 'scheduler_status'=>0,'status'=>0, 'updated_by' =>session()->get('userid') ]);

           
        }else{
       
           $statusdata =array('scheduler_status'=>1,'status'=>1, 'updated_by' =>session()->get('userid'));
           $update_query = Scheduler::where('id',$id)->orderBy('id', 'desc')->update(['scheduler_status'=>1,'status'=>1, 'updated_by' =>session()->get('userid') ]);
       
        }
      
        return true;  


     }

     public function executeScheduler($schedulerId)
     {
        $data = Scheduler::where('id',$schedulerId)->get()->toArray();
        $activityid = $data[0]['activity_id']; 
        $module_id  = $data[0]['module_id'];
        $operation  = $data[0]['operation']; 
        $date_of_execution = $data[0]['date_of_execution'];
        $day_of_execution  = $data[0]['day_of_execution'];
        $time_of_execution = $data[0]['time_of_execution'];
        $comments = $data[0]['comments'];
        $status = $data[0]['status'];
        $updated_by = $data[0]['updated_by'];
        $scheduler_status = $data[0]['scheduler_status'];  
        \Artisan::call('demo:scheduler', ['schedulerId' => $schedulerId,'dayofexecution' =>$day_of_execution ,'timeofexecution' => $time_of_execution]);

        

        
     }


     public function schdulerlogIndex(Request $request)
     {
        $modules = \DB::table('ren_core.modules')->where('patients_service',1)->get();  
        return view('Scheduler::schedulerlog');   
     }

     public function SchedulerlogList(Request $request)
     { 
      
        if($request->ajax()){  
         $activity = sanitizeVariable($request->route('activity'));
         $practicesgrp = sanitizeVariable($request->route('practicesgrp'));
         $practices = sanitizeVariable($request->route('practices'));
         $module_id = sanitizeVariable($request->route('module_id'));
         $startdate  =sanitizeVariable($request->route('startdate'));
         $dateofexecution  =sanitizeVariable($request->route('dateofexecution')); 
         $executionstatus=sanitizeVariable($request->route('executionstatus'));
         $operation = sanitizeVariable($request->route('operation')); 
         $configTZ     = config('app.timezone');
         $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
      
         $scheulerlogdata = SchedulerLogHistory::with('activities','modules','practices','practicegroup');
          
         if($activity!='null'){
            $scheulerlogdata = $scheulerlogdata->where('activity_id',$activity);
         }
         if($practicesgrp!='null'){
            $scheulerlogdata = $scheulerlogdata->where('practice_group',$practicesgrp);
         } 
         if($practices!='null'){
            $scheulerlogdata = $scheulerlogdata->where('practice_id',$practices);
         } 
         if($module_id!='null'){
            $scheulerlogdata = $scheulerlogdata->where('module_id',$module_id);
         }
         if($executionstatus!='null'){
            $scheulerlogdata = $scheulerlogdata->where('execution_status',$executionstatus);   
         }
         if($operation!='null'){
            $operation=='1' ? $operation='add' : $operation='subtract';
            $scheulerlogdata = $scheulerlogdata->where('operation',$operation);     
         }
         if($startdate!='null'){
            $scheulerlogdata = $scheulerlogdata->where('start_date',$startdate);  
         }
         if($dateofexecution=='null' || $dateofexecution==''){ //if null show current month date
            $date=date("Y-m-d");
            $year = date("Y",strtotime($date));
            $month = date("m",strtotime($date));
            $fromdate = $year.'-'.$month.'-01';
            $to_date =  $year.'-'.$month.'-01';                 
            $convertdate = strtotime('-1 second', strtotime('+1 month', strtotime($to_date)));
            $todate=date('Y-m-d', $convertdate);
            $newfromdate =$fromdate." "."00:00:00";   
            $newtodate = $todate ." "."23:59:59"; 
            // $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $newfromdate); 
            // $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $newtodate);
            $scheulerlogdata = $scheulerlogdata->whereBetween('schedulerrecord_date',[$newfromdate, $newtodate]);  
         }
         else{
            
            // $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $dateofexecution);
            // dd("else",$dateofexecution,$dt1);
            $scheulerlogdata = $scheulerlogdata->where('schedulerrecord_date',$dateofexecution);

         } 

         
         
         $scheulerlogdata = $scheulerlogdata->get();       
         return Datatables::of($scheulerlogdata)
         ->addIndexColumn()
         ->make(true); 

        }
        return view('Scheduler::schedulerlog');    

     }
    
   

   

   

   
   
}
