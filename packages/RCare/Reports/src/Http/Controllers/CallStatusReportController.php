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

class CallStatusReportController extends Controller
{

    public function PatientCallStatusReport(Request $request) 
    {
    
          return view('Reports::call-status-report.call-status-list');
    }

    public function PatientCallStatusReportSearch(Request $request)
    {     
       // dd($request);
        $practicesgrp = sanitizeVariable($request->route('practicesgrpid'));
        $practices = sanitizeVariable($request->route('practiceid'));
        $provider = sanitizeVariable($request->route('providerid'));
        $module_id = sanitizeVariable($request->route('module'));
        $timeperiod = sanitizeVariable($request->route('timeperiod'));
        // $fromdate  =sanitizeVariable($request->route('fromdate'));
        // $todate  =sanitizeVariable($request->route('todate'));
        // $time  =sanitizeVariable($request->route('time')); 
        // $timeoption=sanitizeVariable($request->route('timeoption'));
        $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus')); 
        $configTZ     = config('app.timezone');
        $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
     
         $pracgrp; 
         $p;
         $pr;
         $totime;
         $totimeoption;
         $timeperiods;

        if($module_id=='null')
        {
           $module_id=3;
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

        if($timeperiod!='null')
        {
            if( $timeperiod==0) 
            {
                $timeperiods = 0;  
            }
            else
            {
                $timeperiods = $timeperiod;
            }
        }
        else{
            $timeperiods = 'null';
        }
  
        $query = "select pid, pfname, plname, pmname, pdob , pprofileimg, pppracticeemr, pracpracticename, prprovidername, pstatus, to_char(ccsrecdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as ccsrecdate , ccsrec_age from patients.sp_call_status_report($p,$pr,3,$practicesgrp,$activedeactivestatus,$timeperiods)";         
        
        
        // dd($query);     
        $data = DB::select( DB::raw($query) );
        //  dd($data);
        return Datatables::of($data) 
        ->addIndexColumn()             
        ->make(true);      
       
    }




}



