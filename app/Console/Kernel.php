<?php
 
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use RCare\Org\OrgPackages\Scheduler\src\Models\Scheduler;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
     //   Commands\RefreshTokenAPI::class, 
        // Commands\DemoScheduler::class,
		Commands\PatientReScheduleTasks::class,
        Commands\PatientScheduleTasks::class,
        Commands\SchedulerTest::class,
        Commands\RPMObservation::class,
        Commands\UpdateOrder::class,
        Commands\UpdateWebhookAlert::class,
        Commands\UpdateWebhookOrder::class,        
        Commands\OtherAlerts::class,
		Commands\PatientCarePlanAge::class,
		Commands\TelliHealthRpmObservation::class,
		Commands\MFAStatus::class,
		Commands\RPMReadingReminder::class,
		Commands\GenerateScore::class,
		Commands\MioWebhookRpmObservation::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
	
	/*
	protected $middleware = [
        \App\Http\Middleware\XFrameHeadersMiddleware::class,
        \App\Http\Middleware\SecureHeaders::class,
    ];
	*/
	
	

     
    protected function schedule(Schedule $schedule)  
    {
        //created and modified by ashvini 06th april2021
        
        $currentdate = date("Y-m-d");
		$currenttime = date("h:i:s");
       // $schedulerdataid = Scheduler::where('scheduler_status',1)->select('id','date_of_execution','time_of_execution','day_of_execution')->get()->toArray(); 
	   
	   $schedulerdataid = Scheduler::leftJoin('ren_core.reports_master', function($join) {
                                        $join->on('scheduler.report_id', '=', 'ren_core.reports_master.id');
                                        })
                                        ->where('scheduler_status',1)
                                        ->first([
                                                'scheduler.id','date_of_execution','time_of_execution','day_of_execution',
                                                'week_of_execution','month_of_execution','year_of_execution', 'scheduler_date',
                                                'frequency', 'scheduler_type', 'ren_core.reports_master.report_file_path'
                                        ])->get()->toArray();
		
        foreach($schedulerdataid as $s) 
         { 
         
             $id = $s['id']; 
             $execdate = $s['date_of_execution'];
        
             if($execdate==null){   
             }else{
                $newexecdate = explode("-",$execdate);
                $executionstartdate = $newexecdate[2];    
                $exectime = $s['time_of_execution'];
                $time1 = explode(":",$exectime);
                $exectiontime = $time1[0].":".$time1[1];   
                $executionday = $s['day_of_execution']; 
                $scheduler_date = $s['scheduler_date']; 
				
                $scheduler_type = $s['scheduler_type']; 
                $frequency = $s['frequency']; 
                $report_file_path = $s['report_file_path'];
                $executionweek =$s['week_of_execution']; 
                $executionmonth =$s['month_of_execution'];
                //$executionyear =$s['year_of_execution'];
                 
                if($currentdate >= $execdate) 
                {
                    if($scheduler_type == 'patient_time'){
                        try{
                            // print_r('test:scheduler '.$executionday.' '.$exectiontime);	
                            print_r('scheduler procedure called in kernel '.$executionday.' '.$exectiontime); 
                            $schedule->command('test:scheduler',[$id])->monthlyOn($executionday,$exectiontime);
                        }
                        catch(\Exception $e){    
                        // \Log::info("exception catched");
                        // \Log::info($e->getMessage());
                        
                        }

                    }
                    else if ($scheduler_type == 'report_scheduler'){  
						print_r($frequency);
						$sdatetime = explode(" ", $scheduler_date);						
						$sdate = explode("-", $sdatetime[0]);
						$stime = explode(":", $sdatetime[1]);						
						$rsexectiontime = $stime[0].":".$stime[1]; 
						$rsexecutionday = $sdate[1];				
                        if($frequency == 'monthly'){
                        try{
                            //added by ashvini on 9Th aug 2022
                            $report_file_path_array = explode(".", $report_file_path);                            
                            $new_report_file_path_array = $report_file_path_array[0].'monthly.py';			
                            print_r('python3 '.$new_report_file_path_array.' '.$rsexecutionday.' '.$exectiontime);					
                            $schedule->exec('python3 '.$new_report_file_path_array)->monthlyOn($rsexecutionday,$exectiontime);
                        }
                          catch(\Exception $e){    
                            // \Log::info("exception catched");
                            // \Log::info($e->getMessage());
                           
                          }   


                        }
                        if($frequency == 'daily'){
                            //added by ashvini on 9Th aug 2022
                        try{
                            $report_file_path_array = explode(".", $report_file_path);                            
                            $new_report_file_path_array = $report_file_path_array[0].'daily.py';	
                            print_r('python3 daily '.$new_report_file_path_array.' '.$exectiontime);						
                            $schedule->exec('python3 '.$new_report_file_path_array)->dailyAt($exectiontime);
                        }
                        catch(\Exception $e){    
                          // \Log::info("exception catched");
                          // \Log::info($e->getMessage());
                         
                        }
                        }
                         if($frequency == 'weekly'){
                            
                        try{
                             //added by ashvini on 9Th aug 2022
                             $report_file_path_array = explode(".", $report_file_path);                            
                             $new_report_file_path_array = $report_file_path_array[0].'weekly.py';
                             print_r('weekly python3 '.$new_report_file_path_array.' '.$executionweek.''.$exectiontime); 	
                             $schedule->exec('python3 '.$new_report_file_path_array)->weeklyOn($executionweek,$exectiontime);
                        }
                        catch(\Exception $e){    
                          // \Log::info("exception catched");
                          // \Log::info($e->getMessage());
                         
                        }

                        }
                        if($frequency == 'yearly'){	
                        try{
                             //added by ashvini on 9Th aug 2022
                             $report_file_path_array = explode(".", $report_file_path);                            
                             $new_report_file_path_array = $report_file_path_array[0].'yearly.py';   
                             print_r('python3 yearly '.$new_report_file_path_array.' '.$executionmonth.' '.$executionday.' '.$exectiontime);		
                             $schedule->exec('python3 '.$new_report_file_path_array)->yearlyOn($executionmonth, $executionday,$exectiontime);
                        }
                        catch(\Exception $e){    
                          // \Log::info("exception catched");
                          // \Log::info($e->getMessage());
                         
                        }

                        }
                    }
                } 
             }
             
             // $schedule->command('test:scheduler',[$id])->monthlyOn($executionstartdate,$exectiontime);
             // $schedule->command('inspire')
             //          ->hourly();
             
         }
		
		$schedule->command('patient:patientscheduletasks')->monthlyOn('02','05:00'); 
        $schedule->command('patient:patientrescheduletasks')->dailyAt('10:15'); 
	    $schedule->command('patient:patientmarktaskscompleted')->dailyAt('11:37');
	   
        $schedule->command('test:message')->everyFiveMinutes();
        $schedule->command('rpm:obseravtion')->everyFiveMinutes(); 
        $schedule->command('rpm:observation')->everyFiveMinutes(); 
        $schedule->command('API:UpdateOrder')->everyFiveMinutes();  
        //$schedule->command('webhook:alert')->everyFiveMinutes(); 
        $schedule->command('webhook:order')->hourly(); 
        $schedule->command('rpm:ecgialert')->daily();
        $schedule->command('rpm:otheralerts')->daily();  
		$schedule->command('patient:careplan')->everyMinute();
		$schedule->command('tellihealth:telliHealthRpmObservation')->everyFiveMinutes();
		$schedule->command('MFA:message')->everyMinute();
		$schedule->command('RPMReadingReminder:noReadings')->dailyAt('19:00');
		$schedule->command('miowebhook:miowebhookRpmObservation')->everyMinute();
		//$schedule->command('patient:generatescore')->monthlyOn(1,'08:00'); --//3.00 am CST and utc ka 8 am
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}


