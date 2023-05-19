<?php

namespace RCare\Org\OrgPackages\StageCodes\src\Http\Controllers;
use RCare\Org\OrgPackages\StageCodes\src\Models\StageCode;
use RCare\Org\OrgPackages\Stages\src\Models\Stage;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RCare\Org\OrgPackages\StageCodes\src\Http\Requests\StageCodeSaveRequest;
use Illuminate\Support\Facades\Log;
use DataTables;
use Validator;
use Session;

class StageCodesController extends Controller 
{
    public function createStageCode(StageCodeSaveRequest $request)
    {
        // Log::info($request);
        $data = array(
                        'stage_id'    => sanitizeVariable($request->stages),
                        'module_id'   => sanitizeVariable($request->module),
                        'submodule_id'=> sanitizeVariable($request->sub_module),
                        'description' => sanitizeVariable($request->description),
                        'sequence'    => sanitizeVariable($request->sequence),
                        'status'      => 1,
                        'updated_by'=> session()->get('userid'),
                        'created_by'=> session()->get('userid')
                    );
        $stageCode = StageCode::create($data);
        // Session::put('success','Step created successfully!'); 
        // return redirect()->back();
    }

    public function fetchStageCode(Request $request)
    {
		if ($request->ajax()) {
            $data = StageCode::with('stage','module','components','users')->get()->sortByDesc('updated_at');
			return Datatables::of($data)
					->addIndexColumn()
					->addColumn('action', function($row){
					   $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" data-original-title="Edit"  class="editStageCode" title="Edit"><i class=" editform i-Pen-4"></i></a>';
                       
					   if($row->status == 1){
					    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="deleteStageCode" title="Delete"><i class="i-Yess i-Yes" title="Active"></i></a>';
                       }else{
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="deleteStageCode" title="Delete"><i class="i-Closee i-Close" title="Inactive"></i></a>';
                       }
                       return $btn;
					})
					->rawColumns(['action'])
					->make(true);
		}  
        return view('StageCodes::stage-codes');
    }
    
    public function editStageCode($id)
    {
        $id= sanitizeVariable($id);
        $stageCode = StageCode::with('stage')->where("id",$id)->orderBy('description','asc')->get();
        return response()->json($stageCode);
    }

    public function updateStageCode(StageCodeSaveRequest $request)
    {
        // Log::info("--update--");
        // Log::info($request);

        $id= sanitizeVariable($request->stage_code_id);
        $data = array(
                        'stage_id'    => sanitizeVariable($request->stages),
                        'module_id'   => sanitizeVariable($request->module),
                        'submodule_id'=> sanitizeVariable($request->sub_module),
                        'description' => sanitizeVariable($request->description),
                        'sequence'    => sanitizeVariable($request->sequence),
                        'status'      => 1,
                        'updated_by'=> session()->get('userid'),
                    );
        $updateStageCode = StageCode::where('id',$id)->update($data);
        if($updateStageCode) {
            Session::put('success','Step updated successfully!'); 
        } else {
            Session::put('danger','Failed to update step!'); 
        }
        return redirect()->back();
    }

    public function deleteStageCode(Request $request)
    {
        // Log::info($request);
         $id= sanitizeVariable($request->stageCode_id);
         $data   = StageCode::where('id',$id)->get();
         $status = $data[0]->status;
        // $update = ['updated_by'=> session()->get('userid')];

         if($status==1){
            $status       = array('status'=>0, 'updated_by' =>session()->get('userid'));
            $update_query = StageCode::where('id',$id)->orderBy('id', 'desc')->update($status);
            return response()->json(['success'=>'Step Disabled successfully.']);
        } else {
            $status       = array('status'=>1, 'updated_by' =>session()->get('userid'));
            $update_query = StageCode::where('id',$id)->orderBy('id', 'desc')->update($status);  
            return response()->json(['success'=>'Step Enable successfully.']);
        }
         //StageCode::where('id',$id)->delete($update);
        
        return redirect()->back();
    }

    public function stageCodes($id)
	{
        $id= sanitizeVariable($id);
        $stageCodes = [];
        //$stageCodes = StageCode::all()->where("stage_id", $id);//->where("status", 1);

        $stageCodes = StageCode::where("stage_id", $id)->orderBy('description','asc')->get();//->where("status", 1);
        return response()->json($stageCodes);
	}

    public function stages($id){
        $id= sanitizeVariable($id);
        $stageCodes = [];
        //$stageCodes = Stage::all()->where("submodule_id", $id);//->where("status", 1);
        $stageCodes = Stage::where("submodule_id", $id)->orderBy('description','asc')->get();//->where("status", 1);
        return response()->json($stageCodes);
    }
}   