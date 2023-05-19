<?php

namespace RCare\Ccm\Models;

use Illuminate\Database\Eloquent\Model;

class Patients extends Model
{
    //
    protected $table ='patients.patient_medication';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
     protected $fillable = [
        'id',
        'patient_id',
        'med_id',
        'description',
        'purpose',
        'dosage',
        'strength',
        'frequency',
        'route',
        'time',
        'drug_reaction',
        'pharmacogenetic_test'
    ];
}