<?php

namespace RCare\Ccm\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use RCare\System\Traits\DatesTimezoneConversion;

class PatientCareplanCarertool extends Model
{
	// Use Trait
    use DashboardFetchable, ModelMapper,DatesTimezoneConversion;
	
	// custom table
    protected $table ='rcare_history.patient_careplan';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $population_include = [
        "id"
    ];
    /*protected $dates = [
        'created_at',
        'updated_at',
        'v_date'
    ];*/

     protected $fillable = [       
        'patientid', 
		'firstname',
        'lastname',
        'birthday', 
        'practiceID',
        'initialcareplanid',
        'tracking',
        'generalnotes',
        'monthyear',
        'uid',
    ];
	
	 
}
 