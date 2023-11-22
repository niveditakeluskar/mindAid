<?php

namespace RCare\Org\OrgPackages\FollowupTask\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;


class FollowupTask extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='ren_core.followup_tasks';

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
        'task',
        'created_by',
        'updated_by',
        'status'
    ];  

    public static function self($id)
    {   $id = sanitizeVariable($id);
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }

    public static function activeTask(){ 
        $task= FollowupTask::where("status", 1)->orderBy('task','asc')->get();
        // dd($task);
        return $task;
    }
    
    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    } 
}