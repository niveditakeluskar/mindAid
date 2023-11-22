<?php
namespace App\Http\Requests;
namespace RCare\Org\OrgPackages\Users\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Users\src\Http\Requests\OrgUserAddRequest;
use RCare\Org\OrgPackages\Users\src\Http\Requests\OrgUserUpdateRequest;
use RCare\Org\OrgPackages\Users\src\Http\Requests\OrgUserPasswordUpdateRequest;
use RCare\Org\OrgPackages\Users\src\Http\Requests\OrgUserLinkPracticeRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use RCare\Org\OrgPackages\Users\src\Models\OrgUserRole;
use RCare\Org\OrgPackages\Roles\src\Models\Roles;
use RCare\Org\OrgPackages\Users\src\Models\UserPractices;
use RCare\Org\OrgPackages\Practices\src\Models\Practices;
use RCare\Org\OrgPackages\Users\src\Models\UserCategory;
use RCare\Org\OrgPackages\Users\src\Models\Compliance;
use RCare\Org\OrgPackages\Responsibility\src\Models\Responsibility;
use RCare\Org\OrgPackages\Users\src\Models\UsersResponsibility;
use Carbon\Carbon;
use RCare\Org\OrgPackages\Roles\src\Models\RolesTypes;
use Illuminate\Http\UploadedFile; 


use DataTables;
use Hash;
use Redirect,Response;
use Session;
use File,DB;

use Illuminate\Validation\Rule;
use Validator;


class UserController extends Controller {
    public function index() {
        $responsibility = Responsibility::all();
        $cid = session()->get('userid');
        $usersdetails = Users::where('id',$cid)->get();
        $roleid = $usersdetails[0]->role;  
      
        return view('Users::user-list',compact('responsibility','roleid'));
    }

    public function userProfileimage(Request $request)
    { 
        if(isset($_FILES) && !empty($_FILES)) 
            {        
            if(sanitizeVariable($request->hasFile('file'))){                  
               $image = sanitizeVariable($request->file('file'));
               $fname=sanitizeVariable($request->input('f_name'));
               $lname=sanitizeVariable($request->input('l_name')); 
               $time=time(); 
                    $original_name = preg_replace("/\s+/", "_", $image->getClientOriginalName());
                    $file_extension=$image->getClientOriginalExtension();
                    $new_name =$fname."_".$lname."_".$time.".".$file_extension;  
                    //dd($new_name);              
                    $image = $image->move(public_path('images/usersRcare'), $new_name);
                    $img_path =$new_name;                       
                    return $img_path;
            }
        }
 
   }


    public function createRcareusers(OrgUserAddRequest $request) 
    {
       \DB::beginTransaction();
       try {
            $userdata = array(
                'f_name'       => sanitizeVariable($request->f_name), 
                'l_name'       => sanitizeVariable($request->l_name), 
                'email'        => sanitizeVariable($request->email), 
                'role'         => sanitizeVariable($request->role),
                'category_id'  => 2,
                // 'org_id'       => $request->org_id,
                'report_to'    => sanitizeVariable($request->report_to), 
                'practice__id' => sanitizeVariable($request->practice__id),
                'emp_id'       => sanitizeVariable($request->emp_id),
                'password'     => Hash::make(sanitizeVariable($request->input('password'))),
                'profile_img'  => sanitizeVariable($request->image_path), 
                'created_by'   => session()->get('userid'),
                'updated_by'   => session()->get('userid'),
                'status'       => 1,
                'extension'    => sanitizeVariable($request->extension),
                'office_id'    => sanitizeVariable($request->office_id),
                'number'       => str_replace(" ", "", sanitizeVariable($request->number)),
                'country_code' => sanitizeVariable($request->country_code),
            );
             //dd($userdata);
            $user = Users::create($userdata);
            // $user->save();
            $userId = $user->id;
           // dd($userId);
           $roleId = $user->role;
           // dd($roleId);
           if($userId) {
                $UserRole = OrgUserRole::create([
                    'user_id' => $userId,
                    'role_id' => $roleId
                ]);
                //echo $UserRole;
                $Practices = UserPractices::create([
                    'user_id'     => $userId,
                    'practice_id' => sanitizeVariable($request->practice__id)
                ]);

                $responsibility_id = sanitizeVariable($request->responsibility_id);
                
                if(!empty($responsibility_id))
                {
                
                 foreach($responsibility_id as $key => $val) {
                   
                if(!empty($val)){
                    $UsersResponsibility = UsersResponsibility::create([
                        'user_id' => $userId,
                        'responsibility_id' => $key,
                        'created_by' =>  session()->get('userid'),
                        'updated_by' => session()->get('userid')
                    ]); 
                }
            
                }
            }
           } 
        } catch (\Exception $ex){
            // \Log::error("Error in store(): ".$ex->getMessage());
            \DB::rollBack();
            Session::flash('error', 'Error');
            return back();
        }
        DB::commit();
        Session::flash('success', 'Selected record has been successfully saved');
        return back();
    }

    public function usersList(Request $request)
    { 
       

        if ($request->ajax()) {
            
            // $data = Users::latesanitizeVariable(st()->with('reportTo')->with('roleName')->with('rcareOrgs')->get();
            $data = Users::with('reportto','roleName','rcareOrgs','users','office','users_responsibility','users_responsibility.responsibility')->orderby('updated_at','desc')->get();       
            // dd($data);
           return Datatables::of($data)  
            ->addIndexColumn() 
            ->addColumn('action', function($row){ 
                // dd($row->role);
            $cid = session()->get('userid');
            $usersdetails = Users::where('id',$cid)->get();
            $roleid = $usersdetails[0]->role ; 
            $btn =   '<a href="javascript:void(0)" class="edit" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Edit"  title="Edit"><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>'; 
            if($row->status == 1){
                $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_userstatus_active" data-toggle="tooltip" data-original-title="Deactive"  title="Deactive"><i class="i-Yess i-Yes"  style="color: green;" ></i></a>';
            }else {
                $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_userstatus_active" data-toggle="tooltip" data-original-title="Active" title="Active"><i class="i-Closee i-Close"  style="color: red;"></i></a>';
            }
            if($row->block_unblock_status == 1 || $row->temp_lock_time != null && $row->temp_lock_time != "00:00:00"){
                $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_userstatus_block" data-toggle="tooltip" data-original-title="Unlock"  title="Unlock">Unlock</a>';
            }else{
              //  $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_userstatus_block" data-toggle="tooltip" data-original-title="Block"  title="Block">Block</a>';
            }

            if($roleid == 6){
                $btn1 = '<a href="#" title="Login As"><button type="button" id ="loginas'.$row->id.'"class="btn btn-primary_loginas" onclick="loginAction('.$row->id.')">Login As</button></a>';    
                $btn = $btn.$btn1; 
            } 
            
            return $btn;
            })
 
            ->rawColumns(['action']) 
            ->make(true); 
            
            
        }
        $responsibility = Responsibility::all();
        // dd($responsibility);
        return view('Users::user-list',compact('responsibility'));
    }
    public function edit($id) {
        $user = Users::find($id);
        return response()->json($user);
    }

    public function updateUsers(OrgUserUpdateRequest $request) {
        $id =   sanitizeVariable($request->id);
        $users = Users::find($id);
        $userId = $users->id;
        $img_path=sanitizeVariable($request->image_path_edit);
            if($img_path!=' '){
                $usersImage = public_path("images/usersRcare/{$users->profile_img}"); // get previous image from folder
                    if (File::exists($usersImage)) { // unlink or remove previous image from folder
                            File::delete($usersImage);
                    }
              $img_path = sanitizeVariable($request->image_path_edit);
             
            }else{
                $img_path = sanitizeVariable($request->input('hidden_profile_image'));
               
            }

            if($request->role == '6'){
                $reportto = 0;
            }else{
                $reportto = sanitizeVariable($request->report_to);
            }
        $update_users = array(
                    'f_name'       => sanitizeVariable($request->f_name), 
                    'l_name'       => sanitizeVariable($request->l_name), 
                    'email'        => sanitizeVariable($request->email),  
                    'status'       => sanitizeVariable($request->status), 
                    'role'         => sanitizeVariable($request->role),
                    'category_id'  => 2, 
                    'org_id'       => sanitizeVariable($request->org_id),
                    'report_to'    => $reportto, 
                    'practice__id' => sanitizeVariable($request->practice__id),
                    'emp_id'       => sanitizeVariable($request->emp_id), 
                    'profile_img'  => $img_path,
                    'updated_by'   => session()->get('userid'),
                    'status'       => 1,
                    'extension'    => sanitizeVariable($request->extension),
                    'office_id'    => sanitizeVariable($request->office_id),
                    'number'    => str_replace(" ", "", sanitizeVariable($request->number)),
                    'country_code' => sanitizeVariable($request->country_code),
                  //  'responsibility_id' => $myJSON//$res
            ); 
            // dd($update); 
        $user_update = Users::where('id',$id)->update($update_users);
        $responsibility_id = sanitizeVariable($request->responsibility_id);
            $myJSON = json_encode($responsibility_id);
            $json = json_decode($myJSON); 
            if($json!=''){  
                UsersResponsibility::where('user_id',$userId)->delete();
                foreach($json as $key => $val) {
                    // echo "$key = $val<br/>"; 
                    if(!empty($key) &&  !empty($val)){
                        $UpdateUsersResponsibility =array(
                            'user_id' =>$userId,  
                            'responsibility_id' => $key,
                            'created_by' =>  session()->get('userid'),
                            'updated_by' => session()->get('userid')
                        ); 
                        // print_r($UpdateUsersResponsibility);
                        UsersResponsibility::create($UpdateUsersResponsibility);
                    }
                } 
            }
   
    }
    //User level MFA
    public function updateUserlevelMfa(Request $request) {
        \DB::beginTransaction();
        try {
            $id = sanitizeVariable($request->id);
            $mfa_status = sanitizeVariable($request->mfa_status);
            $update =array(
                'mfa_status' => $mfa_status,
                'updated_by' => session()->get('userid')
            );
            $update_query = Users::where('id',$id)->orderBy('id', 'desc')->update($update);
         
        } catch (\Exception $ex){
            // \Log::error("Error in store(): ".$ex->getMessage());
            \DB::rollBack();
            Session::flash('error', 'Error');
            return back();
        }
        DB::commit();
        Session::flash('success', 'Selected record has been successfully Updated');
        return back();
    }
    

    //User active or Inactive
    public function changeUserStatus(Request $request) {
        $id = sanitizeVariable($request->id);  
        $data = Users::where('id',$id)->get(); 
        $status =$data[0]->status;
        if($status==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = Users::where('id',$id)->orderBy('id', 'desc')->update($status);
          return "Deactive";
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = Users::where('id',$id)->orderBy('id', 'desc')->update($status);
          return "Active";
        }
    }
    
    //User block or unblock created by anand
    public function changeBlockUnblockStatus(Request $request) {
        $id = sanitizeVariable($request->id);  
        $data = Users::where('id',$id)->get(); 
        $block_unblock_status =$data[0]->block_unblock_status;
        $temp_lock_time =$data[0]->temp_lock_time;

        if($temp_lock_time!=Null && $temp_lock_time!='' && $temp_lock_time!="00:00:00"){
             $status =array('temp_lock_time'=>"00:00:00", 'updated_by' =>session()->get('userid'));
             $update_query = Users::where('id',$id)->orderBy('id', 'desc')->update($status);
         
             return "Unblock ";
        }else if($block_unblock_status==1){
          $status =array('block_unblock_status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = Users::where('id',$id)->orderBy('id', 'desc')->update($status); 
          return "Unblock ";

        }else{
          $status =array('block_unblock_status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = Users::where('id',$id)->orderBy('id', 'desc')->update($status);
          return "block ";
        }
    }

    public function reportingUser(Request $request){
        $level = Roles::where('id',sanitizeVariable($request->role))->orderBy('role_name','asc')->get();
        $roles = Roles::where('level','<',$level[0]['level'])->orderBy('role_name','asc')->get();
        $role_id='';
        foreach($roles as $val){
            $role_id .= $val->id.',';
        } 
        $value = Users::whereIn('role',array(rtrim($role_id,',')))->orderBy('role_name','asc')->get();    
            echo "<option value='0'>None</option>";
           foreach($value as $key){
                echo "<option value='".$key->id."'>".$key->f_name."</option>";
          }
    }

    public function populate(Request $request)
    {   $id = sanitizeVariable($request->input('id'));
        $user = Users::find($id)->population();
        $users_resp = UsersResponsibility::where('user_id', $id)->orderBy('created_at', 'desc')->get()->toArray();
        $result = $user;
        $result['user_details'] = $users_resp;
        $result['change_mfa'] =$users_resp;
        return $result;
    }

    public function reportTo(Request $request) {
        $role_id = sanitizeVariable($request->input("role_id"));

        $users = DB::table('ren_core.users')
                ->select("f_name", "l_name", "users.id")
                ->join("ren_core.roles", "roles.id", "=", "users.role")
                ->where("roles.level","<=", function($query) use($role_id)
                    {
                        $query->select( 'level' )
                        ->from('ren_core.roles')
                        ->where("roles.id", $role_id);
                    })
                ->orderBy('role_name','asc')
                    ->get();
        $reportTo = [];
        $i=1;
        foreach ($users as $user) {
            $reportTo[$i]["id"]     = $user->id;
            $reportTo[$i]["f_name"] = $user->f_name;
            $reportTo[$i]["l_name"] = $user->l_name;
            $i++;
        }
        return response()->json($reportTo);
    }

    /*Set an user's password*/
    public function updateUserPassword(OrgUserPasswordUpdateRequest $request)
    {
        $static            = Users::find(sanitizeVariable($request->input("id")));
        $static->password  = Hash::make(sanitizeVariable($request->input("password")));
        $static->save();
        return response()->json($static->json());

       // 'password'     =>Hash::make($request->input('password')),
    }

    /* Set an user's Practice */

    public function linkedUserPractices($id) {
        $id          = sanitizeVariable($id);
        $practices = []; $userPracticeData = [];
        $userPractices = DB::table('ren_core.user_practices')
                ->select("user_practices.practice_id", "practices.name", "practices.number", "practices.is_active")
                ->join("ren_core.practices", "practices.id", "=", "user_practices.practice_id")
                ->where('user_practices.user_id', $id)
                ->get();
        return Datatables::of($userPractices)
        ->addIndexColumn()
        ->make(true);
    }

    
    public function linkUserPractices(OrgUserLinkPracticeRequest $request) {

        $id          = sanitizeVariable($request->input("id"));
        $practice_id = sanitizeVariable($request->input("practice__id"));

        $userPractices= UserPractices::create(
            [
                'user_id'     => $id,
                'practice_id' => $practice_id
            ]);
        return response()->json($userPractices);
    }

    public function LoginAsDeveloper($newid){

        $newid = sanitizeVariable($newid);        
        $newusersdetails = Users::where('id',$newid)->get();       
        $roleid = $newusersdetails[0]->role;        
        $role_details = RolesTypes::userRoleType($roleid);   
		$role_type = $role_details[0]->role_type;
       
        session()->put([
        'email'=>$newusersdetails[0]->email,
        'userid'=>$newusersdetails[0]->id,   
        'status'=>$newusersdetails[0]->status,
        'role'=>$newusersdetails[0]->role,
        'f_name'=>$newusersdetails[0]->f_name,
        'l_name'=>$newusersdetails[0]->l_name,
        'profile_img'=>$newusersdetails[0]->profile_img,
        'user_type'=>$newusersdetails[0]->role,
        'org_id'=>$newusersdetails[0]->org_id,
        'role_type'=>$role_type 
        ]);

        $email       =    $newusersdetails[0]->email;
        $password    =    $newusersdetails[0]->password;
        $response = array();
        $response['success']='y';
        $response['url'] = 'patients/worklist';
        $response['error']='';
        return $response;  
      
    }
	
	public function DarkTheme(Request $request){  
        $theme_mode =array('theme'=>$request->darkmode);
        $id = session()->get('userid');
        $update_query = Users::where('id',$id)->orderBy('id', 'desc')->update($theme_mode);
        $theme = $request->darkmode;
        session()->put([ 
           'darkmode'=>$theme//$checked        
           ]);
    }
   
}  