<?php
namespace RCare\Patients\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Notifiable;

class PatientTimeRecords extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='patients.patient_time_records';

    protected $dates = [
        'record_date',
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'uid',
        'patient_id',
        'record_date',
        'stage_id',
        'timer_on',
        'timer_off',
        'net_time',
        'billable',
        'module_id',
        'component_id',
        'created_by',
        'updated_by',
        'stage_code',
        'form_name',
        'scheduler_run_id',
        'comment',
        'callwrap_id',
        'activity',
        'activity_id',
        'scheduler_id' 
    ];
    
    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }
 
    public function patients()
    {
        return $this->belongsTo('RCare\Patients\Models\Patients', 'patient_id');
    }
    
    
    public function module()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\Module', 'module_id');
    }

}

