<?php 
namespace RCare\Reports\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\PatientBilling;
use RCare\TaskManagement\Models\ToDoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RCare\TaskManagement\Models\UserPatients;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use DataTables;
use Carbon\Carbon;
use Session;
// use RCare\Org\OrgPackages\Users\src\Models\RenUserLoginHistory;
use RCare\System\Traits\DatesTimezoneConversion; 

class TaskStatusReportController extends Controller  
{ 
    // public function LoginLogsReport(Request $request)
    // {
        
    //       return view('Reports::task-status-report.task-status-list');
    // }

    public function TaskStatusReportSearch(Request $request)  
    {  

        // dd($request->all());
        $caremanagerid =  sanitizeVariable($request->route('caremanagerid'));
        $practicesgrp = sanitizeVariable($request->route('practicesgrp')); 
        // dd($practicesgrp);
        $practiceid =  sanitizeVariable($request->route('practiceid'));
        $patient = sanitizeVariable($request->route('patient'));
        $taskstatus = sanitizeVariable($request->route('taskstatus'));
        $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus'));
        $score = sanitizeVariable($request->route('score'));
        $fromdate  =sanitizeVariable($request->route('fromdate'));
        $enddate  =sanitizeVariable($request->route('todate'));
        $timeoption = sanitizeVariable($request->route('timeoption'));
        $time = sanitizeVariable($request->route('time'));

        $monthly = Carbon::now();
        $monthlyto = Carbon::now();
        $year = date('Y', strtotime($monthly));
        $month = date('m', strtotime($monthly));

        $configTZ     = config('app.timezone');
        $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');

        $cid = session()->get('userid');
        $userid = $cid;
        $usersdetails = Users::where('id',$cid)->get();
        $roleid = $usersdetails[0]->role;

        if($fromdate=='null' || $fromdate=='')
         {
          $date=date("Y-m-d");   
              

          $fromdate =$date." "."00:00:00";    
          $todate = $date ." "."23:59:59"; 
          $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate); 
          $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate); 

         }
         else{
        
       
          $fromdate =$fromdate." "."00:00:00";   
          $enddate = $enddate ." "."23:59:59"; 
         
          $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);     
          $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $enddate);               
         }   
        


        if( $practicesgrp!='null')
        {
          if( $practicesgrp==0)
          {
            $pracgrp = 0;  
          }
          else{
            $pracgrp = $practicesgrp; 
          }           
       }
       else
       {
         $pracgrp = 'null';
       }

        if( $practiceid!='null') 
        {
           if( $practiceid==0)
           {
             $p = 0;  
           }
           else{
             $p = $practiceid; 
           } 
  
       }
       else
       {
         $p = 'null';
       }


       if( $caremanagerid!='null')
        {
           if( $caremanagerid==0)
           {
             $c = 0;  
           }
           else{
             $c = $caremanagerid;
           } 
  
       }
       else
       {
         $c = 'null';
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

       if( $taskstatus!='null')
       {
          if( $taskstatus==0)
          {
            $ts = 0;  
          }
          else{
            $ts = $taskstatus;
          } 
 
      }
      else
      {
        $ts = 'null';
      }


      if($activedeactivestatus=="null"){  
        $status="null";
    }
    else{
       $status=$activedeactivestatus;  
    } 

    if( $score!='null')
    {
       if( $score==0)
       {
         $sc = 0;  
       }
       else{
         $sc = $score;
       } 

    }
    else
    {
      $sc = 'null';
    }

    if($time=='null' || $time==''){
      $timeoption="1";
      $totime = '00:20:00';     
    } else {
        $totime = $time;
    } 

    if($time!="null" && $time!="00:00:00"){ 
        $totime = $time;
    }

    if($timeoption=="3" && $time=="00:00:00"){
        $timeoption="5";
    } 

    if($timeoption=="2" && $time=='00:00:00'){
        $timeoption = "6"; 
    }  
     
            // $query = "select tm.task_notes as task,tm.status as taskstatus,tm.select_task_category as taskcategory, 
            //           ft.task as followuptaskcategory,
            //           tm.task_date as taskdate, tm.task_completed_at as taskcompletiondate, tm.notes as tasknotes,  
            //           p.fname, p.lname,p.email,p,dob,
            //           u.f_name as caremanagerfname,u.l_name as caremanagerlname,		
            //           m.module as modulename,mc.components as componentsname,
            //           rs.description as stagename, rsc.description as stepname,
            //           pr.name as providername, prac.name as practicename
            //           from task_management.to_do_list tm 
            //           inner join patients.patient p on tm.patient_id = p.id
            //           inner join ren_core.followup_tasks ft on ft.id = tm.select_task_category
            //           left join ren_core.modules m on m.id=tm.module_id
            //           left join ren_core.module_components mc on mc.id=tm.module_id
            //           left join ren_core.stage rs on rs.id = tm.stage_id
            //           left join ren_core.stage_codes rsc on rsc.id = tm.step_id
            //           left join patients.patient_providers pp on pp.patient_id =p.id
            //           left join ren_core.providers pr on pp.provider_id=pr.id 
            //           left join ren_core.practices prac on pp.practice_id = prac.id
            //           inner join patients.patient_services ps on p.id=ps.patient_id
            //           left join task_management.user_patients as up on up.patient_id = p.id and up.status = 1
            //           left join ren_core.users as u on u.id = up.user_id";

        // $query = "select pid, pfname, plname, pmname, pprofileimg, pdob,
        //         pstatus, pracpracticename,prprovidername, tmtask, tmtaskstatus, tmtaskcategory,
        //         fufollowuptaskcategory,tmtaskdate, tmtaskcompletiondate, tmtasknotes, 
        //         mmodulename, mccomponentsname, rsstagename, rscstepname, caremanagerfname, caremanagerlname, createdbyfname, createdbylname
        //         from patients.task_status_report( $c,$p,$pt,$ts,$status,$pracgrp,timestamp '".$dt1."',timestamp '".$dt2."',$roleid, $userid )";
               
        $query = "select pid, pfname, plname, pmname, pprofileimg, pdob,
        pstatus, pracpracticename,ppracticeid,prprovidername, tmtaskid,tmtask, tmtaskstatus, tmtaskstatusflag,tmtaskcategory,
        fufollowuptaskcategory,
        to_char(tmtaskdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as tmtaskdate, 
        tmtaskcompletiondate, tmtasknotes, 
        mmodulename, mccomponentsname, rsstagename, rscstepname, caremanageruserid, caremanagerfname, caremanagerlname, 
        createdbyfname, createdbylname,createdbyuserid,reassignfname,reassignlname,reassignuserid,
        tmscore,ptrtotaltime
        from patients.task_status_report($c,$p,$pt,$ts,$status,$pracgrp,timestamp '".$dt1."',timestamp '".$dt2."',$roleid, $userid,$sc,$timeoption,'".$totime."',$month, $year)";
       
       
// dd($query);                        

    $data = DB::select($query); 
    // dd($data);  

    
    return Datatables::of($data)     
    ->addIndexColumn()  
    ->addColumn('action', function($row){  

      $cid = session()->get('userid');
      $userid = $cid;
      $usersdetails = Users::where('id',$cid)->get();
      $roleid = $usersdetails[0]->role;

      if($row->ppracticeid == '' && $roleid != '4')
      {
        $careManager =   DB::table('ren_core.users')
                        ->where('role', '=', 5)
                        // ->orWhere('role', '=', 8)
                        ->get();

     }else if($row->ppracticeid != '' && $roleid != '4')
     {
        $careManager =   DB::table('ren_core.users')
                        ->where('role', '=', 5)
                        // ->orWhere('role', '=', 8)
                        // ->whereIn('id', DB::table('ren_core.user_practices')->where('practice_id', $row->ppracticeid)->pluck('user_id'))
                        ->get();

     }else if($row->ppracticeid == '' && $roleid == '4')
     {
      
      $careManager =   DB::table('ren_core.users')->where('report_to',$userid)->where('role', '=', 5)->get();
     
    }else
     {

      $careManager =   DB::table('ren_core.users')
                        ->where('role', '=', 5)
                        ->where('report_to',$userid)
                        // ->orWhere('role', '=', 8)
                        // ->whereIn('id', DB::table('ren_core.user_practices')->where('practice_id', $row->ppracticeid)->pluck('user_id'))
                        ->get();
     }
        $btn='';
        $cm='';
        // if($row->practicestatus=='1')
        // {
        $cm = "<input type='hidden' name='patient[]' value='".$row->pid."'><select  onchange='reassignPatientandTask(".$row->pid.",".$row->tmtaskid.",this.value)'><option>select CM</option>
                <option value=''>None</option>";
        foreach($careManager as $key=>$value){ 
            if($value->id == $row->reassignuserid){ $select = 'selected';}else{ $select = '';}         
            $cm .= "<option value='".$value->id."' ".$select.">".$value->f_name.' '.$value->l_name."</option>";
        }
        $cm .= "</select>";
      // }

        $btn .= $cm;              
        return $btn;
    }) 

      ->addColumn('action2', function($row){ 
      $btn='';
      $st=''; 
      //dd($row->status_flag);

      if('0' == $row->tmtaskstatusflag ){ $select0 = 'selected';}else{ $select0 = '';}  //dd($select);
      if('1' == $row->tmtaskstatusflag ){ $select1 = 'selected';}else{ $select1 = '';}  //dd($select);
      if('2' == $row->tmtaskstatusflag ){ $select2 = 'selected';}else{ $select2 = '';}  //dd($select);
      if('3' == $row->tmtaskstatusflag ){ $select3 = 'selected';}else{ $select3 = '';}  //dd($select);
      if('4' == $row->tmtaskstatusflag ){ $select4 = 'selected';}else{ $select3 = '';} 
      //dd($select);
      $st = "<input type='hidden' id= 'idtodoliststatus' name='todoliststatus' value='".$row->tmtaskid."'><select  onchange='todoliststatus(".$row->tmtaskid.", this.value)'>
              <option value='0' ". $select0 .">Pending</option>
              <option value='1' ". $select1 .">Completed</option>
              <option value='2' ". $select2 .">Unmarked Completed</option>
              <option value='3' ". $select3 .">Suspended</option>
              <option value='4' ". $select3 .">Rescheduled</option>";

      
      $st .= "</select>";
      $btn .= $st;       
      return $btn;

  }) 
   
   ->rawColumns(['action','action2'])   
   ->make(true);   

       
    }


    public function reassign(Request $request)
    {

      $user = sanitizeVariable($request->user);
      $patient_id = sanitizeVariable($request->patient);
      $taskid = sanitizeVariable($request->taskid);
      $update_data = array( 
          'assigned_to' => $user,
          'patient_id' => $patient_id,
          'created_by' => session()->get('userid'),
          'updated_by' => session()->get('userid')
          
      );

      
          // if($user != '' ||  $user == 'null'){
              
              // UserPatients::where('patient_id',$patient_id)->where('id',$taskid)->update($update_data);
              ToDoList::where('id',$taskid)->where('patient_id',$patient_id)->update($update_data);

          // }             
      
    }

    public function changeStatus(Request $request)
    {  
        //dd("working");
        $id = sanitizeVariable($request->taskid);
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
        // else
        // {
        //    ToDoList::create($data);
        // }  
    }

    

}