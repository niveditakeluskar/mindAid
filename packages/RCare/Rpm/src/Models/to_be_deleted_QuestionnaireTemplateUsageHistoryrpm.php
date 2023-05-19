<?php

namespace RCare\Rpm\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionnaireTemplateUsageHistoryrpm extends Model
{
    //
    protected $table ='rpm.questionnaire_template_usage_history';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
         
     protected $fillable = [
       
        'id', 
        'template_type',
        // 'UID', 
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
