<?php

namespace RCare\RCareAdmin\AdminPackages\AccessMetrics\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;

class Access extends Model
{
    //
     protected $table ='rcare_admin.rcare_role_services';


      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
        protected $fillable = [
        'id', 'role_id', 'service_id', 'crud', 'status'

    ];
}
