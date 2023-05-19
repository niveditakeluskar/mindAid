<?php

namespace RCare\Patients\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class PatientOrders extends Model
{
    //
    use DashboardFetchable, ModelMapper;
     protected $table ='patients.patient_orders';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
        protected $fillable = [
        'id', 
        'patient_id', 
        'order_date',
        'partner_mrn',
        'device',
        'shipped',
        'created_at',
        'updated_at', 
        'created_by', 
        'updated_by',        
        'hub',
        'carrier_name',
        'tracking_no',
        'order_id'      

    ];

  

    // public function devices(){
    //     return $this->belongsTo('RCare\Org\OrgPackages\Devices\src\Models\Devices','device_id');
    // }
    
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
