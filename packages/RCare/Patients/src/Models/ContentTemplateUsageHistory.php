<?php
namespace RCare\Patients\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class ContentTemplateUsageHistory extends Model
{
    //

    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='patients.content_template_usage_history';

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
        'patient_id',
        'uid',
        'contact_via', 
        'template_type',
        'module_id', 
        'component_id', 
        'template_id',
        'template', 
        'stage_id',
        'created_by',
        'updated_by'
    ];
}