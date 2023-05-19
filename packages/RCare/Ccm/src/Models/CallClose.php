<?php

namespace RCare\Ccm\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Carbon\Carbon;

class CallClose extends Model
{
    // use trait function
	use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
	
	
    protected $table ='ccm.ccm_call_close';

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
        'rec_date',
        'q2_date',
       // 'q2_time',
		'q2_datetime'
    ];
    
    protected $fillable = [       
        'id', 
        'uid',
		'patient_id',
        'rec_date',
        'query1', 
        'q1_notes',
        'query2',
        'q2_notes',
        'q2_date',
        'q2_time',
		'q2_datetime',		
        'monthly_notes',
        'created_by',
        'updated_by',
        'component_id',       
    ];
	
	public static function latest($patientId,$cid)
    {
        $currentMonth = date('m');
        $year = Carbon::now()->year;
        return self::where('patient_id', $patientId)->where('component_id',$cid)->whereYear('created_at', $year)->whereMonth('created_at', $currentMonth)->orderBy('created_at', 'desc')->first();
    }
}
