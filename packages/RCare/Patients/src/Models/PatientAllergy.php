<?php

namespace RCare\Patients\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class PatientAllergy extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion ;
    protected $table ='patients.patient_allergy';


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
        'updated_at'
    ];
        protected $fillable = [
        'id', 
        'patient_id', 
        'uid',
        'specify', 
        'allergy_type', 
        'type_of_reactions', 
        'severity', 
        'course_of_treatment', 
        'notes', 
        'created_at', 
        'updated_at', 
        'created_by', 
        'updated_by', 
        'status',
        'review',
        'allergy_status'

    ];

   
    public static function latest($patientId,$type)
    {
        $patient_id=sanitizeVariable($patientId);
        $typeid=sanitizeVariable($type);
        return self::where('patient_id',$patient_id)->where('status',1)->whereRaw("allergy_type = '". strtolower($typeid)."'")->orderBy('created_at', 'desc')->first();
    }
    public static function self($id)
    {
        $idd=sanitizeVariable($id);
        return self::where('id', $idd)->orderBy('created_at', 'desc')->first();
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
