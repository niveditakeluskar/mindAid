<?php

namespace RCare\Ccm\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Carbon\Carbon;

class CallWrap extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='ccm.ccm_topics';
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
        'uid',
        'record_date',
        'status', 
        'topic',
        'notes',
        'emr_entry_completed',
        'emr_monthly_summary',
        'action_taken',
        'created_by',
        'updated_by',
        'patient_id',
        'sequence',
        'sub_sequence',
        'template_type',
        'task_id',
        'device_id',
        'rpm_observation_id',
        'topic_id',
        'topic_type',
        'emr_monthly_summary_date'
    ];
	
    public static function latest($patientId)   //added by pranali 27Oct2020
    {
        $currentMonth = date('m');
        $year = Carbon::now()->year;
        // return self::where('patient_id', $patientId)->whereYear('created_at', $year)->whereMonth('created_at', $currentMonth)->orderBy('created_at', 'desc')->first();
        //updated by pranali on 9Nov2020 added sub_sequence column in order by clause
        return self::where('patient_id', $patientId)->whereYear('created_at', $year)->whereMonth('created_at', $currentMonth)->orderBy('created_at', 'desc')->first();
        //return self::where('patient_id', $patientId)->whereYear('created_at', $year)->whereMonth('created_at', $currentMonth)->orderBy('sequence', 'desc')->orderBy('sub_sequence', 'desc')->first();
    }
}
