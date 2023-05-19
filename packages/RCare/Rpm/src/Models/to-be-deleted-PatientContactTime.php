<?php

namespace RCare\Rpm\Models;

use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;

class PatientContactTime extends Model
{

    protected $table ='patients.patient_contact_times';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    // protected $population_include = [
    //     "id"
    // ];
    
    protected $fillable = [
        'id',
        'patient_id',
        'mon_0'  ,
        'mon_1'  ,
        'mon_2'  ,
        'mon_3'  ,
        'mon_any',
        'tue_0'  ,
        'tue_1'  ,
        'tue_2'  ,
        'tue_3'  ,
        'tue_any',
        'wed_0'  ,
        'wed_1'  ,
        'wed_2'  ,
        'wed_3'  ,
        'wed_any',
        'thu_0'  ,
        'thu_1'  ,
        'thu_2'  ,
        'thu_3'  ,
        'thu_any',
        'fri_0'  ,
        'fri_1'  ,
        'fri_2'  ,
        'fri_3'  ,
        'fri_any',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'status'
        
    ];
}
