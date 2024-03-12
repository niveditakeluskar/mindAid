<?php

namespace RCare\Org\OrgPackages\Providers\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use Carbon\Carbon;
use RCare\System\Traits\DatesTimezoneConversion;


class Speciality extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
     // DatesTimezoneConversion;

    protected $table ='ren_core.speciality';

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
        'speciality', 
        'status',      
        'created_by',
        'updated_by',        
        'created_at',
        'updated_at'
    ];



    public static function self($id)
    {   $id = sanitizeVariable($id);
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }
    
    public static function activeSpeciality()
    {
        return Speciality::where("status", 1)->orderBy('speciality','asc')->get();
    }
    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }
    
}