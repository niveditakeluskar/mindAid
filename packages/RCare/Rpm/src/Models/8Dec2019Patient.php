<?php

namespace RCare\Rpm\Models;

use Illuminate\Database\Eloquent\Model;

class Dec2019Patient extends Model
{
    //
    protected $table ='patients.patients';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
     protected $fillable = [
        'id',
        'employee_id', 
        'fname', 
        'mname', 
        'lname', 
        'dob', 
        'email',
        'phone_primary', 
        'phone_secondary',
        'emr'
    ];
}