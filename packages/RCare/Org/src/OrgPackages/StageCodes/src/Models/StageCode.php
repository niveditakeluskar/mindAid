<?php

namespace RCare\Org\OrgPackages\StageCodes\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;

class StageCode extends GeneratedFillableModel
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='ren_core.stage_codes'; 

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
        'stage_id',
        'description',
        'sequence',
        'created_by',
        'updated_by',
        'status',
        'module_id',
        'submodule_id'
    ];

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

    public function stage()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Stages\src\Models\Stage', 'stage_id');
    }
	
	public function module()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\Module', 'module_id')->withDefault(['module'=>'none']);
    }

    public function components()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents', 'submodule_id');
    }

    public static function activeStageCode()
    {
       // return StageCode::all()->where("status", 1);
        return StageCode::where("status", 1)->orderBy('description','asc')->get();

    }
    public static function generalStageCode($mid,$sid)
    {
        return StageCode::where('module_id',$mid)->where("status", 1)->where("stage_id", $sid)->orderBy('sequence', 'ASC')->get();
    }
    
    public function users()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\users\src\Models\users','updated_by');
    }
}