<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RCare\API\Http\Controllers\ECGAPIController;
use RCare\Rpm\Http\Controllers\RpmApiController;
class UpdateWebhookOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Webhook Order';

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
         $controller = new ECGAPIController();
        $controller->saveOrderLog();
     
     
    }
}
