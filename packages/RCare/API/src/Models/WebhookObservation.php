<?php

namespace RCare\API\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
// use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class WebhookObservation extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='api.webhook_observation';


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
         'content',
         'eff_date',
         'created_by',
         'updated_by',
         'status',
         'created_at',
         'updated_at' 

    ];

    public static function self($id)
    {   $id = sanitizeVariable($id); 
        return self::where('id', $id)->first();
    }

}
