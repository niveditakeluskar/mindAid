<?php

namespace RCare\Org\OrgPackages\Users\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Carbon\Carbon;

class RenUserLoginHistory extends Model
{ 
    //
     use  DatesTimezoneConversion;
     protected $table ='ren_core.user_login_history';
     public $timestamps = TRUE;


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $dates = [ 
        'created_at',
        'updated_at',
        'login_time',
        'logout_time'  
         
    ];
	 
        protected $fillable = [ 
        'id',
        'login_time',
        'logout_time',
        'userid',
        'user_email',
        'mac_address',
        'ip_address',
        'login_attempt_status',
        'created_by',
        'updated_by',  

    ];

    public function newloginusers() 
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','userid');
    }

   
}
