<?php

namespace RCare\Org\OrgPackages\Users\src\Models; 
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use RCare\System\Traits\DatesTimezoneConversion;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Session, DB;

class UserFilters extends Model
{
    //
    use DashboardFetchable, ModelMapper,  DatesTimezoneConversion;
    protected $table ='ren_core.user_filters';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'id', 
        'user_id', 
        'module_id',
        'submodule_id', 
        'filters' 
    ];

    /**
     * Get the users that are assigned to this practice
     *
     * @return array
     */
    
}