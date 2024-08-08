<?php

namespace RCare\Org\OrgPackages\Practices\src\Models;
use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
use Carbon\Carbon;
use RCare\System\Traits\DatesTimezoneConversion;
use RCare\Org\OrgPackages\Users\src\Models\Users;


use Illuminate\Database\Eloquent\Model;

class PracticesGroup extends Model
{   
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    //
     protected $table ='ren_core.practicegroup';


    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $population_include = [
        "id"
    ];
    
    
    public $timestamps = true;
    protected $dates = [
        'created_at',
        'updated_at'
    ];
	 
    protected $fillable = [
        'id', 
        'practice_name', 
        'status',
        'created_by',
        'updated_by',
        'assign_message',
        'quality_metrics'
    ];

  public static function activeGrpPractices()
    {
        $prac= PracticesGroup::where("status", 1)->orderBy('practice_name','asc')->get();
        return $prac;
    }

    /**
     * Get the users that are assigned to this practice
     *
     * @return array
     */
    public static function self($id)
    {
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }
    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }
}
  