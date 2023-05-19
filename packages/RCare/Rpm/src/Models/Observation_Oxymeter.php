<?php

namespace RCare\Rpm\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Support\Facades\DB;
class Observation_Oxymeter extends Model    
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='rpm.observations_oxymeter';

     protected $population_include = [
        "id"
    ];
  
  protected $dates = [
        'created_at',
        'updated_at',
        'reviewed_date',
        'addressed_date'        
    ];
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
      'id',
      'careplan_no',
      'device_id',
      'effdatetime',
      'oxy_qty',
      'oxy_unit',
      'oxy_code',
      'mrn',
      'patient_id',
      'resting_heartrate',
      'resting_heartrate_unit',
      'resting_heartrate_code',
      // 'processed_flag',
      'created_by',
      'updated_by',
      'created_at',
      'updated_at',
      'reviewed_flag',
      'addressed',
      'alert_status',
      'reviewed_date',
      'observation_id',
      'billing',
      'threshold',
      'vital_device_id',
       'addressed_date',
       'threshold_type',
       'threshold_id'      
      ];

}
