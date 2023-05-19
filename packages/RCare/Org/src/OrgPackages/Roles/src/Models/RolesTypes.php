<?php

namespace RCare\Org\OrgPackages\Roles\src\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
// use RCare\System\Traits\DatesTimezoneConversion;

use Carbon\Carbon;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Database\Eloquent\Model; 
use Session, DB;

class RolesTypes extends Model
{
    use DashboardFetchable, ModelMapper,  DatesTimezoneConversion;
    protected $table ='ren_core.role_type';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $population_include = [
        "id"
    ];
    
    
    public $timestamps = true;
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'id', 
        'role_type', 
        'status', 
        'level',
        'created_by',
        'updated_by'		
    ];

    public static function activeRole()
    {
        return RolesTypes::all()->where("status", 1);
    }

    /**
    * Get the Users Role Type
    *
    * @return array
    */
    public static function userRoleType($roleId)
    {
        $roledetails = DB::select(DB::raw("select r.role_name , rt.role_type
                                from ren_core.roles r 
                                left join ren_core.role_types rt on r.role_type = rt.id
								where r.id = $roleId
                                "));
		return $roledetails;	
								
    }
}