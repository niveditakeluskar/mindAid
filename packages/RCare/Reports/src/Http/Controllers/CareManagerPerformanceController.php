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

class CareManagerPerformanceController extends Controller{
  public function CaremanagerPerformanceReportSearch(Request $request){
    $user_id = sanitizeVariable($request->route('caremanagerid'));
    $fromdate= sanitizeVariable($request->route('fromdate'));
    $todate1 = sanitizeVariable($request->route('todate'));
    $todate = $todate1." "."23:59:59";
    // print_r($todate1); echo "<pre>";
    $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus')); 
    $configTZ = config('app.timezone');
    $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
      if($user_id==''|| $user_id=='null')
        {
            $user_id='null';
        }
        else
        {
            $user_id=$user_id;
        }

        if($fromdate=='null' || $fromdate=='')
        {
          $date=date("Y-m-d");
          $year = date('Y', strtotime($date));
          $month = date('m', strtotime($date));
          $fromdate = $year."-".$month."-01 00:00:00";
          $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);   
        }else{
          $fromdate = $fromdate." 00:00:00";
          $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
          
        }
        
        if($todate=='null' || $todate=='')
        {
        $date=date("Y-m-d");
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $todate = $year."-".$month."-01 23:59:59";
        $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate);   
        }else{
          // $to_date = $todate;
          // $date = date('Y-m-d', strtotime("+1 day", strtotime($to_date)));
          // $todate = $date." 23:59:59";
          // $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate);
            $todate = $todate;
            $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate);   
        }

        if($activedeactivestatus ==''|| $activedeactivestatus =='null')
        {
            $activedeactivestatus ='null';
        }
        else
        {
            $activedeactivestatus = $activedeactivestatus ;
        }
        $query = "select userid,caremanagername,assignedpatient,mon,yr,contacted,completed,bill
        from patients.cm_monthly_perform($user_id,timestamp '".$dt1."',timestamp '".$dt2."',$activedeactivestatus,'".$configTZ ."','".$userTZ."')";
        // dd($query); 
        $data = DB::select( DB::raw($query) );
        //  dd($data);
            return Datatables::of($data) 
            ->addIndexColumn()             
            ->make(true); 
    }     
       
}?> 