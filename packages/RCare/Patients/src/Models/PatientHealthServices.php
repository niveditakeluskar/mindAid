<?php

namespace RCare\Patients\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class PatientHealthServices extends Model 
{   
     use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='patients.patient_healthcare_services';


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
		'service_start_date',
		'service_end_date',
       
    ];

        protected $fillable = [
        'id', 
        'patient_id', 
        'uid',
        'specify', 
        'hid',  
        'brand',  
        'type', 
        'from_whom', 
        'from_where', 
		'service_start_date',
		'service_end_date',
        'purpose', 
        'frequency', 
        'duration',  
        'notes', 
        'created_at', 
        'updated_at', 
        'created_by', 
        'updated_by',
        'status',
        'review'

    ]; 

    public static function latest($patientId,$HealthServicesId)
    {
        $patient_id=sanitizeVariable($patientId);
        $healthservices=sanitizeVariable($HealthServicesId);
        return self::where('patient_id', $patient_id)->where('status',1)->whereRaw("hid = '". strtolower($healthservices)."'")->orderBy('created_at', 'desc')->first();
        // return 
    }

    public function healthservices(){
        return $this->belongsTo('RCare\Org\OrgPackages\HealthServices\src\Models\HealthServices');
    }

    public static function self($id)
    {
        $ids=sanitizeVariable($id);
        return self::where('id', $ids)->orderBy('created_at', 'desc')->first();
    }
    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }
    // public function template(){
    //     return $this->belongsTo('RCare\Rpm\Models\Template','template_type_id');
    // }
    // public function service(){
    //     return $this->belongsTo('RCare\Rpm\Models\RcareServices','module_id');
    // }
    // public function subservice(){
    //     return $this->belongsTo('RCare\Rpm\Models\RcareSubServices','component_id');
    // }
}
