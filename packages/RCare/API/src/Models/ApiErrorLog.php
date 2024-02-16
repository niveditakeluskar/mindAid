<?php

namespace RCare\API\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class ApiErrorLog extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='api.api_error_log';  


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
         'partner_id',
         'message',
         'status',
         'api_url',
         'created_at',
         'updated_at'
       
    ];

}
