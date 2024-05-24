<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use RCare\API\Http\Controllers\ECGAPIController;

class OtherAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rpm:otheralerts';

    /**
     * The console command description.
     *
     * @var string 
     */
    protected $description = 'Rpm Observation Other Alerts'; 
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
        //created and modified by ashvini 22nd nov 2021
        \Log::info("i am calling log info");
        $controller = new ECGAPIController();
        $controller->otherAlerts();
        
        
    }
}
