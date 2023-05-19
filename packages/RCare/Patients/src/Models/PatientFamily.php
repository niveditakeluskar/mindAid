<?php

namespace RCare\Patients\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;
class PatientFamily extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='patients.patient_family';

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
        'patient_id',
        'uid',
        'address',
        'fname',
        'lname',
        'age',
        'relationship',
        'mobile',
        'phone_2',
        'email',
        'created_by',
        'updated_by',
        'review',
        'additional_notes',
        'tab_name',
        'relational_status',
        'patient_living_with',
        'created_at',
        'updated_at'
    ];

    public static function self($id, $relation)
    {
        $ids=sanitizeVariable($id);
        $reltn=sanitizeVariable($relation);
        return self::where('id', $ids)->whereRaw("tab_name = '". strtolower($reltn)."'")->orderBy('created_at', 'desc')->first();
    }
    
     public static function latest($patientId, $relation)
    {        
        $patient_id=sanitizeVariable($patientId);
         $reltn=sanitizeVariable($relation);
         return self::where('patient_id', $patient_id)->whereRaw("tab_name = '". strtolower($reltn)."'")->orderBy('created_at', 'desc')->first();      
    }
    
     public static function activeRelationship()
        {      
         return PatientFamily::distinct()->where("relationship","!=", null)->orderBy('relationship','asc')->get(['relationship']);
        }
    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }
}