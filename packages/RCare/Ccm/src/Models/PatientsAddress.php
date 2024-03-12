<?php

namespace RCare\Ccm\Models;

use Illuminate\Database\Eloquent\Model;

class PatientsAddress extends Model
{
    //
    protected $table ='patients.patient_addresss';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
     protected $fillable = [
        'id',     
        'patient_id',
        'add_1',
        
    ];
}