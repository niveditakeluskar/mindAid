<?php

namespace RCare\Patients\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class PatientThreshold extends Model
{
    //
    use DashboardFetchable, ModelMapper;
     protected $table ='patients.patient_threshold';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     * 
     */

        protected $population_include = [
            "id"
        ];

        protected $dates = [
            'created_at',
            'updated_at',
            'eff_date'
        ];
	 
        protected $fillable = [
        'id', 
        'patient_id',
        'bpmhigh',
        'bpmlow', 
        'diastolichigh', 
        'diastoliclow',
        'glucosehigh',
        'glucoselow',
        'oxsathigh',
        'oxsatlow',
        'systolichigh',
        'systoliclow',
        'temperaturehigh',
        'temperaturelow',
        'spirometerfevlow',
        'spirometerfevhigh',
        'spirometerpefhigh',
        'spirometerpeflow',
        'weighthigh',
        'weightlow',
        'eff_date',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
         

    ];

    public static function latest($patientId)
    {
        $patient_id=sanitizeVariable($patientId);
        return self::where('patient_id', $patient_id)//->whereMonth('eff_date', date('m'))->whereYear('eff_date', date('Y'))
        ->orderBy('created_at', 'desc')->first();
    }


    public function patients(){
        return $this->belongsTo('RCare\Patients\src\Models\Patients','patient_id');  
    }
}
