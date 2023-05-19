<?php
namespace RCare\Patients\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Notifiable;

class PatientTimeButtonLogs extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='patients.patient_time_button_logs';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'timer_on',
        'timer_off',
        'net_time',
        'module_id',
        'component_id',
        'patient_id',
        'uid',
        'action_taken',
        'created_by',
        'updated_by'
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

