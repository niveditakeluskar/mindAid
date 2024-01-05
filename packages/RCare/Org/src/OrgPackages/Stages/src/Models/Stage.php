<?php

namespace RCare\Org\OrgPackages\Stages\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;

class Stage extends GeneratedFillableModel
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    // protected $table ='rpm.stage';

     protected $table ='ren_core.stage';
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
        'description',
        'module_id',
        'submodule_id',
        'status',
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
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents', 'submodule_id');
    }

    // public function mnu()
    // {
    //     return $this->belongsTo('RCare\Org\OrgPackages\Menus\src\Models\OrgMenus', 'parent')->withDefault(['menu'=>'none']);
    // }
    
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
}