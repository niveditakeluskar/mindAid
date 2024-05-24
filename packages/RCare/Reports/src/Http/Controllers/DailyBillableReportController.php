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
use RCare\System\Traits\DatesTimezoneConversion; 
use DataTables;
use Carbon\Carbon; 
use Session; 

class DailyBillableReportController extends Controller
{

    public function PatientDailyReport(Request $request) 
    {
    
          return view('Reports::daily-billable-report.patient-daily-report-list');
    }

    public function DailyReportPatientsSearch(Request $request)
    {     
       // dd($request);
        $practicesgrp = sanitizeVariable($request->route('practicesgrpid'));
        $practices = sanitizeVariable($request->route('practiceid'));
        $provider = sanitizeVariable($request->route('providerid'));
        $module_id = sanitizeVariable($request->route('module'));
        $fromdate  =sanitizeVariable($request->route('fromdate'));
        $todate  =sanitizeVariable($request->route('todate'));
        $time  =sanitizeVariable($request->route('time')); 
        $timeoption=sanitizeVariable($request->route('timeoption'));
        $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus')); 
        $configTZ     = config('app.timezone');
        $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
     
         $pracgrp; 
         $p;
         $pr;
         $totime;
         $totimeoption;

        // die;
        
        if($module_id=='null')
        {
           $module_id=3;
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


          // $date=date("Y-m-d");   
          // $fromdate =$date." "."00:00:00";   
          // $todate = $date ." "."23:59:59"; 
          // $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate); 
          // $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate); 

          // $tz_from = $userTZ;
          // $tz_to = $configTZ;
          // $format = 'Ymd\THis\Z';
          // $dt11 = new \DateTime($fromdate, new \DateTimeZone($tz_from));
          // $dt11->setTimeZone(new \DateTimeZone($tz_to));
          // $dt1 =  $dt11->format('Y-m-d H:i:s');
          // $dt22 = new \DateTime($todate, new \DateTimeZone($tz_from));
          // $dt22->setTimeZone(new \DateTimeZone($tz_to));
          // $dt2 =  $dt22->format('Y-m-d H:i:s'); 

         }
         else{
        
         
          $fromdate = $fromdate." 00:00:00";
          $todate = $todate." 23:59:59" ; 
          $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
          $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate); 

         

          // $tz_from = $userTZ;
          // $tz_to = $configTZ;
          // $format = 'Ymd\THis\Z';
          // $dt11 = new \DateTime($fromdate, new \DateTimeZone($tz_from));
          // $dt11->setTimeZone(new \DateTimeZone($tz_to));
          // $dt1 =  $dt11->format('Y-m-d H:i:s');
          // $dt22 = new \DateTime($todate, new \DateTimeZone($tz_from));
          // $dt22->setTimeZone(new \DateTimeZone($tz_to));
          // $dt2 =  $dt22->format('Y-m-d H:i:s');        

         }
         if($time=='null' || $time=='')
         {
            
               $timeoption="1";
               $totime = '00:20:00';
             
         }
         else{
           $totime = $time;
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

         
         if($time!="null" && $time!="00:00:00")
         {
            $totime = $time;
         }
         
         if($timeoption=="3" && $time=="00:00:00") 
         {
           
            $timeoption="5";
            
         
         } 
         if($timeoption=="2" && $time=='00:00:00')   
         {
           $timeoption = "6"; 
         }
       
        $query = "select pid, pfname, plname, pmname, pdob , pprofileimg, pppracticeemr, pracpracticename, prprovidername, pdcondition, to_char(ccsrecdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as ccsrecdate  , pstatus, ptrtotaltime, billingcode,unit from patients.daily_billing_report($p,$pr,3, timestamp '".$dt1."', timestamp '".$dt2."',$timeoption,'".$totime."',$practicesgrp,$activedeactivestatus)";      
        // dd($query); 
        $data = DB::select($query);
      //  dd($data);
            return Datatables::of($data) 
            ->addIndexColumn()             
            ->make(true);      
       
    }




}



