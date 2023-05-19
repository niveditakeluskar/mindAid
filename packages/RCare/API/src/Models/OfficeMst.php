<?php

namespace RCare\API\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class OfficeMst extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='api.office_mst';


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
            'name',
            'group_code',
            'description', 
            'billing_phone',
            'billing_fname',
            'billing_lname',
            'billing_address',
            'billing_city',
            'billing_state',
            'billing_zip',
            'billing_email',
            'alternate_phone',
            'physicians',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
            'office_id'
        

    ];

}
