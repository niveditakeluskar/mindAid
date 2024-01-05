<?php

namespace RCare\Org\OrgPackages\DeactivationReasons\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;


class DeactivationReasons extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='ren_core.deactivation_reasons';
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
        'reasons', 
        'created_by',
        'updated_by',
        'status'
    ];  

    public static function self($id)
    {   $id = sanitizeVariable($id);
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }

    public static function activeReasons(){ 
        $reasons= DeactivationReasons::where("status", 1)->orderBy('reasons','asc')->get();
        return $reasons;
    }
    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    } 
}