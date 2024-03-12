<?php 
namespace RCare\Rpm\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientProvider;
use RCare\Org\OrgPackages\Diagnosis\src\Models\DiagnosisCode;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RCare\TaskManagement\Models\UserPatients;
use RCare\Org\OrgPackages\Practices\src\Models\Practices; 
use RCare\Org\OrgPackages\Providers\src\Models\Providers; 
use RCare\System\Traits\DatesTimezoneConversion; 

use RCare\Rpm\Models\Observation_BP;
use RCare\Rpm\Models\Observation_Oxymeter;  
use RCare\Rpm\Models\Observation_Heartrate;  
use RCare\Rpm\Models\Observation_Weight;
use RCare\Rpm\Models\Observation_Glucose;
use RCare\Rpm\Models\Observation_Spirometer;
use RCare\Rpm\Models\Observation_Temp;

use RCare\Messaging\Models\MessageLog;
use RCare\TaskManagement\Models\ToDoList;
// use RCare\Reports\Http\Requests\MonthlyBilllingReportPatientsSearchRequest;
use DataTables;
use Carbon\Carbon; 
use Session;

class TimelineActivityController extends Controller
{ 
  public function patientMonthlyData(Request $request)
  {     
        $patient_id = sanitizeVariable($request->route('patient_id'));
        $monthly = date('Y-m');
        $year = date('Y', strtotime($monthly));
        $month = date('m', strtotime($monthly));
        // dd(sanitizeVariable($request->month)); 
        $patient =  Patients::with('patientServices', 'patientServices.module')->where('id',$patient_id)->get();
        $ob_bp = Observation_BP::whereMonth('effdatetime', $month)
        ->whereYear('effdatetime',$year)->where('patient_id',$patient_id)
        ->select('effdatetime','systolic_qty','diastolic_qty')
        ->orderBy('effdatetime','desc')->get();
        
        $ob_oxy = Observation_Oxymeter::whereMonth('effdatetime', $month)
        ->whereYear('effdatetime',$year)->where('patient_id',$patient_id) 
        ->select('effdatetime','oxy_qty') 
        ->orderBy('effdatetime','desc')->get();
        
        $ob_weight = Observation_Weight::whereMonth('effdatetime', $month)
        ->whereYear('effdatetime',$year)->where('patient_id',$patient_id)
        ->select('effdatetime','weight')
        ->orderBy('effdatetime','desc')->get();
        
        $ob_glucose = Observation_Glucose::whereMonth('effdatetime', $month)
        ->whereYear('effdatetime',$year)->where('patient_id',$patient_id)
        ->select('effdatetime','value')
        ->orderBy('effdatetime','desc')->get();
        
        $ob_spiro = Observation_Spirometer::whereMonth('effdatetime', $month)
        ->whereYear('effdatetime',$year)->where('patient_id',$patient_id)
        ->select('effdatetime','fev_value','pef_value')
        ->orderBy('effdatetime','desc')->get(); 

        $to_do_list = ToDoList::whereMonth('task_date', $month)
        ->whereYear('task_date',$year)->where('patient_id',$patient_id)
        ->select('task_date','task_notes','status')
        ->orderBy('task_date','desc')->get();
        // dd($to_do_list);
        
          // $dateS = Carbon::now()->startOfMonth()->subMonth(1);
          // $dateE = Carbon::now(); 
        $reminder_rpm_reading = MessageLog::select('patient_id','status','stage_id','created_at','module_id','message_date','status','message_date','id','message')
                          ->where('patient_id', $patient_id)//202920473
                          ->whereMonth('created_at',$month)
                          ->whereYear('created_at',$year)
                          // ->whereBetween('created_at',[$dateS,$dateE])
                          ->orderBy('created_at', 'desc')->get();
                          // foreach($call_history as $callhistory){  
                          //     echo "<li>" ;
                          //             if($callhistory->status == "received"){
                          //                 echo "<h5> Incoming Response (".$callhistory->created_at.")</h5>";
                          //                 echo  "<b>SMS: </b>".$callhistory->message;
                          //             } else{
                          //                 echo "<h5> Sent Messages (".$callhistory->created_at.")</h5>";
                          //                 echo  "<b>SMS: </b>".$callhistory->message;
                          //             }
                          //     echo  "</li>" ;
                          // } 
                          //  dd($reminder_rpm_reading);
        return view('Rpm::timeline_rpm.timeline-activities',compact('month','year','patient','patient_id','ob_bp','ob_oxy','ob_weight','ob_glucose','ob_spiro','to_do_list','reminder_rpm_reading'));
  } 

  public function patientMonthlyDataSearch(Request $request)
  {     
    $patient_id = sanitizeVariable($request->route('patient_id'));
    // dd($patient_id);
    $monthly = sanitizeVariable($request->route('month_val'));
    // dd($monthly);
    // $monthly = date('Y-m');
    //date('2022-05'); 
    $year = date('Y', strtotime($monthly));
    $month = date('m', strtotime($monthly));
    // dd(sanitizeVariable($request->month)); 
    $patient =  Patients::with('patientServices', 'patientServices.module')->where('id',$patient_id)->get();
    $ob_bp = Observation_BP::whereMonth('effdatetime', $month)
    ->whereYear('effdatetime',$year)->where('patient_id',$patient_id)
    ->select('effdatetime','systolic_qty','diastolic_qty')
    ->orderBy('effdatetime','desc')->get();

    $ob_oxy = Observation_Oxymeter::whereMonth('effdatetime', $month)
    ->whereYear('effdatetime',$year)->where('patient_id',$patient_id) 
    ->select('effdatetime','oxy_qty') 
    ->orderBy('effdatetime','desc')->get();

    $ob_weight = Observation_Weight::whereMonth('effdatetime', $month)
    ->whereYear('effdatetime',$year)->where('patient_id',$patient_id)
    ->select('effdatetime','weight')
    ->orderBy('effdatetime','desc')->get();

    $ob_glucose = Observation_Glucose::whereMonth('effdatetime', $month)
    ->whereYear('effdatetime',$year)->where('patient_id',$patient_id)
    ->select('effdatetime','value')
    ->orderBy('effdatetime','desc')->get();

    $ob_spiro = Observation_Spirometer::whereMonth('effdatetime', $month)
    ->whereYear('effdatetime',$year)->where('patient_id',$patient_id)
    ->select('effdatetime','fev_value','pef_value')
    ->orderBy('effdatetime','desc')->get(); 

    $to_do_list = ToDoList::whereMonth('task_date', $month)
    ->whereYear('task_date',$year)->where('patient_id',$patient_id)
    ->select('task_date','task_notes','status')
    ->orderBy('task_date','desc')->get();
    // dd($to_do_list);

    // $dateS = Carbon::now()->startOfMonth()->subMonth(1);
    // $dateE = Carbon::now(); 
    $reminder_rpm_reading = MessageLog::select('patient_id','status','stage_id','created_at','module_id','message_date','status','message_date','id','message')
        ->where('patient_id', $patient_id)//202920473
        ->whereMonth('created_at',$month)
        ->whereYear('created_at',$year)
        // ->whereBetween('created_at',[$dateS,$dateE])
        ->orderBy('created_at', 'desc')->get();
        return view('Rpm::timeline_rpm.timeline-activities-ui',compact('month','year','patient','patient_id','ob_bp','ob_oxy','ob_weight','ob_glucose','ob_spiro','to_do_list','reminder_rpm_reading'));
  } 
}
