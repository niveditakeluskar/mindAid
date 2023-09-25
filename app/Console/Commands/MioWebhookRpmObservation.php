<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use RCare\API\Http\Controllers\MioDeviceController;
class MioWebhookRpmObservation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'miowebhook:miowebhookRpmObservation';

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
         \Log::info('before Handle miowebhook');
         $controller = new MioDeviceController();
         $controller->process_mio_webhook_observation();
         \Log::info('after Handle miowebhook');
     
     
    }
}
