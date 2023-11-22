<?php

namespace RCare\Org\OrgPackages\Providers\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use Carbon\Carbon;
use RCare\System\Traits\DatesTimezoneConversion;


class ProviderSubtype extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    // DatesTimezoneConversion;

    protected $table ='ren_core.provider_subtype';

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
    //     'created_at',
    //     'updated_at'
    // ];
    protected $fillable = [
        'id',
        'provider_type_id',
        'sub_provider_type',
        'address',
        'phone_no',
        'created_by',
        'updated_by',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public static function self($id)
    {   $id = sanitizeVariable($id);
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }

    public static function activeSubPractices()
    {
        return ProviderSubtype::where("is_active", 1)->orderBy('sub_provider_type','asc')->get();
    }
    //foe only specialist sub type ->where('provider_type_id','2')
    
    //only pcp sub type data
     public static function activeSubPracticesPCP() 
    {
        return self::where("is_active", 1)->orderBy('sub_provider_type','asc')->get();//->where('provider_type_id','1') 
    }
    public function provider_type()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Providers\src\Models\ProviderType', 'provider_type_id');
    }

}