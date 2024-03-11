<?php
namespace RCare\Patients\Http\Controllers;
use RCare\Patients\Models\Patients;
use RCare\TaskManagement\Models\UserPatients;
use RCare\TaskManagement\Models\PatientActivity;   
use RCare\Org\OrgPackages\Activity\src\Models\Activity;
use RCare\Org\OrgPackages\Activity\src\Models\PracticeActivity;
use RCare\Org\OrgPackages\Users\src\Models\UserFilters; 
use RCare\Ccm\Models\CallWrap;
use RCare\Rpm\Models\DeviceEducationTraining;
use RCare\TaskManagement\Http\Requests\PatientActivityAddRequest;
use RCare\System\Http\Controllers\CommonFunctionController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log;
use RCare\Patients\Models\PatientDevices;   
use RCare\Patients\Models\PatientTimeRecords;
use RCare\Patients\Models\PatientCareplanAge;
use RCare\TaskManagement\Models\ToDoList;
use DataTables;
use Carbon\Carbon;
use Session;
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use RCare\Org\OrgPackages\Users\src\Models\OrgUserRole; 
use RCare\Org\OrgPackages\Roles\src\Models\Roles;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Org\OrgPackages\DomainFeatures\src\Models\DomainFeatures;
use RCare\System\Traits\DatesTimezoneConversion; 
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientCareplanLastUpdateandReview;
use RCare\Patients\Models\View_Patient_Diagnosis;
use Auth;    
class PatientWorklistController extends Controller {
    public function getUserListData(Request $request) {  
        $cid = session()->get('userid');
        if(isset($cid)){
            $usersdetails = Users::where('id',$cid)->get();
            $roleid = $usersdetails[0]->role;
            // $sessiontimeoutdetails = DomainFeatures::where('status',1)->get();
            // $sessiontimeout = $sessiontimeoutdetails[0]->session_timeout;
            // $logoutpoptime =  $sessiontimeoutdetails[0]->logoutpoptime;
            // return view('Patients::patient-allocation.work-list',compact('roleid','sessiontimeout','logoutpoptime'));  
            return view('Patients::patient-allocation.work-list',compact('roleid'));      
        } else {
            return redirect('rcare-login');
        }
    }

    public function getTotalTimeSpentByCM(Request $request)
    {
         $cid = session()->get('userid');
         $date=date("Y-m-d");
         $fromdate=$date." 00:00:00";
         $todate=$date." 23:59:59"; 
         //  $fromdate="2021-10-11 00:00:00";
         // $todate="2021-10-11 23:59:59"; 
         $configTZ     = config('app.timezone');
         $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');     
          $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
          $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate);   
         $query="select count(ptr.patient_id) as totalpatients,sum(ptr.totaltime) as totaltime from (
                select DISTINCT pt1.patient_id,pt1.timeone ,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone) as totaltime,pt1.created_by
                              from  (SELECT patient_id,sum(net_time) as timeone,created_by FROM patients.patient_time_records WHERE 
                  adjust_time =1 and module_id in (2,3,8)  and created_by=$cid and created_at between '".$dt1."' and '".$dt2."'                
                   group by patient_id,created_by) pt1                    
                  LEFT JOIN (SELECT patient_id, sum(net_time) AS timetwo,created_by FROM patients.patient_time_records WHERE 
                  adjust_time =0 and module_id in (2,3,8)  and created_by=$cid  and created_at between '".$dt1."' and '".$dt2."'             
                   group by patient_id,created_by) pt2 ON  pt2.patient_id = pt1.patient_id
                  where  1=1 ) ptr where ptr.created_by=$cid";
            //  dd($query);
          $data = DB::select( DB::raw($query)); 
         $patientcount=$data[0]->totalpatients;
          
          if($patientcount!="" || $patientcount!=0 || $patientcount!=null)
          {  
           $time=$data[0]->totaltime;       
           $timesplit=explode(':',$time);
           // $min=($timesplit[0]*60)+($timesplit[1])+($timesplit[2]>30?1:0);     
           $min1=($timesplit[0]*60)+($timesplit[1])+($timesplit[2]/60);  
             $min=number_format((float)$min1, 2, '.', '');        
           if($min < 10 )
           {
            $min='0'.$min;
           }
            $data['minutes']=$min;
          }
          else
          {
            $data=null;
          }
         
          return json_encode($data);

    }
 
    public function get_activitytime(Request $request){
        $id = sanitizeVariable($request->route('id'));
        $practice_id = sanitizeVariable($request->route('practice_id'));
        $query =Activity::where('id',$id)->where('status',1)->get();
        if($query[0]->timer_type==1){
            $query =Activity::where('id',$id)->get();
        }else{
            $check = PracticeActivity::where('activity_id',$id)->where('practice_id',$practice_id)->exists();
                if($check==true){
                    $query = PracticeActivity::where('activity_id',$id)->where('practice_id',$practice_id)->select('time_required')->get();                
                }else{
                    $query =Activity::where('id',$id)->select('default_time')->get();
                }
        }
        return $query;
    }
 
    public function savePatientActivity(PatientActivityAddRequest $request){
        $module_id=sanitizeVariable($request->module_id);
        $stage_id=sanitizeVariable($request->stage_id);
        $start= sanitizeVariable($request->timer_on);
        $end = sanitizeVariable($request->net_time);
        $davice_traning_date = strtotime(sanitizeVariable($request->davice_traning_date));
        $dt_new_date = date("Y-m-d H:i:s", strtotime('+6 hours', $davice_traning_date));
        $today =date('m/d'); 
        // dd($today);
        $time_off = date("H:i:s",strtotime($start) + strtotime($end));
        $sum = strtotime('00:00:00'); 
        $totaltime = 0; 
        // Converting the time into seconds 
        $timeinsecend = strtotime($end) - $sum; 
        $timeinsecstart = strtotime($start) - $sum;
        // Sum the time with previous value 
        $totaltime =  abs($timeinsecstart + $timeinsecend);  
        $timeinsec = strtotime('24:00:00') - $sum;
        if($totaltime > $timeinsec){
            $msg = 'time is not more than 24 hours';
            return $msg;
        } else {
            //Insert Data in callwrapUp
            $sequence   = 6;
            $last_sub_sequence = CallWrap::where('patient_id',sanitizeVariable($request->patient_id))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', $sequence)->max('sub_sequence');
            // dd($last_sub_sequence);
            $timesplit=explode(':',sanitizeVariable($request->net_time));
            $min=($timesplit[0]*60)+($timesplit[1])+($timesplit[2]>30?1:0);
            $new_sub_sequence = $last_sub_sequence + 1;
            $callwrapUp = array(
                        'uid'                 => sanitizeVariable($request->patient_id),
                        'record_date'         => Carbon::now(),
                        'topic'               => sanitizeVariable($request->activity),
                        'notes'               => $today.' '.$min.' Minutes  '.sanitizeVariable($request->notes),
                        'action_taken'        => '',
                        'emr_entry_completed' => null,
                        'emr_monthly_summary' => null,
                        'created_by'          => session()->get('userid') ,
                        'patient_id'          => sanitizeVariable($request->patient_id),
                        'sequence'            => $sequence,
                        'sub_sequence'        => $new_sub_sequence
                  );
            $save = CallWrap::create($callwrapUp);
            $take_id =$save->id;
 
            $start_time="00:00:00";//sanitizeVariable($request->timer_on);
            $end_time=sanitizeVariable($request->net_time);
            $patientid=sanitizeVariable($request->patient_id);
            $component_id=sanitizeVariable($request->component_id);
            $stage_id=0;
            $billable=1;
            $step_id=0;
            $form_name='activity_form';
            $callwrap_id=$take_id;
            $activity=sanitizeVariable($request->activity);
            $activity_id=sanitizeVariable($request->activity_id);
            $comment=sanitizeVariable($request->notes);
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patientid, $module_id, $component_id, $stage_id, $billable, $patientid,$step_id,$form_name,$callwrap_id,$activity,$activity_id,$comment);
                
            $activityname=sanitizeVariable($request->activity);
            if($activityname=='Device Education') //for device education training
            {
                $PatientDevices = PatientDevices::where('patient_id', $patientid)->orderby('id','desc')->get();
                $deviceid='[]';
                if(count($PatientDevices) > 0) {
                    $deviceid=$PatientDevices[0]->vital_devices!='' ? $PatientDevices[0]->vital_devices : "";
                }
                $devicetrainingdata=array(
                      'record_date'=>$dt_new_date,
                      'patient_id'=>$patientid,
                      'device_id'=>$deviceid,
                      'status'=>1,
                      'notes'=>sanitizeVariable($request->notes),  
                      'time'=> sanitizeVariable($request->net_time), 
                      'created_by'=>session()->get('userid')
                );
                DeviceEducationTraining::create($devicetrainingdata);
            } 
        }
    }

   
    //created by ashvini and modified on 6thjuly2022  
  /*  public function getUserListDataAllNew(Request $request)          
    {
        if ($request->ajax()) {
            // $pagemodule_id = getPageModuleName();
            // $pagesubmodule_id =getPageSubModuleName();
            $cid = session()->get('userid');
            $practices = sanitizeVariable($request->route('practice_id'));
            $patient = sanitizeVariable($request->route('patient_id'));
            $module   = sanitizeVariable($request->route('module_id'));
            $timeoption = sanitizeVariable($request->route('timeoption'));
            $time = sanitizeVariable($request->route('time'));
            $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus')); 
            $usersdetails = Users::where('id',$cid)->get();
            $roleid = $usersdetails[0]->role;
            $monthly = Carbon::now();
            $monthlyto = Carbon::now();
            $year = date('Y', strtotime($monthly));
            $month = date('m', strtotime($monthly));
            $configTZ = config('app.timezone');
            $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
            $p;
            $pt;
            $totime; 
            $status;
            $iconcolor;
            $icontitle ;    
            $check_months = [];
            $is_red_or_green_or_yellow = 0;
              
            if( $practices!='null') {
                if( $practices==0){
                    $p = 0;  
                } else{
                    $p = $practices;
                }    
            } else{
                $p = 'null';
            }

            if($patient!="null"){ 
                $pt = $patient; 
            } else {
                $pt = "null";
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

            if($activedeactivestatus=="null"){  
                $status="null";
            } else {
                $status=$activedeactivestatus;  
            }   

            if($module=="null"){
                $module_id = "null";
            } else { 
                $module_id = $module;  
            }
            //$query = "select * from patients.worklist($p,$pt,$month, $year,$timeoption,'".$totime."',$roleid, $cid,'".$configTZ ."','".$userTZ."',$status)"; 
			//dd($query );	  		
            //$query = "select pid, pfname, plname, pmname, pprofileimg, pdob, pppracticeemr,ppracticeid, pracpracticename,pfromdate,ptodate,to_char(csslastdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csslastdate  , pstatus, ptrtotaltime from 
            //patients.worklist($p,$pt,$month, $year,$timeoption,'".$totime."',$roleid, $cid,'".$configTZ ."','".$userTZ."',$status,$module_id)"; 

            if($roleid == 2 || $roleid == 5){            
                $query = "select pid, pfname, plname, pmname, pprofileimg, pdob, pppracticeemr,ppracticeid, pracpracticename,pfromdate,ptodate,to_char(csslastdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csslastdate  , pstatus, ptrtotaltime,psmodule_id from 
                patients.worklist($p,$pt,$month, $year,$timeoption,'".$totime."',$roleid, $cid,'".$configTZ ."','".$userTZ."',$status,$module_id)";  
            } else {
                $query = "select pid, pfname, plname, pmname, pprofileimg, pdob, pppracticeemr,ppracticeid, pracpracticename,pfromdate,ptodate,to_char(csslastdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csslastdate  , pstatus, ptrtotaltime,psmodule_id from 
                patients.worklist_tlm($p,$pt,$month, $year,$timeoption,'".$totime."',$roleid, $cid,'".$configTZ ."','".$userTZ."',$status,$module_id)";
            }         
              //dd($query);  
            // $data = DB::select( DB::raw($query) );  
            if($roleid  == 2) {
                if($module!="null" || $p!="null" || $pt!="null"){   
                    $data = DB::select(DB::raw($query));
                } else {
                    $data = [];  
                }
            } else{
                $data = DB::select(DB::raw($query));    
            } 

           
        foreach($data as $d){    
              
            $viewpatients = View_Patient_Diagnosis::where('patient_id', $d->pid)->get();
           
           
            if(count($viewpatients)==0 ){
                   $iconcolor = null; 
                   $icontitle ='';          
            }else{
                 
                foreach($viewpatients as $p){                   

                    if($p==null  || $p==''){

                       $iconcolor = null;

                    }else{

                       

                       // dd($p);
                   // ************************************ calculation for review date     ********************************************

                       $diffInYearsfor_Reviewdate = (int)$p->review_year_age;
                       $diffInMonthsfor_Reviewdate = (int)$p->review_month_age;
                       $diffInDaysfor_Reviewdate = (int)$p->review_days_age;
                       
                       $diffInDaysfor_Reviewdate_decimal = $diffInDaysfor_Reviewdate/100;
                       $diffInMonthsfor_Reviewdate =  $diffInMonthsfor_Reviewdate+$diffInDaysfor_Reviewdate_decimal;
                      
                       
                    // ************************************ calculation for update date     ********************************************

                       $diffInYearsfor_Updatedate = (int)$p->update_year_age;
                       $diffInMonthsfor_Updatedate = (int)$p->update_month_age;

                    

                    if(($diffInMonthsfor_Reviewdate >= 5) || ( $diffInYearsfor_Reviewdate >=1 ) || ( $diffInYearsfor_Updatedate >=1 )  ){  
                    
                     
                    $iconcolor = 'red';                 
                    $icontitle ='All the Care Plans not reviewed for more than 3 months or
                    Any of the Care Plan not reviewed for more than 5 months or
                    Any of the Care Plan not updated for more than a year';
                    $d->iconcolor = $iconcolor;
                    $d->icontitle = $icontitle;
                    break;
                    }



                    if( (  ($diffInMonthsfor_Reviewdate >= 0  && $diffInYearsfor_Reviewdate < 1) )    ){
                    // if( ($diffInMonthsfor_Reviewdate >0 && $diffInMonthsfor_Reviewdate < 4 || $diffInMonthsfor_Reviewdate == 0 )  && ($diffInMonthsfor_Updatedate >=0 )  ){
                    //#within 5 months
                       //  if( ( ($diffIndaysfor_Reviewdate >=0 && $diffIndaysfor_Reviewdate <= 152 && $diffInYearsfor_Reviewdate < 1) || ($diffIndaysfor_Reviewdate == 30  && $diffInYearsfor_Reviewdate < 1) )    ){

                       
                        if($diffInMonthsfor_Reviewdate <= 1 ){
                            $diffInMonths = 1;

                        }else if( $diffInMonthsfor_Reviewdate > 1 && $diffInMonthsfor_Reviewdate <= 2 ){
                            $diffInMonths = 2;

                        }else if( $diffInMonthsfor_Reviewdate > 2 && $diffInMonthsfor_Reviewdate <= 3 ){
                            $diffInMonths = 3;

                        }else if( $diffInMonthsfor_Reviewdate > 3 && $diffInMonthsfor_Reviewdate <= 4 ){
                                $diffInMonths = 4;
    
                        }else if( $diffInMonthsfor_Reviewdate > 4 && $diffInMonthsfor_Reviewdate < 5 ){//i.e upto 4.1 to 4.9
                           $diffInMonths = 4.9;

                       }else if( $diffInMonthsfor_Reviewdate >= 5  ){
                           $diffInMonths = 5;

                       }   
                        array_push($check_months,$diffInMonths); 
                        $is_red_or_green_or_yellow =1;    
                      
                    }   

                     
                    }

                } 

               //  dd($check_months);


                if($is_red_or_green_or_yellow == 1){ 


                    $uniquearray = array_unique($check_months);
                   //  dd($uniquearray,$check_months);   
 
                    if(  (count($uniquearray) == 1) &&  (in_array(0, $check_months)) || (count($uniquearray) == 1) &&  (in_array(1, $check_months)) ||  (count($uniquearray) == 1) &&  (in_array(2, $check_months)) ||
                         (count($uniquearray) == 1) &&  (in_array(3, $check_months))   ){
                          
                            $iconcolor = 'green';
                            $icontitle = 'All the Care Plans have been reviewed within 3 months';
                            $d->iconcolor = $iconcolor;
                            $d->icontitle = $icontitle;
                            

                    }else if(  (count($uniquearray) == 1) &&  (in_array(5, $check_months)) ){
                            $iconcolor = 'red';
                            $icontitle = 'All the Care Plans not reviewed for more than 3 months or
                            Any of the Care Plan not reviewed for more than 5 months or
                            Any of the Care Plan not updated for more than a year';
                            $d->iconcolor = $iconcolor;
                            $d->icontitle = $icontitle;
                            

                    }else if( (count($uniquearray) > 1) &&  (in_array(5, $check_months))  ){
                        $iconcolor = 'red'; //#rule no 4 #exact 5months and above
                        $icontitle = 'All the Care Plans not reviewed for more than 3 months or
                        Any of the Care Plan not reviewed for more than 5 months or
                        Any of the Care Plan not updated for more than a year';
                        $d->iconcolor = $iconcolor;
                        $d->icontitle = $icontitle;
                    
                    }
                    else if( (((count($uniquearray) > 1) &&  (in_array(0, $check_months)) ) || ((count($uniquearray) > 1) &&  (in_array(1, $check_months)) ) || 
                              ((count($uniquearray) > 1) &&  (in_array(2, $check_months)) )  ||  ((count($uniquearray) > 1) &&  (in_array(3, $check_months)) ) )   &&  
                               
                              ( (count($uniquearray) > 1) &&  (in_array(4, $check_months)) || (count($uniquearray) > 1) &&  (in_array(4.9, $check_months)) ) 
                              ){
                               # atleast  one is reviewed(i.e within3months wala ) and other is not reviewed(within 5 months wala)
                        $iconcolor = 'yellow';
                        $icontitle = 'Any of the Care Plan not reviewed for more than 3 months';
                        $d->iconcolor = $iconcolor;
                        $d->icontitle = $icontitle;
                
                      

                    }else if( ((count($uniquearray) > 1) &&  (in_array(0, $check_months)) ) || ((count($uniquearray) > 1) &&  (in_array(1, $check_months)) ) || 
                            ((count($uniquearray) > 1) &&  (in_array(2, $check_months)) ) || ((count($uniquearray) > 1) &&  (in_array(3, $check_months)) )  ){
                    
                        $iconcolor = 'green';
                        $icontitle = 'All the Care Plans are reviewed within 3 months';
                        $d->iconcolor = $iconcolor;
                        $d->icontitle = $icontitle;
                        
                    }else{
                 
                       $d->iconcolor = '';
                       $d->icontitle = '';
                    }
              
                    
                     
                }
                $check_months = array();  
         

            }  
            
            $d->iconcolor = $iconcolor;
            $d->icontitle = $icontitle;  
          
           //  dd($d);     

              
              
        }
             
           //  dd($data); 
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $check = DB::table('patients.patient_diagnosis_codes')   
                ->where('patient_id',$row->pid)
                ->latest()
                ->first();  

               

                $PatientService = PatientServices::select("*")
                ->with('module')
                ->whereHas('module', function($q) {
                    $q->where('module', '=', 'RPM'); // '=' is optional
                })
                ->where('patient_id',$row->pid)
                ->where('status', 1)
                ->exists();


                if ($PatientService == true)  {
                    if($check == "" || $check == null ){ 
                        $btn1 ='<a href="/rpm/care-plan-development/'.$row->pid.'" title="Action" >Care Plan </a>'; //cpd
                        if($row->iconcolor == null || $row->iconcolor == ''){
                            $btn2 = '';
                        }else{
                          
                            if($row->iconcolor == 'green' || $row->iconcolor == "green"){
                                $row->iconcolor = '#33ff33'; 
                            }
                            $btn2 ='<a href="javascript:void(0)" data-toggle="tooltip"  title = "'.$row->icontitle.'" ><i class="i-Closee i-Data-Yes" style="color: '.$row->iconcolor.' ; cursor: pointer;"></i></a>';  
                           }
                        
                        $btn =  $btn1." ".$btn2;
                    } else {
                       
                        $btn1 ='<a href="/rpm/monthly-monitoring/'.$row->pid.'" title="Action" >CCM </a>'; //monthly
                        if($row->iconcolor == null || $row->iconcolor == ''){
                            $btn2 = '';
                        }else{
                                if($row->iconcolor == 'green' || $row->iconcolor == "green"){
                                    $row->iconcolor = '#33ff33'; 
                                }
                                $btn2 ='<a href="javascript:void(0)" data-toggle="tooltip"   title = "'.$row->icontitle.'" ><i class="i-Closee  i-Data-Yes" style="color: '.$row->iconcolor.' ; cursor: pointer;"></i></a>';    
                        }
                       
                        $btn =  $btn1." ".$btn2;
                    }
                } else {
                    if($check == "" || $check == null ){ 
                        $btn1 ='<a href="/ccm/care-plan-development/'.$row->pid.'" title="Action" >Care Plan</a>'; //cpd
                        if($row->iconcolor == null || $row->iconcolor == ''){

                            $btn2 = '';

                        }else{  
                            
                                if($row->iconcolor == 'green' || $row->iconcolor == "green"){
                                    $row->iconcolor = '#33ff33'; 
                                }
                                $btn2 ='<a href="javascript:void(0)" data-toggle="tooltip"   title = "'.$row->icontitle.'" ><i class="i-Closee  i-Data-Yes" style="color: '.$row->iconcolor.' ; cursor: pointer;"></i></a>';  
                           }
                         
                        $btn =  $btn1." ".$btn2;
                    
                    } else {
                       

                        $btn1 ='<a href="/ccm/monthly-monitoring/'.$row->pid.'" title="Action" >CCM </a>'; //monthly

                        if($row->iconcolor == null || $row->iconcolor == '' ){
                            $btn2 = '';
                        }else{  
                              
                            if($row->iconcolor == 'green' || $row->iconcolor == "green"){
                                $row->iconcolor = '#33ff33'; 
                            }
                              $btn2 ='<a href="javascript:void(0)" data-toggle="tooltip"  title ="'.$row->icontitle.'" ><i class="i-Closee  i-Data-Yes" style="color: '.$row->iconcolor.' ; cursor: pointer;"></i></a>';  

                       }
                        
                        $btn =  $btn1." ".$btn2; 
                    }  
                }  
               
                return $btn;  
            })
            ->addColumn('activedeactive', function($row){
                if($row->pstatus == 1 && $row->pstatus!=0 && $row->pstatus!=2 && $row->pstatus!=3){
                    $btn ='<a href="javascript:void(0)" class="ActiveDeactiveClass" data-toggle="modal"
                    onclick=ccmcpdcommonJS.onActiveDeactiveClick("'.$row->pid.'","'.$row->pstatus.'") data-target="#active-deactive"  id="active_deactive">             
                    <i class="i-Yess i-Yes"  title="Patient Status"></i></a>';
                } else {
                    $btn ='<a href="javascript:void(0)" class="ActiveDeactiveClass" data-toggle="modal"
                    onclick=ccmcpdcommonJS.onActiveDeactiveClick("'.$row->pid.'","'.$row->pstatus.'") data-target="#active-deactive"  id="active_deactive"> 
                    <i class="i-Closee i-Close" title="Patient Status"></i></a>'; 
                    
                } 
                return $btn;  
            })
            ->addColumn('addaction', function($row){
                $btn ='<a href="javascript:void(0)"  data-toggle="modal" data-id="'.$row->pid.'/'.$row->ptrtotaltime.'/'.$row->ppracticeid.'/'.$row->psmodule_id.'" data-target="#add-activities" id="add-activity"
                data-original-title="Patient Activity" class="patient_activity" title="Patient Activity"><i class="text-20 i-Stopwatch" style="color: #2cb8ea;"></i></a>';
                return $btn; 
            }) 
            ->rawColumns(['action','activedeactive','addaction'])  
            ->make(true);
        }
    }

*/

    public function getUserFilters(Request $request)  
    {
        $cid = session()->get('userid');  
        $check =  UserFilters::where('user_id',$cid)->select('filters')->get('filters');   
        $d = []; 
        // dd($check); 

        if(count($check)>0)
        {
     
          $filter = $check[0]->filters;
          $decodedfilters = json_decode($filter); 
          $practice = $decodedfilters->practice;
          $timeoption =  $decodedfilters->timeoption;
          $time = $decodedfilters->time;
          $patientstatus = $decodedfilters->patientstatus;
          $patient = $decodedfilters->patient;
          $data=array('practice'=>$practice,'patient'=>$patient,'timeoption'=>$timeoption,'time'=> $time,
                    'patientstatus'=>$patientstatus);   
                   
                    $data = json_encode($data);
          return $data ;
        }
        else{
          $d = json_encode($d);
          return $d;
        }  
        
    }

    public function saveUserFilters(Request $request)
    {
        if ($request->ajax()) {
            $pagemodule_id = getPageModuleName();
            $pagesubmodule_id =getPageSubModuleName();
            $filters = [];

            $cid = session()->get('userid');
            $practices = sanitizeVariable($request->route('practice_id'));
            $patient = sanitizeVariable($request->route('patient_id'));
            $module   = sanitizeVariable($request->route('module_id'));
            $timeoption = sanitizeVariable($request->route('timeoption'));
            $time = sanitizeVariable($request->route('time'));
            $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus')); 
            $usersdetails = Users::where('id',$cid)->get();
            $roleid = $usersdetails[0]->role;
            $monthly = Carbon::now();
            $monthlyto = Carbon::now();
            $year = date('Y', strtotime($monthly));
            $month = date('m', strtotime($monthly));
            $configTZ = config('app.timezone');
            $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
            $p;
            $pt;
            $totime; 
            $status;
              
             if( $practices!='null'){
                if( $practices==0){
                  $p = 0;  
                }
                else{
                  $p = $practices;
                }    
              }
              else{
                $p = 'null';
              }

              if($patient!="null"){ 
                $pt = $patient; 
               }
               else{
                   $pt = "null";
               }

                
             if($time=='null' || $time==''){
                $timeoption="1";
                $totime = '00:20:00';     
             }
             else{
               $totime = $time;
             } 
                
    
             if($time!="null" && $time!="00:00:00"){ 
                $totime = $time;
             }

             if($activedeactivestatus=="null"){ 
                $status=1;
            }
            else{
               $status=$activedeactivestatus;   
            }


            $filters['practice'] = $p;
            $filters['patient'] = $pt; 
            $filters['timeoption'] = $timeoption;
            $filters['time'] = $totime;
            $filters['patientstatus'] = $status; 
            
             
            $data = array(
                'user_id'         =>  $cid,
                'module_id'       =>  $pagemodule_id,
                'submodule_id'    =>  $pagesubmodule_id,
                'filters'         =>  json_encode($filters),
            );
            $check =  UserFilters::where('user_id',$cid)->get();
           
            if(count($check)>0){ 
               
                $update = UserFilters::where('user_id',$cid)->update($data);
            }
            else{
           
                $insert = UserFilters::create($data);  
            }
              
        

        }
   
    }
    
	

     
     //created by ashvini and modified on 17thOct2022  
    public function getUserListDataAll(Request $request)          
      {		
          //return null;
          if ($request->ajax()) {
              // $pagemodule_id = getPageModuleName();
              // $pagesubmodule_id =getPageSubModuleName();
              $cid = session()->get('userid');
              $practices = sanitizeVariable($request->route('practice_id'));
              $patient = sanitizeVariable($request->route('patient_id'));
              $module   = sanitizeVariable($request->route('module_id'));
              $timeoption = sanitizeVariable($request->route('timeoption'));
              $time = sanitizeVariable($request->route('time'));
              $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus')); 
              $usersdetails = Users::where('id',$cid)->get();
              $roleid = $usersdetails[0]->role;
              $monthly = Carbon::now();
              $monthlyto = Carbon::now();
              $year = date('Y', strtotime($monthly));
              $month = date('m', strtotime($monthly));
              $configTZ = config('app.timezone');
              $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
              $p;
              $pt;
              $totime; 
              $status;
              $iconcolor;
              $icontitle ;    
              $check_months = [];
              $is_red_or_green_or_yellow = 0;
                
              if( $practices!='null') {
                  if( $practices==0){
                      $p = 'null';  
                  } else{
                      $p = $practices;
                  }    
              } else{
                  $p = 'null';
              }
  
              if($patient!="null"){ 
                  $pt = $patient; 
              } else {
                  $pt = "null";
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
  
              if($activedeactivestatus=="null"){  
                  $status="null";
              } else {
                  $status=$activedeactivestatus;  
              }   
  
              if($module=="null"){
                  $module_id = "null";
              } else { 
                  $module_id = $module;  
              }
                 
              $run_score_procedure = 0;
  
              //$query = "select pid, pfname, plname, pmname, pprofileimg, pdob, pppracticeemr,ppracticeid, pracpracticename,pfromdate,ptodate,to_char(csslastdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csslastdate  , pstatus, ptrtotaltime from 
              //patients.worklist_v2($p,$pt,$month, $year,$timeoption,'".$totime."',$roleid, $cid,'".$configTZ ."','".$userTZ."',$status,$module_id)"; 
              
             if( $roleid == 5  ){
  
                  $query = "select pid, pfname, plname, pmname, pprofileimg, pdob, pppracticeemr,ppracticeid, pracpracticename,pfromdate,
                            ptodate,to_char(csslastdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csslastdate  , 
                            pstatus, ptrtotaltime,psmodule_id , cciconcolor,ccicontitle,pssscore from 
                            patients.worklist_v2($p,$pt,$month, $year,$timeoption,'".$totime."',$roleid, $cid,'".$configTZ ."','".$userTZ."',$status,$module_id)";  
              } else if($roleid == 2 || $roleid == 6 || $roleid ==10){
  
                  $query = "select pid, pfname, plname, pmname, pprofileimg, pdob, pppracticeemr,ppracticeid, pracpracticename,pfromdate,
                            ptodate,to_char(csslastdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csslastdate  , 
                            pstatus, ptrtotaltime,psmodule_id , cciconcolor,ccicontitle,pssscore from 
                            patients.worklist_admin_v2($p,$pt,$month, $year,$timeoption,'".$totime."',$roleid, $cid,'".$configTZ ."','".$userTZ."',$status,$module_id)";  
              }else { 
  
                          // $query = "select pid, pfname, plname, pmname, pprofileimg, pdob, pppracticeemr,ppracticeid, 
                          // 		  pracpracticename,pfromdate,ptodate,to_char(csslastdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csslastdate  ,
                          // 		  pstatus, ptrtotaltime,psmodule_id ,ccdiagnosis_count, ccreview_age_green,ccreview_age_yellow,iconcolor,icontitle from 
                          // 		  patients.worklist_tlm($p,$pt,$month, $year,$timeoption,'".$totime."',$roleid, $cid,'".$configTZ ."','".$userTZ."',$status,$module_id)";
  
                            $query = "select pid, pfname, plname, pmname, pprofileimg, pdob, pppracticeemr,ppracticeid, pracpracticename,pfromdate,
                            ptodate,to_char(csslastdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csslastdate  , 
                            pstatus, ptrtotaltime,psmodule_id , cciconcolor,ccicontitle,pssscore from 
                            patients.worklist_tlm_v2($p,$pt,$month, $year,$timeoption,'".$totime."',$roleid, $cid,'".$configTZ ."','".$userTZ."',$status,$module_id)";  
              }
                       
  
              // $data = DB::select( DB::raw($query) );  
              if($roleid  == 2) {
                  if($module!="null" || $p!="null" || $pt!="null"){   
                      $data = DB::select(DB::raw($query));
                  } else {
                      $data = [];  
                  }
              } else{
                  $data = DB::select(DB::raw($query));    
              } 
  
  
             foreach($data as $d){
              if($d->pssscore==0 || $d->pssscore==0){
                  $run_score_procedure = 1;
              }
             }
          
             if($run_score_procedure == 1 || $run_score_procedure == '1'){
             
            
              $query2 = "select * from patients.generate_patient_score()";   
              $data2 = DB::select( DB::raw($query2) );
              
              $run_score_procedure = 0;    
             }
             
              return Datatables::of($data)
              ->addIndexColumn()
              ->addColumn('action', function($row)
              { 
                  $check = DB::table('patients.patient_diagnosis_codes')   
                  ->where('patient_id',$row->pid)
                  ->latest()
                  ->first();  
  
                  $PatientService = PatientServices::select("*")
                  ->with('module')
                  ->whereHas('module', function($q) {
                      $q->where('module', '=', 'RPM'); // '=' is optional
                  })
                  ->where('patient_id',$row->pid)
                  ->where('status', 1)
                  ->exists(); 
                  if ($PatientService == true)  {
                      if($check == "" || $check == null ){ 
                          $btn1 ='<a href="/rpm/care-plan-development/'.$row->pid.'" title="Action" >Care Plan </a>'; //cpd
                          if($row->cciconcolor == null || $row->cciconcolor == ''){
                              $btn2 = '';
                          }else{
                            
                              if($row->cciconcolor == 'green' || $row->cciconcolor == "green"){
                                  $row->cciconcolor = '#33ff33'; 
                              }
                              $btn2 ='<a href="javascript:void(0)" data-toggle="tooltip"  title = "'.$row->ccicontitle.'" ><i class="i-Closee i-Data-Yes" style="color: '.$row->cciconcolor.' ; cursor: pointer;"></i></a>';  
                             }
                          
                          $btn =  $btn1." ".$btn2;
                      } else {
                         
                          $btn1 ='<a href="/rpm/monthly-monitoring/'.$row->pid.'" title="Action" >CCM </a>'; //monthly
                          if($row->cciconcolor == null || $row->cciconcolor == ''){
                              $btn2 = '';
                          }else{
                                  if($row->cciconcolor == 'green' || $row->cciconcolor == "green"){
                                      $row->cciconcolor = '#33ff33'; 
                                  }
                                  $btn2 ='<a href="javascript:void(0)" data-toggle="tooltip"   title = "'.$row->ccicontitle.'" ><i class="i-Closee  i-Data-Yes" style="color: '.$row->cciconcolor.' ; cursor: pointer;"></i></a>';    
                          }                       
                          $btn =  $btn1." ".$btn2;
                      }
                  } else {
                      if($check == "" || $check == null ){ 
                          $btn1 ='<a href="/ccm/care-plan-development/'.$row->pid.'" title="Action" >Care Plan</a>'; //cpd
                          if($row->cciconcolor == null || $row->cciconcolor == ''){
  
                              $btn2 = '';
  
                          }else{  
                              
                                  if($row->cciconcolor == 'green' || $row->cciconcolor == "green"){
                                      $row->cciconcolor = '#33ff33'; 
                                  }
                                  $btn2 ='<a href="javascript:void(0)" data-toggle="tooltip"   title = "'.$row->ccicontitle.'" ><i class="i-Closee  i-Data-Yes" style="color: '.$row->cciconcolor.' ; cursor: pointer;"></i></a>';  
                             }
                           
                          $btn =  $btn1." ".$btn2;
                      
                      } else {
                         
  
                          $btn1 ='<a href="/ccm/monthly-monitoring/'.$row->pid.'" title="Action" >CCM </a>'; //monthly
  
                          if($row->cciconcolor == null || $row->cciconcolor == '' ){
                              $btn2 = '';
                          }else{  
                                
                              if($row->cciconcolor == 'green' || $row->cciconcolor == "green"){
                                  $row->cciconcolor = '#33ff33'; 
                              }
                                $btn2 ='<a href="javascript:void(0)" data-toggle="tooltip"  title ="'.$row->ccicontitle.'" ><i class="i-Closee  i-Data-Yes" style="color: '.$row->cciconcolor.' ; cursor: pointer;"></i></a>';  
  
                         }
                          
                          $btn =  $btn1." ".$btn2; 
                      }  
                  }  
                 
                  return $btn;  
              })
  
              ->addColumn('activedeactive', function($row){
                
                $formattedDate = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
                if ($row->pstatus == 1 && $row->pstatus != 0 && $row->pstatus != 2 && $row->pstatus != 3) {
                    $btn = '<a href="javascript:void(0)" class="ActiveDeactiveClass" data-toggle="modal"
                            onclick="ccmcpdcommonJS.onActiveDeactiveClick(\'' . $row->pid . '\', \'' . $row->pstatus . '\', \'' . $formattedDate . '\')" data-target="#active-deactive"  id="active_deactive">             
                            <i class="i-Yess i-Yes"  title="Patient Status"></i></a>';
                } else { 
                    $btn = '<a href="javascript:void(0)" class="ActiveDeactiveClass" data-toggle="modal"
                            onclick="ccmcpdcommonJS.onActiveDeactiveClick(\'' . $row->pid . '\', \'' . $row->pstatus . '\', \'' . $formattedDate . '\')" data-target="#active-deactive"  id="active_deactive"> 
                            <i class="i-Closee i-Close" title="Patient Status"></i></a>';
                }
                return $btn;
                 
              })
              ->addColumn('addaction', function($row){
                  $btn ='<a href="javascript:void(0)"  data-toggle="modal" data-id="'.$row->pid.'/'.$row->ptrtotaltime.'/'.$row->ppracticeid.'/'.$row->psmodule_id.'" data-target="#add-activities" id="add-activity"
                  data-original-title="Patient Activity" class="patient_activity" title="Patient Activity"><i class="text-20 i-Stopwatch" style="color: #2cb8ea;"></i></a>';
                  return $btn; 
              }) 
              ->rawColumns(['action','activedeactive','addaction'])  
              ->make(true);
              
  
          }
          
      }

	/*public function addCarePlanAge(Request $request){
		PatientCareplanAge::truncate();
		$generate_careplan_age = DB::select('SELECT patients.generate_patient_careplan_age()');
		return 1;		
	}*/

    public function addCarePlanAge()
    {
        $query =    "select 
                    cc.patient_id, 
                    cc.diagnosis_count,
                    cc.review_age_green, 
                    cc.review_age_yellow,
                    cc.update_age_years,
                    CASE
                    when ((cc.diagnosis_count = cc.review_age_green) and (cc.review_age_green = 0 )) 
                    or (cc.update_age_years >= 1) THEN 'red'
                    when ( cc.review_age_yellow > 1 ) or (cc.update_age_years >= 1)   THEN 'red'
                    when (cc.review_age_green = 0 and cc.review_age_yellow = 0 )  THEN 'green'
                    when ( cc.review_age_green > 0 and cc.review_age_yellow = 0 )   THEN 'yellow'  
                    ELSE null 
                    end AS result,
                    CASE
                    when ((cc.diagnosis_count = cc.review_age_green) and (cc.review_age_green = 0 )) 
                    or (cc.update_age_years >= 1)  THEN 'All the Care Plans not reviewed for more than 6 months or
                    Aleast one Care Plan not reviewed for more than 6 months or
                    Aleast one Care Plan not updated for more than a year'	

                    when ( cc.review_age_yellow > 1 ) or (cc.update_age_years >= 1)  
                    THEN 'All the Care Plans not reviewed for more than 6 months or
                    Aleast one Care Plan not reviewed for more than 6 months or
                    Aleast one Care Plan not updated for more than a year'
                                                            				
                    when (cc.review_age_green = 0 and cc.review_age_yellow = 0 )  
                    THEN 'All the Care Plans have been reviewed within 6 months'
                    when ( cc.review_age_green > 0) and (cc.review_age_yellow = 0 )  
                    THEN 'Aleast one Care Plan not reviewed for more than 6 months'  
                    ELSE null
                    end AS resulttitle
                    from 
                    (SELECT 
                    patient_id, 
                    count(diagnosis_id)::float as diagnosis_count,
                    sum( case when
                            ((date_part ('year'::text , age(now(), patient_careplan_last_update_n_review.review_date::timestamp with time zone))::float *12 + 
                            date_part ('month'::text , age(now(), patient_careplan_last_update_n_review.review_date::timestamp with time zone))::float +
                            (date_part ('day'::text , age(now(), patient_careplan_last_update_n_review.review_date::timestamp with time zone))::float /100 )::float
                            )::float)
                            < 6 then 0 else 1 end
                        ) as review_age_green,      
                    sum( case when
                        ((date_part ('year'::text , age(now(), patient_careplan_last_update_n_review.review_date::timestamp with time zone))::float *12 + 
                        date_part ('month'::text , age(now(), patient_careplan_last_update_n_review.review_date::timestamp with time zone))::float +
                        (date_part ('day'::text , age(now(), patient_careplan_last_update_n_review.review_date::timestamp with time zone))::float /100 )::float
                        )::float)
                            < 6 then 0 else 1 end  
                        ) as review_age_yellow, 
                    sum( case when
                        ((date_part ('year'::text , age(now(), patient_careplan_last_update_n_review.update_date::timestamp with time zone))::float *12 + 
                        date_part ('month'::text , age(now(), patient_careplan_last_update_n_review.update_date::timestamp with time zone))::float +
                        (date_part ('day'::text , age(now(), patient_careplan_last_update_n_review.update_date::timestamp with time zone))::float /100 )::float
                        )::float)
                            < 12 then 0 else 1 end
                        ) as update_age_years 
                        
                    FROM patients.patient_careplan_last_update_n_review         
                        
                    WHERE patient_careplan_last_update_n_review.status = 1 
                    
                    group by patient_id) cc" ;  
                    
        // dd($query);   
        $data = DB::select( DB::raw($query) );
         
        

        
        
        foreach($data as $d){
            $mydata = array( 'patient_id' =>$d->patient_id ,       
                            'diagnosis_id_count' =>$d->diagnosis_count,
                            'review_age_green'=> $d->review_age_green, 
                            'review_age_yellow'=>$d->review_age_yellow,
                            'update_age_years'=>$d->update_age_years,
                            'iconcolor'=>$d->result,
                            'icontitle'=>$d->resulttitle,
                            'created_by'=>1,
                            'updated_by'=>1,
                            'status'=>1 );

                            $check = PatientCareplanAge::where('patient_id',$d->patient_id)->exists();
                            if($check){
                                PatientCareplanAge::where('patient_id',$d->patient_id)->update($mydata);
                            }else{
                                PatientCareplanAge::create($mydata);
                            }

            
        }


        
    }

/*************************************************************ashvini arumugam changes***********************/
public function reschdule_tasks_sp()
{
    $module_id = 3;
    $component_id= 19;
    $stage_id =0;
    $step_id = 0;
    $month = 10;
    $firstday = date('Y-m-d',strtotime('first day of this month'));
    $lastday = date('Y-m-d',strtotime('last day of this month'));
    
    $monthStartDate = $firstday;
    $monthEndDate = $lastday;
    $nearing_20mins = "select * from patients.reschdule_tasks( timestamp '".$monthStartDate."' , timestamp '".$monthEndDate."')";

    $nearing_20minsResults = DB::select(DB::raw($nearing_20mins));  
    if(count($nearing_20minsResults)>0){
        foreach($nearing_20minsResults as $res){
        $user_id = $res->created_by;
        // $patient_id = $res->patient_id;
        $patient_id = $res->pid;
        $getuseravailableschedule = "select * from patients.reschedule_tasks_date($userid,timestamp '".$monthStartDate."', timestamp '".$monthEndDate."')";
                $scheduleSQLresult = DB::select( DB::raw($user_available_schedule));
            $data = array();
            if(count($scheduleSQLresult)>0){
                $schedule_day_pref = 0;
                $schedule_time_pref = 0;
                $score = 0;				
                    foreach($scheduleSQLresult as $pr){
                        $data = array(
                                        'uid'                         => $patient_id,
                                        'module_id'                   => $module_id,
                                        'component_id'                => $component_id,
                                        'stage_id'                    => $stage_id,
                                        'step_id'                     => $step_id,
                                        'task_notes'                  => "CCM monthly call",
                                        'task_date'                   => $pr->dddate_actual,
                                        'task_time'                   => $pr->wworkinghour,
                                        'assigned_to'                 => $user_id,
                                        'assigned_on'                 => Carbon::now(),
                                        'status'                      => 'Pending',
                                        'status_flag'                 => 0,
                                        'created_by'                  => 1,
                                        'patient_id'                  => $patient_id,
                                        'weekday'					  => $pr->ddday_of_week,
                                        'schedule_day_pref'			  => $schedule_day_pref,
                                        'schedule_time_pref'		  => $schedule_time_pref,								
                                        'score'		                  => $score,	
                                        'etl_flag'		              => 4								
                                    );
                                    $insert = ToDoList::create($data);	
                                    echo "added entry for user".$user_id." patient ".$patient_id." from daily logic 1st condition on ".$pr->dddate_actual." at ".$pr->wworkinghour;
                                    break; 

                    }	
        
            }
        }
        /* end of patients whose time is between 15 to 20 mins */
    }
    
        /* reschedule all previous pending tasks */
        $pendingtasks = "select patient_id, assigned_to, score from task_management.to_do_list tdl 
                        where date(task_date) < date(now()) and (status = 'pending' or status_flag=0)
                        and extract(month from task_date) = extract(month from now())
                        and extract(year from task_date) = extract(year from now())
                        and patient_id not in 
(select distinct patient_id from task_management.to_do_list tdl where date(task_date)> date(now()) and date(task_date) < '".$monthEndDate."') order by score desc";
        $pendingtasksResults = DB::select( DB::raw($pendingtasks));
        if(count($pendingtasksResults)>0){	
            foreach($pendingtasksResults as $r){
                $score = $r->score;
                $patient_id = $r->patient_id;
                $user_id = $r->assigned_to;
                
                $getuseravailableschedule = "select * from patients.reschedule_tasks_date($user_id,timestamp '".$monthStartDate."', timestamp '".$monthEndDate."')";

            $scheduleSQLresult = DB::select( DB::raw($getuseravailableschedule));
            $data = array();
            if(count($scheduleSQLresult)>0){
                $schedule_day_pref = 0;
                $schedule_time_pref = 0;
                
                        foreach($scheduleSQLresult as $pr){
                        $data = array(
                                        'uid'                         => $patient_id,
                                        'module_id'                   => $module_id,
                                        'component_id'                => $component_id,
                                        'stage_id'                    => $stage_id,
                                        'step_id'                     => $step_id,
                                        'task_notes'                  => "CCM monthly call",
                                        'task_date'                   => $pr->dddate_actual,
                                        'task_time'                   => $pr->wworkinghour,
                                        'assigned_to'                 => $user_id,
                                        'assigned_on'                 => Carbon::now(),
                                        'status'                      => 'Pending',
                                        'status_flag'                 => 0,
                                        'created_by'                  => 1,
                                        'patient_id'                  => $patient_id,
                                        'weekday'					  => $pr->ddday_of_week,
                                        'schedule_day_pref'			  => $schedule_day_pref,
                                        'schedule_time_pref'		  => $schedule_time_pref,								
                                        'score'		                  => $score,	
                                        'etl_flag'		              => 5								
                                    );
                                    $insert = ToDoList::create($data);	
                                    echo "added entry for user".$user_id." patient ".$patient_id." from daily logic 1st condition on ".$pr->dddate_actual." at ".$pr->wworkinghour;
                                    break; 

        }
            
        }
        

            }
        
        }
        
    
    
}

public function createTaskList_sp_bkup(){
    // dd('monthly');  
    $module_id = 3;
    $component_id= 19;
    $stage_id =0;
    $step_id = 0;
    $month = 10;
    
    $firstday = date('Y-m-d',strtotime('first day of this month'));
    $lastday = date('Y-m-d',strtotime('last day of this month'));

    // dd($firstday,  $lastday);    



    $monthStartDate = $firstday;
    $monthEndDate = $lastday;
    $cid = session()->get('userid');

    // dd( $monthStartDate , $monthEndDate);
    
   

    $patientspreferrenceSQL = "select * from task_management.createtasklist(timestamp '".$monthStartDate."', timestamp '".$monthEndDate."')";   

echo $patientspreferrenceSQL;
    
    $patientspreferrenceResults = DB::select( DB::raw($patientspreferrenceSQL));
    $timearr = ['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00'];	
    
    $resarr = array();
    $user1 = 0;
    
    //echo $start_date;
    //echo "<pre>";
    $schedule_arr = [];
    $preferreddays = [];
    $j = 0;
    $i = 0;
    
    foreach($patientspreferrenceResults as $r1){        
        
        // $patient_id = $r1->patient_id; 
        $patient_id = $r1->pid;
        $user_id = $r1->user_id;
        $score = $r1->call_score;        

        $patient_pref = "select * from patients.createlist_patientpreference($patient_id, timestamp '".$monthStartDate."', timestamp '".$monthEndDate."','1 hour','hour',$user_id)";

        $prefSQLresult = DB::select( DB::raw($patient_pref));
        
        $prefcounter = 0;
        if(count($prefSQLresult)>0){
            echo "prefSQLresult count ".count($prefSQLresult);
            foreach($prefSQLresult as $pr){
                if($pr->tttaskdate == ''){
                    echo $user_id." ".$patient_id ." ".$pr->dddays." task_date value".$pr->tttaskdate. "task time from t1".$pr->time1."|<br>";
                    
                    $schedule_day_pref = 1;
                    if($pr->tttimepref=='any'){
                        $schedule_time_pref = 0;
                        $preftime = '08:00:00';
                    }else{
                        $schedule_time_pref = 1;
                        $preftime = $pr->time1;
                    }
                    //$schedule_arr['assigned_to'] = $user_id;
                    
                    $data = array(
                                'uid'                         => $patient_id,
                                'module_id'                   => $module_id,
                                'component_id'                => $component_id,
                                'stage_id'                    => $stage_id,
                                'step_id'                     => $step_id,
                                'task_notes'                  => "CCM monthly call",
                                'task_date'                   => $pr->dateactual,
                                'task_time'                   => $preftime,
                                'assigned_to'                 => $user_id,
                                'assigned_on'                 => Carbon::now(),
                                'status'                      => 'Pending',
                                'status_flag'                 => 0,
                                'created_by'                  => 1,
                                'patient_id'                  => $patient_id,
                                'weekday'					  => $pr->cccontactday,
                                'schedule_day_pref'			  => $schedule_day_pref,
                                'schedule_time_pref'		  => $schedule_time_pref,
                                'score'		  				  => $score,
                                'etl_flag'		 			  => 1,
								'etl_title' 					=> 'scheduler task step 1',
								'etl_notes'						=>'task for patient with preference on available slot'
                                
                            );
                            $insert = ToDoList::create($data);	
                            echo "added entry for user".$user_id." patient ".$patient_id." from 1 logic on ".$pr->dateactual." at ".$preftime;
                            $prefcounter++;
                    break;
                }else{
                    $res = array_map(function ($value) {
                            return (array)$value;
                        }, $prefSQLresult);
                    echo "<pre>";
                    //$preferreddays = array_column($res, 'task_date');
                    $schedule_day_pref = 1;
                    
                    
                    echo $user_id." ".$patient_id ." task_date found value".$pr->tttaskdate." working hour available".$pr->wwworkinghour."|<br>";
                    if($pr->tttaskdate !='' and $pr->wwworkinghour!=''){
                        
                    if($pr->tttimepref=='any'){
                        $schedule_time_pref = 0;
                    }else{
                        $schedule_time_pref = 1;
                    }
                    
                    $data = array(
                                'uid'                         => $patient_id,
                                'module_id'                   => $module_id,
                                'component_id'                => $component_id,
                                'stage_id'                    => $stage_id,
                                'step_id'                     => $step_id,
                                'task_notes'                  => "CCM monthly call",
                                'task_date'                   => $pr->dateactual,
                                'task_time'                   => $pr->wwworkinghour,
                                'assigned_to'                 => $user_id,
                                'assigned_on'                 => Carbon::now(),
                                'status'                      => 'Pending',
                                'status_flag'                 => 0,
                                'created_by'                  => 1,
                                'patient_id'                  => $patient_id,
                                'weekday'					  => $pr->cccontactday,
                                'schedule_day_pref'			  => $schedule_day_pref,
                                'schedule_time_pref'		  => $schedule_time_pref,
                                'score'		  	              => $score,
                                'etl_flag'		              => 2,
								'etl_title' => 'scheduler task step 2',
								'etl_notes'=>'task for patient with preference on available slot in step 2'
                            );
                            $insert = ToDoList::create($data);	
                            //echo "added entry for patient ".$patient_id;
                            echo "added entry for user".$user_id." patient ".$patient_id." from 2 logic on ".$pr->dateactual." at ".$pr->wwworkinghour;
                            $prefcounter++;
                            break;
                    }elseif(count($prefSQLresult)!=$prefcounter){
                        echo "check next available slot";
                        $prefcounter++;
                    }else{
                        echo "all pref slots of patients are full ";
                        $patient_extra_pref = "select * from patients.createlist_patientpreference($patient_id, timestamp '".$monthStartDate."', timestamp '".$monthEndDate."','30 min','min',$user_id)";
                        echo $patient_extra_pref;


                        
                        
                        
                                    //break;
                    }
                    
                    
                    
                }
            }
        }
        else{
            // call for patient is already scheduled in scheduler
            
        }
        
        $i++;
        $j++;			
        
    }

        
    $sql = "select * from patients.createtasklist(timestamp '".$monthStartDate."', timestamp '".$monthEndDate."')";	
    $results = DB::select( DB::raw($sql));
    //$date = Carbon::now();
    //$date->modify('first day of next month');
    //echo $date->format('Y-m-d');
    //$start_date = $date->format('Y-m-d');
    
    //$i=0;
    //$schedule_date = $start_date;
    
    foreach($results as $r){
        $user_id = $r->user_id;
        // $patient_id = $r->patient_id;
        $patient_id = $r->pid;
        $score = $r->call_score;
        $user_available_schedule = "select * from task_management.get_user_available_slot($user_id , $monthStartDate,  $monthEndDate , '1' , 'hour' )";
            $scheduleSQLresult = DB::select( DB::raw($user_available_schedule));
            $data = array();
            if(count($scheduleSQLresult)>0){
                $schedule_day_pref = 0;
                $schedule_time_pref = 0;
            
                    foreach($scheduleSQLresult as $pr){
                        $data = array(
                                        'uid'                         => $patient_id,
                                        'module_id'                   => $module_id,
                                        'component_id'                => $component_id,
                                        'stage_id'                    => $stage_id,
                                        'step_id'                     => $step_id,
                                        'task_notes'                  => "CCM monthly call",
                                        'task_date'                   => $pr->date_actual,
                                        'task_time'                   => $pr->workinghour,
                                        'assigned_to'                 => $user_id,
                                        'assigned_on'                 => Carbon::now(),
                                        'status'                      => 'Pending',
                                        'status_flag'                 => 0,
                                        'created_by'                  => 1,
                                        'patient_id'                  => $patient_id,
                                        'weekday'					  => $pr->day_of_week,
                                        'schedule_day_pref'			  => $schedule_day_pref,
                                        'schedule_time_pref'		  => $schedule_time_pref,								
                                        'score'		  => $score,	
                                        'etl_flag'		  => 3,
										'etl_title' => 'scheduler task step 3',
										'etl_notes'=>'task for patient without preference on available slot step 3'
                                    );
                                    $insert = ToDoList::create($data);	
                                    echo "added entry for user".$user_id." patient ".$patient_id." from 3 logic on ".$pr->date_actual." at ".$pr->workinghour;
                                    break;
                    }
                }else{
                    
                    echo "slots not available. schedule made at 30 min interval for patients without preference";

         $user_available_schedule = "select * from task_management.get_user_available_slot($user_id , $monthStartDate,  $monthEndDate , '30' , 'min' )";
            $scheduleSQLresult = DB::select( DB::raw($user_available_schedule));
            $data = array();
            if(count($scheduleSQLresult)>0){
                $schedule_day_pref = 0;
                $schedule_time_pref = 0;
            
                    foreach($scheduleSQLresult as $pr){
                        $data = array(
                                        'uid'                         => $patient_id,
                                        'module_id'                   => $module_id,
                                        'component_id'                => $component_id,
                                        'stage_id'                    => $stage_id,
                                        'step_id'                     => $step_id,
                                        'task_notes'                  => "CCM monthly call",
                                        'task_date'                   => $pr->date_actual,
                                        'task_time'                   => $pr->workinghour,
                                        'assigned_to'                 => $user_id,
                                        'assigned_on'                 => Carbon::now(),
                                        'status'                      => 'Pending',
                                        'status_flag'                 => 0,
                                        'created_by'                  => 1,
                                        'patient_id'                  => $patient_id,
                                        'weekday'					  => $pr->day_of_week,
                                        'schedule_day_pref'			  => $schedule_day_pref,
                                        'schedule_time_pref'		  => $schedule_time_pref,								
                                        'score'		                  => $score,	
                                        'etl_flag'		              => 4,
                                        'etl_title' =>'step 4 No preference, Schedule at 30 min',
                                        'etl_notes' =>'slots not available. schedule made at 30 min interval for patients without preference',
                                    );
                                    $insert = ToDoList::create($data);	
                                    echo "added entry for user".$user_id." patient ".$patient_id." from 4 logic on ".$pr->date_actual." at ".$pr->workinghour;
                                    break;
                    }
                    


                }
        
    }
    
}


}

public function createTaskList_sp(){
	    // dd('monthlynew');     
                $module_id = 3;
                $component_id= 19;
                $stage_id =0;
                $step_id = 0;
                $month = 10;
                $scheduler_run_value = 0;

                $firstday = date('Y-m-d',strtotime('first day of next month'));
                $lastday = date('Y-m-d',strtotime('last day of next month'));

                $firstday = $firstday." ".'00:00:00';
                $lastday =  $lastday." ".'23:59:59'; 

               // $firstday = "2023-02-01 00:00:00";
                //$lastday =  "2023-02-28 23:59:59";  

                // $firstday = "2023-02-01";
                // $lastday =  "2023-02-28";  
                
                // dd($firstday, $lastday);    

                $monthStartDate = $firstday;
                $monthEndDate = $lastday;
                $cid = session()->get('userid');


                $patientspreferrenceSQL = "select * from task_management.createtasklist(timestamp '".$monthStartDate."', timestamp '".$monthEndDate."') ";   

                echo $patientspreferrenceSQL;
                
                $patientspreferrenceResults = DB::select( DB::raw($patientspreferrenceSQL));
                $timearr = ['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00'];	
                
                $resarr = array();
                $user1 = 0;
                
                //echo $start_date;
                //echo "<pre>";
                $schedule_arr = [];
                $preferreddays = [];
                $j = 0;
                $i = 0;
                
                foreach($patientspreferrenceResults as $r1){        
                    
                    // $patient_id = $r1->patient_id; 
                    $patient_id = $r1->pid;
                     $user_id = $r1->user_id;
                    // $user_id = 124;
                    //$user_id = 98;
                    $score = $r1->call_score;        
                    $patient_pref = "select * from task_management.createlist_patientpreference($patient_id, timestamp '".$monthStartDate."', timestamp '".$monthEndDate."','1 hour','hour',$user_id)";

                    $prefSQLresult = DB::select( DB::raw($patient_pref));
                    
                    $prefcounter = 0;
                    if(count($prefSQLresult)>0){
                        echo "prefSQLresult count ".count($prefSQLresult);
                        foreach($prefSQLresult as $pr){
                            if($pr->tttaskdate == ''){
                                echo $user_id." ".$patient_id ." ".$pr->dddays." task_date value".$pr->tttaskdate. "task time from t1".$pr->time1."|<br>";
                                
                                $schedule_day_pref = 1;
                                if($pr->tttimepref=='any'){
                                    $schedule_time_pref = 0;
                                    $preftime = '08:00:00';
                                }else{
                                    $schedule_time_pref = 1;
                                    $preftime = $pr->time1;
                                }
                                //$schedule_arr['assigned_to'] = $user_id;
                                $d = explode(" ", $pr->dateactual);
                                $d1 = $d[0]; 
                               
                                $newtask_date = $d1." ".$preftime; 
                                $converted_task_date = $d1." ".$preftime; 
                               // $converted_task_date = DatesTimezoneConversion::userToConfigTimeStamp($newtask_date);
                              

                                
                                $data = array(
                                            'uid'                         => $patient_id,
                                            'module_id'                   => $module_id,
                                            'component_id'                => $component_id,
                                            'stage_id'                    => $stage_id,
                                            'step_id'                     => $step_id,
                                            'task_notes'                  => "CCM monthly call",
                                            // 'task_date'                   => $pr->dateactual,
                                            'task_date'                   => $converted_task_date,
                                            'task_time'                   => $preftime,
                                            'assigned_to'                 => $user_id,
                                            'assigned_on'                 => Carbon::now(),
                                            'status'                      => 'Pending',
                                            'status_flag'                 => 0,
                                            'created_by'                  => 1,
                                            'patient_id'                  => $patient_id,
                                            'weekday'					  => $pr->cccontactday,
                                            'schedule_day_pref'			  => $schedule_day_pref,
                                            'schedule_time_pref'		  => $schedule_time_pref,
                                            'score'		  				  => $score,
                                            'etl_flag'		 			  => 1,
                                            'etl_title' 					=> 'scheduler task step 1',
                                            'etl_notes'						=>'task for patient with preference on available slot'
                                            
                                        );
                                    
                                $insert = ToDoList::create($data);	
                                if($insert->id != null && $insert->id != "" && $insert->id != '' ){
                                    $scheduler_run_value = 1;
                                    \Log::info("Scheduler in 1st logic user".$user_id." patient ".$patient_id);
                                    echo "scheduler in 1st logic and scheduler_run_value=1";
                                }
                                else{
                                    \Log::info("Scheduler in 1st logic user".$user_id." patient ".$patient_id." failed");
                                }
                                echo "added entry for user".$user_id." patient ".$patient_id." from 1 logic on ".$pr->dateactual." at ".$preftime;
                                $prefcounter++;
                                break;
                            }else{
                                $res = array_map(function ($value) {
                                        return (array)$value;
                                    }, $prefSQLresult);
                                echo "<pre>";
                                //$preferreddays = array_column($res, 'task_date');
                                $schedule_day_pref = 1;
                                
                                
                                echo $user_id." ".$patient_id ." task_date found value".$pr->tttaskdate." working hour available".$pr->wwworkinghour."|<br>";
                                if($pr->tttaskdate !='' and $pr->wwworkinghour!=''){
                                    
                                if($pr->tttimepref=='any'){
                                    $schedule_time_pref = 0;
                                }else{
                                    $schedule_time_pref = 1;
                                }

                                //$d = explode(" ", $pr->dateactual);
								$d1 = $pr->dateactual;
                                //$d1 = $d[0]; 
                               
                                $newtask_date = $d1." ".$pr->wwworkinghour;
                                $converted_task_date = $d1." ".$pr->wwworkinghour;
                                // $newtask_date = $pr->dateactual." ".$pr->wwworkinghour;
                               // $converted_task_date = DatesTimezoneConversion::userToConfigTimeStamp($newtask_date);

                                $data = array(
                                            'uid'                         => $patient_id,
                                            'module_id'                   => $module_id,
                                            'component_id'                => $component_id,
                                            'stage_id'                    => $stage_id,
                                            'step_id'                     => $step_id,
                                            'task_notes'                  => "CCM monthly call",
                                            // 'task_date'                   => $pr->dateactual,
                                            'task_date'                   => $converted_task_date,
                                            'task_time'                   => $pr->wwworkinghour,
                                            'assigned_to'                 => $user_id,
                                            'assigned_on'                 => Carbon::now(),   
                                            'status'                      => 'Pending',
                                            'status_flag'                 => 0,
                                            'created_by'                  => 1,
                                            'patient_id'                  => $patient_id,
                                            'weekday'					  => $pr->cccontactday,
                                            'schedule_day_pref'			  => $schedule_day_pref,
                                            'schedule_time_pref'		  => $schedule_time_pref,
                                            'score'		  	              => $score,
                                            'etl_flag'		              => 2,
                                            'etl_title' => 'scheduler task step 2',
                                            'etl_notes'=>'task for patient with preference on available slot in step 2'
                                        );
                                        $insert = ToDoList::create($data);	
                                        //echo "added entry for patient ".$patient_id;
                                    if($insert->id != null && $insert->id != "" && $insert->id != '' ){
                                        $scheduler_run_value = 1;
                                        \Log::info("Scheduler in 2nd logic");
                                        echo "scheduler in 2nd logic and scheduler_run_value=1";
                                    }
                                    echo "added entry for user".$user_id." patient ".$patient_id." from 2 logic on ".$pr->dateactual." at ".$pr->wwworkinghour;
                                    $prefcounter++;
                                    break;
                                }elseif(count($prefSQLresult)!=$prefcounter){
                                    echo "check next available slot";
                                    $prefcounter++;
                                }else{
                                    echo "all pref slots of patients are full ";
                                    $patient_extra_pref = "select * from patients.createlist_patientpreference($patient_id, timestamp '".$monthStartDate."', timestamp '".$monthEndDate."','30 min','min',$user_id)";
                                    echo $patient_extra_pref;
                                    break;

                                    
                                    
                                    
                                                //
                                }
                                
                                
                                
                            }
                        }
                    }
                    else{
                        // call for patient is already scheduled in scheduler
                        
                    }
                    
                    $i++;
                    $j++;			
                    
                }

                

                // $sql = "select * from task_management.createtasklist(timestamp '".$monthStartDate."', timestamp '".$monthEndDate."')"; changed and modified on 20th feb 2023 ashvini bharti
                $sql = "select * from task_management.get_patients_withoutpreference(timestamp '".$monthStartDate."', timestamp '".$monthEndDate."')";
                    
                $results = DB::select( DB::raw($sql));
                //$date = Carbon::now();
                //$date->modify('first day of next month');
                //echo $date->format('Y-m-d');
                //$start_date = $date->format('Y-m-d');
                
                //$i=0;
                //$schedule_date = $start_date;
                
                foreach($results as $r){
                    // $user_id = 124;
                   // $user_id = 98;
                     $user_id = $r->user_id;
                    // $patient_id = $r->patient_id;
                    $patient_id = $r->pid;
                    $score = $r->call_score;
                    $user_available_schedule = "select * from task_management.get_user_available_slot($user_id , '".$monthStartDate."',  '".$monthEndDate."' , '1' , 'hour' )";
                        $scheduleSQLresult = DB::select( DB::raw($user_available_schedule));
                        $data = array();
                        if(count($scheduleSQLresult)>0){  
                            $schedule_day_pref = 0;
                            $schedule_time_pref = 0;

                           
                        
                            foreach($scheduleSQLresult as $pr){

                                $d = explode(" ", $pr->date_actual);
                                $d1 = $d[0]; 
                                //$newtask_date = $d1." ".$pr->workinghour;
                               // $newtask_date = $pr->date_actual." ".$pr->workinghour;
                                $converted_task_date = $d1." ".$pr->workinghour;
                                // $newtask_date = $pr->date_actual." ".$pr->workinghour;
                               // $converted_task_date = DatesTimezoneConversion::userToConfigTimeStamp($newtask_date);


                                $data = array(
                                                'uid'                         => $patient_id,
                                                'module_id'                   => $module_id,
                                                'component_id'                => $component_id,
                                                'stage_id'                    => $stage_id,
                                                'step_id'                     => $step_id,
                                                'task_notes'                  => "CCM monthly call",
                                                // 'task_date'                   => $pr->date_actual,
                                                'task_date'                   => $converted_task_date,
                                                'task_time'                   => $pr->workinghour,
                                                'assigned_to'                 => $user_id,
                                                'assigned_on'                 => Carbon::now(),
                                                'status'                      => 'Pending',
                                                'status_flag'                 => 0,
                                                'created_by'                  => 1,
                                                'patient_id'                  => $patient_id,
                                                'weekday'					  => $pr->day_of_week,
                                                'schedule_day_pref'			  => $schedule_day_pref,
                                                'schedule_time_pref'		  => $schedule_time_pref,								
                                                'score'		  => $score,	
                                                'etl_flag'		  => 3,
                                                'etl_title' => 'scheduler task step 3',
                                                'etl_notes'=>'task for patient without preference on available slot step 3'
                                            );
                                $insert = ToDoList::create($data);	
                                if($insert->id != null && $insert->id != "" && $insert->id != '' ){
                                    $scheduler_run_value = 1;
                                    \Log::info("Scheduler in 3rd logic");
                                    echo "scheduler in 3rd logic and scheduler_run_value=1";
                                }
                                echo "added entry for user".$user_id." patient ".$patient_id." from 3 logic on ".$pr->date_actual." at ".$pr->workinghour;
                                break;
                            }
                        }else{
                                
                            echo "slots not available. schedule made at 30 min interval for patients without preference";

                            $user_available_schedule = "select * from task_management.get_user_available_slot($user_id , '".$monthStartDate."',  '".$monthEndDate."' , '30' , 'min' )";
                            $scheduleSQLresult = DB::select( DB::raw($user_available_schedule));
                            $data = array();
                            if(count($scheduleSQLresult)>0){
                                $schedule_day_pref = 0;
                                $schedule_time_pref = 0;
                                
                        
                                foreach($scheduleSQLresult as $pr){

                                $d = explode(" ", $pr->date_actual);
                                $d1 = $d[0]; 
                                //$newtask_date = $d1." ".$pr->workinghour;  
                                $newtask_date = $pr->date_actual." ".$pr->workinghour;  
                                $converted_task_date = $d1." ".$pr->workinghour;  
                                // $newtask_date = $pr->date_actual." ".$pr->workinghour;
                               // $converted_task_date = DatesTimezoneConversion::userToConfigTimeStamp($newtask_date);


                                    $data = array(
                                                    'uid'                         => $patient_id,
                                                    'module_id'                   => $module_id,
                                                    'component_id'                => $component_id,
                                                    'stage_id'                    => $stage_id,
                                                    'step_id'                     => $step_id,
                                                    'task_notes'                  => "CCM monthly call",
                                                    // 'task_date'                   => $pr->date_actual,
                                                    'task_date'                   => $converted_task_date, 
                                                    'task_time'                   => $pr->workinghour,
                                                    'assigned_to'                 => $user_id,
                                                    'assigned_on'                 => Carbon::now(),
                                                    'status'                      => 'Pending',
                                                    'status_flag'                 => 0,
                                                    'created_by'                  => 1,
                                                    'patient_id'                  => $patient_id,
                                                    'weekday'					  => $pr->day_of_week,
                                                    'schedule_day_pref'			  => $schedule_day_pref,
                                                    'schedule_time_pref'		  => $schedule_time_pref,								
                                                    'score'		                  => $score,	
                                                    'etl_flag'		              => 4,
                                                    'etl_title' =>'step 4 No preference, Schedule at 30 min',
                                                    'etl_notes' =>'slots not available. schedule made at 30 min interval for patients without preference',
                                                );
                                    $insert = ToDoList::create($data);	
                                    if($insert->id != null && $insert->id != "" && $insert->id != '' ){
                                        \Log::info("Scheduler in 4 logic");
                                        echo "scheduler in 4th logic and scheduler_run_value=1";
                                        $scheduler_run_value = 1;
                                    }
                                    echo "added entry for user".$user_id." patient ".$patient_id." from 4 logic on ".$pr->date_actual." at ".$pr->workinghour;
                                    break;
                                }
                            }        
                }
                
            }

                
            if($scheduler_run_value == 1){
                \Log::info("Schedulerlog in calenders called");  
                $schedulerdata =  \DB::table('ren_core.scheduler')
                                ->where('command_name','patientscheduletasks')
                                ->where('scheduler_type','command_scheduler')
                                ->first();
                                // dd($schedulerdata);
                $schedulerId = $schedulerdata->id;
                $start_date  = $schedulerdata->start_date; 
                $currentdatetimeforschedulerrecorddate = date("Y-m-d h:i:s");   
                $schedulerrecorddate = DatesTimezoneConversion::userToConfigTimeStamp($currentdatetimeforschedulerrecorddate); 

                $scharray = array('scheduler_id'   =>  $schedulerId,  
                            'activity_id'          =>  null,
                            'module_id'            =>  null,
                            'operation'            =>  null,
                            'start_date'           =>  $start_date,
                            'comments'             =>  null,
                            'practice_id'          =>  null,
                            'practice_group'       =>  null,  
                            'execution_status'     =>  1,
                            'patients_count'       =>  0,
                            'exception_comments'   =>  null,
                            'schedulerrecord_date' =>  $schedulerrecorddate   
                            );
                            // dd($scharray);
                            $insertschedulerlog = SchedulerLogHistory::create($scharray);
                

            }    

            
	
}


/*********************************************************ashvini arumugam store procedure changes end *******************************/

public function patientCompletedTasks(){ 
    $first_day_currentmonth = date('Y-m-01 00:00:00');
    $last_day_currentmonth  = date('Y-m-t 12:59:59');
    $fromdate = $first_day_currentmonth;
    $todate = $last_day_currentmonth;
    $year = date("Y"); 
    $month = date("m");
   
    $sql = "select distinct tdl.patient_id from patients.patient p 
            inner join (select DISTINCT pt.patient_id,pt.created_at ,pt1.timeone ,
            pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone) as totaltime
            from  patients.patient_time_records pt            
            left JOIN (SELECT patient_id,sum(net_time) as timeone FROM patients.patient_time_records WHERE 
            adjust_time =1 and billable = 1 and module_id in (2,3,8) 
            and (created_at::timestamp between '".$fromdate."' and  '".$todate."') 
            group by patient_id) pt1 ON  pt1.patient_id = pt.patient_id 
            
            LEFT JOIN (SELECT patient_id, sum(net_time) AS timetwo FROM patients.patient_time_records WHERE 
            adjust_time =0 and billable = 1 and module_id in (2,3,8)
            and (created_at::timestamp between '".$fromdate."' and  '".$todate."' ) 
            group by patient_id) pt2 ON  pt2.patient_id = pt.patient_id
                       
            where (pt.created_at::timestamp between '".$fromdate."' and  '".$todate."' )
            and pt.module_id in  (2,3,8) 					 
            ) ptr on ptr.patient_id=p.id and ptr.totaltime >= '00:20:00'
            
            inner join task_management.to_do_list tdl on tdl.patient_id =  ptr.patient_id

            inner join ccm.ccm_hippa_verification chv on chv.patient_id = tdl.patient_id 
            

            where tdl.etl_flag >= 1   
            and (EXTRACT(Month from chv.v_date) = '".$year."') 
            and (EXTRACT(YEAR from chv.v_date) = '".$month."')  
               
            " ;   
  

    $results = DB::select( DB::raw($sql));
    
    if(count($results) >= 1){
        foreach($results as $r){
        
            $timerecordquery = \DB::table('patients.patient_time_records')
                                ->where('patient_id',$r->patient_id)
                                ->orderby('id','desc')
                                ->skip(0)->take(1)->get();

            // dd($timerecordquery[0]->created_at);
            $data = \DB::table('task_management.to_do_list')
                    ->where('etl_flag','>=',1)
                    ->where('patient_id',$r->patient_id)
                    ->whereBetween('task_date', [$fromdate, $todate])
                    ->update(['status'=>'Completed',
                            'status_flag'=>1,
                            'task_completed_at'=>$timerecordquery[0]->created_at
                    ]);

            
            // $timerecordquery = "select max(created_at) from patients.patient_time_records where patient_id = '.$r->patient_id.'";
            
        }  
}



    
}



}