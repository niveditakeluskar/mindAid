<?php

namespace RCare\Rpm\Models;
use Illuminate\Database\Eloquent\Model;

class PatientTimeRecordPatients extends Model
{
    //
     protected $table ='patients.patient_time_records';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
        protected $fillable = [
        'id', 'UID', 'record_date', 'module_id', 'component_id', 'stage_id', 'timer_on', 'timer_off', 'net_time', 'billable'

    ];

}