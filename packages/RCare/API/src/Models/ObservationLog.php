<?php

namespace RCare\API\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class ObservationLog extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='api.observation_log';  


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

      protected $dates = [  
        'created_at' ,
        'updated_at'    
    ];

    protected $primaryKey = 'id';
	 
        protected $fillable = [  
         'id',
         'patient_id',
         'partner_id',
         'observation_id',
         'record_timestamp',
         'device_code', 
         'content',
         'content_format',
         'mrn',
         'status',
         'created_by',
         'updated_by',
         'created_at',
         'updated_at',
         'processed_flag',
         'api_url'    
        

    ];

}
