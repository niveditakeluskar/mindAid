<?php

namespace RCare\Org\OrgPackages\Labs\src\Http\Controllers;

use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Labs\src\Http\Requests\LabsAddRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\Labs\src\Models\Labs;
use RCare\Org\OrgPackages\Labs\src\Models\LabsParam;
use DataTables;
use Session;
use Illuminate\Support\Facades\Log;

class LabsController extends Controller
{
  public function index()
  {
    return view('Labs::labs-list');
  }

  public function addLabs()
  {
    //return view('Labs::labs-add');
  }

  public function populateLabsData($patientId)
  {
    $patientId = sanitizeVariable($patientId);
    $patient = (Labs::self($patientId) ? Labs::self($patientId)->population() : "");
    $paramdata = LabsParam::where('lab_test_id', $patientId)->where('status', 1)->get()->toArray();

    if ($paramdata) {
      $labparamdata = array('paramdata' => $paramdata);
      $patient['static'] = array_merge($patient['static'], $labparamdata);
    }
    $result['main_edit_labs_form'] = $patient;
    // dd($result);
    return $result;
  }

  public function editLabs($id)
  {
    $id = sanitizeVariable($id);
    $patient = Labs::where('id', $id)->get();

    return view('Labs::labs-edit', ['patient' => $patient]);
  }

  public function saveLabs(LabsAddRequest $request)
  {
    $description = sanitizeVariable($request->lab);

    $id = sanitizeVariable($request->id);
    $data = array(
      'description' => $description,
    );

    $dataExist  = Labs::where('id', $id)->exists();
    $parameter = sanitizeVariable($request->parameters);

    if ($dataExist == true) {
      $data['updated_by'] = session()->get('userid');
      $update_query = Labs::where('id', $id)->update($data);

      $paramdata_exist = LabsParam::where('lab_test_id', $id)->exists();
      if ($paramdata_exist == true) {
        $updatestatusdeactive = array(
          "status" => 0
        );
        //$delparamdata=LabsParam::where('lab_test_id',$id)->delete();
        $delparamdata = LabsParam::where('lab_test_id', $id)->update($updatestatusdeactive);
      }
      for ($i = 0; $i < count($parameter); $i++) {
        $paramlabdata = array(
          'lab_test_id' => $id,
          'parameter' => $parameter[$i]
        );

        $paramlabdata['updated_by'] = session()->get('userid');
        $paramlabdata['created_by'] = session()->get('userid');
        $param_query = LabsParam::create($paramlabdata);
      }
    } else {
      $parameter = sanitizeVariable($request->addparameters);
      $data['updated_by'] = session()->get('userid');
      $data['created_by'] = session()->get('userid');

      $insert_query = Labs::create($data);
      $lastinsertedid = $insert_query->id;

      for ($i = 0; $i < count($parameter); $i++) {
        $paramlabdata = array(
          'lab_test_id' => $lastinsertedid,
          'parameter' => $parameter[$i]
        );
        $paramlabdata['updated_by'] = session()->get('userid');
        $paramlabdata['created_by'] = session()->get('userid');

        $param_query = LabsParam::create($paramlabdata);
      }
    }
  }

  public function existLab(Request $request)
  {
    $desc = sanitizeVariable($request->lab);
    $labExist  = Labs::where('description', $desc)->exists();
    if ($labExist == true) {
      return "yes";
    }

    // dd($desc); 
  }

  public function changeLabStatus(Request $request)
  {
    $id = sanitizeVariable($request->id);
    $data = Labs::where('id', $id)->get();
    $status = $data[0]->status;
    if ($status == 1) {
      $status = array('status' => 0, 'updated_by' => session()->get('userid'));
      $update_query = Labs::where('id', $id)->orderBy('id', 'desc')->update($status);
      return view('Labs::labs-list');
    } else {
      $status = array('status' => 1, 'updated_by' => session()->get('userid'));
      $update_query = Labs::where('id', $id)->orderBy('id', 'desc')->update($status);
      return view('Labs::labs-list');
    }
  }


  public function LabsList(Request $request)
  {
    if ($request->ajax()) {
      $data = Labs::with('users')->latest()->get();
      return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
          $btn =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit editLab" title="Edit"><i class=" editform i-Pen-4"></i></a>';
          if ($row->status == 1) {
            $btn = $btn . '<a href="javascript:void(0)" data-id="' . $row->id . '" class="change_lab_status_active" id="active"><i class="i-Yess i-Yes" title="Active"></i></a>';
          } else {
            $btn = $btn . '<a href="javascript:void(0)" data-id="' . $row->id . '" class="change_lab_status_deactive" id="deactive"><i class="i-Closee i-Close"  title="Deactive"></i></a>';
          }
          return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
    return view('Labs::labs-list');
  }

  // public function deleteLab(Request $request)
  // {
  //     $id = $request->id;
  //     $data = Labs::where('id',$id)->get();
  //     $status =$data[0]->status;
  //     if($status==1){
  //       $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
  //       $update_query = Labs::where('id',$id)->orderBy('id', 'desc')->update($status);
  //     }else{
  //       $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
  //       $update_query = Labs::where('id',$id)->orderBy('id', 'desc')->update($status);
  //     }

  // }

  public function activeLabs()
  {
    $labs = Labs::activeLabs();
    return $labs;
  }
}
