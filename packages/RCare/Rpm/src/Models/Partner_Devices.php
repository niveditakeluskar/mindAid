<?php

namespace RCare\Rpm\Models;
use Illuminate\Database\Eloquent\Model;

class Partner_Devices extends Model
{
    //
     protected $table ='ren_core.partner_devices_listing';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
        protected $fillable = [
        'id',
        'partner_id',
        'device_name_api',
        'device_id',
        'device_attr',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'status',
        'device_order_seq'

    ];

     public function device(){
        return $this->belongsTo('RCare\Rpm\Models\Devices','device_id');
    }

}
