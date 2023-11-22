<?php

namespace RCare\Rpm\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class DeviceTraining extends Model
{
    //
     use DatesTimezoneConversion;
     protected $table ='rpm.device_training';


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
        'practice_id', 'UID', 'device_id', 'download_protocol_completed', 'usage_instruction_completed','device_training_completed',
        'created_by','updated_by','created_at','updated_at','status'

    ];

}
