<?php

namespace RCare\Rpm\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Support\Facades\DB;
class VitalsAlertNotes extends Model    
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='rpm.vitals_alert_notes';

     protected $population_include = [
        "id"
    ];
  
  protected $dates = [
        'created_at',
        'updated_at'        
    ];
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
      'id',
      'patient_id',
      'notes',
      'observation_id',
      'vital',
      'rpm_observation_id',
      'created_by',
      'updated_by',
      'created_at',
      'updated_at'
     
      ];

}
