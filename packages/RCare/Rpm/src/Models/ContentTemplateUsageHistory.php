<?php

namespace RCare\Rpm\Models;

use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;

class ContentTemplateUsageHistory extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
   // protected $table ='patients.content_template_usage_history';
          protected $table ='rpm.content_template_usage_history';
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
        'id',
        'contact_via', 
        'template_type',
        'uid',  
        'module_id', 
        'component_id', 
        'template_id',
        'template', 
        'stage_id',
        'created_by',
        'updated_by',
        'patient_id'
    ];
}
