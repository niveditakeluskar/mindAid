<?php


namespace RCare\Patients\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
class PatientVitalsData extends Model
{
    //
use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='patients.patient_vitals';

        /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
	 
    protected $population_include = [
        "id"
    ];
    protected $dates = [
        'rec_date',
        'created_at',
        'updated_at'
    ]; 
    protected $fillable = [
        'patient_id',
        'uid',
        'rec_date',
        'height',
        'weight',
        'age',
        'bmi',
        'bp',
        'o2',
        'other_vitals',
        'pulse_rate',
        'pain_level',
        'created_by',
        'updated_by',
        'diastolic',
        'oxygen',
        'notes'
    ];
	
	
	 public static function latest($patientId)
    {
        $patient_id=sanitizeVariable($patientId);
        return self::where('patient_id', $patient_id)->orderBy('created_at', 'desc')->first();
    }
	
	
	public static function getLatestValuesOnly($patientId)
    {
         $patient_id=sanitizeVariable($patientId);
        return self::select('height','weight','bmi','bp','o2','pulse_rate','diastolic','other_vitals')->where('patient_id', $patient_id)->orderBy('created_at', 'desc')->first();

    }
}