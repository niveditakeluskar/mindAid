<?php

namespace RCare\Org\OrgPackages\Medication\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\Patients\Models\PatientMedication;
use RCare\System\Traits\DatesTimezoneConversion;

class SubCategory extends Model
{
use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='ren_core.sub_category';

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
    public $timestamps = true;
    protected $fillable = [
		'id',
        'category',
        'subcategory',
        'created_by',
        'updated_by',
        'status'
    ];
    public static function activeSubCategory()
    {
        return SubCategory::where("status", 1)->orderBy('sub_category','asc')->distinct('sub_category')->get();
    }
	
	public function patientMedication()
    {
        return $this->hasMany('RCare\Patients\Models\PatientMedication', 'med_id');
    }
	
	public static function self($id)
    {   $id  = sanitizeVariable($id);
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }
    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }
    
    public function category()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Medication\src\Models\Category','category');
    }
	
	



}