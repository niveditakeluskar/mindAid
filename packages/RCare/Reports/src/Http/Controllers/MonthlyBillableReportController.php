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
use Inertia\Inertia;

class MonthlyBillableReportController extends Controller
{

  public function PatientMonthlyBillingReport(Request $request)
  {
    $monthly = date('Y-m');
    $year = date('Y', strtotime($monthly));
    $month = date('m', strtotime($monthly));

    $diagnosis = "select max(count) from (select uid,count(*) as count from patients.patient_diagnosis_codes where EXTRACT(Month from created_at) = '$month' and EXTRACT(year from created_at) = $year group by uid) x";
    $diagnosis = DB::select($diagnosis);
    //dd($diagnosis);
    return view('Reports::monthly-biling-report.patients-monthly-billing-report');
  }

  public function PatientMonthlyBillingReports(Request $request)
  {
    $monthly = date('Y-m');
    $year = date('Y', strtotime($monthly));
    $month = date('m', strtotime($monthly));

    $diagnosis = "select max(count) from (select uid,count(*) as count from patients.patient_diagnosis_codes where EXTRACT(Month from created_at) = '$month' and EXTRACT(year from created_at) = $year group by uid) x";
    $diagnosis = DB::select($diagnosis);
  return Inertia::render('Report/MonthlyBilling', [
    'diagnosis' => $diagnosis,
]);

  }



  public function TotalBillablePatientsSearch(Request $request)
  {

    $practices = sanitizeVariable($request->route('practice'));
    $p;

    if ($practices == 'null' || $practices == '') {
      $p = "null";
    } else if ($practices == '0') {
      $p = 0;
    } else {
      $p = $practices;
    }


    $query =  "select * from patients.sp_monthly_billing_patients($p, null, 3, null,null, null) sp
            left join (select distinct patient_id,count(*)as total,count(quali) as qualified,
            count(disquali) as disquali from (select distinct pd.patient_id ,pd.code,r.qualified as quali,
            dr.qualified as disquali
            from patients.patient_diagnosis_codes pd 
            left join ren_core.diagnosis_codes r on r.code = pd.code and  r.diagnosis_id  = pd.diagnosis and r.qualified = 1 and r.status =1
            left join ren_core.diagnosis_codes dr on dr.code = pd.code and  dr.diagnosis_id  = pd.diagnosis and dr.qualified = 0 and dr.status =1
            left join patients.patient_providers pp on pp.patient_id =pd.patient_id
            left join ren_core.providers pr on pp.provider_id=pr.id 
            left join ren_core.practices prac on pp.practice_id = prac.id
            inner join patients.patient_services ps on pd.patient_id=ps.patient_id
            where pd.status =1 
            and pp.provider_type_id = 1 and pp.is_active =1  and ps.module_id =3
           
            ";



    if ($practices != "" && $practices != 'null') {
      $query .= " and pp.practice_id =" . $p;
    }

    $query .= " group by pd.patient_id,pd.code,r.qualified,dr.qualified ) x group  by x.patient_id) y on y.patient_id=sp.pid";


    $data = DB::select($query);
    return Datatables::of($data)
      ->addIndexColumn()
      ->make(true);
  }


  public function TotalNonBillablePatientSearch(Request $request)
  {
    $practices = sanitizeVariable($request->route('practice'));
    $p;

    if ($practices == 'null' || $practices == '') {
      $p = "null";
    } else if ($practices == '0') {
      $p = 0;
    } else {
      $p = $practices;
    }


    $query =  "select p.id,p.fname as pfname,p.lname as plname,p.mname as  pmname,p.profile_img as pprofileimg, p.email,
        pp.practice_emr as pracpracticeemr,p.dob as pdob,pr.name as prprovidername,prac.name as pracpracticename
        from patients.patient p  
        
        inner join patients.patient_services ps on p.id=ps.patient_id
        
        left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
        from patients.patient_providers pp1
        inner join (select patient_id, max(id) as max_pat_practice  
        from patients.patient_providers  where provider_type_id = 1 and is_active =1
        group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice
        and pp1.provider_type_id = 1 ) pp                 
        on ps.patient_id = pp.patient_id 
    
        left join ren_core.providers pr on pp.provider_id=pr.id 
        left join ren_core.practices prac on pp.practice_id = prac.id 
    
        where
         
        p.id not in (select pid  from patients.sp_monthly_billing_patients(null, null, 3, null,null, null))
        
         ";








    if ($practices != "" && $practices != 'null') {
      $query .= " and pp.practice_id =" . $p;
    }



    $data = DB::select($query);
    return Datatables::of($data)
      ->addIndexColumn()
      ->make(true);
  }

  public function TotalBillablePatientRPMSearch(Request $request)
  {

    $practices = sanitizeVariable($request->route('practice'));
    $p;

    if ($practices == 'null' || $practices == '') {
      $p = "null";
    } else if ($practices == '0') {
      $p = 0;
    } else {
      $p = $practices;
    }


    $query = "select * from patients.sp_monthly_billing_patients($p,null,2,null,null,null) sp
  left join (select distinct patient_id,count(*)as total,count(quali) as qualified,
  count(disquali) as disquali from (select distinct pd.patient_id ,pd.code,r.qualified as quali,
  dr.qualified as disquali
  from patients.patient_diagnosis_codes pd 
  left join ren_core.diagnosis_codes r on r.code = pd.code and  r.diagnosis_id  = pd.diagnosis and r.qualified = 1 and r.status =1
  left join ren_core.diagnosis_codes dr on dr.code = pd.code and  dr.diagnosis_id  = pd.diagnosis and dr.qualified = 0 and dr.status =1
  left join patients.patient_providers pp on pp.patient_id =pd.patient_id
  left join ren_core.providers pr on pp.provider_id=pr.id 
  left join ren_core.practices prac on pp.practice_id = prac.id
  inner join patients.patient_services ps on pd.patient_id=ps.patient_id
  where pd.status =1 
  and pp.provider_type_id = 1 and pp.is_active =1  and ps.module_id =2 ";










    if ($practices != "" && $practices != 'null') {
      $query .= " and pp.practice_id =" . $p;
    }
    $query .= " group by pd.patient_id,pd.code,r.qualified,dr.qualified ) x group  by x.patient_id) y on y.patient_id=sp.pid";


    $data = DB::select($query);
    return Datatables::of($data)
      ->addIndexColumn()
      ->make(true);
  }



  //created by ->ashwini(14march22) for patient summary in report
  public function getPatientData(Request $request)
  {

    // end
    $practicesgrp = "null";
    $practices = "null";
    $provider = "null";
    $module_id = "null";
    $monthly   = "null";
    $monthlyto   = "null";
    $activedeactivestatus = "null";
    $callstatus = "null";

    if ($module_id == 'null') {
      $module_id = 3;
    }
    if ($monthly == '' || $monthly == 'null' || $monthly == '0') {
      $monthly = date('Y-m');
    } else {
      $monthly = $monthly;
    }
    if ($monthlyto == '' || $monthlyto == 'null' || $monthlyto == '0') {
      $monthlyto = date('Y-m-d');
    } else {
      $monthlyto = $monthlyto;
    }

    $year = date('Y', strtotime($monthly));
    $month = date('m', strtotime($monthly));

    $toyear = date('Y', strtotime($monthlyto));
    $tomonth = date('m', strtotime($monthlyto));


    $fromdate = $year . '-' . $month . '-01';
    $to_date = $toyear . '-' . $tomonth . '-01';
    $convertdate = strtotime('-1 second', strtotime('+1 month', strtotime($to_date)));
    $todate = date('Y-m-d', $convertdate);


    $fdt = $fromdate . " " . "00:00:00";
    $tdt = $todate . " " . "23:59:59";

    $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fdt);
    $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($tdt);

    // dd($practices,$provider,$module_id,$dt1,$dt2,$fromdate,$todate,$activedeactivestatus,$callstatus);  

    $querytotalcount = "select * from patients.active_patient_count()";
    $totalPatient = DB::select($querytotalcount);

    $querytotalbillablepatientsrpm = "select count(*) from patients.sp_monthly_billing_patients(null,null,2,null,null,null) sp
                                    left join (select distinct patient_id,count(*)as total,count(quali) as qualified,
                                    count(disquali) as disquali from (select distinct pd.patient_id ,pd.code,r.qualified as quali,
                                    dr.qualified as disquali
                                    from patients.patient_diagnosis_codes pd 
                                    left join ren_core.diagnosis_codes r on r.code = pd.code and  r.diagnosis_id  = pd.diagnosis and r.qualified = 1 and r.status =1
                                    left join ren_core.diagnosis_codes dr on dr.code = pd.code and  dr.diagnosis_id  = pd.diagnosis and dr.qualified = 0 and dr.status =1
                                    left join patients.patient_providers pp on pp.patient_id =pd.patient_id
                                    left join ren_core.providers pr on pp.provider_id=pr.id 
                                    left join ren_core.practices prac on pp.practice_id = prac.id
                                    inner join patients.patient_services ps on pd.patient_id=ps.patient_id
                                    where pd.status =1 
                                    and pp.provider_type_id = 1 and pp.is_active =1  and ps.module_id =2 ";

    $querytotalbillablepatientsrpm .= " group by pd.patient_id,pd.code,r.qualified,dr.qualified ) x group  by x.patient_id) y on y.patient_id=sp.pid";



    $querytotalbillablepatients =    "select count(*) from patients.sp_monthly_billing_patients(null, null, 3, null,null, null) sp
                                    left join (select distinct patient_id,count(*)as total,count(quali) as qualified,
                                    count(disquali) as disquali from (select distinct pd.patient_id ,pd.code,r.qualified as quali,
                                    dr.qualified as disquali
                                    from patients.patient_diagnosis_codes pd 
                                    left join ren_core.diagnosis_codes r on r.code = pd.code and  r.diagnosis_id  = pd.diagnosis and r.qualified = 1 and r.status =1
                                    left join ren_core.diagnosis_codes dr on dr.code = pd.code and  dr.diagnosis_id  = pd.diagnosis and dr.qualified = 0 and dr.status =1
                                    left join patients.patient_providers pp on pp.patient_id =pd.patient_id
                                    left join ren_core.providers pr on pp.provider_id=pr.id 
                                    left join ren_core.practices prac on pp.practice_id = prac.id
                                    inner join patients.patient_services ps on pd.patient_id=ps.patient_id
                                    where pd.status =1 
                                    and pp.provider_type_id = 1 and pp.is_active =1  and ps.module_id =3 ";

    $querytotalbillablepatients .= " group by pd.patient_id,pd.code,r.qualified,dr.qualified ) x group  by x.patient_id) y on y.patient_id=sp.pid";


    $querytotalbillablepatientscount = DB::select($querytotalbillablepatients);

    $querytotalbillablepatientsrpmcount = DB::select($querytotalbillablepatientsrpm);



    $queryEnrolledActiveCcm = "select * from patients.sp_enrolled_in_ccm_active_detailscount()";
    $totalActiveCCMPatient = DB::select($queryEnrolledActiveCcm);


    $queryEnrolledActiveRpm = "select * from patients.sp_enrolled_in_rpm_active_detailscount()";
    $totalActiveRPMPatient = DB::select($queryEnrolledActiveRpm);

    $data = array(
      'Totalpatient' => $totalPatient,
      'TotalbillablePatient' => $querytotalbillablepatientscount,
      'TotalEnrolledActiveCcm' => $totalActiveCCMPatient,
      'TotalBillableRpm' => $querytotalbillablepatientsrpmcount,
      'TotalEnrolledActiveRPM' => $totalActiveRPMPatient,


    );

    return $data;
  }





  public function MonthlyBilllingReportPatientsSearch(Request $request)
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
    if ($module_id == 'null') {
      $module_id = 3;
    }
    if ($monthly == '' || $monthly == 'null' || $monthly == '0') {
      $monthly = date('Y-m');
    } else {
      $monthly = $monthly;
    }
    if ($monthlyto == '' || $monthlyto == 'null' || $monthlyto == '0') {
      $monthlyto = date('Y-m-d');
    } else {
      $monthlyto = $monthlyto;
    }

    $year = date('Y', strtotime($monthly));
    $month = date('m', strtotime($monthly));

    $toyear = date('Y', strtotime($monthlyto));
    $tomonth = date('m', strtotime($monthlyto));

    $fromdate = $year . '-' . $month . '-01';
    $to_date = $toyear . '-' . $tomonth . '-01';
    $convertdate = strtotime('-1 second', strtotime('+1 month', strtotime($to_date)));
    $todate = date('Y-m-d', $convertdate);

    $fdt = $fromdate . " " . "00:00:00";
    $tdt = $todate . " " . "23:59:59";

    $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fdt);
    $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($tdt);

    $query = "select * from patients.SP_MONTHLY_BILLING_REPORT($practices,$provider,$module_id,timestamp '" . $dt1 . "',timestamp '" . $dt2 . "',$practicesgrp,'" . $fromdate . "','" . $todate . "',$activedeactivestatus,$callstatus,'UTC', 'America/Chicago') sp
               left join (select distinct patient_id,count(*)as total,count(quali) as qualified,
                count(disquali) as disquali from (select distinct pd.patient_id ,pd.code,r.qualified as quali,dr.qualified as disquali
        from patients.patient_diagnosis_codes pd 
         left join ren_core.diagnosis_codes r on r.code = pd.code and  r.diagnosis_id  = pd.diagnosis and r.qualified = 1 and r.status =1
         left join ren_core.diagnosis_codes dr on dr.code = pd.code and  dr.diagnosis_id  = pd.diagnosis and dr.qualified = 0 and dr.status =1
         left join patients.patient_providers pp on pp.patient_id =pd.patient_id
         left join ren_core.providers pr on pp.provider_id=pr.id 
         left join ren_core.practices prac on pp.practice_id = prac.id
         inner join patients.patient_services ps on pd.patient_id=ps.patient_id
         where pd.created_at between '" . $dt1 . "'and '" . $dt2 . "' and pd.status =1 and pp.provider_type_id = 1 and pp.is_active =1
         ";

    if ($practices != "" && $practices != 'null') {
      $query .= " and pp.practice_id =" . $practices;
    }

    if ($provider != "" && $provider != 'null') {
      $query .= " and pp.provider_id =" . $provider;
    }
    if ($module_id != "" && $module_id != 'null') {
      $query .= " and ps.module_id =" . $module_id;
    }
    if ($practicesgrp != "" && $practicesgrp != 'null') {
      $query .= " and prac.practice_group =" . $practicesgrp;
    }

    //  $query .= "and pd.patient_id in ('1896660271','1264936305','706138193')";

    $query .= " group by pd.patient_id,pd.code,r.qualified,dr.qualified ) x group  by x.patient_id) y on y.patient_id=sp.pid";

    $data = DB::select($query);
    $diagnosis = "select max(maxval.total) as total ,max(maxval.qualified) as quli,max(maxval.disquali) as nonquli from ($query) maxval";
    $diagnosis = DB::select($diagnosis);
    //  dd($diagnosis);  



    // if($diagnosis[0]->quli<2){
    //   $tot_quali=2;
    // }else{
    //   $tot_quali= $diagnosis[0]->quli;
    // }
    // $total_diag=$tot_quali+$diagnosis[0]->nonquli;

    $total_diag = $diagnosis[0]->total;
    // dd($total_diag = );

    $arrydata = array();
    $ddata = array();
    $columnheader = array();
    $columnheader1 = array();
    $finalheader = array();
    $maxcount = 0;
    $codedata;


    for ($i = 0; $i < count($data); $i++) {
      if ($data[$i]->pracgrpname != "MANA") {
        $headername = "header" . $i;

        $pid = $data[$i]->pid;

        $dcode = $data[$i]->pdcode;

        $splitcode = explode(',', $dcode);


        if (is_null($data[$i]->prprovidername)) {
          $data[$i]->prprovidername = '';
        }
        if (is_null($data[$i]->pppracticeemr)) {
          $data[$i]->pppracticeemr = '';
        }
        if (is_null($data[$i]->pfname)) {
          $data[$i]->pfname = '';
        }
        if (is_null($data[$i]->plname)) {
          $data[$i]->plname = '';
        }
        if (is_null($data[$i]->pfin_number)) {
          $data[$i]->pfin_number = '';
        }
        if (is_null($data[$i]->pdob)) {
          $data[$i]->pdob = '';
        } else {
          $data[$i]->pdob = gmdate("m/d/Y", strtotime($data[$i]->pdob));
        }

        if (is_null($data[$i]->pstatus)) {
          $data[$i]->pstatus = '';
        } else {

          if ($data[$i]->pstatus == '1') {
            $status = 'Active';
          }
          if ($data[$i]->pstatus == '0') {
            $status = 'Suspended';
          }
          if ($data[$i]->pstatus == '2') {
            $status = 'Deactived';
          }
          if ($data[$i]->pstatus == '3') {
            $status = 'Deceased';
          }
        }

        if (is_null($data[$i]->userid)) {
          $assign_cm = '';
        } else {
          $assign_cm = ucwords(strtolower($data[$i]->userfname . ' ' . $data[$i]->userlname));
        }


        if (is_null($data[$i]->ccsrecdate)) {

          $data[$i]->ccsrecdate = '';
        } else {

          $data[$i]->ccsrecdate = date("m/d/Y", strtotime($data[$i]->ccsrecdate));
        }
        if ($data[$i]->billingcode == '000') {
          $data[$i]->billingcode = '';
        }
        $unit = '';
        if (($data[$i]->ptrtotaltime >= '00:20:00') && ($data[$i]->ptrtotaltime < '00:40:00')) {
          $unit = '1';
        }
        if (($data[$i]->ptrtotaltime >= '00:40:00') && ($data[$i]->ptrtotaltime < '00:60:00')) {
          $unit = '1';
        }
        if (($data[$i]->ptrtotaltime >= '00:60:00') && ($data[$i]->ptrtotaltime < '01:30:00')) {
          $unit = '2';
        }
        if ($data[$i]->ptrtotaltime >= '01:30:00') {
          $unit = '1';
        }
        if ($data[$i]->billingcode == '99490') {
          $unit = '1';
        }


        if ($data[$i]->call_conti_status == '000') {
          $data[$i]->call_conti_status = '';
        }

        if (is_null($data[$i]->finalize_cpd)) {
          $finalize_cpd = '';
        } else {
          if ($data[$i]->finalize_cpd == '1') {
            $finalize_cpd = 'Yes';
          }
          if ($data[$i]->finalize_cpd == '0') {
            $finalize_cpd = 'No';
          }
        }
        $arrydata = array($data[$i]->prprovidername, $data[$i]->pppracticeemr, $data[$i]->pfname, $data[$i]->plname, $data[$i]->pfin_number, $data[$i]->pdob, $data[$i]->ccsrecdate, $data[$i]->billingcode, $unit, $status, $assign_cm, $data[$i]->call_conti_status, $finalize_cpd);
        //  $arrydata=array($data[$i]->pfname);            

        $qualified_array = array();
        $nonqualified_array = array();

        dd($splitcode);
        for ($j = 0; $j < $total_diag; $j++) // change 11 to 0 ashwini changes
        {

          if (array_key_exists($j, $splitcode)) //&& array_key_exists($j,$splitcondition)
          {   //chk key availible in $splitcode                    


            $maxcount = $maxcount;

            $iscode = $splitcode[$j];

            if ($iscode == '' || $iscode == null) {
              $spcondition = '';
              $spcode = '';
            } else {
              $splitcondition = explode('|', $iscode);
              $iscondition = $splitcondition[0];   //isme diagnosis condition hai.. eg.Arthritis  ( Osteoarthritis )
              $spcondition = str_replace("'", "''", $iscondition);
              $spcode = '';
              if (isset($splitcondition[1])) {
                $spcode = $splitcondition[1];   //isme diagnosis code hai....eg.M13.10
              }
            }

            $chk = "select pd.code,pd.condition,pd.status,
                                      dc.qualified,dc.status from patients.patient_diagnosis_codes pd
                                      left join ren_core.diagnosis_codes dc
                                      on dc.code = pd.code and dc.diagnosis_id =pd.diagnosis
                                      where dc.qualified =1";

            if ($iscode != "" || $iscode != null) {
              $chk .= " and pd.code = '" . $spcode . "' ";
            }
            if ($iscode != "" || $iscode != null) {
              $chk .= " and pd.condition = '" . $spcondition . "' ";
            }
            $chk .= "and pd.status=1 and dc.status=1";
            // echo "<br>";  print_r($chk);


            if ($chk) {

              if (!empty($splitcode[$j]) && $splitcode[$j] != "") {

                $qualified_array[] = $spcode;                  //$splitcode[$j];// Qualified value                                 
                array_push($qualified_array, $spcondition); //array:1 [▼
                // dd($qualified_array) ;                                           //   0 => "J45.909"
                // ]
                // "Asthma"

              }
            } else {
              if (!empty($splitcode[$j]) && $splitcode[$j] != "") {

                $nonqualified_array[] = $spcode; //$splitcode[$j];// Non-Qualified value store in array
                array_push($nonqualified_array, $spcondition);
                // dd($nonqualified_array) ;  
              }
            }
          } else {
          }
        } //for loop close  
        // print_r($qualified_array); echo "string";
        //  print_r($nonqualified_array);echo "<pre>";

        //  dd($qualified_array,$nonqualified_array) ;
        //die; 
        if ($unit != '' && count($qualified_array) >= 2) { //$data[$i]->finalize_cpd==1 && //column billable yes/no
          $billable = 'Yes';
        } else {
          $billable = 'No';
        }


        $arrydata[] = $billable;
        // dd($arrydata);
        $arrydata[] = count($qualified_array); //column qualify condition
        $get_cnt_quli = count($qualified_array); //get qualified  count 
        // dd($qualified_array,$get_cnt_quli);     

        if ($get_cnt_quli < 2) {
          $minus = 2;
        } else {
          $minus = $get_cnt_quli;
        }

        if ($get_cnt_quli == 0) {
          // dd("if");
          if (count($nonqualified_array) == 0) {
            $get_cnt_nonquli1 = $total_diag;  //10
            $get_cnt_nonquli = $get_cnt_nonquli1 - $minus;  //10-2=8
          } else if (count($nonqualified_array) > 0) {  //9=9-(1+2)
            $get_cnt_nonquli1 = ($total_diag) - count($nonqualified_array); //get count
            $get_cnt_nonquli = $get_cnt_nonquli1 - $minus;
          }
        } else if ($get_cnt_quli > 0) {
          // dd("else if");
          if (count($nonqualified_array) == 0 && $get_cnt_quli < 2) { //9
            $get_cnt_nonquli1 = $total_diag;
            $get_cnt_nonquli = $get_cnt_nonquli1 - $minus;
          } else if (count($nonqualified_array) == 0 && $get_cnt_quli >= 2) { //here = add by priya
            $get_cnt_nonquli1 = $total_diag - $get_cnt_quli;
            $get_cnt_nonquli = $get_cnt_nonquli1;
          } else if (count($nonqualified_array) > 0 && $get_cnt_quli < 2) {  //9=(9-4
            $get_cnt_nonquli1 = $total_diag - count($nonqualified_array); //get count 5-3
            $get_cnt_nonquli = $get_cnt_nonquli1 - $minus;
          } else if (count($nonqualified_array) > 0 && $get_cnt_quli >= 2) { //6-2-5  //here = add by priya
            $get_cnt_nonquli1 = ($total_diag - $get_cnt_quli) - count($nonqualified_array); //get count 8-5
            $get_cnt_nonquli = $get_cnt_nonquli1;
          }
        }

        // dd($get_cnt_nonquli);
        for ($q = 0; $q < $get_cnt_nonquli; $q++) {
          array_push($nonqualified_array, "");
        }
        //  dd($nonqualified_array,$total_diag); 


        //dd($total_diag);
        if ($get_cnt_quli == 0 && count($nonqualified_array) == 0) {
          // dd("if");
          //$add1=($total_diag); //added by priya onn 24th feb 22
          $add1 = ($total_diag * 2);
          $kk = array();
          for ($k = 1; $k <= $add1; $k++) {
            array_push($kk, "");
          }

          $arrydata =  array_merge($arrydata, $kk);
        } else if ($get_cnt_quli == 0 && count($nonqualified_array) > 0) {
          // dd("first else if");
          $k = array();
          for ($x = 1; $x <= ($total_diag + 2); $x++) {
            array_push($k, "");
          }
          // $k= array("",""); //modified by radha
          //  $k= array("","","","","","","","","","","","");   //modified yestrdy by me
          $kk = $nonqualified_array;
          $arrydata = array_merge($arrydata, $k, $kk);
        } else if ($get_cnt_quli >= 2) {
          // dd("second else if");  
          $k = $qualified_array;
          //  dd(count($qualified_array),($total_diag*2); 

          if ((count($qualified_array)) < ($total_diag * 2)) {
            //  dd("inside if");
            $kk = array();
            $remaingcount =  ($total_diag * 2) - (count($qualified_array));
            for ($x = 1; $x <= $remaingcount; $x++) {
              array_push($kk, "");
            }
          } else {
            // dd("inside else");
            $kk = $nonqualified_array;
          }


          //  dd($arrydata,$k,$kk); 

          //  if(count($qualified_array)<=($total_diag*2)){
          //   $arrydata=array_merge($arrydata,$k,$kk,$kkk);
          //  }
          //  else{
          //   $arrydata=array_merge($arrydata,$k,$kk);
          //  }
          $arrydata = array_merge($arrydata, $k, $kk);
        } else if ($get_cnt_quli == 1) {
          // dd("third else if");  
          $k = $qualified_array;
          $kk = array();
          for ($x = 1; $x <= ($total_diag + 1); $x++) {
            array_push($kk, "");
          }
          // $kk=array(""); //modified by radha
          $kkk = $nonqualified_array;
          $arrydata =  array_merge($arrydata, $k, $kk, $kkk);
        } else {
          // dd("fourth else if");
          // $k=array("","");
          $k = array();
          for ($x = 1; $x <= ($total_diag + 1); $x++) {
            array_push($k, "");
          }
          $kk = $nonqualified_array;
          $arrydata =  array_merge($arrydata, $k, $kk);
        }




        if (is_null($data[$i]->pracpracticename)) {
          $arrydata[] = '';
        } else {
          $arrydata[] = $data[$i]->pracpracticename;
        }

        $arrydata[] = $data[$i]->ptrtotaltime;

        $patientdetails = '';
        if ($data[$i]->pprofileimg == '' || $data[$i]->pprofileimg == null) {
          $patientdetails = "<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' /> " . $data[$i]->pfname;
        } else {
          $patientdetails = "<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' /> " . $data[$i]->pfname;
        }
        $ddata['DATA'][] = $arrydata;
      } //end if mana practice
    } //end for loop



    $dynamicheader = array();
    $columnheader = array("Provider", "EMR", "Patient First Name", "Patient Last Name", "Patient Fin Number", "DOB", "DOS", "CPT Code", "Units", "Status", "Assigned Care Manager", "Call Status", "CPD Status", "Billable", "Qualifying Conditions");
    // $columnheader=array("Patient First Name");  


    for ($m = 0; $m < count($columnheader); $m++) {
      $dynamicheader[] = array("title" => $columnheader[$m]);
    }

    $add = $total_diag; //added by priya onn 24th feb 22
    for ($k = 1; $k <= $add; $k++) {
      $varheader = "Diagnosis " . $k;
      $varheader1 = "DiagnosisCondition " . $k;
      $DiagnosisConditionheader = array("title" => $varheader1);
      $dynamicheader[] = array("title" => $varheader);
      array_push($dynamicheader, $DiagnosisConditionheader);
    }
    // dd($dynamicheader);
    $columnheader1 = array("Practice", "Minutes Spent");
    for ($m1 = 0; $m1 < count($columnheader1); $m1++) {
      $dynamicheader[] = array("title" => $columnheader1[$m1]);
    }

    $fdata['COLUMNS'] = $dynamicheader;

    // dd($ddata);

    $finldata = array_merge($fdata, $ddata);

    return json_encode($finldata);
  }


  public function getMonthlyBilllingReportPatientsSearch(Request $request)
  {

    $practicesgrp = sanitizeVariable($request->route('practicesgrpid'));
    $practices = sanitizeVariable($request->route('practiceid'));
    // dd($practices);
    $provider = sanitizeVariable($request->route('providerid'));
    $module_id = sanitizeVariable($request->route('module'));
    $monthly   = sanitizeVariable($request->route('monthly'));
    $activedeactivestatus = sanitizeVariable($request->route('activedeactivestatus'));
    $callstatus = sanitizeVariable($request->route('callstatus'));
    if ($module_id == 'null') {
      $module_id = 3;
    }

    $m = substr($monthly, -1);//dd($m);
    $m = (int)$m;

      $monthly = date('Y-m');
      $year = date('Y', strtotime($monthly));
      $month = date('m', strtotime($monthly));

    $query = "select * from billing.sp_monthly_billing_report_py where month = ".$m." and year = ".$year." ";

    if($practices!="" && $practices !='null'){
      $query .= " and practicesid =".$practices;
    }
   
    if($provider!="" && $provider !='null'){
      $query .= " and provideridz =".$provider;
    }
    if($practicesgrp!="" && $practicesgrp !='null'){
      $query .= " and practicegroupid =".$practicesgrp;
    }
    if($activedeactivestatus!="" && $activedeactivestatus !='null'){
      $query .= " and pstatus =".$activedeactivestatus;
    }
    if($callstatus!="" && $callstatus !='null'){
      $query .= " and callstatusz =".$callstatus;
    }

    if ($module_id != "" && $module_id != 'null') {
     $query .= " and modulesid =" . $module_id;
   }

    $data = DB::select($query);

    return Datatables::of($data) 
    ->addIndexColumn()            
    ->make(true);  
      }

}
