<?php

namespace RCare\Ccm\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Carbon\Carbon;

class FollowUp extends Model
{
	// use trait function
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    
    //
    protected $table ='ccm.ccm_followup';

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
        'rec_date'
    ];
           
     protected $fillable = [       
        'id', 
        'uid',
		'patient_id',
        'rec_date',
        'emr_complete', 
        'reseach_complete',
        'care_coord_complete',
        'emr_select',
        'notes',
        'update_status',
        'created_by',
        'updated_by'       
    ];
	
	public static function latest($patientId)
    {
        $currentMonth = date('m');
        $year = Carbon::now()->year;
        return self::where('patient_id', $patientId)->whereYear('created_at', $year)->whereMonth('created_at', $currentMonth)->orderBy('created_at', 'desc')->first();
    }
}
