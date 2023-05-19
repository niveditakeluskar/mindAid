<?php

namespace RCare\Org\OrgPackages\Practices\src\Models;
use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
use Carbon\Carbon;
use RCare\System\Traits\DatesTimezoneConversion;
use RCare\Org\OrgPackages\Users\src\Models\Users;


use Illuminate\Database\Eloquent\Model;

class PracticeThreshold extends Model
{   
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    //
     protected $table ='patients.practice_threshold';


    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $population_include = [
        "id"
    ];
    
    
    public $timestamps = true;
    protected $dates = [
        'eff_date',
        'created_at',
        'updated_at'
    ];
	 
    protected $fillable = [
        'id',
        'practice_id',
        'bpmhigh',
        'bpmlow',
        'diastolichigh',
        'diastoliclow',
        'glucosehigh',
        'glucoselow',
        'oxsathigh',
        'oxsatlow',
        'systolichigh',
        'systoliclow',
        'temperaturehigh',
        'temperaturelow',
        'spirometerfevlow',
        'spirometerfevhigh',
        'spirometerpefhigh',
        'spirometerpeflow',
        'weighthigh',
        'weightlow',
        'eff_date',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'

    ];

    public static function self($id)
    {
        return self::where('practice_id', $id)->orderBy('created_at', 'desc')->first();
    }
 
}
   