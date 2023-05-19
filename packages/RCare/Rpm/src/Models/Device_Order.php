<?php

namespace RCare\Rpm\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Support\Facades\DB;
class Device_Order extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='rpm.device_order';

     protected $population_include = [
        "id"
    ];
  
  protected $dates = [
        'created_at',
        'updated_at'        
    ];
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
      'id',
      'devices',
      'neck_pendant',
      'group_code',
      'action_plan',
      'userid',
      'cmid',
      'cmfname',
      'cmlastname',
      'cmphone',
      'cmaddress',
      'cmcity',
      'cmzip',
      'cmstate',
      'cmemail',
      'pfname',
      'plastname',
      'pphone',
      'paddress',
      'pcity',
      'pzip',
      'pstate',
      'pemail',
      'billingname',
      'billinglastname',
      'billingphone',
      'billingaddress',
      'billingcustcity',
      'billingstate',
      'billingzip',
      'billingemail',
      'shippingname',
      'shippinglastname',
      'shippingphone',
      'shippingaddress',
      'shippingcustcity',
      'shippingzip',
      'shippingstate',
      'shippingemail',
      'shippingoption',
      'dob',
      'timezone',
      'sms_notification',
      'doctor_name',
      'doctor_phone',
      'Pref_hosp',
      'gender',
      'blood_Type',
      'mrn',
      'office_loc',
      'provider',
      'height_feet',
      'height-inches',
      'weight',
      'respname',
      'resplastname',
      'respphone',
      'respaddress',
      'respcustcity',
      'respzip',
      'respstate',
      'respemail',
      'relationship',
      'sourceid',
      'orderid',
      'med_reminder',      
      'partner_id',
      'created_by',
      'updated_by',
      'created_at',
      'updated_at',
      'system_id',
      'order_id',
      'device_code',
      'device_status',
      'order_date',
      'active_date',      
      'office_provider',
      'office_id',
      'tracking_num',
      'date_shipped',
      'fetch_status',
      'practice_id',
      'provider_id',
      'device_id'
      ];
      
      public static function self($id)
    {   $id = sanitizeVariable($id);
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }

}
