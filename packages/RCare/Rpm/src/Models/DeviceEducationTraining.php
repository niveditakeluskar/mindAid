<?php

namespace RCare\Rpm\Models;
//use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Support\Facades\DB;
class DeviceEducationTraining extends Model
{
     //
     use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='rpm.device_education_training';

     protected $population_include = [
        "id"
    ];
  
  protected $dates = [
        'created_at',
        'updated_at',
        'record_date'
              
    ];
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
      'id',
      'record_date',
      'patient_id',
      'device_id',
      'status',
      'created_by',
      'updated_by',
      'created_at',
      'updated_at',      
      'notes',
      'time'
      ];

        public function partnerdevice(){
        return $this->belongsTo('RCare\Rpm\Models\Partner_Devices','device_id');
    }

}
