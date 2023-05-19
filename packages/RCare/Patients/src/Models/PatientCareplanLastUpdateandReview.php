<?php

namespace RCare\Patients\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class PatientCareplanLastUpdateandReview extends Model
{
    //
    use DashboardFetchable, ModelMapper,DatesTimezoneConversion;
    protected $table ='patients.patient_careplan_last_update_n_review';  

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = true;
    protected $population_include = [
        "id"
    ]; 
    

    
    protected $dates = [
        'created_at',
        'updated_at',
        'update_date',
        'review_date'     

    ];
 
    protected $fillable = [         
      
        'patient_id',       
        'diagnosis_id', 
        'created_by',
        'updated_by',
        'update_date',
        'review_date',
        'status'
       
    ]; 
	
	// public static function latest($patientId)
    // {
    //     $currentMonth = date('m');
    //     $year = Carbon::now()->year;
    //     return self::where('patient_id', $patientId)->whereYear('created_at', $year)->whereMonth('created_at', $currentMonth)->orderBy('created_at', 'desc')->first();
    //     // return self::where('patient_id', $patientId)->orderBy('created_at', 'desc')->first();
    // }

    
    
}