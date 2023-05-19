<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RCare\API\Http\Controllers\caregiverAPIController;
use RCare\Rpm\Http\Controllers\RpmApiController;
class UpdateOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'API:UpdateOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Order from API';

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
         $controller = new RpmApiController();
        $controller->getPlaceOrderDetails();
     
     
    }
}
