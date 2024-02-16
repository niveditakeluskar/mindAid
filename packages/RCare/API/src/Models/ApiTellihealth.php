<?php

namespace RCare\API\Models;  

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model; 
use RCare\System\Traits\DatesTimezoneConversion;


class ApiTellihealth extends Model
{
    //
    protected $table ='api.tellihealth';
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
 protected $dates = [
        'created_at'
    ];  

    protected $fillable = [
        'id',
        'content',
        'partner'
    ];

}