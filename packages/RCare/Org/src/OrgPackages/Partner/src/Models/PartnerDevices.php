<?php

namespace RCare\Org\OrgPackages\Partner\src\Models; 
use Illuminate\Database\Eloquent\Model;   
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\Patients\Models\PatientMedication;
use RCare\System\Traits\DatesTimezoneConversion;

class PartnerDevices extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='ren_core.partner_devices_listing';

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
    public $timestamps = true;
    
    protected $fillable = [
        'id',
        'partner_id',
        'device_name_api',
        'device_id',
        'device_attr',
        'status',
        'created_by',
        'updated_by',
        'device_order_seq'
    ];

  
	
	public static function self($id)
    {   $id  = sanitizeVariable($id);
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }

    public static function activePartnerDevices()
    {   
        return self::where('status', 1)->orderBy('created_at', 'desc')->first();
    }


    // public function users()
    // {
    //      return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    // }
}