<?php

namespace RCare\Patients\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class PatientAlert extends Model
{
    //
     use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='patients.partner_patient_alerts';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
       protected $dates = [
        'created_at' ,
        'updated_at'    
      ];
	 
        protected $fillable = [
        'id',
        'threshold',
        'timestamp',
        'addressedby',
        'careplanid',
        'expiretime',
        'limitover',
        'flag',
        'device_code',
        'addressedtime',
        'notes',
        'observation_id',
        'addressed',
        'readingtimestamp',
        'officeid',
        'measuredat',
        'type',
        'patient_id',
        'mrn',
        'created_by',
        'updated_by',
        'webhook_obervation_id',
        'alert_status',
        'match_status'      
    ];

  
}
