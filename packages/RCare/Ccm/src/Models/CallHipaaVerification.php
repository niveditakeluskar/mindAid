<?php

namespace RCare\Ccm\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use RCare\System\Traits\DatesTimezoneConversion;

class CallHipaaVerification extends Model
{
	// Use Trait
    use DashboardFetchable, ModelMapper,DatesTimezoneConversion;
	
	// custom table
    protected $table ='ccm.ccm_hippa_verification';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = true;
    
    // public function setDateAttribute( $value ) {
    //   $this->attributes['v_date'] = (new Carbon($value))->format('Y-m-d');
    // }
    protected $population_include = [
        "id"
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'v_date'
    ];

     protected $fillable = [       
        'id', 
        'uid',
		'patient_id',
        'rec_date',
        'v_date', 
        'notes',
        'verification',
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
