<?php

namespace RCare\Ccm\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
    use DashboardFetchable, ModelMapper;
    protected $table ='patients.patient_healthcare_services';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $population_include = [
        "id"
    ];
    
     protected $fillable = [
        'id', 'patient_id', 'specify', 'hid',  'brand',  'type', 'from_whom', 'from_where', 'purpose', 'frequency', 'duration',  'notes', 'created_at', 
        'updated_at', 'created_by', 'updated_by', 'status'

    ];
}