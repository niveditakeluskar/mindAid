<?php

namespace RCare\Rpm\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;

class PatientInsurance extends Model
{
    //
    use DashboardFetchable, ModelMapper;
    protected $table ='patients.patient_insurance';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $population_include = [
        "id"
    ];
    
    protected $fillable = [
        'code','ins_id', 'ins_type', 'ins_provider','ins_plan', 'mobile', 'phone_2', 'email', 'patient_id'
    ];
}