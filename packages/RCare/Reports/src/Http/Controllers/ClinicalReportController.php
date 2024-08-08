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

class ClinicalReportController extends Controller  
{ 
    public function ClinicalInsightList(Request $request) 
    {
            return Inertia::render('Report/ManagementReports/ClinicalInsight');
    }

    public function ClinicalReportSearch(Request $request)  
    {  
        $practicesgrp = sanitizeVariable($request->route('practicesgrp'));
        $practices = sanitizeVariable($request->route('practices'));
        $fromdate  =sanitizeVariable($request->route('from_date'));
        // $todate  =sanitizeVariable($request->route('todate1'));
        $configTZ     = config('app.timezone');
        $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        $cid = session()->get('userid');
        $userid = $cid;
        $usersdetails = Users::where('id',$cid)->get();
        $roleid = $usersdetails[0]->role;
        $pracgrp; 
        $pr;
        if($fromdate=='' || $fromdate=='null' || $fromdate=='0') 
        {
            $fromdate = date('Y-m');
        }
        else
        { 
             $fromdate = $fromdate;
        }

        $year = date('Y', strtotime($fromdate));
        // $month = date('m', strtotime($fromdate));  
        $month = strtoupper(date('M', strtotime($fromdate)));
      

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

        // $query =   "select * from patients.initial_report($pracgrp,$pr,$p,'".$fromdate."', '".$todate."')";
               
        $query = "Select * from reports.zr_mis_01_clinicalinsights order by refmonth";
        // "select * from reports.zr_mis_clinical_insights($pracgrp,$p,'".$month."',$year)";            
            $data = DB::select($query);  
            return Datatables::of($data)     
            ->addIndexColumn()            
            ->make(true);
    }


    public function ClinicalReportDynamicSearch(Request $request)  
    {  
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

        // $query = "select month_year, pid, ccm_enrolled, ccm_active_patients, HTN, Diabetes,CHF,CKD, Hyperlipidemia,COPD, Asthma, Depression, Anxiety,
        //         Dementia,Arthritis, other_diagnosis, female,male,younger,age_40to49,age_50to59,age_60to69,age_70to79,age_80to89,age_90to99,above,
        //          hospitalization, er_visit, urgent_care, social_needs, medications_prescribed,
        //         fallen, office_appointment, resource_medication, medication_renewal, called_office_patientbehalf, referral_support,
        //         no_other_services, routine_response_one,routine_response_two,
        //         routine_response_three,
        //         routine_response_four,
        //         routine_response_five, 
        //         routine_response_six,
        //         routine_response_seven, 
        //         routine_response_eight,
        //         urgent_response_one, urgent_response_two,
        //         urgent_response_three, urgent_response_four,
        //         urgent_response_five, urgent_response_six,
        //         urgent_response_seven
        //         from patients.initial_report($pracgrp,$pr,$p,'".$fromdate."', '".$todate."')";

        $query =   "select * from patients.initial_report($pracgrp,$pr,$p,'".$fromdate."', '".$todate."')";

               
               
        //  dd($query);                          

            $data = DB::select($query);    
            // dd($data)  ;
        
            // return Datatables::of($data)     
            // ->addIndexColumn()            
            // ->make(true);
            
            
            // $diagnosis = "select max(maxval.total) as total ,max(maxval.qualified) as quli,max(maxval.disquali) as nonquli from ($query) maxval";
            // $activity = "select count(activity) as total from ren_core.activities where timer_type like '4' and activity_type like 'Routine Response' ";
            $a = "select count(*),activity_type from ren_core.activities where timer_type like '4' group by activity_type";
            $activity = DB::select($a);
            // dd($activity); 
            // $total_diag = $activity[3]->count;

            $arrydata=array(); 
            $ddata=array(); 
            $columnheader=array();
            $columnheader1=array();
            $finalheader=array();
            $maxcount=0;
            $codedata;

            // dd($data);

            // $newdata = array("month_year", "ccm_enrolled", "ccm_active_patients", "HTN", "Diabetes","CHF","CKD", "Hyperlipidemia",
            // "COPD", "Asthma", "Depression", "Anxiety",
            // "Dementia","Arthritis", "other_diagnosis", "female","male","younger","age_40to49","age_50to59","age_60to69",
            // "age_70to79","age_80to89","age_90to99","above",
            // "hospitalization", "er_visit", "urgent_care", "social_needs", "medications_prescribed",
            // "fallen"," "," "," "," "," "," "," "," "," ");

            // $data=array_merge($newdata,$data);
            // dd(count($data),$data);

            for($i=0;$i<count($data);$i++){

                if($data[$i]->month_year!=""){
                    $headername="header".$i;

                //0.Authorized Cm Only    
                // 1.Mailed Documents
                // 2.Medication Support
                // 3.Referral/Order Support
                // 4.Resource Support
                // 5.Routine Response
                // 6.Urgent/Emergent Response
                // 7.Verbal Education/Review with Patient
                // 8.Veterans Services
                    

                    $arrydata=array($data[$i]->month_year,$data[$i]->ccm_enrolled,$data[$i]->ccm_active_patients,
                    $data[$i]->htn,$data[$i]->diabetes,$data[$i]->chf,$data[$i]->ckd,
                    $data[$i]->hyperlipidemia,$data[$i]->copd,$data[$i]->asthma,$data[$i]->depression,
                    $data[$i]->anxiety,$data[$i]->dementia,$data[$i]->arthritis,$data[$i]->other_diagnosis,
                    $data[$i]->female,$data[$i]->male,$data[$i]->younger,$data[$i]->age_40to49,
                    $data[$i]->age_50to59,$data[$i]->age_60to69,$data[$i]->age_70to79,$data[$i]->age_80to89,
                    $data[$i]->age_90to99,$data[$i]->above,$data[$i]->hospitalization,$data[$i]->er_visit,
                    $data[$i]->urgent_care,$data[$i]->social_needs,$data[$i]->medications_prescribed,$data[$i]->fallen,

                    $data[$i]->no_additional_response_one,

                    $data[$i]->authorized_response_one, $data[$i]->authorized_response_two,  

                    $data[$i]->mailed_response_one,$data[$i]->mailed_response_two,$data[$i]->mailed_response_three,
                    $data[$i]->mailed_response_four,$data[$i]->mailed_response_five,

                    $data[$i]->medication_response_one, 
                    $data[$i]->medication_response_two, $data[$i]->medication_response_three,  $data[$i]->medication_response_four,
                    $data[$i]->medication_response_five,

                    $data[$i]->referral_response_one, $data[$i]->referral_response_two,  $data[$i]->referral_response_three, 
                    $data[$i]->referral_response_four, $data[$i]->referral_response_five,  $data[$i]->referral_response_six,
                    $data[$i]->referral_response_seven, $data[$i]->referral_response_eight,$data[$i]->referral_response_nine, 
                    $data[$i]->referral_response_ten,

                    $data[$i]->resource_response_one,$data[$i]->resource_response_two,$data[$i]->resource_response_three,
                    $data[$i]->resource_response_four,$data[$i]->resource_response_five,$data[$i]->resource_response_six,
                    $data[$i]->resource_response_seven,$data[$i]->resource_response_eight,$data[$i]->resource_response_nine,
                    $data[$i]->resource_response_ten,$data[$i]->resource_response_eleven,

                    $data[$i]->routine_response_one, $data[$i]->routine_response_two,  $data[$i]->routine_response_three, 
                    $data[$i]->routine_response_four, $data[$i]->routine_response_five,  $data[$i]->routine_response_six,
                    $data[$i]->routine_response_seven, $data[$i]->routine_response_eight,

              
                    $data[$i]->urgent_response_one, $data[$i]->urgent_response_two,  $data[$i]->urgent_response_three, 
                    $data[$i]->urgent_response_four, $data[$i]->urgent_response_five,  $data[$i]->urgent_response_six,
                    $data[$i]->urgent_response_seven, 

                    $data[$i]->verbal_response_one, $data[$i]->verbal_response_two, $data[$i]->verbal_response_three,  $data[$i]->verbal_response_four,

                    $data[$i]->veterans_response_one,$data[$i]->veterans_response_two,$data[$i]->veterans_response_three,
                    $data[$i]->veterans_response_four,$data[$i]->veterans_response_five,$data[$i]->veterans_response_six,
                    $data[$i]->veterans_response_seven,$data[$i]->veterans_response_eight

                   


                   );

                       
                   $ddata['DATA'][]=$arrydata; 

                }//end of if loop
            } //end of for loop

            // dd($ddata);

          
            // $ddata['DATA'][1]=$arrydata; 
            // dd($ddata);

            $dynamicheader=array();
              $columnheader=array("Month Year", "Ccm Enrolled", "Ccm Active Patients", "HTN", "Diabetes","CHF","CKD", "Hyperlipidemia",
              "COPD", "Asthma", "Depression", "Anxiety",
              "Dementia","Arthritis", "Other Diagnosis", "Female","Male","Younger","Age 40to49","Age 50to59","Age 60to69",
              "Age 70to79","Age 80to89","Age 90to99","Above",
              "Hospitalization", "Er Visit", "Urgent Care", "Social Needs", "Medications Prescribed",
              "Fallen","No Additional Services"
            );
              // $columnheader=array("Patient First Name");  


            for($m=0;$m<count($columnheader);$m++)
             { 
              $dynamicheader[]=array("title"=>$columnheader[$m]);
             }

             
                  
            //   $add = $total_diag; 
            //   $type_activity = "select activity from ren_core.activities where activity_type like 'Routine Response' ";
            //   $type_activity = \DB::select( \DB::raw($type_activity) ); 
              
            //   for($k=0;$k < count($type_activity);$k++)
            //   { 
            //      $n = $type_activity[$k]->activity;
            //      $varheader= $n;
            //      $dynamicheader[]=array("title"=>$varheader);
 
            //   }


              /*******trying all for activities ***************/
              $all_activity = "select activity_type,ARRAY_AGG(activity || ',' ) as activity from ren_core.activities where timer_type like '4' group by activity_type order by activity_type ";
              $all_activity = DB::select($all_activity);  
              //   dd($all_activity);
                foreach($all_activity as $all){
                   $specificactivity = $all->activity;
                   $activitytype = $all->activity_type;
                   $str = explode(',",',$specificactivity);
              
                   foreach($str as $s){
                  
                    
                    $s = str_replace('{"', '', $s);
                    $s = str_replace(',"}', '', $s);
                    $s = str_replace('"', '', $s);  
                    $varheader= $activitytype."-".$s;
                    $dynamicheader[]=array("title"=>$varheader);
                 
                   }
                  
                }   
              
                //0.Authorized Cm Only    
                // 1.Mailed Documents
                // 2.Medication Support
                // 3.Referral/Order Support
                // 4.Resource Support
                // 5.Routine Response
                // 6.Urgent/Emergent Response
                // 7.Verbal Education/Review with Patient
                // 8.Veterans Services





             /*******trying all for activities ***************/
            





            // dd($dynamicheader);           
            //  $columnheader1=array("Practice","Minutes Spent");
            //  for($m1=0;$m1<count($columnheader1);$m1++)
            //  {
            //   $dynamicheader[]=array("title"=>$columnheader1[$m1]);
            //  }
            // dd($dynamicheader);

            $fdata['COLUMNS']=$dynamicheader; 
            // $fdata['COLUMNS2']=$dynamicheader; 
            // $fdata['COLUMNS'][1]=$dynamicheader;  
            
            // $newdata = array();
            // $newdata = $fdata;

            // $f1data = array_merge($newdata,$fdata);

            // dd($f1data);  
    
            $finldata=array_merge($fdata,$ddata);
            // $finldata=array_merge($f1data,$ddata);
            // dd($finldata);
          
             return json_encode($finldata);
    }



}