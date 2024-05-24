<?php

namespace RCare\Ccm\Models;
use Illuminate\Database\Eloquent\Model;

class PatientDataStatus extends Model
{
//
    protected $table ='ccm.patient_data_status';
    /**
     * The attributes that are mass assignable.
     *s
    */

    protected $fillable = [
       'id',    
       'patient_id',
       'module_id',
       'component_id',
       'stag_id',
       'stage_code_id',
       'completion_status',
       'created_by',    
       'updated_by', 
       'created_at',    
       'updated_at'
    ];

    
}