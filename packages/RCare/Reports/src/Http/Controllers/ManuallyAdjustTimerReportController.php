<?php 
namespace RCare\Reports\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
use Session; 
use RCare\System\Traits\DatesTimezoneConversion;

 
class ManuallyAdjustTimerReportController extends Controller
{
    //updated by ashvini 4th dec 2020
    public function listMonthlyReportPatientsSearch(Request $request)//manually adjust timer report storeprocedure
    {    
        if ($request->ajax()) { 
            $practicesgrp = sanitizeVariable($request->route('practicesgrpid'));
            $practices    = sanitizeVariable($request->route('practice'));
            $provider     = sanitizeVariable($request->route('provider'));
            $module_id    = sanitizeVariable($request->route('modules'));
            $monthly      = sanitizeVariable($request->route('monthly'));  
            $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus')); 
            $configTZ     = config('app.timezone');
            $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
    
            if($module_id == '' || $module_id == 0|| $module_id == null){
                $module_id=3;
            }
            if($monthly=='' || $monthly=='null' || $monthly=='0'){
                $monthly=date('Y-m-d');
            }  
            $year         = date('Y', strtotime($monthly));
            $month        = date('m', strtotime($monthly));

            // $fromdate = $year."-".$month."-01 00:00:00";
            // $todate = $monthly." 23:59:59"; 
  
            // $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);
            // $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate);        

      
            // to_char(ccsrecdate at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as
            $query        = "select pid , pppracticeemr , pfname , plname , pmname , pprofileimg , pdob ,pdcondition , prprovidername , pracpracticename , pppracticeid , 
              ccsrecdate  , ptrtotaltime , pstatus, pbillable, nonbillabeltime, billabeltime
              from patients.sp_manually_adjust_timer_report($practices,$provider,$module_id,$month,$year,$practicesgrp,$activedeactivestatus)" ; 
            // echo $query;

            // dd($query);

            $data         = DB::select( DB::raw($query) );
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                $btn ='<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->pid.'" data-original-title="Manually adjust time" class="manually_adjust_time" title="Manually adjust time"><i class="text-20 i-Stopwatch" style="color: #2cb8ea;"></i></a><input type="hidden" id="pbillable" value="'.$row->pbillable.'">';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
}