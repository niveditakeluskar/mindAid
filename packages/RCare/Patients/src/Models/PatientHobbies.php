<?php

namespace RCare\Patients\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class PatientHobbies extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='patients.patient_hobbies';

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
        'location',
        'hobbies_status',
        'hobbies_name',
        'frequency',
        'with_whom',
        'notes',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public static function self($id)
    {
        $ids=sanitizeVariable($id);
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }
    
    
    public static function latest($patientId)
    {
        $patient_id=sanitizeVariable($patientId);
        return self::where('patient_id', $patient_id)->orderBy('created_at', 'desc')->first();
    }
    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }

}