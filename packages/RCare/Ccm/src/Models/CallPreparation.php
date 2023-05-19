<?php

namespace RCare\Ccm\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;

class CallPreparation extends Model
{
     use DashboardFetchable, ModelMapper,DatesTimezoneConversion;

    //
    protected $table ='ccm.ccm_preparation';
    public $timestamps = TRUE;

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
        'updated_at',
        'prep_date'
    ];
    protected $fillable = [
        'id', 
        'uid',
        'prep_date',
        'condition_requirnment1', 
        'condition_requirnment_notes',
        'newofficevisit',
        'nov_notes', 
        'newdiagnosis',
        'nd_notes',
        'report_requirnment1',  
        'report_requirnment_notes', 
        // 'newdme', 
        // 'dme_notes',
        // 'changetodme',
        // 'ctd_notes',
        'med_added_or_discon',
        'med_added_or_discon_notes',  
        'anything_else',
        'created_by',
        'updated_by',
        'patient_id',
        'condition_requirnment2', 
        'condition_requirnment3', 
        'condition_requirnment4', 
        'report_requirnment2', 
        'report_requirnment3',
        'report_requirnment4', 
        'report_requirnment5',
        'patient_relationship_building',
        'pcp_reviwewd',
        'submited_to_emr',
    ];
	
	 public static function latest($patientId)
    {
		// if($month=='previous'){
		// 	$year = Carbon::now()->year;
		// 	$month = Carbon::now()->subMonth;
		// }elseif($month=='current'){
		// 	$year = Carbon::now()->year;
		// 	$month = Carbon::now()->month;			
		// }
  //       return self::where('patient_id', $patientId)->whereYear('created_at', $year)->whereMonth('created_at', $month)->orderBy('created_at', 'desc')->first();
        $currentMonth = date('m');
        $year = Carbon::now()->year;
        return self::where('patient_id', $patientId)->whereYear('created_at', $year)->whereMonth('created_at', $currentMonth)->orderBy('created_at', 'desc')->first();
    }
}
