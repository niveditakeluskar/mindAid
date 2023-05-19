<?php

namespace RCare\Org\OrgPackages\Responsibility\src\Models;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use Carbon\Carbon;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Database\Eloquent\Model;
use Session, DB;

// class Responsibility extends GeneratedFillableModel
class Responsibility extends Model
{
    use DashboardFetchable, ModelMapper,  DatesTimezoneConversion;
	protected $table ='ren_core.responsibilities';
	 
	// const created_at = null;
	
	
	protected $dates = [
        'created_at',
        'updated_at'
    ];
		
	
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $population_include = [
        "id"
    ];

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'id',
       'responsibility', 
        'module_id', 
        'component_id', 
        'status',
        'created_by',
        'updated_by'
    ];

    public static function activeResponsibility()
    {
        // return Users::all()->where("status", 1);
        return Responsibility::where("status", 1)->orderBy('responsibility','asc')->get();
    }

    public static function self($id)
    {   $id = sanitizeVariable($id); 
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }

    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }

    
   
}