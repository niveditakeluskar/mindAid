<?php

namespace RCare\Patients\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class PatientSurgery extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='patients.patient_surgery';


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
        'surgery_date'
    ];
        protected $fillable = [
        'id', 
        'patient_id', 
        'surgery_id', 
        'surgery_date',
        'status',
        'created_at', 
        'updated_at', 
        'created_by', 
        'updated_by',
    ];

    public static function latest($patientId)
    {
        $patient_id=sanitizeVariable($patientId);
        return self::where('patient_id', $patient_id)->orderBy('created_at', 'desc')->first();
    }
    public static function self($id)
    {
         $ids=sanitizeVariable($id);
        return self::where('id', $ids)->orderBy('created_at', 'desc')->first();
    }
}
