<?php

namespace RCare\Org\OrgPackages\RPMBillingConfiguration\src\Models;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use Carbon\Carbon;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Database\Eloquent\Model;
use Session, DB;

// class Offices extends GeneratedFillableModel
class RPMBilling extends Model
{
    use DashboardFetchable, ModelMapper,  DatesTimezoneConversion;
	protected $table ='rpm.rpm_billing_configuration';
	 
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
        'vital_review_time',
        'required_billing_days',
        'billing_phone',
        'billing_fname',
        'billing_lname',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_zip',
        'billing_email',
        // 'device_phone',
        // 'device_fname',
        // 'device_lname',
        // 'device_address',
        // 'device_city',
        // 'device_state',
        // 'device_zip',
        // 'device_email',
        'headquaters_phone',
        'headquaters_fname',
        'headquaters_lname',
        'headquaters_address',
        'headquaters_city',
        'headquaters_state',
        'headquaters_zip',
        'headquaters_email'
    ];

    public static function self()
    {
        return self::orderBy('created_at', 'desc')->first();
    }
    // public static function activeRPMBilling()
    // { 
    //     return self::get();
    // }

    // public static function self($id)
    // {   $id = sanitizeVariable($id); 
    //     return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    // }

    // public function rcareOrgs() {
    //     return $this->belongsTo('RCare\RCareAdmin\AdminPackages\Organization\src\Models\RcareOrgs', 'Office_id');
    // }

    // public function users()
    // {
    //      return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    // }

    
   
}