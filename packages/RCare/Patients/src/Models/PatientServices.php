<?php

namespace RCare\Patients\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
class PatientServices extends Model
{
    //
    
use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='patients.patient_services';

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
        'date_enrolled',
        'date_expired',
        'finalize_date'
    ];

    
    protected $fillable = [
		'id',
        'patient_id',
        'module_id', 
        'uid', 
        'date_enrolled',
        'date_expired',
        'created_by', 
        'updated_by',
        'created_at',
        'updated_at',
        'finalize_cpd',
        'finalize_date',
        'deactivation_drpdwn',
        'suspended_from',
        'suspended_to',
        'status_value',
        'status',
        'deactivation_reason'

    ];
	
    public static function latest($patientId)
    {
        $patient_id=sanitizeVariable($patientId);
        return self::where('patient_id', $patient_id)->orderBy('created_at', 'desc')->first();
    }
    
	public function patients()
    {
        return $this->belongsTo('RCare\Patients\Models\Patients', 'id');
    }
	
	
	public function module()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Modules\src\Models\Module', 'module_id');
    }
	
	public static function latest_module($patientId, $module_id)
    {
        $patient_id=sanitizeVariable($patientId);
        $moduleid=sanitizeVariable($module_id);
        $service = self::where('patient_id', $patient_id)->where("module_id", $module_id)->orderBy('created_at', 'asc')->take(1)->get();
        // if(!(self::where('patient_id', $patient_id)->where("module_id", $moduleid)->orderBy('created_at', 'desc')->take(1)->exists())) {
        //     $service = self::where('patient_id', $patient_id)->orderBy('created_at', 'desc')->take(1)->get();
        // }
        return $service;
    }
}