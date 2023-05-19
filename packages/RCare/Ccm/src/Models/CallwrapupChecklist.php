<?php

namespace RCare\Ccm\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Carbon\Carbon;

class CallWrapupChecklist extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='ccm.callwrapup_checklist';
    public $timestamps = TRUE;
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
        'record_date'
       
    ]; 

    protected $fillable = [
        'id', 
        'emr_entry_completed',
        'schedule_office_appointment',
        'resources_for_medication',
        'medical_renewal',
        'called_office_patientbehalf',
        'referral_support',
        'no_other_services',
        'patient_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'record_date'


       
        
    ];
	
    public static function latest($patientId)   //added by pranali 27Oct2020
    {
        $currentMonth = date('m');
        $year = Carbon::now()->year;
        return self::where('patient_id', $patientId)->whereYear('created_at', $year)->whereMonth('created_at', $currentMonth)->first();
        
    }
}
