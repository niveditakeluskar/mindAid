<?php

namespace RCare\Rpm\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Carbon\Carbon;

class BulkUploadDevices extends Model
{
    // 
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='rpm.duplicate_bulk_upload_device';
    public $timestamps = TRUE;
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
        'record_date'
       
    ]; 

    protected $fillable = [
        'id', 
        'patient_id', 
        'partner',
        'practice',
        'fname',
        'lname',
        'dob',
        'device_code',
        'device',
        'created_by',
        'updated_by',
        'created_at', 
        'updated_at', 
        'record_date',
        'status'


       
        
    ];
	
    public static function latest($patientId)   //added by pranali 27Oct2020
    {
        $currentMonth = date('m');
        $year = Carbon::now()->year;
        return self::where('patient_id', $patientId)->whereYear('created_at', $year)->whereMonth('created_at', $currentMonth)->first();
        
    }
}
