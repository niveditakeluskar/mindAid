<?php

namespace RCare\TaskManagement\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
class UserPatients extends Model
{
    //
    protected $table ='task_management.user_patients';
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
        'practice_id',
        'user_id',
        'patient_id',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'status',
        'assigned_to'
    ];

    public function users_created_by(){
        return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','created_by');
    }

    public function users_updated_by(){
        return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }

    public function users_assign_to(){
        return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','user_id');
    }    
} 