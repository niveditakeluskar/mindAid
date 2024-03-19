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

class CallActivityPractiseWiseCountReportController extends Controller
{
    public function CAPWCReportSearch(Request $request)
    {
        // dd($request->all());
        $practices =  sanitizeVariable($request->route('practices'));
        $provider = sanitizeVariable($request->route('provider'));
        $practicesgrp = sanitizeVariable($request->route('practicesgrp'));
        $fromdate11 =  sanitizeVariable($request->route('fromdate1'));
        $todate11 = sanitizeVariable($request->route('todate1'));

        $configTZ     = config('app.timezone');
        $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        $cid = session()->get('userid');
        $userid = $cid;
        $usersdetails = Users::where('id', $cid)->get();
        $roleid = $usersdetails[0]->role;

        if ($fromdate11 == 'null' || $fromdate11 == '') {
            $date = date("Y-m-d");
            $fromdate1 = $date . " " . "00:00:00";
            $todate1 = $date . " " . "23:59:59";
            $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate1);
            $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate1);
        } else {
            $fromdate1 = $fromdate11 . " " . "00:00:00";
            $todate1 = $todate11 . " " . "23:59:59";

            $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate1);
            $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate1);
        }
        $prac;
        $p;
        $pt;

        if ($practicesgrp != 'null') {
            if ($practicesgrp == 0) {
                $prac = 0;
            } else {
                $prac = $practicesgrp;
            }
        } else {
            $prac = 'null';
        }

        if ($practices != 'null') {
            if ($practices == 0) {
                $p = 0;
            } else {
                $p = $practices;
            }
        } else {
            $p = 'null';
        }

        if ($provider != 'null') {
            if ($provider == 0) {
                $pt = 0;
            } else {
                $pt = $provider;
            }
        } else {
            $pt = 'null';
        }

        $query = "select * from patients.call_and_additional_service_report($prac, $p, $pt,'" . $fromdate1 . "', '" . $todate1 . "')";
        // dd($query);
        $data = DB::select($query);

        $a = "select count(*),activity_type from ren_core.activities where timer_type like '4' group by activity_type";
        $activity = DB::select($a);
        //$total_diag = $activity[3]->count;

        $arrydata = array();
        $ddata = array();
        $columnheader = array();
        $columnheader1 = array();
        $finalheader = array();
        $maxcount = 0;
        $codedata;
        // if(count($data) != '' || count($data) != null){ 
        for ($i = 0; $i < count($data); $i++) {  //dd($i);
            $j = $i + 1;
            if ($data[$i]->patient_count != "") {
                $headername = "header" . $i;

                $patient_count_data = '<button type="button" id="patientdetails" onclick="patientdetailsmodal(\'' . $data[$i]->prac_id . '\',\'' . $provider . '\',\'' . $provider . '\',\'' . $fromdate11 . '\',\'' . $todate11 . '\')" class="">' . $data[$i]->patient_count . '</button>';


                $arrydata = array(
                    $j, $patient_count_data, $data[$i]->call_answered, $data[$i]->call_not_answered,
                    $data[$i]->pracname,

                    $data[$i]->no_additional_response,

                    $data[$i]->authorized_response_one, $data[$i]->authorized_response_two,

                    $data[$i]->mailed_response_one, $data[$i]->mailed_response_two, $data[$i]->mailed_response_three,
                    $data[$i]->mailed_response_four, $data[$i]->mailed_response_five,

                    $data[$i]->medication_response_one,
                    $data[$i]->medication_response_two, $data[$i]->medication_response_three,  $data[$i]->medication_response_four,
                    $data[$i]->medication_response_five,

                    $data[$i]->referral_response_one, $data[$i]->referral_response_two,  $data[$i]->referral_response_three,
                    $data[$i]->referral_response_four, $data[$i]->referral_response_five,  $data[$i]->referral_response_six,
                    $data[$i]->referral_response_seven, $data[$i]->referral_response_eight, $data[$i]->referral_response_nine,
                    $data[$i]->referral_response_ten,

                    $data[$i]->resource_response_one, $data[$i]->resource_response_two, $data[$i]->resource_response_three,
                    $data[$i]->resource_response_four, $data[$i]->resource_response_five, $data[$i]->resource_response_six,
                    $data[$i]->resource_response_seven, $data[$i]->resource_response_eight, $data[$i]->resource_response_nine,
                    $data[$i]->resource_response_ten, $data[$i]->resource_response_eleven,

                    $data[$i]->routine_response_one, $data[$i]->routine_response_two,  $data[$i]->routine_response_three,
                    $data[$i]->routine_response_four, $data[$i]->routine_response_five,  $data[$i]->routine_response_six,
                    $data[$i]->routine_response_seven, $data[$i]->routine_response_eight,


                    $data[$i]->urgent_response_one, $data[$i]->urgent_response_two,  $data[$i]->urgent_response_three,
                    $data[$i]->urgent_response_four, $data[$i]->urgent_response_five,  $data[$i]->urgent_response_six,
                    $data[$i]->urgent_response_seven,

                    $data[$i]->verbal_response_one, $data[$i]->verbal_response_two, $data[$i]->verbal_response_three,  $data[$i]->verbal_response_four,

                    $data[$i]->veterans_response_one, $data[$i]->veterans_response_two, $data[$i]->veterans_response_three,
                    $data[$i]->veterans_response_four, $data[$i]->veterans_response_five, $data[$i]->veterans_response_six,
                    $data[$i]->veterans_response_seven, $data[$i]->veterans_response_eight
                );

                $ddata['DATA'][] = $arrydata;
            }
        }

        $dynamicheader = array();
        $columnheader = array(
            "Sr.No.", "Patient Count", "Call Answered", "Call Not Answered", "Practice Name", "No Additional Services Provided",
        );

        for ($m = 0; $m < count($columnheader); $m++) {
            $dynamicheader[] = array("title" => $columnheader[$m]);
        }



        //   $add = $total_diag; 
        //   $type_activity = "select activity from ren_core.activities where activity_type like 'Routine Response' ";
        //   $type_activity = \DB::select($type_aivity); 

        //   for($k=0;$k < count($type_activity);$k++)
        //   { 
        //      $n = $type_activity[$k]->activity;
        //      $varheader= $n;
        //      $dynamicheader[]=array("title"=>$varheader);

        //   }


        /*******trying all for activities ***************/
        $all_activity = "select activity_type,ARRAY_AGG(activity || ',' ) as activity from ren_core.activities where timer_type like '4' group by activity_type order by activity_type ";
        $all_activity = DB::select($all_activity);
        // dd($all_activity);
        foreach ($all_activity as $all) {
            $specificactivity = $all->activity;
            $activitytype = $all->activity_type;
            $str = explode(',",', $specificactivity);

            foreach ($str as $s) {


                $s = str_replace('{"', '', $s);
                $s = str_replace(',"}', '', $s);
                $s = str_replace('"', '', $s);
                $varheader = $activitytype . "-" . $s;
                $dynamicheader[] = array("title" => $varheader);
            }
        }


        $fdata['COLUMNS'] = $dynamicheader;
        $finldata = array_merge($fdata, $ddata);

        return json_encode($finldata);
    }



    public function callActivityServiceListSearch(Request $request)
    {
        // dd($request);
        $patient = sanitizeVariable($request->route('patient'));
        $practices = sanitizeVariable($request->route('practices'));
        $provider = sanitizeVariable($request->route('provider'));
        $practicesgrp = sanitizeVariable($request->route('practicesgrp'));
        $fromdate = sanitizeVariable($request->route('fromdate1'));
        $todate = sanitizeVariable($request->route('todate1'));

        $configTZ     = config('app.timezone');
        $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');

        $pracgrp;
        $p;
        $pr;
        $pro;

        if ($practices != 'null') {
            if ($practices == 0) {
                $pr = 'null';
            } else {
                $pr = $practices;
            }
        } else {
            $pr = 'null';
        }

        if ($practicesgrp != 'null') {
            if ($practicesgrp == 0) {
                $pracgrp = 'null';
            } else {
                $pracgrp = $practicesgrp;
            }
        } else {
            $pracgrp = 'null';
        }

        if ($provider != 'null') {
            if ($provider == 0) {
                $pro = 'null';
            } else {
                $pro = $provider;
            }
        } else {
            $pro = 'null';
        }

        if ($patient != 'null') {
            if ($patient == 0) {
                $p = 'null';
            } else {
                $p = $patient;
            }
        } else {
            $p = 'null';
        }

        if ($fromdate == 'null' || $fromdate == '') {
            $date = date("Y-m-d");
            $year = date('Y', strtotime($date));
            $month = date('m', strtotime($date));
            $fromdate = $year . "-" . $month . "-01 00:00:00";
            $todate = $date . " 23:59:59";

            $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
            $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate);
        } else {
            $fromdate = $fromdate . " 00:00:00";
            $todate = $todate . " 23:59:59";
            $dt1 = DatesTimezoneConversion::userToConfigTimeStamp($fromdate);
            $dt2 = DatesTimezoneConversion::userToConfigTimeStamp($todate);
        }

        // $query = "select pid,pdob,patient_name, practicename, caremanager, 
        // to_char(call_record_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as call_record_date,
        // call_continue_status, emr_entry_completed, schedule_office_appointment,  
        // resources_for_medication,medical_renewal,called_office_patientbehalf,referral_support,no_other_services
        // from patients.sp_call_activity_services_report($pracgrp,$pr,$pro,$p,timestamp '".$dt1."',timestamp '".$dt2."' )";          
        // // dd($query); 
        // $data = DB::select( DB::raw($query) ); 
        // // dd($data);
        // return Datatables::of($data) 
        // ->addIndexColumn()             
        // ->make(true); 

        $query = "select * from patients.child_call_and_additional_service_report($pracgrp,$pr,$pro,$p,'" . $fromdate . "','" . $todate . "' , '" . $configTZ . "','" . $userTZ . "')";
        //    dd($query);  
        $data = \DB::select($query);
        // dd($data);
        $a = "select count(*),activity_type from ren_core.activities where timer_type like '4' group by activity_type";
        $activity = DB::select($a);
        // $total_diag = $activity[3]->count;

        $arrydata = array();
        $ddata = array();
        $columnheader = array();
        $columnheader1 = array();
        $finalheader = array();
        $maxcount = 0;
        $codedata;        // if(count($data) != '' || count($data) != null){ 
        for ($i = 0; $i < (count($data)); $i++) {
            $j = $i + 1;
            if ($data[$i]->pid != "") {

                $headername = "header" . $i;

                $no_additional_response = $data[$i]->no_additional_response;
                if ($no_additional_response == null) {
                    $no_additional_response1 = '';
                }
                if ($no_additional_response == 1) {
                    $no_additional_response1 = 'Yes';
                }
                if ($no_additional_response == 0) {
                    $no_additional_response1 = 'No';
                }
                // if($no_additional_response!='' && $no_additional_response!='NULL' && $no_additional_response!='undefined'){
                //     $no_additional_response1 = $no_additional_response;
                // }


                $authorized_response_one = $data[$i]->authorized_response_one;
                if ($authorized_response_one == null) {
                    $authorized_response_one1 = '';
                }
                if ($authorized_response_one == 1) {
                    $authorized_response_one1 = 'Yes';
                }
                if ($authorized_response_one == 0) {
                    $authorized_response_one1 = 'No';
                }

                $authorized_response_two = $data[$i]->authorized_response_two;
                if ($authorized_response_two == null) {
                    $authorized_response_two1 = '';
                }
                if ($authorized_response_two == 1) {
                    $authorized_response_two1 = 'Yes';
                }
                if ($authorized_response_two == 0) {
                    $authorized_response_two1 = 'No';
                }

                // if($authorized_response_one!='' && $authorized_response_one!='NULL' && $authorized_response_one!='undefined'){
                //     $authorized_response_one1 = $authorized_response_one;
                // }

                $routine_response_one = $data[$i]->routine_response_one;
                if ($routine_response_one == null) {
                    $routine_response_one1 = '';
                }
                if ($routine_response_one == 1) {
                    $routine_response_one1 = 'Yes';
                }
                if ($routine_response_one == 0) {
                    $routine_response_one1 = 'No';
                }
                // if($routine_response_one!='' && $routine_response_one!='NULL' && $routine_response_one!='undefined'){
                //     $routine_response_one1 = $routine_response_one;
                // }

                $routine_response_two = $data[$i]->routine_response_two;
                if ($routine_response_two == null) {
                    $routine_response_two1 = '';
                }
                if ($routine_response_two == 1) {
                    $routine_response_two1 = 'Yes';
                }
                if ($routine_response_two == 0) {
                    $routine_response_two1 = 'No';
                }
                // if($routine_response_two!='' && $routine_response_two!='NULL' && $routine_response_two!='undefined'){
                //     $routine_response_two1 = $routine_response_two;
                // }

                $routine_response_three = $data[$i]->routine_response_three;
                if ($routine_response_three == null) {
                    $routine_response_three1 = '';
                }
                if ($routine_response_three == 1) {
                    $routine_response_three1 = 'Yes';
                }
                if ($routine_response_three == 0) {
                    $routine_response_three1 = 'No';
                }
                // if($routine_response_three!='' && $routine_response_three!='NULL' && $routine_response_three!='undefined'){
                //     $routine_response_three1 = $routine_response_three;
                // }

                $routine_response_four = $data[$i]->routine_response_four;
                if ($routine_response_four == null) {
                    $routine_response_four1 = '';
                }
                if ($routine_response_four == 1) {
                    $routine_response_four1 = 'Yes';
                }
                if ($routine_response_four == 0) {
                    $routine_response_four1 = 'No';
                }
                // if($routine_response_four!='' && $routine_response_four!='NULL' && $routine_response_four!='undefined'){
                //     $routine_response_four1 = $routine_response_four;
                // }

                $routine_response_five = $data[$i]->routine_response_five;
                if ($routine_response_five == null) {
                    $routine_response_five1 = '';
                }
                if ($routine_response_five == 1) {
                    $routine_response_five1 = 'Yes';
                }
                if ($routine_response_five == 0) {
                    $routine_response_five1 = 'No';
                }
                // if($routine_response_five!='' && $routine_response_five!='NULL' && $routine_response_five!='undefined'){
                //     $routine_response_five1 = $routine_response_five;
                // }

                $routine_response_six = $data[$i]->routine_response_six;
                if ($routine_response_six == null) {
                    $routine_response_six1 = '';
                }
                if ($routine_response_six == 1) {
                    $routine_response_six1 = 'Yes';
                }
                if ($routine_response_six == 0) {
                    $routine_response_six1 = 'No';
                }
                // if($routine_response_six!='' && $routine_response_six!='NULL' && $routine_response_six!='undefined'){
                //     $routine_response_six1 = $routine_response_six;
                // }

                $routine_response_seven = $data[$i]->routine_response_seven;
                if ($routine_response_seven == null) {
                    $routine_response_seven1 = '';
                }
                if ($routine_response_seven == 1) {
                    $routine_response_seven1 = 'Yes';
                }
                if ($routine_response_seven == 0) {
                    $routine_response_seven1 = 'No';
                }
                // if($routine_response_seven!='' && $routine_response_seven!='NULL' && $routine_response_seven!='undefined'){
                //     $routine_response_seven1 = $routine_response_seven;
                // }

                $routine_response_eight = $data[$i]->routine_response_eight;
                if ($routine_response_eight == null) {
                    $routine_response_eight1 = '';
                }
                if ($routine_response_eight == 1) {
                    $routine_response_eight1 = 'Yes';
                }
                if ($routine_response_eight == 0) {
                    $routine_response_eight1 = 'No';
                }
                // if($routine_response_eight!='' && $routine_response_eight!='NULL' && $routine_response_eight!='undefined'){
                //     $routine_response_eight1 = $routine_response_eight;
                // }

                $urgent_response_one = $data[$i]->urgent_response_one;
                if ($urgent_response_one == null) {
                    $urgent_response_one1 = '';
                }
                if ($urgent_response_one == 1) {
                    $urgent_response_one1 = 'Yes';
                }
                if ($urgent_response_one == 0) {
                    $urgent_response_one1 = 'No';
                }
                // if($urgent_response_one!='' && $urgent_response_one!='NULL' && $urgent_response_one!='undefined'){
                //     $urgent_response_one1 = $urgent_response_one;
                // }

                $urgent_response_two = $data[$i]->urgent_response_two;
                if ($urgent_response_two == null) {
                    $urgent_response_two1 = '';
                }
                if ($urgent_response_two == 1) {
                    $urgent_response_two1 = 'Yes';
                }
                if ($urgent_response_two == 0) {
                    $urgent_response_two1 = 'No';
                }
                // if($urgent_response_two!='' && $urgent_response_two!='NULL' && $urgent_response_two!='undefined'){
                //     $urgent_response_two1 = $urgent_response_two;
                // }
                $urgent_response_three = $data[$i]->urgent_response_three;
                if ($urgent_response_three == null) {
                    $urgent_response_three1 = '';
                }
                if ($urgent_response_three == 1) {
                    $urgent_response_three1 = 'Yes';
                }
                if ($urgent_response_three == 0) {
                    $urgent_response_three1 = 'No';
                }
                // if($urgent_response_three!='' && $urgent_response_three!='NULL' && $urgent_response_three!='undefined'){
                //     $urgent_response_three1 = $urgent_response_three;
                // }
                $urgent_response_four = $data[$i]->urgent_response_four;
                if ($urgent_response_four == null) {
                    $urgent_response_four1 = '';
                }
                if ($urgent_response_four == 1) {
                    $urgent_response_four1 = 'Yes';
                }
                if ($urgent_response_four == 0) {
                    $urgent_response_four1 = 'No';
                }
                // if($urgent_response_four!='' && $urgent_response_four!='NULL' && $urgent_response_four!='undefined'){
                //     $urgent_response_four1 = $urgent_response_four;
                // }

                $urgent_response_five = $data[$i]->urgent_response_five;
                if ($urgent_response_five == null) {
                    $urgent_response_five1 = '';
                }
                if ($urgent_response_five == 1) {
                    $urgent_response_five1 = 'Yes';
                }
                if ($urgent_response_five == 0) {
                    $urgent_response_five1 = 'No';
                }
                // if($urgent_response_five!='' && $urgent_response_five!='NULL' && $urgent_response_five!='undefined'){
                //     $urgent_response_five1 = $urgent_response_five;
                // }

                $urgent_response_six  = $data[$i]->urgent_response_six;
                if ($urgent_response_six  == null) {
                    $urgent_response_six1 = '';
                }
                if ($urgent_response_six  == 1) {
                    $urgent_response_six1 = 'Yes';
                }
                if ($urgent_response_six  == 0) {
                    $urgent_response_six1 = 'No';
                }
                // if($urgent_response_six !='' && $urgent_response_six !='NULL' && $urgent_response_six !='undefined'){
                //     $urgent_response_six1 = $urgent_response_six ;
                // }

                $urgent_response_seven = $data[$i]->urgent_response_seven;
                if ($urgent_response_seven == null) {
                    $urgent_response_seven1 = '';
                }
                if ($urgent_response_seven == 1) {
                    $urgent_response_seven1 = 'Yes';
                }
                if ($urgent_response_seven == 0) {
                    $urgent_response_seven1 = 'No';
                }
                // if($urgent_response_seven!='' && $urgent_response_seven!='NULL' && $urgent_response_seven!='undefined'){
                //     $urgent_response_seven1 = $urgent_response_seven;
                // }

                $referral_response_one = $data[$i]->referral_response_one;
                if ($referral_response_one == null) {
                    $referral_response_one1 = '';
                }
                if ($referral_response_one == 1) {
                    $referral_response_one1 = 'Yes';
                }
                if ($referral_response_one == 0) {
                    $referral_response_one1 = 'No';
                }
                // if($referral_response_one!='' && $referral_response_one!='NULL' && $referral_response_one!='undefined'){
                //     $referral_response_one1 = $referral_response_one;
                // }

                $referral_response_two = $data[$i]->referral_response_two;
                if ($referral_response_two == null) {
                    $referral_response_two1 = '';
                }
                if ($referral_response_two == 1) {
                    $referral_response_two1 = 'Yes';
                }
                if ($referral_response_two == 0) {
                    $referral_response_two1 = 'No';
                }
                // if($referral_response_two!='' && $referral_response_two!='NULL' && $referral_response_two!='undefined'){
                //     $referral_response_two1 = $referral_response_two;
                // }

                $referral_response_three = $data[$i]->referral_response_three;
                if ($referral_response_three == null) {
                    $referral_response_three1 = '';
                }
                if ($referral_response_three == 1) {
                    $referral_response_three1 = 'Yes';
                }
                if ($referral_response_three == 0) {
                    $referral_response_three1 = 'No';
                }
                // if($referral_response_three!='' && $referral_response_three!='NULL' && $referral_response_three!='undefined'){
                //     $referral_response_three1 = $referral_response_three;
                // }

                $referral_response_four = $data[$i]->referral_response_four;
                if ($referral_response_four == null) {
                    $referral_response_four1 = '';
                }
                if ($referral_response_four == 1) {
                    $referral_response_four1 = 'Yes';
                }
                if ($referral_response_four == 0) {
                    $referral_response_four1 = 'No';
                }
                // if($referral_response_four!='' && $referral_response_four!='NULL' && $referral_response_four!='undefined'){
                //     $referral_response_four1 = $referral_response_four;
                // }

                $referral_response_five = $data[$i]->referral_response_five;
                if ($referral_response_five == null) {
                    $referral_response_five1 = '';
                }
                if ($referral_response_five == 1) {
                    $referral_response_five1 = 'Yes';
                }
                if ($referral_response_five == 0) {
                    $referral_response_five1 = 'No';
                }
                // if($referral_response_five!='' && $referral_response_five!='NULL' && $referral_response_five!='undefined'){
                //     $referral_response_five1 = $referral_response_five;
                // }

                $referral_response_six = $data[$i]->referral_response_six;
                if ($referral_response_six == null) {
                    $referral_response_six1 = '';
                }
                if ($referral_response_six == 1) {
                    $referral_response_six1 = 'Yes';
                }
                if ($referral_response_six == 0) {
                    $referral_response_six1 = 'No';
                }
                // if($referral_response_six!='' && $referral_response_six!='NULL' && $referral_response_six!='undefined'){
                //     $referral_response_six1 = $referral_response_six;
                // }

                $referral_response_seven = $data[$i]->referral_response_seven;
                if ($referral_response_seven == null) {
                    $referral_response_seven1 = '';
                }
                if ($referral_response_seven == 1) {
                    $referral_response_seven1 = 'Yes';
                }
                if ($referral_response_seven == 0) {
                    $referral_response_seven1 = 'No';
                }
                // if($referral_response_seven!='' && $referral_response_seven!='NULL' && $referral_response_seven!='undefined'){
                //     $referral_response_seven1 = $referral_response_seven;
                // }

                $referral_response_eight = $data[$i]->referral_response_eight;
                if ($referral_response_eight == null) {
                    $referral_response_eight1 = '';
                }
                if ($referral_response_eight == 1) {
                    $referral_response_eight1 = 'Yes';
                }
                if ($referral_response_eight == 0) {
                    $referral_response_eight1 = 'No';
                }
                // if($referral_response_eight!='' && $referral_response_eight!='NULL' && $referral_response_eight!='undefined'){
                //     $referral_response_eight1 = $referral_response_eight;
                // }

                $referral_response_nine = $data[$i]->referral_response_nine;
                if ($referral_response_nine == null) {
                    $referral_response_nine1 = '';
                }
                if ($referral_response_nine == 1) {
                    $referral_response_nine1 = 'Yes';
                }
                if ($referral_response_nine == 0) {
                    $referral_response_nine1 = 'No';
                }
                // if($referral_response_nine!='' && $referral_response_nine!='NULL' && $referral_response_nine!='undefined'){
                //     $referral_response_nine1 = $referral_response_nine;
                // }

                $referral_response_ten = $data[$i]->referral_response_ten;
                if ($referral_response_ten == null) {
                    $referral_response_ten1 = '';
                }
                if ($referral_response_ten == 1) {
                    $referral_response_ten1 = 'Yes';
                }
                if ($referral_response_ten == 0) {
                    $referral_response_ten1 = 'No';
                }
                // if($referral_response_ten!='' && $referral_response_ten!='NULL' && $referral_response_ten!='undefined'){
                //     $referral_response_ten1 = $referral_response_ten;
                // }

                $medication_response_one = $data[$i]->medication_response_one;
                if ($medication_response_one == null) {
                    $medication_response_one1 = '';
                }
                if ($medication_response_one == 1) {
                    $medication_response_one1 = 'Yes';
                }
                if ($medication_response_one == 0) {
                    $medication_response_one1 = 'No';
                }
                // if($medication_response_one!='' && $medication_response_one!='NULL' && $medication_response_one!='undefined'){
                //     $medication_response_one1 = $medication_response_one;
                // }

                $medication_response_two = $data[$i]->medication_response_two;
                if ($medication_response_two == null) {
                    $medication_response_two1 = '';
                }
                if ($medication_response_two == 1) {
                    $medication_response_two1 = 'Yes';
                }
                if ($medication_response_two == 0) {
                    $medication_response_two1 = 'No';
                }
                // if($medication_response_two!='' && $medication_response_two!='NULL' && $medication_response_two!='undefined'){
                //     $medication_response_two1 = $medication_response_two;
                // }

                $medication_response_three = $data[$i]->medication_response_three;
                if ($medication_response_three == null) {
                    $medication_response_three1 = '';
                }
                if ($medication_response_three == 1) {
                    $medication_response_three1 = 'Yes';
                }
                if ($medication_response_three == 0) {
                    $medication_response_three1 = 'No';
                }
                // if($medication_response_three!='' && $medication_response_three!='NULL' && $medication_response_three!='undefined'){
                //     $medication_response_three1 = $medication_response_three;
                // }

                $medication_response_four = $data[$i]->medication_response_four;
                if ($medication_response_four == null) {
                    $medication_response_four1 = '';
                }
                if ($medication_response_four == 1) {
                    $medication_response_four1 = 'Yes';
                }
                if ($medication_response_four == 0) {
                    $medication_response_four1 = 'No';
                }
                // if($medication_response_four!='' && $medication_response_four!='NULL' && $medication_response_four!='undefined'){
                //     $medication_response_four1 = $medication_response_four;
                // }

                $medication_response_five = $data[$i]->medication_response_five;
                if ($medication_response_five == null) {
                    $medication_response_five1 = '';
                }
                if ($medication_response_five == 1) {
                    $medication_response_five1 = 'Yes';
                }
                if ($medication_response_five == 0) {
                    $medication_response_five1 = 'No';
                }
                // if($medication_response_five!='' && $medication_response_five!='NULL' && $medication_response_five!='undefined'){
                //     $medication_response_five1 = $medication_response_five;
                // }

                $verbal_response_one = $data[$i]->verbal_response_one;
                if ($verbal_response_one == null) {
                    $verbal_response_one1 = '';
                }
                if ($verbal_response_one == 1) {
                    $verbal_response_one1 = 'Yes';
                }
                if ($verbal_response_one == 0) {
                    $verbal_response_one1 = 'No';
                }
                // if($verbal_response_one!='' && $verbal_response_one!='NULL' && $verbal_response_one!='undefined'){
                //     $verbal_response_one1 = $verbal_response_one;
                // }

                $verbal_response_two = $data[$i]->verbal_response_two;
                if ($verbal_response_two == null) {
                    $verbal_response_two1 = '';
                }
                if ($verbal_response_two == 1) {
                    $verbal_response_two1 = 'Yes';
                }
                if ($verbal_response_two == 0) {
                    $verbal_response_two1 = 'No';
                }
                // if($verbal_response_two!='' && $verbal_response_two!='NULL' && $verbal_response_two!='undefined'){
                //     $verbal_response_two1 = $verbal_response_two;
                // }

                $verbal_response_three = $data[$i]->verbal_response_three;
                if ($verbal_response_three == null) {
                    $verbal_response_three1 = '';
                }
                if ($verbal_response_three == 1) {
                    $verbal_response_three1 = 'Yes';
                }
                if ($verbal_response_three == 0) {
                    $verbal_response_three1 = 'No';
                }
                if ($verbal_response_three != '' && $verbal_response_three != 'NULL' && $verbal_response_three != 'undefined') {
                    $verbal_response_three1 = $verbal_response_three;
                }

                $verbal_response_four = $data[$i]->verbal_response_four;
                if ($verbal_response_four == null) {
                    $verbal_response_four1 = '';
                }
                if ($verbal_response_four == 1) {
                    $verbal_response_four1 = 'Yes';
                }
                if ($verbal_response_four == 0) {
                    $verbal_response_four1 = 'No';
                }
                // if($verbal_response_four!='' && $verbal_response_four!='NULL' && $verbal_response_four!='undefined'){
                //     $verbal_response_four1 = $verbal_response_four;
                // }

                $mailed_response_one = $data[$i]->mailed_response_one;
                if ($mailed_response_one == null) {
                    $mailed_response_one1 = '';
                }
                if ($mailed_response_one == 1) {
                    $mailed_response_one1 = 'Yes';
                }
                if ($mailed_response_one == 0) {
                    $mailed_response_one1 = 'No';
                }
                // if($mailed_response_one!='' && $mailed_response_one!='NULL' && $mailed_response_one!='undefined'){
                //     $mailed_response_one1 = $mailed_response_one;
                // }

                $mailed_response_two = $data[$i]->mailed_response_two;
                if ($mailed_response_two == null) {
                    $mailed_response_two1 = '';
                }
                if ($mailed_response_two == 1) {
                    $mailed_response_two1 = 'Yes';
                }
                if ($mailed_response_two == 0) {
                    $mailed_response_two1 = 'No';
                }
                // if($mailed_response_two!='' && $mailed_response_two!='NULL' && $mailed_response_two!='undefined'){
                //     $mailed_response_two1 = $mailed_response_two;
                // }

                $mailed_response_three = $data[$i]->mailed_response_three;
                if ($mailed_response_three == null) {
                    $mailed_response_three1 = '';
                }
                if ($mailed_response_three == 1) {
                    $mailed_response_three1 = 'Yes';
                }
                if ($mailed_response_three == 0) {
                    $mailed_response_three1 = 'No';
                }
                // if($mailed_response_three!='' && $mailed_response_three!='NULL' && $mailed_response_three!='undefined'){
                //     $mailed_response_three1 = $mailed_response_three;
                // }

                $mailed_response_four = $data[$i]->mailed_response_four;
                if ($mailed_response_four == null) {
                    $mailed_response_four1 = '';
                }
                if ($mailed_response_four == 1) {
                    $mailed_response_four1 = 'Yes';
                }
                if ($mailed_response_four == 0) {
                    $mailed_response_four1 = 'No';
                }
                // if($mailed_response_four!='' && $mailed_response_four!='NULL' && $mailed_response_four!='undefined'){
                //     $mailed_response_four1 = $mailed_response_four;
                // }

                $mailed_response_five = $data[$i]->mailed_response_five;
                if ($mailed_response_five == null) {
                    $mailed_response_five1 = '';
                }
                if ($mailed_response_five == 1) {
                    $mailed_response_five1 = 'Yes';
                }
                if ($mailed_response_five == 0) {
                    $mailed_response_five1 = 'No';
                }
                // if($mailed_response_five!='' && $mailed_response_five!='NULL' && $mailed_response_five!='undefined'){
                //     $mailed_response_five1 = $mailed_response_five;
                // }


                $resource_response_one = $data[$i]->resource_response_one;
                if ($resource_response_one == null) {
                    $resource_response_one1 = '';
                }
                if ($resource_response_one == 1) {
                    $resource_response_one1 = 'Yes';
                }
                if ($resource_response_one == 0) {
                    $resource_response_one1 = 'No';
                }
                // if($resource_response_one!='' && $resource_response_one!='NULL' && $resource_response_one!='undefined'){
                //     $resource_response_one1 = $resource_response_one;
                // }

                $resource_response_two = $data[$i]->resource_response_two;
                if ($resource_response_two == null) {
                    $resource_response_two1 = '';
                }
                if ($resource_response_two == 1) {
                    $resource_response_two1 = 'Yes';
                }
                if ($resource_response_two == 0) {
                    $resource_response_two1 = 'No';
                }
                // if($resource_response_two!='' && $resource_response_two!='NULL' && $resource_response_two!='undefined'){
                //     $resource_response_two1 = $resource_response_two;
                // }

                $resource_response_three = $data[$i]->resource_response_three;
                if ($resource_response_three == null) {
                    $resource_response_three1 = '';
                }
                if ($resource_response_three == 1) {
                    $resource_response_three1 = 'Yes';
                }
                if ($resource_response_three == 0) {
                    $resource_response_three1 = 'No';
                }
                // if($resource_response_three!='' && $resource_response_three!='NULL' && $resource_response_three!='undefined'){
                //     $resource_response_three1 = $resource_response_three;
                // }

                $resource_response_three = $data[$i]->resource_response_three;
                if ($resource_response_three == null) {
                    $resource_response_three1 = '';
                }
                if ($resource_response_three == 1) {
                    $resource_response_three1 = 'Yes';
                }
                if ($resource_response_three == 0) {
                    $resource_response_three1 = 'No';
                }
                // if($resource_response_three!='' && $resource_response_three!='NULL' && $resource_response_three!='undefined'){
                //     $resource_response_three1 = $resource_response_three;
                // }

                $resource_response_four = $data[$i]->resource_response_four;
                if ($resource_response_four == null) {
                    $resource_response_four1 = '';
                }
                if ($resource_response_four == 1) {
                    $resource_response_four1 = 'Yes';
                }
                if ($resource_response_four == 0) {
                    $resource_response_four1 = 'No';
                }
                // if($resource_response_four!='' && $resource_response_four!='NULL' && $resource_response_four!='undefined'){
                //     $resource_response_four1 = $resource_response_four;
                // }

                $resource_response_five = $data[$i]->resource_response_five;
                if ($resource_response_five == null) {
                    $resource_response_five1 = '';
                }
                if ($resource_response_five == 1) {
                    $resource_response_five1 = 'Yes';
                }
                if ($resource_response_five == 0) {
                    $resource_response_five1 = 'No';
                }
                // if($resource_response_five!='' && $resource_response_five!='NULL' && $resource_response_five!='undefined'){
                //     $resource_response_five1 = $resource_response_five;
                // }

                $resource_response_six = $data[$i]->resource_response_six;
                if ($resource_response_six == null) {
                    $resource_response_six1 = '';
                }
                if ($resource_response_six == 1) {
                    $resource_response_six1 = 'Yes';
                }
                if ($resource_response_six == 0) {
                    $resource_response_six1 = 'No';
                }
                // if($resource_response_six!='' && $resource_response_six!='NULL' && $resource_response_six!='undefined'){
                //     $resource_response_six1 = $resource_response_six;
                // }

                $resource_response_seven = $data[$i]->resource_response_seven;
                if ($resource_response_seven == null) {
                    $resource_response_seven1 = '';
                }
                if ($resource_response_seven == 1) {
                    $resource_response_seven1 = 'Yes';
                }
                if ($resource_response_seven == 0) {
                    $resource_response_seven1 = 'No';
                }
                // if($resource_response_seven!='' && $resource_response_seven!='NULL' && $resource_response_seven!='undefined'){
                //     $resource_response_seven1 = $resource_response_seven;
                // }

                $resource_response_eight = $data[$i]->resource_response_eight;
                if ($resource_response_eight == null) {
                    $resource_response_eight1 = '';
                }
                if ($resource_response_eight == 1) {
                    $resource_response_eight1 = 'Yes';
                }
                if ($resource_response_eight == 0) {
                    $resource_response_eight1 = 'No';
                }
                // if($resource_response_eight!='' && $resource_response_eight!='NULL' && $resource_response_eight!='undefined'){
                //     $resource_response_eight1 = $resource_response_eight;
                // }

                $resource_response_nine = $data[$i]->resource_response_nine;
                if ($resource_response_nine == null) {
                    $resource_response_nine1 = '';
                }
                if ($resource_response_nine == 1) {
                    $resource_response_nine1 = 'Yes';
                }
                if ($resource_response_nine == 0) {
                    $resource_response_nine1 = 'No';
                }
                // if($resource_response_nine!='' && $resource_response_nine!='NULL' && $resource_response_nine!='undefined'){
                //     $resource_response_nine1 = $resource_response_nine;
                // }

                $resource_response_ten = $data[$i]->resource_response_ten;
                if ($resource_response_ten == null) {
                    $resource_response_ten1 = '';
                }
                if ($resource_response_ten == 1) {
                    $resource_response_ten1 = 'Yes';
                }
                if ($resource_response_ten == 0) {
                    $resource_response_ten1 = 'No';
                }
                // if($resource_response_ten!='' && $resource_response_ten!='NULL' && $resource_response_ten!='undefined'){
                //     $resource_response_ten1 = $resource_response_ten;
                // }

                $resource_response_eleven = $data[$i]->resource_response_eleven;
                if ($resource_response_eleven == null) {
                    $resource_response_eleven1 = '';
                }
                if ($resource_response_eleven == 1) {
                    $resource_response_eleven1 = 'Yes';
                }
                if ($resource_response_eleven == 0) {
                    $resource_response_eleven1 = 'No';
                }
                // if($resource_response_eleven!='' && $resource_response_eleven!='NULL' && $resource_response_eleven!='undefined'){
                //     $resource_response_eleven1 = $resource_response_eleven;
                // }

                $veterans_response_one = $data[$i]->veterans_response_one;
                if ($veterans_response_one == null) {
                    $veterans_response_one1 = '';
                }
                if ($veterans_response_one == 1) {
                    $veterans_response_one1 = 'Yes';
                }
                if ($veterans_response_one == 0) {
                    $veterans_response_one1 = 'No';
                }
                // if($veterans_response_one!='' && $veterans_response_one!='NULL' && $veterans_response_one!='undefined'){
                //     $veterans_response_one1 = $veterans_response_one;
                // }

                $veterans_response_two = $data[$i]->veterans_response_two;
                if ($veterans_response_two == null) {
                    $veterans_response_two1 = '';
                }
                if ($veterans_response_two == 1) {
                    $veterans_response_two1 = 'Yes';
                }
                if ($veterans_response_two == 0) {
                    $veterans_response_two1 = 'No';
                }
                // if($veterans_response_two!='' && $veterans_response_two!='NULL' && $veterans_response_two!='undefined'){
                //     $veterans_response_two1 = $veterans_response_two;
                // }

                $veterans_response_two = $data[$i]->veterans_response_two;
                if ($veterans_response_two == null) {
                    $veterans_response_two1 = '';
                }
                if ($veterans_response_two == 1) {
                    $veterans_response_two1 = 'Yes';
                }
                if ($veterans_response_two == 0) {
                    $veterans_response_two1 = 'No';
                }
                // if($veterans_response_two!='' && $veterans_response_two!='NULL' && $veterans_response_two!='undefined'){
                //     $veterans_response_two1 = $veterans_response_two;
                // }

                $veterans_response_three = $data[$i]->veterans_response_three;
                if ($veterans_response_three == null) {
                    $veterans_response_three1 = '';
                }
                if ($veterans_response_three == 1) {
                    $veterans_response_three1 = 'Yes';
                }
                if ($veterans_response_three == 0) {
                    $veterans_response_three1 = 'No';
                }
                // if($veterans_response_three!='' && $veterans_response_three!='NULL' && $veterans_response_three!='undefined'){
                //     $veterans_response_three1 = $veterans_response_three;
                // }

                $veterans_response_four = $data[$i]->veterans_response_four;
                if ($veterans_response_four == null) {
                    $veterans_response_four1 = '';
                }
                if ($veterans_response_four == 1) {
                    $veterans_response_four1 = 'Yes';
                }
                if ($veterans_response_four == 0) {
                    $veterans_response_four1 = 'No';
                }
                // if($veterans_response_four!='' && $veterans_response_four!='NULL' && $veterans_response_four!='undefined'){
                //     $veterans_response_four1 = $veterans_response_four;
                // }

                $veterans_response_five = $data[$i]->veterans_response_five;
                if ($veterans_response_five == null) {
                    $veterans_response_five1 = '';
                }
                if ($veterans_response_five == 1) {
                    $veterans_response_five1 = 'Yes';
                }
                if ($veterans_response_five == 0) {
                    $veterans_response_five1 = 'No';
                }
                // if($veterans_response_five!='' && $veterans_response_five!='NULL' && $veterans_response_five!='undefined'){
                //     $veterans_response_five1 = $veterans_response_five;
                // }

                $veterans_response_six = $data[$i]->veterans_response_six;
                if ($veterans_response_six == null) {
                    $veterans_response_six1 = '';
                }
                if ($veterans_response_six == 1) {
                    $veterans_response_six1 = 'Yes';
                }
                if ($veterans_response_six == 0) {
                    $veterans_response_six1 = 'No';
                }
                // if($veterans_response_six!='' && $veterans_response_six!='NULL' && $veterans_response_six!='undefined'){
                //     $veterans_response_six1 = $veterans_response_six;
                // }

                $veterans_response_seven = $data[$i]->veterans_response_seven;
                if ($veterans_response_seven == null) {
                    $veterans_response_seven1 = '';
                }
                if ($veterans_response_seven == 1) {
                    $veterans_response_seven1 = 'Yes';
                }
                if ($veterans_response_seven == 0) {
                    $veterans_response_seven1 = 'No';
                }
                // if($veterans_response_seven!='' && $veterans_response_seven!='NULL' && $veterans_response_seven!='undefined'){
                //     $veterans_response_seven1 = $veterans_response_seven;
                // }

                $veterans_response_eight = $data[$i]->veterans_response_eight;
                if ($veterans_response_eight == null) {
                    $veterans_response_eight1 = '';
                }
                if ($veterans_response_eight == 1) {
                    $veterans_response_eight1 = 'Yes';
                }
                if ($veterans_response_eight == 0) {
                    $veterans_response_eight1 = 'No';
                }
                // if($veterans_response_eight!='' && $veterans_response_eight!='NULL' && $veterans_response_eight!='undefined'){
                //     $veterans_response_eight1 = $veterans_response_eight;
                // }

                $arrydata = array(
                    $j, $data[$i]->pdob, $data[$i]->patient_name, $data[$i]->practicename, $data[$i]->caremanager,
                    $data[$i]->call_record_date, $data[$i]->call_continue_status,

                    $no_additional_response1,

                    $authorized_response_one1, $authorized_response_two1,

                    $mailed_response_one1, $mailed_response_two1, $mailed_response_three1,
                    $mailed_response_four1, $mailed_response_five1,

                    $medication_response_one1,
                    $medication_response_two1, $medication_response_three1,  $medication_response_four1,
                    $medication_response_five1,

                    $referral_response_one1, $referral_response_two1,  $referral_response_three1,
                    $referral_response_four1, $referral_response_five1,  $referral_response_six1,
                    $referral_response_seven1, $referral_response_eight1, $referral_response_nine1,
                    $referral_response_ten1,

                    $resource_response_one1, $resource_response_two1, $resource_response_three1,
                    $resource_response_four1, $resource_response_five1, $resource_response_six1,
                    $resource_response_seven1, $resource_response_eight1, $resource_response_nine1,
                    $resource_response_ten1, $resource_response_eleven1,

                    $routine_response_one1, $routine_response_two1,  $routine_response_three1,
                    $routine_response_four1, $routine_response_five1,  $routine_response_six1,
                    $routine_response_seven1, $routine_response_eight1,


                    $urgent_response_one1, $urgent_response_two1,  $urgent_response_three1,
                    $urgent_response_four1, $urgent_response_five1,  $urgent_response_six1,
                    $urgent_response_seven1,

                    $verbal_response_one1, $verbal_response_two1, $verbal_response_three1,  $verbal_response_four1,

                    $veterans_response_one1, $veterans_response_two1, $veterans_response_three1,
                    $veterans_response_four1, $veterans_response_five1, $veterans_response_six1,
                    $veterans_response_seven1, $veterans_response_eight1
                );

                $ddata['DATA'][] = $arrydata;
            }
        }


        $dynamicheader = array();
        $columnheader = array(
            "Sr.No.", "DOB", "Patient Name", "Practice Name", "Caremanager Name", "Call Record Date", "Call Answered or Not Answered status", "No Additional Services Provided"
        );
        // $columnheader=array("Patient First Name");  


        for ($m = 0; $m < count($columnheader); $m++) {
            $dynamicheader[] = array("title" => $columnheader[$m]);
        }



        //   $add = $total_diag; 
        //   $type_activity = "select activity from ren_core.activities where activity_type like 'Routine Response' ";
        //   $type_activity = \DB::select($type_aivity) ); 

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
        foreach ($all_activity as $all) {
            $specificactivity = $all->activity;
            $activitytype = $all->activity_type;
            $str = explode(',",', $specificactivity);

            foreach ($str as $s) {


                $s = str_replace('{"', '', $s);
                $s = str_replace(',"}', '', $s);
                $s = str_replace('"', '', $s);
                $varheader = $activitytype . "-" . $s;
                $dynamicheader[] = array("title" => $varheader);
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

        $fdata['COLUMNS'] = $dynamicheader;
        // $fdata['COLUMNS2']=$dynamicheader; 
        // $fdata['COLUMNS'][1]=$dynamicheader;  

        // $newdata = array();
        // $newdata = $fdata;

        // $f1data = array_merge($newdata,$fdata);

        // dd($f1data);  

        $finldata = array_merge($fdata, $ddata);
        // $finldata=array_merge($f1data,$ddata);
        // dd($finldata);

        return json_encode($finldata);
    }
}
