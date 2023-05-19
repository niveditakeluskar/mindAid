<?php

namespace RCare\Patients\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use DB;
use RCare\System\Traits\DatesTimezoneConversion;
class PatientProvider extends Model
{
    //
use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='patients.patient_providers';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $population_include = [
        "id"
    ];
    
    protected $dates = [
        'last_visit_date',
        'created_at',
        'updated_at'
    ];

    
    protected $fillable = [
        'patient_id',
        'uid',
        'provider_id',
        'provider_subtype_id',
        'practice_id',
        'address',
        'phone_no',
        'last_visit_date',
        'provider_type_id',
        'created_by',
        'review',
        'provider_name' ,
        'practice_emr',
        'specialist_id',  
        'updated_by',
        'is_active',
        // 'status'
    ];

     public static function self($id)
    {
        $ids=sanitizeVariable($id);
        return self::where('id', $ids)->orderBy('created_at', 'desc')->first();
    }


     public static function latest($patientId,$type)
    {
        $patient_id=sanitizeVariable($patientId);
        $types=sanitizeVariable($type);
        return self::where('patient_id', $patient_id)->where('is_active',1)->whereRaw("provider_type_id = '". strtolower($types)."'")->orderBy('id', 'desc')->orderBy('created_at', 'desc')->first();
    }

    public function provider_subtype()
    {        
        return $this->belongsTo('RCare\Org\OrgPackages\Providers\src\Models\ProviderSubtype', 'provider_subtype_id');
    }

    public function practice()
    {       
        return $this->belongsTo('RCare\Org\OrgPackages\Practices\src\Models\Practices', 'practice_id')->where('is_active','1');
    }
    public function provider()
    {       
        return $this->belongsTo('RCare\Org\OrgPackages\Providers\src\Models\Providers', 'provider_id')->where('is_active','1');
    }
    public function pcp_provider()
    {       
        return $this->belongsTo('RCare\Org\OrgPackages\Providers\src\Models\Providers', 'provider_id')->whereRaw("provider_type_id = '1'");
    }
    public function specility()
    {  
        return $this->belongsTo('RCare\Org\OrgPackages\Providers\src\Models\Speciality', 'specialist_id');
    }
    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }
    public static function getPatientPCPProvider($patientId)
    {
        // return DB::table('patients.patient_providers')
        // ->select('ren_core.practices.name as practice_name', 'ren_core.practices.outgoing_phone_number as outgoing_phone_number', 'ren_core.providers.name as provider_name', 'patients.patient_providers.practice_emr as patient_emr')
        // ->join('ren_core.practices', 'ren_core.practices.id', '=', 'patients.patient_providers.practice_id')
        // ->join('ren_core.providers', 'ren_core.providers.id', '=', 'patients.patient_providers.provider_id')
        // ->where('patients.patient_providers.patient_id', $patientId)
        // ->where('patients.patient_providers.provider_type_id', '1')
        // ->orderBy('patients.patient_providers.created_at', 'desc')
        // ->first();
       $patient_id = sanitizeVariable($patientId);
        return self::with(['practice','provider'])
         ->where('patient_id', $patient_id)
        ->where('provider.provider_type_id', '1')
        ->orderBy('created_at', 'desc')
         ->get(['practice.name AS practice_name', 
         'practice.outgoing_phone_number as outgoing_phone_number',
         'provider.name as provider_name','practice_emr as patient_emr']);

    }
   
}
  