<?php

namespace RCare\Ccm\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class CallStatus extends Model
{
    //
    use DashboardFetchable, ModelMapper,DatesTimezoneConversion;
    protected $table ='ccm.ccm_call_status';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = true;
    protected $population_include = [
        "id"
    ]; 
    

    protected $casts = [
    'rec_date' => 'datetime:Y-m-d',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'rec_date', 
        'assigned_on', 
        'ccm_call_followup_date', 
        'ccm_answer_followup_date',
        // 'call_followup_date',
        // 'answer_followup_date'
    ];
 
    protected $fillable = [
        'id', 
        'uid',
        'rec_date',
        'phone_no', 
        'call_action_template',
        'call_continue_status',
        'call_status',
        'not_recorded_action_template', 
        'voice_mail',
        'call_followup_date',
        'answer_followup_date', 
        'text_msg',
        'created_by',
        'updated_by',
        'patient_id',
        'module_id',
        'component_id',
        'ccm_call_followup_date',
        'ccm_answer_followup_date',
        'ccm_answer_followup_time'
    ]; 
	
	public static function latest($patientId)
    {
        $currentMonth = date('m');
        $year = Carbon::now()->year;
        return self::where('patient_id', $patientId)->whereYear('created_at', $year)->whereMonth('created_at', $currentMonth)->orderBy('created_at', 'desc')->first();
        // return self::where('patient_id', $patientId)->orderBy('created_at', 'desc')->first();
    }
}