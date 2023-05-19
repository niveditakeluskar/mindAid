<?php

namespace RCare\Org\OrgPackages\RPMAlert\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;
use RCare\Patients\Models\PatientDevices;
use RCare\Patients\Models\PatientProvider;

class PatientAlert extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='patients.patient_alerts';

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
            "id",
            "threshold",
            "timestamp" ,
            "addressedby",
            "careplanid",
            "expiretime",
            "limitover",
            "flag",
            "device_code",
            "addressedtime",
            "notes",
            "observation_id",
            "addressed",
            "readingtimestamp",
            "officeid",
            "measuredat",
            "type",
            "patient_id",
            "mrn",
            "created_by",
            "updated_by"
           
    ];

    
    public static function self($patientId)
    {   $patientId = sanitizeVariable($patientId);
        return self::where('id', $patientId)->orderBy('created_at', 'desc')->first();
    }
    public function patient()
    {
         return $this->belongsTo('RCare\Patients\Models\Patients','patient_id');
    }
    public function praticeid()
    {
         return $this->belongsTo('RCare\Patients\Models\PatientProvider','patient_id');
    }
     public function pratice()
    {
         return $this->belongsTo('RCare\Patients\Models\PatientProvider','patient_id');
    }
}