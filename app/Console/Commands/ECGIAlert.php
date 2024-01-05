<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use RCare\API\Http\Controllers\ECGAPIController;

class ECGIAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rpm:ecgialert';

    /**
     * The console command description.
     *
     * @var string 
     */
    protected $description = 'Rpm ecgialert'; 
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
        //created and modified by ashvini 10thjune2021

        $controller = new ECGAPIController();
        $controller->updateRPMPatientAlert();
        
        
    }
}
