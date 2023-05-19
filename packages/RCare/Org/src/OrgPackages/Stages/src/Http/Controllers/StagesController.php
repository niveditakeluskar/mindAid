<?php

namespace RCare\Org\OrgPackages\Stages\src\Http\Controllers;
use RCare\Org\OrgPackages\Stages\src\Models\Stage;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RCare\Org\OrgPackages\Stages\src\Http\Requests\StageAddRequest;
use RCare\Org\OrgPackages\Stages\src\Http\Requests\StageUpdateRequest;
use Illuminate\Support\Facades\Log;
use DataTables;
use Validator;
use Session;
use Illuminate\Support\Carbon;

class StagesController extends Controller {

    public function index() {
		$service = Module::all();
		$component = ModuleComponents::all();
        // $menus = OrgMenus::where('parent','=',0)->orderBy('sequence','asc')->get();
        return view('Stages::createStage', compact('service','component'));
        
    }
    
    public function createStage(StageAddRequest $request)
    {
        // Log::info($request);
        $data = array(
            'description'  => sanitizeVariable($request->description),
			'module_id'    => sanitizeVariable($request->service_id),
			'submodule_id' => sanitizeVariable($request->component_id),
            'status'       => 1,
            'operation'    => sanitizeVariable($request->operation),
            'created_by'   => session()->get('userid'),
            'updated_by'   => session()->get('userid')
        );
        
        $stage = Stage::create($data);
     // return "success";
      // return back()->with('success','Menu created successfully!');
        Session::put('success','Stage created successfully!'); 
        return redirect()->back();
    }

    public function fetchStage(Request $request)
    {
		if ($request->ajax()) {
            $data = Stage::with('module','components','users')->get()->sortByDesc('updated_at');
			return Datatables::of($data)
			->addIndexColumn()
			->addColumn('action', function($row){

				   $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" data-original-title="Edit"  class="editStage" title="Edit"><i class=" editform i-Pen-4"></i></a>';
                   
                   if($row->status == 1){
				    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="deleteStage" title="Delete"><i class="i-Yess i-Yes" title="Active"></i></a>';
                   }else{
                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="deleteStage" title="Delete"><i class="i-Closee i-Close" title="Inactive"></i></a>';
                   }

                   return $btn;
					})
			->rawColumns(['action'])
			->make(true);
		}  
        return view('createStage');
    }
    
    public function editStage($id)
    {
        $id= sanitizeVariable($id);
        $stage = Stage::find($id);
        return response()->json($stage);
    }

    public function updateStage(StageUpdateRequest $request)
    {
        $id= sanitizeVariable($request->stage_id);
        $update = array(
                        'description'  => sanitizeVariable($request->description),
                        'module_id'    => sanitizeVariable($request->service_id),
                        'submodule_id' => sanitizeVariable($request->component_id),
                        'status'       => 1,
                        'operation'    => sanitizeVariable($request->operation),
                        'updated_by'   => session()->get('userid')
                    );
        // $update = ['description' => $request->description, 'module_id' => $request->service_id, 'submodule_id' => $request->component_id, 'operation' => $request->operation,
        //     'updated_at'=> Carbon::now()->setTime(23,59,59)->format('Y-m-d H:i:s'),
        //     'updated_by'=>session()->get('userid')];
        Stage::where('id',$id)->update($update);
  
      // return back()->with('success','Menu updated successfully!');
    // return Redirect::to('index')
       //->with('success','Great! Product updated successfully');
        Session::put('success','Stage updated successfully!'); 
        return redirect()->back();
    }

    public function deleteStage(Request $request)
    {
        //Log::info($request);
         $id= sanitizeVariable($request->stage_id);
         $data   = Stage::where('id',$id)->get();
         $status = $data[0]->status;
         if($status==1){
            $status       = array('status'=>0, 'updated_by' =>session()->get('userid'));
            $update_query = Stage::where('id',$id)->orderBy('id', 'desc')->update($status);
            return response()->json(['success'=>'Stage Disabled successfully.']);
        } else {
            $status       = array('status'=>1, 'updated_by' =>session()->get('userid'));
            $update_query = Stage::where('id',$id)->orderBy('id', 'desc')->update($status);
            return response()->json(['success'=>'Stage Enable successfully.']);
        }
         //$update = ['updated_by'   => session()->get('userid')];//'status' => '0'
         //Stage::where('id',$id)->delete($update);
     
        
    }

    public function subModuleStages($id)
	{
        $id= sanitizeVariable($id);
        $stages = [];
        //$stages = Stage::all()->where("submodule_id", $id)->where("status", 1);
        $stages = Stage::where("submodule_id", $id)->where("status", 1)->orderBy('description','asc')->get();
        return response()->json($stages);
	}
}   