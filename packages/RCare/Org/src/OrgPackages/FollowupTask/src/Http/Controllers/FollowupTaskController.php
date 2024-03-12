<?php

namespace RCare\Org\OrgPackages\FollowupTask\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\FollowupTask\src\Http\Requests\FollowupTaskAddRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\FollowupTask\src\Models\FollowupTask;
// use RCare\Org\OrgPackages\Labs\src\Models\LabsParam;
use DataTables;
use Session; 
use Illuminate\Support\Facades\Log; 

class FollowupTaskController extends Controller {

    public function AddFollowupTask(FollowupTaskAddRequest $request)  
    {
    	$task = sanitizeVariable($request->task);
        $id = sanitizeVariable($request->id);
    	$created_by  = session()->get('userid');
		$data = array(
                    'task' => $task
                );
        $existtask=FollowupTask::where('id',$id)->exists();
        if($existtask==true){
            $data['updated_by']= $created_by;
            $update_task = FollowupTask::where('id',$id)->orderBy('id', 'asc')->update($data);
        }else{
            $data['created_by']= $created_by;
            $data['updated_by']= $created_by;
            $insert_task = FollowupTask::create($data);
        }
    }

    public function populateFollowupTask(Request $request) {   
        $id = sanitizeVariable($request->id);
        $followupTask = (FollowupTask::self($id) ? FollowupTask::self($id)->population() : "");
        $result['followuptask_form'] = $followupTask;    
        return $result;
    }

public function deleteFollowupTask(Request $request)
{
    $id = sanitizeVariable($request->id);
    $data = FollowupTask::where('id',$id)->get();
    $status =$data[0]->status;
    if($status==1){
      $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
      $update_query = FollowupTask::where('id',$id)->orderBy('id', 'asc')->update($status);
      return view('FollowupTask::followupTask');
    }else{
      $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
      $update_query = FollowupTask::where('id',$id)->orderBy('id', 'asc')->update($status);
      return view('FollowupTask::followupTask');
    }
}

public function getFollowupTaskListData(Request $request)
{
   if ($request->ajax()) {
            $data=FollowupTask::with('users')->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                // $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editroles" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editTask" title="Edit"><i class=" editform i-Pen-4"></i></a>';
                if($row->status == 1){
                  $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_followupTask_status_active" id="active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                  }
                  else 
                  {
                    $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_followupTask_status_deactive" id="deactive"><i class="i-Closee i-Close" title="Deactive"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('FollowupTask::followupTask');
    }
    
} 