<?php

namespace RCare\Patients\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class PatientDevices extends Model
{
    //
    use DashboardFetchable, ModelMapper;
     protected $table ='patients.patient_devices';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     * 
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
        'patient_id',
        'partner_device_id',
        'device_code', 
        'status', 
        'activation_date',
        'deactivation_date',
        'device_attr',
        'created_by',
        'updated_by',
        'order_id',        
        'source_id',
        'created_at',
        'updated_at',
        'device_id',
        'vital_devices',
        'mrn_no',
        'partner_id'
         

    ];

    public function patients(){
        return $this->belongsTo('RCare\Patients\src\Models\Patients','patient_id');  
    }

    public function devices(){
        return $this->belongsTo('RCare\Org\OrgPackages\Devices\src\Models\Devices','device_id');
    }
    public static function device() 
    {   
        $id  = '1'; //sanitizeVariable($patient_id);
        return self::where('status', $id)->orderBy('created_at', 'desc')->get();
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
