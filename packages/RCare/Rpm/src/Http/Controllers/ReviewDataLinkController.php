<?php
namespace RCare\Rpm\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RCare\Rpm\Models\Patients;
use RCare\Rpm\Models\Devices;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Org\OrgPackages\RPMBillingConfiguration\src\Models\RPMBilling; 
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
use Hash;
use Validator,Redirect,Response;
use DataTables;
use Carbon\Carbon;
use Session,DB; 
class ReviewDataLinkController extends Controller
{
   
   
    // public function fetchPatients(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = Patients::latest()->get();
    //         return Datatables::of($data)
    //         ->addIndexColumn()
    //         ->addColumn('action', function($row){
                
    //             $btn =    '<a href="edit/'.$row->id.'" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>';
    //             $btn ='<a href="traning-checklist/'.$row->id.'" title="Start" ><i class="text-20 i-Start-2" style="color: #2cb8ea;"></i></a>';
    //             return $btn;
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);
    //     }
    //     return view('patients');
    // }

    public function getPatientDeviceDataNew($id)
    {   //dd("hi");
        $patient = Patients::where('id',$id)->get();
       // $devices   = Devices::where('status','1')->orderby('id','asc')->get();
        return view('Rpm::review-data-link.review-data',['patient'=>$patient]);
       //  return view('Rpm::review-data-link.reading',['patient'=>$patient,'devices'=>$devices]);
    }

    public function toDolist()
    {
        return view('Rpm::stepsbreadcum.to-do-list');
    }

    //Device Traning viewsiew

    public function ccmDevicelist()
    {
        return view('Rpm::ccm.patient-device-list');
    }


    
    public function getPatientDataAlertAccordingtoDevice(Request $request)
    {
        $configTZ = config('app.timezone');
        $userTZ  = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        $patient = sanitizeVariable($request->route('patientid'));  
        // dd($patient);
        $unit =   sanitizeVariable($request->route('unit')); 
        $fromdate=sanitizeVariable($request->route('fromdate'));
        $todate=sanitizeVariable($request->route('todate'));
        $pat;
        $u;
         if($patient!='null')
        {
            if( $patient==0) 
            {
                $pat = 0;  
            }
            else
            {
                $pat = $patient;        
            }
        }
        else{
            $pat = 'null';
        } 
        
        
        if($unit!='null')
        {
           $u = $unit;
        }
        else{
            $u = 'null';
        } 

        if($fromdate=='null' || $fromdate=='')
        {
              $date=date("Y-m-d");
              $year = date('Y', strtotime($date));
              $month = date('m', strtotime($date));
              $fromdate = $year."-".$month."-01 00:00:00";
              $todate = $date." 23:59:59"; 
    
              $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
              $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate);        
  
        }
        else
        {      
           $fromdate = $fromdate." 00:00:00";
           $todate = $todate." 23:59:59" ; 
           $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
           $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate);  
        }

        // dd($pat);
        $query = "select     
        bptempid,
        hrtempid,
        pid, 
        readingone,
        readingtwo,
        heartratereading,
        threshold,
        heartrate_threshold,
        reviewedflag,
        rwaddressed,
        alert,
        unit,
        hrunit,
        
        --to_char(effdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS')
        effdate as csseffdate  
        from patients.sp_patientdetailvitalalertsdevicelink($pat,'".$u."',timestamp '".$dt1."',timestamp '".$dt2."')";       
//dd($query);
        $data = DB::select( DB::raw($query) ); 
        // dd( $data) ; 
        return Datatables::of($data) 
            ->addIndexColumn()
             
            ->make(true); 
    }

    public function saveRpmReviewNotes(ActiveAlertNotesRequest $request){
        $id             = sanitizeVariable($request->uid);
        $patient_id     = sanitizeVariable($request->patient_id);
        $module_id      = sanitizeVariable($request->module_id);
        $notes          = sanitizeVariable($request->notes);   
        $device_id      = sanitizeVariable($request->device_id);
        $currentMonth   = date('m');
        $currentYear    = date('Y');
        //record time
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $component_id = sanitizeVariable($request->component_id); 
        $stage_id     = sanitizeVariable($request->stage_id);
        $billable     = 1;
        $form_name    = sanitizeVariable($request->form_name);
        $step_id      = sanitizeVariable($request->step_id); 
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);

        DB::beginTransaction();
        try {   
        $data = array( 
            'patient_id'     => $patient_id,
            'device_id'      => $device_id,
            'notes'          => $notes,
            'created_by'     => session()->get('userid'),
            'updated_by'     => session()->get('userid'),
        );

        $insert_query = VitalsObservationNotes::create($data);

        $sequence     = 9;
        $new_sub_sequence = 1; 
            $topic_name = "Rpm Review caremanager notes" ;
            $callWrapUp = array(
                'uid'                 => $patient_id,
                'record_date'         => Carbon::now(),
                'topic'               => $topic_name,
                'notes'               => $notes,
                'created_by'          => session()->get('userid'),
                'update_by'           => session()->get('userid'),
                'patient_id'          => $patient_id, 
                'sequence'            => $sequence, 
                'sub_sequence'        => $new_sub_sequence,
            );
            CallWrap::create($callWrapUp); 
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
         DB::commit();
         return response(['form_start_time' =>$form_save_time]);
        } catch(\Exception $ex) {
            DB::rollBack();
            // return $ex;
            return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }
    
    public function saveRpmNotes(ActiveAlertNotesRequest $request)
    {
        $notes  = sanitizeVariable($request->notes);
       // $vitals = explode(",",sanitizeVariable($request->vital));
        $units                 = explode(",",sanitizeVariable($request->rpm_unit_bp));
        $hrunits               = explode(",",sanitizeVariable($request->rpm_unit_hr));
        $patient_id            = explode(",",sanitizeVariable($request->care_patient_id));
        $rpm_observation_id_bp = explode(",",sanitizeVariable($request->rpm_observation_id_bp));
        $rpm_observation_id_hr = explode(",",sanitizeVariable($request->rpm_observation_id_hr));
        $csseffdate            = explode(",",sanitizeVariable($request->csseffdate));
        $table                 = explode(",",sanitizeVariable($request->table));
        $form_name              = explode(",",sanitizeVariable($request->formname));
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = sanitizeVariable($request->step_id);
        //$form_name     = sanitizeVariable($request->form_name);
        $time         = RPMBilling::get();
        $nettime      = $time[0]->vital_review_time;
        $module_id    = getPageModuleName();
        $component_id = sanitizeVariable($request->component_id);
        $componentid  = (int)$component_id;
        $uid          = session()->get('userid');
        $usersdetails = Users::where('id',$uid)->get();
        $roleid       = $usersdetails[0]->role; 
        $billable     =1;
        $start_time   =sanitizeVariable($request->hd_timer_start);
       // dd($start_time);
        $deviceid     =sanitizeVariable($request->device_id);
        //dd($device_id);
        $serialid="";
        $serialid_hr="";
        for($i=0;$i<count($patient_id);$i++){

            $myDateTime   =  explode(" ",$csseffdate[$i]);
            $mydates      = explode("-",$myDateTime[0]); 
            $effectivedate= $mydates[2]."-".$mydates[0]."-".$mydates[1];
            $datearray[]=  date("F j", strtotime($effectivedate));
           
            $fromdate     =$effectivedate." "."00:00:00";    
            $dt1          = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
            $todate       = $effectivedate ." "."23:59:59"; 
            $dt2          = DatesTimezoneConversion::userToConfigTimeStamp($todate); //"2021-03-12 18:29:59" 
                     
                
            $serialid.=$rpm_observation_id_bp[$i].",";
            $serialid_hr.=$rpm_observation_id_hr[$i].",";
            $unit=$units[$i];
            $hrunit=$hrunits[$i];
            $patientid=$patient_id[$i];
            
           // $nettime= date("H:i:s",strtotime($time[0]->vital_review_time)+strtotime($start_time));
        $nettime=$start_time;
            if($unit=='lbs' || $unit==1){  
                $vital = 'Weight';
                
                $obdata = Observation_Weight::where('id',$rpm_observation_id_bp[$i])->get();       
                $observationid = $obdata[0]->observation_id;  
                $reviewstatus =$obdata[0]->reviewed_flag; 
                if($reviewstatus==0)
                {
                    $reviewstatus=1;    
                    $data=array('addressed'=>1,'reviewed_flag'=> $reviewstatus,'reviewed_date'=>Carbon::now(),'addressed_date'=>Carbon::now());
                    $nettime= date("H:i:s",strtotime($time[0]->vital_review_time)+strtotime($start_time));

                    $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid, $stage_id, $billable, $uid, $step_id, $form_name);
                }
                else
                {
                    $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
                }           
                $updateweight = Observation_Weight::where('id',$rpm_observation_id_bp[$i])->update($data); 

                $billingstatuscheck = Observation_Weight::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');   
            }  
            else if($unit=='%' || $unit==2){  
                $vital = 'Oxygen';
            
                $obdata = Observation_Oxymeter::where('id',$rpm_observation_id_bp[$i])->get();       
                $observationid = $obdata[0]->observation_id; 
                  $reviewstatus =$obdata[0]->reviewed_flag; 
                if($reviewstatus==0)
                {
                    $reviewstatus=1;    
                     $data=array('addressed'=>1,'reviewed_flag'=> $reviewstatus,'reviewed_date'=>Carbon::now(),'addressed_date'=>Carbon::now());
                     $nettime= date("H:i:s",strtotime($time[0]->vital_review_time)+strtotime($start_time));

                 $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid, $stage_id, $billable, $uid, $step_id, $form_name);
                 }
                else
                {
                     $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
                }   
                $updateoxy = Observation_Oxymeter::where('id',$rpm_observation_id_bp[$i])->update($data); 

                $billingstatuscheck =   Observation_Oxymeter::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');  
            }
            else if($unit=='mm[Hg]' || $unit=='mmHg' || $unit==3){  
                $vital = 'Blood Pressure'; 
                
                $obdata = Observation_BP::where('id',$rpm_observation_id_bp[$i])->get();
                $observationid = $obdata[0]->observation_id;
                $reviewstatus =$obdata[0]->reviewed_flag; 
                if($reviewstatus==0)
                {
                    $reviewstatus=1;    
                    $data=array('addressed'=>1,'reviewed_flag'=> $reviewstatus,'reviewed_date'=>Carbon::now(),'addressed_date'=>Carbon::now()); 
                   $nettime= date("H:i:s",strtotime($time[0]->vital_review_time)+strtotime($start_time));


                    $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid, $stage_id, $billable, $uid, $step_id, $form_name);
                     
                }else{
                    $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
                }
                
                $updatebp=Observation_BP::where('id',$rpm_observation_id_bp[$i])->update($data);
                $billingstatuscheck=Observation_BP::where('patient_id',$patientid)->whereBetween('effdatetime',[$dt1 , $dt2])->pluck('billing');
                
            }        
            else if($unit=='L' || $unit=='L/min' || $unit==5){  
                $vital = 'Spirometer';  
                
                 $obdata = Observation_Spirometer::where('id',$rpm_observation_id_bp[$i])->get();       
                $observationid = $obdata[0]->observation_id;      
                 $reviewstatus =$obdata[0]->reviewed_flag; 
                if($reviewstatus==0)
                {
                    $reviewstatus=1;    
                     $data=array('addressed'=>1,'reviewed_flag'=> $reviewstatus,'reviewed_date'=>Carbon::now(),'addressed_date'=>Carbon::now());
                     $nettime= date("H:i:s",strtotime($time[0]->vital_review_time)+strtotime($start_time));

                     $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid, $stage_id, $billable, $uid, $step_id, $form_name);
                     }   
                 else
                {
                     $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
                 } 
                $updatespirometer = Observation_Spirometer::where('id',$rpm_observation_id_bp[$i])->update($data);
                $billingstatuscheck = Observation_Spirometer::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');            
            }
            else if($unit=='mg/dl' || $unit==6){             
                $vital = 'Glucose';  
           
                 $obdata = Observation_Glucose::where('id',$rpm_observation_id_bp[$i])->get();       
                $observationid = $obdata[0]->observation_id; 
                 $reviewstatus =$obdata[0]->reviewed_flag; 
                if($reviewstatus==0)
                {
                    $reviewstatus=1;    
                     $data=array('addressed'=>1,'reviewed_flag'=> $reviewstatus,'reviewed_date'=>Carbon::now(),'addressed_date'=>Carbon::now());
                     $nettime= date("H:i:s",strtotime($time[0]->vital_review_time)+strtotime($start_time));

                    $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid, $stage_id, $billable, $uid, $step_id, $form_name);
                 }
                else
                {
                     $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
                 }       
                $updateglucose = Observation_Glucose::where('id',$rpm_observation_id_bp[$i])->update($data);
                $billingstatuscheck = Observation_Glucose::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');            
            }

            if(!empty($hrunit)  && $hrunit=='beats/minute'){ 
            
                $vital_hr = 'Heartrate'; 
                $deviceid_hr= 0;
               //  dd($rpm_observation_id_hr[$i]);
                $obdata = Observation_Heartrate::where('id',$rpm_observation_id_hr[$i])->get();
                $observationid_hr = $obdata[0]->observation_id;
                  $reviewstatus =$obdata[0]->reviewed_flag; 
                if($reviewstatus==0)
                {
                    $reviewstatus=1;    
                     $data=array('addressed'=>1,'reviewed_flag'=> $reviewstatus,'reviewed_date'=>\Carbon\Carbon::now(),'addressed_date'=>\Carbon\Carbon::now());

                    $Second_nettime= date("H:i:s",strtotime($time[0]->vital_review_time)+strtotime($nettime));
        
                    $record_time  = CommonFunctionController::recordTimeSpent($nettime, $Second_nettime, $patientid, $module_id, $componentid, $stage_id, $billable, $uid, $step_id, $form_name);

                }
                else
                {
                    $data=array('addressed'=>1,'addressed_date'=>\Carbon\Carbon::now());
                }    
                      
                $updateheartrate = Observation_Heartrate::where('id',$rpm_observation_id_hr[$i])->update($data);

                $billingstatuscheck = Observation_Heartrate::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');           
            }
     
        
            $billarray = $billingstatuscheck->toArray();
             
            if(in_array("1",$billarray)){
             
            }
            else{
                
                if($unit=='lbs' || $unit==1){  
                    $vital = 'Weight';
                    $updateoxy = Observation_Weight::where('id',$rpm_observation_id_bp[$i])->update(['billing'=> 1]);                     
                }
                else if($unit=='%' || $unit==2){  
                    $vital = 'Oxygen';
                    $updateoxy = Observation_Oxymeter::where('id',$rpm_observation_id_bp[$i])->update(['billing'=> 1]);                     
                }
                else if($unit=='mm[Hg]'  || $unit=='mmHg' || $unit==3){  
                    $vital = 'Blood Pressure';        
                    $updatebp = Observation_BP::where('id',$rpm_observation_id_bp[$i])->update(['billing'=> 1]);
                }               
                else if($unit=='L' || $unit=='L/min' || $unit==5){  
                    $vital = 'Spirometer';        
                    $updatespirometer = Observation_Spirometer::where('id',$rpm_observation_id_bp[$i])->update(['billing'=> 1]);
                }
                else if($unit=='mg/dl' || $unit==6){  
                    $vital = 'Glucose';        
                    $updateglucose = Observation_Glucose::where('id',$rpm_observation_id_bp[$i])->update(['billing'=> 1]);
                }

                if( !empty($hrunit) && $hrunit=='beats/minute' ){  
                     $vital_hr = 'Heartrate';        
                    $updateheartrate = Observation_Heartrate::where('id',$rpm_observation_id_hr[$i])->update(['billing'=> 1]);
                } 
                $start_time=$nettime;
  
            }

            //============= this is for heartrate=====================//
            if(!empty($hrunit)  && $hrunit=="beats/minute"){

                  $b = array(
                    'notes' => $notes, 
                    'device_id' => $deviceid_hr, 
                    'patient_id' => $patientid,
                    'rpm_observation_id' => $rpm_observation_id_hr[$i],
                    'observation_id' => $observationid_hr,
                    'created_by'=>$uid,
                    'updated_by'=>$uid
                    );                    
                $checkvitalnotes_hr = VitalsObservationNotes::where('patient_id',$patientid)
                                    ->where('device_id',$deviceid_hr)
                                    ->where('rpm_observation_id',$rpm_observation_id_hr[$i])
                                    ->get();                     
        

                if($checkvitalnotes_hr->isEmpty()){
                    VitalsObservationNotes::create($b);           
                }
                else{
                    VitalsObservationNotes::where('id',$checkvitalnotes_hr[0]->id)->update($b); 
                }   
            }
            // =================this is for BP,oxi,glu,etc..==========================       
            $a = array(
                'notes' => $notes, 
                'device_id' => $deviceid, 
                'patient_id' => $patientid,
                'rpm_observation_id' => $rpm_observation_id_bp[$i],
                'observation_id' => $observationid,
                'created_by'=>$uid,
                'updated_by'=>$uid
                );                    
            $checkvitalnotes = VitalsObservationNotes::where('patient_id',$patientid)
                                ->where('device_id',$deviceid)
                                ->where('rpm_observation_id',$rpm_observation_id_bp[$i])
                                ->get();                     
            

            if($checkvitalnotes->isEmpty()){
                VitalsObservationNotes::create($a);           
            }
            else{
                VitalsObservationNotes::where('id',$checkvitalnotes[0]->id)->update($a); 
            }   
            //====================================================================//
      
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
        
         if(count(explode(',', $newReportedDate))>1){
            $str="have";
         }else{
            $str="has";
         }

        $topic = $vital.' alert reported on '.$newReportedDate.' '. $str .' been addressed by '.$name.' on ' .$newReviewDate.' at '.$reviewTime;

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

        if($deviceid==3 || $deviceid==2){
           $topic_hr = $vital_hr.' alert reported on '.$newReportedDate.' '. $str .' been addressed by '.$name.' on ' .$newReviewDate.' at '.$reviewTime;

        $CallWrap_hr = array('uid'=>$uid,
                    'record_date'=>$nwdt,
                    'status'=>1, 
                    'topic'=>$topic_hr,
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
                    'device_id'=>$deviceid_hr,
                    'rpm_observation_id'=>rtrim($serialid_hr, ',') 
                );
        CallWrap::create($CallWrap_hr);

        }
       
    }



    public function updateDailyReviewstatus(Request $request){ 
        $units                 = sanitizeVariable($request->rpm_unit_bp);
        $hrunits               = sanitizeVariable($request->rpm_unit_hr);
        $patient_id            = sanitizeVariable($request->care_patient_id);
        $rpm_observation_id_bp = sanitizeVariable($request->rpm_observation_id_bp);
        $rpm_observation_id_hr = sanitizeVariable($request->rpm_observation_id_hr);
        $csseffdate            = sanitizeVariable($request->csseffdate);
        $table                 = sanitizeVariable($request->table);
        $form_name              = sanitizeVariable($request->formname);
        $reviewstatus = sanitizeVariable($request->reviewstatus);
        $time         = RPMBilling::get();
        $nettime      = $time[0]->vital_review_time;
        $module_id    = getPageModuleName();
        $component_id = sanitizeVariable($request->component_id);
        $stage_id = sanitizeVariable($request->stage_id);
        $step_id = sanitizeVariable($request->step_id);
        $componentid  = (int)$component_id;
        $uid          = session()->get('userid');
        $usersdetails = Users::where('id',$uid)->get();
        $roleid       = $usersdetails[0]->role;
        $billable     = 1;
        $start_time   = sanitizeVariable($request->hd_timer_start);
        $deviceid     = sanitizeVariable($request->device_id);
        // dd($start_time);
        $serialid="";
        $serialid_hr="";
        
            $myDateTime   =  explode(" ",$csseffdate);
            $mydates      = explode("-",$myDateTime[0]); 
            $effectivedate= $mydates[2]."-".$mydates[0]."-".$mydates[1];
            $datearray[]=  date("F j", strtotime($effectivedate));
           
            $fromdate     =$effectivedate." "."00:00:00";    
            $dt1          = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
            $todate       = $effectivedate ." "."23:59:59"; 
            $dt2          = DatesTimezoneConversion::userToConfigTimeStamp($todate); //"2021-03-12 18:29:59" 
                     
                
            $serialid.=$rpm_observation_id_bp.",";
            $serialid_hr.=$rpm_observation_id_hr.",";
            $unit=$units;
            $hrunit=$hrunits;
            $patientid=$patient_id;
            
          //  $nettime= date("H:i:s",strtotime($time[0]->vital_review_time)+strtotime($start_time));
        
            $nettime= $start_time;
        
       

            if($unit=='lbs' || $unit==1){  
                $vital = 'Weight';
                $updateweight = Observation_Weight::where('id',$rpm_observation_id_bp)->update(['reviewed_flag'=> $reviewstatus,'reviewed_date'=> Carbon::now() ]); 

                $billingstatuscheck = Observation_Weight::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');   
            }  
            else if($unit=='%' || $unit==2){  
                $vital = 'Oxygen';
                $updateoxy = Observation_Oxymeter::where('id',$rpm_observation_id_bp)->update(['reviewed_flag'=> $reviewstatus,'reviewed_date'=> Carbon::now() ]); 

                $billingstatuscheck =   Observation_Oxymeter::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');  
            }
            else if($unit=='mm[Hg]' || $unit=='mmHg' || $unit==3){  
                $vital = 'Blood Pressure'; 
                
                $updatebp=Observation_BP::where('id',$rpm_observation_id_bp)->update(['reviewed_flag'=> $reviewstatus,'reviewed_date'=> Carbon::now() ]);
                $billingstatuscheck=Observation_BP::where('patient_id',$patientid)->whereBetween('effdatetime',[$dt1 , $dt2])->pluck('billing');
                
            }        
            else if($unit=='L' || $unit=='L/min' || $unit==5){  
                $vital = 'Spirometer';  
               
                $updatespirometer = Observation_Spirometer::where('id',$rpm_observation_id_bp)->update(['reviewed_flag'=> $reviewstatus,'reviewed_date'=> Carbon::now() ]);
                $billingstatuscheck = Observation_Spirometer::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');            
            }
            else if($unit=='mg/dl' || $unit==6){             
                $vital = 'Glucose';  
               
                $updateglucose = Observation_Glucose::where('id',$rpm_observation_id_bp)->update(['reviewed_flag'=> $reviewstatus,'reviewed_date'=> Carbon::now() ]);
                $billingstatuscheck = Observation_Glucose::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');            
            }

            if(!empty($hrunit)  && $hrunit=='beats/minute'){ 
            
                $vital_hr = 'Heartrate'; 
                $deviceid_hr= 0;
                 $updateheartrate = Observation_Heartrate::where('id',$rpm_observation_id_hr)->update(['reviewed_flag'=> $reviewstatus,'reviewed_date'=> Carbon::now() ]);

                $billingstatuscheck_hr = Observation_Heartrate::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');           
            }
     

            $billarray = $billingstatuscheck->toArray();
             
            if(in_array("1",$billarray)){
             
            }
            else{

                
                if($unit=='lbs' || $unit==1){  
                    $vital = 'Weight';
                    $updateoxy = Observation_Weight::where('id',$rpm_observation_id_bp)->update(['billing'=> 1]);                     
                }
                else if($unit=='%' || $unit==2){  
                    $vital = 'Oxygen';
                    $updateoxy = Observation_Oxymeter::where('id',$rpm_observation_id_bp)->update(['billing'=> 1]);                     
                }
                else if($unit=='mm[Hg]'  || $unit=='mmHg' || $unit==3){  
                    $vital = 'Blood Pressure';        
                    $updatebp = Observation_BP::where('id',$rpm_observation_id_bp)->update(['billing'=> 1]);
                }               
                else if($unit=='L' || $unit=='L/min' || $unit==5){  
                    $vital = 'Spirometer';        
                    $updatespirometer = Observation_Spirometer::where('id',$rpm_observation_id_bp)->update(['billing'=> 1]);
                }
                else if($unit=='mg/dl' || $unit==6){  
                    $vital = 'Glucose';        
                    $updateglucose = Observation_Glucose::where('id',$rpm_observation_id_bp)->update(['billing'=> 1]);
                }

                

                if($reviewstatus == 1 && $table=="parent"){
                  //  $nettime= date("H:i:s",strtotime($nettime)+strtotime($start_time));
                    $billable=1;
                    $nettime= date("H:i:s",strtotime($time[0]->vital_review_time)+strtotime($start_time));
                    $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid,$stage_id, $billable, $uid, $step_id, $form_name);
                }
            }     
             

            $billarray_hr = $billingstatuscheck_hr->toArray();
             
            if(in_array("1",$billarray_hr)){
             
            }
            else{
              if( !empty($hrunit) && $hrunit=='beats/minute' ){  
                     $vital_hr = 'Heartrate';        
                    $updateheartrate = Observation_Heartrate::where('id',$rpm_observation_id_hr)->update(['billing'=> 1]);
                } 


                if($reviewstatus == 1 && $table=="parent"){
                    $billable=1;

                    $Second_nettime= date("H:i:s",strtotime($time[0]->vital_review_time)+strtotime($nettime));
                   // dd("$nettime".$nettime."=".$time[0]->vital_review_time."=".$Second_nettime);
   
                    $record_time  = CommonFunctionController::recordTimeSpent($nettime, $Second_nettime, $patientid, $module_id, $componentid,$stage_id, $billable, $uid, $step_id, $form_name);
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
        
         

        $topic = 'Observation reviewed';
        $notes = $name.' reviewed '.$vital.' on '.$newReviewDate.' at '.$reviewTime;
        

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

        if($deviceid==3 || $deviceid==2){
          
            
            $notes_hr = $name.' reviewed '.$vital_hr.' on '.$newReviewDate.' at '.$reviewTime;
        
         
            $CallWrap_hr = array('uid'=>$uid,
                        'record_date'=>$nwdt,
                        'status'=>1, 
                        'topic'=>$topic,
                        'notes'=>$notes_hr,
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
                        'device_id'=>$deviceid_hr,
                        'rpm_observation_id'=>rtrim($serialid_hr, ',') 
                    );
            CallWrap::create($CallWrap_hr);

        }
       
    }    
    

}