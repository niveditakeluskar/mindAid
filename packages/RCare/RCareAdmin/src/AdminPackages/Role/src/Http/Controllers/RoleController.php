<?php

namespace RCare\RCareAdmin\AdminPackages\Role\src\Http\Controllers;

use RCare\RCareAdmin\AdminPackages\Role\src\Http\Requests\RoleSaveRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use RCare\RCareAdmin\AdminPackages\Role\src\Models\Role;
use DataTables;

class RoleController extends Controller {
    public function index() {
        return view('Role::user-roles');
    }

    public function createUsersRoles(RoleAddRequest $request)
    {
        $user =Roles::insert(['role_name' => $request->input('role_name')]);
        return back()->with('success','Role created successfully!');
    }

    ///showing list of roles
    public function usersRolesList(Request $request)
    {
        if ($request->ajax()) {
            $data = Roles::latest()->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editroles" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                if($row->status == 1){
                    $btn = $btn. '<a href="changeRoleStatus/'.$row->id.'"><i class="i-Yess i-Yes" title="Active"></i></a>

                    ';
                } else {
                    $btn = $btn.'<a href="changeRoleStatus/'.$row->id.'"><i class="i-Closee i-Close"  title="Inactive"></i></a>

                    ';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Role::user-roles');
    }

    public function editRoles($id)
    {
        $user = Roles::find($id);
        return response()->json($user);
    }

    // edit users
    public function updateUserRole(Request $request)
    {
        $id= $request->id;
        $update = ['role_name' => $request->role_name];
        Roles::where('id',$id)->update($update);
        return back()->with('success','Role updated successfully!');
    }

    /*
    public function deleteRole(Request $request)
    {
        $id= $request->id;
        $update = ['status' => '0'];
        Roles::where('id',$id)->update($update);
        return response()->json(['success'=>'Role deleted successfully.']);
    }
    */

    public function changeRoleStatus($id)
    {
        //User active or notactive
        $row = Roles::find($id);
        $row->status=!$row->status;
        if($row->save()) {
            return redirect()->route('roles');
        } else {
            return redirect()->route('statuschange');
        }
    }
}
