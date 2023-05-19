<?php

namespace RCare\Patients\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 use RCare\System\Traits\DatesTimezoneConversion;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;


class PatientReading extends Model
{
    //
    use DashboardFetchable, ModelMapper;//,DatesTimezoneConversion;
     protected $table ='patients.patient_readings';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 // protected $population_include = [
  //       "id"
  //   ];
    
    // protected $dates = [
    //     'R'
    //     'created_at',
    //     'updated_at'
      
    // ];
        protected $fillable = [
        'id', 
        'patient_id', 
        'reading_id',
        'reading_date',
        'partner_mrn',        
        'device_id',       
        'reading',       
        'created_by', 
        'updated_by', 
        'created_at',  
        'updated_at'  

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
