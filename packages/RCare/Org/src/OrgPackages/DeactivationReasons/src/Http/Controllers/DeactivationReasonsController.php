<?php

namespace RCare\Org\OrgPackages\DeactivationReasons\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\DeactivationReasons\src\Http\Requests\DeactivationReasonsAddRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\DeactivationReasons\src\Models\DeactivationReasons;
// use RCare\Org\OrgPackages\Labs\src\Models\LabsParam;
use DataTables;
use Session; 
use Illuminate\Support\Facades\Log;  

class DeactivationReasonsController extends Controller {

    public function AddDeactivationReasons(DeactivationReasonsAddRequest $request)  
    { 
    	$reasons = sanitizeVariable($request->reasons);
        $id = sanitizeVariable($request->id);
    	$created_by  = session()->get('userid');
		$data = array(
                    'reasons' => $reasons
                );
        $existreasons=DeactivationReasons::where('id',$id)->exists();
        if($existreasons==true){
            $data['updated_by']= $created_by;
            $update_task = DeactivationReasons::where('id',$id)->orderBy('id', 'asc')->update($data);
        }else{
            $data['created_by']= $created_by;
            $data['updated_by']= $created_by;
            $insert_reasons = DeactivationReasons::create($data);
        }
    }

    public function populateDeactivationReasons(Request $request) {   
        $id = sanitizeVariable($request->id);
        $deactivationReasons = (DeactivationReasons::self($id) ? DeactivationReasons::self($id)->population() : "");
        $result['deactivationReasons_form'] = $deactivationReasons;    
        return $result;
    }
 
public function deleteDeactivationReasons(Request $request)
{
    $id = sanitizeVariable($request->id); 
    $data = DeactivationReasons::where('id',$id)->get();
    $status =$data[0]->status;
    if($status==1){
      $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
      $update_query = DeactivationReasons::where('id',$id)->orderBy('id', 'asc')->update($status);
      return view('DeactivationReasons::deactivation-reasons');
    }else{
      $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
      $update_query = DeactivationReasons::where('id',$id)->orderBy('id', 'asc')->update($status);
      return view('DeactivationReasons::deactivation-reasons');
    }
}

public function getDeactivationReasonsListData(Request $request)
{
   if ($request->ajax()) {
            $data=DeactivationReasons::with('users')->get();
            return Datatables::of($data) 
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editReasons" title="Edit"><i class=" editform i-Pen-4"></i></a>';
                if($row->status == 1){
                  $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_deactivationReasons_status_active" id="active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                  }
                  else 
                  {
                    $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_deactivationReasons_status_deactive" id="deactive"><i class="i-Closee i-Close" title="Deactive"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        } 
        return view('DeactivationReasons::deactivation-reasons');
    }
    
} 