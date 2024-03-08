<?php
namespace RCare\Org\OrgPackages\DomainFeatures\src\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\DomainFeatures\src\Http\Requests\DomainFeaturesAddRequest;
use RCare\Org\OrgPackages\DomainFeatures\src\Models\DomainFeatures;
use DataTables;
use Session;
class DomainFeaturesController extends Controller{

     public function index()
    {   
        return view('DomainFeatures::domain-feature-list'); 
    }


    public function fetchDomainFeatures(Request $request){
        if ($request->ajax()) {
            $data = DomainFeatures::with('users')->orderBy('status','desc')->get();
            return Datatables::of($data)
            ->addIndexColumn() 
            ->addColumn('action', function($row){
                $btn =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editDomainFeatures" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                if($row->status == 1){
                    $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_Domainstatus_active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                }else{
                    $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_Domainstatus_deactive"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('DomainFeatures::domain-feature-list');
    }
    public function createDomainFeatures(DomainFeaturesAddRequest $request) {
        $id = sanitizeVariable($request->id); 
        $data = array(
            'url'               => sanitizeVariable($request->url),
            'features'          => sanitizeVariable($request->features),
            'password_attempts' => sanitizeVariable($request->password_attempts),
            'digit_in_otp'      => sanitizeVariable($request->digit_in_otp),
            'otp_max_attempts'  => sanitizeVariable($request->otp_max_attempts),
            'otp_text'          => sanitizeVariable($request->otp_text),
            'otp_email'         => sanitizeVariable($request->otp_email),
            'no_of_days'        => sanitizeVariable($request->no_of_days),
            'status'            => '1',
            'session_timeout'   => sanitizeVariable($request->session_timeout),
            'logoutpoptime'     => sanitizeVariable($request->logoutpoptime),
            'idle_time_redirect'=> sanitizeVariable($request->idle_time_redirect),
            'block_time'        => sanitizeVariable($request->block_time),
            'instance'          => sanitizeVariable($request->instance),
            'updated_by'        => session()->get('userid'),
            'created_by'        => session()->get('userid'),
        );

        $dataExist  = DomainFeatures::where('id', $id)->exists();
        if ($dataExist == true) { 
            $data['updated_by']= session()->get('userid'); 
            $update_query = DomainFeatures::where('id',$id)->orderBy('id', 'desc')->first()->update($data);
        } else { 
            $data['created_by']= session()->get('userid'); 
            $data['updated_by']= session()->get('userid'); 
            $insert_query = DomainFeatures::create($data);
        }
    }

    //     public function populate(Request $request)
    // {
    //     // return printHello('pranali');
    //     $orgmenus = OrgMenus::find(sanitizeVariable($request->input('org_menu_id')));
    //     return $orgmenus->population();
    // }
    // public function editMenu($id)
    // {
    //     $id= sanitizeVariable($id);
    //     $menu = OrgMenus::find($id);
    //     return response()->json($menu);
    // }


        public function editDomainFeatures($id)
        {
          $id = sanitizeVariable($id); 
            $data = (DomainFeatures::self($id) ? DomainFeatures::self($id)->population() : "");
            $result['domain_features_form'] = $data;
            return $result;   
        }



        public function changeDomainFeaturesStatus(Request $request)
        {
        $id= sanitizeVariable($request->id);
        $row = DomainFeatures::find($id);
       // dd($row);
        $row->status=!$row->status;
        $row->save();
        /*$config_type = Configurations::where('id',$id)->pluck('config_type');
        $status_deactive = ['status' => '0','updated_by'=> session()->get('userid')];
        $status_active = ['status' => '1','updated_by'=> session()->get('userid')];
        $all = Configurations::where('config_type',$config_type[0])->where('status', 1)->update($status_deactive);
        // dd($all);
        Configurations::where('id',$id)->update($status_active);
        return response()->json(['success'=>'Configuration status update successfully.']);*/
        return view('DomainFeatures::domain-feature-list'); 
        }
    
}