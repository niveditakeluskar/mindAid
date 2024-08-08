<?php

namespace RCare\Org\OrgPackages\Practices\src\Models;
use RCare\System\Support\DashboardFetchable;
use RCare\System\Support\ModelMapper;
use RCare\System\Support\GeneratedFillableModel;
use Carbon\Carbon;
use RCare\System\Traits\DatesTimezoneConversion; 
use RCare\Org\OrgPackages\Users\src\Models\Users;


use Illuminate\Database\Eloquent\Model;

class Practices extends Model
{   
    use DashboardFetchable, ModelMapper, DatesTimezoneConversion;
    //
     protected $table ='ren_core.practices';


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
        'name', 
        'number', 
        'location', 
        'address',
        'phone',
        'key_contact',
        'outgoing_phone_number',
        'is_active',
        'logo',
        'practice_group',
        'created_by',
        'updated_by',
        'billable', 
        'partner_id',
        'practice_type'
    ];
    
public static function activePractices()
{
    // $prac= Practices::where("is_active", 1)->orderBy('name','asc')->get();
    $prac = \DB::table('ren_core.practices')->where("is_active", 1)->orderBy('name','asc')->get();
    // dd($prac);
	
    return $prac;
}


// Inactive Practices
public static function InactivePractices()
{
    // $prac= Practices::where("is_active", 1)->orderBy('name','asc')->get();
    $prac = \DB::table('ren_core.practices')->where("is_active", 0)->orderBy('name','asc')->get();
    // dd($prac);
	
    return $prac;
}

//select pcp practices
public static function activePcpPractices()
{
    // $prac= Practices::where("is_active", 1)->orderBy('name','asc')->get();
    $prac = \DB::table('ren_core.practices')->where("is_active", 1)->where("practice_type", 'pcp')->orderBy('name','asc')->get();
    // dd($prac);
	
    return $prac;
}

public static function InactivePcpPractices() 
{
    // $prac= Practices::where("is_active", 1)->orderBy('name','asc')->get();
    $prac = \DB::table('ren_core.practices')->where("is_active", 1)->where("practice_type", 'pcp')->orderBy('name','asc')->get();
    // dd($prac);
	
    return $prac;
}
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

    public function practice_group()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Practices\src\Models\PracticesGroup','practice_group');
    }

    public function partners()
    {
         return $this->belongsTo('RCare\Org\OrgPackages\Partner\src\Models\Partner','partner_id');
    }

    // public function physicians()
    // {
    //     return $this->hasMany("RCare\Rpm\Models\PracticePhysician");
    // }

    public function providers()
    {
        return $this->hasMany("RCare\Org\OrgPackages\Providers\src\Models\Providers");
    }

    public  static function  worklistPractices()
    {
        $userid = session()->get('userid');
        $usersdetails = Users::where('id',$userid)->get(); 
        $roleid = $usersdetails[0]->role;
        if($roleid == 2)
        {
            $practdrop =\DB::table('ren_core.practices as rp')
                        ->select('rp.name','rp.id','rp.location')
						->where("is_active", 1)
                        ->where("rp.practice_type",'pcp')
                        ->get();
        } else {
            
            $practdrop =\DB::table('ren_core.practices as rp')
                        ->select('rp.name','rp.id','rp.location')
                        ->join('ren_core.user_practices as up','up.practice_id','=','rp.id')
                        ->where('up.user_id',$userid)
						->where("rp.is_active", 1)
                        ->where("rp.practice_type",'pcp')
                        ->get();
                         
            
      
        }
        $index = count($practdrop);
        $d = "All Practices";
        $obj = new \stdClass;
        $obj->name = 'All Practices';
        $obj->id = 0;
        $obj->location = "";
        
        $practdrop->put($index,$obj);
        // dd($practdrop);
        return $practdrop;
                        // dd($practdrop);
       
    }

     public static function groupedPractices() {
        $query = \DB::select(("select p.id, p.name || ' (' || p.location || ')' as name,
                case
                    when p.is_active = 1 and p.practice_type='pcp' then 'Active'
                    when p.is_active = 0 and p.practice_type='pcp' then 'Inactive'
                    else ''
                end AS group_by
                from ren_core.practices p where p.name not ilike '%test%' and p.practice_type='pcp' and p.is_active = 1 order by group_by, p.name"));
             // from ren_core.practices p order by group_by, p.name"));

        return $query;        
    }

    public static function RpmPractice() 
    {
        $prac1 = [];
        

        $prac1 = \DB::table('patients.patient as p')
        ->join('patients.patient_services as ps','p.id','=','ps.patient_id')
        ->join('patients.patient_providers as pp','p.id','=','pp.patient_id')
        ->join('ren_core.practices as rp','rp.id','=','pp.practice_id')
        ->where('rp.is_active', 1)
        ->where('rp.practice_type', 'pcp') // aded for only pcp practice
      //  ->where('ps.module_id',2)
        // ->select('rp.name','rp.location')
        ->select('rp.*')
        ->orderBy('rp.name','asc')->get();
      
        return $prac1;   
    }

    public static function RpmPracticeRolesbased() 
    {
        
        $prac1 = [];
        
        $userid = session()->get('userid');
        $usersdetails = Users::where('id',$userid)->get(); 
        $roleid = $usersdetails[0]->role;
        if($roleid == 2)
        {
            $prac1 = \DB::table('patients.patient as p')
                        ->join('patients.patient_services as ps','p.id','=','ps.patient_id')
                        ->join('patients.patient_providers as pp','p.id','=','pp.patient_id')
                        ->join('ren_core.practices as rp','rp.id','=','pp.practice_id')
                        ->where('rp.is_active', 1)
                        ->where('rp.practice_type','pcp') // for pcp practices
                        // ->where('ps.module_id',2)
                        // ->select('rp.name','rp.location')
                        ->select('rp.*')
                        ->orderBy('rp.name','asc')
                        ->get();

        } else {
            
            $prac1 = \DB::table('patients.patient as p')
                        ->join('patients.patient_services as ps','p.id','=','ps.patient_id')
                        ->join('patients.patient_providers as pp','p.id','=','pp.patient_id')
                        ->join('ren_core.practices as rp','rp.id','=','pp.practice_id')
                        ->join('ren_core.user_practices as up','up.practice_id','=','rp.id')
                        ->where('up.user_id',$userid)
                        ->where('rp.is_active', 1)
                        ->where('rp.practice_type','pcp') // for pcp practices
                        // ->where('ps.module_id',2)
                        // ->select('rp.name','rp.location')
                        ->select('rp.*')
                        ->orderBy('rp.name','asc')
                        ->get();
      
        }

        return $prac1;
    }

    
    public static function RpmPracticeGroup() 
    {
        $pracgroup = [];
        

        $pracgroup = \DB::table('patients.patient as p')
        ->join('patients.patient_services as ps','p.id','=','ps.patient_id')
        ->join('patients.patient_providers as pp','p.id','=','pp.patient_id')
        ->join('ren_core.practices as rp','rp.id','=','pp.practice_id')
        ->join('ren_core.practicegroup as rpg','rpg.id','=','rp.practice_group')
        ->where('rp.is_active', 1)
        ->where('rpg.status', 1)
        ->where('ps.module_id',2)
        ->select('rpg.*') 
        ->orderBy('rpg.practice_name','asc')->get();
      
        return $pracgroup;  
    }

    public static function RpmPracticeGroupRolesbased() 
    {

        $pracgroup = [];
        
        $userid = session()->get('userid');
        $usersdetails = Users::where('id',$userid)->get(); 
        $roleid = $usersdetails[0]->role;
        // dd($roleid);
        if($roleid == 2)
        {
            $pracgroup = \DB::table('patients.patient as p')
                            ->join('patients.patient_services as ps','p.id','=','ps.patient_id')
                            ->join('patients.patient_providers as pp','p.id','=','pp.patient_id')
                            ->join('ren_core.practices as rp','rp.id','=','pp.practice_id')
                            ->join('ren_core.practicegroup as rpg','rpg.id','=','rp.practice_group')
                            ->where('rp.is_active', 1)
                            ->where('rpg.status', 1)
                            ->where('ps.module_id',2)
                            ->select('rpg.*') 
                            ->orderBy('rpg.practice_name','asc')
                            ->get();


        } else {
            
            $pracgroup = \DB::table('patients.patient as p')
                        ->join('patients.patient_services as ps','p.id','=','ps.patient_id')
                        ->join('patients.patient_providers as pp','p.id','=','pp.patient_id')
                        ->join('ren_core.practices as rp','rp.id','=','pp.practice_id')
                        ->join('ren_core.user_practices as up','up.practice_id','=','rp.id')
                        ->join('ren_core.practicegroup as rpg','rpg.id','=','rp.practice_group')
                        // ->join('ren_core.practicegroup as rpg','rpg.id','=','up.practice_group')
                        ->where('rp.is_active', 1)
                        ->where('rpg.status', 1)
                        ->where('ps.module_id',2)
                        ->where('up.user_id',$userid)
                        ->select('rpg.*') 
                        ->orderBy('rpg.practice_name','asc')
                        // ->select('rp.*')
                        ->distinct()
                        ->get();            
      
        }
        // dd($pracgroup);
        return $pracgroup; 
    }


}
  