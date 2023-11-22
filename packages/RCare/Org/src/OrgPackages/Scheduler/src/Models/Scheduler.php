<?php

namespace RCare\Org\OrgPackages\Scheduler\src\Models;    
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;


class Scheduler extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='ren_core.scheduler'; 

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
        // 'date_of_execution',  
        // 'time_of_execution'
        'scheduler_date' 
    ];
    protected $fillable = [
        'id',
        'activity_id',
        'module_id',
        'operation',
        'date_of_execution',
        'start_date',
        'day_of_execution', 
        'time_of_execution',
        'comments',
        'created_by',
        'updated_by',
        'status',
        'created_at',
        'updated_at',
        'scheduler_status',
        'scheduler_date',
        'user_id',
        'report_id',
        'frequency',
        'scheduler_type',
        'report_format',
        'comments',
        'year_of_execution',
        'month_of_execution',
        'week_of_execution'
    
    ];  

    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by'); 
    }

    public function activity() 
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Activity\src\Models\Activity','activity_id');    
    }

    public function modules()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\Module','module_id');   
    }

    // public static function schedulerDateofExecution()  
    // {
    //     $configTZ = config('app.timezone'); 
    //     $userTZ   = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');  
    //     return \DB::table('ren_core.scheduler_log')
    //              ->select(\DB::raw("to_char(schedulerrecord_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as convertedschedulerrecord_date"),'schedulerrecord_date')
    //              ->distinct('convertedschedulerrecord_date')
    //              ->get();             
    // }

    // public function practicegroup()
    // { 
    //     return $this->belongsTo('RCare\Org\OrgPackages\Practices\src\Models\PracticesGroup','practice_group');   
    // }
 
    // public function schedulerLog() 
    // { 
    //     return $this->belongsTo('RCare\Org\OrgPackages\Scheduler\src\Models\Scheduler','id'); 
    // }


    public static function self($schedulerId) 
    {   $schedulerId = sanitizeVariable($schedulerId);
        return self::where('id', $schedulerId)->orderBy('created_at', 'desc')->first();     
    }

   

    // public static function schedulerActivity($schedulerId) 
    // {
    //     $schedulerId = sanitizeVariable($schedulerId);
    //     // return self::with('activity')->where('id',$schedulerId)->orderBy('created_at','desc')->first();
    //     $a = \DB::table('ren_core.scheduler as s')
    //     ->join('ren_core.activities as a','a.id','=','s.activity_id')
    //     ->where('a.status',1)
    //     ->where('s.id',$schedulerId)
    //     ->get();
    //     // dd($a);    
    //     return $a;     
    // } 
}