<?php

namespace RCare\RCareAdmin\AdminPackages\Configuration\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class Configurations extends Model
{
    use DatesTimezoneConversion;
    protected $table ='rcare_admin.configuration';

    protected $population_include = [
        "id"
    ];
    
    protected $dates = [
        'created_at',
        'updated_at'
    ];  
    protected $fillable = [
        'config_type',
        'org_id',
        'configurations',
        'status',
        'created_by',
        'updated_by',  
    ];
    public function users()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\users\src\Models\users','updated_by');
    }
}
