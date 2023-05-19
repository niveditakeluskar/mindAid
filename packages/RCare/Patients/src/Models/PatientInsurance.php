<?php

namespace RCare\Patients\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
class PatientInsurance extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='patients.patient_insurance';

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
        'ins_code',
        'ins_id', 
        'ins_type', 
        'ins_provider',
        'ins_plan', 
        'ins_mobile', 
        'ins_phone_2', 
        'ins_email', 
        'updated_by',
        'created_by'
    ];
	
	public static function patientsins()
    {
       return self::get();
       // return $this->belongsTo('RCare\Patients\Models\Patients','patient_id');
    }
}