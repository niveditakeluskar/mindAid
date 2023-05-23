<?php

namespace RCare\Org\OrgPackages\QCTemplates\src\Models;
use Illuminate\Database\Eloquent\Model;
use Eloquent;
use RCare\System\Traits\DatesTimezoneConversion;

class TemplateTypes extends Model
{
    //
    use DatesTimezoneConversion;
    protected $table ='rcare_admin.template';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'id', 
        'template_type'
    ];


}
