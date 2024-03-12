<?php

namespace RCare\RCareAdmin\AdminPackages\UserRoles\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\RCareAdmin\AdminPackages\UserRoles\src\Http\Requests\UserRoleAddRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RCare\RCareAdmin\AdminPackages\UserRoles\src\Models\UserRoles;
use DataTables;
use Validator;

class UserRolesController extends Controller {
    public function index() {
        return view('UserRoles::user-role-list');
    }

    public function createUsersRoles(UserRoleAddRequest $request) {
        $user = UserRoles::create(request(['role_name']));

         Session::put('success','Role created successfully!');
             // return redirect()->back();
              return redirect()->route();

      //  return redirect('/')->with('success', 'Message sent!');
        // return back()->with('success','Role created successfully!');
       /* $arr = array('msg' => 'Something goes to wrong. Please try again lator', 'status' => false);
        if($user){ 
        $arr = array('msg' => 'Successsfully submit form using ajax', 'status' => true);
        }
        return Response()->json($arr);*/
    }

    ///showing list of roles
    public function usersRolesList(Request $request) {
        if ($request->ajax()) {
            $data = UserRoles::latest()->get()->sortByDesc('updated_at');
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                
                $btn =   '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit editroles" title="Edit"><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>';   


                if($row->status == 1){
                    $btn =  $btn. '<a href="changeRoleStatus/'.$row->id.'"><i class="i-Yess i-Yes" style="color: green;" title="Active"></i></a>

                    ';
                } else {
                    $btn =  $btn. '<a href="changeRoleStatus/'.$row->id.'"><i class="i-Closee i-Close" style="color: red;" title="Inactive"></i></a>

                    ';
                } 
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('UserRoles::user-role-list');
    }

    public function editRoles($id) {
        $user = UserRoles::find($id);
        return response()->json($user);
    }

    // edit users
    public function updateUserRole(Request $request) {
        $id= $request->id;
        $update = ['role_name' => $request->role_name];
        UserRoles::where('id',$id)->update($update);
        return "success";
        // return back()->with('success','Role updated successfully!');
    }

    public function deleteRole(Request $request) {
        $id= $request->id;
        $update = ['status' => '0'];
        UserRoles::where('id',$id)->update($update);
        return response()->json(['success'=>'Role deleted successfully.']);
    }

    //User active or notactive
    public function changeRoleStatus($id) {
        $row = UserRoles::find($id);
        $row->status=!$row->status;
        if($row->save()) {
            return redirect()->route('roles');
        } else {
            return redirect()->route('statuschange');
        }
    }

    public function populate(Request $request)
    {
        // return printHello('pranali');
        $role = UserRoles::find($request->input('role_id'));
        return $role->population();
    }
}


