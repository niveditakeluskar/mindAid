<?php

namespace RCare\Rpm\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Support\Facades\DB;
class Observation_Spirometer extends Model    
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='rpm.observations_spirometer';

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
      'mrn',
      'patient_id',
      'created_by',
      'updated_by',
      'created_at',
      'updated_at',
      'reviewed_flag',
      'addressed',
      'reviewed_date',
      'observation_id',
      'billing',
      'fev_value',
      'pef_value',	
      'fev_unit',
      'pef_unit', 	
      'fev_code', 
      'pef_code',	
      'alert_status',
      'vital_device_id',
      'threshold',
       'addressed_date',
       'threshold_type',
       'threshold_id',
       'fev_threshold',
       'pef_threshold'    
   
      ];

}
