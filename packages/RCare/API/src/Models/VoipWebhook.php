<?php

namespace RCare\API\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
// use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class VoipWebhook extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='api.voip_webhook';


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
         'content',
		 'callid',
		 'caller',
		 'agent',
		 'direction',
		 'datetime',
		 'recording_file_name',
         'created_by',
         'updated_by',
         'status',
         'created_at',
         'updated_at' 

    ];

    

}
