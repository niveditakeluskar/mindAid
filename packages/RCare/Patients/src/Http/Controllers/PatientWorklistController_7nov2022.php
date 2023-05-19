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
    
	public function createTaskList(){
		$sql = "select distinct user_id, ps.patient_id, call_score, pct.* from  
                patients.patient_score ps left join patients.patient_contact_times pct 
                on ps.patient_id = pct.patient_id 
                and task_date is null and user_id is not null order by user_id asc, call_score desc";	
        $results = DB::select( DB::raw($sql));
		
		
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
               
            //$query = "select pid, pfname, plname, pmname, pprofileimg, pdob, pppracticeemr,ppracticeid, pracpracticename,pfromdate,ptodate,to_char(csslastdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csslastdate  , pstatus, ptrtotaltime from 
            //patients.worklist_v2($p,$pt,$month, $year,$timeoption,'".$totime."',$roleid, $cid,'".$configTZ ."','".$userTZ."',$status,$module_id)"; 

            if( $roleid == 5 || $roleid == 2 || $roleid == 8 ){
                // $query = "select pid, pfname, plname, pmname, pprofileimg, pdob, pppracticeemr,ppracticeid, pracpracticename,pfromdate,
				// 		  ptodate,to_char(csslastdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csslastdate  , 
				// 		  pstatus, ptrtotaltime,psmodule_id ,ccdiagnosis_count, ccreview_age_green,ccreview_age_yellow,iconcolor,icontitle from 
                //           patients.worklist_v2($p,$pt,$month, $year,$timeoption,'".$totime."',$roleid, $cid,'".$configTZ ."','".$userTZ."',$status,$module_id)"; 

               $query = "select pid, pfname, plname, pmname, pprofileimg, pdob, pppracticeemr,ppracticeid, pracpracticename,pfromdate,
                         ptodate,to_char(csslastdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csslastdate  , 
                         pstatus, ptrtotaltime,psmodule_id ,cciconcolor,ccicontitle from 
                         patients.worklist_v2($p,$pt,$month, $year,$timeoption,'".$totime."',$roleid, $cid,'".$configTZ ."','".$userTZ."',$status,$module_id)";

            } else {
                $query = "select pid, pfname, plname, pmname, pprofileimg, pdob, pppracticeemr,ppracticeid, 
						  pracpracticename,pfromdate,ptodate,to_char(csslastdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csslastdate  ,
						  pstatus, ptrtotaltime, psmodule_id , cciconcolor, ccicontitle from 
						  patients.worklist_tlm_v2($p,$pt,$month, $year,$timeoption,'".$totime."',$roleid, $cid,'".$configTZ ."','".$userTZ."',$status,$module_id)";
            }         
            // dd($query);   
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

			//dd($data);
           
        
             
            // dd($data); 
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

	public function addCarePlanAge(Request $request){
		PatientCareplanAge::truncate();
		$generate_careplan_age = DB::select('SELECT patients.generate_patient_careplan_age()');
		return 1;		
	}

/*
patients.generate_patient_careplan_age() 

	 public function addCarePlanAge(Request $request){
		 
		PatientCareplanAge::truncate();

        $cid = 1;
        $query =    "select 
                    cc.patient_id, 
                    cc.diagnosis_count,
                    cc.review_age_green, 
                    cc.review_age_yellow,
                    cc.update_age_years,
                    CASE
                    when ((cc.diagnosis_count = cc.review_age_green) and (cc.review_age_green = 0 )) or (cc.update_age_years >= 1) THEN 'red'
                    when ( cc.review_age_yellow > 1 ) or (cc.update_age_years >= 1)   THEN 'red'
                    when (cc.review_age_green = 0 and cc.review_age_yellow = 0 )  THEN 'green'
                    when ( cc.review_age_green > 0) and (cc.review_age_yellow = 0 )   THEN 'yellow'
                    ELSE null
                    end AS result,
                    CASE
                    when ((cc.diagnosis_count = cc.review_age_green) and (cc.review_age_green = 0 )) or (cc.update_age_years >= 1)  THEN 'All the Care Plans not reviewed for more than 3 months or
                                                                                                        Any of the Care Plan not reviewed for more than 5 months or
                                                                                                        Any of the Care Plan not updated for more than a year'				
                    when ( cc.review_age_yellow > 1 ) or (cc.update_age_years >= 1)  THEN 'All the Care Plans not reviewed for more than 3 months or
                                                            Any of the Care Plan not reviewed for more than 5 months or
                                                            Any of the Care Plan not updated for more than a year'				
                    when (cc.review_age_green = 0 and cc.review_age_yellow = 0 )  THEN 'All the Care Plans have been reviewed within 3 months'				
                    when ( cc.review_age_green > 0) and (cc.review_age_yellow = 0 )  THEN 'Any of the Care Plan not reviewed for more than 3 months'
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
                            <=3 then 0 else 1 end
                        ) as review_age_green,      
                    sum( case when
                       
                        ((date_part ('year'::text , age(now(), patient_careplan_last_update_n_review.review_date::timestamp with time zone))::float *12 + 
                        date_part ('month'::text , age(now(), patient_careplan_last_update_n_review.review_date::timestamp with time zone))::float +
                        (date_part ('day'::text , age(now(), patient_careplan_last_update_n_review.review_date::timestamp with time zone))::float /100 )::float
                        )::float)
                            < 5 then 0 else 1 end
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
                            'created_by'=>$cid,
                            'updated_by'=>$cid,
                            'status'=>1 );

            PatientCareplanAge::create($mydata);
        }
        
    }
*/
}