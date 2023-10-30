<?php

namespace RCare\Org\OrgPackages\Physicians\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Physicians\src\Http\Requests\physiciansAddRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\Physicians\src\Models\Physicians;
// use RCare\Rpm\Models\Providers;
use RCare\Org\OrgPackages\Providers\src\Models\Providers;

use DataTables;
use Hash;
use Redirect,Response;
use Session;
use File;

use Illuminate\Validation\Rule;
use Validator;


class PhysiciansController extends Controller {

    public function index() {
        return view('Physicians::physician-list');
        
    }

    public function createphysician(physiciansAddRequest $request) {
     // echo "string"; die;
        $data = array(
            'name' => sanitizeVariable($request->physician_name),
            'phone' =>sanitizeVariable($request->contact),
            'email' =>sanitizeVariable($request->email),
            'physicians_uid'=>sanitizeVariable($request->physicians_uid),
            'is_active' => '1',
        ); 
        // dd($data); 
        $user = Physicians::create($data);
        return back()->with('success','Physician created successfully!');
    }

    ///showing list of roles
    public function physicianList(Request $request) {
        if ($request->ajax()) {
            $data = Physicians::latest()->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editPhysicians" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                           if($row->is_active == 1){
                               $btn = $btn. '<a href="changePhysicianstatus/'.$row->id.'"><i class="i-Yess i-Yes" title="Active"></i></a>';
                         }
                       else
                      {
                          $btn = $btn.'<a href="changePhysicianstatus/'.$row->id.'"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
                     }
                    return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Physicians::physician-list');
    }

    public function editPhysicians($id) {
        $id  = sanitizeVariable($id);
        $user = Physicians::find($id);
        return response()->json($user);
    }

    // edit users
    public function updatephysician(Request $request) {
        $id= sanitizeVariable($request->id);
        $update = [
        'name' =>   sanitizeVariable($request->physician_name),
        'phone'=>   sanitizeVariable($request->contact),
        'email'=>   sanitizeVariable($request->email),
        'physicians_uid' =>sanitizeVariable($request->physicians_uid),

        ];
        Physicians::where('id',$id)->update($update);
        return back()->with('success','Practice updated successfully!');
    }

    public function deleteRole(Request $request) {
        $id= sanitizeVariable($request->id);
        $update = ['status' => '0'];
        Physicians::where('id',$id)->update($update);
        return response()->json(['success'=>'Practice deleted successfully.']);
    } 

    //User active or notactive
    public function changePhysicianstatus($id) {
        $id = sanitizeVariable($id);
        $row = Physicians::find($id);
        $row->is_active=!$row->is_active;
        if($row->save()) { 
            return redirect()->route('org_physician');
        } else {
            return redirect()->route('statuschange');
        }
    }

    public function physician($practice)
	{
      $practice  = sanitizeVariable($practice);
      // dd($practice);  
        $physicians = [];
        
        if($practice=="null")
        { 
            // dd("null");
            $physicians = \DB::table('ren_core.providers')
            ->where("is_active", 1)
            ->where("name","!=","null")->orderBy('name','asc')->get();

            foreach($physicians as $p) 
            {
                $id = $p->id;
                $pro= \DB::select(("select  count(distinct p.id) from patients.patient p 
                left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                from patients.patient_providers pp1  
                inner join (select patient_id, max(id) as max_pat_practice 
                from patients.patient_providers  where provider_type_id = 1  and is_active =1  
                group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
                on p.id = pp.patient_id
                left join ren_core.providers rp on rp.id = pp.provider_id where rp.id = '".$id."' ")); 
    
                $providerscount = $pro[0]->count; 
                 
                $p->count = $providerscount;
            }   
        }
        else if($practice == 001){
        //    dd("elseif");
            $physicians = \DB::table('ren_core.providers')->where("is_active", 1)->where("name","!=","null")->orderBy('name','asc')->get();
          
            foreach($physicians as $p) 
            {
                $id = $p->id;
                $pro= \DB::select(("select  count(distinct p.id) from patients.patient p 
                left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                from patients.patient_providers pp1  
                inner join (select patient_id, max(id) as max_pat_practice 
                from patients.patient_providers  where provider_type_id = 1  and is_active =1  
                group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
                on p.id = pp.patient_id
                left join ren_core.providers rp on rp.id = pp.provider_id where rp.id = '".$id."' ")); 
     
                $providerscount = $pro[0]->count; 
                
                $p->count = $providerscount;
            }

        }
       
        else{
            // dd("else");
            $physicians = \DB::table('ren_core.providers')->where("is_active", 1)->where("practice_id", $practice)->where("name","!=","null")->orderBy('name','asc')->get();
            //dd($physicians);
            foreach($physicians as $p)
            {
                $id = $p->id;
                $pro= \DB::select(("select  count(distinct p.id) from patients.patient p 
                left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                from patients.patient_providers pp1  
                inner join (select patient_id, max(id) as max_pat_practice 
                from patients.patient_providers  where provider_type_id = 1  and is_active =1  
                group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
                on p.id = pp.patient_id
                left join ren_core.providers rp on rp.id = pp.provider_id where rp.id = '".$id."' ")); 
    
                $providerscount = $pro[0]->count; 
                 
                $p->count = $providerscount;
            }
          
        }
       
        return response()->json($physicians);
    }

    //created and modified by ashvini 2nd june 2021
    public function ProviderPhysician($practice)
    {
        //DIS IS FOR RPM PATIENTS PLEASE NOTE
        $practice  = sanitizeVariable($practice);
   
        $physicians = [];
      
        if($practice=="null")
        { 
            // dd("null");
            $physicians = \DB::table('ren_core.providers')
            ->where("is_active", 1)
            ->where("name","!=","null")->orderBy('name','asc')->get();

            foreach($physicians as $p) 
            {
                $id = $p->id;
                $pro= \DB::select(("select  count(distinct p.id) from patients.patient p 
                left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                from patients.patient_providers pp1  
                inner join (select patient_id, max(id) as max_pat_practice 
                from patients.patient_providers  where provider_type_id = 1  and is_active =1  
                group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
                on p.id = pp.patient_id
                left join ren_core.providers rp on rp.id = pp.provider_id where rp.id = '".$id."' "));  
    
                $providerscount = $pro[0]->count; 
                 
                $p->count = $providerscount;
            }   
        }
       
        else{
            // dd("else");
            $physicians = \DB::table('ren_core.providers')->where("is_active", 1)->where("practice_id", $practice)->where("name","!=","null")->orderBy('name','asc')->get();
            //dd($physicians);
            foreach($physicians as $p)
            {
                $id = $p->id;
                $pro= \DB::select(("select  count(distinct p.id) from patients.patient p 
                left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                from patients.patient_providers pp1  
                inner join (select patient_id, max(id) as max_pat_practice 
                from patients.patient_providers  where provider_type_id = 1  and is_active =1  
                group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
                on p.id = pp.patient_id
                left join ren_core.providers rp on rp.id = pp.provider_id where rp.id = '".$id."' "));  
    
                $providerscount = $pro[0]->count; 
                 
                $p->count = $providerscount;
            }
          
        }
       
        return response()->json($physicians);
    }


    public function ProviderPopulatePhysician(Request $request){
        $practice=sanitizeVariable($request->route('practice'));
        if($practice!=0){
            $physicians = []; 
            $physicians = Providers::where("practice_id", $practice)
            ->where("is_active", 1)->where("name","!=","null")->orderBy('name','asc')->get();//->toSql();
            return response()->json($physicians); 
        }else{
            $physicians = []; 
            return response()->json($physicians);
        }  
    }
    public function PcpPhysician(Request $request) {
        $practiceId=sanitizeVariable($request->practiceId);
        $providerList = []; 
		\DB::enableQueryLog(); 
        $providerList = Providers::where("is_active", 1)->where('provider_type_id',1)
                                        ->where("name","!=","null")->where("practice_id",$practiceId)->get();
										//dd(\DB::getQueryLog());
        return response()->json($providerList);
    }

} 