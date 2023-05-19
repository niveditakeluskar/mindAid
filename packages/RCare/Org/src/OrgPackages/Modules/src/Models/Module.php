<?php

namespace RCare\Org\OrgPackages\Modules\src\Models;
use Illuminate\Database\Eloquent\Model;
use Eloquent;
use RCare\System\Traits\DatesTimezoneConversion;


class Module extends Model
{
    //
    use  DatesTimezoneConversion;
    protected $table ='ren_core.modules';


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
        'module', 
        'status',
        'patients_service',
        'created_by',
        'updated_by'
    ];

    public static function activeModule()
    {
        // return Module::all()->where("status", 1);
         return Module::where("status", 1)->where('patients_service',1)->orderBy('module','asc')->get();
   
    }

     public static function activeMasterModule()
    {       
         return Module::where("status", 1)->orderBy('module','asc')->get();   
    }

    public static function mainModule()
    {
        // return Module::all()->where("status", 1); RPM CCm only
         return Module::where("status", 1)->whereIn('id',array(2,3))->orderBy('module','asc')->get();
    }


    public function questionnaireTemplates()
    {
        return $this->hasMany('RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate');
    }
	
	public function patientServices()
    {
        return $this->hasMany('RCare\Patients\Models\PatientServices');
    }

    public function users()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\users\src\Models\users','updated_by');
    }
}
  