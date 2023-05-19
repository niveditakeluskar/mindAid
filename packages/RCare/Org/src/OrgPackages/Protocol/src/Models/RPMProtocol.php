<?php


namespace RCare\Org\OrgPackages\Protocol\src\Models;
use Illuminate\Database\Eloquent\Model;
 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use RCare\System\Traits\DatesTimezoneConversion;

class RPMProtocol extends Model
{
     use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='ren_core.rpm_protocol';


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
     ];
	 
    protected $fillable = [  
        'id',
        'device_id',
        'file_name',
        'created_by',
        'updated_by',
        'status'
     ];
   
     public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','created_by');
    }
     public function devices()
    {
         return $this->belongsTo('RCare\Rpm\Models\Devices','device_id');
    }
}
