<?php

namespace RCare\Ccm\Models;

namespace RCare\Ccm\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Database\Eloquent\Model;

class ContentTemplateUsageHistory extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    
    //
    protected $table ='ccm.content_templates_usage_history';

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
		'patient_id',
        'module_id', 
        'component_id', 
        'template_id',
        'template', 
        'stage_id',
        'created_by'
    ];
}
