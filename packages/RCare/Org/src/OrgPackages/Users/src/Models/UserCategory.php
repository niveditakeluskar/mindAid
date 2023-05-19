<?php

namespace RCare\Org\OrgPackages\Users\src\Models;
use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{
    //
     protected $table ='ren_core.user_category';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
        protected $fillable = [
        'id', 'description'

    ];
}
