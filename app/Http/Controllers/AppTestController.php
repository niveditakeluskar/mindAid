<?php

namespace App\Http\Controllers;
use Auth;
use App\Http\Requests\TestUserAddRequest;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use DataTables;

class AppTestController extends Controller {
    //
    public function index() {
        return view('test.testUsersList');
    }
	
	public function timezone(){
		return view('timezone');
	}
	
    //user create
    public function createRcareusers(UserAddRequest $request) {
        $data = $request->validate([
            'f_name' => 'required|max:255',
            'l_name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmation' => 'required',
        ]);
        $user = User::create(request(['f_name', 'l_name', 'email', 'remember_me', 'password']));
        //auth()->login($user);
        return back()->with('success','User created successfully!');
    }

    public function fetchUser(Request $request) {
        if ($request->ajax()) {
            /*  $data = User::latest()->get()->where('status',1);*/
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
            /* $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteUser">Delete</a>';*/
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

    // edit users
    public function updateUser(Request $request) {
        $id= $request->id;
        $update = ['f_name' => $request->f_name, 'l_name' => $request->l_name, 'email' => $request->email, 'status' => $request->status];
        User::where('id',$id)->update($update);
        return back()->with('success','User updated successfully!');
    }

    public function changeUserStatus($id) {
        //User active or notactive
        $row = User::find($id);
        $row->status=!$row->status;
        if($row->save()) {
            return redirect()->route('users');
        } else {
            return redirect()->route('statuschange');
        }
    }

	
}
