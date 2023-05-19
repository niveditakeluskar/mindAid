<?php

namespace RCare\Org\OrgPackages\Menus\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;

class OrgMenus extends GeneratedFillableModel
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='ren_core.menu_master';

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
        'menu',
        'menu_url',
        'module_id',
        'component_id',
        'icon',
        'parent',
        'status',
        'sequence',
        'operation',
        'created_by',
        'updated_by'
    ];

    public function module()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\Module', 'module_id')->withDefault(['module'=>'none']);
    }

    public function components()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents', 'component_id');
    }

    public function mnu()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Menus\src\Models\OrgMenus', 'parent')->withDefault(['menu'=>'none']);
    }
    public function users()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\users\src\Models\users','updated_by');
    }

    /**
     * Generate some fillable attributes for the model
     */
    public function generateFillables()
    {
        return $this->fillable;
    }
    
    public function populationRelations()
    {
        return [];
    }

     public static function activeMenu()
    {
        return OrgMenus::where("status", 1)->orderBy('menu','asc')->get();
    }
}