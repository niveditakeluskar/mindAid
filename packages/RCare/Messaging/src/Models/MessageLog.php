<?php

namespace RCare\Messaging\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class MessageLog extends Model
{
    //
    use DashboardFetchable, ModelMapper,DatesTimezoneConversion;
    protected $table ='ccm.message_log';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = true;
    protected $population_include = [
        "id"
    ]; 
    

    protected $dates = [
        'created_at',
        'updated_at',
        'message_date'
    ];
 
    protected $fillable = [
        'id', 
        'message_id',
        'patient_id',
        'module_id', 
        'stage_id',
        'from_phone',
        'to_phone',
        'status', 
        'message_date',
        'created_by',
        'updated_by',
        'status_update',
        'message',
        'read_status'
    
    ]; 
	
	
} 