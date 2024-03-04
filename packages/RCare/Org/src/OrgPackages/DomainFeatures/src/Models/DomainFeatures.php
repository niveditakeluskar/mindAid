<?php

namespace RCare\Org\OrgPackages\DomainFeatures\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Session, DB;

// class Users extends GeneratedFillableModel 
class DomainFeatures extends Model
{ 
      use DashboardFetchable, ModelMapper,  DatesTimezoneConversion;

    protected $table ='ren_core.domain_features';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'id',
        'url', 
        'features',
        'password_attempts',
        'digit_in_otp',
        'otp_max_attempts',
        'otp_text',
        'otp_email',
        'status',
        'no_of_days', 
        'session_timeout',
        'created_by',
        'updated_by',
        'logoutpoptime',
        'idle_time_redirect',
        'block_time', 
        'instance'
    ];
    
    public function users()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\users\src\Models\users','updated_by');
    }

    public static function self($id)
    {   $id = sanitizeVariable($id);
        return self::where('id', $id)->first();
    }

    public static function getSessionLogoutTimeWithPopupTime() 
    {
        $url = url('/');
        // return self::select('session_timeout', 'logoutpoptime','url')->where('status',1)->first();  
        return self::select('session_timeout', 'logoutpoptime','url')->where('status',1)->where('url', 'like', '%' . $url . '%')->first();  
    }

}