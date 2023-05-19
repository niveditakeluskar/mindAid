<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RCare\API\Http\Controllers\caregiverAPIController;
class RefreshTokenAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RefreshToken:API';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Token Refresh for API';

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
      caregiverAPIController::RefreshToken();
     
    }
}
