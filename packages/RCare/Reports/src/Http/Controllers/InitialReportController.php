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
// use RCare\Org\OrgPackages\Users\src\Models\RenUserLoginHistory;
use RCare\System\Traits\DatesTimezoneConversion; 

class InitialReportController extends Controller  
{ 
    // public function LoginLogsReport(Request $request)
    // {
        
    //       return view('Reports::task-status-report.task-status-list');
    // }

    public function initialReportSearch(Request $request)  
    {  
        //dd($request);
        $practicesgrp = sanitizeVariable($request->route('practicesgrp'));
        $practices = sanitizeVariable($request->route('practices'));
        $provider = sanitizeVariable($request->route('provider'));
       // dd($practicesgrp);

        $fromdate  =sanitizeVariable($request->route('fromdate1'));
        $todate  =sanitizeVariable($request->route('todate1'));
        $configTZ     = config('app.timezone');
        $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        $cid = session()->get('userid');
        $userid = $cid;
        $usersdetails = Users::where('id',$cid)->get();
        $roleid = $usersdetails[0]->role;
        //dd($practiceid);
        $pracgrp; 
        $p;
        $pr;

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

        $query = "select month_year, pid, ccm_enrolled, ccm_active_patients, HTN, Diabetes,CHF,CKD, Hyperlipidemia,COPD, Asthma, Depression, Anxiety,
                Dementia,Arthritis, other_diagnosis, female,male,younger,age_40to49,age_50to59,age_60to69,age_70to79,age_80to89,age_90to99,above,
                 hospitalization, er_visit, urgent_care, social_needs, medications_prescribed,
                fallen, office_appointment, resource_medication, medication_renewal, called_office_patientbehalf, referral_support, no_other_services
                from patients.initial_report($pracgrp,$pr,$p,'".$fromdate."', '".$todate."')";

               
               
         // dd($query);                        

            $data = \DB::select( \DB::raw($query) );      
        
            return Datatables::of($data)     
            ->addIndexColumn()            
            ->make(true);     
    }
}