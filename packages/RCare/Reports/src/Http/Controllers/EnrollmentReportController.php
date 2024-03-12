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
use RCare\Org\OrgPackages\Practices\src\Models\Practices; 
use RCare\Org\OrgPackages\Providers\src\Models\Providers; 
// use RCare\Reports\Http\Requests\MonthlyBilllingReportPatientsSearchRequest;
use DataTables;
use Carbon\Carbon; 
use Session; 
use RCare\System\Traits\DatesTimezoneConversion;

class EnrollmentReportController extends Controller 
{
   
    //created and modified by ashvini(1dec2020)
    public function EnrollmentReportSearch(Request $request)
    {
      $practicesgrp = sanitizeVariable($request->route('practicesgrpid'));
      $caremanager = sanitizeVariable($request->route('care_manager_id'));
      $practices = sanitizeVariable($request->route('practiceid'));
      $fromdate=sanitizeVariable($request->route('fromdate'));
      $todate=sanitizeVariable($request->route('todate'));
      $module_id = sanitizeVariable($request->route('module'));
      $provider_id = sanitizeVariable($request->route('provider'));
      $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus'));
      $configTZ     = config('app.timezone');
      $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
      $p;
      $pr;
      $c;
      $m;
      
      // if($module_id !='0' && $module_id != 'null')
      // {
          $m = $module_id;
      // }
      // else if($module_id =='0') 
      // {
      //   $m = 0     ;
      // }

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

         if($caremanager != '0' && $caremanager != 'null')              
         {
            $c = $caremanager;
         }
        else if($caremanager == '0' )   
         {
            $c = 0;
         }
         else{
           $c = 'null';
         } 
       
      $query = "select pid, pfname, plname, pmname, pprofileimg, pdob , practicename ,address, mmodule, prprovidername, userfname, userlname, to_char(pscreatedat at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as pscreatedat , tptotalpatient, pralocation, pracpracticeemr, pstatus
      from patients.SP_ENROLLMENT_REPORT($c,$p,timestamp '".$dt1."',timestamp '".$dt2."',$module_id,$practicesgrp,$provider_id,$activedeactivestatus)";  
     
      $data = DB::select($query);
     
      return Datatables::of($data) 
      ->addIndexColumn()            
      ->make(true); 
    }

          
      //created by ->radha(19oct20) non enrolled details in enrollment report
    //modified by ->ashvini(7dec2020) procedure
   public function getNonEnrolledPatientData(Request $request)
   {
      $practices = sanitizeVariable($request->route('practice'));
      $p;
      if($practices=='null' || $practices==''){    
        $p = "null";
      }
      else if($practices=='0'){
        $p = 0;
      }
      else{
        $p = $practices;   
      }
      $query = "select * from patients.sp_nonenrolled_patients_details($p)"; 
      // dd($query);
      $data = DB::select($query);
            return Datatables::of($data) 
            ->addIndexColumn()            
            ->make(true);
 
   }
    
     //created by ->radha(19oct20) enrolled details in enrollment report
     //modifiedby ashvini(7dec2020) for procedure
    public function getEnrolledPatientData(Request $request)
   {
    $practices = sanitizeVariable($request->route('practice'));
    $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus'));
    $p;
    if($practices=='null' || $practices==''){    
      $p = "null";
    }
    else if($practices=='0'){
      $p = 0;
    }
    else{ 
      $p = $practices;   
    }

    $query = "select * from patients.sp_enrolled_patients_details($p,$activedeactivestatus)"; 
    // dd($query);
    $data = DB::select($query);
          return Datatables::of($data)  
          ->addIndexColumn()            
          ->make(true);
   }

   //created by ->radha(20oct20) enrolled details in enrollment report
   //modified by ->ashvini (07dec2020)procedure
    public function getEnrolledInCCMPatientData(Request $request)
   {
    $practices = sanitizeVariable($request->route('practice'));
    $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus'));
    $p;
    if($practices=='null' || $practices==''){    
      $p = "null";
    }
     else if($practices=='0'){
      $p = 0;
    }
    else{
      $p = $practices;   
    }
    $query = "select * from patients.sp_enrolled_in_ccm_details($p,$activedeactivestatus)"; 
    // dd($query);
    $data = DB::select($query);
            return Datatables::of($data) 
            ->addIndexColumn()            
            ->make(true);
   }
    
    //created by ->radha(20oct20) enrolled details in enrollment report
    //modified by ashvini(7dec202) procedure
    public function getEnrolledInRPMPatientData(Request $request) 
    {
    $practices = sanitizeVariable($request->route('practice'));
    $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus'));
    $p;
    if($practices=='null' || $practices==''){    
      $p = "null";
    }
     else if($practices=='0'){
      $p = 0;
    }
    else{
      $p = $practices;   
    }
    $query = "select * from patients.sp_enrolled_in_rpm_details($p,$activedeactivestatus)"; 
    // dd($query);
    $data = DB::select($query);
            return Datatables::of($data) 
            ->addIndexColumn()            
            ->make(true);
   }

     //created by ->radha(19oct20) total patients details in enrollment report
    public function getTotalPatientData(Request $request)
   {
      $practices = sanitizeVariable($request->route('practice'));
      $p;
      if($practices=='null' || $practices==''){   
        $p = "null";
      }
       else if($practices=='0'){
        $p = 0;
      }
      else{ 
        $p = $practices;   
      }
      $query = "select * from patients.sp_totalpatients($p)"; 
      // dd($query);
      $data = DB::select($query);
              return Datatables::of($data) 
              ->addIndexColumn()            
              ->make(true);
   }

//created by ->radha(20oct20) view total patients details 
    public function viewTotalPatients(Request $request)
    {
       return view('Reports::enrollment-report.sub-steps-enrollment-report.total-patients-list');
    }
//created by ->radha(20oct20) view total patients details 
    public function viewEnrolledPatients(Request $request)
    {
       return view('Reports::enrollment-report.sub-steps-enrollment-report.enrolled-list');
    }
    //created by ->radha(20oct20) view total patients details 
    public function viewNonEnrolledPatients(Request $request)
    {
       return view('Reports::enrollment-report.sub-steps-enrollment-report.non-enrolled-list');
    }
    //created by ->radha(20oct20) view total patients details 
    public function viewEnrolledInCCMPatients(Request $request)
    {
       return view('Reports::enrollment-report.sub-steps-enrollment-report.enrolled-in-ccm-list');
    }
    //created by ->radha(20oct20) view total patients details 
    public function viewEnrolledInRPMPatients(Request $request)
    {
       return view('Reports::enrollment-report.sub-steps-enrollment-report.enrolled-in-rpm-list');
    }

   
  //created by ->radha(17oct20) for patient summary in enrollment report
  public function getPatientEnrolledData(Request $request)    
  {    
    // for current Month and YEAR 
     $from_monthly = sanitizeVariable($request->route('from_month'));  
     $to_monthly   = sanitizeVariable($request->route('to_month'));  
             
       if($from_monthly=='' || $from_monthly=='null' || $from_monthly=='0'){
            $from_monthly1=date('Y-m');
        }else{
            $from_monthly1=$from_monthly;
        } 

        if($to_monthly=='' || $to_monthly=='null' || $to_monthly=='0'){
            $to_monthly1=date('Y-m');
        }else{ 
             $to_monthly1=$to_monthly;
        }

      $fromyear  = date('Y', strtotime($from_monthly1));
      $frommonth = date('m', strtotime($from_monthly1));

      $toyear = date('Y', strtotime($to_monthly1)); 
      $tomonth = date('m', strtotime($to_monthly1));  
        
      $fromdate=$toyear.'-'.$frommonth.'-01 00:00:00';            
      $to_date= $fromyear.'-'.$tomonth.'-01';
      $convertdate = strtotime('-1 second', strtotime('+1 month', strtotime($to_date)));
      $todate=date('Y-m-d', $convertdate);
      // end

      $querytotalcount="select * from patients.active_patient_count()"; 

      $totalPatient=DB::select($querytotalcount);



      $queryActivepatientenrolledcount = "select * from patients.sp_enrolled_active_patients_count()";
      $querySuspendedpatientenrolledcount ="select * from patients.sp_enrolled_suspended_patients_count()";

      $totalActiveEnreolledPatient=DB::select($queryActivepatientenrolledcount);
      $totalSuspendedEnrolledPatient = DB::select($querySuspendedpatientenrolledcount);
 
      $queryActiveCcm ="select * from patients.sp_enrolled_in_ccm_active_detailscount()";
      $querySuspendedCcm ="select * from patients.sp_enrolled_in_ccm_suspended_detailscount()";
      $totalActiveCCMPatient =DB::select($queryActiveCcm);
      $totalSuspendedEnrolledInCCM =DB::select($querySuspendedCcm);


      $queryActiveRpm ="select * from patients.sp_enrolled_in_rpm_active_detailscount()";
      $querySuspendedRpm ="select * from patients.sp_enrolled_in_rpm_suspended_detailscount()";
   

      $totalActiveRPMPatient=DB::select($queryActiveRpm); 
      $totalSuspendedRPMPatient=DB::select($querySuspendedRpm);
      
      $query4 = "select * from patients.sp_nonenrolled_patientscount()";
      $totalUnEnrolledPatient=DB::select($query4);  

      
       $query5 = " select count(distinct up.patient_id) from task_management.user_patients up
         inner join patients.patient p on  up.patient_id=p.id 
         inner join patients.patient_providers pp on pp.patient_id =p.id
         inner join ren_core.practices p2 on p2.id=pp.practice_id 
         where up.status=1  and pp.provider_type_id =1 and pp.is_active=1 and p2.is_active =1 and p.status=1";

        $totalAssignedPatient=DB::select($query5);

       
       //added by radha 18nov20
          $query6="select count(distinct p.id) from patients.patient p
           inner join patients.patient_providers pp on pp.patient_id =p.id
           inner join ren_core.practices p2 on p2.id=pp.practice_id 
          where pp.provider_type_id =1 and pp.is_active=1 and p2.is_active =1 and p.status=1";

        $totalPatientActive=DB::select($query6);


      // $query6 = "select count(*) from ren_core.users where role=5 ";
      //Updated by -pranali on 22Oct2020
      $query6 = "select count(*) from ren_core.users where role=5 and status = 1";
      $totalCareManeger=DB::select($query6);




      $querynewenroll="select count(distinct ps.patient_id) 
      from patients.patient_services ps 
      where ps.status=1 and  ps.patient_id in (select id from patients.patient p where p.status=1)
      and ps.created_at between '".$fromdate."' and '".$todate." 23:59:59' ";

      $totalnewenroll=DB::select($querynewenroll);



      //added by ashwinimali
      $queryallpatientcount ="select * from patients.patient_count()";

      $totalallPatient=DB::select($queryallpatientcount);

      $totalPatientAssignTask = "select * from patients.active_assign_patient_count()";
        $totalPatientAssignTask=DB::select($query5);


       $data=array('Totalpatient'=>$totalPatient, 
        'Totalallpatient'=>$totalallPatient,
        'TotalActiveEnreolledPatient'=>$totalActiveEnreolledPatient,
        'TotalSuspendedEnrolledPatient'=>$totalSuspendedEnrolledPatient,
        'TotalActiveEnrolledInCCM'=>$totalActiveCCMPatient,
        'TotalSuspendedEnrolledInCCM'=>$totalSuspendedEnrolledInCCM,
        // 'TotalEnrolledInCCM'=>$totalCCMPatient,
         //'TotalEnrolledInRPM'=>$totalRPMPatient,
          'TotalActiveEnrolledInRPM'=>$totalActiveRPMPatient,
          'TotalSuspendedEnrolledInRPM'=>$totalSuspendedRPMPatient,
         'TotalUnEnrolledPatient'=>$totalUnEnrolledPatient,
         'TotalCareManeger'=>$totalCareManeger,
         'ToltalAssignedPatient'=>$totalAssignedPatient,
         'totalPatientActive'=>$totalPatientActive, 
         'totalnewenroll'=>$totalnewenroll,
         'TotalPatientAssignTask'=>$totalPatientAssignTask);

      return $data;
  } 


}