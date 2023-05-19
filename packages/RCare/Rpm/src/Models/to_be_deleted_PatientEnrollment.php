<?php

namespace RCare\Rpm\Models;

use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class PatientEnrollment extends Model
{
    //
    use DatesTimezoneConversion;
    protected $table ='patients.patient_enrollment';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $population_include = [
        "id"
    ];
    
    protected $dates = [
        'created_at',
        'updated_at'
    ];
         
     protected $fillable = [
        'id',
        'practice_id', 
        'UID', 
        'action', 
        'action_template', 
        'call_status', 
        'enrollment_response',
        'callback_time', 
        'calback_date',
        'voice_mail',
        'enrollment_checklist',
        'finalization_checklist',
        'created_by',
        'update_by'
    ];

    /**
     * Fetch the latest record from a given patient
     */
    public static function latest($patientId)
    {
        return self::where("UID", $patientId)->orderBy("id", "desc")->first();
    }

    public function patient()
    {
        return $this->belongsTo('RCare\Rpm\Models\Patients');
    }
}
