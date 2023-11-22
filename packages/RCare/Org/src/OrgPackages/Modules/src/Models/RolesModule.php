<?php

namespace RCare\Org\OrgPackages\Modules\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;


class RolesModule extends Model
{
    //
    use  DatesTimezoneConversion;
    protected $table ='ren_core.role_modules';


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
        'role_id', 
        'module_id', 
        'components_id', 
        'crud', 
        'status',
        'created_by',
        'updated_by'
    ];
}