<?php

namespace RCare\Org\OrgPackages\Holiday\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;

class Holiday extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='ren_core.holiday';

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
        'event',
        'date',
        'created_by',
        'updated_by',
        'status'
    ];

    // public static function activeDiagnosis()
    // {
    //     return Diagnosis::where("status", 1)->orderBy('condition','asc')->get();
    // }

    // public static function self($patientId)
    // {   $patientId = sanitizeVariable($patientId);
    //     return self::where('id', $patientId)->orderBy('created_at', 'desc')->first();
    // }

    // public static function latest($patientId)
    // {  $patientId = sanitizeVariable($patientId);
    //     return self::where('id', $patientId)->orderBy('created_at', 'desc')->first();
    // }
    
    // public function DiagnosisCode()
    // {
    //      return $this->belongsTo('RCare\Org\OrgPackages\Diagnosis\src\Models\DiagnosisCode','code');
    // }

    // public function CareplanTemplate()
    // {
    //      return $this->belongsTo('RCare\Org\OrgPackages\CarePlanTemplate\src\Models\CarePlanTemplate','condition');
    // }
    // public function users()
    // {
    //      return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    // }
    

}