<?php

namespace  RCare\Rpm\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;

class PatientProvider extends Model
{
    //
    use DashboardFetchable, ModelMapper;
    protected $table ='patients.patient_providers';


    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
	 
    protected $population_include = [
        "id"
    ];
    
    protected $fillable = [
        'patient_id',
        'provider_id',
        'provider_subtype_id',
        'practice_id',
        'address',
        'phone_no',
        'last_visit_date',
        'created_by',
        'review',
        'provider_name' 
    ];
}
  