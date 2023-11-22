<?php
namespace RCare\Org\OrgPackages\OrgThreshold\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\OrgThreshold\src\Http\Requests\orgAddRequest;
//use RCare\Org\OrgPackages\OrgThreshold\src\Http\Requests\practicesGrpAddRequest;
//use RCare\Org\OrgPackages\Practices\src\Http\Requests\practicesThresholdRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\OrgThreshold\src\Models\Practices;
use RCare\Org\OrgPackages\Practices\src\Models\PracticesGroup;
use RCare\Org\OrgPackages\Practices\src\Models\PracticeThreshold;
use RCare\Org\OrgPackages\Providers\src\Models\Providers;
use RCare\Org\OrgPackages\Providers\src\Models\ProviderType;
use RCare\Org\OrgPackages\Providers\src\Models\ProviderSubtype;
use RCare\Org\OrgPackages\Physicians\src\Models\Physicians;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;
use DataTables;
use Hash;
use Redirect,Response;
use Session;
use File;

use Illuminate\Validation\Rule;
use Validator;


class OrgPracticesController extends Controller {

    
    public function AddPracticeLogo(Request $request){
     if(isset($_FILES) && !empty($_FILES)) {          
        if(sanitizeVariable($request->hasFile('file'))){                  
            $image = sanitizeVariable($request->file('file'));                
            $original_name = preg_replace("/\s+/", "_", $image->getClientOriginalName());
            $file_extension=$image->getClientOriginalExtension();
            $new_name = $original_name;  
            $image = $image->move(public_path('PracticeLogo'), $new_name);
         // $img_path = '/orgLogoRcare/PracticeLogo'.$new_name;                       
            return $new_name;
        }
      }
   }

  // public function addpracticethreshold(practicesThresholdRequest $request) {
      public function addpracticethreshold(Request $request) {
    
            $practice_id = sanitizeVariable($request->practice_id);
            $bpmHigh = sanitizeVariable($request->bpmhigh);
            $bpmLow = sanitizeVariable($request->bpmlow);
            $diastolicHigh = sanitizeVariable($request->diastolichigh);
            $diastolicLow = sanitizeVariable($request->diastoliclow);
            $glucoseHigh = sanitizeVariable($request->glucosehigh);
            $glucoseLow = sanitizeVariable($request->glucoselow);
            $oxSatHigh = sanitizeVariable($request->oxsathigh);
            $oxSatLow = sanitizeVariable($request->oxsatlow);
            $systolicHigh = sanitizeVariable($request->systolichigh);
            $systolicLow = sanitizeVariable($request->systoliclow);
            $temperatureHigh= sanitizeVariable($request->temperaturehigh);
            $temperatureLow = sanitizeVariable($request->temperaturelow);
            $eff_date = Carbon::now();
            $created_by            = session()->get('userid');

            $threshold_data = array(
                'practice_id' => $practice_id,
                'bpmhigh' => $bpmHigh,
                'bpmlow' => $bpmLow,
                'diastolichigh' => $diastolicHigh,
                'diastoliclow' => $diastolicLow,
                'glucosehigh' => $glucoseHigh,
                'glucoselow' => $glucoseLow,
                'oxsathigh' => $oxSatHigh,
                'oxsatlow' => $oxSatLow,
                'systolichigh' => $systolicHigh,
                'systoliclow' => $systolicLow,
                'temperaturehigh' => $temperatureHigh,
                'temperaturelow' => $temperatureLow,
                'eff_date' => $eff_date
                
            );
            $PracticeThreshold = PracticeThreshold::where('practice_id',$practice_id)->exists();
        if($PracticeThreshold==true)
        {
            $threshold_data['updated_by']=$created_by; 
            $update_practice = PracticeThreshold::where('practice_id',$practice_id)->update($threshold_data);
            //return "edit";
        }else{  
            $threshold_data['created_by']=$created_by;
            $threshold_data['updated_by']=$created_by;
            $insert_practice = PracticeThreshold::create($threshold_data);
            //return "add";
        }
    
   }

    public function addpractice(practicesAddRequest $request) {
        $name                  = sanitizeVariable($request->name);
        $practice_group        = sanitizeVariable($request->practice_group);
        $number                = sanitizeVariable($request->number);
        $location              = sanitizeVariable($request->location);
        $address               = sanitizeVariable($request->address);
        $phone                 = sanitizeVariable($request->phone);
        $key_contact           = sanitizeVariable($request->key_contact);
        $outgoing_phone_number = sanitizeVariable($request->outgoing_phone_number);
        $id                    = sanitizeVariable($request->id);       
        $provider_name         = sanitizeVariable($request->providers);
        $provider_type_id      = sanitizeVariable($request->provider_type_id);
        $provider_subtype_id   = sanitizeVariable($request->provider_subtype_id);
        $billable              = sanitizeVariable($request->billable);
        $created_by            = session()->get('userid');
        $is_active             = 1;
        $logo                  =sanitizeVariable($request->image_path);
        if($billable == null){
            $billable = 1;
        }
      //  dd($billable);
        $practice_array = array(
                            'name'                  => $name,
                            'location'              => $location,
                            'number'                => $number,
                            'address'               => $address,
                            'phone'                 => $phone,
                            'key_contact'           => $key_contact,
                            'outgoing_phone_number' => $outgoing_phone_number,
                            'is_active'             => $is_active,
                            'logo'                  => $logo,
                            'practice_group'        => $practice_group,
                            'billable'              => $billable
                            
                        );

       

        $Practices = Practices::where('id',$id)->exists();
        if($Practices==true)
        {
            $practice_array['updated_by']=$created_by; 
            $update_practice = Practices::where('id',$id)->update($practice_array);
            return "edit";
        }else{  
            $practice_array['created_by']=$created_by;
            $practice_array['updated_by']=$created_by;
            $insert_practice = Practices::create($practice_array);
            return "add";
        }
    }

    public function addPracticeGroup(practicesGrpAddRequest $request)  
    {
        $practice_name = sanitizeVariable($request->practice_name);
        $assign_message = sanitizeVariable($request->assign_message);
        $id = sanitizeVariable($request->id);
        $created_by  = session()->get('userid');
        $data = array(
                    'practice_name' => $practice_name,
                    'assign_message' => $assign_message
                );
        $existpractice_name=PracticesGroup::where('id',$id)->exists();
        if($existpractice_name==true){
            $data['updated_by']= $created_by;
            $update_practice_name = PracticesGroup::where('id',$id)->orderBy('id', 'asc')->update($data);
        }else{
            $data['created_by']= $created_by;
            $data['updated_by']= $created_by;
            $insert_practice_name = PracticesGroup::create($data);
        }
    }


    
    ///showing list of roles
    public function practice($caremanager){
        $caremanager = sanitizeVariable($caremanager);
        $practice = [];
        if($caremanager==null)
        {
            $caremanager = 001;   
        }
        if($caremanager == 001){
            $practice =  DB::table('ren_core.practices')->get();
            foreach($practice as $p)
            {
                
                $id = $p->id ; 
                
                $pat = \DB::select(\DB::raw("select count(distinct p.id) from patients.patient p 
                left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                from patients.patient_providers pp1  
                inner join (select patient_id, max(id) as max_pat_practice 
                from patients.patient_providers  where provider_type_id = 1  and is_active =1  
                group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
                on p.id = pp.patient_id
                left join ren_core.practices rp on rp.id = pp.practice_id where rp.id = '".$id."' "));    
                
    
                $patientcount =$pat[0]->count;
                     
                $p->count  = $patientcount;
                // dd($p);
                                 
            }
            
        }else{
            $practice = DB::table('ren_core.practices')
        ->whereIn('id', DB::table('ren_core.user_practices')->where('user_id', $caremanager)->pluck('practice_id'))
        ->get();
        foreach($practice as $p)
        {
            
            $id = $p->id ; 
            
            $pat = \DB::select(\DB::raw("select count(distinct p.id) from patients.patient p 
            left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
            from patients.patient_providers pp1  
            inner join (select patient_id, max(id) as max_pat_practice 
            from patients.patient_providers  where provider_type_id = 1  and is_active =1  
            group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
            on p.id = pp.patient_id
            left join ren_core.practices rp on rp.id = pp.practice_id where rp.id = '".$id."' "));    
            

            $patientcount =$pat[0]->count;
                 
            $p->count  = $patientcount;
            // dd($p);
                             
        }
        }
        return response()->json($practice);
    }


    public function PracticeList(Request $request) {
        if ($request->ajax()) {
            $data = Practices::with('users')->with('practice_group')->latest()->get();
            return Datatables::of($data) 
            ->addIndexColumn()
            ->addColumn('threshold', function($row){ 
                $btns =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="practicethreshold" title="Edit">Edit</a>'; 
                return $btns;
            })
            ->addColumn('action', function($row){
            
                $btn =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editPractices" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                           if($row->is_active == 1){
                               $btn = $btn. '<a href="javascript:void(0)" data-id ="'.$row->id.'" class="change_practicestatus_active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                         }
                       else
                      {
                          $btn = $btn.'<a href="javascript:void(0)" data-id ="'.$row->id.'" class="change_practicestatus_deactive"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
                     }
                    return $btn;
            })
            ->rawColumns(['action','threshold'])
            ->make(true);
        }
        // return view('Practices::practice-list');

        return view('Practices::practice-main');
    }

    public function PracticeGroupList(Request $request) {
        if ($request->ajax()) {
            $data = PracticesGroup::with('users')->latest()->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editPracticesGroup" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                           if($row->status == 1){
                               $btn = $btn. '<a href="javascript:void(0)" data-id ="'.$row->id.'" class="change_practicegrpstatus_active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                         }
                       else
                      {
                          $btn = $btn.'<a href="javascript:void(0)" data-id ="'.$row->id.'" class="change_practicegrpstatus_deactive"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
                     }
                    return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        // return view('Practices::practice-list');

        return view('Practices::practice-main');
    }

    public function deleteRole(Request $request) {
        $id= sanitizeVariable($request->id);
        $update = ['status' => '0'];
        Practices::where('id',$id)->update($update);
        return response()->json(['success'=>'Practice deleted successfully.']);
    }

    public function deleteProviderName(Request $request) {
         $id= sanitizeVariable($request->id);
         // dd($id);
    }

    //Practice active or notactive
    public function changePracticeGrpStatus($id) {
        $id = sanitizeVariable($id);
        $row = PracticesGroup::find($id);
        $row->status=!$row->status;
        if($row->save()) {
            return redirect()->route('org_practice');
        } else {
            return redirect()->route('statuschange');
        }
    }
    public function changePracticeStatus($id) {
        $id = sanitizeVariable($id);
        $row = Practices::find($id);
        $row->is_active=!$row->is_active;
        if($row->save()) {
            return redirect()->route('org_practice');
        } else {
            return redirect()->route('statuschange');
        }
    }

    public function populategrppractice(Request $request){
        $id = sanitizeVariable($request->id);
        $org_practice = (PracticesGroup::self($id) ? PracticesGroup::self($id)->population() : "");
        $result['AddPracticeGrpForm'] = $org_practice;
        return $result;
    }

    public function populatethreshold(Request $request){
        $id = sanitizeVariable($request->id);
        $threshold_practice = (PracticeThreshold::self($id) ? PracticeThreshold::self($id)->population() : "");
        $result['practice_threshold_form'] = $threshold_practice;
        return $result;
    }

    public function populate(Request $request)
    {   $id = sanitizeVariable($request->id);
        $org_practice = (Practices::self($id) ? Practices::self($id)->population() : "");

        $org_provider = Providers::where('practice_id',$id)->get()->toArray();
  
    if($org_provider){ 
            $prdata=array('providerdata'=>$org_provider);
            $org_practice['static']=array_merge($org_practice['static'], $prdata);
        }
       
       $result['AddPracticeForm'] = $org_practice;
      
        return $result;
    }

    public function getsubProviders(Request $request){
        $provider_type_id = sanitizeVariable($request->provider_type_id);
        // dd($request->provider_type_id);
        if(!empty($provider_type_id)){
            $param = providerSubtype::where('provider_type_id',sanitizeVariable($request->provider_type_id))->get();
            // dd($param);
            echo "<option value=''>Select Provider Subtype</option>";
            foreach($param as $value){
                echo "<option value='".$value->id."'>".$value->sub_provider_type."</option>";
                
            }
        }else{
            echo "<option value=''>Select Provider Subtype</option>";
        }
        }

        public function practicegrpRelatedPractice($practicegrpid)
        {   
            $practicegrpid = sanitizeVariable($practicegrpid);
            $practice = [];
            if($practicegrpid=="null")
            {
                $practice = Practices::where("is_active", 1)->where("name","!=","null")->orderBy('name','asc')->get();
                foreach($practice as $p) 
                {
                    $id = $p->id;
                    $pro= \DB::select(\DB::raw("select  count(distinct p.id) from patients.patient p 
                    left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                    from patients.patient_providers pp1  
                    inner join (select patient_id, max(id) as max_pat_practice 
                    from patients.patient_providers  where provider_type_id = 1  and is_active =1  
                    group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
                    on p.id = pp.patient_id
                    left join ren_core.practices rp on rp.id = pp.practice_id where rp.id = '".$id."' ")); 
        
                    $practicecount = $pro[0]->count; 
                     
                    $p->count = $practicecount;
                }    
            }
            else{
                $practice = Practices::where("is_active", 1)->where("practice_group", $practicegrpid)->where("name","!=","null")->orderBy('name','asc')->get();
                foreach($practice as $p) 
                {
                    $id = $p->id;
                    $pro= \DB::select(\DB::raw("select  count(distinct p.id) from patients.patient p 
                    left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                    from patients.patient_providers pp1  
                    inner join (select patient_id, max(id) as max_pat_practice 
                    from patients.patient_providers  where provider_type_id = 1  and is_active =1  
                    group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
                    on p.id = pp.patient_id
                    left join ren_core.practices rp on rp.id = pp.practice_id where rp.id = '".$id."' ")); 
        
                    $practicecount = $pro[0]->count; 
                     
                    $p->count = $practicecount;
                }  
            }
            return response()->json($practice); 
        }

             

} 