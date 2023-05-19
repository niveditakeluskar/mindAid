<?php

namespace RCare\Org\OrgPackages\Modules\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;


class ModuleComponents extends Model
{
    //
    use  DatesTimezoneConversion;
    protected $table ='ren_core.module_components';

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
        'module_id', 
        'components', 
        'status',
        'created_by',
        'updated_by',
    ];
    public static function activeComponents()
    {
    //     return ModuleComponents::all()->where("status", 1);
            return ModuleComponents::where("status", 1)->orderBy('components','asc')->get();
    }
    public function module(){
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\Module','module_id');
    }

    public function services(){
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\Module','module_id');
    }

    public function users()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\users\src\Models\users','updated_by');
    }
}
