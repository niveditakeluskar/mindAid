<?php

namespace RCare\Org\OrgPackages\Users\src\Models;
use Illuminate\Database\Eloquent\Model;

class OrgUserRole extends Model
{
    //
    protected $table ='ren_core.user_roles';


    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
	 
    protected $fillable = [
        'id', 
        'user_id', 
        'role_id'
    ];
}
  