<?php 
namespace RCare\Reports\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\PatientBilling;
use RCare\TaskManagement\Models\ToDoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RCare\TaskManagement\Models\UserPatients;
use DataTables;
use Carbon\Carbon;
use Session;
use RCare\System\Traits\DatesTimezoneConversion;

class CMBillingStatusReport extends Controller
{ 
    public function CareManagerReport(Request $request)
    {
        
          return view('Reports::cm-billing-status.care-manager-report');
    }
    public function CareManagerReportSearch(Request $request)
    {  
        // dd($request->all()); 
        $practicesgrp = sanitizeVariable($request->route('practicesgrpid')); 
        // dd($practicesgrp);
        $care_manager_id  = sanitizeVariable($request->route('care_manager_id')); 
        $practices = sanitizeVariable($request->route('practiceid'));
        $provider = sanitizeVariable($request->route('providerid'));
        $module_id = sanitizeVariable($request->route('module'));
        $date  = sanitizeVariable($request->route('fromdate'));
        $tdate  = sanitizeVariable($request->route('todate'));
        $time  = sanitizeVariable($request->route('time'));
        $timeoption=sanitizeVariable($request->route('timeoption'));
        $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus'));
         $configTZ     = config('app.timezone');
         $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
        $c;
        $p;
        $pr;
        $totime;
        $totimeoption;
       
        if($module_id=='null')
        {
           $module_id=3;
       }
         if($date=='null' || $date=='')
         {
               $date=date("Y-m-d");
               $year = date('Y', strtotime($date));
               $month = date('m', strtotime($date));
               $fromdate = $year."-".$month."-01" ." "."00:00:00";
               $todate = $date ." "."23:59:59";
               $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
               $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate);
             
         }
         else{
          $year = date('Y', strtotime($date));
          $month = date('m', strtotime($date));
          $fromdate =  $date ." "."00:00:00";
          $todate = $tdate ." "."23:59:59";
          $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
          $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate);
         }
       

         if($time=='null' || $time=='')
         {
            $timeoption="1";
            $totime = '00:20:00'; 
         }
         else{
           $totime = $time;
          //  dd($totime);
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


        if( $care_manager_id!='null')
         {
            if( $care_manager_id==0)
            {
              $c = 0;  
            }
            else{
              $c = $care_manager_id;
            } 
   
        }
        else
        {
          $c = 'null';
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
    
    if($timeoption=="3" && $time=="00:00:00" ) //equal to 00:00:00
    {
      
      $timeoption="5";
      
    
    } 
    if($timeoption=="2" && $time=='00:00:00') //greater than 00:00:00
    {
      $timeoption = "6"; 
    } 
       
        $query = "select pid , pfname , plname , pmname , pdob date, pprofileimg , pppracticeemr , pracpracticename , prprovidername ,  to_char(ccsrecdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as ccsrecdate  , pbstatus , f_name , l_name , pstatus , ptrtotaltime  from patients.caremanager_billing_status_report($c,$p,$pr,$module_id, timestamp '".$dt1."', timestamp '".$dt2."',$timeoption,'".$totime."',$practicesgrp,$activedeactivestatus)"; 
        // dd($query);   
        $data = DB::select($query);  
       
            return Datatables::of($data) 
            ->addIndexColumn()            
            ->make(true);   
       
    }

    public function CMBillUpdate(Request $request) 
    {
      
       $patient_id=$request->patient_id;
       $module_id=$request->module_id;
       $count=count($patient_id);
       $uncheckedpatient_id = $request->uncheckedpatient_id; 
       //dd($uncheckedpatient_id);
       $count1=count($uncheckedpatient_id);
      //dd($count1);
       for($i=0;$i<$count1;$i++)
      {
        $uncheckedpatient_bill = PatientBilling::where('patient_id',$uncheckedpatient_id[$i])->exists();
        //dd();
        if ($uncheckedpatient_bill == true)
        {
          PatientBilling::where('patient_id',$uncheckedpatient_id[$i])->delete();
        }
      }

      for($i=0;$i<$count;$i++)
      {
              $patient_bill = PatientBilling::where('patient_id',$patient_id[$i])->exists();  
            if ($patient_bill == true)
            {
              //PatientBilling::where('patient_id',$patient_id[$i])->delete();
            }
            else
            {
              $data = array(
                    'patient_id'=>$patient_id[$i],
                    'module_id' => $module_id,                    
                    'created_by' => session()->get('userid'),
                    'billing_date'=>Carbon::now(),
                    'updated_by' => session()->get('userid')
                );
                PatientBilling::create($data);    
            }
      }
       
    }

}