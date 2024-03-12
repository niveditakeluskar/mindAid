<?php

namespace RCare\Org\OrgPackages\Scheduler\src\Models; 
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;
use Session;
use DB;


class SchedulerLogHistory extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='ren_core.scheduler_log'; 
  
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
        'schedulerrecord_date'
    ];
    protected $fillable = [   
        'id',
        'scheduler_id',
        'activity_id',
        'start_date', 
        'practice_id',
        'practice_group',
        'execution_status',
        'module_id',
        'operation',   
        'patients_count',
        'exception_comments',
        'comments',      
        'created_at',
        'updated_at',
        'schedulerrecord_date'    
          
    ];  

    public static function schedulerAll()
    {
        return self::orderBy('id', 'asc')->get();      
    }

    public static function schedulerStartdate()
    {
       $a= DB::table('ren_core.scheduler_log')       
        ->distinct('start_date')
        ->get(); 
      
       foreach($a as $key=>$value){
           $value->start_date = date('m-d-Y',strtotime($value->start_date)); 
       }   
       return $a;    
        
    }

    public static function schedulerDateofExecution()  
    {
        $configTZ = config('app.timezone'); 
        $userTZ   = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');  
        return \DB::table('ren_core.scheduler_log')
                 ->select(\DB::raw("to_char(schedulerrecord_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI') as convertedschedulerrecord_date"),'schedulerrecord_date')
                 ->distinct('convertedschedulerrecord_date')
                 ->orderBy('convertedschedulerrecord_date','desc')
                 ->get();             
    }

    



    public function activities() 
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Activity\src\Models\Activity','activity_id');    
    }

    public function scheduler() 
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Scheduler\src\Models\Scheduler','scheduler_id');    
    }

    public function modules()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\Module','module_id');   
    }

    public function practices()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Practices\src\Models\Practices','practice_id');   
    }

    public function practicegroup()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Practices\src\Models\PracticesGroup','practice_group');   
    }


   
    // public static function self($schedulerlogId)
    // {   $schedulerlogId = sanitizeVariable($schedulerlogId);
    //     return self::where('id', $schedulerlogId)->orderBy('created_at', 'desc')->first();     
    // }

    
}