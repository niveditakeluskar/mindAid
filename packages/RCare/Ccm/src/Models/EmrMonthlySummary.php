<?php

namespace RCare\Ccm\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Carbon\Carbon;

class EmrMonthlySummary extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='ccm.ccm_emr_monthly_summary';
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
        'record_date',
        'status', 
        'topic',
        'notes',
        'emr_entry_completed',
        'created_by',
        'updated_by',
        'patient_id',
        'sequence',
        'sub_sequence',
        'emr_type'
       
        
    ];
	
    public static function latest($patientId)   //added by ashvini 15May2023
    {
        $currentMonth = date('m');
        $year = Carbon::now()->year;  
        return self::where('patient_id', $patientId)->whereYear('created_at', $year)->whereMonth('created_at', $currentMonth)->first();
        
    }
}
