<?php

namespace RCare\Rpm\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;

class PatientServices extends Model
{
    //
    use DashboardFetchable, ModelMapper;
    protected $table ='patients.patient_services';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $population_include = [
        "id"
    ];
    
    protected $fillable = [
        'patient_id','module_id', 'uid', 'date_enrolled','date_expired','created_by', 'updated_by'
    ];
}