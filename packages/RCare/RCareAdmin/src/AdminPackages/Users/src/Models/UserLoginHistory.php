<?php

namespace RCare\RCareAdmin\AdminPackages\Users\src\Models;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
// use RCare\System\Support\ModelMapper;
use Carbon\Carbon;

class UserLoginHistory extends Model 
{
    //
    use  DatesTimezoneConversion;
     protected $table ='rcare_admin.rcare_users_login_history';
     public $timestamps = TRUE;
    

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     * 
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

    public function users(){
        return $this->belongsTo('App\Models\User','userid');
    }
   
}
