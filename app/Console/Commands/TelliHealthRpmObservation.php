<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use RCare\API\Http\Controllers\TellihealthAPIController;
class TelliHealthRpmObservation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tellihealth:telliHealthRpmObservation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insert data into patient_devices table & Observations Table';

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
         \Log::info('before Handle Tellihealth');
         $controller = new TellihealthAPIController();
         $controller->process_webhook_observation();
         \Log::info('after Handle Tellihealth');
     
     
    }
}
