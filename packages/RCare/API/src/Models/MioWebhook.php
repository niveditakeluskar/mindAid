<?php

namespace RCare\API\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class MioWebhook extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='api.mio_webhook';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

      protected $dates = [
        'created_at'  ,
        'updated_at'      
    ];

    protected $primaryKey = 'id';
	 
        protected $fillable = [
         'id',
         'content',
         'partner',
         'status',
         'device_id',
         'url'      

    ];

}
