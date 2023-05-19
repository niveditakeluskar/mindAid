<?php

namespace RCare\Rpm\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Support\Facades\DB;
class Other_Alerts extends Model  
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='api.other_alerts';

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
      'device_id',
     
    
      'mrn',
      'patient_id',
      'created_by',
      'updated_by',
      'created_at',
      'updated_at', 
      'reviewed_flag',
      'alert_status',
      'addressed',
      'reviewed_date',     
      'addressed_date'
           
      ];

}
