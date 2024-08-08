<?php

namespace RCare\Org\OrgPackages\Medication\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\Patients\Models\PatientMedication;
use RCare\System\Traits\DatesTimezoneConversion;

class Medication extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='ren_core.surgery';
 
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
    public $timestamps = true;
    
    protected $fillable = [
        'id',
        'code',
        'name',
        'description', 
        'category',
        'sub_category',
        'duration',
        'created_by',
        'updated_by',
        'status',
    ];

    public static function activeMedication()
    {
        return Medication::where("status", 1)->orderBy('description','asc')->distinct('description')->get();
    }
	
	public function patientMedication()
    {
        return $this->hasMany('RCare\Patients\Models\PatientMedication', 'med_id');
    }
	
	public static function self($patientId)
    {   $patientId  = sanitizeVariable($patientId);
        return self::where('id', $patientId)->orderBy('created_at', 'desc')->first();
    }
    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }
}