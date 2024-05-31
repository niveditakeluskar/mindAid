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
use RCare\Org\OrgPackages\Users\src\Models\Users;
use RCare\TaskManagement\Models\ToDoList;
use RCare\Org\OrgPackages\Practices\src\Models\Practices; 
use RCare\Org\OrgPackages\Providers\src\Models\Providers;
//use RCare\Reports\Http\Requests\MonthlyBilllingReportPatientsSearchRequest;
use RCare\System\Traits\DatesTimezoneConversion; 
use DataTables;
use Carbon\Carbon; 
use Session; 
    use RCare\Org\OrgPackages\StageCodes\src\Models\StageCode;

class QuestionaireReportController extends Controller {

    public function generalStageCode(Request $request)
        {
    $options = [];
    $module_id = '3';
$submodule_id = '19';
$stage_id = getFormStageId($module_id, $submodule_id, 'General Question');

        foreach (StageCode::generalStageCode($module_id,$stage_id) as $activeDiagnosisList) {
            $options[$activeDiagnosisList->id] = $activeDiagnosisList->description;
        }
          
        $options = array_unique($options);
   
        return response()->json($options);
  }

    public function QuestionaireReport(Request $request){ 
        return view('Reports::questionaire_report.questionaire-report');
    }

    public function QuestionaireReportSearch(Request $request){ 
        $practicesgrp = sanitizeVariable($request->route('practicesgrp'));
        $practices = sanitizeVariable($request->route('practice'));
        $provider = sanitizeVariable($request->route('provider'));
        $fromdate  =sanitizeVariable($request->route('fromdate1'));
        $todate  =sanitizeVariable($request->route('todate1'));
        $genquestionselection = sanitizeVariable($request->route('genquestionselection'));
        $login_user = Session::get('userid');
        $configTZ   = config('app.timezone');
        $userTZ     = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
        $cid = session()->get('userid');
        $userid = $cid;
        $usersdetails = Users::where('id',$cid)->get();
        $roleid = $usersdetails[0]->role;
        $p;
        $pr;
        $pracgrp; 
        $dt1;
        $dt2;
       
        if( $practicesgrp!='null')
        {
            if( $practicesgrp==0)
            {
                $pracgrp = 0;  
            }
            else{
                $pracgrp = $practicesgrp;
            } 
        }
        else
        {
        $pracgrp = 'null';
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

       if( $genquestionselection !='null'){
        if($genquestionselection==0){
            $gq = 0;
        }else{
            $gq = $genquestionselection;
        }

       } else{
         $gq = 'null';
       }
                   
        if($fromdate=='null' || $fromdate=='')
         {
          $date=date("Y-m-d");   
              

          $fromdate =$date." "."00:00:00";    
          $todate = $date ." "."23:59:59"; 
          $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate); 
          $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate); 

         }
         else{
        
       
          $fromdate =$fromdate." "."00:00:00";   
          $todate = $todate ." "."23:59:59"; 
         
          $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fromdate);     
          $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $todate);               
         }   
         

        $query = "select month_year, bmicount, bpcount, bmi_greater_25, bmi_less_18,bp_140_90, bp_180_110, HgA1C_greater_7, HgA1C_less_7, topic, option, count from patients.questionaire_report($p,$pr,$pracgrp,timestamp '".$dt1."',timestamp '".$dt2."',$gq)";
                             
        //dd($query);
        $data = DB::select($query);
        //dd($data);
        return Datatables::of($data) 
        ->addIndexColumn()
        ->rawColumns(['action'])     
        ->make(true);       
    }
}











