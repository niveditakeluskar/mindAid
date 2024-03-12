<?php

namespace RCare\Org\OrgPackages\CarePlanTemplate\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
//use RCare\System\Traits\DatesTimezoneConversion;
use RCare\System\Traits\DatesTimezoneConversion;
use RCare\Org\OrgPackages\Diagnosis\src\Models\DiagnosisCode;

class CarePlanTemplate extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='ren_core.care_plan_templates';
	
	
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
	// protected $dates = [
 //        'created_at',
 //        'updated_at'
 //    ];
     
   
		
    protected $fillable = [
        'id',
        'code',
        'condition',
        'symptoms',
        'goals',
        'tasks',
        'medications',
        'support',
        'allergies',
        'created_by',
        'updated_by',
        'status',
        'created_at',
        'updated_at',
        'diagnosis_id',
        'numbers_tracking',
        'health_data',
        'labs',
        'vitals'
    ];
    
    public static function activeCarePlanTemplate()
    {
        return CarePlanTemplate::where("status", 1)->orderBy('condition', 'asc')->get();
    }
 
    public static function self($patientId)
    {   $patientId = sanitizeVariable($patientId);
        return self::where('id', $patientId)->orderBy('created_at', 'desc')->first();
    }

    public static function diagnosisCode($Id)
    {   $Id = sanitizeVariable($Id);
       // return self::where('diagnosis_id', $Id)->orderBy('created_at', 'desc')->first(); // changes for upper case 13 Feb 2023 ashwini mali
         return self::select(DiagnosisCode::raw('upper(code) as code,condition,symptoms,goals,tasks,medications,support,allergies,created_by,updated_by,status,created_at,updated_at,diagnosis_id,numbers_tracking,health_data,labs,vitals'))->where('diagnosis_id', $Id)->orderBy('created_at', 'desc')->first();
    }
	/*
    public static function latest($patientId)
    {
        return self::where('diagnosis_id', $patientId)->orderBy('created_at', 'desc')->first();
    }
    */
    public function Diagnosis()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Diagnosis\src\Models\Diagnosis','condition');
    }

    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }
} 