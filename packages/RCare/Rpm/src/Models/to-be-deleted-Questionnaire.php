<?php

namespace RCare\Rpm\Models;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    //
     protected $table ='rpm.questionnaire';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
        protected $fillable = [
        'id', 'content_title', 'module_id', 'component_id', 'stage_id', 'stage_code', 'template_type_id', 'question', 'created_at', 
        'updated_at', 'created_by', 'modified_by', 'status'

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
    public function moduleComponents(){
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents','component_id');
    }
}
