<?php

namespace RCare\API\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class ApiException extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='api.api_exceptions';  


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
         'api',
         'parameter',
         'exception_type',
         'incident',
         'action_taken', 
         'created_at',
         'updated_at',
         'webhook_id',
         'reprocess',
         'mrn',
         'patient_id',
		 'partner_id',
         'observation_id',
         'device_code',
            
        

    ];

   
   public static function ExceptionEMR()
    {
        self::whereNotNull('mrn')->distinct()->get(['mrn']);
    }

}
