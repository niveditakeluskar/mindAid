<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// use RCare\API\Http\Controllers\ECGAPIController;
use RCare\Patients\Http\Controllers\PatientWorklistController;
use RCare\Rpm\Http\Controllers\RpmApiController;
class PatientReScheduleTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patient:patientrescheduletasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reschedule tasks in task_management.todo_list tablefor current month for patients nearing 20 mins';

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
         $controller = new PatientWorklistController();
         $controller->reschdule_tasks_sp();  
     
     
    }
}
