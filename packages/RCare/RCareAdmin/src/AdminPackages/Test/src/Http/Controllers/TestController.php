<?php

namespace RCare\RCareAdmin\AdminPackages\Test\src\Http\Controllers;

use App\Http\Controllers\Controller;
use RCare\RCareAdmin\AdminPackages\Test\src\Http\Requests\TestAddRequest;
use RCare\RCareAdmin\AdminPackages\Test\src\Models\Test;
use RCare\RCareAdmin\AdminPackages\Users\src\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Support\ModelMapper;
use Illuminate\Database\Eloquent\Model;
use DataTables;
use Validator;
use Ayesh\PHP_Timer\Timer; 

class TestController extends Controller {
    public function index() {
        return view('Test::timer');
    }

    public function testAdd() {
        return view('Test::test-add');
    }

    //user create
    public function createRcareusers(TestAddRequest $request) {
        $user = User::create(request(['f_name', 'l_name', 'email', 'remember_me', Hash::make('password')]));
        return back()->with('success','User created successfully!');
    }

    public function fetchTest(Request $request) {
        if ($request->ajax()) {
            $data = User::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if($row->status == 1){
                        $btn = '<a href="changeUserStatus/'.$row->id.'"
                        onclick="return alert("Are you sure you want to Inactive this user?")" class="btn btn-success btn-sm">Active</a>

                        ';
                    } else {
                        $btn = '<a href="changeUserStatus/'.$row->id.'" 
                        data-id="" onclick="return confirm("Are you sure you want to Deactive this user?")" class="btn btn-warning btn-sm">Inactive</a>

                        ';
                    }
                    $btn = $btn.  '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm edit">Edit</a>'; 
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('show');
    }

    public function edit($id) {
        $user = User::find($id);
        // return response()->json($user);
        return response()->json(["form" => $user]);
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

    public function autoPopulateExample() {
        return view('Test::test-auto-populate');
    }

    /**
     * Return the information to populate the form with
     *
     * @return array
     */
    public function populate(Request $request)
    {
        // return printHello('pranali');
        $user = User::find($request->input('test_id'));
        return $user->population();
    }
}
