<?php

namespace RCare\Rpm\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;

class PatientAddress extends Model
{
    //
    use DashboardFetchable, ModelMapper;
    protected $table ='patients.patient_addresss';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $population_include = [
        "id"
    ];
    
    protected $fillable = [
        'patient_id','add_1','add_2','state','zipcode','city'
    ];
}