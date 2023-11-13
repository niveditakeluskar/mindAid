<?php

namespace RCare\Org\OrgPackages\QCTemplates\src\Models;
use Illuminate\Database\Eloquent\Model;
use Eloquent;
use RCare\System\Traits\DatesTimezoneConversion;

class QuestionnaireTemplate extends Model
{
    //
    use DatesTimezoneConversion;
    protected $table ='ren_core.questionnaire_templates';


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
        'content_title', 
        'module_id', 
        'component_id', 
        'stage_id', 
        'stage_code', 
        'template_type_id', 
        'question',
        'updated_by',
        'created_by',
        'status',
        'sequence',
        'add_to_patient_status',
        'one_time_entry',
        'display_months',
        'score',
        'tags'
    ];

    public function template(){
        return $this->belongsTo('RCare\Org\OrgPackages\QCTemplates\src\Models\TemplateTypes','template_type_id');
    }

    // public function service(){
    //     return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\Module','module_id');
    // }

    // public function subservice(){
    //     return $this->belongsTo('RCare\Ccm\Models\RcareSubServices','component_id');
    // }

    public function module(){
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\Module','module_id');
    }

    public function components(){
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents','component_id');
    }

    public function users(){
        return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','created_by');
    }

    public function stage(){
        return $this->belongsTo('RCare\Org\OrgPackages\Stages\src\Models\Stage','stage_id');
    }

    public function step(){
        return $this->belongsTo('RCare\Org\OrgPackages\StageCodes\src\Models\StageCode','stage_code');
    }

    // public function moduleComponents(){
    //     return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents','component_id');
    // }

    public static function getQuestionnaire($template_id)
    {
        //echo $template_id;
        return QuestionnaireTemplate::where("template_type_id", $template_id)->get();
    }

}