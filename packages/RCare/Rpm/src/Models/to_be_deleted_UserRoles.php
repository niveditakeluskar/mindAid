<?php

namespace RCare\Rpm\Models;
use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    //
     protected $table ='ren_core.roles';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
        protected $fillable = [
        'id', 'description', 'status'

    ];
}
