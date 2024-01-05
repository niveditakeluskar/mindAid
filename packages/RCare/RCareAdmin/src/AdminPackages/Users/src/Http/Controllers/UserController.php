<?php

namespace RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers;

use RCare\RCareAdmin\AdminPackages\Users\src\Http\Requests\UserAddRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use RCare\RCareAdmin\AdminPackages\Users\src\Models\User;
use RCare\RCareAdmin\AdminPackages\Users\src\Models\UserRole;
use RCare\RCareAdmin\AdminPackages\Users\src\Models\Role;
use Illuminate\Support\Str;
use Session;
use File;
use DataTables;
use Validator,Redirect,Response;


class UserController extends Controller {
    public function index() {
        return view('Users::users-list');
    }

    //dashboard view

    public function dashboard()
    {
        return view('Users::dashboardv1');
    }

    //user create
    public function createRcareusers(Request $request) {
        $validation = Validator::make($request->all(), [
        'select_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        
       
        ]);
        if($validation->passes()){
           $image = $request->file('select_file');
            // $new_name = time() . '.' . $image->getClientOriginalExtension();
            $new_name = time() . '-' . $image->getClientOriginalName();
            $image->move(public_path('images/usersRcare'), $new_name);
            $img_path = $new_name;
            $user = User::create([
              'f_name' => $request->input('f_name'),        
              'l_name' => $request->input('l_name'),
              'email' => $request->input('email'),
              'role' => $request->input('role'),
              'password' =>Hash::make($request->input('password')),
              'profile_img' =>$img_path,
          ]);

            $user->save();
            $userId = $user->id;
            $roleId = $user->role;

            $UserRole = UserRole::create([
        
             'user_id' => $userId,
             'role_id' => $roleId

        ]);
      }
      
         //return back()->with('success','User created successfully!');
        Session::put('success', 'User created successfully!'); 
        return redirect()->back();
    }

    public function fetchUser(Request $request) {
        if ($request->ajax()) {

             $data =  User::with('roles')->get()->sortByDesc('updated_at');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                     $btn =   '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit" title="Edit"><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>'; 

               



                        if($row->status == 1){
                         $btn = $btn.'<a href="change-User-Status/'.$row->id.'"><i class="i-Yess i-Yes"  style="color: green;" title="Active"></i></a>

                         ';
                       }

                    
                       else
                         {
                        $btn = $btn.'<a href="change-User-Status/'.$row->id.'" title="Inactive"><i class="i-Closee i-Close"  style="color: red;"></i></a>
                         ';
                       }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('show');
    }

    public function edit($id) {
        $user = User::find($id);
        return response()->json($user);
    }

    public function updateUser(Request $request) {
      $id= $request->id;
      $users = User::find($id);
       $update = ['f_name' => $request->f_name, 
                 'l_name' => $request->l_name, 
                 'email' => $request->email, 
                 'status' => $request->status, 
                 'role' => $request->role,
                // 'profile_img'=>$img_path
            ];

            $role = $request->role;

            $user_id= $request->id;
            //$data = UserRole::find($user_id);
            
            $update1 = ['role_id' => $role,  ];
          
  

     
        
         
        $update_query = User::where('id',$id)->update($update);
        $update_role  = UserRole::where('user_id',$user_id)->update($update1);
        $user_id =Session::get('id');
        if ($update_query==1 && $user_id == $id) {
            $items = Session::get('items', []);

            foreach ($users as $update) {
                if ($update['id'] == $id) {
                    $update['item_quantity']--;
                }
            }

            Session::push('items', $items);
        }
       

        if($request->hasFile('select_file')){
          $usersImage = public_path("images/usersRcare/{$users->profile_img}"); // get previous image from folder
        if (File::exists($usersImage)) { // unlink or remove previous image from folder
            File::delete($usersImage);
        }
        $image = $request->file('select_file');
        $new_name = time() . '-' . $image->getClientOriginalName();
        $image = $image->move(('images/usersRcare'), $new_name);
        $img_path = $new_name;

        $update=array('profile_img'=>$img_path);
        $update_query = User::where('id',$id)->update($update);
      }

       //return back()->with('success','User created successfully!');
      Session::put('success','User updated successfully!');
      return redirect()->back();
   }

    //User active or notactive
    public function changeUserStatus($id) {
        $row = User::find($id);
        $row->status=!$row->status;
        
        if($row->save()) {
           Session::put('success','User status updated successfully!');
           return redirect()->back();
            // return redirect()->route('users');
        } else {
           Session::put('danger','User status not updated successfully!');
              return redirect()->back();
            // return redirect()->route('statuschange');
        }
    }
}
