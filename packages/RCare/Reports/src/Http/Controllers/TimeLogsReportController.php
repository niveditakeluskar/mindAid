<?php 
namespace RCare\Reports\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\System\Traits\DatesTimezoneConversion;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\PatientBilling;
use RCare\Patients\Models\PatientTimeRecords;
use RCare\TaskManagement\Models\ToDoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RCare\TaskManagement\Models\UserPatients;
use DataTables;
use Carbon\Carbon;
use Session;
use RCare\Org\OrgPackages\Users\src\Models\RenUserLoginHistory;

class TimeLogsReportController extends Controller  
{ 
    public function timeLogsReportSearch(Request $request)
    {
        $configTZ     = config('app.timezone');
        $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        $caremanagerid  = sanitizeVariable($request->route('caremanagerid'));
        $practiceid = sanitizeVariable($request->route('practiceid')); 
        $emr = sanitizeVariable($request->route('emr'));
        $patient = sanitizeVariable($request->route('patient'));
        $sub_module = sanitizeVariable($request->route('sub_module'));
        $module = sanitizeVariable($request->route('module'));
        $date   = sanitizeVariable($request->route('fromdate'));
        $tdate  = sanitizeVariable($request->route('todate')); 
        $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus'));
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
         
         if($practiceid==''|| $practiceid=='null'){
            $practiceid='null';
          }else{
            $practiceid=$practiceid;
          }
         if($patient==''|| $patient=='null'){
            $patient='null';
          }else{
            $patient=$patient;
          }
          if($caremanagerid==''|| $caremanagerid=='null'){
            $caremanagerid='null';
          }else{
            $caremanagerid=$caremanagerid;
          }
          if($module==''|| $module=='null'){
            $module='null';
          }else{
            $module=$module;
          }
          if($sub_module==''|| $sub_module=='null'){
            $sub_module='null';
          }else{
            $sub_module=$sub_module;
          }
          if($activedeactivestatus==''|| $activedeactivestatus=='null'){
            $activedeactivestatus='null';
          }else{ 
            $activedeactivestatus=$activedeactivestatus;
          }

          if($emr==''|| $emr=='null'){
            $emr='null';
          }else{
            $emr= "'".$emr."'";
          }
          
          // dd($emr);
       $query = "select * from patients.time_logs_report($patient,$practiceid,$caremanagerid,$module,$sub_module,$emr,
        timestamp '".$dt1."',timestamp '".$dt2."',
        '".$configTZ ."','".$userTZ."',
        $activedeactivestatus)"; 
//  dd($query);    
       $data = DB::select($query);
        return Datatables::of($data)
               ->addIndexColumn()            
               ->make(true);   
    }   
}