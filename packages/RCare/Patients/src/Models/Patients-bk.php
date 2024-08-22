<?php

namespace RCare\Patients\Models;

use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Support\Facades\DB;
class Patients-bk extends Model
{
use DashboardFetchable, ModelMapper, DatesTimezoneConversion;

    protected $table ='patients.patient-bk';

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
        'to_date'
    ];
	protected $primaryKey = 'id';
	
	/*public $keyType = 'string';
	
	protected $casts = [
        'id' => 'string',
    ];
	*/
   

    protected $fillable = [
		   'id',
       'uid',
       'fname',
       'mname',
       'lname',
       'email',
       'org_id',
       'partner_id',
       'home_number', 
       'mob', 
       'dob', 
       'review', 
       'created_by',
       'updated_by',
       'contact_preference_calling',
       'contact_preference_sms', 
       'contact_preference_email', 
       'contact_preference_letter', 
       'age', 
       'no_email', 
       'preferred_contact',
       'profile_img',
       'country_code',
       'secondary_country_code',
       'from_date',
       'to_date',
       'status',
       'comments',
       'deactive_permanently',
       'deactivation_comments',
       'primary_cell_phone',
       'secondary_cell_phone',
       'consent_to_text',
       'fin_number',
	    'enrollment_from',
        'surgery_date',
        'total_ques_resp',
        'pending_ques_resp',
    ];
	
	//  public function populationRelations()
 //    {
 //        return [
	// 			"patientServices",	
	// 			"patientInsurance"			
	// 		];
	// }
	
	/**
     * Generate some fillable attributes for the model
     */

    // public static function latest($patientId)
    // {
    //     return self::where('id', $patientId)->orderBy('created_at', 'desc')->first();
    // }

    public function generateFillables()
    {
	   	return $this->fillable;
 	  }	 

    public function tasks() {
        return $this->belongsTo('RCare\Patients\src\Models\PatientTimeRecords', 'id')->withDefault(['id'=>'none']);
    }
    public function users(){
        return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','created_by');
    }
    
    public function patientDoTo()
    {
        return $this->hasMany('ToDoList');
    }
	
	  public function patientServices()
    {
        return $this->hasMany('RCare\Patients\Models\PatientServices', 'patient_id')->whereIn('status',['0','1']);
    }

    public function patient_services()
    {
        return $this->belongsTo('RCare\Patients\Models\PatientServices', 'patient_id');
    }

    public function patientTimeRecords()
    {
        return $this->hasMany('RCare\Patients\Models\PatientTimeRecords', 'patient_id');
    }
	
	// public function patientInsurance()
 //    {
 //        return $this->hasMany('RCare\Patients\Models\PatientInsurance', 'patient_id');
 //    }

    public static function getpatient($patientId)
    {
      return self::where('id',$patientId)->where('status',1)->first();
    }

	public static function patientDetails($patientId)
  {
    $patient_id=sanitizeVariable($patientId);
		return self::where('id', $patient_id)->orderBy('created_at', 'desc')->first();
  }
    
    public static function Allpatients()
    {
        $patients = Patients::all(); 
        return $patients;
    }

    public static function assignpatients($loginid)
    {
        $login_user =sanitizeVariable($loginid);
        // dd($login_user);
        $patients = \DB::table('patients.patient as p')
        ->join('task_management.user_patients as up','p.id','=','up.patient_id')
        ->join('ren_core.users as usr','usr.id','=','up.user_id')
        ->where('up.status',1)
        ->where('usr.id',$login_user)
        ->select('p.id', 'p.fname', 'p.lname','p.mname')
        ->distinct('p.id')->get();

        return $patients;
    }

    // public static function Allpatientswithnewdob()
    // {
    //     // $patients = Patients::all(); 
    //     $a= DB::table('patients.patient')       
    //     // ->distinct('start_date')
    //     ->get(); 
      
    //    foreach($a as $key=>$value)
    //    {
    //        $value->dob = date('m-d-Y',strtotime($value->dob)); 
    //    }  
        
    //    return $a; 
    // }

    public static function PatientsEnroledInRPM()
    {
      $patientsinrpm = DB::table('patients.patient as p')->join('patients.patient_services', 'patients.patient_services.patient_id', '=', 'p.id')->where('patients.patient_services.module_id',2)->where('p.status',1)->orderBy('p.fname', 'asc')->select('p.id','p.fname','p.lname','p.mname')->get();
   
        return $patientsinrpm; 
    }

    public static function RpmPatients()
    {
    //  $p = self::with('patient_services')->where('patient_services.module_id',2)->get();
    //  dd($p);
    $p = [];
        

        $patients = \DB::table('patients.patient as p')
        ->join('patients.patient_services as ps','p.id','=','ps.patient_id')
        ->join('patients.patient_providers as pp','p.id','=','pp.patient_id')
        ->where('ps.module_id',2)
        ->where('pp.practice_id',$practice)
        ->select('p.id', 'p.fname', 'p.lname','p.mname', 'p.dob', 'p.mob')
        ->distinct('p.id')->get();
      
        return $p; 
        
    }
 
    public static function worklistpatient()
    {
        // $patients1 = \DB::table('patients.patient as p')
        //              ->join('patients.patient_services as ps','ps.patient_id','=','p.id')
        //              ->where('ps.module_id',3)
        //              ->get();

        // return $patients1; 
        return self::with('patient_services')->where('patient_services.module_id',3)->get(); 
    }

     
	

}