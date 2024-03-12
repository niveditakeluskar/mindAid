<?php

namespace RCare\RCareAdmin\AdminPackages\Organization\src\Models;

use Illuminate\Database\Eloquent\Model;

class Rcare_Modules extends Model
{
    ////
    //
     protected $table ='rcare_admin.rcare_modules';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
        protected $fillable = [
        'id', 'modules'

    ];



     public function module()

    {
        return $this->belongsTo('App\Models\License', 'org_id');
    }

}
