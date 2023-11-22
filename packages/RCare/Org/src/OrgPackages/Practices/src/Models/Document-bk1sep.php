<?php

namespace RCare\Org\OrgPackages\Practices\src\Models;
use RCare\System\Support\DashboardFetchable;
 use RCare\System\Support\ModelMapper;
 use RCare\System\Support\GeneratedFillableModel;
use Carbon\Carbon;
use RCare\System\Traits\DatesTimezoneConversion;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use RCare\Org\OrgPackages\Practices\src\Models\Practices;
use RCare\Org\OrgPackages\Providers\src\Models\Providers;



use Illuminate\Database\Eloquent\Model;

class Document extends Model
{   
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    //
     protected $table ='dms.documents';


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
        'doc_type',  
        'practice_id', 
        'provider_id',
        'doc_name',
        'doc_size',
        'doc_content',
        'doc_comments',
        'status' 
    ];

 
    /**
     * Get the users that are assigned to this practice
     *
     * @return array
     */
    public static function self($id)
    {   
        $id = sanitizeVariable($id);
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }
    public function users()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }

    public static function activeDocument()
    {
        $docs= Document::where("status", 1)->orderBy('id','desc')->get();
        return $docs;
    }
    public function practices()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Practices\src\Models\Practices','practice_id');
    }

    public function providers()
    {
        return $this->belongsTo("RCare\Org\OrgPackages\Providers\src\Models\Providers",'provider_id');
    }

}
  