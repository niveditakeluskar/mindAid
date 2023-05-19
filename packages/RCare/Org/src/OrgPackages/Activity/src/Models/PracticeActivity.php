<?php

namespace RCare\Org\OrgPackages\Activity\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;


class PracticeActivity extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='ren_core.practice_activity_time_required';

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
    protected $fillable = [ 
        'id',
        'activity_id',
        'practice_id',
        'time_required',
        'created_by',
        'updated_by',
        'status',
        'created_at', 
        'updated_at',
        'practice_group'    
    ]; 
    
    public function practices()
    { 
        return $this->belongsTo('RCare\Org\OrgPackages\Practices\src\Models\Practices','practice_id');   
    }
 
    public function practicegroup()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Practices\src\Models\PracticesGroup','practice_group');   
    }

}