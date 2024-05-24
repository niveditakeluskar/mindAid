<?php

namespace RCare\Patients\Models;

 use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
 // use Carbon\Carbon;
 use Illuminate\Database\Eloquent\Model;
 use RCare\System\Traits\DatesTimezoneConversion;

class CountryCode extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     protected $table ='patients.countries';
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $population_include = [
        "id"
    ];
    
        protected $fillable = [
        'id', 
        'countries_name', 
        'countries_iso_code', 
        'countries_isd_code',
    ];

    public static function country(){
        return CountryCode::orderBy('countries_name','asc')->get();
    }
 
}
 