<?php 
namespace RCare\Messaging\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Roles\src\Models\Roles;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use RCare\TaskManagement\Models\ToDoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RCare\TaskManagement\Models\UserPatients;
use RCare\Messaging\Models\MessageLog;
use RCare\Ccm\Models\CallWrap;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use DataTables;
use Carbon\Carbon;
use Session;
use RCare\System\Traits\DatesTimezoneConversion; 

class MessageLogController extends Controller
{


  public function getMessageCount(Request $request){
    $role = session()->get('role_type');
    $role_id = session()->get('role');

    $roles = Roles::where('id',$role_id)->get();
    //echo $roles[0]->role_name;
    //echo session()->get('role');
    $caremanager = session()->get('userid');
    if($roles[0]->role_name == 'Care Manager'){
     // echo $roles[0]->role_name;
      $query = "select count(distinct patient_id) from ccm.message_log ml where patient_id in (select distinct patient_id 
      from task_management.user_patients up where user_id = $caremanager and up.status = 1  and read_status = 1) or 
      patient_id  in (select distinct patient_id from ccm.message_log ml where created_by= $caremanager  and read_status = 1) and read_status = 1";
      $data  = DB::select(DB::raw($query));
     // dd($data[0]->count);
      $count = $data[0]->count;
    }elseif($roles[0]->role_name == 'Team Lead'){
      $query = "select count(distinct patient_id) from ccm.message_log ml where patient_id in (select distinct patient_id 
      from task_management.user_patients up where user_id = $caremanager and up.status = 1  and read_status = 1) or 
      patient_id in (select distinct  b.patient_id from ren_core.user_practices a join patients.patient_providers b
      on a.practice_id = b.practice_id join ccm.message_log c on b.patient_id = c.patient_id  where a.user_id = $caremanager and b.is_active = 1 and read_status = 1) or
      patient_id  in (select distinct patient_id from ccm.message_log ml where created_by= $caremanager  and read_status = 1) and read_status = 1";
      $data  = DB::select(DB::raw($query));
     // dd($data[0]->count);
      $count = $data[0]->count;
    }else{
      $count = MessageLog::where('read_status',1)->distinct('patient_id')->count('patient_id');
    }
    
    print($count);
  }

  public function  getMessageHistory(Request $request){
    $id = sanitizeVariable($request->id);
    MessageLog::where('patient_id',$id)->where('read_status', 1)->update(['read_status' => 0]);
    $data = MessageLog::where('patient_id',$id)->where('message','!=','')->orderBy('message_date','asc')->get();
    $info = Patients::where('id',$id)->latest()->first();
    $name = $info->fname.' '.$info->mname.' '.$info->lname;
    $enrollModule = PatientServices::where('patient_id',$id)->get();
    $module = 'CCM';
    $module_id = 3;
    $chat = '';
    $link = '<a href="/ccm/monthly-monitoring/'.$id.'" style="color:white">MM</a>';
    foreach($enrollModule as $enroll){
      $mname = Module::where('id',$enroll->module_id)->get();
      if($mname[0]->module == 'RPM'){
        $module = 'RPM';
        $module_id = $enroll->module_id;
        $link = '<a href="/rpm/monthly-monitoring/'.$id.'" style="color:white">MM</a>';
      }
    }

    $last_time_spend_db                = CommonFunctionController::getCcmNetTime($id, $module_id);
    $non_billabel_time_db              = CommonFunctionController::getNonBillabelTime($id, $module_id);
    $billable_time_db                = CommonFunctionController::getCcmMonthlyNetTime($id, $module_id);        
    $non_billabel_time = empty($non_billabel_time_db)?'00:00:00':$non_billabel_time_db;
    $billable_time = empty($billable_time_db)?'00:00:00':$billable_time_db;
    $last_time_spend = empty($last_time_spend_db)?'00:00:00':$last_time_spend_db;
    foreach($data as $msg){

      $date = $msg->message_date;

      if($msg->status == 'delivered'){
        $tick = '<span>&#10003; </span>';
      }else{
        $tick = '<i class="text-muted i-Timer1" style="color: white!important;"></i>';
      }

      if($msg->status == 'received'){
         $chat.='<div class="incoming_msg">
              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
              <div class="received_msg">
                <div class="received_withd_msg">
                  <p>'.$msg->message.'</p>
                  <span class="time_date">'.$date.'</span></div>
              </div>
            </div>';
      }else{
       $chat.='<div class="outgoing_msg">
        <div class="sent_msg">
          <p>'.$tick.' '.$msg->message.'</p>
          <span class="time_date">'.$date.'</span> </div>
      </div>';
      }
     
    }

    return ['enrollModule'   =>$enrollModule,
            'chat' =>$chat,
            'non_billabel_time'   => $non_billabel_time,
            'billable_time' =>$billable_time,
            'link' => $link,
            'last_time_spend'=> $last_time_spend];
  }

  public function  getMessage(Request $request){
    //dd($request->all());
    $practices = sanitizeVariable($request->practice);
    $role_id = session()->get('role');
    $roles = Roles::where('id',$role_id)->get();
   // $practices =null;
    $practicesgrp = sanitizeVariable($request->practicesgrp);
    $module = sanitizeVariable($request->module);
    $caremanager = sanitizeVariable($request->caremanager);
    $fromdate= sanitizeVariable($request->fromdate);
    $mstatus = sanitizeVariable($request->status);
    $readstatus = sanitizeVariable($request->read_status);
    $configTZ     = config('app.timezone');
    $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
      $fromdate1 =$fromdate." "."00:00:00";
      $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate1);
      $todate= sanitizeVariable($request->todate); 
      $todate1 = $todate ." "."23:59:59";  
      $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate1); 
      if($roles[0]->role_name == 'Team Lead' && $caremanager == 'null'){
        $cm = session()->get('userid');
        $query="select patientid,id,message,fname,lname,mname,profile_img,
        to_char(messagedate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as messagedate, readstatus
        from patients.message_list_team_lead($practices,$practicesgrp,$caremanager,$module,timestamp '".$dt1."',timestamp '".$dt2."','".$mstatus."','".$cm."','".$readstatus."')";
     }else{
        $query="select patientid,id,message,fname,lname,mname,profile_img,
        to_char(messagedate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as messagedate, readstatus
        from patients.message_list($practices,$practicesgrp,$caremanager,$module,timestamp '".$dt1."',timestamp '".$dt2."','".$mstatus."','".$readstatus."')";
      }
   

     // print_r($query);
  $data  = DB::select(DB::raw($query));
  foreach($data as $datas){
   //active_chat
   if($datas->patientid ==  sanitizeVariable($request->uid)){
     $activeclass = 'active_chat';
   }else{
    $activeclass = '';
   }
   $number = 0;
   $message_count = '';
   if($datas->readstatus == 1){
     $message_count = MessageLog::where('read_status',1)->where('patient_id',$datas->patientid)->count();
     $style = 'style="color:black"'; 
     $style1 = 'style="text-align:center;float:right;border-radius: 20px;border: 2px solid #FFF;width: 20px;height: 20px;background-color: #27a8de;position: relative;top: -10px;left: -26px;font-size: 10px;line-height: 20px, sans-serif;font-weight: 400;color: #FFF;font-weight: 700;"';
    }else{
      $style = ''; $style1 = '';
    }
   $name = $datas->fname.' '.$datas->mname.' '.$datas->lname;
    echo '<div class="chat_list '.$activeclass.'" id="'.$datas->patientid.'" onclick="getHistory('.$datas->patientid.','.$number.','."'$name'".')">
              <div class="chat_people">
                <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                <div class="chat_ib">
                  <h5>'.$datas->fname.' '.$datas->mname.' '.$datas->lname.' <span class="chat_date">'.$datas->messagedate.'</span></h5>
                  <p '.$style.'>'.substr($datas->message, 0, 50).'<span class="chat_date online_icon" '.$style1.'>'.$message_count.'</span></p>
                </div>
              </div>
            </div>';
  }
  }

  //onclick="getConsent('.$val->id.','.$number.','."'$name'".')"onclick="getConsent('.$val->id.','.$number.','."'$name'".')"

  public function getUserList($value,$id){
   
    $query = "select p.id as id, fname, mname, mname, lname, message_date, message, primary_cell_phone, mob, home_number, secondary_cell_phone, 
    p.country_code, secondary_country_code, consent_to_text from patients.patient p
    left join (select distinct max(mm.id) mid,patient_id from ccm.message_log mm group by patient_id ) m1 on p.id = m1.patient_id 
    left join ccm.message_log m on m.id = m1.mid ";
    $query.="left join ren_core.users u on m.updated_by = u.id
    left join task_management.user_patients up on p.id = up.patient_id and up.status = 1
    left join ren_core.users u1 on up.user_id = u1.id where 1 = 1
    ";
    if($id == 'null'){
     
    }else{
      $query.=" and (u1.id=$id or m.updated_by = $id)";
    }
    
    if($value == 'concent patient list'){
      $query.="order BY fname asc";
      //$query.="(primary_cell_phone = 1 or secondary_cell_phone = 1) and consent_to_text = 1 order BY fname asc";
    }else{
      $query.=" and (fname like '%$value%' or lname like '%$value%' or mname like '%$value%')";
    }
   
    $data  = DB::select(DB::raw($query));
    foreach($data as $val){ 
      $number = 1;
      $name = $val->fname.' '.$val->mname.' '.$val->lname;
      if(($val->primary_cell_phone == 1 || $val->secondary_cell_phone == 1) and $val->consent_to_text == 1){
            echo '<div class="chat_list" id="'.$val->id.'" onclick="getHistory('.$val->id.','.$number.','."'$name'".')">
            <div class="chat_people">
              <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
              <div class="chat_ib">
                <h5>'.$val->fname.' '.$val->mname.' '.$val->lname.' <span class="chat_date">'.$val->message_date.'</span></h5>
                <p>'.substr($val->message, 0, 50).'</p>
              </div>
            </div>
          </div>';
      }else{
          echo '<div class="chat_list" id="'.$val->id.'" >
          <div class="chat_people">
            <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
            <div class="chat_ib">
              <h5>'.$val->fname.' '.$val->mname.' '.$val->lname.' <span class="chat_date">'.$val->message_date.'</span></h5>
              <p style="float:left">No Consent for Text</p> <button class="btn btn-info editContact" style="float: right;
              margin-top: -17px;" data-toggle="modal"  data-id="'.$val->id.'">Edit</button>
            </div>
          </div>
        </div>';
      }
     
    }
  }

  public function MessageLog(Request $request)
  {

    $practices = sanitizeVariable($request->practice);
    $practicesgrp = sanitizeVariable($request->practicesgrpid);
    $module = sanitizeVariable($request->module);
    $caremanager = sanitizeVariable($request->caremanager);
    $fromdate= sanitizeVariable($request->route('fromdate'));
    $mstatus = sanitizeVariable($request->route('status'));
    $configTZ     = config('app.timezone');
    $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
      $fromdate1 =$fromdate." "."00:00:00";
      $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
      $todate= sanitizeVariable($request->route('todate')); 
      $todate1 = $todate ." "."23:59:59";  
      $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate1); 
      $query="select id,fphone,tophone,messageid,status,modulename,ufname,ulname,fname,lname,mname,profile_img,dob,practicename,
      to_char(messagedate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as messagedate, u1fname,u1lname
      from patients.message_log($practices,$practicesgrp,$caremanager,$module,timestamp '".$dt1."',timestamp '".$dt2."','".$mstatus."')";
   
  $data  = DB::select( DB::raw($query) );

  return Datatables::of($data)
      ->addIndexColumn()
      ->addColumn('action', function($row){ 
        if($row->status == 'failed' || $row->status == 'undelivered'){
          $btn ='<i class="text-15 i-Share" onclick=reSend("'.$row->messageid.'") style="color: red;cursor: pointer;"></i><i class="text-15 i-Eye" onclick=viewSendMessage("'.$row->messageid.'") style="color: green;cursor: pointer;"></i>';
        }else{
          $btn ='<i class="text-15 i-Eye" onclick=viewSendMessage("'.$row->messageid.'") style="color: green;cursor: pointer;"></i>';
        }
        return $btn;
    })
    ->rawColumns(['action'])
  ->make(true);
  }

  public function ViewMessage(Request $request){
   $message = getMessage(sanitizeVariable($request->id));
   echo $message;
  }

  public function resendMessage(Request $request){
    $message = reSendMessage(sanitizeVariable($request->id));
    echo $message;
  }
  public function sendMessage(Request $request){
    $id = sanitizeVariable($request->id);
    $content = sanitizeVariable($request->text);
    $module_id = sanitizeVariable($request->module);
    $number = sanitizeVariable($request->number);
    $start_time   = sanitizeVariable($request->timer_start);
    $end_time     = sanitizeVariable($request->timer_paused);
    //dd($end_time);
    if($number == 0){
      $data = MessageLog::where('patient_id',$id)->where('status','!=','received')->latest()->first();
      $number = $data->to_phone;
    }else{
      $data = Patients::where('id',$id)->latest()->first();
      if($data->primary_cell_phone == 1){
        $number = $data->country_code.''.$data->mob;
      }else{
        $number = $data->secondary_country_code.''.$data->home_number;
      }
    }
    $data = MessageLog::where('patient_id',$id)->where('status','!=','received')->latest()->first();
    $stage_id = 0;
    $step_id = 0;
    $form_name = 'message_log_form';
    $billable = 1;
    $component_id = 0;
    //print_r($data->to_phone);
    sendTextMessage($number, $content, $id, $module_id, $stage_id); 
    $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $id, $module_id, $component_id, $stage_id, $billable, $id, $step_id, $form_name);

    $text_note = array(
      'uid'                 => $id,
      'record_date'         => Carbon::now(),
      'topic'               => 'Text Message Sent',
      'notes'               => $content,
      'patient_id'          => $id,
      'sequence'            => "12",
      'sub_sequence'        => "11",
      'created_by'          => session()->get('userid')
  );

  CallWrap::create($text_note);

  }

  public function getContactDetail(Request $request){
    $id = sanitizeVariable($request->val);
    $data = Patients::where('id', $id)->get();
    return $data;
  }

  public function updateDetalis(Request $request){
      $country_code = sanitizeVariable($request->country_code);
      $mob = sanitizeVariable($request->mob);
      $primary_cell_phone = sanitizeVariable($request->primary_cell_phone);
      $secondary_cell_phone = sanitizeVariable($request->secondary_cell_phone);
      $consent_to_text = sanitizeVariable($request->consent_to_text);
      $secondary_country_code = sanitizeVariable($request->secondary_country_code);
      $home_number = sanitizeVariable($request->home_number);
      $uid = sanitizeVariable($request->uid);

      $patient_data = array(  
        "country_code" => $country_code,
        "mob" => $mob ,
        "primary_cell_phone" => $primary_cell_phone,
        "secondary_cell_phone" => $secondary_cell_phone,
        "consent_to_text" => $consent_to_text,
        "secondary_country_code" => $secondary_country_code,
        "home_number" => $home_number
      );
      Patients::where('id',$uid)->update($patient_data);

  }

}