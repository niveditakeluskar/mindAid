<?php

namespace RCare\Ccm\Models;
use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
    //
     protected $table ='ccm.content_templates';


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
        return $this->belongsTo('RCare\Ccm\Models\Template','template_type_id');
    }
    // public function service(){
    //     return $this->belongsTo('RCare\Ccm\Models\RcareServices','module_id');
    // }
    public function service(){
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\Module','module_id');
    }
    public function subservice(){
        return $this->belongsTo('RCare\Ccm\Models\RcareSubServices','component_id');
    }

    public static function getContent($template_id)
    {
        return MailTemplate::where("template_type_id", $template_id)->orderBy('id','ASC')->get();
    }

}
