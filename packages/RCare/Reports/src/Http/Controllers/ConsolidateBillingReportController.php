<?php 
namespace RCare\Reports\Http\Controllers;
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
// use RCare\Reports\Http\Requests\MonthlyBilllingReportPatientsSearchRequest;
use DataTables;
use Carbon\Carbon; 
use Session; 

class ConsolidateBillingReportController extends Controller
{   
    public function PatientMonthlyBillingReport(Request $request)
    {
          $monthly = date('Y-m');
          $year = date('Y', strtotime($monthly));
          $month = date('m', strtotime($monthly));
          
          $diagnosis = "select max(count) from (select uid,count(*) as count from patients.patient_diagnosis_codes 
          where EXTRACT(Month from created_at) = '$month' and EXTRACT(year from created_at) = $year group by uid) x";
          $diagnosis = DB::select($diagnosis);    
          //dd($diagnosis);
          return view('Reports::monthly-biling-report.consolidated-monthly-billing-report');
          
    }
    public function ConsolidateMonthlyBilllingReportPatientsSearch(Request $request)
    {   
        $practicesgrp = sanitizeVariable($request->route('practicesgrpid'));  
        $practices = sanitizeVariable($request->route('practiceid'));
        // dd($practices);
        $provider = sanitizeVariable($request->route('providerid'));
        $module_id = sanitizeVariable($request->route('module'));
        $monthly   = sanitizeVariable($request->route('monthly'));
        $monthlyto   = sanitizeVariable($request->route('monthlyto'));
        $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus'));
        $callstatus = sanitizeVariable($request->route('callstatus'));
        if($module_id=='null')
        {
           $module_id=3; 
    
        }
       if($monthly=='' || $monthly=='null' || $monthly=='0')
       {
           $monthly=date('Y-m'); 
       }
       else
       {
            $monthly=$monthly;
       }
       if($monthlyto=='' || $monthlyto=='null' || $monthlyto=='0')
            {
                $monthlyto=date('Y-m-d');
            }
            else
            {
                 $monthlyto=$monthlyto;
            }
    
              $year = date('Y', strtotime($monthly));
              $month = date('m', strtotime($monthly));
    
              $toyear = date('Y', strtotime($monthlyto));
              $tomonth = date('m', strtotime($monthlyto)); 
             
              
                $fromdate=$year.'-'.$month.'-01';
                $to_date=$toyear.'-'.$tomonth.'-01';
                $convertdate = strtotime('-1 second', strtotime('+1 month', strtotime($to_date)));
                $todate=date('Y-m-d', $convertdate);
                
    
                 $fdt =$fromdate." "."00:00:00";   
                 $tdt = $todate ." "."23:59:59"; 
    
                  $dt1 = DatesTimezoneConversion::userToConfigTimeStamp( $fdt); 
                  $dt2 = DatesTimezoneConversion::userToConfigTimeStamp( $tdt);   
                 
                  $configTZ     = config('app.timezone');
                  $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone'); 
                  
                 $query = "select * from patients.sp_consolidate_monthly_billing_report($practices,$provider,$module_id,timestamp '".$dt1."',timestamp '".$dt2."',$practicesgrp,'".$fromdate."','".$todate."',$activedeactivestatus,$callstatus,'".$configTZ."', '".$userTZ."') sp
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
    
                if($practices!="" && $practices !='null'){
                  $query .= " and pp.practice_id =".$practices;
                }
               
                if($provider!="" && $provider !='null'){
                  $query .= " and pp.provider_id =".$provider;
                }
                if($module_id!="" && $module_id !='null'){
                  $query .= " and ps.module_id =".$module_id;
                }
                if($practicesgrp!="" && $practicesgrp !='null'){
                  $query .= " and prac.practice_group =".$practicesgrp;
                }
    
              //  $query .= "and pd.patient_id in ('1896660271','1264936305','706138193')";
    
                    $query .=" group by pd.patient_id,pd.code,r.qualified,dr.qualified ) x group  by x.patient_id) y on y.patient_id=sp.pid";
                    $data = DB::select( DB::raw($query) );
                        //  dd($query);
                        //  dd($data);
                        
                
                    $diagnosis = "select max(maxval.total) as total ,max(maxval.qualified) as quli,max(maxval.disquali) as nonquli from ($query) maxval";
                    $diagnosis = DB::select( DB::raw($diagnosis) );
                    //  dd($diagnosis);  
    
          
         
                  // if($diagnosis[0]->quli<2){
                  //   $tot_quali=2;
                  // }else{
                  //   $tot_quali= $diagnosis[0]->quli;
                  // }
                  // $total_diag=$tot_quali+$diagnosis[0]->nonquli;
    
                  $total_diag = $diagnosis[0]->total;
                  // dd($total_diag = );
          
                  $arrydata=array(); 
                  $ddata=array(); 
                  $columnheader=array();
                  $columnheader1=array();
                  $finalheader=array();
                  $maxcount=0;
                  $codedata; 
    
            
                for($i=0;$i<count($data);$i++){
                    $headername="header".$i;
                    
                        $pid=$data[$i]->pid; 
                    
                        $dcode=$data[$i]->pdcode;
                        
                        $splitcode=explode(',', $dcode);
                        
                        if(is_null($data[$i]->pfin_number)){
                          $data[$i]->pfin_number='';
                        }

                        if(is_null($data[$i]->prprovidername)){
                          $data[$i]->prprovidername='';
                        }
                        if(is_null($data[$i]->pppracticeemr)){
                          $data[$i]->pppracticeemr='';
                        }
                        if(is_null($data[$i]->pfname)){
                          $data[$i]->pfname='';
                        }
                        if(is_null($data[$i]->plname)){
                          $data[$i]->plname='';
                        }if(is_null($data[$i]->pdob)){
                          $data[$i]->pdob='';
                        }else{
                          $data[$i]->pdob = gmdate("m/d/Y", strtotime($data[$i]->pdob));
                        }
                        
                        if(is_null($data[$i]->pstatus)){
                          $data[$i]->pstatus='';
                        }else{
                        
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
                          $assign_cm = ucwords(strtolower($data[$i]->userfname.' '.$data[$i]->userlname));
                        }
                        
    
                        if(is_null($data[$i]->ccsrecdate)){
                    
                          $data[$i]->ccsrecdate='';
                        }else{
                        
                          $data[$i]->ccsrecdate = date("m/d/Y", strtotime($data[$i]->ccsrecdate));
                        }
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
    
                        if($data[$i]->call_conti_status == '000'){
                          $data[$i]->call_conti_status='';
                        }
    
                        if(is_null($data[$i]->finalize_cpd)){
                          $finalize_cpd='';
                        }else{
                          if($data[$i]->finalize_cpd=='1'){
                            $finalize_cpd ='Yes';
                          } 
                          if($data[$i]->finalize_cpd=='0'){
                            $finalize_cpd ='No';
                          }
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

                        $rpm_dos_date=''; 
                        if(is_null($data[$i]->dos)){
                          $rpm_dos_date ='';
                        }else{
                          $data[$i]->dos = date("m/d/Y",strtotime($data[$i]->dos));//gmdate("m/d/Y", strtotime($data[$i]->dtrec_date))
                          $rpm_dos_date = $data[$i]->dos;
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

                        $dev_traing_date=''; 
                        if(is_null($data[$i]->dtrec_date)){
                          $dev_traing_date ='';
                        }else{
                          $data[$i]->dtrec_date = date("m/d/Y",strtotime($data[$i]->dtrec_date));//gmdate("m/d/Y", strtotime($data[$i]->dtrec_date))
                          $dev_traing_date = $data[$i]->dtrec_date;
                        }  
                        
                        if(is_null($data[$i]->enrolled_modules)){ 
                          $enrolled_modules = '';  
                        }else{  
                            if($data[$i]->enrolled_modules=='2,3'){ 
                              $enrolled_modules = 'RPM,CCM';
                            } 
                            if($data[$i]->enrolled_modules=='3,2'){ 
                              $enrolled_modules = 'CCM,RPM';
                            } 
                            if($data[$i]->enrolled_modules=='3'){
                              $enrolled_modules = 'CCM';
                            }
                            if($data[$i]->enrolled_modules=='2'){
                              $enrolled_modules = 'RPM';
                            }
                        }

                      $arrydata=array($data[$i]->prprovidername,$data[$i]->pppracticeemr,
                      ucwords(strtolower($data[$i]->pfname)),ucwords(strtolower($data[$i]->plname)),
                      $data[$i]->pfin_number,$data[$i]->pdob,
                      $enrolled_modules,$data[$i]->ccsrecdate,$billingcode,$unit,$status,$assign_cm,
                      $data[$i]->call_conti_status,$finalize_cpd,$count_distinct_days, 
                      $reading_days,$total_reading_days,$rpm_dos_date,$billing_threshold_days,$reading_exceeds,$dev_traing_date);
                    //  $arrydata=array($data[$i]->pfname);             
                    
                            $qualified_array=array();
                            $nonqualified_array=array();
                            
                          
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
    
                              //  dd($qualified_array,$nonqualified_array) ;
                                //die;   
                                if($unit!='' && count($qualified_array) >=2){//$data[$i]->finalize_cpd==1 && //column billable yes/no
                                  $billable ='Yes'; 
                                }else{  
                                  $billable ='No';  
                                }
    
    
                                $arrydata[] = $billable;
                                // dd($arrydata);
                                $arrydata[] = count($qualified_array); //column qualify condition
                                $get_cnt_quli=count($qualified_array);//get qualified  count 
                                // dd($qualified_array,$get_cnt_quli);     
    
                                if($get_cnt_quli<2){
                                  $minus=2;
                                }else{ 
                                  $minus=$get_cnt_quli;
                                } 
                          
                            if($get_cnt_quli == 0){
                              // dd("if");
                            if( count($nonqualified_array)==0 ){
                              $get_cnt_nonquli1= $total_diag;  //10
                              $get_cnt_nonquli= $get_cnt_nonquli1-$minus;  //10-2=8
                            }
                            else if(count($nonqualified_array) > 0){  //9=9-(1+2)
                            $get_cnt_nonquli1=($total_diag) -count($nonqualified_array) ; //get count
                            $get_cnt_nonquli= $get_cnt_nonquli1-$minus;
                            }
                          }else if($get_cnt_quli>0){
                            // dd("else if");
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
          
                          // dd($get_cnt_nonquli);
                            for($q=0;$q<$get_cnt_nonquli;$q++){
                              array_push($nonqualified_array,"");  
    
                            
                            }
                            //  dd($nonqualified_array,$total_diag); 
                        
                              
                              //dd($total_diag);
                                      if ($get_cnt_quli==0 && count($nonqualified_array)==0) {
                                        // dd("if");
                                        //$add1=($total_diag); //added by priya onn 24th feb 22
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
                                        // dd("second else if");  
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
    
                                      
                                      //  dd($arrydata,$k,$kk); 
                                        
                                      //  if(count($qualified_array)<=($total_diag*2)){
                                      //   $arrydata=array_merge($arrydata,$k,$kk,$kkk);
                                      //  }
                                      //  else{
                                      //   $arrydata=array_merge($arrydata,$k,$kk);
                                      //  }
                                      $arrydata=array_merge($arrydata,$k,$kk);
                                      
                                      }
                                      else if($get_cnt_quli==1){
                                        // dd("third else if");  
                                        $k= $qualified_array;
                                        $kk=array(); 
                                        for($x=1; $x<=($total_diag+1); $x++){
                                          array_push($kk,"");
                                        }
                                        // $kk=array(""); //modified by radha
                                        $kkk=$nonqualified_array;
                                        $arrydata=  array_merge($arrydata,$k,$kk,$kkk);
                                      
                                    
                                      }else{ 
                                        // dd("fourth else if");
                                      // $k=array("","");
                                      $k=array(); 
                                        for($x=1; $x<=($total_diag+1); $x++){
                                          array_push($k,"");
                                        }
                                      $kk=$nonqualified_array;
                                      $arrydata=  array_merge($arrydata,$k,$kk);
                                      
                                      }  
                                  
    
                                
    
                    if(is_null($data[$i]->pracpracticename)){
                    $arrydata[]='';
                    }else{
                      $arrydata[]=$data[$i]->pracpracticename; 
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
                  $columnheader=array("Provider","EMR","Patient First Name","Patient Last Name","Patient FIN Number","DOB","Enrolled Services","CCM(DOS)","CPT Code","Units","Status","Assigned Care Manager","Call Status","CPD Status",
                  "Number of days readings","Days of the month of readings","Number of unique readings","RPM(DOS)","Billing threshold # days",
                  "Billing threshold met","Device Education Date",
                  "Billable","Qualifying Conditions");
                  // $columnheader=array("Patient First Name");  
    
    
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
                // dd($dynamicheader);
                 $columnheader1=array("Practice","Minutes Spent");
                 for($m1=0;$m1<count($columnheader1);$m1++)
                 {
                  $dynamicheader[]=array("title"=>$columnheader1[$m1]);
                 }
    
                $fdata['COLUMNS']=$dynamicheader;
    
                // dd($ddata);
        
                $finldata=array_merge($fdata,$ddata);
              
                 return json_encode($finldata);
                                
    }
}
