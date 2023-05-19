<?php

namespace RCare\Rpm\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
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
        return $this->belongsTo('App\Models\RcareOrgs', 'org_id');
    }
 
    public function licenses()

    {
        return $this->belongsTo('App\Models\RcareOrgs', 'org_id');
    }

     public function modules()

    {
        return $this->belongsTo('App\Models\Rcare_Modules', 'service_id');
    }
    
     public function category()
    {
        
        return $this->belongsTo('App\Models\OrgCategory', 'category');


    }  

    

}
