<?php

namespace RCare\Org\OrgPackages\HealthServices\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;

class HealthServices extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='ren_core.health_services';

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
        'type',
        'description',
        'module_id',
        'created_by',
        'updated_by',
        'status',
        'alias',
    ];

    public function PatientHealthServices()
    {
        return $this->hasMany('RCare\Patients\Models\PatientHealthServices','id','hid');
    }

    // public function module()
    // {
    //     return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\Module', 'module_id')->withDefault(['module'=>'none']);
    // }

    // public function components()
    // {
    //     return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents', 'component_id');
    // }

    // public function mnu()
    // {
    //     return $this->belongsTo('RCare\Org\OrgPackages\Menus\src\Models\OrgMenus', 'parent')->withDefault(['menu'=>'none']);
    // }

    // /**
    //  * Generate some fillable attributes for the model
    //  */
    // public function generateFillables()
    // {
    //     return $this->fillable;
    // }
    
    // public function populationRelations()
    // {
    //     return [];
    // }
}