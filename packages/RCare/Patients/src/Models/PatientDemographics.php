<?php

namespace RCare\Patients\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class PatientDemographics extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='patients.patient_demographics';

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
        'uid',
        'gender',
        'marital_status',
        'education',
        'ethnicity',
        'height',
        'weight',
        'occupation', 
        'employer', 
        'occupation_description',
        'other_contact_name', 
        'other_contact_relationship', 
        'other_contact_phone_number', 
        'other_contact_email', 
        'military_status', 
        'ethnicity_2',
        'created_by',
        'updated_by',
        'template',
    ];

    public static function latest($patientId)
    {
        $patient_id=sanitizeVariable($patientId);
        return self::where('patient_id', $patient_id)->orderBy('created_at', 'desc')->first();
    }
}

    