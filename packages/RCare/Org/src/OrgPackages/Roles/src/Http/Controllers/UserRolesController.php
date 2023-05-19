<?php

namespace RCare\Org\OrgPackages\Roles\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Roles\src\Http\Requests\UserRoleAddRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\Roles\src\Models\Roles;
use DataTables;
use Session; 
class UserRolesController extends Controller {
    public function index() {
        return view('Roles::user-role-list');
        
    }

    public function createUsersRoles(UserRoleAddRequest $request) {
        $data = array(
            'role_name' => sanitizeVariable($request->role_name),
            'status' => '1',
            'level' => sanitizeVariable($request->level),
            'updated_by'=> session()->get('userid'),
            'created_by'=> session()->get('userid'),
        );
        $user = Roles::create($data);
        // return back()->with('success','Role created successfully!');
        Session::put('success','Role created successfully!'); 
        return redirect()->back();
    }

    ///showing list of roles
    public function usersRolesList(Request $request) {
        if ($request->ajax()) {
            $data = Roles::with('users')->latest()->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editroles" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                if($row->status == 1){
                    $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_role_active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                }else{
                    $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_role_deactive"><i class="i-Closee i-Close"  title="Inactive"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Roles::user-role-list');
    }

    public function editRoles($id) {
        $user = Roles::find(sanitizeVariable($id));
        return response()->json($user);
    }

    // edit users
    public function updateUserRole(UserRoleAddRequest $request) {
        $id= sanitizeVariable($request->id);
        $update = ['role_name' => sanitizeVariable($request->role_name), 'level' => sanitizeVariable($request->level),'updated_by'=> session()->get('userid')];
        Roles::where('id',$id)->update($update);
        // return back()->with('success','Role updated successfully!');
        Session::put('success','Role updated successfully!'); 
        return redirect()->back();
    }

    public function deleteRole(Request $request) {
        $id= sanitizeVariable($request->id);
        $data = Roles::where('id',$id)->get();
        $status =$data[0]->status;
        if($status==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = Roles::where('id',$id)->orderBy('id', 'desc')->update($status);
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = Roles::where('id',$id)->orderBy('id', 'desc')->update($status);
        }
    }

    //User active or notactive
    public function changeRoleStatus(Request $request) {
        $id= sanitizeVariable($request->id);
        $data = Roles::where('id',$id)->get();
        $status =$data[0]->status;
        if($status==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = Roles::where('id',$id)->orderBy('id', 'desc')->update($status);
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = Roles::where('id',$id)->orderBy('id', 'desc')->update($status);
        }
    }
} 