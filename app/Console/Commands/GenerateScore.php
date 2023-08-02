<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// use RCare\API\Http\Controllers\ECGAPIController;
use RCare\Patients\Http\Controllers\PatientWorklistController;
use RCare\Rpm\Http\Controllers\RpmApiController;

class GenerateScore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patient:generatescore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insert data into patient_score table';

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
        \Log::info('working inside generate score');
       
        $query = "select * from patients.generate_patient_score()";   
        $data = \DB::select( \DB::raw($query) );

       
       
     
     
    }
}
