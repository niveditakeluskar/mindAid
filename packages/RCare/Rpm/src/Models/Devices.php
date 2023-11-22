<?php

namespace RCare\Rpm\Models;
//use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Support\Facades\DB;
class Devices extends Model
{
     //
     use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='ren_core.devices';

     protected $population_include = [
        "id"
    ];
  
  protected $dates = [
        'created_at',
        'updated_at'        
    ];
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [
      'id',
      'device_name',
      'vitals',
      'created_by',
      'updated_by',
      'created_at',
      'updated_at'   
      ];

        public function partnerdevice(){
        return $this->belongsTo('RCare\Rpm\Models\Partner_Devices','device_id');
    }

}
