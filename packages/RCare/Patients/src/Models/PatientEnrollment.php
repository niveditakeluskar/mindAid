<?php
namespace  RCare\Patients\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
class PatientEnrollment extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
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
        'updated_at',
        'callback_date'
        
    ];   
    protected $fillable = [
        'id',
        'practice_id', 
        'uid', 
        'patient_id',
        'action', 
        'action_template', 
        'call_status', 
        'enrollment_response',
        'callback_time', 
        'callback_date',
        'voice_mail',
        'enrollment_checklist',
        'finalization_checklist',
        'created_by',
        'updated_by',
        'module_id',
        'component_id',
        'stage_id',
        'call_continue_status',
        'created_at',
        'updated_at',
        'enrl_notes',
        'fin_number'
    ];

    /**
    * Fetch the latest record from a given patient
    */
    public static function latest($patientId)
    {
        $patient_id=sanitizeVariable($patientId);
        return self::where("patient_id", $patient_id)->orderBy("id", "desc")->first();
    }

    public function patient()
    {
        return $this->belongsTo('RCare\Rpm\Models\Patients');
    }
}