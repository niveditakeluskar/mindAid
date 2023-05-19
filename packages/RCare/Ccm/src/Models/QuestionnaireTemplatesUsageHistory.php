<?php

namespace RCare\Ccm\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class QuestionnaireTemplatesUsageHistory extends Model
{

    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='ccm.questionnaire_templates_usage_history';
    /**
     * The attributes that are mass assignable.
     *s
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
        'module_id', 
        'template_type', 
        'uid', 
        'patient_id',
        'component_id', 
        'template_id', 
        'template', 
        'created_at',    
        'updated_at', 
        'created_by', 
        'modified_by',
        'stage_id',
        'stage_code',
        'monthly_notes',
        'step_id'
    ];

    
}