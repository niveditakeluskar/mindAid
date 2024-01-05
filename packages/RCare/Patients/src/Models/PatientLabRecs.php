<?php

namespace RCare\Patients\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;
 use RCare\System\Traits\DatesTimezoneConversion;

class PatientLabRecs extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='patients.patient_lab_recs';
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $population_include = [
        "id"
    ];
    
    protected $dates = [
         'rec_date',
        'created_at',
        'updated_at'
    ];
    
        protected $fillable = [
        'id', 
        'patient_id', 
        'uid', 
        'rec_date',
        'lab_test_id',
        'lab_test_parameter_id',
        'reading',
        'high_val',
        'notes', 
        'created_by', 
        'updated_by', 
        'created_at', 
        'updated_at', 
        'lab_date'
    ];
 
    public function labTest(){
        // return $this->belongsTo('RCare\Org\OrgPackages\Labs\src\Models\Labs','id'); // this is not used anywhere so updated
        return $this->belongsTo('RCare\Org\OrgPackages\Labs\src\Models\Labs'); 
    }

    public function labsParameters(){
        // return $this->hasMany('RCare\Org\OrgPackages\Labs\src\Models\LabsParam', 'lab_test_parameter_id', 'id');
        return $this->hasMany('RCare\Org\OrgPackages\Labs\src\Models\LabsParam','id','lab_test_parameter_id');
    } 
// 
    //ren_core.rcare_lab_test_param_range
    //ren_core.rcare_lab_tests

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
