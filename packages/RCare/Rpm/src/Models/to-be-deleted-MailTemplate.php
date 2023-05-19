<?php

namespace RCare\Rpm\Models;
use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
    //
     protected $table ='rpm.content_templates';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
        protected $fillable = [
        'id', 'content_title', 'module_id', 'component_id', 'stage_id', 'stage_code', 'template_type_id', 'content', 'created_at', 
        'updated_at', 'created_by', 'modified_by', 'status', 'device_id'

    ];

    public function template(){
        return $this->belongsTo('RCare\Rpm\Models\Template','template_type_id');
    }
    public function service(){
        return $this->belongsTo('RCare\Rpm\Models\RcareServices','module_id');
    }
    public function subservice(){
        return $this->belongsTo('RCare\Rpm\Models\RcareSubServices','component_id');
    }
    public function module_components(){
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents','component_id');
    }

    public static function getContent($template_id)
    {
        //echo $template_id;
        return MailTemplate::where("template_type_id", $template_id)->get();
    }

}
