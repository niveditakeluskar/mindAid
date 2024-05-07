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
use RCare\Org\OrgPackages\Diagnosis\src\Models\DiagnosisCode;
use DataTables;
use Carbon\Carbon;
use Session;
use RCare\Org\OrgPackages\Users\src\Models\RenUserLoginHistory;
use RCare\Org\OrgPackages\RPMBillingConfiguration\src\Models\RPMBilling; 
use RCare\System\Traits\DatesTimezoneConversion;  

class RpmBillingReportController extends Controller  
{ 
    public function RpmBillingReportSearch(Request $request)  
    {  
        $patient_id =  sanitizeVariable($request->route('patient')); 
        $practice_id =  sanitizeVariable($request->route('practice')); 
        $users =        sanitizeVariable($request->route('users'));
        $fromdate = sanitizeVariable($request->route('fromdate'));
        $todate = sanitizeVariable($request->route('todate'));
        $moduleid = sanitizeVariable($request->route('moduleid'));
        $provider= sanitizeVariable($request->route('provider'));
        $practicesgrp= sanitizeVariable($request->route('practicesgrp'));
        $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus'));
        $callstatus= sanitizeVariable($request->route('callstatus'));
        $RpmConfiguration = RPMBilling::orderby('id','desc')->first();
        $RpmBillingDay = $RpmConfiguration->required_billing_days;
        if($fromdate=='' || $fromdate=='null' || $fromdate=='0'){
               $fromdate=date('Y-m-d');
        }else{
            $fromdate=$fromdate; 
        }
        if($todate=='' || $todate=='null' || $todate=='0')
          {
              $todate=date('Y-m-d');
          }
          else
          {
               $todate=$todate;
          }

        // $year = date('Y', strtotime($fromdate));
        // $month = date('m', strtotime($fromdate));

        //$firstDateOfMonth= $fromdate; 
        //Last date of current month.
       // $lastDateOfMonth = date("Y-m-t", strtotime($firstDateOfMonth));

        //$last_day_this_month  = date($fromdate.'-'.'t');

        $fdt = $fromdate." "."00:00:00";   
        $tdt = $todate ." "."23:59:59"; 

        $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fdt);
        $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($tdt);   

        //print_r($fdt);
        //print_r($tdt);die;
        $configTZ     = config('app.timezone');
        $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
       //  $query = "select * from patients.sp_rpm_monthly_billing_report($patient_id, $practice_id, $moduleid,timestamp '".$fdt."',timestamp '".$tdt."',$provider,$practicesgrp,$activedeactivestatus,$callstatus)"; 
       //  $data = DB::select( DB::raw($query) );
       // // dd($data);
        //dd($fdt);
        //dd($tdt);
        //dd($configTZ);
        //dd($userTZ);
        //dd($dt1);
        //dd($dt2);
        $query = "select * from patients.sp_rpm_monthly_billing_report($patient_id, $practice_id, $moduleid,timestamp '".$fdt."',timestamp '".$tdt."',$provider,$practicesgrp,$activedeactivestatus,$callstatus,'".$configTZ."', '".$userTZ."')sp
               left join (select distinct patient_id,count(*)as total,count(quali) as qualified,
                count(disquali) as disquali from (select distinct pd.patient_id ,pd.code,r.qualified as quali,dr.qualified as disquali
        from patients.patient_diagnosis_codes pd 
         left join ren_core.diagnosis_codes r on r.code = pd.code and  r.diagnosis_id  = pd.diagnosis and r.qualified = 1 and r.status =1 
         left join ren_core.diagnosis_codes dr on dr.code = pd.code and  dr.diagnosis_id  = pd.diagnosis and dr.qualified = 0 and dr.status =1
         left join patients.patient_providers pp on pp.patient_id =pd.patient_id
         left join ren_core.providers pr on pp.provider_id=pr.id 
         left join ren_core.practices prac on pp.practice_id = prac.id
         inner join patients.patient_services ps on pd.patient_id=ps.patient_id
         where pd.created_at between '".$dt1."'and '".$dt2."' and pd.status =1 and pp.provider_type_id = 1 and pp.is_active =1
         "; 

            if($practice_id!="" && $practice_id !='null'){
              $query .= " and pp.practice_id =".$practice_id;
            }
           
           if($provider!="" && $provider !='null'){
              $query .= " and pp.provider_id =".$provider;
           }
           if($moduleid!="" && $moduleid !='null'){
              $query .= " and ps.module_id =".$moduleid;
           }
           if($practicesgrp!="" && $practicesgrp !='null'){
              $query .= " and prac.practice_group =".$practicesgrp;
           }

       $query .=" group by pd.patient_id,pd.code,r.qualified,dr.qualified ) x group  by x.patient_id) y on y.patient_id=sp.rwpid";
      //  dd($query);
             
          $data = DB::select($query);
            
          $diagnosis = "select max(maxval.total) as total ,max(maxval.qualified) as quli,max(maxval.disquali) as nonquli from ($query) maxval";

      $diagnosis = DB::select($diagnosis);
      // dd($diagnosis);
      // if($diagnosis[0]->quli<2){
      //   $tot_quali=2;
      // }else{
      //   $tot_quali= $diagnosis[0]->quli;
      // }
      // $total_diag=$tot_quali+$diagnosis[0]->nonquli;
          //dd(DB::getQueryLog()); 
      $total_diag = $diagnosis[0]->total;
            $arrydata=array(); 
            $ddata=array();
              $columnheader=array();
              $columnheader1=array();
              $finalheader=array();
              $maxcount=0;
              $codedata; 
               
            for($i=0;$i<count($data);$i++)
            {
              // dd($data[$i]->cssrecdate);  
               $headername="header".$i;
               
              //  dd($data[$i]->dtrec_date); 
              // $arry_anand[]=$data[$i]->pdcode;
                  $dcode=$data[$i]->pdcode;
                  // print_r($dcode);

                  $splitcode=explode(',', $dcode);
                  // print_r($splitcode);
                  // if(is_null($data[$i]->prprovidername)){
                  //   $data[$i]->prprovidername='';
                  // }
                  
                  
                  if(is_null($data[$i]->pppracticeemr)){
                    $practiceemr='';
                  }else{
                    $practiceemr = $data[$i]->pppracticeemr;
                  }

                  
                  if(is_null($data[$i]->provider_name)){
                    $provider_name='';  
                  }else{
                    $provider_name = $data[$i]->provider_name;
                  }
                  
                  $pfinnumber = '';
                  if(is_null($data[$i]->pfin_number)){
                    $pfin_number ='';
                  }else{
                     $pfin_number = $data[$i]->pfin_number;
                  }

                  $patientdetails='';

                  if(is_null($data[$i]->pfname)){
                    $data[$i]->pfname='';
                  }
                  if(is_null($data[$i]->plname)){
                    $data[$i]->plname='';
                  } 
                  $patientdetails = $data[$i]->pfname.' '.$data[$i]->plname;;

                  // if($data[$i]->pprofileimg=='' || $data[$i]->pprofileimg==null)
                  // {
                  //   $patientdetails="<img src= assets/images/faces/avatar.png width='50px' class='user-image' /> ".$data[$i]->pfname;
                  // }
                  // else
                  // {
                  //     $patientdetails="<img src= assets/images/faces/avatar.png width='50px' class='user-image' /> ".$data[$i]->pfname;
                  // }

                  $pdob  = '';
                  if(is_null($data[$i]->pdob)){
                    //$data[$i]->pdob='';
                    $pdob ='';
                  }else{
                    $data[$i]->pdob = gmdate("m/d/Y", strtotime($data[$i]->pdob));
                    $pdob = $data[$i]->pdob;
                  }

                  $dev_traing_date=''; 
                  if(is_null($data[$i]->dtrec_date)){
                    $dev_traing_date ='';
                  }else{
                    $data[$i]->dtrec_date = date("m/d/Y",strtotime($data[$i]->dtrec_date));//gmdate("m/d/Y", strtotime($data[$i]->dtrec_date))
                    $dev_traing_date = $data[$i]->dtrec_date;
                  }
                  
                  if(is_null($data[$i]->pstatus)){
                    $data[$i]->pstatus='';
                  }else{
                     // dd($data[$i]->pstatus);
                    if($data[$i]->pstatus=='1'){
                      $status ='Active';
                    } 
                    if($data[$i]->pstatus=='0'){
                      $status ='Suspended';
                    }
                    if($data[$i]->pstatus=='2'){
                      $status ='Deactived'; 
                    }
                    if($data[$i]->pstatus=='3'){
                      $status ='Deceased';
                    }
                    
                  }
                  

                  if(is_null($data[$i]->userid)){
                    $assign_cm ='';
                  }else{
                    $assign_cm = $data[$i]->userfname.' '.$data[$i]->userlname;
                  }


                  
                  if(is_null($data[$i]->device_code)){
                    $assign_device_code ='';
                  }else{
                    $assign_device_code = $data[$i]->device_code;
                  }


                  if(is_null($data[$i]->total_reading_days)){
                    $total_reading_days ='';
                  }else{
                    $total_reading_days = $data[$i]->total_reading_days;
                  }

                  if(is_null($data[$i]->count_distinct_days)){
                    $count_distinct_days ='';
                  }else{
                    $count_distinct_days = $data[$i]->count_distinct_days;
                  }

                  if(is_null($data[$i]->reading_days)){
                    $reading_days ='';
                  }else{
                    $reading_days = $data[$i]->reading_days;
                  }
                  

                  if(is_null($data[$i]->billing_threshold_days)){
                    $billing_threshold_days = '';
                  }else{
                    $billing_threshold_days = $data[$i]->billing_threshold_days;

                     //$RpmBillingDay;
                  }

                  if(is_null($data[$i]->reading_exceeds)){
                    $reading_exceeds = '';
                  }else{
                    $reading_exceeds = $data[$i]->reading_exceeds; //$RpmBillingDay;
                  }


                  // if($data[$i]->count_distinct_days < $RpmBillingDay){
                  //   $reading_exceeds = 'No';
                  //   $billingcode = '';
                  //   $unit = '';
                  //   $billing_threshold_days = $RpmBillingDay;
                  // }else{
                  //   $reading_exceeds = 'Yes';
                  //     $billingcode = $data[$i]->billingcode;
                  //     $unit = $data[$i]->unit;
                  //     $billing_threshold_days = $RpmBillingDay;
                  // }
                  if(is_null($data[$i]->billingcode) ||$data[$i]->billingcode=='null'){
                    $billingcode = '';
                  }else{
                    $billingcode = $data[$i]->billingcode; //$RpmBillingDay;
                  }


                  if(is_null($data[$i]->unit)){
                    $unit = '';
                  }else{
                    $unit = $data[$i]->unit; //$RpmBillingDay;
                  }
                  
                  //$billing_threshold_days = $RpmBillingDay;
                  if(is_null($data[$i]->dos)){
               
                    $dos='';
                  }else{
                   
                    $dos = date("m/d/Y", strtotime($data[$i]->dos));
                  }
                  
               // $arrydata=array($data[$i]->prprovidername,$data[$i]->pppracticeemr,$data[$i]->pfname.''.$data[$i]->plname,$data[$i]->pdob,$data[$i]->dtrec_date,$data[$i]->billingcode,$unit,$status,$assign_cm);
                

                // if($data[$i]->count_distinct_days < $RpmBillingDay){
                //     $reading_exceeds = 'No';
                //   $unit ='';
                //   $billingcode='';
                //   }else{
                //     $reading_exceeds = 'Yes';
                //     $billingcode = $data[$i]->billingcode;
                //     $unit = $data[$i]->unit;
               $arrydata=array($provider_name,$practiceemr,ucwords(strtolower($data[$i]->pfname)),ucwords(strtolower($data[$i]->plname)),$data[$i]->pfin_number,$pdob,$count_distinct_days,
                $reading_days,$total_reading_days, 
                $billing_threshold_days,$reading_exceeds,$dos,$billingcode,$unit,$status,$assign_cm,$assign_device_code,$dev_traing_date);
                      $qualified_array=array();
                      $nonqualified_array=array();
                       //  for($j=0;$j<$diagnosis[0]->total;$j++) anand
                          for($j=0;$j<$total_diag;$j++) // change 11 to 0 ashwini changes
                          {
                          
                            if (array_key_exists($j,$splitcode) )//&& array_key_exists($j,$splitcondition)
                             {   //chk key availible in $splitcode                    
                          
                             
                              $maxcount=$maxcount;
                            
                              $iscode = $splitcode[$j];
                              
                              if($iscode=='' ||$iscode==null) {
                                $spcondition = '';
                                $spcode = ''; 
                               
                              }else{ 
                                  $splitcondition = explode('|', $iscode);
                                  $iscondition = $splitcondition[0];   //isme diagnosis condition hai.. eg.Arthritis  ( Osteoarthritis )
                                  $spcondition = str_replace("'","''",$iscondition);
                                  $spcode = '';
                                   if(isset($splitcondition[1])){
                                      $spcode = $splitcondition[1];   //isme diagnosis code hai....eg.M13.10
                                   }

                                   
                              }
                              
                             $chk ="select pd.code,pd.condition,pd.status,
                                    dc.qualified,dc.status from patients.patient_diagnosis_codes pd
                                    left join ren_core.diagnosis_codes dc
                                    on dc.code = pd.code and dc.diagnosis_id =pd.diagnosis
                                    where dc.qualified =1";

                              if($iscode!="" || $iscode !=null){
                                $chk .= " and pd.code = '".$spcode."' ";
                              }
                              if($iscode!="" || $iscode !=null){
                                $chk .= " and pd.condition = '".$spcondition."' ";
                              }
                              $chk .="and pd.status=1 and dc.status=1";
                                  // echo "<br>";  print_r($chk);

                                
                              if($chk){ 
                                   
                                if(!empty($splitcode[$j]) && $splitcode[$j]!=""){
                               
                                  $qualified_array[]=$spcode;                  //$splitcode[$j];// Qualified value                                 
                                  array_push($qualified_array, $spcondition); //array:1 [▼
                                  // dd($qualified_array) ;                                           //   0 => "J45.909"
                                                                              // ]
                                                                              // "Asthma"
                              } 
                              }else{   
                                if(!empty($splitcode[$j]) && $splitcode[$j]!=""){
                               
                                  $nonqualified_array[]=$spcode;//$splitcode[$j];// Non-Qualified value store in array
                                  array_push($nonqualified_array, $spcondition);
                                  // dd($nonqualified_array) ;  
                                }
                                
                              }  
                            } 
                            else 
                            {
                            
                            } 
                                  
                          }//for loop close  
                         // print_r($qualified_array); echo "string";
                         //  print_r($nonqualified_array);echo "<pre>";

                          //die; 
                          $get_cnt_quli=count($qualified_array);//get qualified  count 
                          if($get_cnt_quli<2){
                            $minus=2;
                          }else{ 
                            $minus=$get_cnt_quli;
                          } 
                     // print_r($get_cnt_quli);
                     // dd(count($nonqualified_array));
                        //  dd($total_diag);
                      if($get_cnt_quli == 0){
                      if( count($nonqualified_array)==0 ){
                         $get_cnt_nonquli1= $total_diag;
                         $get_cnt_nonquli= $get_cnt_nonquli1-$minus;
                      }
                      else if(count($nonqualified_array) > 0){  //9=9-(1+2)
                       $get_cnt_nonquli1=($total_diag) -count($nonqualified_array) ; //get count
                       $get_cnt_nonquli= $get_cnt_nonquli1-$minus;
                      }
                    }else if($get_cnt_quli>0){
                       if( count($nonqualified_array)==0 && $get_cnt_quli<2){//9
                         $get_cnt_nonquli1= $total_diag;
                         $get_cnt_nonquli= $get_cnt_nonquli1-$minus;
                      }else if( count($nonqualified_array)==0 && $get_cnt_quli>=2){ //here = add by priya
                         $get_cnt_nonquli1= $total_diag-$get_cnt_quli;
                         $get_cnt_nonquli= $get_cnt_nonquli1;
                      }

                      else if(count($nonqualified_array) > 0 && $get_cnt_quli<2){  //9=(9-4
                       $get_cnt_nonquli1=$total_diag -count($nonqualified_array) ; //get count 5-3
                       $get_cnt_nonquli= $get_cnt_nonquli1-$minus;
                      }else if(count($nonqualified_array) > 0 && $get_cnt_quli>=2){//6-2-5  //here = add by priya
                        $get_cnt_nonquli1=($total_diag-$get_cnt_quli)-count($nonqualified_array) ; //get count 8-5
                        $get_cnt_nonquli= $get_cnt_nonquli1;
                      }
                    }
                   // print_r($get_cnt_nonquli);
 //print_r($total_diag."=".count($nonqualified_array)."=".$get_cnt_quli."=".$get_cnt_nonquli);echo "<br>";//6423
                      /*if($get_cnt_nonquli1>0){  
                        $get_cnt_nonquli= $get_cnt_nonquli1-$minus;
                        
                      }*/ 
                      for($q=0;$q<$get_cnt_nonquli;$q++){
                        array_push($nonqualified_array,"");
                      }
                              // print_r($get_cnt_quli."=".count($nonqualified_array)); 
                               if($get_cnt_quli==0 && count($nonqualified_array)==0) {
                                  // dd("if");
                                 // $add1=($total_diag); //added by priya onn 24th feb 22
                                  
                                  $add1=($total_diag*2); 
                                  $kk=array();
                                  for($k=1;$k<=$add1;$k++){
                                    array_push($kk,"");
                                  } 
                                 
                                  $arrydata =  array_merge($arrydata,$kk);
                                } 
                                else if($get_cnt_quli==0 && count($nonqualified_array)>0){
                                  // dd("first else if");
                                  $k=array();
                                  for($x=1; $x<=($total_diag+2); $x++){
                                    array_push($k,"");
                                  }
                                  // $k= array("",""); //modified by radha
                                //  $k= array("","","","","","","","","","","","");   //modified yestrdy by me
                                 $kk= $nonqualified_array;                                    
                                 $arrydata=array_merge($arrydata,$k,$kk);
                             
                                }
                                else if($get_cnt_quli >=2){
                                 $k= $qualified_array;
                                //  dd(count($qualified_array),($total_diag*2) ); 

                                if( (count($qualified_array))<($total_diag*2) ){
                                  //  dd("inside if");
                                  $kk=array();
                                  $remaingcount =  ($total_diag*2)-(count($qualified_array));
                                  for($x=1; $x<= $remaingcount; $x++){
                                    array_push($kk,"");
                                  }
                                }else{
                                  // dd("inside else");
                                  $kk= $nonqualified_array; 
                                }
                                  $arrydata=array_merge($arrydata,$k,$kk);
                                }
                                else if($get_cnt_quli==1){
                                 $k= $qualified_array;
                                  $kk=array(); 
                                  for($x=1; $x<=($total_diag+1); $x++){
                                    array_push($kk,"");
                                  }
                                  $kkk=$nonqualified_array;
                                  $arrydata=  array_merge($arrydata,$k,$kk,$kkk);
                                }else{ 
                                    $k=array(); 
                                    for($x=1; $x<=($total_diag+1); $x++){
                                      array_push($k,"");
                                    }
                                  $kk=$nonqualified_array;
                                  $arrydata=  array_merge($arrydata,$k,$kk);
                                   
                                }
                           


            if(is_null($data[$i]->praclocname)){
              $arrydata[]='';
              }else{
                $arrydata[]=$data[$i]->praclocname; 
              }
              
              $arrydata[]=$data[$i]->ptrtotaltime; 

              $patientdetails='';
              if($data[$i]->pprofileimg=='' || $data[$i]->pprofileimg==null)
              {
                $patientdetails="<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' /> ".$data[$i]->pfname;
              }
              else
              {
                  $patientdetails="<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' /> ".$data[$i]->pfname;  
              }

            
               $ddata['DATA'][]=$arrydata;
            }//end for loop
             
             $dynamicheader=array();
             $columnheader=array("Provider Name","EMR","First Name","Last Name","Patient Fin Number","DOB","Number of days of readings","Days of the month of readings","Number of unique readings","Billing threshold # days","Billing threshold met","Date of Service","CPT Code","Number of units","Status","Assigned Care Manager","Device Code","Device Education Date");
                for($m=0;$m<count($columnheader);$m++)
             { 
              $dynamicheader[]=array("title"=>$columnheader[$m]);  
             }
             $add = $total_diag; //added by priya onn 24th feb 22
              for($k=1;$k<=$add;$k++) 
             {  
                $varheader="Diagnosis ".$k; 
                $varheader1= "DiagnosisCondition ".$k;
                $DiagnosisConditionheader = array("title"=>$varheader1); 
                $dynamicheader[]=array("title"=>$varheader);
                array_push($dynamicheader, $DiagnosisConditionheader); 

             } 
             $columnheader1=array("Practice","Minutes Spent");
             for($m1=0;$m1<count($columnheader1);$m1++) 
             {
              $dynamicheader[]=array("title"=>$columnheader1[$m1]);
             }

            $fdata['COLUMNS']=$dynamicheader;
    
            $finldata=array_merge($fdata,$ddata);
          
             return json_encode($finldata);
                            
  }


}
//         $data = DB::select( DB::raw($query) );
//         return Datatables::of($data)   
//         ->addIndexColumn()            
//         ->make(true);   
   
//     }

    
// }