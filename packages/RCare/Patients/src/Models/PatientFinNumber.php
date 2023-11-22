<?php

namespace RCare\Patients\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class PatientFinNumber extends Model
{
    //
    use DashboardFetchable, ModelMapper;
     protected $table ='patients.patient_fin';


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
        'status',
        'fin_number', 
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
         

    ];

    public function patients(){
        return $this->belongsTo('RCare\Patients\src\Models\Patients','patient_id');  
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
