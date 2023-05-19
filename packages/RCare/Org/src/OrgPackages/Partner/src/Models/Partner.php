<?php

namespace RCare\Org\OrgPackages\Partner\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\Patients\Models\PatientMedication;
use RCare\System\Traits\DatesTimezoneConversion;

class Partner extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='ren_core.partners';

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
        'name',
        'add1',
        'add2',
        'email',
        'category',
        'city',
        'state',
        'zip',
        'phone',
        'contact_person',
        'contact_person_phone',
        'contact_person_email',
        'created_by',
        'updated_by',
        'status',
    ];

  
	
	public static function self($id)
    {   $id  = sanitizeVariable($id);
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }
    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }

    public static function activePartner(){
        $prat= self::where("status", 1)->orderBy('name','asc')->get();
        return $prat;
    }
}