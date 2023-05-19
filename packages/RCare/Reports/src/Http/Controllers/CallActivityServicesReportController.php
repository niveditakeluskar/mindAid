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

class CallActivityServicesReportController extends Controller

{

    public function callActivityServiceListsReport(Request $request) 
    {
    
          return view('Reports::call-additional-services.call-additioanl-services-list');
    }

    public function callActivityServiceListSearch(Request $request)
    {     
       // dd($request);
        $patient = sanitizeVariable($request->route('patient'));
        $practices = sanitizeVariable($request->route('practices'));
        $fromdate=sanitizeVariable($request->route('fromdate'));
        $todate=sanitizeVariable($request->route('todate'));
        
        $configTZ     = config('app.timezone');
        $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
     
         $pracgrp; 
         $p;
         $pr;

     
    
        if( $practices!='null')
        {
            if( $practices==0)
            {
                $pr = 'null';  
            }
            else{
                $pr = $practices;
            } 
        }
        else
        {
        $pr = 'null';
        }

        if($patient!='null')
        {
            if( $patient==0) 
            {
                $p = 'null';  
            }
            else
            {
                $p = $patient;
            } 
        }
        else{
        $p = 'null';
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
  
        } 
        else
        {      
           $fromdate = $fromdate." 00:00:00"; 
           $todate = $todate." 23:59:59" ; 
           $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
           $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate);
        }
          
        $query = "select pid, patient_name, practicename, caremanager,
        to_char(call_record_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as call_record_date,
        call_continue_status, emr_entry_completed, schedule_office_appointment,  
        resources_for_medication,medical_renewal,called_office_patientbehalf,referral_support,no_other_services
        from patients.sp_call_activity_services_report($p,$pr,timestamp '".$dt1."',timestamp '".$dt2."' )";          
        // dd($query); 
        $data = DB::select( DB::raw($query) ); 
        // dd($data);
        return Datatables::of($data) 
        ->addIndexColumn()             
        ->make(true);       
       
    }




}



