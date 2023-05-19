<?php

namespace RCare\Patients\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;
 use RCare\System\Traits\DatesTimezoneConversion;
class PatientMedication extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion ;
     protected $table ='patients.patient_medication';


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
        // 'time'
    ];
     
    protected $fillable = [
        'id', 
        'patient_id', 
        'uid',
        'med_id', 
        'description', 
        'purpose', 
        'dosage', 
        'strength', 
        'frequency',
        'route',
        'duration',
        'drug_reaction',
        'pharmacogenetic_test',
        'created_by', 
        'updated_by',
        'review',
        'med_name',
        'pharmacy_name',
        'pharmacy_phone_no',
        'status'
    ]; 
    
    public static function self($id)
    {
        $ids=sanitizeVariable($id);
        return self::where('id', $ids)->orderBy('created_at', 'desc')->first();
    }
    
    public static function latest($patientId)
    {
        $patient_id=sanitizeVariable($patientId);
        return self::where('patient_id', $patient_id)->where('status',1)->orderBy('created_at', 'desc')->first();
    }

    public function medication()
    {       
        return $this->belongsTo('RCare\Org\OrgPackages\Medication\src\Models\Medication', 'med_id');
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
