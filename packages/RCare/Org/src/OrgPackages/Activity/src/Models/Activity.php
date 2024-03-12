<?php

namespace RCare\Org\OrgPackages\Activity\src\Models; 
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;


class Activity extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='ren_core.activities'; 

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
        'updated_at'
    ];
    protected $fillable = [
        'id',
        'activity_type',
        'activity',
        'timer_type',
        'default_time',
        'created_by',
        'updated_by',
        'status',
        'created_at',
        'updated_at',
        'sequence'   
    ];  

    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }
    
    public static function self($activityId)
    {   $activityId = sanitizeVariable($activityId);
        return self::where('id', $activityId)->orderBy('created_at', 'desc')->first();   
    }

    // public static function groupedPatientActiviesonTimerType() {
    //     $query = 
    //     \DB::select(\DB::raw("select id,activity as name,timer_type,
    //       case
    //           when activity_type is not null then activity_type
    //       else ''
    //       end AS group_by
    //       from ren_core.activities where status='1' and timer_type in [2,3]
    //       order by group_by,timer_type"));
    //       dd($query); 
    //   } 

      public static function ActivityTimerType(){
       return self::whereIn('timer_type', [2, 3])->where('status',1)->orderBy('created_at', 'desc')->get(); 
         
     
      }

      public static function Activitymodule()
      {
          $m = \DB::table('ren_core.modules')->where('patients_service',1)->get();
          return $m;
      }
/*
       public function schedulerActivity()
       {
           return $this->belongsTo('RCare\RCareAdmin\AdminPackages\Scheduler\src\Models\Scheduler', 'activity_id')->where('status', '=', '1'); 
       }*/

       
} 