<?php

namespace RCare\Org\OrgPackages\Roles\src\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
// use RCare\System\Traits\DatesTimezoneConversion;

use Carbon\Carbon;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Database\Eloquent\Model; 
class Roles extends Model
{
    use DashboardFetchable, ModelMapper,  DatesTimezoneConversion;
    protected $table ='ren_core.roles';

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
        'role_name', 
        'status', 
        'level',
        'created_by',
        'updated_by',
		'role_type'
    ];

    public static function activeRole()
    {
        return Roles::all()->where("status", 1);
    }

    /**
    * Get the Users that are assigned to this Role
    *
    * @return array
    */
    public function users()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }
}