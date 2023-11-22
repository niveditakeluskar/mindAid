<?php

namespace RCare\Ccm\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Session;

class SpMonthlyMonitoringPatientListing extends Model
{
    //
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    protected $table =null;
    public $timestamps = TRUE;
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	public static function find($module_id, $patient_id, $columns = array('*')) {
		// make all fillable
        Model::unguard();
		//$searchpatientlisting = [];
		$configTZ = config('app.timezone');
        $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
			
		// //$attrs = \DB::select('CALL getLocation(?);',[$id]);
		
		// $attrs = DB::select("select id, fname, lname, mname, profile_img, mob, home_number, dob, created_by_user, created_by, 
        //     last_modified_at, 
        //     last_contact_date
        //      from ccm.patient_listing_search(?,?,?,?)", [$module_id, $patient_id, $configTZ, $userTZ]);
        //     //  dd($attrs[0]);
		// return $attrs[0]);
        // //  return new SpMonthlyMonitoringPatientListing((array)$attrs[0]);
        
        $sp =  DB::select("select id, fname, lname, mname, profile_img, mob, home_number, dob, created_by_user, created_by, 
        last_modified_at, 
        last_contact_date
         from ccm.patient_listing_search(?,?,?,?)", [$module_id, $patient_id, $configTZ, $userTZ]);
        return $sp;
	}
}