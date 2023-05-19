<?php

namespace RCare\Org\OrgPackages\Users\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Session, DB;

// class Users extends GeneratedFillableModel
class DomainFeatures extends Model
{ 
      use DashboardFetchable, ModelMapper,  DatesTimezoneConversion;

    protected $table ='ren_core.domain_features';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'id',
        'url',
        'features',
        'status',
        'created_by',
        'updated_by'
    ];
    
   
}