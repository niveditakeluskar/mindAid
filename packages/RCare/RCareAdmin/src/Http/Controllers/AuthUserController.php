<?php

namespace RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers;

use RCare\RCareAdmin\AdminPackages\Users\src\Http\Requests\UserAddRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use RCare\RCareAdmin\AdminPackages\Users\src\Models\User;
use RCare\RCareAdmin\AdminPackages\Users\src\Models\UserRole;
use RCare\RCareAdmin\AdminPackages\Users\src\Models\UserRoles;
use DataTables;
use File;
use Validator,Redirect,Response;


class AuthUserController extends Controller {
    public function index() {
        return view('Users::users-list');
    }

    //user create
    public function createRcareusers(UserAddRequest $request) {
     
       
        $validation = Validator::make($request->all(), [
        'select_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
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
        return back()->with('success','User created successfully!');
    }

    public function fetchUser(Request $request) {
        if ($request->ajax()) {
            $data = User::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                     $btn =   '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit" title="Edit"><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>'; 





                        if($row->status == 1){
                         $btn = $btn.'<a href="changeUserStatus/'.$row->id.'"><i class="i-Yess i-Yes"  style="color: green;" title="Active"></i></a>

                         ';
                       }

                    
                       else
                         {
                        $btn = $btn.'<a href="changeUserStatus/'.$row->id.'" title="Inactive"><i class="i-Closee i-Close"  style="color: red;"></i></a>
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
        $update = ['f_name' => $request->f_name, 'l_name' => $request->l_name, 'email' => $request->email, 'status' => $request->status];
        User::where('id',$id)->update($update);
        return back()->with('success','User updated successfully!');
    }

    //User active or notactive
    public function changeUserStatus($id) {
        $row = User::find($id);
        $row->status=!$row->status;
        
        if($row->save()) {
            return redirect()->route('users');
        } else {
            return redirect()->route('statuschange');
        }
    }
}
