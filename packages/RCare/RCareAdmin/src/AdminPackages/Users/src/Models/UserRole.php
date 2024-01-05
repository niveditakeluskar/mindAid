<?php

namespace RCare\RCareAdmin\AdminPackages\Users\src\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    //
    //
     protected $table ='rcare_admin.rcare_user_roles';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
        protected $fillable = [
        'id', 'user_id', 'role_id'

    ];

}
