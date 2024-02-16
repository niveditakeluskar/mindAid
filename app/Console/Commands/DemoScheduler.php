<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RCare\Org\OrgPackages\Scheduler\src\Models\Scheduler;  

class DemoScheduler extends Command
{
    /**
     * The name and signature of the console command. 
     *
     * @var string
     */
    protected $signature = 'demo:scheduler {schedulerId} {dayofexecution} {timeofexecution}';
    // protected $signature = 'demo:scheduler'; 

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $schedulerId = $this->argument('schedulerId');
        $day_of_execution = $this->argument('dayofexecution');
        $time_of_execution =  $this->argument('timeofexecution');
        $this->info('Successfully sent daily quote to everyone.'); 
    
        // $schedulerId = 46; 
        // $day_of_execution = 22; 
        // $time_of_execution =  '15:50';     


       
        // $data = Scheduler::where('id',$schedulerId)->get()->toArray();    
        
        // $activityid = $data[0]['activity_id'];   
        // $module_id  = $data[0]['module_id'];
        // $operation  = $data[0]['operation']; 
        // $date_of_execution = $data[0]['date_of_execution'];
        // $day_of_execution  = $data[0]['day_of_execution'];
        // $time_of_execution = $data[0]['time_of_execution'];  
        // $comments = $data[0]['comments'];
        // $status = $data[0]['status'];
        // $updated_by = $data[0]['updated_by'];
        // $scheduler_status = $data[0]['scheduler_status']; 
        $created_at = \Carbon\Carbon::now(); 
        $updated_at = \Carbon\Carbon::now();   
        // $results = \DB::select('call patients.dummyinsertscheduler("'.$schedulerId.'", "'.$day_of_execution.'", "'.$time_of_execution.'")');
        // $results = \DB::select('call patients.dummyinsertscheduler(?, ?, ?)', [$schedulerId,$day_of_execution,$time_of_execution]);
        // \DB::connection('rcaredb_19oct')->select('Select patients.dummyinsertscheduler (?,?,?) as result',array($schedulerId,$day_of_execution,$time_of_execution));
         \DB::select('SELECT patients.insertscheduler(?,?,?,?,?) AS result', [$schedulerId,$day_of_execution,$time_of_execution,$created_at,$updated_at]); 
        
        // return 0;
    }
}
