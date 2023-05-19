<?php

namespace RCare\Org\OrgPackages\Responsibility\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Responsibility\src\Http\Requests\ResponsibilityAddRequest;
use Illuminate\Http\Request; 
use RCare\Org\OrgPackages\Responsibility\src\Models\Responsibility; 
use DataTables;
use Session; 

class ResponsibilityController extends Controller {
    public function populateResponsibilityData($id){
    $id = sanitizeVariable($id); 
    $data = (Responsibility::self($id) ? Responsibility::self($id)->population() : "");
    $result['main_edit_responsibility_form'] = $data;
    return $result; 
    }

    public function saveResponsibility(ResponsibilityAddRequest $request){
        $responsibility = sanitizeVariable($request->responsibility); 
        $module_id = sanitizeVariable($request->module_id); 
        $component_id = sanitizeVariable($request->component_id); 
        $id = sanitizeVariable($request->id); 
        $data = array(
            'responsibility' => $responsibility,
            'module_id'  => $module_id, 
            'component_id'    => $component_id,
            'status'      => 1,
        ); 
        $dataExist  = Responsibility::where('id', $id)->exists();
        if ($dataExist == true) { 
            $data['updated_by']= session()->get('userid'); 
            $update_query = Responsibility::where('id',$id)->orderBy('id', 'desc')->first()->update($data);
        } else { 
            $data['created_by']= session()->get('userid'); 
            $data['updated_by']= session()->get('userid'); 
            $insert_query = Responsibility::create($data);
        }
    }
    public function responsibilityList(Request $request) {
        if ($request->ajax()) {
            $data = Responsibility::with('users')->latest()->get();
            return Datatables::of($data) 
            ->addIndexColumn()
            ->addColumn('action', function($row){ 
                $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editResponsibility" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                if($row->status == 1){
                  $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_responsibilitystatus_active" id="active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                  }
                  else
                  {
                    $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_responsibilitystatus_deactive" id="deactive"><i class="i-Closee i-Close"  title="Deactive"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Responsibility::responsibility-list');
    } 
    public function deleteResponsibility(Request $request)
    {
        $id = sanitizeVariable($request->id); 
        $data = Responsibility::where('id',$id)->get();
        $status =$data[0]->status; 
        if($status==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = Responsibility::where('id',$id)->orderBy('id', 'desc')->update($status);
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = Responsibility::where('id',$id)->orderBy('id', 'desc')->update($status);
        }
        
        // Diagnosis::where('id', $id)->delete();
    }

} 