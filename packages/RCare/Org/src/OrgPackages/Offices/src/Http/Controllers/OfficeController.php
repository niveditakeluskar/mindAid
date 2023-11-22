<?php

namespace RCare\Org\OrgPackages\Offices\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Offices\src\Http\Requests\OfficeAddRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\Offices\src\Models\Office; 
use DataTables;
use Session; 

class OfficeController extends Controller {
    public function populateOfficeData($id){
    $id = sanitizeVariable($id); 
    $data = (Office::self($id) ? Office::self($id)->population() : "");
    $result['main_edit_office_form'] = $data;
    return $result;   
    }

    public function saveOffice(OfficeAddRequest $request){
        $location = sanitizeVariable($request->location); 
        $address = sanitizeVariable($request->address); 
        $phone = sanitizeVariable($request->phone); 
        $id = sanitizeVariable($request->id); 
        $data = array(
            'location' => $location,
            'address'  => $address,
            'phone'    => $phone,
            'status'      => 1,
        ); 
        $dataExist  = Office::where('id', $id)->exists();
        if ($dataExist == true) { 
            $data['updated_by']= session()->get('userid'); 
            $update_query = Office::where('id',$id)->orderBy('id', 'desc')->first()->update($data);
        } else { 
            $data['created_by']= session()->get('userid'); 
            $data['updated_by']= session()->get('userid'); 
            $insert_query = Office::create($data);
        }
    }
    public function officeList(Request $request) {
        if ($request->ajax()) {
            $data = Office::with('rcareOrgs','users')->latest()->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){ 
                $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editOffice" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                if($row->status == 1){
                  $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_officestatus_active" id="active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                  }
                  else
                  {
                    $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_officestatus_deactive" id="deactive"><i class="i-Closee i-Close"  title="Deactive"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Offices::office-list');
    }
    public function deleteOffice(Request $request)
    {
        $id = sanitizeVariable($request->id);
        $data = Office::where('id',$id)->get();
        $status =$data[0]->status;
        if($status==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = Office::where('id',$id)->orderBy('id', 'desc')->update($status);
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = Office::where('id',$id)->orderBy('id', 'desc')->update($status);
        }
        
        // Diagnosis::where('id', $id)->delete();
    }

} 