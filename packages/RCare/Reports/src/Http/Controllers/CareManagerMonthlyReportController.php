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

class CareManagerMonthlyReportController extends Controller
{
   
    public function listCareManagerMonthlyReportPatients(Request $request ){
        return view('Reports::caremanager-monthly-report.patients-monthly-reports-associated-with-caremanager');
    }
    public function listCareManagerMonthlyReportPatientsSearch(Request $request)
    {
            $practicesgrp = sanitizeVariable($request->route('practicegrpid'));          
            $practices =   sanitizeVariable($request->route('practice'));
            $caremanager = sanitizeVariable($request->route('caremanager'));
            $module_id = sanitizeVariable($request->route('modules'));
            $monthly   = sanitizeVariable($request->route('monthly'));
            $monthlyto   = sanitizeVariable($request->route('monthlyto')); 
            $configTZ = config('app.timezone');
            $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
            $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus')); 

            if($practicesgrp==''|| $practicesgrp=='null')
            {
                $practicesgrp='null';
            }
            else
            {
                $practicesgrp=$practicesgrp;
            }
            if($caremanager==''|| $caremanager=='null')
            {
                $caremanager='null';
            }
            if($module_id==''|| $module_id=='null')
            {
                $module_id='null';
            }
            if($practices==''|| $practices=='null')
            {
                $practices='null';
            }
           
            if($monthly=='' || $monthly=='null' || $monthly=='0') 
            {
                $monthly=date('Y-m');
            }
            else
            { 
                 $monthly=$monthly;
            }

            if($monthlyto=='' || $monthlyto=='null' || $monthlyto=='0')
            {
                $monthlyto=date('Y-m');
            }
            else
            {
                 $monthlyto=$monthlyto;
            }
              $year = date('Y', strtotime($monthly));
              $month = date('m', strtotime($monthly));  

              $toyear = date('Y', strtotime($monthlyto)); 
              $tomonth = date('m', strtotime($monthlyto));  
              
              $fromdatetime=$toyear.'-'.$tomonth.'-01 00:00:00';
              $to_date=$year.'-'.$month.'-01';
              $convertdate = strtotime('-1 second', strtotime('+1 month', strtotime($to_date)));
              $todate=date('Y-m-d', $convertdate);
              $todatetime = $todate ." "."23:59:59";
              $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdatetime); 
              $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todatetime); 
                 
            //   $query ="select pid, pfname, plname, pmname, pdob , pprofileimg, pppracticeemr, pracpracticename , prprovidername,  to_char(ccsrecdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as ccsrecdate, ptrtotaltime , f_name, l_name, pstatus from patients.caremanager_monthly_billing_report($practices,$caremanager,$module_id,'".$fromdate."','".$todate." 23:59:59',$practicesgrp,'".$configTZ ."','".$userTZ."',$activedeactivestatus)";
          //  dd($query);//here $tomonth is fromdate and $month is todate
          $query ="select pid, pfname, plname, pmname, pdob , pprofileimg, pppracticeemr, pracpracticename , prprovidername,  to_char(ccsrecdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as ccsrecdate, ptrtotaltime , f_name, l_name, pstatus from patients.caremanager_monthly_billing_report($practices,$caremanager,$module_id,timestamp '".$dt1."',timestamp '".$dt2."',$practicesgrp,'".$configTZ ."','".$userTZ."',$activedeactivestatus)";
              $data = DB::select($query);  
              //dd($data);
                 return Datatables::of($data) 
                 ->addIndexColumn()           
                 ->make(true);  
    }
}



