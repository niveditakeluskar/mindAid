<?php

namespace RCare\Rpm\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;

class PatientPoa extends Model
{
    //
    use DashboardFetchable, ModelMapper;
    protected $table ='patients.patient_poa';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $population_include = [
        "id"
    ];
    
    protected $fillable = [
        'patient_id','first_name','last_name','age','relationship', 'mobile', 'phone_2', 'email'
    ];
}