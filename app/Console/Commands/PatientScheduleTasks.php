<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// use RCare\API\Http\Controllers\ECGAPIController;
use RCare\Patients\Http\Controllers\PatientWorklistController;
use RCare\Rpm\Http\Controllers\RpmApiController;
class PatientScheduleTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patient:patientscheduletasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create tasks in task_management.todo_list table based on patient score';

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
         $controller->createTaskList_sp();  
     
     
    }
}
