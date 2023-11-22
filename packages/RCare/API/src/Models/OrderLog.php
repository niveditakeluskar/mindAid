<?php

namespace RCare\API\Models;  

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class OrderLog extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='api.order_log';


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
         'order_id',
         'source_id',         
         'record_timestamp',
         'mrn',
         'status',
         'created_by',
         'updated_by',
         'created_at',
         'updated_at',
         'processed_flag',
         'api_url',
         'patient_id',
         'partner_id',
         'device_code',
         'order_date',
         'order_status',
         'active_date'
    ];

}
