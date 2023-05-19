<?php

namespace RCare\Org\OrgPackages\Diagnosis\src\Models;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use RCare\System\Traits\DatesTimezoneConversion;

class DiagnosisCode extends Model
{
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='ren_core.diagnosis_codes';

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
        'diagnosis_id',
        'code',
        'created_by',
        'updated_by',
        'status',
        'qualified',
        'valid_invalid',
        'verify_status',
        'verified_by',
        'verify_on'
    ];

    public static function activeDiagnosiscode()
    {
        //return DiagnosisCode::select(DiagnosisCode::raw('upper(code) as code'))->where("status", 1)->whereNotNull('code')->orderBy('code','asc')->get();
        return DiagnosisCode::where("status", 1)->whereNotNull('code')->orderBy('code','asc')->get();
    }
    public static function self($id)
    {   $id = sanitizeVariable($id);
        return self::where('diagnosis_id', $id)->orderBy('created_at', 'desc');
    }

    public static function latest($id)
    {   $id = sanitizeVariable($id); 
        return self::where('diagnosis_id', $id)->orderBy('created_at', 'desc')->first();
    }
    

        public function users()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }
    

    
}