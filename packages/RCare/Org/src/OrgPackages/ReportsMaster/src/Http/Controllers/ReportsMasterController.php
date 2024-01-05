<?php
namespace RCare\Org\OrgPackages\ReportsMaster\src\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\ReportsMaster\src\Http\Requests\ReportsMasterRequest;
use Illuminate\Http\Request;
use RCare\Org\OrgPackages\ReportsMaster\src\Models\ReportsMaster;
use DataTables;
use Hash;
use Redirect,Response;
use Session;
use File,DB;
use Illuminate\Validation\Rule;
use Validator;

class ReportsMasterController extends Controller {
   
    public function addReportsMaster(ReportsMasterRequest $request) { //ReportsMasterRequest
       
        $display_report_name = sanitizeVariable($request->display_name);
        $management_status=sanitizeVariable($request->management_status);
        $id = sanitizeVariable($request->id);
        $created_by  = session()->get('userid');

        $reportname = str_replace(" ","",$display_report_name);


        $data =array(
                    'report_name' => $reportname,
                    'display_name' =>$display_report_name, 
                    // 'status' =>$status,
                    'management_status'=>$management_status
                );
        $existtask=ReportsMaster::where('id',$id)->exists();
        if($existtask==true){
            $data['updated_by']= $created_by;
            $update_task = ReportsMaster::where('id',$id)->orderBy('id', 'asc')->update($data);
        }else{
            $data['created_by']= $created_by;
            $data['updated_by']= $created_by;
            $insert_task = ReportsMaster::create($data);
        }
    } 
       

    //showing list
    public function ReportsMasterList(Request $request) {
        if ($request->ajax()) {
        $data = ReportsMaster::with('users')->latest()->get();
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($row){
        $btn =    '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
        if($row->status == 1){           
            $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_Report_active" data-toggle="tooltip" data-original-title="Deactive" title="Deactive"><i class="i-Yess i-Yes" style="color: green;"></i></a>';
        } else {         
            $btn = $btn. '<a href="javascript:void(0)" data-id="'.$row->id.'" class="change_Report_deactive" data-toggle="tooltip" data-original-title="Active" title="Active"><i class="i-Closee i-Close"  style="color: red;"></i></a>';
        }
        return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
        }
    }

    public function populateReportsMaster(Request $request) {   
        $id = sanitizeVariable($request->id);
        $reportsMaster = (ReportsMaster::self($id) ? ReportsMaster::self($id)->population() : "");
        $result['ReportsMaster_form'] = $reportsMaster;    
        return $result;
    }

   
    public function updateReportsMaster(ReportsMasterRequest $request) {
      
    }   

    //User active or Inactive
    public function changeReportsMasterStatus(Request $request) {
        $id = sanitizeVariable($request->id);
        $data = ReportsMaster::where('id',$id)->get();

        $status_val =$data[0]->status;
       
        if($status_val==1){
          $status =array('status'=>0, 'updated_by' =>session()->get('userid'));
          $update_query = ReportsMaster::where('id',$id)->orderBy('id', 'desc')->update($status);
        //   return "Deactive";
        }else{
          $status =array('status'=>1, 'updated_by' =>session()->get('userid'));
          $update_query = ReportsMaster::where('id',$id)->orderBy('id', 'desc')->update($status);
        //   return "Active";
        }
    }     
} 