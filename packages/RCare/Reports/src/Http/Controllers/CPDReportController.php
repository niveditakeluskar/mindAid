<?php 
namespace RCare\Reports\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use RCare\TaskManagement\Models\ToDoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RCare\TaskManagement\Models\UserPatients;
use DataTables;
use Carbon\Carbon;
use Session;
use RCare\System\Traits\DatesTimezoneConversion; 

class CPDReportController extends Controller
{

  public function CPDReportSerch(Request $request)
  {

    $practices = sanitizeVariable($request->practice);
    $practicesgrp = sanitizeVariable($request->practicesgrpid);
    $billable = sanitizeVariable($request->billable);
    $careplanstatuas = sanitizeVariable($request->careplanstatuas);
    $fromdate= sanitizeVariable($request->route('fromdate'));
    $configTZ     = config('app.timezone');
    $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
    $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus'));
      $fromdate1 =$fromdate." "."00:00:00";
      $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
      $todate= sanitizeVariable($request->route('todate')); 
      $todate1 = $todate ." "."23:59:59";  
      $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate1); 
     
      $query="select  ppatient_id, fname, lname, mname, profile_img, dob, practicename, cpdstautus, totaltimecpd, billabeltime, nonbillabeltime, totaltime, 
      to_char(finalizedate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as finalizedate 
       from patients.cpd_report($practices,$practicesgrp,$billable,$careplanstatuas,timestamp '".$dt1."',timestamp '".$dt2."',$activedeactivestatus)";
   
  $data  = DB::select($query);

  return Datatables::of($data)
      ->addIndexColumn()
  ->make(true);
  }
  
}