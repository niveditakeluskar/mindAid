<?php

namespace RCare\TaskManagement\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Carbon\Carbon;
class ToDoList extends Model
{
    //
    protected $table ='task_management.to_do_list';
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
 protected $dates = [
        'created_at',
        'updated_at',
        'task_date'
       
    ];  

    protected $fillable = [
        'id',
        'uid',
        'module_id',
        'component_id',
        'stage_id',
        'step_id',
        'task_notes',
        'status',
        'status_flag',
        'task_date',
        'task_completed_at',
        'assigned_to',
        'assigned_on',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'patient_id',
        'task_time',
        'enrolled_service_id',
        'notes', 
        'select_task_category',
		'weekday',
		'schedule_day_pref',
		'schedule_time_pref',
		'score',
		'etl_flag',
		'etl_title',
		'etl_notes'
    ];

    public static function activeTask(){ 
        $task= ToDoList::distinct('status')->get();
        // dd($task);
        return $task;
    }
    

    public function Patient()
    {
      return $this->belongsTo('RCare\Patients\Models\Patients');
    }

    public static function groupedTasks($patient_id) {
    $query = \DB::select("SELECT tdl.id as og_id,CONCAT(tdl.id) as id,tdl.task_notes as name, 
                CASE tdl.status_flag 
                           WHEN '0'
                           THEN 'Scheduled-Tasks'
                           ELSE 'Unknown'
                       END AS group_by 
                FROM task_management.to_do_list tdl
                              where status_flag =0 and tdl.patient_id =$patient_id
                            AND EXTRACT(Month from tdl.task_date) = 01  
                            AND EXTRACT(YEAR from tdl.task_date) = 2021
                union all
SELECT ft.id as og_id,CONCAT('st',ft.id) as id,ft.task as name,
                CASE ft.status
                           WHEN '1' 
                           THEN 'Standard-Followup-Tasks'
                           ELSE 'Unknown' 
                       END AS group_by        
                FROM ren_core.followup_tasks ft where ft.status = 1 
                GROUP BY task,ft.id               
            ");
    return $query;
}

    public static function latest($patientId)
    {
        return self::where('patient_id', $patientId)->orderBy('created_at', 'desc')->first();
    }

    public static function pending($patientId)
    {
        return self::where('patient_id', $patientId)->where('status_flag', '0')->get();
    }
        
    public static function toDoPatientData($patientId)
    {
        return self::where('patient_id', $patientId)->orderBy('created_at', 'desc')->get();
    }

    public function master_followuptask(){ 
        return $this->belongsTo('RCare\Org\OrgPackages\FollowupTask\src\Models\FollowupTask', 'select_task_category');
    }
}