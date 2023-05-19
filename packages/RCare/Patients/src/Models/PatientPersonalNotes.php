<?php

namespace RCare\Patients\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
class PatientPersonalNotes extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='patients.personal_notes';

        /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
	 
    protected $population_include = [
        "id"
    ];
    
    protected $dates = [
         'rec_date',
        'created_at',
        'updated_at'
    ];
    
    protected $fillable = [
        'uid',
        'patient_id',
        'module_id',
        'component_id',
        'rec_date',
        'status',
        'created_by',
        'updated_by',
        'personal_notes',
        'created_at',
        'updated_at',
    ];
	
    public static function latest($patientId)
    {
        $patient_id=sanitizeVariable($patientId);
        return self::where('patient_id', $patient_id)->whereMonth('rec_date', date('m'))->whereYear('rec_date', date('Y'))->orderBy('created_at', 'desc')->first();
    }

    public static function self($patientId)
    {
        $patient_id=sanitizeVariable($patientId);
        return self::where('patient_id',$patient_id)->first();
    }
    
    public function users(){
        return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','created_by');
    }
}