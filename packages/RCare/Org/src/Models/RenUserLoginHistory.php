<?php

namespace RCare\Org\OrgPackages\Users\src\Models;
use Illuminate\Database\Eloquent\Model;

class _RenUserLoginHistory extends Model
{
    //
     protected $table ='ren_core.user_login_history';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
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

   
}
