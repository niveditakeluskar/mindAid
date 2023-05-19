<?php
namespace RCare\Rpm\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use Illuminate\Http\Request;
use RCare\Rpm\Models\Patient;
use RCare\Patients\Models\PatientPartResearchStudy;
use RCare\Patients\Models\PatientPersonalNotes;
use RCare\Org\OrgPackages\RPMBillingConfiguration\src\Models\RPMBilling;
use DB;
use Hash;
use Validator,Redirect,Response;
use DataTables;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Session; 
use RCare\Rpm\Models\VitalsAlertNotes;
use RCare\Rpm\Models\Observation_Oxymeter;
use RCare\Rpm\Models\Observation_BP;
use RCare\Rpm\Models\Observation_Glucose;
use RCare\Rpm\Models\Observation_Heartrate;
use RCare\Rpm\Models\Observation_Spirometer;
use RCare\Rpm\Models\Observation_Weight;
use RCare\System\Traits\DatesTimezoneConversion; 
use RCare\Org\OrgPackages\Users\src\Models\Users;
use RCare\Patients\Models\VitalsObservationNotes;
use RCare\Rpm\Http\Requests\ActiveAlertNotesRequest;    
use RCare\Ccm\Models\CallWrap; 
use RCare\API\Http\Controllers\ECGAPIController;
use RCare\Org\OrgPackages\Users\src\Models\OrgUserRole;
use RCare\Org\OrgPackages\Roles\src\Models\Roles;   
use RCare\Rpm\Models\Other_Alerts;  

class ActiveAlertController extends Controller
{


        public function getActiveAlert(Request $request)
        {
            $uid = session()->get('userid');
            $usersdetails = Users::where('id',$uid)->get();
            $roleid = $usersdetails[0]->role;
            // dd($roleid); 
			
            return view('Rpm::active-alert.active-alert',compact('roleid')); 
        }    


      public function getActiveAlertData(Request $request)
      {
        //  $base_url="http://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/';
        // dd( $base_url."test");
        $practicesgrp = sanitizeVariable($request->route('practicesgrp')); 
        $caremanagerid  = sanitizeVariable($request->route('caremanagerid')); 
        $practices = sanitizeVariable($request->route('practices'));
        $provider = sanitizeVariable($request->route('provider'));
        $timeframe = sanitizeVariable($request->route('timeframe'));
        $patient = sanitizeVariable($request->route('patient'));

       
        
       
         if($timeframe=="null" || $timeframe=="")
        {
            
            $timeframe = 'null';
            $dt1 = DatesTimezoneConversion::userToConfigTimeStamp(date('Y-m-d H:i:s'));    
            $dt2 = DatesTimezoneConversion::userToConfigTimeStamp(date('Y-m-d H:i:s')); 
                       
        }
        else{    

                $date =date('Y-m-d H:i:s'); 
                $timeframedate = date('Y-m-d H:i:s', strtotime(' -'.$timeframe." days"));   

                $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($timeframedate);    
                $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($date);                       
        }
        $configTZ = config('app.timezone');
        $userTZ  = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
        $uid = session()->get('userid');
        $usersdetails = Users::where('id',$uid)->get();
        $roleid = $usersdetails[0]->role;  

      //  if($timeframe==null){
            $query = "select 
            tempid,      
            pid, 
            reading,
            unit,
            to_char(effdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csseffdate,
            pfname, 
            plname,
            pmname, 
            pdob , 
            pprofileimg, 
            practicename, 
            providername, 
            caremanagerfname,
            caremanagerlname,
            filename,
            devicename,
            threshold,
             addressed,
            deviceid,
            threshold_type
            from patients.sp_activealert($practicesgrp,$practices,$provider,$caremanagerid,
            $patient,timestamp '".$dt1."',timestamp '".$dt2."',$timeframe,$roleid, $uid) order by csseffdate asc";  
     
        $data = DB::select( DB::raw($query) );
            return Datatables::of($data) 
            ->addIndexColumn()
            ->addColumn('action', function($row){
               $devicenm = str_replace(' ', '-', $row->devicename);
               if($row->filename=='')
               {
                $btn ='<a href="#"  title="Start" ><button type="button" id="detailsbutton" class="btn btn-primary">Click</button></a>';
               }
               else
               {
                $btn ='<a href="'.$row->filename.'"  target="_blank" title="Start" ><button type="button" id="detailsbutton" class="btn btn-primary">Click</button></a>';
               }
                
                return $btn;
            })
            ->addColumn('patients', function($row){
            $date=date("Y-m-d");
              $year = date('Y', strtotime($date));
              $month = date('m', strtotime($date));
              $fromdate = $year."-".$month."-01 00:00:00";
              $todate = $date." 23:59:59"; 

              $dt1 = $year."-".$month."-01";
              $dt2 = $date;
               if($row->unit == 'beats/minute'){
                        $unittable ='observationsheartrate';
                    }
                    else if($row->unit == 'lbs'){
                        $unittable = 'observationsweight';
                    }
                    else if($row->unit == '%'){
                        $unittable = 'observationsoxymeter';
                    }
                    else if($row->unit == 'mm[Hg]'){
                        $unittable ='observationsbp';    
                    }
                    else if($row->unit == 'degrees F'){
                        $unittable = 'observationstemperature';
                    }
                    else if($row->unit == 'mg/dl'){
                        $unittable = 'observationsglucose';
                    }
                    else{
                        $unittable = 'observationsspirometer';
                    }  
                // /Rpm/alerts-history-details/'.$row->id.'
                $btn ='<a href="/rpm/patient-alert-data-list/'.$row->pid.'/'.$unittable.'/'.$dt1.'/'.$dt2.'" title="Start" >'.$row->pfname.' '.$row->plname.'</a>';
                return $btn;
            })
            ->rawColumns(['action','patients'])  
            ->make(true);    
          
    }

    

      public function saveRpmNotes(ActiveAlertNotesRequest $request)
       { 
        // dd($request->all());
        $notes = sanitizeVariable($request->notes);
        $vitals = explode(",",sanitizeVariable($request->vital));
        $units = explode(",",sanitizeVariable($request->unit));
        $patient_id = explode(",",sanitizeVariable($request->care_patient_id));
        $rpm_observation_id = explode(",",sanitizeVariable($request->rpm_observation_id));
        $csseffdate = explode(",",sanitizeVariable($request->csseffdate));
        //$hd_chk_this = explode(",",sanitizeVariable($request->hd_chk_this));
        // $table="RPMdailyreviewedcompleted";
        $uid = session()->get('userid');
        $usersdetails = Users::where('id',$uid)->get();
        
        $table        = explode(",",sanitizeVariable($request->table));
        $formname     = explode(",",sanitizeVariable($request->formname));

        $time         = RPMBilling::get();
        $nettime      = $time[0]->vital_review_time;
        $module_id    = getPageModuleName();
        $component_id = sanitizeVariable($request->component_id);
        $componentid  = (int)$component_id;
        $roleid       = $usersdetails[0]->role; 
        $billable     =1;
        $start_time   =sanitizeVariable($request->hd_timer_start);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id     = sanitizeVariable($request->add_step_id);
         //dd($start_time );
        $serialid="";


        for($i=0;$i<count($patient_id);$i++){

            $myDateTime   =  explode(" ",$csseffdate[$i]);
            $mydates      = explode("-",$myDateTime[0]); 
            $effectivedate= $mydates[2]."-".$mydates[0]."-".$mydates[1];
            $datearray[]=  date("F j", strtotime($effectivedate));
           
            $fromdate     =$effectivedate." "."00:00:00";    
            $dt1          = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
            $todate       = $effectivedate ." "."23:59:59"; 
            $dt2          = DatesTimezoneConversion::userToConfigTimeStamp($todate); //"2021-03-12 18:29:59" 

            $serialid.=$rpm_observation_id[$i].",";
            $vital=$vitals[$i];
            $unit=$units[$i];
            $patientid=$patient_id[$i];
            
            $nettime= date("H:i:s",strtotime($time[0]->vital_review_time)+strtotime($start_time));
            

         if($unit=='' || $unit=="" || $unit==null || $unit=='null'){
    
                $observationid = null;
                $billable = 0;
             
                $updateotheralerts =   Other_Alerts::where('id',$rpm_observation_id[$i])->get();
                $reviewstatus =$updateotheralerts[0]->reviewed_flag; 
                if($reviewstatus==0){

                    $reviewstatus=1;    
                    $data=array('addressed'=>1,
                                'reviewed_flag'=> $reviewstatus,
                                'reviewed_date'=>Carbon::now(),
                                'addressed_date'=>Carbon::now());

                    $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id,
                                     $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);
                }else{
                    
                    $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
                    $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id,
                    $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);
                } 
                $updateweight = Other_Alerts::where('id',$rpm_observation_id[$i])->update($data); 

              if($vital == 'Weight' || $vital == 'observationsweight'){
                   $deviceid=1;
               }else if($vital == 'Oxygen' || $vital == 'observationsoxygen'){
                  $deviceid=2;
               }else if($vital == 'Blood Pressure' || $vital == 'observationsbp'){
                  $deviceid=3;
               }else if($vital == 'Spirometer' || $vital == 'observationsspirometer'){
                  $deviceid=5;
               }else if($vital == 'Glucose' || $vital == 'observationsglucose'){
                  $deviceid=6;
               }else if($vital == 'Heartrate' || $vital == 'observationsheartrate'){
                $deviceid=0;
               }else{}
         }
         else if($unit=='lbs' || $unit==1){  
     

            $vital = 'Weight';
            $deviceid=1;
            $obdata = Observation_Weight::where('id',$rpm_observation_id[$i])->get();       
            $observationid = $obdata[0]->observation_id;  
            $reviewstatus =$obdata[0]->reviewed_flag; 
            if($reviewstatus==0)
            {
                $reviewstatus=1;    
                $data=array('addressed'=>1,'reviewed_flag'=> $reviewstatus,'reviewed_date'=>Carbon::now(),'addressed_date'=>Carbon::now());$record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);
                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);
            }
            else
            {
                $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);
            } 

            $timestampquery="select content->>'timestamp' as timestamp,content->>'xmit_id' as devicecode from api.webhook_observation  where content->>'observation_id' ='".$observationid."'";
            $gettimestamp=DB::select( DB::raw($timestampquery) );
            $devicecode=$gettimestamp[0]->devicecode;
            $timestampaddby=$gettimestamp[0]->timestamp;
            ECGAPIController::alertAddressedBy($devicecode,$timestampaddby);      
            $updateweight = Observation_Weight::where('id',$rpm_observation_id[$i])->update($data); 

            $billingstatuscheck = Observation_Weight::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');   
        }  
        else if($unit=='%' || $unit==2){  

            $vital = 'Oxygen';
            $deviceid=2;
            $obdata = Observation_Oxymeter::where('id',$rpm_observation_id[$i])->get();       
            $observationid = $obdata[0]->observation_id; 
            $reviewstatus =$obdata[0]->reviewed_flag; 

           
            if($reviewstatus==0)
            {

                 $reviewstatus=1;    
                 $data=array('addressed'=>1,'reviewed_flag'=> $reviewstatus,'reviewed_date'=>Carbon::now(),'addressed_date'=>Carbon::now());
                 $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);
             
            
            }
            else
            {

                 $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
                 $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);

           
                }  
                $timestampquery="select content->>'timestamp' as timestamp,content->>'xmit_id' as devicecode from api.webhook_observation  where content->>'observation_id' ='".$observationid."'";
                $gettimestamp=DB::select( DB::raw($timestampquery) );
                $devicecode=$gettimestamp[0]->devicecode;
                $timestampaddby=$gettimestamp[0]->timestamp;
                ECGAPIController::alertAddressedBy($devicecode,$timestampaddby);
                $updateoxy = Observation_Oxymeter::where('id',$rpm_observation_id[$i])->update($data); 

                $billingstatuscheck =   Observation_Oxymeter::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');  
        }
        else if($unit=='mm[Hg]' || $unit=='mmHg' || $unit==3){ 
            
      

            $vital = 'Blood Pressure'; 
            $deviceid=3;
            $obdata = Observation_BP::where('id',$rpm_observation_id[$i])->get();
            $observationid = $obdata[0]->observation_id;
            $reviewstatus =$obdata[0]->reviewed_flag; 
            if($reviewstatus==0)
            {
                $reviewstatus=1;    
                $data=array('addressed'=>1,'reviewed_flag'=> $reviewstatus,'reviewed_date'=>Carbon::now(),'addressed_date'=>Carbon::now()); $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);
                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);
           
           
            }else{

                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);
                $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
            }
            
            $timestampquery="select content->>'timestamp' as timestamp,content->>'xmit_id' as devicecode from api.webhook_observation  where content->>'observation_id' ='".$observationid."'";
            $gettimestamp=DB::select( DB::raw($timestampquery) );
            $devicecode=$gettimestamp[0]->devicecode;
            $timestampaddby=$gettimestamp[0]->timestamp;
            ECGAPIController::alertAddressedBy($devicecode,$timestampaddby);
            $updatebp=Observation_BP::where('id',$rpm_observation_id[$i])->update($data);
            $billingstatuscheck=Observation_BP::where('patient_id',$patientid)->whereBetween('effdatetime',[$dt1 , $dt2])->pluck('billing');
            
         }        
          else if($unit=='L' || $unit=='L/min' || $unit==5){  

           

                $vital = 'Spirometer';  
                $deviceid=5;
                $obdata = Observation_Spirometer::where('id',$rpm_observation_id[$i])->get();       
                $observationid = $obdata[0]->observation_id;      
                $reviewstatus =$obdata[0]->reviewed_flag; 

            if($reviewstatus==0)
            {
                $reviewstatus=1;    
                 $data=array('addressed'=>1,'reviewed_flag'=> $reviewstatus,'reviewed_date'=>Carbon::now(),'addressed_date'=>Carbon::now());
                 $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);
            }   
             else
            {
                 $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
                 $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);
            } 
            $timestampquery="select content->>'timestamp' as timestamp,content->>'xmit_id' as devicecode from api.webhook_observation  where content->>'observation_id' ='".$observationid."'";
            $gettimestamp=DB::select( DB::raw($timestampquery) );
            $devicecode=$gettimestamp[0]->devicecode;
            $timestampaddby=$gettimestamp[0]->timestamp;
            ECGAPIController::alertAddressedBy($devicecode,$timestampaddby);
            $updatespirometer = Observation_Spirometer::where('id',$rpm_observation_id[$i])->update($data);
            $billingstatuscheck = Observation_Spirometer::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');            
          }
        else if($unit=='mg/dl' || $unit==6){  
            
          

            $vital = 'Glucose';  
            $deviceid=6;
            $obdata = Observation_Glucose::where('id',$rpm_observation_id[$i])->get();       
            $observationid = $obdata[0]->observation_id; 
            $reviewstatus =$obdata[0]->reviewed_flag; 

            if($reviewstatus==0)
            {
                $reviewstatus=1;    
                 $data=array('addressed'=>1,'reviewed_flag'=> $reviewstatus,'reviewed_date'=>Carbon::now(),'addressed_date'=>Carbon::now());
                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);
             }
            else
            {
                 $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
                 $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);
             }    
             $timestampquery="select content->>'timestamp' as timestamp,content->>'xmit_id' as devicecode from api.webhook_observation  where content->>'observation_id' ='".$observationid."'";
            $gettimestamp=DB::select( DB::raw($timestampquery) );
             $devicecode=$gettimestamp[0]->devicecode;
            $timestampaddby=$gettimestamp[0]->timestamp;
            ECGAPIController::alertAddressedBy($devicecode,$timestampaddby);   
            $updateglucose = Observation_Glucose::where('id',$rpm_observation_id[$i])->update($data);
            $billingstatuscheck = Observation_Glucose::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');            
         }
        
        else{ 

             $vital = 'Heartrate'; 
            $deviceid=0;
            $obdata = Observation_Heartrate::where('id',$rpm_observation_id[$i])->get();
            $observationid = $obdata[0]->observation_id;
            $reviewstatus =$obdata[0]->reviewed_flag; 

            if($reviewstatus==0)
            {
                $reviewstatus=1;    
                 $data=array('addressed'=>1,'reviewed_flag'=> $reviewstatus,'reviewed_date'=>Carbon::now(),'addressed_date'=>Carbon::now());
                 $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);
            }
            else
            {
                 $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
                 $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);
            }    
            $timestampquery="select content->>'timestamp' as timestamp,content->>'xmit_id' as devicecode from api.webhook_observation  where content->>'observation_id' ='".$observationid."'";
            $gettimestamp=DB::select( DB::raw($timestampquery) );
             $devicecode=$gettimestamp[0]->devicecode;
            $timestampaddby=$gettimestamp[0]->timestamp;
            ECGAPIController::alertAddressedBy($devicecode,$timestampaddby);     
            $updateheartrate = Observation_Heartrate::where('id',$rpm_observation_id[$i])->update($data);
            $billingstatuscheck = Observation_Heartrate::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');           
         }


     
        if($unit=='' || $unit=='null' || $unit==null || $unit=="null"){ 
          //do nothing bcz != is not working
        }else{ 

        $billarray = $billingstatuscheck->toArray();         
        if(in_array("1",$billarray)){
         
        }
        else{
                
                if($unit=='lbs' || $unit==1){  
                    $vital = 'Weight';
                    $updateoxy = Observation_Weight::where('id',$rpm_observation_id[$i])->update(['billing'=> 1]);                     
                }
                else if($unit=='%' || $unit==2){  
                    $vital = 'Oxygen';
                    $updateoxy = Observation_Oxymeter::where('id',$rpm_observation_id[$i])->update(['billing'=> 1]);                     
                }
                else if($unit=='mm[Hg]'  || $unit=='mmHg' || $unit==3){  
                    $vital = 'Blood Pressure';        
                    $updatebp = Observation_BP::where('id',$rpm_observation_id[$i])->update(['billing'=> 1]);
                }               
                else if($unit=='L' || $unit=='L/min' || $unit==5){  
                    $vital = 'Spirometer';        
                    $updatespirometer = Observation_Spirometer::where('id',$rpm_observation_id[$i])->update(['billing'=> 1]);
                }
                else if($unit=='mg/dl' || $unit==6){  
                    $vital = 'Glucose';        
                    $updateglucose = Observation_Glucose::where('id',$rpm_observation_id[$i])->update(['billing'=> 1]);
                }
                else{  
                     $vital = 'Heartrate';        
                    $updateheartrate = Observation_Heartrate::where('id',$rpm_observation_id[$i])->update(['billing'=> 1]);
                  
                } 
           $start_time=$nettime;

        }
      }
     


        $a = array(
            'notes' => $notes, 
            'device_id' => $deviceid, 
            'patient_id' => $patientid,
            'rpm_observation_id' => $rpm_observation_id[$i],
            'observation_id' => $observationid,
            'created_by'=>$uid,
            'updated_by'=>$uid
            );                    
        $checkvitalnotes = VitalsObservationNotes::where('patient_id',$patientid)
                            ->where('device_id',$deviceid)
                            ->where('rpm_observation_id',$rpm_observation_id[$i])
                            ->get();                     
        

        if($checkvitalnotes->isEmpty()){
            VitalsObservationNotes::create($a);           
        }
        else{
            VitalsObservationNotes::where('id',$checkvitalnotes[0]->id)->update($a); 
        }   

      
        }


        $fname = $usersdetails[0]->f_name;
        $lname = $usersdetails[0]->l_name;
        $name =  $fname.''.$lname;
        $revieweddatetime =Carbon::now();
        $convertedrevieweddatetime =DatesTimezoneConversion::userToConfigTimeStamp($revieweddatetime);
        $r =  explode(" ",$convertedrevieweddatetime); 
        $reviewDate =  $r[0];
        $reviewTime =  $r[1];
        $timestamp = strtotime($reviewDate); 
        $newReviewDate = date("F j Y", $timestamp);
        $dateunique= array_unique($datearray);
        $newReportedDate =implode(',', $dateunique);
        $reportdate=$myDateTime[0];
        $reporttime=$myDateTime[1];
       // $newReportedDate = date("F j Y", strtotime($effectivedate));
        $nwdt= date("Y-m-d H:i:s", strtotime($revieweddatetime));
        
        $topic = $vital.' alert reported on '.$newReportedDate.' has been addressed by '.$name.' on ' .$newReviewDate.' at '.$reviewTime;

        $CallWrap = array('uid'=>$uid,
                    'record_date'=>$nwdt,
                    'status'=>1, 
                    'topic'=>$topic,
                    'notes'=>$notes,
                    'emr_entry_completed'=>null,
                    'emr_monthly_summary'=>null,
                    'action_taken'=>null,
                    'created_by'=>session()->get('userid'),
                    'updated_by'=>session()->get('userid'),
                    'patient_id'=>$patientid,
                    'sequence'=>'9',
                    'sub_sequence'=>'0',
                    'template_type'=>null,
                    'task_id'=>null ,
                    'device_id'=>$deviceid,
                    'rpm_observation_id'=>rtrim($serialid, ',') 
                );
        CallWrap::create($CallWrap);  
       
    }

   

} 