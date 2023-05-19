<?php

namespace RCare\Org\OrgPackages\Users\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class Compliance extends Model
{
    //
    use DatesTimezoneConversion;
    protected $table ='ren_core.compliance';


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
        'condition', 
        'Range',
        'lower',
        'higer',
        'notes',
        'created_by',
        'updated_by'
    ];
}
  