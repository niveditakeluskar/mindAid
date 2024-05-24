<?php

namespace RCare\Patients\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;
class PatientQuestionnaire extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='patients.patient_questionnaire';

        /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $population_include = [
        "id"
    ];
    
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    
    protected $fillable = [
        'patient_id',
        'questionnaire'
    ];

   
} 