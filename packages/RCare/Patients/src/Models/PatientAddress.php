<?php

namespace RCare\Patients\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class PatientAddress extends Model
{ 
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='patients.patient_addresss';

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
        'add_1',
        'add_2',
        'state',
        'zipcode',
        'city',
        'created_by',
        'updated_by'
    ];

    public static function latest($patientId)
    {
        
        return self::where('patient_id', sanitizeVariable($patientId))->orderBy('created_at', 'desc')->first();
    }
}