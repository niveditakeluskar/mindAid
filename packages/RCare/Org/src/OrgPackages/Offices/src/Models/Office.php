<?php

namespace RCare\Org\OrgPackages\Offices\src\Models;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use Carbon\Carbon;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Database\Eloquent\Model;
use Session, DB;

// class Offices extends GeneratedFillableModel
class Office extends Model
{
    use DashboardFetchable, ModelMapper,  DatesTimezoneConversion;
	protected $table ='ren_core.office';
	 
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
        'location',
        'address',
        'phone',
        'status',
        'created_by',
        'updated_by'
    ];

    public static function activeOffice()
    {
        // return Users::all()->where("status", 1);
        return Office::where("status", 1)->orderBy('location','asc')->get();
    }

    public static function self($id)
    {   $id = sanitizeVariable($id); 
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }

    public function rcareOrgs() {
        return $this->belongsTo('RCare\RCareAdmin\AdminPackages\Organization\src\Models\RcareOrgs', 'Office_id');
    }

    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }

    
   
}