<?php
namespace RCare\Rpm\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use Illuminate\Http\Request;
use RCare\Rpm\Models\Patient;
use RCare\Patients\Models\PatientPartResearchStudy;
use RCare\Patients\Models\PatientPersonalNotes;

use RCare\Patients\Models\Patients;
use RCare\Rpm\Models\Devices;
use RCare\Rpm\Models\VitalsAlertNotes;
use RCare\Rpm\Models\Observation_Oxymeter;
use RCare\Rpm\Models\Observation_BP;
use RCare\Rpm\Models\Observation_Heartrate;
use RCare\Rpm\Models\Observation_Glucose;
use RCare\Rpm\Models\Observation_Weight; 
use RCare\Rpm\Models\PatientTimeRecord;
// use RCare\Rpm\Models\DeviceTraining;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;

use RCare\Patients\Models\PatientAddress; 
use RCare\Patients\Models\PatientDevices;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Patients\Models\PatientDemographics;
use RCare\Patients\Models\PatientThreshold;
use RCare\Patients\Models\PatientTimeRecords;
use DB;
use Hash;
use Validator,Redirect,Response;
use DataTables;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Session;     
use RCare\System\Traits\DatesTimezoneConversion; 
use RCare\Org\OrgPackages\Users\src\Models\Users; 
use RCare\Org\OrgPackages\RPMBillingConfiguration\src\Models\RPMBilling;

// use RCare\Rpm\Models\Observation_Oxymeter;
// use RCare\Rpm\Models\Observation_BP;
// use RCare\Rpm\Models\Observation_Heartrate;
// use RCare\Rpm\Models\Observation_Spirometer;
// use RCare\Rpm\Models\Observation_Glucose;

class AlertHistoryController extends Controller
{
      
    public function getAlertHistory(Request $request)
        {
            $uid = session()->get('userid');
            $usersdetails = Users::where('id',$uid)->get();
            $roleid = $usersdetails[0]->role;

            return view('Rpm::alert-history.alert-history',compact('roleid')); 
        }    





      public function getAlertHistoryData(Request $request){   


        $practicesgrp = sanitizeVariable($request->route('practicesgrp')); 
         $caremanagerid  = sanitizeVariable($request->route('caremanagerid'));          
        $practices = sanitizeVariable($request->route('practices'));
        $provider = sanitizeVariable($request->route('provider'));
        $timeframe = sanitizeVariable($request->route('timeframe'));
        $patient = sanitizeVariable($request->route('patient'));
        $uid = session()->get('userid');
        $usersdetails = Users::where('id',$uid)->get();
        $roleid = $usersdetails[0]->role;

        $patient_id=sanitizeVariable($request->route('patientid'));
        $exportmonth=sanitizeVariable($request->route('month'));

        
        if($patient_id!="" || $patient_id!=null)
        {
          $timeframe = '30';
              if($exportmonth < 10)
            {
              $exportmonth='0'.$exportmonth;
            }
            $year = date("Y");
            $Exfromdate=$year."-".$exportmonth."-01 00:00:00";
            $Extoate=$year."-".$exportmonth."-30 23:59:59";
           $dt1 =$Exfromdate;   
           $dt2 = $Extoate ;
           $patient=$patient_id;
        }
        else if($timeframe=="null" || $timeframe=="")
        {
            $timeframe = 'null';
            $dt1 = DatesTimezoneConversion::userToConfigTimeStamp(date('Y-m-d H:i:s'));    
            $dt2 = DatesTimezoneConversion::userToConfigTimeStamp(date('Y-m-d H:i:s')); 
           //  $dt1 = date('Y-m-d H:i:s');$dt2 =date('Y-m-d H:i:s');             
        }
        else{          
                $date =date('Y-m-d H:i:s'); 
                $timeframedate = date('Y-m-d H:i:s', strtotime(' -'.$timeframe." days"));              
                $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($timeframedate);    
                $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($date);                       
        }
       
        $configTZ = config('app.timezone');
        $userTZ  = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 

        $pracgrp; 
        $p;
        $pr;
        $pat;
        $cr;
        $totime;
        $totimeoption;

        if( $practicesgrp=='null' || $practicesgrp=="" || $practicesgrp==null)
        {
          $pracgrp = 'null';
        }
        else
        {
           if( $practicesgrp==0)
           {
             $pracgrp = 0;  
           }
           else{
             $pracgrp = $practicesgrp;
           }  
        
        }

        if( $practices=='null' || $practices=="" || $practices==null)
        {
          $p = 'null';           
        }
        else
        {
        if( $practices==0)
           {
             $p = 0;  
           }
           else{
             $p = $practices;
           }   
        }


        if($provider=='null' || $provider=="" || $provider==null)
        {
           $pr = 'null';           
        }
        else{
            if( $provider==0) 
            {
                $pr = 0;  
            }
            else
            {
                $pr = $provider;
            }
        }

        if($caremanagerid=='null' || $caremanagerid=="" || $caremanagerid==null)
        {
          $cr = 'null';          
        }
        else{
              if( $caremanagerid==0) 
            {
                $cr = 0;  
            }
            else
            {
                $cr = $caremanagerid;
            }
        }

      
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
                    threshold,
                     deviceid,
                    threshold_type,
                    notes
                    from patients.sp_alerthistory($pracgrp,$p,$pr,$cr,$patient,timestamp '".$dt1."',timestamp '".$dt2."',$timeframe,$roleid, $uid) order by csseffdate desc";
            
              
       //  dd($query);
        $data = DB::select( DB::raw($query) ); 
     
        return Datatables::of($data) 
            ->addIndexColumn()
            ->addColumn('action', function($row){
               // if($patient_id=="" || $patient_id==null)
               //  { 

               //  $btn ='<div id="notesdiv" style="display:none"><label>'.$row->notes.'</label></div>';
               //  }
               //  else
               //  {
                  $btn ='<a href="#"  title="Start" ><button type="button" class="btn btn-primary activealertpatientstatus" name="activealertpatientstatus" id="activealertpatientstatus_'.$row->pid.'">Click</button></a><input type="hidden" class="loadclass">';  
                // }
                
                return $btn;
            })
            ->addColumn('review-patients', function($row){
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
                // $btn ='<a href="/rpm/patient-alert-data-list/'.$row->pid.'/'.$unittable.'/'.$dt1.'/'.$dt2.'" title="Start" ><button type="button" id="detailsbutton" class="btn btn-primary">Click</button></a>';
                $btn ='<a href="/rpm/patient-alert-data-list/'.$row->pid.'/'.$unittable.'/'.$row->deviceid.'" title="Start" ><button type="button" class="btn btn-primary">Click</button></a>';
                return $btn;
            })
            ->addColumn('patients', function($row){
                $btn ='<a href="/rpm/monthly-monitoring/'.$row->pid.'" title="Start" >'.$row->pfname.' '.$row->plname.'</a>';
                return $btn;
            })
            ->rawColumns(['action','review-patients','patients'])  
            ->make(true);    
               

    }

    public function getPatientDataAlert($patientid,$unit,$deviceid)
    {
        $patientid = sanitizeVariable($patientid);
        $unit = sanitizeVariable($unit);
        $deviceid= sanitizeVariable($deviceid);
        $patient = Patients::where('id',$patientid)->get();
        $devices = Devices::where('status','1')->get();
        $module_id = getPageModuleName(); 
        $PatientAddress = PatientAddress::where('uid',$patientid);

        $personal_notes = (PatientPersonalNotes::latest($patientid,'patient_id') ? PatientPersonalNotes::latest($patientid,'patient_id')->population() : "");
        $research_study = (PatientPartResearchStudy::latest($patientid,'patient_id') ? PatientPartResearchStudy::latest($patientid,'patient_id')->population() : "");
        $patient_threshold = (PatientThreshold::latest($patientid,'patient_id') ? PatientThreshold::latest($patientid,'patient_id')->population() : "");
        $patient_providers = PatientProvider::where('patient_id', $patientid)
       ->with('practice')->with('provider')->with('users')->where('provider_type_id',1)->orderby('id','desc')->first();   
        $patient_enroll_date = PatientServices::latest_module($patientid, $module_id);
        $last_time_spend = CommonFunctionController::getNetTimeBasedOnModule($patientid, $module_id);
        $services = Module::where('patients_service',1)->where('status',1)->get();
        $patient_demographics = PatientDemographics::where('patient_id', $patientid)->latest()->first();
        $PatientAddress = PatientAddress::where('patient_id', $patientid)->latest()->first();
        $PatientDevices = PatientDevices::where('patient_id', $patientid)->orderby('id','desc')->get();   
        
        return  view('Rpm::alert-history.patient-alert-data-list',compact('patient','unit','devices', 'PatientAddress', 'patient_providers', 'patient_enroll_date', 'last_time_spend', 'services', 'patient_demographics', 'personal_notes', 'research_study','patient_threshold','PatientDevices','deviceid')); 
        
     
    }

    public function getPatientDataAlertAccordingtoDevice(Request $request)
    {
        $configTZ = config('app.timezone');
        $userTZ  = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        $patient = sanitizeVariable($request->route('patientid'));  
        // dd($patient);
       // $unit =   sanitizeVariable($request->route('unit')); 
        $fromdate=sanitizeVariable($request->route('fromdate'));
        $todate=sanitizeVariable($request->route('todate'));
        $deviceid=sanitizeVariable($request->route('deviceid'));
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
        tempid,
        pid, 
        readingone,
        readingtwo,
        heartratereading,
        threshold,
        heartrate_threshold,
        to_char(effdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csseffdate  
        from patients.sp_patientdetailvitalalerts($pat,timestamp '".$dt1."',timestamp '".$dt2."',$deviceid)";       
        
         $data = DB::select( DB::raw($query) ); 
               $ddata['DATA']=array();
          for($i=0;$i<count($data);$i++)
          {
             if(is_null($data[$i]->csseffdate)){
                    $data[$i]->csseffdate='';
                  }
            if(is_null($data[$i]->readingone)){
                    $data[$i]->readingone='';
                  }
            if(is_null($data[$i]->readingtwo)){
                    $data[$i]->readingtwo='';
                  }
            if(is_null($data[$i]->threshold)){
                    $data[$i]->threshold='';
                  }
              if(is_null($data[$i]->heartrate_threshold)){
                    $data[$i]->heartrate_threshold='';
                  } 
             if(is_null($data[$i]->heartratereading)){
                    $data[$i]->heartratereading='';
                  }    
             if($deviceid=='1' || $deviceid=='4' || $deviceid=='6')  
             {  
               $arrydata=array($data[$i]->csseffdate,$data[$i]->threshold,$data[$i]->readingone);
             }
             elseif($deviceid=='2' || $deviceid=='3'){
               $arrydata=array($data[$i]->csseffdate,$data[$i]->threshold,$data[$i]->readingone, $data[$i]->readingtwo,$data[$i]->heartrate_threshold,$data[$i]->heartratereading);                
             }
             else
             {
                 $arrydata=array($data[$i]->csseffdate,$data[$i]->threshold,$data[$i]->readingone, $data[$i]->readingtwo);
             }
             $ddata['DATA'][]=$arrydata;
          }
             
           if($deviceid=='1')  
           {
             $columnheader=array("Timestamp","Threshold","Weight");
           }
           elseif($deviceid=='2'){
               $columnheader=array("Timestamp","Threshold","SpO2","Perfusion Index","Threshold","Heartrate");
           }
           elseif($deviceid=='3'){
               $columnheader=array("Timestamp","Threshold","Systolic","Diastolic","Threshold","Heartrate");
           }
           elseif($deviceid=='4'){
               $columnheader=array("Timestamp","Threshold","Temperature");
           }
            elseif($deviceid=='5'){
               $columnheader=array("Timestamp","Threshold","FEV1 Value","PEF Value");
           }
           else
           {
              $columnheader=array("Timestamp","Threshold","Glucose Level");
           }

              $dynamicheader=array();
             for($m=0;$m<count($columnheader);$m++)
             {
              $dynamicheader[]=array("title"=>$columnheader[$m]);
             }

              $fdata['COLUMNS']=$dynamicheader;
    
            $finldata=array_merge($fdata,$ddata);
          
             return json_encode($finldata);
         
    }

      public function saveRpmNotess(Request $request){
        // dd($request->all());
        $notes = sanitizeVariable($request->notes);
        $vital = sanitizeVariable($request->vital);
        $patient_id = sanitizeVariable($request->patient_id);
        $rpm_observation_id = sanitizeVariable($request->rpm_observation_id); 
        $a = array(
        'notes' => $notes, 
        'vital' => $vital, 
        'patient_id' =>  $patient_id,
        'rpm_observation_id' => $rpm_observation_id,
        'observation_id' => null);
        VitalsAlertNotes::create($a);

        $formname     = explode(",",sanitizeVariable($request->formname));
        // $formname1     = sanitizeVariable($request->form_name);

        $time         = RPMBilling::get();
        $nettime      = $time[0]->vital_review_time;
        $module_id    = getPageModuleName();
        $component_id = sanitizeVariable($request->component_id);
        $componentid  = (int)$component_id;
        $uid = session()->get('userid');
        $usersdetails = Users::where('id',$uid)->get();
        $roleid       = $usersdetails[0]->role; 
        $billable     = 1;
        $start_time   = sanitizeVariable($request->hd_timer_start);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = sanitizeVariable($request->step_id);


        $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patient_id, $module_id,
                                     $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);


        $time         = RPMBilling::get(); 
        $nettime      = $time[0]->vital_review_time;
        $module_id    = getPageModuleName();
        $component_id = sanitizeVariable($request->component_id); 
        $componentid  = (int)$component_id;
        $uid = session()->get('userid');
        $usersdetails = Users::where('id',$uid)->get();
        $roleid       = $usersdetails[0]->role; 
        $billable     = 1;
        $start_time   = sanitizeVariable($request->hd_timer_start);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = sanitizeVariable($request->add_step_id);


        $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patient_id, $module_id,
                                     $componentid,  $stage_id, $billable, $uid, $step_id,$formname[0]);

        // if($vital=='Heartrate'){
        //     Observation_Heartrate::where('id',$rpm_observation_id)->update(['addressed'=>1]);
        // }
        // else if($vital=='Blood Pressure'){
        //     Observation_BP::where('id',$rpm_observation_id)->update(['addressed'=>1]);
        // }
        // else if($vital=='Oxygen'){
        //     Observation_Oxymeter::where('id',$rpm_observation_id)->update(['addressed'=>1]);
        // }
        // else{

        // }
    }

    public function populateVitalsAlertNotes($patientid,$vital,$csseffdate,$reading){
        // dd($patientid,$vital,$csseffdate,$reading); 
        $patientid = sanitizeVariable($patientid);
        $vital = sanitizeVariable($vital);
        $csseffdate = sanitizeVariable($csseffdate);
        $newDate = date("Y-m-d H:i:s", strtotime($csseffdate));
      
        
    

        $t = explode(" ",$csseffdate);
        $d = explode("-",$t[0]);
        // dd($d);
        $month = $d[0];
        $day = $d[1];
        $year = $d[2];
        $time = $t[1];
        $newdatetime = $d[2] ."-".$d[0]."-".$d[1]." ".$time;
        // dd($csseffdate,$newdatetime);


        $reading = sanitizeVariable($reading);
        // $n = '2021-03-12 23:13:07';
        // $csseffdateconverted = DatesTimezoneConversion::userToConfigTimeStamp($n);
        $csseffdateconverted = DatesTimezoneConversion::userToConfigTimeStamp($newdatetime);

        //$c = DatesTimezoneConversion::userNewTimeStamp($n); //2021-03-13 04:43:07
        // dd($reading,$csseffdateconverted); 


        if($vital == 'Heartrate')
        {
            // dd($vital);
            $rpmid = Observation_Heartrate::where('patient_id',$patientid)
                    ->where('resting_heartrate',$reading)
                    ->where('effdatetime',$csseffdateconverted)
                    ->where('alert_status',1)
                    ->get('id');
                    // dd($rpmid);
        }
        else if($vital == 'Blood Pressure'){
            
            $a = explode("-",$reading); 
            $systolicreading = $a[0];
            $diastolicreading = $a[1];

            $rpmid = Observation_BP::where('patient_id',$patientid)
                    ->where('systolic_qty',$systolicreading)
                    ->where('diastolic_qty',$diastolicreading)
                    ->where('effdatetime',$csseffdateconverted)
                    ->where('alert_status',1)
                    ->get('id');
        }
        else if($vital == 'Oxygen'){

            $rpmid = Observation_Oxymeter::where('patient_id',$patientid)
                    ->where('oxy_qty',$reading)
                    ->where('effdatetime',$csseffdateconverted)
                    ->where('alert_status',1)
                    ->get('id');

                    // dd($rpmid[0]->id); 
                    
                    // 06-16-2021 18:46:51
        }
        else if($vital == 'Weight'){

            $rpmid = Observation_Weight::where('patient_id',$patientid)
                    ->where('weight',$reading)
                    ->where('effdatetime',$csseffdateconverted)
                    ->where('alert_status',1)
                    ->get('id');
        }
        else if($vital == 'Glucose'){

            $rpmid = Observation_Glucose::where('patient_id',$patientid)
                    ->where('value',$reading)
                    ->where('effdatetime',$csseffdateconverted)
                    ->where('alert_status',1)
                    ->get('id');
        }
        else if($vital == 'Temperature'){

        }
        else{

            $a = explode("-",$reading); 
            $fevreading = $a[0];
            $pefreading = $a[1]; 


            $rpmid = Observation_Spirometer::where('patient_id',$patientid)
                    ->where('fev_value',$fevreading)
                    ->where('pef_value',$pefreading)
                    ->where('effdatetime',$csseffdateconverted)
                    ->where('alert_status',1)
                    ->get('id');
        }

        if($rpmid->isEmpty()){
        }
        else{
            $data = VitalsAlertNotes::where('patient_id',$patientid)->where('vital',$vital)->where('rpm_observation_id',$rpmid[0]->id)->get();
            // $data = (WebhookObservation::self($id) ? WebhookObservation::self($id)->population() : "");
            // dd($data);   
            $result['rpm_cm_form'] = $data;
            
            return $result;
        }
       
        

    }


} 