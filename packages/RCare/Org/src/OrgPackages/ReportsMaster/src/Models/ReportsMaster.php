<?php

namespace RCare\Org\OrgPackages\ReportsMaster\src\Models; 
use Illuminate\Database\Eloquent\Model;   
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\Patients\Models\PatientMedication;
use RCare\System\Traits\DatesTimezoneConversion;

class ReportsMaster extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table ='ren_core.reports_master';

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
        'report_name',
        'management_status',
        'status',
        'created_by',
        'updated_by',
        'report_file_path',
        'display_name'
        ];

  
	
	public static function self($id)
    {   $id  = sanitizeVariable($id);
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
    }

    // public static function activeReports()
    // {   
    //     return self::where('status', 1)->orderBy('created_at', 'desc')->first();
    // }
    public static function activeReports()
    {
        return self::where("status", 1)->orderBy('created_at','desc')->get();
    }

    public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }
}