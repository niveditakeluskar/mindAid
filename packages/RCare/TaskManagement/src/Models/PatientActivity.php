<?php

namespace RCare\TaskManagement\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
class PatientActivity extends Model
{
    //
    protected $table ='patients.patient_time_records';
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
 protected $dates = [
        'created_at',
        'updated_at',
    ];  

    protected $fillable = [
        'id',
        'uid',
        'patient_id',
        'record_date',
        'net_time',
        'timer_on',
        'timer_off',
        'module_id',
        'component_id',
        'stage_id',
        'comment',
        'created_by',
        'updated_by',
        'billable',
        'callwrap_id',
        'activity_id',
        'activity'

    ];

    public static function groupedPatientActivies() {
      $query = 
      \DB::select(\DB::raw("select id,activity as name,
        case
            when activity_type is not null then activity_type
        else ''
        end AS group_by
        from ren_core.activities where status='1' and timer_type!='3'  
        order by group_by,activity_type"));
        return $query;
    }  
} 
