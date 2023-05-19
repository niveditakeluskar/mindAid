<?php

namespace RCare\Org\OrgPackages\Labs\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;

class LabsParam extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='ren_core.rcare_lab_test_param_range';

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
        'lab_test_id',
        'parameter',
        'status',
        // 'created_by',
        // 'updated_by'
    ]; 

    public static function activeLabs()
    {
        return Labs::where("status", 1)->orderBy('description', 'asc')->get();
    }
    public static function self($patientId)
    {   $patientId = sanitizeVariable($patientId);
        return self::where('id', $patientId)->orderBy('created_at', 'desc')->first();
    }
 
    // public function Labs(){
    //     return $this->belongsTo('RCare\Org\OrgPackages\Labs\src\Models\Labs'); 
    // }  

    public function PatientLabRecs(){
        // return $this->belongsTo('RCare\Patients\Models\PatientLabRecs','id','lab_test_parameter_id');
        return $this->belongsTo('RCare\Patients\Models\PatientLabRecs', 'lab_test_parameter_id', 'id');
    }
} 