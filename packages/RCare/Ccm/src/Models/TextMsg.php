<?php

namespace RCare\Ccm\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;
// use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
class TextMsg extends Model
{
   use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='ccm.ccm_msg';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $population_include = [
        "id"
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'rec_date'
    ];  
     protected $fillable = [
       
        'id', 
        'uid',
		'patient_id',
        'rec_date',
        'contact_no', 
        'template_id',
        'template',
        'msg',
        'response_msg'
    ];
}
