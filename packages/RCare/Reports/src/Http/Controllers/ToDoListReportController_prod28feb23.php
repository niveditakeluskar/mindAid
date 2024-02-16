<?php

namespace RCare\Reports\Http\Controllers; 
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RCare\TaskManagement\Models\UserPatients;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use RCare\TaskManagement\Models\ToDoList;
use RCare\Org\OrgPackages\Practices\src\Models\Practices; 
use RCare\Org\OrgPackages\Providers\src\Models\Providers;
//use RCare\Reports\Http\Requests\MonthlyBilllingReportPatientsSearchRequest;
use RCare\System\Traits\DatesTimezoneConversion; 
use DataTables;
use Carbon\Carbon; 
use Session; 
    
class ToDoListReportController extends Controller {

    public function todolistReport(Request $request){ 

        return view('Reports::to-do-list-report.to-do-list-report');
    }

  public function ToDoListReportSearch(Request $request){ 
        $practices = sanitizeVariable($request->route('practice'));
       // $provider = sanitizeVariable($request->route('provider'));
        $patient = sanitizeVariable($request->route('patient'));
        $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus')); 
        $login_user = Session::get('userid');
        $configTZ   = config('app.timezone');
        $userTZ     = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
       

        $cid = session()->get('userid');
        $userid = $cid;
        $usersdetails = Users::where('id',$cid)->get();
        $roleid = $usersdetails[0]->role;

       // dd("ash".",".$practices.",".$patient_id.",".$module_id.",".$login_user.",".$configTZ.",".$userTZ);
        $pt;
        $p;
       

        if( $practices!='null') 
        {
           if( $practices==0)
           {
             $p = 0;  
           }
           else{
             $p = $practices; 
           } 
  
       }
       else
       {
         $p = 'null';
       }
                   
       if( $patient!='null')
        {
           if( $patient==0)
           {
             $pt = 0;  
           }
           else{
             $pt = $patient;
           } 
  
       }
       else
       {
         $pt = 'null';
       }

        if($activedeactivestatus=="null"){  
        $status="null";
        }
        else{
           $status=$activedeactivestatus;  
        }
        //dd($status);
          $query = "select pid,tmid, pfname, plname, pmname,pstatus,pracpracticename,prprovidername, tmtask,status_flag,enrolled_service_id,tmtaskstatus, tmtaskcategory,task_date,tmtasktime, createdbyfname, createdbylname,to_char(task_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH12:MI:SS') as tt from patients.task_management_to_do_list($p,$pt,$status,$roleid, $userid );";
         
    //dd($query);
        $data = DB::select( DB::raw($query) );
        //dd($data);
            return Datatables::of($data) 
            ->addIndexColumn()
            ->addColumn('action', function($row){ 
                $btn='';
                $cm=''; 
                //dd($row->status_flag);

                if('0' == $row->status_flag){ $select0 = 'selected';}else{ $select0 = '';}  //dd($select);
                if('1' == $row->status_flag){ $select1 = 'selected';}else{ $select1 = '';}  //dd($select);
                if('2' == $row->status_flag){ $select2 = 'selected';}else{ $select2 = '';}  //dd($select);
                if('3' == $row->status_flag){ $select3 = 'selected';}else{ $select3 = '';}  //dd($select);
                //dd($select);
                $cm = "<input type='hidden' id= 'idtodoliststatus' name='todoliststatus' value='".$row->tmid."'><select  onchange='todoliststatus(".$row->tmid.", this.value)'>
                        <option value='0' ". $select0 .">Pending</option>
                        <option value='1' ". $select1 .">Completed</option>
                        <option value='2' ". $select2 .">Unmarked Completed</option>
                        <option value='3' ". $select3 .">Suspended</option>";

                
                $cm .= "</select>";
                $btn .= $cm;       
                return $btn;

            })
           ->rawColumns(['action'])     
           ->make(true);       
    }



    public function changeStatusToDoList(Request $request){  
        //dd("working");
        $id = sanitizeVariable($request->id);
        //dd($id);
        $status_flag = sanitizeVariable($request->status_flag);
       // dd($id , $status_flag);
        switch ($status_flag) {
            case '0':
                $status = "Pending";
                break;
            case '1':
                $status = "Completed";
                break;
            case '2':
                $status = "Unmarked Completed";
                break;
            case '3':
                $status = "Suspended";
                break;
            default: 
                $status = "Pending"; 
            break;
        }
        

        //dd($status_flag);
        $data = array( 
          //'id' => sanitizeVariable($request->id),
          'status' => $status,
          'status_flag' => $status_flag,
          'created_by' => session()->get('userid'),
          'updated_by' => session()->get('userid')
        );
        //dd($data);
        if(ToDoList::where('id',$id)->exists()){
          if($status_flag == "1"){
            $update_data = array(
                'status_flag' => $status_flag,
                'status' => $status,
                'task_completed_at' =>Carbon::now()
            );
          } else {
            $update_data = array(
                'status_flag' => $status_flag,
                'status' => $status
            );
          }
          if($status_flag == '' ||  $status_flag == 'select status'){
                $updatetodo = ToDoList::where('id',$id)->update($update_data);
          }else{
                $updatetodo = ToDoList::where('id',$id)->update($update_data);
                //return redirect()->with('message', 'The success message!');
               //$updatetodo = ToDoList::create($data);
          } 
         // dd($updatetodo);        
        }
        else
        {
           ToDoList::create($data);
        }  
    }
}