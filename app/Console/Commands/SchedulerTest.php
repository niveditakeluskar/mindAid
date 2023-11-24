<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RCare\Org\OrgPackages\Scheduler\src\Models\Scheduler; 
use RCare\Org\OrgPackages\Scheduler\src\Models\SchedulerLogHistory;
use RCare\Org\OrgPackages\Activity\src\Models\Activity;
use RCare\Org\OrgPackages\Activity\src\Models\PracticeActivity; 
use RCare\System\Traits\DatesTimezoneConversion; 
use Illuminate\Support\Facades\Log;

class SchedulerTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:scheduler {schedulerId}';

    /**
     * The console command description.
     *
     * @var string 
     */
    protected $description = 'Add-deduct time for all the patients in patient-time-record of a particular activity practices for the given schedulerid  '; 
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    { 		   
        //created and modified by ashvini 24thmarch2021
        $schedulerId = $this->argument('schedulerId');
        $data = Scheduler::where('id',$schedulerId)->get()->toArray();    
        $activityid = $data[0]['activity_id'];
        $operation  = $data[0]['operation']; 
        $date_of_execution = $data[0]['date_of_execution'];
        $day_of_execution  = $data[0]['day_of_execution'];
        $time_of_execution = $data[0]['time_of_execution'];  
        $comments = $data[0]['comments'];
        $module_id  = 3;//$data[0]['module_id'];
        $componentid = 0;
        $paramdata    = PracticeActivity::where('activity_id',$activityid)->where('status',1)->distinct('practice_id')->pluck('practice_id')->toArray(); 
		
		 \Log::info("practice ".print_r($paramdata, true));
        foreach($paramdata as $key=>$p)     
        { 
          $practicegrp = \DB::table('ren_core.practices')->where('id',$p)->where('is_active',1)->value('practice_group');
          $patientscount = \DB::table('patients.patient_providers')->where('practice_id',$p)->where('is_active',1)->where('provider_type_id',1)->count();   
          $practicetime = PracticeActivity::where('activity_id',$activityid)->where('status',1)->where('practice_id',$p)->value('time_required');
          $currentdatetimeforschedulerrecorddate =   date("Y-m-d h:i:s");
          $schedulerrecorddate = DatesTimezoneConversion::userToConfigTimeStamp($currentdatetimeforschedulerrecorddate); 
          $scharray = array('scheduler_id'       =>  $schedulerId,  
                          'activity_id'          =>  $activityid,
                          'module_id'            =>  $module_id ,
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
		  
		  \Log::info("schedule command executed"); 
		  \Log::info("schedule time status ".$insertscheduler); 
         
          try{      
            // $insert =\DB::select('SELECT patients.insertscheduler111222(?,?,?,?,?,?,?,?) AS result', [$schedulerId,$day_of_execution,$time_of_execution,$p,$practicegrp,$activityid,$created_at,$updated_at]); 
            $operation=='add' ? $convertedoperation=1 : $convertedoperation=0;           
            $insert =\DB::select('SELECT patients.sp_scheduleradditionaltime(?,?,?,?,?,?,?,?,?,?) AS result', 
                      [$practicegrp,$p,$module_id,$componentid,$practicetime,$comments,$schedulerId,$activityid,$schedulerlogrunid,$convertedoperation]); 
                       
                      $schupdatearray = array('execution_status'=>  1, 'patients_count'=> $patientscount);    
                      $updatescheduler = SchedulerLogHistory::where('id',$schedulerlogrunid)->update($schupdatearray);          

            
           \Log::info("schedule history section"); 
            
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
}
