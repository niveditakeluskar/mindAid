<?php

namespace RCare\RCareAdmin\AdminPackages\Services\src\Http\Controllers;

use App\Http\Controllers\Controller;
use RCare\RCareAdmin\AdminPackages\Services\src\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DataTables;

class ServicesController extends Controller {

    public function index() {
        return view('Services::services');
    }

    public function createServices(Request $request) {
        $services = Services::create(request(['service_name']));
        return back()->with('success','Service created successfully!');
    }

    public function fetchServices(Request $request) {
        if ($request->ajax()) {
            $data = Services::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" data-original-title="Edit"  class="editServices"><i class="text-15 editform i-Pen-4" style="color: #2cb8ea;"></i></a>';

                       if($row->status == 1){
                        $btn = $btn.'<a href="changeServiceStatus/'.$row->id.'"><i class="text-15 i-Yes"  style="color: green;"></i></a>';
                      }

                  
                      else
                        {
                       $btn = $btn.'<a href="changeServiceStatus/'.$row->id.'"><i class="text-15 i-Close"  style="color: red;"></i></a>';

                      }
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('fetchServices');
    }

    public function editServices($id) {
        $services = Services::find($id);
        return response()->json($services);
    }

    public function updateServices(Request $request) {
        $id= $request->service_id;
        $update = ['service_name' => $request->service_name];
        Services::where('id',$id)->update($update);
    
        return back()->with('success','Service updated successfully!');
    }

   public function deleteServices(Request $request) {
        Log::info($request);
        $id= $request->service_id;
        $update = ['status' => '0'];
        Services::where('id',$id)->update($update);

        return response()->json(['success'=>'Product deleted successfully.']);
   }

   public function changeServiceStatus($id) {
        //Service active or notactive
        $row = Services::find($id);
        $row->status=!$row->status;

        if($row->save()) {
            return redirect()->route('services');
        } else {
            return redirect()->route('statuschange');
        }
    }
}