<?php

namespace RCare\Ccm\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use RCare\System\Traits\DatesTimezoneConversion;
class CallHomeServiceVerification extends Model
{
	// use trait function
	use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
	
    //
    protected $table ='ccm.ccm_home_service_verification';

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
        'v_date',
        'service_end_date',
        'follow_up_date'
    ];  
     protected $fillable = [
        'id', 
        'uid',
		'patient_id',
        'v_date',
        'query1', 
        'therapist_come_home_care',
        'notes',
        'home_service_ends',
        'query2',
        'wound_care',
        'Injections_IV',
        'catheter', 
        'tubefeeding',
        'physio',
        'oc_therapy',
        'speech_therapy',
        'reason_for_visit', 
        'service_end_date',
        'follow_up_date',
        'created_by',
        'updated_by',
        'module_id',
        'component_id'
    ];
	
	public static function latest($patientId)
    {
        $currentMonth = date('m');
        $year = Carbon::now()->year;
        return self::where('patient_id', $patientId)->whereYear('created_at', $year)->whereMonth('created_at', $currentMonth)->orderBy('created_at', 'desc')->first();
    }
}