<?php

namespace RCare\Patients\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class PatientPoa extends Model
{
    //
use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='patients.patient_poa';

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
        'poa_first_name',
        'poa_last_name',
        'poa_age',
        'poa_relationship', 
        'poa_mobile', 
        'poa_phone_2', 
        'poa_email', 
        'poa',
        'created_by',
        'updated_by',
    ];
}