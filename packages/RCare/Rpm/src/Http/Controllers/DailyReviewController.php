<?php
namespace RCare\Rpm\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use Illuminate\Http\Request;
use RCare\Rpm\Models\Patient;
// use RCare\Rpm\Models\MailTemplate;
use RCare\Org\OrgPackages\QCTemplates\src\Models\ContentTemplate;
use RCare\Rpm\Models\Template;
use RCare\Rpm\Models\RcareServices;
use RCare\Rpm\Models\RcareSubServices;
use RCare\Rpm\Models\Questionnaire;
use RCare\Rpm\Models\PatientEnrollment;
use RCare\Rpm\Models\Devices;
use RCare\Rpm\Models\Observation_Weight;
use RCare\Rpm\Models\Observation_Oxymeter;
use RCare\Rpm\Models\Observation_BP;
use RCare\Rpm\Models\Observation_Heartrate;
use RCare\Rpm\Models\Observation_Spirometer;
use RCare\Rpm\Models\Observation_Glucose;


use RCare\Rpm\Models\DeviceTraining;
use RCare\Rpm\Models\PatientTimeRecord;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientAddress; 
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Patients\Models\PatientDemographics;
use RCare\Rpm\Http\Requests\DeviceTraningRequest;
use RCare\Patients\Models\PatientPartResearchStudy;
use RCare\Patients\Models\PatientPersonalNotes;
use RCare\Patients\Models\PatientTimeRecords;
use RCare\Org\OrgPackages\RPMBillingConfiguration\src\Models\RPMBilling;
use DB;
use Hash;
use Validator,Redirect,Response;
use DataTables;
use Illuminate\Support\Facades\Log;  
use Carbon\Carbon;
use Session;
use RCare\System\Traits\DatesTimezoneConversion; 
use RCare\Patients\Models\PatientThreshold;
use RCare\Org\OrgPackages\Threshold\src\Models\GroupThreshold;
use RCare\Org\OrgPackages\Practices\src\Models\PracticeThreshold;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use RCare\Org\OrgPackages\Users\src\Models\OrgUserRole;
use RCare\Org\OrgPackages\Roles\src\Models\Roles;
use RCare\Ccm\Models\CallWrap;
use RCare\Patients\Models\VitalsObservationNotes;
use RCare\Rpm\Http\Requests\RPMWorklistRequest;
use RCare\Rpm\Models\Other_Alerts;

class DailyReviewController extends Controller
{
    public function index()
    {
        return view('Rpm::patient.patient-list');    
    }

    public function getDailyReview(Request $request)
    {
        $JobPhase1 = \DB::table('rpm.observations_oxymeter')->where('reviewed_flag',0)->select('effdatetime')->min('effdatetime');
        $JobPhase2 = \DB::table('rpm.observations_heartrate')->where('reviewed_flag',0)->select('effdatetime')->min('effdatetime');
        $JobPhase3 = \DB::table('rpm.observations_bp')->where('reviewed_flag',0)->select('effdatetime')->min('effdatetime');
        $JobPhase4 = \DB::table('rpm.observations_glucose')->where('reviewed_flag',0)->select('effdatetime')->min('effdatetime');
        $JobPhase5 = \DB::table('rpm.observations_spirometer')->where('reviewed_flag',0)->select('effdatetime')->min('effdatetime');
        $JobPhase6 = \DB::table('rpm.observations_temp')->where('reviewed_flag',0)->select('effdatetime')->min('effdatetime');
        $JobPhase7 = \DB::table('rpm.observations_weight')->where('reviewed_flag',0)->select('effdatetime')->min('effdatetime');
        
        $rpmarray = array($JobPhase1,$JobPhase2, $JobPhase3,$JobPhase4,$JobPhase5,$JobPhase6,$JobPhase7);
        $rpmfilterarray = array_filter($rpmarray);
        if(count($rpmfilterarray)>0){
            $a =  min($rpmfilterarray); 
            //userToConfigTimeStamp dont use this function 
            $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($a);
            $datetimearray = explode(" ", $dt1);
            $mineffdate = $datetimearray[0];
            
        }
        else{
            $currentdate = \Carbon\Carbon::now();
            $datetimearray = explode(" ", $currentdate);          
            $mineffdate = $datetimearray[0];
            // $mineffdate = "";
            
        }
      

        $maxJobPhase1 = \DB::table('rpm.observations_oxymeter')->where('reviewed_flag',0)->select('effdatetime')->max('effdatetime');
        $maxJobPhase2 = \DB::table('rpm.observations_heartrate')->where('reviewed_flag',0)->select('effdatetime')->max('effdatetime');
        $maxJobPhase3 = \DB::table('rpm.observations_bp')->where('reviewed_flag',0)->select('effdatetime')->max('effdatetime');
        $maxJobPhase4 = \DB::table('rpm.observations_glucose')->where('reviewed_flag',0)->select('effdatetime')->max('effdatetime');
        $maxJobPhase5 = \DB::table('rpm.observations_spirometer')->where('reviewed_flag',0)->select('effdatetime')->max('effdatetime');
        $maxJobPhase6 = \DB::table('rpm.observations_temp')->where('reviewed_flag',0)->select('effdatetime')->max('effdatetime');
        $maxJobPhase7 = \DB::table('rpm.observations_weight')->where('reviewed_flag',0)->select('effdatetime')->max('effdatetime');
       
        $maxdate =      max($maxJobPhase1,$maxJobPhase2, $maxJobPhase3,$maxJobPhase4,$maxJobPhase5,$maxJobPhase6,$maxJobPhase7);
        // dd($maxdate); 
        // $dt2 = DatesTimezoneConversion::userTimeStamp($maxdate);
        $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($maxdate);      
        $maxdatetimearray = explode(" ", $dt2);
        $maxeffdate = $maxdatetimearray[0];
       


        if($maxeffdate=="" || $maxeffdate==null){
            $currentdate = \Carbon\Carbon::now();
            $datetimearray = explode(" ", $currentdate);
            $maxeffdate = $datetimearray[0]; 
        
            
        }   
       
      

        $uid = session()->get('userid');
        $usersdetails = Users::where('id',$uid)->get();
        $roleid = $usersdetails[0]->role;   
        // $roleid = 5;

        return view('Rpm::daily-review.daily-review',compact('mineffdate','maxeffdate','roleid')); 

    }

    public function exportWorlistData()
    {
        $configTZ = config('app.timezone');
        $userTZ  = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
        $dt1='2021-08-01 23:59:59';
        $dt2='2021-08-24 23:59:59';
        $reviewchilddata =[];
        $query = "select
        rwserialid,   
        pid, 
        reading,
        vital_unit,
        to_char(effdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csseffdate,
        reviewedflag,
        rwaddressed,
        alert,
        vitaldeviceid,
        pfname, 
        plname,
        pmname, 
        pdob , 
        pprofileimg, 
        practicename, 
        providername, 
        caremanagerfname,
        caremanagerlname,
        rppracticeid,
        vital_threshold,
        rwthreshold_type,
        vital_name
        from rpm.sp_reviewdailydata(null, null, null,null,null,timestamp'".$dt1."', timestamp'".$dt2."',null,null,null) order by csseffdate desc limit 8";     
    //   dd($query);  
        $reviewdata1 = DB::select( DB::raw($query) );   
        $reviewdata=array_filter($reviewdata1);
       $childresult=[];
       $i=0;
       if(!empty($reviewdata))
       {
        foreach($reviewdata as $r){
            $i++;
            /*if($r->vital_unit!='null')
            {   
                if( $r->vital_unit=="beats/minute"){
                    $unit = 0;
                }
                else if( $r->vital_unit=="lbs"){
                    $unit = 1;
                }
                else if( $r->vital_unit=="%"){  
                    $unit = 2;
                }
                else if( $r->vital_unit=="mm[Hg]" || $r->vital_unit=="mmHg"){  
                    $unit = 3;
                }
                else if( $r->vital_unit=="degrees F"){
                     $unit = 4;  //for temperature
                }
                else if( $r->vital_unit=="mg/dl"){
                    $unit = 6;
                }
                else{
                    $unit = 5;//spirometer pef and fev value
                }   

            }
            else{ 
                $unit = 'null';
            } */
            if( $r->vital_unit=="beats/minute"){
                $unit = 0;
            }else{
                $unit =$r->vitaldeviceid;
            }
           
            $serialid = $r->rwserialid;
            $pat = $r->pid;
            $reviewedstatus = $r->reviewedflag;
            $onlydate = explode(" ",$r->csseffdate);
            $datetimearray = explode("-",$onlydate[0]);
         
            // dd($datetimearray);
            $arr = array($datetimearray[2],$datetimearray[0],$datetimearray[1]);                                   
            $neweffdate =implode("-",$arr);
            
            $fromdate =$neweffdate." "."00:00:00";    
            $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
            $todate = $neweffdate ." "."23:59:59"; 
            $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate); //"2021-03-12 18:29:59" 
            $childquery = "select  
            tempid,
            pid, 
            reading,
            unit,
            to_char(effdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csseffdate,
            reviewedflag,
            addressed,
            alert,
            pfname, 
            plname,
            pmname, 
            pdob , 
            pprofileimg, 
            practicename, 
            providername,  
            caremanagerfname,  
            caremanagerlname,
            vitalthreshold,
            thresholdtype,
            deviceid,
            vital_name
            from rpm.sp_childreviewdailydata(null, null, null,null,'".$pat."',timestamp'".$dt1."',timestamp'".$dt2."','".$unit."','".$reviewedstatus."','".$serialid."')";    
        
            $reviewchilddata = DB::select( DB::raw($childquery) );
            $r->results=$reviewchilddata;
            //array_push($reviewdata,$r->results);
            //$r->childdatacount = count($reviewchilddata);
            
        }

         return json_encode($reviewdata);
     }
       
    }

   
    public function getDailyReviewData(Request $request)
    {
          
        $practicesgrp = sanitizeVariable($request->route('practicesgrp')); 
        $caremanagerid  = sanitizeVariable($request->route('caremanagerid')); 
        $practices = sanitizeVariable($request->route('practices'));
        $provider = sanitizeVariable($request->route('provider'));
        $patient = sanitizeVariable($request->route('patient'));
        $fromeffdate = sanitizeVariable($request->route('fromeffdate'));
        $toeffdate = sanitizeVariable($request->route('toeffdate'));
        $reviewedstatus = sanitizeVariable($request->route('reviewedstatus'));
        $uid = session()->get('userid');
        $usersdetails = Users::where('id',$uid)->get();
        $roleid = $usersdetails[0]->role;  
        // dd($neweffdate);
        $configTZ = config('app.timezone');
        $userTZ  = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
        $pracgrp; 
        $p;
        $pr;
        $pat;
        $cr;
        $totime;
        $totimeoption;
   
        
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


        if($provider!='null')
        {
            if( $provider==0) 
            {
                $pr = 0;  
            }
            else
            {
                $pr = $provider;
            }
        }
        else{
            $pr = 'null';
        }

        if($caremanagerid!='null')
        {
            if( $caremanagerid==0) 
            {
                $cr = 0;  
            }
            else
            {
                $cr = $caremanagerid;
            }
        }
        else{
            $cr = 'null';
        }

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

        if($reviewedstatus=='null'){
            $reviewedstatus = 0;
        }

       
        if($fromeffdate=='null' || $fromeffdate=='' || $fromeffdate=="" || $fromeffdate=="null")
         {
             
            $JobPhase1 = \DB::table('rpm.observations_oxymeter')->where('reviewed_flag',0)->select('effdatetime')->min('effdatetime');
            $JobPhase2 = \DB::table('rpm.observations_heartrate')->where('reviewed_flag',0)->select('effdatetime')->min('effdatetime');
            $JobPhase3 = \DB::table('rpm.observations_bp')->where('reviewed_flag',0)->select('effdatetime')->min('effdatetime');
            $JobPhase4 = \DB::table('rpm.observations_glucose')->where('reviewed_flag',0)->select('effdatetime')->min('effdatetime');
            $JobPhase5 = \DB::table('rpm.observations_spirometer')->where('reviewed_flag',0)->select('effdatetime')->min('effdatetime');
            $JobPhase6 = \DB::table('rpm.observations_temp')->where('reviewed_flag',0)->select('effdatetime')->min('effdatetime');
            $JobPhase7 = \DB::table('rpm.observations_weight')->where('reviewed_flag',0)->select('effdatetime')->min('effdatetime');
            $rpmarray = array($JobPhase1,$JobPhase2, $JobPhase3,$JobPhase4,$JobPhase5,$JobPhase6,$JobPhase7);
            $rpmfilterarray = array_filter($rpmarray);
            if(count($rpmfilterarray)>0){  
            $a =  min($rpmfilterarray);                
            $datetimearray = explode(" ", $a);
            $mineffdate = $datetimearray[0];   
            $dt1 =$mineffdate." "."00:00:00";    
            
            }
            else{  
                $currentdate = \Carbon\Carbon::now();
                $datetimearray = explode(" ", $currentdate);
                $mineffdate = $datetimearray[0];
                $dt1 =$mineffdate." "."00:00:00";

            }
            
           
         }
         else{
            
            $fromdate =$fromeffdate." "."00:00:00";     
            $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate); //"2021-03-11 18:30:00"
              //   dd($dt1,$dt2);
         }

         if($toeffdate=='null' || $toeffdate=='' || $toeffdate== "" || $toeffdate== "null") 
         {
            $maxJobPhase1 = \DB::table('rpm.observations_oxymeter')->where('reviewed_flag',0)->select('effdatetime')->max('effdatetime');
            $maxJobPhase2 = \DB::table('rpm.observations_heartrate')->where('reviewed_flag',0)->select('effdatetime')->max('effdatetime');
            $maxJobPhase3 = \DB::table('rpm.observations_bp')->where('reviewed_flag',0)->select('effdatetime')->max('effdatetime');
            $maxJobPhase4 = \DB::table('rpm.observations_glucose')->where('reviewed_flag',0)->select('effdatetime')->max('effdatetime');
            $maxJobPhase5 = \DB::table('rpm.observations_spirometer')->where('reviewed_flag',0)->select('effdatetime')->max('effdatetime');
            $maxJobPhase6 = \DB::table('rpm.observations_temp')->where('reviewed_flag',0)->select('effdatetime')->max('effdatetime');
            $maxJobPhase7 = \DB::table('rpm.observations_weight')->where('reviewed_flag',0)->select('effdatetime')->max('effdatetime');
            $maxdate =  max($maxJobPhase1,$maxJobPhase2, $maxJobPhase3,$maxJobPhase4,$maxJobPhase5,$maxJobPhase6,$maxJobPhase7); 
            $maxdatetimearray = explode(" ", $maxdate); 
            // dd($maxdate,$da,$maxdatetimearray);   

            $maxeffdate = $maxdatetimearray[0];
            if($maxeffdate=="" || $maxeffdate==null){
                $currentdate = \Carbon\Carbon::now();
                $datetimearray = explode(" ", $currentdate); 
                $maxeffdate = $datetimearray[0]; 
            }            
                $dt2 = $maxeffdate ." "."23:59:59"; 
         }
         else{
            $todate = $toeffdate ." "."23:59:59"; 
            $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate); //"2021-03-12 18:29:59"
         }

        

        $query = "select
        rwserialid,   
        pid, 
        reading,
        vital_unit,
        to_char(effdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csseffdate,
        reviewedflag,
        rwaddressed,
        alert,
        vitaldeviceid,
        pfname, 
        plname,
        pmname, 
        pdob , 
        pprofileimg, 
        practicename, 
        providername, 
        caremanagerfname,
        caremanagerlname,
        rppracticeid,
        vital_threshold,
        rwthreshold_type,
        vital_name,
        csslastdate
        from rpm.sp_reviewdailydata($pracgrp, $p, $pr,$cr,$pat,timestamp'".$dt1."', timestamp'".$dt2."',$reviewedstatus,$roleid,$uid) order by csseffdate";// desc limit 8";     
    //  dd($query);

        


        $reviewdata1 = DB::select( DB::raw($query) ); 
        // dd($reviewdata1);  
         $reviewdata=array_filter($reviewdata1);
          if(!empty($reviewdata))
       {
        foreach($reviewdata as $r){
            
/*            if($r->vital_unit!='null')
            {   
                if( $r->vital_unit=="beats/minute"){
                    $unit = 0;
                }
                else if( $r->vital_unit=="lbs"){
                    $unit = 1;
                }
                else if( $r->vital_unit=="%"){  
                    $unit = 2;
                }
                else if( $r->vital_unit=="mm[Hg]" || $r->vital_unit=="mmHg"){  
                    $unit = 3;
                }
                else if( $r->vital_unit=="degrees F"){
                     $unit = 4;  //for temperature
                }
                else if( $r->vital_unit=="mg/dl"){
                    $unit = 6;
                }
                else{
                    $unit = 5;//spirometer pef and fev value
                }   

            }
            else{ 
                $unit = 'null';
            } 
   */       if( $r->vital_unit=="beats/minute"){
                $unit = 0;
            }else{
                $unit=  $r->vitaldeviceid;
            }
                    
            $serialid = $r->rwserialid;
            $pat = $r->pid;
            $reviewedstatus = $r->reviewedflag;
             if($r->csseffdate==null || $r->csseffdate=='null' || $r->csseffdate=='' || $r->csseffdate=="" ){}else{
            $onlydate = explode(" ",$r->csseffdate);
           


            $datetimearray = explode("-",$onlydate[0]);
         
            // dd($datetimearray);
            $arr = array($datetimearray[2],$datetimearray[0],$datetimearray[1]);                                   
            $neweffdate =implode("-",$arr);
            
            $fromdate =$neweffdate." "."00:00:00";    
            $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
            $todate = $neweffdate ." "."23:59:59"; 
            $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate); //"2021-03-12 18:29:59" 
            }

            $childquery = "select  
            tempid,
            pid, 
            reading,
            unit,
            to_char(effdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csseffdate,
            reviewedflag,
            addressed,
            alert,
            pfname, 
            plname,
            pmname, 
            pdob , 
            pprofileimg, 
            practicename, 
            providername,  
            caremanagerfname,  
            caremanagerlname,
            vitalthreshold,
            thresholdtype,
            deviceid,
            vital_name
            from rpm.sp_childreviewdailydata(null, null, null,null,'".$pat."',timestamp'".$dt1."',timestamp'".$dt2."','".$unit."','".$reviewedstatus."','".$serialid."')";    
        
            $reviewchilddata = DB::select( DB::raw($childquery) );
            $r->childdatacount = count($reviewchilddata);
             $r->results=$reviewchilddata;
            
        }
    }

        return Datatables::of($reviewdata) 
        ->addIndexColumn()      
        ->addColumn('action', function($row){    
            $d = $row->csseffdate;
            $newdatetimearray = explode(" ", $d); 
            $neweffdate = $newdatetimearray[0];
            $runit = $row->vital_unit;
            $serialid = $row->rwserialid;
            
            if($runit == 'beats/minute'){
                $unittable ='observationsheartrate';
            }
            else if($runit == 'lbs'){
                $unittable = 'observationsweight';
            }
            else if($runit == '%'){
                $unittable = 'observationsoxymeter';
            }
            else if($runit == 'mm[Hg]' || $runit == 'mmHg'){
                $unittable ='observationsbp';    
            }
            else if($runit == 'degrees F'){
                $unittable = 'observationstemperature';
            }
            else if($runit == 'mg/dl'){
                $unittable = 'observationsglucose';
            }
            else{
                $unittable = 'observationsspirometer';
            }

            

            if($row->childdatacount==0){  
            }
            else{
                $btn = '<a href="javascript:void(0)" class="reviewdetailsclick" id="'.$row->pid.'/'.$neweffdate.'/'.$unittable.'/'.$row->reviewedflag.'/'.$serialid.'"><i data-toggle="tooltip" data-placement="top" class="plus-icons i-Add" data-original-title="View Details" ></i></a> <span style="float:right; margin-right:60px;">' .$row->childdatacount.'</span>';
                // $btn ='<img src="http://i.imgur.com/SD7Dz.png" id="'.$row->ppatient_id.'/'.$row->pfromdate.'/'.$row->ptodate.'" >';
                 return $btn;
            }
        
            }) 
        ->rawColumns(['action'])          
        ->make(true); 
     
    }

    public function getDailyReviewChildData(Request $request)
    {
        
        $patient = sanitizeVariable($request->route('patient'));
        $effdate = sanitizeVariable($request->route('effdate'));
        $unittable = sanitizeVariable($request->route('unittable'));
        $reviewedstatus = sanitizeVariable($request->route('reviewedstatus'));
        $serialid = sanitizeVariable($request->route('serialid'));
        // dd($serialid);
        $configTZ = config('app.timezone');
        $userTZ  = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
        $pat;
        $effecdate;
        $unit;
       
        $datetimearray = explode("-",$effdate);
        $arr = array($datetimearray[2],$datetimearray[0],$datetimearray[1]);                                   
        $neweffdate =implode("-",$arr);
        
        $fromdate =$neweffdate." "."00:00:00";    
        $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
        $todate = $neweffdate ." "."23:59:59"; 
        $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate); //"2021-03-12 18:29:59" 
        

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

        if($unittable!='null')
        {
            if( $unittable=='observationsbp'){  
                $unit = 3;     
            }
            else if( $unittable=='observationsoxymeter'){  
                $unit = 2;
            }
            else if( $unittable=='observationsweight'){  
                $unit = 1;
            }
            else if( $unittable=='observationsglucose'){  
                $unit = 6;
            }
            else if( $unittable=='observationsheartrate'){  
                $unit = 0;
            }
            else if( $unittable=='observationstemperature'){  
                 $unit = 4;
            }
            else{
                $unit = 5; //observationsspirometer
            }

            
        }
        else{
            $unit = 'null';
        }  

        $query = "select 
        tempid, 
        pid, 
        reading,
        unit,
        to_char(effdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as csseffdate,
        reviewedflag,
        addressed,
        alert,
        pfname, 
        plname,
        pmname, 
        pdob , 
        pprofileimg, 
        practicename, 
        providername,  
        caremanagerfname,
        caremanagerlname,
        rppracticeid,
        vitalthreshold,
        thresholdtype,
        deviceid,
        vital_name
        from rpm.sp_childreviewdailydata(null, null, null,null,'".$pat."',timestamp'".$dt1."',timestamp'".$dt2."','".$unit."','".$reviewedstatus."','".$serialid."')";  
        

        // dd($query);   
        $reviewdata = DB::select( DB::raw($query) );  
   
        foreach($reviewdata as $r)
        {
            // if($r->unit!='null')
            // {
            // if( $r->unit=="mm[Hg]"){  
            //     // $unit = 1;
            //     $pt =PatientThreshold::where('patient_id',$r->pid)->select(DB::raw("CONCAT(systoliclow,'-',systolichigh,'/',diastoliclow,'-',diastolichigh) AS systolic"))->get();
            //     if($pt->isEmpty()){
            //                 $pract  = PracticeThreshold::where('practice_id',$r->rppracticeid)->select(DB::raw("CONCAT(systoliclow,'-',systolichigh,'/',diastoliclow,'-',diastolichigh) AS systolic"))->get();
            //                 if($pract->isEmpty()){
            //                     $gp  = GroupThreshold::select(DB::raw("CONCAT(systoliclow,'-',systolichigh,'/',diastoliclow,'-',diastolichigh) AS systolic"))->get(); 
            //                     $r->thresholdrange = $gp[0]->systolic;
            //                 }
            //                 else{
            //                     $r->thresholdrange = $pract[0]->systolic;
            //                 }
            //         }
            //         else{
            //             $r->thresholdrange =  $pt[0]->systolic;
            //         } 
                
            // }
            // else if( $r->unit=="%"){  
            //     // $unit = 2;
            //     $pt =PatientThreshold::where('patient_id',$r->pid)->select(DB::raw("CONCAT(oxsatlow,'-',oxsathigh) AS oxygen"))->get();
            //     if($pt->isEmpty()){
            //                 $pract  = PracticeThreshold::where('practice_id',$r->rppracticeid)->select(DB::raw("CONCAT(oxsatlow,'-',oxsathigh) AS oxygen"))->get();
            //                 if($pract->isEmpty()){
            //                     $gp  = GroupThreshold::select(DB::raw("CONCAT(oxsatlow,'-',oxsathigh) AS oxygen"))->get(); 
            //                     $r->thresholdrange = $gp[0]->oxygen;
            //                 }
            //                 else{
            //                     $r->thresholdrange = $pract[0]->oxygen;
            //                 }
            //         }
            //         else{
            //             $r->thresholdrange =  $pt[0]->oxygen;  
            //         }

            // }
            // else{
            //     // $unit = 3;
            //     $pt =PatientThreshold::where('patient_id',$r->pid)->select(DB::raw("CONCAT(bpmlow,'-',bpmhigh) AS bpm"))->get();
            //     if($pt->isEmpty()){
            //                 $pract  = PracticeThreshold::where('practice_id',$r->rppracticeid)->select(DB::raw("CONCAT(bpmlow,'-',bpmhigh) AS bpm"))->get();
            //                 if($pract->isEmpty()){
            //                     $gp  = GroupThreshold::select(DB::raw("CONCAT(bpmlow,'-',bpmhigh) AS bpm"))->get(); 
            //                     $r->thresholdrange = $gp[0]->bpm;
            //                 }
            //                 else{
            //                     $r->thresholdrange = $pract[0]->bpm;
            //                 }
            //         }
            //         else{
            //             $r->thresholdrange =  $pt[0]->bpm;
            //         }



            //     }
                
            
            // }
            // else{
            //     $unit = 'null';
            // } 
        }

        return Datatables::of($reviewdata) 
        ->addIndexColumn()      
        ->addColumn('action', function($row){
        $btn = '<a href="javascript:void(0)" class="reviewdetailsclick" id="'.$row->pid.'/'.$row->csseffdate.'"><i data-toggle="tooltip" data-placement="top" class="plus-icons i-Add" data-original-title="View Details" ></i></a>';
               // $btn ='<img src="http://i.imgur.com/SD7Dz.png" id="'.$row->ppatient_id.'/'.$row->pfromdate.'/'.$row->ptodate.'" >';
                return $btn;
            }) 
        ->rawColumns(['action'])          
        ->make(true); 
    }


    public static function updateDailyReviewstatus(Request $request){ 
        // dd($request->all());
        $time = RPMBilling::get();
        $nettime = $time[0]->vital_review_time;
        $module_id    = getPageModuleName();
        $component_id = sanitizeVariable($request->component_id);
        $stage_id = sanitizeVariable($request->stage_id);
        $step_id = sanitizeVariable($request->step_id);
        $componentid = (int)$component_id;
        $patientid = sanitizeVariable($request->patient_id);
        $unit  = $request->unit;
        $vital_name = $request->vital_name;
        $reading = $request->reading;
        $deviceid = $request->vitaldeviceid;


        //dd($patientid+"check patient");
        $csseffdate = sanitizeVariable($request->csseffdate);
        $myDateTime =  explode(" ",$csseffdate);
        $mydates = explode("-",$myDateTime[0]); 
        $formname = sanitizeVariable($request->formname);
        $form_name = sanitizeVariable($request->formname);
        $effectivedate = $mydates[2]."-".$mydates[0]."-".$mydates[1];
        
        $fromdate =$effectivedate." "."00:00:00";    
        $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
        $todate = $effectivedate ." "."23:59:59"; 
        $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate); //"2021-03-12 18:29:59" 
        
        $reviewstatus = sanitizeVariable($request->reviewstatus);
        $serialid = sanitizeVariable($request->serialid);
        $table = sanitizeVariable($request->table);
        $uid = session()->get('userid');
        
        $usersdetails = Users::where('id',$uid)->get();
        $roleid = $usersdetails[0]->role; 


       if($reading==null || $reading=='' || $reading=="")
         {
               $updateotheralerts =   Other_Alerts::where('id',$serialid)
                                     ->update(['reviewed_flag'=> $reviewstatus,'reviewed_date'=>\Carbon\Carbon::now() ]); 

               $billingstatuscheck = null;
               $vital = $vital_name;
                                      
         }
         else{

         
                if($unit=='lbs' || $unit==1 || $vital_name=='Weight'){

                    $vital = 'Weight';
                    $deviceid=1;
                    
                        $updateweight =  Observation_Weight::where('id',$serialid)
                                        ->update(['reviewed_flag'=> $reviewstatus,'reviewed_date'=>\Carbon\Carbon::now() ]); 
                                                                

                        $billingstatuscheck =   Observation_Weight::where('patient_id',$patientid)
                                                ->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');
                     
                      
                    
                 }  
                else if($unit=='%' || $unit==2 || $vital_name=='Oxygen' ){  
                    $vital = 'Oxygen';
                    $deviceid=2;
                   
                         $updateoxy =   Observation_Oxymeter::where('id',$serialid)->update(['reviewed_flag'=> $reviewstatus,'reviewed_date'=> \          Carbon\Carbon::now() ]); 

                         $billingstatuscheck =   Observation_Oxymeter::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->                     pluck('billing'); 
                     
                     
                    
                 }
                 else if($unit=='mm[Hg]' || $unit=='mmHg' || $unit==3 || $vital_name=='Blood Pressure'){  
                    $vital = 'Blood Pressure'; 
                    $deviceid=3;  
                    
                        $updatebp= Observation_BP::where('id',$serialid)
                                   ->update(['reviewed_flag'=>$reviewstatus,'reviewed_date'=>\Carbon\Carbon::now()]);

                        $billingstatuscheck = Observation_BP::where('patient_id',$patientid)
                                              ->whereBetween('effdatetime',[$dt1 , $dt2])->pluck('billing');
                         
                   
                 }
        /*
                else if($unit=='L' || $unit=='L/min' || $unit==4){  
                    $vital = 'Spirometer';
                    $updatespirometer = Observation_Spirometer::where('id',$serialid)->update(['reviewed_flag'=> $reviewstatus,'reviewed_date'=> \Carbon\Carbon::now() ]); 

                    $billingstatuscheck =   Observation_Spirometer::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');   
                    
                 }*/
                  else if($unit=='L' || $unit=='L/min' || $unit==5 || $vital_name=='Spirometer'){  
                    $vital = 'Spirometer';
                    $deviceid=5;  
                   
                            $updatespirometer = Observation_Spirometer::where('id',$serialid)->update(['reviewed_flag'=> $reviewstatus, 'reviewed_date'=> \Carbon\Carbon::now()]);
                            $billingstatuscheck = Observation_Spirometer::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');
                           
                  
                  }
                else if($unit=='mg/dl' || $unit==6 || $vital_name=='Glucose'){  
                    $vital = 'Glucose';   
                    $deviceid=6;     
                    $updateglucose = Observation_Glucose::where('id',$serialid)->update(['reviewed_flag'=> $reviewstatus, 'reviewed_date'=> \Carbon\Carbon::now()]);
                    $billingstatuscheck = Observation_Glucose::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');
                 }
                
                else{ 
                     $vital = 'Heartrate'; 
                        $deviceid=0;     
                    $updateheartrate = Observation_Heartrate::where('id',$serialid)->update(['reviewed_flag'=> $reviewstatus, 'reviewed_date'=> \Carbon\Carbon::now()]);
                    $billingstatuscheck = Observation_Heartrate::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');
                 }

           }

         
       if($reading!=null || $reading!='' || $reading!="")
       {
            
            $billarray = $billingstatuscheck->toArray();
             
            if(in_array("1",$billarray)){
         
            }
            else{
                
                if($unit=='lbs' || $unit==1){  
                    $vital = 'Weight';
                    $updateoxy = Observation_Weight::where('id',$serialid)->update(['billing'=> 1]);                     
                }
                else if($unit=='%' || $unit==2){  
                    $vital = 'Oxygen';
                    $updateoxy = Observation_Oxymeter::where('id',$serialid)->update(['billing'=> 1]);                     
                }
                else if($unit=='mm[Hg]'  || $unit=='mmHg' || $unit==3){  
                    $vital = 'Blood Pressure';        
                    $updatebp = Observation_BP::where('id',$serialid)->update(['billing'=> 1]);
                }
                /*else if($unit=='L' || $unit=='L/min' || $unit==4){  
                    $vital = 'Heartrate';        
                    $updateheartrate = Observation_Heartrate::where('id',$serialid)->update(['billing'=> 1]);
                }*/
                else if($unit=='L' || $unit=='L/min' || $unit==5){  
                    $vital = 'Spirometer';        
                    $updatespirometer = Observation_Spirometer::where('id',$serialid)->update(['billing'=> 1]);
                }
                else if($unit=='mg/dl' || $unit==6){  
                    $vital = 'Glucose';        
                    $updateglucose = Observation_Glucose::where('id',$serialid)->update(['billing'=> 1]);
                }
                else{  
                     $vital = 'Heartrate';        
                    $updateheartrate = Observation_Heartrate::where('id',$serialid)->update(['billing'=> 1]);
                  
                    } 

               }

            }
     
  
                if($reviewstatus == 1 && $table=="parent"){
          
                    

                              if($formname=="RPMdailyreviewedcompleted" || $formname=="RPMdailyreviewedreadingcompleted"
                               || $formname=="RPMWorklistreviewedcompleted")
                               
                              {
                                    $start_time   = sanitizeVariable($request->timer_start); 
                                    //  $nettime     = sanitizeVariable($request->timer_paused); //nettime means end time
                                    $nettime= date("H:i:s",strtotime($nettime)+strtotime($start_time));
                             }else{
                                $start_time='00:00:00';
                             }

                            if($reading==null || $reading=='' || $reading==""){
                                $billable=0;
                            
                            }
                            else{
                                $billable=1;
                            }

                    $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id,$componentid, $stage_id, $billable, $patientid, $step_id, $form_name);
                }
                else if($reviewstatus == 1 && $table=="child" )
                {
                   
                    if($formname=="child_RPMdailyreviewedcompleted"){
                        $start_time= sanitizeVariable($request->timer_start); 
                        $nettime   = date("H:i:s",strtotime($nettime)+strtotime($start_time));
                    }else{
                        $start_time='00:00:00';
                    }
                            if($reading==null || $reading=='' || $reading==""){
                                $billable=0;
                            
                            }
                            else{
                                $billable=1; 
                            }
                    
                    $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid, $stage_id, $billable, $patientid, $step_id, $form_name);
                }  
        

        
        $fname = $usersdetails[0]->f_name;
        $lname = $usersdetails[0]->l_name;
        $name =  $fname.''.$lname;
        $revieweddatetime = \Carbon\Carbon::now();
        $convertedrevieweddatetime = DatesTimezoneConversion::userToConfigTimeStamp($revieweddatetime);
        $r =  explode(" ",$convertedrevieweddatetime); 
        $reviewDate =  $r[0];
        $reviewTime =  $r[1];
        $timestamp = strtotime($reviewDate); 
        $newReviewDate = date("d-m-Y", $timestamp);
          

        $topic = 'Observation reviewed';
        $notes = $name.' reviewed '.$vital.' on '.$newReviewDate.' at '.$reviewTime;
        

        $a = array('uid'=>$uid,
                    'record_date'=>\Carbon\Carbon::now(),
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
                    'rpm_observation_id'=>$serialid 
                );
        CallWrap::create($a);        

       
    }    
    
    
    public static function updateDailyReviewAddress(RPMWorklistRequest $request)
    { 
        $notes = sanitizeVariable($request->notes);
        $vitals = explode(",",sanitizeVariable($request->vital));
        $units = explode(",",sanitizeVariable($request->unit));
        $patient_id = explode(",",sanitizeVariable($request->care_patient_id));
        
        $rpm_observation_id = explode(",",sanitizeVariable($request->rpm_observation_id));
        // dd($rpm_observation_id);
        $csseffdate = explode(",",sanitizeVariable($request->csseffdate));
        // $hd_chk_this = explode(",",sanitizeVariable($request->hd_chk_this));
        // $table="RPMworklistaddresscompleted";
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
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id     = sanitizeVariable($request->add_step_id);
        // $start_time   =sanitizeVariable($request->hd_timer_start);
        $start_time = "00:00:00";
//dd($start_time );
        $serialid="";
        for($i=0;$i<count($patient_id);$i++){

            $myDateTime   =  explode(" ",$csseffdate[$i]);
            $mydates      = explode("-",$myDateTime[0]); 
            $effectivedate= $mydates[2]."-".$mydates[0]."-".$mydates[1];
       
            $fromdate     =$effectivedate." "."00:00:00";    
            $dt1          = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
            $todate       = $effectivedate ." "."23:59:59"; 
            $dt2          = DatesTimezoneConversion::userToConfigTimeStamp($todate); //"2021-03-12 18:29:59" 

            $serialid.=$rpm_observation_id[$i].",";
            $vital=$vitals[$i];
            $unit=$units[$i];
            $patientid=$patient_id[$i];
            
            $nettime= date("H:i:s",strtotime($time[0]->vital_review_time)+strtotime($start_time));
        

         if($unit=='lbs' || $unit==1){  
            $vital = 'Weight';
            $deviceid=1;
            $obdata = Observation_Weight::where('id',$rpm_observation_id[$i])->get();       
            $observationid = $obdata[0]->observation_id;  
            $reviewstatus =$obdata[0]->reviewed_flag; 
            if($reviewstatus==0)
            {
                $reviewstatus=1;    
                $data=array('addressed'=>1,'reviewed_flag'=> $reviewstatus,'reviewed_date'=>Carbon::now(),'addressed_date'=>Carbon::now());$record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid, $stage_id, $billable, $patientid, $step_id, $formname);
            }
            else
            {
                $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
            }           
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
             $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid, $stage_id, $billable, $patientid, $step_id, $formname);
             }
            else
            {
                 $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
            }   
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
                $data=array('addressed'=>1,'reviewed_flag'=> $reviewstatus,'reviewed_date'=>Carbon::now(),'addressed_date'=>Carbon::now()); $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid, $stage_id, $billable, $patientid, $step_id, $formname);
            }else{
                $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
            }

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
                 $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid, $stage_id, $billable, $patientid, $step_id, $formname);
                 }   
             else
            {
                 $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
             } 
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
                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid, $stage_id, $billable, $patientid, $step_id, $formname);
             }
            else
            {
                 $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
             }       
            $updateglucose = Observation_Glucose::where('id',$rpm_observation_id[$i])->update(['addressed'=>1,'reviewed_flag'=> $reviewstatus, 'reviewed_date'=> \Carbon\Carbon::now()]);
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
                 $record_time  = CommonFunctionController::recordTimeSpent($start_time, $nettime, $patientid, $module_id, $componentid, $stage_id, $billable, $patientid, $step_id, $formname);
            }
            else
            {
                 $data=array('addressed'=>1,'addressed_date'=>Carbon::now());
            }         
            $updateheartrate = Observation_Heartrate::where('id',$rpm_observation_id[$i])->update(['addressed'=>1,'reviewed_flag'=> $reviewstatus, 'reviewed_date'=> \Carbon\Carbon::now()]);
            $billingstatuscheck = Observation_Heartrate::where('patient_id',$patientid)->whereBetween('effdatetime', [$dt1 , $dt2])->pluck('billing');           
         }
     
        
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

        $reportdate=$myDateTime[0];
        $reporttime=$myDateTime[1];
        $newReportedDate = date("F j Y", strtotime($effectivedate));
        $nwdt= date("Y-m-d H:i:s", strtotime($revieweddatetime));

        $topic = $vital.' alert reported on '.$newReportedDate.' at '.$reporttime.' has been addressed by '.$name.' on ' .$newReviewDate.' at '.$reviewTime;

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
	
	public function noReadingsLastthreedays(Request $request)
 {
     //   dd("hello");
       $date=date("Y-m-d");
       $year = date('Y', strtotime($date));
       $month = date('m', strtotime($date));
       //$fromdate = $year."-".$month."-01 00:00:00";
       $fromdate = $date." 00:00:00";
       $todate = $date." 23:59:59"; 

       $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
       $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate); 
       
       $lastthreedays = date('Y-m-d',strtotime('-3 days', strtotime($fromdate)));
       $lastthreedaysfromdate = $lastthreedays." 00:00:00";
       $lastthreedaystodate = $lastthreedays." 23:59:59" ; 
       $lastdt1 = DatesTimezoneConversion::userToConfigTimeStamp( $lastthreedaysfromdate);
       $lastdt2 = DatesTimezoneConversion::userToConfigTimeStamp( $lastthreedaystodate); 


     $query = "select distinct p.id,p.fname,p.lname,p.email,p.country_code,p.mob
,            rp.name as practicename, pd.device_id , rpp.name as providername
             from patients.patient p 
             inner join patients.patient_services ps on ps.patient_id = p.id and ps.module_id = 2 and ps.status = 1
             inner join patients.patient_providers pp on pp.patient_id = p.id  and pp.is_active = 1 and pp.provider_type_id = 1 and pp.practice_id= 40
			 inner join ren_core.providers rpp on pp.provider_id = rpp.id
             inner join ren_core.practices rp on rp.id = pp.practice_id and rp.is_active = 1
             inner join patients.patient_devices pd on pd.patient_id = p.id where pd.status = 1
             and p.id not in 			 
			 (select patient_id from rpm.patient_cons_observations where effdatetime::timestamp between '".$lastdt1."' and '".$lastdt2."') and p.status = 1";

     $data = (DB::select (DB::raw($query)) );

    //  dd($data); 

     foreach($data as $d)
     {
      
     //    dd($d->id);

      if($d->device_id == '3' || $d->device_id == '' || $d->device_id == null ){
         $reading = "Blood pressure reading"; 
      }else if($d->device_id == '2'){
         $reading = "Oxymeter reading"; 
      }else if($d->device_id == '1'){
         $reading = "Weight reading";
      }else if($d->device_id == '8'){
         $reading = "Glucose reading";
      }else if($d->device_id == '5'){
         $reading = "Spirometer reading";
      } 
      
      $loginuserid = session()->get('userid');  
    
      
      $scripts = ContentTemplate::where('id', 42)->where('status', 1)->get(); 
      $intro = get_object_vars(json_decode($scripts[0]->content));
      $replace_pt = str_replace("[patient_name]",$d->fname.' '.$d->lname, $intro['message']);
     
      $replace_provider = str_replace("[provider]", $d->providername, $replace_pt);
      $replace_practice_name = str_replace("[practice_name]", $d->practicename, $replace_provider);
      $replace_readings = str_replace("[device_condition]", $reading, $replace_practice_name);
     
     // $message = "Last three days readings not captured- RCARE";
     // $messageold = "Hi Ms Jones,
     // This is Dr Lee from MANA Prairie Grove. I've noticed we haven't seen a Blood Pressure reading from you in the past three days. Could you please do your best to take a reading once per day so I can better manage your care. 
     // Taking regular readings helps me to identify trends in your blood pressure which improves medication management, and can prevent any avoidable emergency room visits. 
     // If you need help with using your assigned device please call (479) 485-3227, and one of my nurses will be happy to help. 
     // Thank you, Dr Ron Lee";

     

     $m = $replace_readings;
     $message = strip_tags($m);
     

     $receiverNumber= $d->country_code.$d->mob;
     // dd($receiverNumber);
     // dd($message,$receiverNumber);
     // $receiverNumber =$data[0]->country_code.$data[0]->mob;
     // dd($receiverNumber);    
    //  $receiverNumber = '+918451972393';  
                 $sms = Configurations::where('config_type','sms')->orderBy('created_at', 'desc')->first();
               
                 if(isset($sms->configurations)){
                     $sms_detail = json_decode($sms->configurations);
                     $account_sid = $sms_detail->username;
                     $auth_token = $sms_detail->password;
                     $twilio_number = $sms_detail->phone;  
                     $client = new Client($account_sid, $auth_token);
                     // dd($client);
                     $msg = $client->messages->create($receiverNumber, [
                     'from' => $twilio_number, 
                     'body' => $message]);
                     $sid = $msg->sid;
                     $date = $msg->dateCreated;   
                     $message_date = $date->format('Y-m-d H:i:s');    
                                
                    
                     $message1 = $client->messages($sid)->fetch();
                     // $message1 = $client->messages('SM82407cd4316e76b207513625bc54db75')->fetch();
                     // $message1 = $client->messages('SM5c2176d4e8bcbd7237fb2591717b0e13')->fetch();
                     // dd($message1);
                     
                     $datamessagelog = MessageLog::create([
						 'message_id' =>$msg->sid,
						 'patient_id'=> $d->id,
						 'message' => $msg->body,
						 'from_phone' => $msg->from,
						 'to_phone' => $msg->to, 
						 'status' => $msg->status,
						 'module_id' => 2,
						 'stage_id' => 0,
						 'message_date'=>  $message_date,
						 'created_by' => $loginuserid,
						 'updated_by' => $loginuserid,  
						 'status_update' => 1,
						 'read_status' => 0

                     ]);

                    

                 }

     }           



 }

   
   

   
}