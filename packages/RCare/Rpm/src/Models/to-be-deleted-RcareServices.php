<?php

namespace RCare\Rpm\Models;
use Illuminate\Database\Eloquent\Model;

class RcareServices extends Model
{
    //
     protected $table ='ren_core.modules';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
        protected $fillable = [
            'id', 'module', 'status'

    ];

}
