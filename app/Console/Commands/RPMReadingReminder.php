<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RCare\RPM\Http\Controllers\DailyReviewController;

class RPMReadingReminder extends Command
{ 
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RPMReadingReminder:noReadings';

    /**
     * The console command description.
     *
     * @var string 
     */
    protected $description = 'Messaging status update  '; 
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
     
     * @return int
     */
    public function handle()
    {   		
		Log::info("Reminder ");
		$controller = new DailyReviewController();
        $r = $controller->noReadingsLastthreedays();      
		//Log::info("reminder msg outcome".$r);
    }
}
 