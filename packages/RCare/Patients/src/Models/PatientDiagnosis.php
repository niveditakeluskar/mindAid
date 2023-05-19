<?php

namespace RCare\Patients\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;
 use RCare\System\Traits\DatesTimezoneConversion;

class PatientDiagnosis extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='patients.patient_diagnosis_codes';


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
    ];
    
     
    protected $fillable = [
        'id', 
        'patient_id', 
        'uid',
        'code', 
        'condition', 
        'symptoms',
        'tasks', 
        'goals', 
        'comments', 
        'created_at', 
        'updated_at', 
        'created_by', 
        'updated_by',
        'support',
        'diagnosis',
        'status' 
    ];

  public static function latest($patientId)
    {
        $patient_id=sanitizeVariable($patientId);
        return self::where('patient_id', $patient_id)->where('status',1)->orderBy('created_at', 'desc')->first();
    }

    // public static function self($patientId,$id)
    // {
    //     return self::where('patient_id', $id)->where('diagnosis', $patientId)->first();
    // }
    public static function selfDiagnosis($patientId,$id)
    {
        $patient_id=sanitizeVariable($patientId);
        $idd=sanitizeVariable($id);
        return self::where('patient_id', $idd)->where('diagnosis', $patient_id)->orderBy('created_at', 'desc')->first();
    }
    public static function selfDiagnosisByEditId($patientId,$id)
    {
        $patient_id=sanitizeVariable($patientId);
        $idd=sanitizeVariable($id);
        return self::where('patient_id', $patient_id)->where('id', $idd)->orderBy('created_at', 'desc')->first();
    }
    public static function self($patientId,$id)
    {
        $patient_id=sanitizeVariable($patientId);
        $idd=sanitizeVariable($id);
        return self::where('patient_id', $idd)->where('id', $patient_id)->orderBy('created_at', 'desc')->first();
    }

    public function carePlanTemplate(){
        return $this->belongsTo('RCare\Org\OrgPackages\CarePlanTemplate\src\Models\CarePlanTemplate','condition');
    }
    public function users(){
        return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','created_by');
    }
    
    public function users_created_by(){
        return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','created_by');
    }
    public function users_updated_by(){
        return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }
    public function diagnosis(){
        return $this->belongsTo('RCare\Org\OrgPackages\Diagnosis\src\Models\Diagnosis','diagnosis');
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


// namespace RCare\Patients\Models;

//  use RCare\System\Support\DashboardFetchable;
//  use RCare\System\Support\ModelMapper;
//  use RCare\System\Support\GeneratedFillableModel;
//  // use Carbon\Carbon;
//  use Illuminate\Database\Eloquent\Model;
//  use RCare\System\Traits\DatesTimezoneConversion;

// class PatientDiagnosis extends Model
// {
//     //
//     use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
//      protected $table ='patients.patient_diagnosis_codes';


//       /**
//      * The attributes that are mass assignable.
//      *
//      * @var array
//      */
	 
//     protected $population_include = [
//         "id"
//     ];

//      protected $dates = [
//         'created_at',
//         'updated_at',
//     ];
    
     
//         protected $fillable = [
//         'id', 
//         'patient_id', 
//         'uid',
//         'code', 
//         'condition', 
//         'symptoms',
//         'tasks', 
//         'goals', 
//         'comments', 
//         'created_at', 
//         'updated_at', 
//         'created_by', 
//         'updated_by',
//         'support',
//         'diagnosis'
//     ];

//     public static function latest($patientId)
//     {
//         return self::where('patient_id', $patientId)->orderBy('created_at', 'desc')->first();
//     }

//     // public static function self($patientId,$id)
//     // {
//     //     return self::where('patient_id', $id)->where('diagnosis', $patientId)->first();
//     // }
//     public static function selfDiagnosis($patientId,$id)
//     {
//         return self::where('patient_id', $id)->where('diagnosis', $patientId)->orderBy('created_at', 'desc')->first();
//     }
//     public static function self($patientId,$id)
//     {
//         return self::where('patient_id', $id)->where('id', $patientId)->orderBy('created_at', 'desc')->first();
//     }

//     public function carePlanTemplate(){
//         return $this->belongsTo('RCare\Org\OrgPackages\CarePlanTemplate\src\Models\CarePlanTemplate','condition');
//     }
//     public function users(){
//         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','created_by');
//     }
//     public function diagnosis(){
//         return $this->belongsTo('RCare\Org\OrgPackages\Diagnosis\src\Models\Diagnosis','diagnosis');
//     }
//     // public function template(){
//     //     return $this->belongsTo('RCare\Rpm\Models\Template','template_type_id');
//     // }
//     // public function service(){
//     //     return $this->belongsTo('RCare\Rpm\Models\RcareServices','module_id');
//     // }
//     // public function subservice(){
//     //     return $this->belongsTo('RCare\Rpm\Models\RcareSubServices','component_id');
//     // }
// } 