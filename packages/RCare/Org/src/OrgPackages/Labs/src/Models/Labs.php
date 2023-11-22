<?php

namespace RCare\Org\OrgPackages\Labs\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;


class Labs extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='ren_core.rcare_lab_tests';

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
        'description',
        'status',
        'created_by',
        'updated_by'
    ];
    public function LabsParam(){
        return $this->hasMany('RCare\Org\OrgPackages\Labs\src\Models\LabsParam');
    }
    public static function activeLabs()
    {
        return Labs::where("status", 1)->orderBy('description', 'asc')->get();
    }
    public static function self($patientId)
    {   $patientId = sanitizeVariable($patientId);
        return self::where('id', $patientId)->orderBy('created_at', 'desc')->first();
    }
    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }     
    public function PatientLabRecs(){
        return $this->hasMany('RCare\Patients\Models\PatientLabRecs');
    }
}