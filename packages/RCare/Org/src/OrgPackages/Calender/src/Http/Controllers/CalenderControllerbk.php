<?php
 
namespace RCare\Org\OrgPackages\Calender\src\Http\Controllers;

// use RCare\RCareAdmin\AdminPackages\Users\src\Http\Requests\UserAddRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Http\Request;
use RCare\RCareAdmin\AdminPackages\Users\src\Models\User;
use RCare\Org\OrgPackages\Calender\src\Http\Requests\calendarAddRequest;
// use RCare\RCareAdmin\AdminPackages\Users\src\Models\UserRole;
use RCare\Org\OrgPackages\Calender\src\Models\Calender;
use RCare\TaskManagement\Models\ToDoList;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
// use RCare\Org\OrgPackages\Calender\src\Models\CalenderLogHistory; 
// use RCare\Org\OrgPackages\Calender\src\Http\Requests\CalenderAddRequest;
// use RCare\Org\OrgPackages\Calender\src\Http\Requests\ReportCalenderAddRequest;
use RCare\System\Traits\DatesTimezoneConversion; 
use Illuminate\Support\Str; 
use Session;
use DB;
use File;
use DataTables; 
use Validator,Redirect,Response;
use Carbon\Carbon;

class CalenderControllerbk extends Controller {   
 
    public function index($patient_id,$module_id) {
        $patient_id = sanitizeVariable($patient_id);
        $module_id  = sanitizeVariable($module_id); 
        return view('Calender::calender',compact('patient_id','module_id'));
    }
    
    public function getDataCalender($patient_id,$module_id){ 
      $login_user = Session::get('userid');
        $configTZ   = config('app.timezone');
        $userTZ     = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
         // $patient_id = sanitizeVariable($patient_id);
        // $module_id  = sanitizeVariable($module_id);
        $patient_id = 0 ;
        $module_id  = 0 ;
        //dd(.$module_id.",".$patient_id.",".$login_user.",".$configTZ.",".$userTZ);
   
         $data = "select patient_id,
         fname, lname, id, task_time,  task_notes, notes, module_id, component_id, patient_id,
         module, components, created_at, enrolled_service_id, 
         status_flag,
         date(to_char(task_date at time zone '".$configTZ ."' at time zone '".$userTZ."','MM-DD-YYYY HH12:MI:SS')::timestamp) as taskdate, 
         date(to_char(task_date at time zone '".$configTZ ."' at time zone '".$userTZ."','MM-DD-YYYY HH12:MI:SS')::timestamp) || ' ' || task_time as tt, 
         userfname,userlname
         from patients.calender_to_do_list($patient_id,$module_id,$login_user,'".$configTZ ."','".$userTZ."')";

        //dd($data);
        $query = DB::select( DB::raw($data) );
        //dd($query);
        $url='';
        $event =array();
            foreach ($query as $row) {
            $start_date_format =  $row->tt;
            $end_date_format =  $row->tt;
                $fname1 = $row->fname;
                $lname1 = $row->lname;
                $patient_name = $fname1 . ' ' . $lname1;
                $assign_fname = $row->userfname;
                $assign_lname = $row->userlname;
                $assignby_name = $assign_fname .' ' . $assign_lname;
                $component_idd = $row->component_id;
                $module_name     = strtolower(str_replace(' ', '-', $row->module));
                $components_name = strtolower(str_replace(' ', '-', $row->components));
                $enrolled_service_id = strtolower(str_replace(' ', '-', $row->enrolled_service_id));
                $patient_data = $row->patient_id;
                //dd($components_name);
                $componentdata =  ModuleComponents::find($component_idd);
               // dd($componentdata);
                $component_data = $componentdata['components'];
                //dd($component_data);
                if($component_data =='Patient Enrollment'){
                  $components_enroll = "patient-enrollment";
                  $url = "/patients/".$components_enroll."/".$patient_data."/".$enrolled_service_id;
                } elseif($component_data == '' || $component_data == 'null') {
                  // $components_enroll = "patient-enrollment";
                  // $url = "/patients/".$components_enroll."/".$patient_data."/".$enrolled_service_id;
                }else{
                  $url = "/".$module_name."/".$components_name."/".$patient_data;
                } 
                $color = null;
                $currentTime = Carbon::now();
                $currentdate =$currentTime->toDateTimeString();

                $datadate1 = (explode(" ",$currentdate));
                //dd($datadate1[0]);
                $dateValue1 = $datadate1[0];
                //dd($dateValue1);
                $currentday = date('d',strtotime($dateValue1));
                $currentmonth = date('m',strtotime($dateValue1));
               // dd($currentmonth);
                $currentyear = date('Y',strtotime($dateValue1));
                $cmydate = $currentyear .' '. $currentmonth;
                //dd($currentmonth , $currentyear );

                $date = $row->taskdate;
                //dd($date);
                //$datadate = (explode(" ",$date));
                //dd($datadate[0]);
               // $dateValue = strtotime($datadate[0]);
                $day = date('d',strtotime($date));
                $month = date('m',strtotime($date));
                $year = date('Y',strtotime($date));
                $mydate = $year .' '.$month;
               // dd($mydate , $cmydate);
                //print_r($mydate .'++++' . $cmydate);
                if(($row->status_flag) == 1){ 
                  $color = '#008000'; //green
               }elseif ($mydate >= $cmydate) {      //"06 2022" "9 2022"       
                  $color = '#F6BE00'; //yellow
                }else{
                  $color = '#FF0000'; //red
                }
                //$button = "<button type='button' class ='btn btn-info'>Click Me!</button>";
                if(($row->task_notes) == NULL || ($row->task_notes) == ''){
                     $event[] = array(
                        'id'   => $row->id,
                        'title'   =>"No Data Found.",
                        'start'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                            ($row->tt),
                        'end'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                            ($row->tt)
                    );
                } elseif(($row->tt) == NULL || ($row->tt) == ''){ 
                      $tt = $row->taskdate . ' ' . '08:00:00' ;
                    $event[] = array(
                      'id'   => $row->id,
                      'title'   =>!empty($patient_id)? $row->task_notes:$patient_name . ' ' . '(' .$row->task_notes. ')' ,
                      'url' => $url,
                      'color' => $color,
                      'status_flag' => $row->status_flag,
                      'start'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                        ($tt),
                      'end'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                          ($tt)
                        );
                } else{
                  $event[] = array(
                      'id'   => $row->id,
                      'title'   =>!empty($patient_id)? $row->task_notes:$patient_name . ' ' . '(' .$row->task_notes. ')' ,
                      'url' => $url,
                      'color' => $color,
                      'status_flag' => $row->status_flag,
                      'start'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                        ($row->tt),
                      'end'   =>// DatesTimezoneConversion::userToConfigTimeStamp
                          ($row->tt)
                  );
                }
            }
           // dd(response()->json($event));
            return response()->json($event); 
    }
    public function addDataCalender(calendarAddRequest $request){ 
      //dd("working");
      $practicesid = sanitizeVariable($request->practice_id);
      $patientid = sanitizeVariable($request->patient);
      $modulesid = sanitizeVariable($request->modules);
      $componentid = sanitizeVariable($request->submodule_id);
      $event_name = sanitizeVariable($request->event_name);
      $event_start_date = sanitizeVariable($request->event_start_date);
      $event_end_date = sanitizeVariable($request->event_end_date);
      $event_time = sanitizeVariable($request->momenttime);

      $eventtodate = date('d',strtotime($event_start_date));
      $eventtomonth = date('m',strtotime($event_start_date));
      $eventtoyear = date('Y',strtotime($event_start_date));
      $eventdate = $eventtomonth .'-'. $eventtodate .'-'. $eventtoyear;

      $currentTime1 = Carbon::now();
      $currentdate1 =$currentTime1->toDateTimeString();
      $datadate11 = (explode(" ",$currentdate1));
      $dateValue11 = $datadate11[0];
      $todate = date('d',strtotime($dateValue11));
      $tomonth = date('m',strtotime($dateValue11));
      $toyear = date('Y',strtotime($dateValue11));
      $updatedate = $tomonth .'-'. $todate .'-'. $toyear;
    
      $todo_data = array( 
                    'uid'                         => $patientid,
                    'module_id'                   => $modulesid, 
                    'component_id'                => $componentid,
                    'stage_id'                    => '0',
                    'step_id'                     => '0',
                    'task_notes'                  => $event_name,
                    'notes'                       => null,
                    'task_time'                   => $event_time,
                    'task_date'                   => $event_start_date .' '.  $datadate11[1],
                    'assigned_on'                 => $currentTime1,
                    'created_at'                  => $currentTime1,
                    'status'                      => 'pending',
                    'task_completed_at'           => null,
                    'status_flag'                 => '0',
                    'assigned_to'                 => session()->get('userid'),
                    'created_by'                  => session()->get('userid'),
                    'updated_by'                  => session()->get('userid'),
                    'patient_id'                  => $patientid
      );
      //dd($todo_data);
      //($event_start_date , $updatedate);
      if($eventdate < $updatedate){ //"2022-09-26" 10-01-2022"
          return 'reschedule the date today or next';
      }else{ 
          $insert = ToDoList::insert($todo_data); 
          // dd($insert , $update);
          if($insert) { 
                   return ' Task Succesfully add!';

          } else {
                   return 'Something went wrong! Please try again.';
          }
      }
    }
    public function updateDataCalender(Request $request){
      //scheduletime
      $scheduledTime = $request->input('scheduledtime');
     // dd($scheduledTime);
      $cal_Id = $request->input('cal_id');
      $checkdate = (explode(" ",$scheduledTime));
      $d = $checkdate[0];
      //dd($scheduledTime);


      $currentTime1 = Carbon::now();
      $currentdate1 =$currentTime1->toDateTimeString();
      $datadate11 = (explode(" ",$currentdate1));
      $dateValue11 = $datadate11[0];
      $todate = date('d',strtotime($dateValue11));
      $tomonth = date('m',strtotime($dateValue11));
      $toyear = date('Y',strtotime($dateValue11));
      $updatedate = $tomonth .'-'. $todate .'-'. $toyear;


      $check ="select * from task_management.to_do_list where id = '".$cal_Id."'";
      $updatecheck = DB::select( DB::raw($check) );

      foreach ($updatecheck as $row) {
        $status_flag =  $row->status_flag;
        $module_id  = $row->module_id;
        $component_id = $row->component_id;
        $stage_id = $row->stage_id;
        $task_notes = $row->task_notes;
        $created_at = $currentdate1;
        $updated_at = $currentdate1;
        $assigned_to = $row->assigned_to;
        $patient_id = $row->patient_id;
        $uid = $row->uid;
        $task_time = $checkdate[1];
        $enrolled_service_id = $row->enrolled_service_id;
        $notes = $row->notes;
      }
      if($component_id == '' || $component_id = 'null'){
        $component_id1 = '0';
      }else{
        $component_id1 = $component_id;
      }
      $to_do = array( 
                            'uid'                         => $patient_id,
                            'module_id'                   => $module_id, 
                            'component_id'                => $component_id1,
                            'stage_id'                    => $stage_id,
                            'task_notes'                  => $task_notes,
                            'notes'                       => $notes,
                            'assigned_to'                 => $assigned_to,
                            'task_time'                   => $task_time,
                            'task_date'                   => $d .' '. $datadate11[1],
                            'assigned_on'                 => $currentTime1,
                            'status'                      => 'pending',
                            'task_completed_at'           => null,
                            'status_flag'                 => '0',
                            'created_by'                  => session()->get('userid'),
                            'updated_by'                  => session()->get('userid'),
                            'patient_id'                  => $patient_id
                        ); 

      $todo_data = array( 
                        'status_flag' => '4',
                        'status' => 'Rescheduled',
                        'updated_by' => session()->get('userid')
                        );
     // print_r($to_do); 
    //dd("dfasd");
      if($d < $updatedate){ //dd("ifworking"); //"09-12-2022" "09-15-2022"
        return 'reschedule the date today or next';
      }elseif($status_flag == '1'){  //dd("elseif");
       return 'Completed task not able to reschedule';
      }else{ 

        $update = ToDoList::where('id',$cal_Id)->update($todo_data);
        $insert = ToDoList::insert($to_do); 
      // dd($insert , $update);
        if($insert) { 
                 return ' Updated Succesfully!';

        } else {
                 return 'Something went wrong! Please try again.';
        }
      }

    }
}
