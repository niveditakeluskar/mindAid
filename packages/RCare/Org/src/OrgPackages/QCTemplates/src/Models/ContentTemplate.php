<?php

namespace RCare\Org\OrgPackages\QCTemplates\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class ContentTemplate extends Model
{
    //
    use DatesTimezoneConversion;
    protected $table ='ren_core.content_templates';


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
        'content', 
        'created_at', 
        'updated_at', 
        'created_by', 
        'updated_by', 
        'status', 
        'device_id'
    ];

    public function template(){
        return $this->belongsTo('RCare\Ccm\Models\Template','template_type_id');
    }

    public function service(){
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\Module','module_id');
    }

    public function subservice(){
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents','component_id');
    }

    public function users(){
        return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }

    public static function getContent($template_id)
    {
        return ContentTemplate::where("template_type_id", $template_id)->where("status", 1)->orderBy('id','DESC')->get();
    }

    public static function getContentScripts($module_id, $submodule_id, $stage_id, $step_id,$device_id) 
    {
        

        if($device_id=="" || $device_id=='' || $device_id==null)
        {
            $device_id = null;
        }
        if(isset($step_id) && $step_id != "" && $step_id != null) {
            return ContentTemplate::where("module_id", $module_id)
            ->where("component_id", $submodule_id)
            ->where("stage_id", $stage_id)
            ->where("stage_code", $step_id)
            ->where("device_id",$device_id)
            ->where("status", 1)
            ->orderBy('id','ASC')->get();
        } else {
            return ContentTemplate::where("module_id", $module_id)
            ->where("component_id", $submodule_id)
            ->where("stage_id", $stage_id)
            ->where("device_id",$device_id)
            ->where("status", 1)
            ->orderBy('id','ASC')->get();
        }


    }
    public function stage(){
        return $this->belongsTo('RCare\Org\OrgPackages\Stages\src\Models\Stage','stage_id');
    }

    public function step(){
        return $this->belongsTo('RCare\Org\OrgPackages\StageCodes\src\Models\StageCode','stage_code');
    }

}
