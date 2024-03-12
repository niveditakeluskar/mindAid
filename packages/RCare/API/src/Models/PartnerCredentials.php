<?php

namespace RCare\API\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class PartnerCredentials extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='ren_core.partner_credentials';


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
            'username',
            'password ',  
            'group_name',
            'purpose',
            'status',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at'
    ];

}
