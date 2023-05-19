<?php

namespace RCare\Org\OrgPackages\Partner\src\Models;  
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\Patients\Models\PatientMedication;
use RCare\System\Traits\DatesTimezoneConversion;

class PartnerApiConfiguration extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='ren_core.partner_api_configuration';

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
    public $timestamps = true;
    
    protected $fillable = [
        'id',
        'url',
        'username',
        'password',
        'status',
        'env',    
        'partner_id',   
        'created_at',
        'updated_at'
    ];

  
	
	public static function self($id)
    {   $id  = sanitizeVariable($id);
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }

    public static function activePartner()
    {
        return self::where('status', 1)->orderBy('created_at', 'desc')->first();
    }

    // public function users()
    // {
    //      return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    // }
}