<?php

namespace RCare\Ccm\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class EmailLog extends Model
{
    //
    use DashboardFetchable, ModelMapper,DatesTimezoneConversion;
    protected $table ='ccm.email_log';

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
        'email_date'
    ];
 
    protected $fillable = [
        'id', 
        'patient_id',
        'module_id', 
        'stage_id',
        'from_email',
        'to_email',
        'email_subject',
        'email_content', 
        'email_date',
        'status',
        'created_by',
        'updated_by'
    
    ]; 
	
	
}  