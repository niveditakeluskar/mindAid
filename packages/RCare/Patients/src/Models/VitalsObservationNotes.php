<?php

namespace RCare\Patients\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class VitalsObservationNotes extends Model
{
use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='patients.vitals_observation_notes';

    protected $population_include = ["id"];
    protected $dates = [ 'created_at','updated_at'];
    
    protected $fillable = [
		   'id',
       'patient_id',
       'notes',
       'device_id',
       'observation_id',
       'rpm_observation_id',
       'created_by',
       'updated_by',
       ];
	

       public static function latest($patientId)
       {   $patientId = sanitizeVariable($patientId);
           $currentMonth = date('m');
           $year = Carbon::now()->year;
           return self::where('patient_id', $patientId)->whereYear('created_at', $year)->whereMonth('created_at', $currentMonth)->orderBy('created_at', 'desc')->first();
       }

}