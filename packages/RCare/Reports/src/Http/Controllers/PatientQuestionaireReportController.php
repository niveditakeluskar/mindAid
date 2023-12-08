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
    
class PatientQuestionaireReportController extends Controller {

    public function PatientQuestionaireReport(Request $request){ 
        return view('Reports::pateint-questionaire-report.pateint_questionaire_report');
    }

    public function PatientQuestionaireReportSearch(Request $request){ 
        $practices = sanitizeVariable($request->route('practice'));
        $patient = sanitizeVariable($request->route('patient'));
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
        $dt1;
        $dt2;
       


        if( $patient!='null')
        {
            if( $patient==0)
            {
                $p = 0;  
            }
            else{
                $p = $patient;
            } 
        }
        else
        {
        $p = 'null';
        }

        if($practices!='null')
        {
            if( $practices==0) 
            {
                $pr = 0;  
            }
            else
            {
                $pr = $practices;
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
         
        $query = "select * from patients.patient_questionaire_report($pr,$p,timestamp '".$dt1."',timestamp '".$dt2."',$gq)";                
        // dd($query);
        $data = DB::select( DB::raw($query) );
        //dd($data);
        return Datatables::of($data) 
        ->addIndexColumn()
        ->rawColumns(['action'])     
        ->make(true);       
    }
}











