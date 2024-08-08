<?php

namespace RCare\RCareAdmin\AdminPackages\License\src\Models;

use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;

class License extends GeneratedFillableModel
{
    use DashboardFetchable, ModelMapper;
    //
    protected $table ='rcare_admin.rcare_licences';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
     protected $fillable = [
        'id',
        'org_id', 
        'service_id', 
        'license_key', 
        'license_model', 
        'subscription_in_months', 
        'start_date',
        'end_date', 
        'status'
    ];

    /* public function rcareorg()
    {
        return $this->belongsTo('App\Models\RcareOrgs', 'org_id');
    }
*/
    public function licensfetch()
    {
        return $this->belongsTo('RCare\RCareAdmin\AdminPackages\Organization\src\Models\RcareOrgs', 'org_id');
    }
 
    public function licenses()
    {
        return $this->belongsTo('RCare\RCareAdmin\AdminPackages\Organization\src\Models\RcareOrgs', 'org_id');
    }

    public function modules()
    {
        return $this->belongsTo('RCare\RCareAdmin\AdminPackages\Organization\src\Models\Rcare_Modules', 'service_id');
    }
    
    public function category()
    {
        return $this->belongsTo('RCare\RCareAdmin\AdminPackages\Organization\src\Models\OrgCategory', 'category');
    } 
    
    /**
     * Generate some fillable attributes for the model
     */
    public function generateFillables()
    {
        return $this->fillable;
    }
    
    public function populationRelations()
    {
        return [];
    }
}