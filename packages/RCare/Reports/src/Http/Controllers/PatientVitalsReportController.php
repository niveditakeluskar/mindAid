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
use RCare\Org\OrgPackages\Users\src\Models\Users;
use DataTables;
use Carbon\Carbon;
use Session;
use Inertia\Inertia;
// use RCare\Org\OrgPackages\Users\src\Models\RenUserLoginHistory;
use RCare\System\Traits\DatesTimezoneConversion;

class PatientVitalsReportController extends Controller
{

  public function PatientVitalReport(Request $request)
  {
    return Inertia::render('Report/ManagementReports/PatientVitalReport');
  }
  // public function LoginLogsReport(Request $request)
  // {

  //       return view('Reports::task-status-report.task-status-list');
  // }

  public function vitalsReportSearch(Request $request)
  {
    // dd($request->all());
    $practicegrpid = sanitizeVariable($request->route('practicegrpid'));
    $practiceid = sanitizeVariable($request->route('practiceid'));
    $patient = sanitizeVariable($request->route('patient'));
    $fromdate = sanitizeVariable($request->route('fromdate1'));
    $todate = sanitizeVariable($request->route('todate1'));

    $configTZ = config('app.timezone');
    $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
    if ($fromdate == 'null' || $fromdate == '') {
      $date = date("Y-m-d");


      $fromdate = $date . " " . "00:00:00";
      $todate = $date . " " . "23:59:59";
      $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
      $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate);

    } else {


      $fromdate = $fromdate . " " . "00:00:00";
      $todate = $todate . " " . "23:59:59";

      $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
      $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate);
    }

    //  print_r($dt1. '==='. $dt2);
    if ($practicegrpid != 'null') {
      if ($practicegrpid == 0) {
        $prg = 0;
      } else {
        $prg = $practicegrpid;
      }

    } else {
      $prg = 'null';
    }

    if ($practiceid != 'null') {
      if ($practiceid == 0) {
        $p = 0;
      } else {
        $p = $practiceid;
      }

    } else {
      $p = 'null';
    }

    if ($patient != 'null') {
      if ($patient == 0) {
        $pt = 0;
      } else {
        $pt = $patient;
      }

    } else {
      $pt = 'null';
    }

    $query = "select practicegrp, practice,fname,lname, dob,
        to_char(bp_bmi_date at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY HH24:MI:SS') as bp_bmi_date,bp,bmi,
        to_char(hga1c_date at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY HH24:MI:SS') as hga1c_date,
        hga1c_val from patients.patient_vitals_report($prg,$p,$pt,timestamp '" . $dt1 . "',timestamp '" . $dt2 . "')";
    // dd($query);      

    // $query = "select p2.name as practice_name,p2.id as prac_id,
    //           count(distinct p.id)  as patient_count,
    //           count(distinct pv.patient_id)as bmicount, 
    //           count(distinct pv.patient_id) as bpcount
    //           from patients.patient p 
    //           left join patients.patient_vitals pv on pv.patient_id = p.id
    //           inner join patients.patient_providers pp on pp.patient_id = p.id and pp.provider_type_id = 1 and pp.is_active = 1
    //           inner join ren_core.practices p2 on p2.id = pp.practice_id    
    //           where(pv.bmi is not null or pv.bp is not null ) 
    //           and pv.created_at between '".$fromdate."' and '".$todate."' ";

    //           if($practiceid!='null' && ($patient=='null' || $patient == '')){
    //               $query .= "  and pp.practice_id = '".$p."'";
    //           } elseif (($practiceid=='null' || $practiceid == '') && $patient!='null') {
    //               $query .= "  and p.pid = '".$pt."'";
    //           } elseif ($practiceid!='null' && $patient!='null') {
    //               $query .= "  and pp.practice_id = '".$p."' and p.pid = '".$pt."'";
    //           }else{}

    //           $query .= "group by p2.name,p2.id";


    // dd($query);                        

    $data = DB::select($query);

    return Datatables::of($data)
      ->addIndexColumn()
      ->make(true);

  }

  public function vitalsChildcountReportSearch(Request $request)
  {
    // dd($request->all());
    $prac_id = sanitizeVariable($request->route('prac_id'));
    $fromdate = sanitizeVariable($request->route('fromdate'));
    $todate = sanitizeVariable($request->route('todate'));

    $configTZ = config('app.timezone');
    $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');

    $cid = session()->get('userid');
    $userid = $cid;
    $usersdetails = Users::where('id', $cid)->get();
    $roleid = $usersdetails[0]->role;

    if ($fromdate == 'null' || $fromdate == '') {
      $date = date("Y-m-d");


      $fromdate = $date . " " . "00:00:00";
      $todate = $date . " " . "23:59:59";
      $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
      $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate);

    } else {


      $fromdate = $fromdate . " " . "00:00:00";
      $todate = $todate . " " . "23:59:59";

      $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
      $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate);
    }

    if ($prac_id != 'null') {
      if ($prac_id == 0) {
        $p = 0;
      } else {
        $p = $prac_id;
      }

    } else {
      $p = 'null';
    }

    $query = "select p.fname ,p.lname ,p.dob,p2.name from patients.patient p 
                  left join patients.patient_vitals pv on pv.patient_id = p.id
                  inner join patients.patient_providers pp on pp.patient_id = p.id and pp.provider_type_id = 1 and pp.is_active = 1
                  inner join ren_core.practices p2 on p2.id = pp.practice_id  
                  where p2.id = '" . $p . "'  and (pv.bmi is not null or pv.bp is not null ) and pv.created_at between '" . $fromdate . "' and '" . $todate . "' ";


    // dd($query);                        

    $data = DB::select($query);

    return Datatables::of($data)
      ->addIndexColumn()
      ->make(true);

  }


}