<?php

namespace RCare\Patients\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class View_Patient_Diagnosis extends Model 
{   
     use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='patients.view_patient_diagnosis';


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
        'updated_at',
		'update_date',
		'review_date',
       
    ];

        protected $fillable = [
        'id', 
        'patient_id', 
        'review_date',
        'update_date', 
        'now',  
        'review_age',  
        'review_year_age', 
        'review_month_age',
        'review_days_age',
        'update_age',
        'update_month_age',
        'update_days_age'
        

    ];  



}
