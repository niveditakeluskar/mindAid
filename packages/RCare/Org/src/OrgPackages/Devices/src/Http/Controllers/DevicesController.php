<?php

namespace RCare\Org\OrgPackages\Devices\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Devices\src\Http\Requests\DevicesAddRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\Devices\src\Models\Devices;
use DataTables;
use Session; 

class DevicesController extends Controller {
    public function index() {
       return view('Devices::devices-list');
    }

    public function addDevices()  
    {
       return view('Devices::devices-add');
    }

    public function populateDevicesData($patientId){
    $patientId = sanitizeVariable($patientId);
    $patient = (Devices::self($patientId) ? Devices::self($patientId)->population() : "");
    $result['main_edit_devices_form'] = $patient;
    return $result;
    }

    public function editDevices($id)  
    {   $id = sanitizeVariable($id);
        $patient = Devices::where('id',$id)->get();
       return view('Devices::devices-edit',['patient'=>$patient]);
    }

    public function saveDevices(DevicesAddRequest $request){
        $device_name = sanitizeVariable($request->device_name); 
        $id = sanitizeVariable($request->id); 
        $data = array(
            'device_name' => $device_name,
            'status'      => 1,
        ); 
        $dataExist  = Devices::where('id', $id)->exists();
        if ($dataExist == true) {
            $data['updated_by']= session()->get('userid');
            $update_query = Devices::where('id',$id)->orderBy('id', 'desc')->first()->update($data);
        } else {
            $data['created_by']= session()->get('userid');
            $insert_query = Devices::create($data);
        }
      
    }
    public function DevicesList(Request $request) {
        if ($request->ajax()) {
            $data = Devices::with('users')->latest()->get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editDevice" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
                if($row->status == 1){
                  $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_status_active" id="active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                  }
                  else
                  {
                    $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_status_deactive" id="deactive"><i class="i-Closee i-Close"  title="Deactive"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('Devices::devices-list');
    }
    public function deleteDevices(Request $request)
    {
        $id = sanitizeVariable($request->id);
        $data = Devices::where('id',$id)->get();
        $status =$data[0]->status;
        if($status==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = Devices::where('id',$id)->orderBy('id', 'desc')->update($status);
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = Devices::where('id',$id)->orderBy('id', 'desc')->update($status);
        }
        
        // Diagnosis::where('id', $id)->delete();
    }

} 