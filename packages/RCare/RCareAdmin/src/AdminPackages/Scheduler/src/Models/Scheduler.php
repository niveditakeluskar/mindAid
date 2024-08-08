<?php

namespace RCare\RCareAdmin\AdminPackages\Scheduler\src\Models; 
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
        'updated_at'
    ];
    protected $fillable = [
        'id',
        'activity_id',
        'module_id',
        'operation',
        'day_of_execution',
        'time_of_execution',
        'comments',
        'created_by',
        'updated_by',
        'status',
        'created_at',
        'updated_at' 
    ];  

    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by'); 
    }

    public function activity() 
    {
        return $this->hasOne('RCare\Org\OrgPackages\Activity\src\Models\Activity','id'); 
    }

    public function modules()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\Module','module_id');   
    }
    
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