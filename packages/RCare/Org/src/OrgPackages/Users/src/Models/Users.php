<?php

namespace RCare\Org\OrgPackages\Users\src\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
// use RCare\System\Traits\DatesTimezoneConversion;

use Carbon\Carbon;
use RCare\System\Traits\DatesTimezoneConversion;
use Illuminate\Database\Eloquent\Model;
use Session, DB;

// class Users extends GeneratedFillableModel
class Users extends Authenticatable
{
    use DashboardFetchable, ModelMapper,  DatesTimezoneConversion;
	protected $table ='ren_core.users';
	 
	// const created_at = null;
	
	
	protected $dates = [
        'created_at',
        'updated_at',
        'otp_date'
    ];
		
	
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $population_include = [
        "id"
    ];

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'id',
        'f_name',
        'l_name',
        'email',
        'status',
        // 'remember_me',
        'password',
        'role',
        'org_id',
        'profile_img',
        // 'partner_id',
        'report_to',
        'practice__id',
        'category_id',
        'emp_id',
        'office_id',
        'extension',
        'number',
        'country_code',
        'otp_code',// add by anand
        'max_attempts', // add by anand
        'temp_lock_time', // add by anand
        'block_unblock_status', // add by anand
        'token', // add for 2fa forget pwd
        'mfa_status', //add for 2fa
        'created_by',
        'updated_by',
		'theme'
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
    * Boot the model.
    *
    * @return void
    */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->remember_token = str_random(30);
        });
    }

    /**
    * Fetch all active Users
    */
    public static function activeUsers()
    {
        // return Users::all()->where("status", 1);
        return Users::where("status", 1)->orderBy('f_name','asc')->get();
    }

    public static function latest($id)
    {   $id = sanitizeVariable($id); 
        return self::where('id', $id)->orderBy('created_at', 'desc')->first();
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

    public function getReportToName(){
        return $this->users()->f_name;
    }

    public function roleName() {
        return $this->belongsTo('RCare\Org\OrgPackages\Roles\src\Models\Roles', 'role');
    }

    public function rcareOrgs() {
        return $this->belongsTo('RCare\RCareAdmin\AdminPackages\Organization\src\Models\RcareOrgs', 'org_id');
    }

    /**
     * Get the practices that are assigned for the employee
     *
     * @return array
     */
    public function UserPractices()
    {
        return $this->belongsToMany("RCare\Org\OrgPackages\Users\src\Models\UserPractices");
    }

    public function practices() {
        return $this->belongsTo('RCare\Org\OrgPackages\Practices\src\Models\Practices', 'practice_id');
    }

    public function reportto()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users', 'report_to');

    }
    
    public function users()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users','updated_by');
    }

    public function office()
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Offices\src\Models\Office','office_id');
    }

    public function users_responsibility() 
    {   
        return $this->hasMany('RCare\Org\OrgPackages\Users\src\Models\UsersResponsibility','user_id');
    }
    
    public function responsibility()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Responsibility\src\Models\Responsibility','responsibility_id')->where('status',1);
    }
    /*public function reportto1() 
    {
        return $this->belongsTo('RCare\Org\OrgPackages\Users\src\Models\Users', 'report_to');

    }*/

    

    /**
     * Determine if this user is associated with a practice
     *
     * @return bool
     */
    public function hasPractice($id)
    {
        foreach ($this->UserPractices as $practice) {
            if ($practice->id == $id) {
                return True;
            }
        }
        return False;
    }
    
    /**
     * The role this user belongs to
     *
     * @return RCare\Org\OrgPackages\Roles\src\Models\Roles
     */
    public function role()
    {
        return $this->belongsTo("RCare\Org\OrgPackages\Roles\src\Models\Roles");
    }

    /**
     * Get the data values in a JSON Object
     *
     * @return array
     */
    public function json()
    {
        return [
            "id"           => $this->id,
            "f_name"       => $this->f_name,
            "l_name"       => $this->l_name,
            "email"        => $this->email,
            "status"       => $this->status,
            "role"         => $this->role,
            "org_id"       => $this->org_id,
            "profile_img"  => $this->profile_img,
            "partner_id"   => $this->partner_id,
            "report_to"    => $this->report_to,
            "practice__id" => $this->practice__id,
            "category_id"  => $this->category_id,
            "emp_id"       => $this->emp_id
           
        ];
    }

    //public function reportinguser(){
       // return $this->belongsTo('RCare\Org\OrgPackages\Roles\src\Models\Roles', 'module_id')->withDefault(['module'=>'none']);
   // }
   

   public static function activeCareManager()
   {   
        // select u.id, u.f_name, u.l_name, r.role_name 
        // from ren_core.users u left join ren_core.roles r on r.id = u.role where u.status = 1 
        // and (r.id = 5 or  r.id =4 or  r.id = 11 )order by f_name
      $cm = DB::select(DB::raw("select u.id, u.f_name, u.l_name, r.role_name 
                                from ren_core.users u 
                                left join ren_core.roles r on r.id = u.role
                                where u.status = 1 and (UPPER(r.role_name) LIKE UPPER('%Care Manager%') 
                                or UPPER(r.role_name) LIKE UPPER('%Team Lead%')
                                or UPPER(r.role_name) LIKE UPPER('%Sr. Care Manager%'))"));
        
        foreach($cm as $c)
        {          
            $count =  DB::table('task_management.user_patients')
                    ->where('user_id',$c->id)
                    ->where("status", 1)
                    ->distinct('patient_id')->count('patient_id'); 
            $c->count= $count;   
        }   
       return $cm;
    }

    
   public static function activeUsersexceptadmin()
   {   
        // select u.id, u.f_name, u.l_name, r.role_name 
        // from ren_core.users u left join ren_core.roles r on r.id = u.role where u.status = 1 
        // and (r.id = 5 or  r.id =4 or  r.id = 11 )order by f_name

      $cm = DB::select(DB::raw("select u.id, u.f_name, u.l_name, r.role_name 
                                from ren_core.users u 
                                left join ren_core.roles r on r.id = u.role
                                where u.status = 1 and r.id != 2"));
                           
        
        foreach($cm as $c)
        {          
            $count =  DB::table('task_management.user_patients')
                    ->where('user_id',$c->id)
                    ->where("status", 1)
                    ->distinct('patient_id')->count('patient_id'); 
            $c->count= $count;   
        }   
       return $cm;
    }



        
    public static function RpmActiveCareManager()
   {
      $cm = DB::select(DB::raw("select u.id, u.f_name, u.l_name, r.role_name 
                                from ren_core.users u 
                                left join ren_core.roles r on r.id = u.role
                                left join patients.patient_services ps on ps.created_by = u.id 
                                where u.status = 1 and r.id = 5 and ps.module_id =2 order by f_name"));
        
        foreach($cm as $c) 
        {          
            $count =  DB::table('task_management.user_patients')
                    ->where('user_id',$c->id)
                    ->where("status", 1)
                    ->distinct('patient_id')->count('patient_id'); 
            $c->count= $count;   
        }   
       return $cm;
    }
    public static function caremanagerPatients($caremanagerid)
    {
       $p = DB::table('task_management.user_patients')  
            ->where('up.user_id',$caremanagerid)
            ->count();
            return $p;
    }

    public function PatientDiagnosis()
    {
        return $this->hasMany('RCare\Patients\Models\PatientDiagnosis');
    }

    public function UserPatients()
    {
        return $this->hasMany('RCare\TaskManagement\Models\UserPatients');
    }
}