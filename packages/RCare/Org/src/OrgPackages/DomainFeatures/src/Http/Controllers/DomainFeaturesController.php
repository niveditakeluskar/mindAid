<?php
namespace RCare\Org\OrgPackages\DomainFeatures\src\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\DomainFeatures\src\Http\Requests\DomainFeaturesAddRequest;
use RCare\Org\OrgPackages\DomainFeatures\src\Models\DomainFeatures;
use DataTables;
use Session;
use File,DB;
class DomainFeaturesController extends Controller{

     public function index()
    {   
        return view('DomainFeatures::domain-feature-list'); 
    }

    public function domainlogoimage(Request $request)
    { 
        if(isset($_FILES) && !empty($_FILES)) 
            {        
            if(sanitizeVariable($request->hasFile('file'))){                  
               $image = sanitizeVariable($request->file('file'));
               $url=sanitizeVariable($request->input('url'));
            //    $lname=sanitizeVariable($request->input('l_name')); 
               $time=time(); 
                    $original_name = preg_replace("/\s+/", "_", $image->getClientOriginalName());
                    $file_extension=$image->getClientOriginalExtension();
                    $new_name = $image->getClientOriginalName();
                    $new_name =$url."_".$time.".".$file_extension;  
                    // dd($new_name);              
                    $image = $image->move(public_path('images/usersRcare'), $new_name);
                    $img_path =$new_name;                       
                    return $img_path;
            }
        }
 
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
    public function createDomainFeatures(DomainFeaturesAddRequest $request) { //dd($request);
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
        $domain = DomainFeatures::find($id);
        $urlId = $domain->id;
        
        if ($dataExist == true) { 

            $img_path=sanitizeVariable($request->image_path);
            if($img_path!=' '){
                $domainlogo = public_path("images/usersRcare/{$domain->logo}"); // get previous image from folder
                    if (File::exists($domainlogo)) { // unlink or remove previous image from folder
                            File::delete($domainlogo);
                    }
              $img_path1 = sanitizeVariable($request->image_path);
             
            }else{
                $img_path1 = sanitizeVariable($request->input('hidden_domain_logo'));   
            }


            $data['logo']  = $img_path1;
            $data['updated_by']= session()->get('userid'); 
            $update_query = DomainFeatures::where('id',$id)->orderBy('id', 'desc')->first()->update($data);
        } else { 
            $data['logo']  = sanitizeVariable($request->image_path);
            $data['created_by']= session()->get('userid'); 
            $data['updated_by']= session()->get('userid'); 
            $insert_query = DomainFeatures::create($data);
            // dd($insert_query);
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
            $domainFeature = DomainFeatures::find($id);
            $logoPath = $domainFeature->logo;

            // $result['domain_features_form'] = $data;
            // return $result;  
            return [
                'domain_features_form' => $data,
                'logo_path' => $logoPath
            ]; 
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