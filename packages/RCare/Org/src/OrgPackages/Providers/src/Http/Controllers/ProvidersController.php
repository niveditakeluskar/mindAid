<?php

namespace RCare\Org\OrgPackages\Providers\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Providers\src\Http\Requests\providersAddRequest;
use RCare\Org\OrgPackages\Providers\src\Http\Requests\providersTypeAddRequest;
use RCare\Org\OrgPackages\Providers\src\Http\Requests\providersSubtypeAddRequest;
use RCare\Org\OrgPackages\Providers\src\Http\Requests\SpecialityAddRequest; 
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\Providers\src\Models\Providers;
use RCare\Org\OrgPackages\Providers\src\Models\ProviderType;
use RCare\Org\OrgPackages\Providers\src\Models\Speciality;
use RCare\Org\OrgPackages\Providers\src\Models\ProviderSubtype;
use Illuminate\Support\Facades\DB;
// use RCare\Rpm\Models\Providers;

use DataTables;
use Hash;
use Redirect,Response;
use Session;
use File;

use Illuminate\Validation\Rule;
use Validator;


class ProvidersController extends Controller {

    
    public function getsubProviders(Request $request){
        $provider_type_id = sanitizeVariable($request->provider_type_id);
        $param = providerSubtype::where('provider_type_id',sanitizeVariable($request->provider_type_id))->get();
        return $param;
    }
   
    public function createprovider(providersAddRequest $request) {
        $data = array(
            'name' =>   sanitizeVariable($request->name),
            'phone'=>   sanitizeVariable($request->phone), 
            'email'=>   sanitizeVariable($request->email),
            'address' =>sanitizeVariable($request->address),
            'provider_type_id' =>sanitizeVariable($request->provider_type_id),
            'provider_subtype_id' =>sanitizeVariable($request->provider_subtype_id),
            'speciality_id' =>sanitizeVariable($request->speciality_id), 
            'practice_id' =>sanitizeVariable($request->practice_id),
            'created_by' =>session()->get('userid'),
            'updated_by' =>session()->get('userid'),
            'is_active' => 1,
        );
        $user = Providers::create($data);
    }

     public function createprovidertype(providersTypeAddRequest $request) {
        $data = [
        'provider_type'=> sanitizeVariable($request->provider_type),
        'created_by' =>session()->get('userid'),
        'updated_by' =>session()->get('userid'),
        'is_active' => 1,
        ];
       $user = ProviderType::create($data);
    }

    public function createproviderspeciality(SpecialityAddRequest $request) {
        $data = [
        'speciality'=> sanitizeVariable($request->speciality),     
        'created_by' =>session()->get('userid'), 
        'updated_by' =>session()->get('userid')   
        ];
        $exist=Speciality::where('speciality',sanitizeVariable($request->speciality))->exists();
        if($exist==false){
            $user = Speciality::create($data);
        }
        else
        {
            return "yes";
        }
       
    }
    
   public function updateproviderspeciality(SpecialityAddRequest $request) {
        $id= sanitizeVariable($request->id);
        $update = [
        'speciality'=> sanitizeVariable($request->speciality),       
        'updated_by' =>session()->get('userid')
        ];
        $exist=Speciality::where('speciality',sanitizeVariable($request->speciality))->exists();
        if($exist==true){
         return "yes";
        }
        else
        {
            Speciality::where('id',$id)->update($update);
           
        }
    }

    public function createprovidersubtype(providersSubtypeAddRequest $request) {
        $id= sanitizeVariable($request->hidden_providersubtype_id);
        $data = [
        'provider_type_id' =>sanitizeVariable($request->provider_type_id),
        'sub_provider_type'=> sanitizeVariable($request->sub_provider_type),
        'created_by' =>session()->get('userid'),
        'updated_by' =>session()->get('userid'),
        'is_active' => 1,
        ];
        $user = ProviderSubtype::create($data);
    }

    ///showing list of roles
    public function providerList(Request $request) {
        $configTZ = config('app.timezone'); 
        $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        if ($request->ajax()) {
           // $data = Providers::with('practice','provider_type','subprovider_type','users')
           // ->select('Providers.name,a.id,a.name,a.phone,a.address,a.email,a.is_active,
           //      b.name as practices,provider_type,sub_provider_type,sp.speciality,f_name, l_name')
           // ->get();
           // echo"<pre>";print_r($data);
            $data = DB::select(DB::raw("select a.id,a.name,a.phone,a.address,a.email,a.is_active,
                b.name as practices,provider_type,a.provider_subtype_id as provider_subtype_id,d.sub_provider_type as sub_provider_type,sp.speciality,f_name, l_name,
                to_char(a.updated_at at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as updated_at
                FROM ren_core.providers as a
                left join ren_core.practices as b on a.practice_id=b.id 
                left join ren_core.provider_types as c on a.provider_type_id = c.id 
                left join ren_core.provider_subtype as d on a.provider_subtype_id = d.id 
                left join ren_core.speciality as sp on sp.id=a.speciality_id 
                left join ren_core.users as u on a.created_by=u.id"));
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editProvider" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                            if($row->is_active == 1){
                               $btn = $btn. '<a href="javascript:void(0)" class="changeProviderstatus_active" data-id="'.$row->id.'"><i class="i-Yess i-Yes" title="Active"></i></a>';
                            } 
                            else
                            {
                              $btn = $btn.'<a href="javascript:void(0)" class="changeProviderstatus_deactive" data-id="'.$row->id.'"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
                            }
                    return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Providers::provider-list');
    }

    public function providerTypeList(Request $request){
        if ($request->ajax()) {
            $data = ProviderType::with('users')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                if($row->id == 1 || $row->id == 2 || $row->id == 3 || $row->id == 4){
                    // $btn ='<a href="javascript:void(0)" class="changeProvidertypestatus_active" data-id="'.$row->id.'"><i class="i-Yess i-Yes" title="Active"></i></a>';
                }else{
                    $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editProviderType" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                        if($row->is_active == 1){
                               $btn = $btn. '<a href="javascript:void(0)" class="changeProvidertypestatus_active" data-id="'.$row->id.'"><i class="i-Yess i-Yes" title="Active"></i></a>';
                         }
                       else
                        {
                          $btn = $btn.'<a href="javascript:void(0)" class="changeProvidertypestatus_deactive" data-id="'.$row->id.'"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
                        }
                    return $btn;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Providers::provider-type-list');
    }


    public function SpecialistList(Request $request){
        
        if ($request->ajax()) {
            $data = Speciality::with('users')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editSpeciality" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                        if($row->status == 1){
                               $btn = $btn. '<a href="javascript:void(0)" class="changeSpecialitystatus_active" data-id="'.$row->id.'"><i class="i-Yess i-Yes" title="Active"></i></a>';
                         }
                       else
                        {
                          $btn = $btn.'<a href="javascript:void(0)" class="changeSpecialitystatus_deactive" data-id="'.$row->id.'"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
                        }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Providers::provider-speciality-list');
    }

    public function providerSubTypeList(Request $request){
    $configTZ = config('app.timezone');
    $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        if ($request->ajax()) {
            // $data = ProviderSubtype::all();  to_char(a.updated_at at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as updated_at,
            $data = DB::select(DB::raw("select a.id,a.provider_type_id,a.sub_provider_type,a.phone_no,a.address,a.is_active,
                b.provider_type,f_name,l_name,
                to_char(a.updated_at at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as updated_at
                FROM ren_core.provider_subtype as a 
                left join ren_core.provider_types as b on a.provider_type_id=b.id left join ren_core.users as u on a.created_by=u.id"));
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editProviderSubtype" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                           if($row->is_active == 1){
                               $btn = $btn. '<a  href="javascript:void(0)" class="changeProvidersubtypestatus_active" data-id="'.$row->id.'"><i class="i-Yess i-Yes" title="Active"></i></a>';
                         }
                       else
                      {
                          $btn = $btn.'<a  href="javascript:void(0)" class="changeProvidersubtypestatus_deactive" data-id="'.$row->id.'"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
                     }
                    return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Providers::provider-subtype-list');
    }


    public function editPhysicians($id) {
        $id = sanitizeVariable($id);
        $user = Providers::find($id);
        return response()->json($user);
    }

    // provider
    public function updateprovider(providersAddRequest $request) {
        $id = sanitizeVariable($request->id);
        $update = [
        'name' =>   sanitizeVariable($request->name),
        'phone'=>   sanitizeVariable($request->phone),
        'email'=>   sanitizeVariable($request->email), 
        'address' =>sanitizeVariable($request->address),
        'provider_type_id' =>sanitizeVariable($request->provider_type_id),
        'provider_subtype_id' =>sanitizeVariable($request->provider_subtype_id),
        'speciality_id' =>sanitizeVariable($request->speciality_id),
        'practice_id' =>sanitizeVariable($request->practice_id),
        'updated_by' =>session()->get('userid'),
        ];
    // dd($update);
        Providers::where('id',$id)->update($update);
    }
    //provider Type
    public function updateprovidertype(providersTypeAddRequest $request) {
        $id= sanitizeVariable($request->id);
        $update = [
        'provider_type'=> sanitizeVariable($request->provider_type), 
        'updated_by' =>session()->get('userid'),
        ];
        ProviderType::where('id',$id)->update($update);
    }
    //provider subtype

    public function updateprovidersubtype(providersSubtypeAddRequest $request) {
        $id= sanitizeVariable($request->id);
        $update = [
        'provider_type_id' =>sanitizeVariable($request->provider_type_id),
        'sub_provider_type'=>sanitizeVariable($request->sub_provider_type),
        'updated_by' =>session()->get('userid'),
        ];
        ProviderSubtype::where('id',$id)->update($update);
    }

    

    //User active or notactive
    public function changeProviderstatus($id) {
        $id = sanitizeVariable($id);
        $row = Providers::find($id);
        $row->is_active=!$row->is_active;
        $row->save();
        // if($row->save()) {
        //     // return redirect()->route('org_physician');
        // } else {
        //     // return redirect()->route('statuschange');
        // }
    }
    public function changeProvidertypestatus($id) {
        $id = sanitizeVariable($id);
        $row = ProviderType::find($id);
        $row->is_active=!$row->is_active;
        $row->save();
    }
    public function changeProvidersubtypestatus($id) {
        $id = sanitizeVariable($id);
        $row = ProviderSubtype::find($id);
        $row->is_active=!$row->is_active;
        $row->save();
    }
     public function changeSpecialitystatus($id) {
        $id = sanitizeVariable($id);
        $row = Speciality::find($id);
        $row->status=!$row->status;
        $row->save();
    }
    public function providerpopulate($id)
    {   $id = sanitizeVariable($id);
        $provider_data = (Providers::self($id) ? Providers::self($id)->population() : "");
        $result['EditProviderForm'] = $provider_data;
        return $result;
    }
    public function providertypepopulate($id){
        $id = sanitizeVariable($id);
        $provider_type_data = (Providertype::self($id) ? Providertype::self($id)->population() : "");
        $result['EditProviderTypeForm'] = $provider_type_data;
        return $result;
    }
    public function providersubtypepopulate($id){
        $id = sanitizeVariable($id);
        $provider_subtype_data = (ProviderSubtype::self($id) ? ProviderSubtype::self($id)->population() : "");
        $result['EditProviderSubtypeForm'] = $provider_subtype_data;
        return $result;
    }
     public function specialitypopulate($id){
        $id = sanitizeVariable($id);
        $provider_type_data = (Speciality::self($id) ? Speciality::self($id)->population() : "");
        $result['EditProviderSpecialityForm'] = $provider_type_data;
        return $result;
    } 

    public function getActiveProvidersList(Request $request) {
       //  $practiceid=$request->practice_id;
     
        $providerList = [];
        $providerList = Providers::activeProviders();
        return response()->json($providerList);
    }
    public function getActivePracticeProvidersList(Request $request) {
        $practiceid=sanitizeVariable($request->practice_id);
        $providerList = [];
        $providerList = Providers::activePracticeProvider($practiceid);
        return response()->json($providerList);
    }
} 