<?php

namespace RCare\Org\OrgPackages\Providers\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use Carbon\Carbon;
use RCare\System\Traits\DatesTimezoneConversion;


class ProviderType extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     // DatesTimezoneConversion;

    protected $table ='ren_core.provider_types';

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
    // public $timestamps = true;
    // protected $dates = [
    //      'created_at',
    //     'updated_at'
    // ];
    protected $fillable = [
        'id',
        'provider_type',
        'address',
        'phone_no',
        'created_by',
        'updated_by',
        'is_active',
        'created_at',
        'updated_at',
    ];



    public static function self($id)
    {   $id = sanitizeVariable($id);
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }
     
    public static function activeProvidertype()
    {
        return ProviderType::where("is_active", 1)->orderBy('provider_type','asc')->get();
    }

    public function provider_type()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Providers\src\Models\Providers', 'provider_type_id');
    }
    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }

}