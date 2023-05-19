<?php

namespace RCare\Patients\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;
class PatientFirstReview extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='patients.patient_first_review';

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
        'independent_living_at_home',
        'at_home_with_assistance',
        'assisted_living_group_living',
        'other',
        'description',
        'living_with_someone',
        'created_at',
        'updated_at'
    ];

    public static function latest($patientId)
    {
        $patient_id=sanitizeVariable($patientId);
        return self::where('patient_id', $patient_id)->first();
    }


    
} 