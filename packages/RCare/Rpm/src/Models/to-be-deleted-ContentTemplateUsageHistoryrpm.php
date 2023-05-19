<?php

namespace RCare\Rpm\Models;

use Illuminate\Database\Eloquent\Model;

class ContentTemplateUsageHistoryrpm extends Model
{
    //
    protected $table ='rpm.content_template_usage_history';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
         
     protected $fillable = [
        'id',
        'contact_via', 
        'template_type',
        'UID',  
        'module_id', 
        'component_id', 
        'template_id',
        'template', 
        'stage_id',
        'created_by'
    ];
}
