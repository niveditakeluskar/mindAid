<?php

namespace RCare\Ccm\Http\Controllers;

use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientAddress;
use RCare\Ccm\Models\CallPreparation;
use RCare\Ccm\Models\CallStatus;
use RCare\Ccm\Models\CallHipaaVerification;
use RCare\Ccm\Models\CallHomeServiceVerification;
use RCare\Ccm\Models\CallClose;
use RCare\Ccm\Models\CallWrap;
use RCare\Ccm\Models\FollowUp;
use RCare\Ccm\Models\TextMsg;
use RCare\Ccm\Models\EmailLog;
use RCare\Ccm\Models\SpMonthlyMonitoringPatientListing;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientThreshold;
use RCare\Patients\Models\PatientDevices;
use RCare\Patients\Models\PatientTimeRecords;
use RCare\System\Traits\DatesTimezoneConversion;

use RCare\Rpm\Models\Devices;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
use RCare\Org\OrgPackages\Medication\src\Models\Medication;
use RCare\Org\OrgPackages\StageCodes\src\Models\StageCode;
use RCare\Ccm\Models\ContentTemplateUsageHistory;
use RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate;
use RCare\Ccm\Models\QuestionnaireTemplatesUsageHistory;
use RCare\Org\OrgPackages\QCTemplates\src\Models\ContentTemplate;
use RCare\Patients\Models\PatientProvider;
use RCare\TaskManagement\Models\ToDoList;
use RCare\Org\OrgPackages\Roles\src\Models\RolesTypes;
use Illuminate\Http\Request;
use RCare\Ccm\src\Http\Requests\PreparationAddRequest;
use RCare\Ccm\src\Http\Requests\PreparationDraftAddRequest;
use RCare\Patients\Models\PatientPartResearchStudy;
use RCare\Patients\Models\PatientPersonalNotes;
use RCare\Patients\Models\PatientMedication;
use RCare\Patients\Models\PatientAllergy;
use RCare\Patients\Models\PatientDiagnosis;
use RCare\Patients\Models\PatientDemographics;
use RCare\Patients\Models\PatientQuestionnaire;
use RCare\Patients\Models\PatientContactTime;
use RCare\Patients\Models\PatientVitalsData;
use RCare\Patients\Models\PatientImaging;
use RCare\Patients\Models\PatientHealthData;
use RCare\Patients\Models\PatientLabRecs;
use RCare\TaskManagement\Models\UserPatients;
use RCare\Patients\Models\PatientHealthServices;
use RCare\Ccm\src\Http\Requests\CallAddRequest;
use RCare\Ccm\src\Http\Requests\HippaAddRequest;
use RCare\Ccm\src\Http\Requests\HomeServicesAddRequest;
use RCare\Ccm\src\Http\Requests\RelationshipAddRequest;
use RCare\Ccm\src\Http\Requests\CallCloseAddRequest;
use RCare\Ccm\src\Http\Requests\CallwrapAddRequest;
use RCare\Ccm\src\Http\Requests\FollowupAddRequest;
use RCare\Ccm\src\Http\Requests\TextAddRequest;
use RCare\Ccm\src\Http\Requests\CarePlanSaveRequest;
use RCare\Messaging\Models\MessageLog;
use RCare\Org\OrgPackages\CarePlanTemplate\src\Models\CarePlanTemplate;
use RCare\Org\OrgPackages\Diagnosis\src\Models\DiagnosisCode;
use RCare\Org\OrgPackages\Activity\src\Models\Activity;
use RCare\Org\OrgPackages\Partner\src\Models\Partner;
use RCare\TaskManagement\Models\PatientActivity;
use RCare\Rpm\Models\Partner_Devices;
use RCare\Patients\Models\VitalsObservationNotes;
use RCare\Ccm\Models\CallWrapupChecklist;
use RCare\Ccm\Models\EmrMonthlySummary;
use Illuminate\Support\Facades\Response;
use View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use DataTables;
use Carbon\Carbon;
use Session;
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;
use Mail;

use Inertia\Inertia;


class CcmController extends Controller
{

    public function callWrapUpActivities()
    {

        $routineresponsedata = \DB::table('ren_core.activities')
            ->where('timer_type', 4)
            // ->where('activity_type','Routine Response')
            ->where('status', 1)
            ->orderBy('sequence', 'asc')
            ->get();

        // dd($routineresponsedata);
        return $routineresponsedata;

        // return view('Ccm::monthly-monitoring.sub-steps.call-sub-steps.call-wrap-up', compact('routineresponsedata'));

    }


    public function testScheduler()
    {
        $data = \DB::table('ren_core.newtestscheduler')->orderBy('id', 'desc')->first();
        $d = $data->dayexe;
        $t = $data->time_of_execution;
        echo $d;
        echo $t;
    }

    public function currentMonthStatus($patient_id, $module_id)
    {
        $patient_id  = sanitizeVariable($patient_id);
        $module_id   = sanitizeVariable($module_id);
        $curr_topics = CallWrap::where('patient_id', $patient_id)
            ->select('topic', 'notes')
            ->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->latest()->get();
        return view('Ccm::current-month-data', compact('curr_topics'));
    }

    // public function previousMonthStatus($patient_id, $module_id, $month, $year)
    // {
    //     $patient_id  = sanitizeVariable($patient_id);
    //     $module_id   = sanitizeVariable($module_id);
    //     $month       = sanitizeVariable($month);
    //     $year        = sanitizeVariable($year);

    //     // dd($month ,$year, date('m'), date('Y') );


    //                     $prev_topics = DB::select("(select topic,notes from ccm.ccm_topics
    //                         where id in (select max(id)
    //                         FROM ccm.ccm_topics
    //                         WHERE patient_id='" . $patient_id . "'
    //                         AND EXTRACT(Month from record_date) = '" . $month . "'
    //                         AND EXTRACT(YEAR from record_date) = '" . $year . "'
    //                         AND topic NOT LIKE 'EMR Monthly Summary%' And topic NOT LIKE 'Summary notes added on%'
    //                         group by topic)
    //                         order by sequence, sub_sequence ASC)
    //                         ");

    //                     if($month == date('m') && $year == date('Y')){


    //                         $emr_table = DB::select("(select topic, notes
    //                         from ccm.ccm_emr_monthly_summary WHERE patient_id='".$patient_id."' And status = 1
    //                         AND EXTRACT(Month from record_date) = '".$month."'
    //                         AND EXTRACT(YEAR from record_date) = '".$year."' order by sequence, sub_sequence ASC)");

    //                         foreach($emr_table as $e){
    //                             if($e!=''){
    //                                 array_push($prev_topics,$e);
    //                             }

    //                         }

    //                         if(count($emr_table)==0){
    //                             $emr_table = DB::select("(select topic,notes from ccm.ccm_topics
    //                             where id in (select max(id)
    //                             FROM ccm.ccm_topics
    //                             WHERE patient_id='" . $patient_id . "'
    //                             AND topic LIKE 'EMR Monthly Summary%'
    //                             AND EXTRACT(Month from record_date) = '" . $month . "'
    //                             AND EXTRACT(YEAR from record_date) = '" . $year . "'
    //                             group by topic)
    //                             order by sequence, sub_sequence ASC)");

    //             foreach ($emr_table as $e) {
    //                 if ($e != '') {
    //                     array_push($prev_topics, $e);
    //                 }
    //             }

    //                             $emr_table_summary_notes =  DB::select("(select topic,notes
    //                             from ccm.ccm_topics
    //                             WHERE patient_id='" . $patient_id . "'
    //                             AND topic LIKE 'Summary notes added on%'
    //                             AND EXTRACT(Month from record_date) = '" . $month . "'
    //                             AND EXTRACT(YEAR from record_date) = '" . $year . "'
    //                             AND status = 1
    //                             order by sequence, sub_sequence ASC)");

    //                             foreach($emr_table_summary_notes as $n){
    //                                 if($n!=''){
    //                                     array_push($prev_topics,$n);
    //                                 }
    //                             }



    //                         }

    //                     }else{
    //                         $emr_table = DB::select("(select topic,notes from ccm.ccm_emr_monthly_summary
    //                                          WHERE patient_id='".$patient_id."'
    //                                          AND topic LIKE 'EMR Monthly Summary%'
    //                                          AND EXTRACT(Month from record_date) = '" . $month . "'
    //                                          AND EXTRACT(YEAR from record_date) = '" . $year . "'
    //                                          And status = 1
    //                                          order by sequence, sub_sequence ASC)");

    //                         if(count($emr_table)==0){
    //                             $emr_table = DB::select("(select topic,notes from ccm.ccm_topics
    //                             where id in (select max(id)
    //                             FROM ccm.ccm_topics
    //                             WHERE patient_id='" . $patient_id . "'
    //                             AND topic LIKE 'EMR Monthly Summary%'
    //                             AND EXTRACT(Month from record_date) = '" . $month . "'
    //                             AND EXTRACT(YEAR from record_date) = '" . $year . "'
    //                             group by topic)
    //                             order by sequence, sub_sequence ASC)");


    //                         $emr_table_summary_notes =  DB::select("(select topic,notes
    //                         from ccm.ccm_emr_monthly_summary
    //                         WHERE patient_id='" . $patient_id . "'
    //                         AND topic LIKE 'Summary notes added on%'
    //                         AND EXTRACT(Month from record_date) = '" . $month . "'
    //                         AND EXTRACT(YEAR from record_date) = '" . $year . "'
    //                         AND status = 1
    //                         order by sequence, sub_sequence ASC)");

    //                         if(count($emr_table_summary_notes)==0){
    //                         $emr_table_summary_notes =  DB::select("(select topic,notes
    //                         from ccm.ccm_topics
    //                         WHERE patient_id='" . $patient_id . "'
    //                         AND topic LIKE 'Summary notes added on%'
    //                         AND EXTRACT(Month from record_date) = '".$month."'
    //                         AND EXTRACT(YEAR from record_date) = '".$year."'
    //                         order by sequence, sub_sequence ASC)");
    //                         }


    //         if (count($emr_table_summary_notes) > 0) {
    //             foreach ($emr_table_summary_notes as $notes) {
    //                 array_push($prev_topics, $notes);
    //             }
    //         }
    //     }


    //     return view('Ccm::previous-month-data', compact('prev_topics'));
    // }

    public function PatientPreviousMonthCalender($patient_id, $module_id)
    {
        $patient_id   = sanitizeVariable($patient_id);
        $module_id    = sanitizeVariable($module_id);
        $prevcalender = CallWrap::where('patient_id', $patient_id)->orderBy('created_at', 'asc')->first();
        return $prevcalender;
    }

    public function patientRelationshipBuilding($patient_id)
    {
        $patient_id  = sanitizeVariable($patient_id);
        $callp       = CallPreparation::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->orderBy('created_at', 'desc')->first();
        if (isset($callp)) {
            return $callp->patient_relationship_building;
        } else {
            return '';
        }
    }

    public function getDiagnosisAllCodes(Request $request)
    {
        $conditionId = sanitizeVariable($request->conditionId);
        if ($conditionId != '') {
            $conditionCode =  DiagnosisCode::where('diagnosis_id', $conditionId)->WhereNotNull('code')->where("status", 1)->get();
            return $conditionCode;
        } else {
            $conditionCode =  DiagnosisCode::where("status", 1)->WhereNotNull('code')->get();
            return $conditionCode;
        }
    }

    public function patientCarePlanStatus($patient_id, $module_id)
    {
        $uid          = sanitizeVariable($patient_id);
        $module_id    = getPageModuleName();
        $component_id = getPageSubModuleName();
        $subModule    = ""; //this is because some condition in print-pdf- here we are using same blade file
        $configTZ     = config('app.timezone');
        $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        $patient = Patients::with('patientServices', 'patientServices.module')->where('id', $uid)->get();
        $patientDiagnosisDetails    =  PatientDiagnosis::where("patient_id", $uid)
            ->where('status', 1)
            ->whereMonth('updated_at', '>=', date('m'))
            ->whereYear('updated_at', '>=', date('Y'))
            ->orderBy('condition', 'asc')
            ->get(['code', 'condition', 'updated_at as date', 'goals', 'symptoms', 'tasks', 'comments'])
            ->unique('code');
        $PatientDiagnosis  = PatientDiagnosis::where("patient_id", $uid)
            ->where('status', 1)
            ->whereMonth('updated_at', '>=', date('m'))
            ->whereYear('updated_at', '>=', date('Y'))
            ->with('users')
            ->orderBy('condition', 'asc')
            ->get(['code', 'condition', 'updated_at as date']);
        $PatientDiag      = DB::select(
            "select distinct code,condition,jsonb(goals) as goals,jsonb(symptoms) as symptoms ,jsonb(tasks) as tasks,
                                to_char(max(updated_at) at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY HH24:MI:SS')
                                as date
                                from patients.patient_diagnosis_codes
                                WHERE  updated_at >= date_trunc('month', current_date)
                                AND  updated_at >= date_trunc('year', current_date)
                                AND patient_id = '" . $uid . "'
                                AND status = 1
                                group  by code,condition,jsonb(patient_diagnosis_codes.goals),jsonb(patient_diagnosis_codes.symptoms),jsonb(patient_diagnosis_codes.tasks)
                                order by date desc"
        );

        // dd($PatientDiag);
        $patient_cmnt     = PatientDiagnosis::select('comments', 'updated_at', DB::raw("TO_CHAR(MAX(updated_at) AT TIME ZONE '$configTZ' AT TIME ZONE '$userTZ', 'MM-DD-YYYY HH24:MI:SS') as date"))
            // "distinct comments, updated_at, to_char(max(updated_at) at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as date")
            ->where("patient_id", $uid)
            ->where('status', 1)
            ->whereMonth('updated_at', '>=', date('m'))
            ->whereYear('updated_at', '>=', date('Y'))
            ->groupBy('comments', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get(['code', 'condition', 'updated_at as date']);

        $PatientAllergy1  = PatientAllergy::where('patient_id', $uid)
            ->whereMonth('updated_at', '>=', Carbon::now()) //->subMonth()->month
            ->whereYear('updated_at', '>=', Carbon::now())
            ->where('status', '=', 1) //add by priya on 13th may 2021
            ->select(DB::raw('DATE(created_at) AS date'))
            ->distinct()
            ->get()->toArray();
        // foreach($PatientAllergy1 as $key=>$value) {
        //     $d = $value['date'];
        //     $i = 0;
        //     $PatientAllergy = PatientAllergy::where('patient_id', $uid)
        //                     ->where('status',1)
        //                     ->whereDate('created_at','=',$value['date'])
        //                     ->select('DATE(created_at)date','allergy_type','specify','type_of_reactions','severity','course_of_treatment','allergy_status')
        //                     ->get()->toArray();
        //     $PatientAllergy1[$key]['date'] =  $PatientAllergy;
        //     $PatientAllergy1[$key]['displaydate'] = $d;
        //     $i++;
        // }
        foreach ($PatientAllergy1 as $key => $value) {
            $d = $value['date'];

            // Fetch all related data in one query instead of a query inside the loop
            $patientAllergies = PatientAllergy::where('patient_id', $uid)
                ->where('status', 1)
                ->whereDate('created_at', '=', $value['date'])
                ->select(DB::raw('DATE(created_at) AS date'), 'allergy_type', 'specify', 'type_of_reactions', 'severity', 'course_of_treatment', 'allergy_status')
                ->get()
                ->toArray();

            $PatientAllergy1[$key]['date'] = $patientAllergies;
            $PatientAllergy1[$key]['displaydate'] = $d;
        }

        // Now $PatientAllergy1 contains all the related data for each date

        $PatientMedication1     = DB::select("select med_id,pm1.id,pm1.description,purpose,strength,duration,dosage,frequency,route,pharmacy_name,pharmacy_phone_no,
                                        rm.description as name,pm1.updated_at as date
                                        from patients.patient_medication pm1
                                        left join ren_core.medication rm on rm.id = pm1.med_id
                                        where pm1.status = 1 AND pm1.id in (select max(pm.id) from patients.patient_medication pm
                                            where pm.patient_id = '" . $uid . "'
                                            AND pm.created_at >= date_trunc('month', current_date)
                                            AND pm.created_at >= date_trunc('month', current_date)
                                            group by pm.med_id)
                                        order by rm.description asc");
        $last_time_spend        = CommonFunctionController::getCcmNetTime($uid, $module_id);
        $patient_demographics   = PatientDemographics::where('patient_id', $uid)->get();
        $patient_providers      = PatientProvider::where('patient_id', $uid)->with('practice')->with('provider')->with('users')->where('provider_type_id', 1)->where('is_active', 1)->orderby('id', 'desc')->get();
        $patient_providersusers = PatientProvider::where('patient_id', $uid)->where('is_active', 1)->with('users')->get();
        $caremanager            = UserPatients::with('users_created_by')->where('patient_id', $uid)->where('status', 1)->orderby('id', 'desc')->limit(1)->get();
        $medication             = Medication::where("status", 1)->get();
        $patient_vitals         = PatientVitalsData::where('patient_id', $uid)
            ->whereMonth('updated_at', '>=', date('m'))
            ->whereYear('updated_at', '>=', date('Y'))
            ->groupBy('height')
            ->groupBy('weight')
            ->groupBy('bmi')
            ->groupBy('bp')
            ->groupBy('o2')
            ->groupBy('pulse_rate')
            ->groupBy('diastolic')
            ->groupBy('oxygen')
            ->groupBy('notes')
            ->groupBy('pain_level')
            ->groupBy('updated_at')
            ->get(['height', 'weight', 'bmi', 'bp', 'o2', 'pulse_rate', 'diastolic', 'oxygen', 'notes', 'updated_at as date', 'pain_level']);
        $patient_healthdata     = PatientHealthData::where('patient_id', $uid)
            // ->select("distinct health_data, to_char( max(updated_at) at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as updated_at, health_date")//,max(updated_at) as updated_at
            ->select(
                'health_data',
                DB::raw("TO_CHAR(MAX(updated_at) AT TIME ZONE '$configTZ' AT TIME ZONE '$userTZ', 'MM-DD-YYYY HH24:MI:SS') as updated_at"),
                'health_date'
            )
            ->whereMonth('updated_at', '=', date('m'))
            ->whereYear('updated_at', '=', date('Y'))
            ->groupBy('health_data', 'health_date')
            ->orderBy('health_date', 'desc')->get();
        $patient_imaging        = PatientImaging::where('patient_id', $uid)
            // ->select("distinct imaging_details, to_char( max(updated_at) at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as updated_at, imaging_date")//,max(updated_at) as updated_at
            ->select(
                DB::raw("DISTINCT imaging_details"),
                DB::raw("TO_CHAR(MAX(updated_at) AT TIME ZONE '$configTZ' AT TIME ZONE '$userTZ', 'MM-DD-YYYY HH24:MI:SS') as updated_at"),
                'imaging_date'
            )
            ->whereMonth('created_at', '=', date('m'))
            ->whereYear('created_at', '=', date('Y'))
            ->groupBy('imaging_details', 'imaging_date')
            ->orderBy('imaging_date', 'desc')->get();
        // $patient_lab1        = PatientLabRecs::select("distinct to_char( max(created_at) at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as date,
        //                                                         lab_test_id, (case when lab_date is null then rec_date else lab_date end) as lab_date, lab_test_parameter_id, reading, high_val,
        //                                                         (case when lab_date is null then '0' else '1' end) as lab_date_exist, notes")
        //                                         ->where('patient_id', $uid)
        //                                         ->with(['labTest','labsParameters'])
        //                                         ->whereMonth('created_at','=', date('m'))
        //                                         ->whereYear('created_at','=', date('Y'))
        //                                         ->groupBy('lab_test_parameter_id')
        //                                         ->groupBy('reading')
        //                                         ->groupBy('high_val')
        //                                         ->groupBy('lab_test_id')
        //                                         ->groupBy('lab_date')
        //                                         ->groupBy('rec_date')
        //                                         ->groupBy('notes')
        //                                         ->get('id')->toArray();

        $patient_lab1 = PatientLabRecs::select(
            DB::raw("TO_CHAR(MAX(created_at) AT TIME ZONE '$configTZ' AT TIME ZONE '$userTZ', 'MM-DD-YYYY HH24:MI:SS') as date"),
            'lab_test_id',
            DB::raw("(CASE WHEN lab_date IS NULL THEN rec_date ELSE lab_date END) as lab_date"),
            'lab_test_parameter_id',
            'reading',
            'high_val',
            DB::raw("(CASE WHEN lab_date IS NULL THEN '0' ELSE '1' END) as lab_date_exist"),
            'notes'
        )
            ->where('patient_id', $uid)
            ->with(['labTest', 'labsParameters'])
            ->whereMonth('created_at', '=', date('m'))
            ->whereYear('created_at', '=', date('Y'))
            ->groupBy('lab_test_id', 'lab_date', 'rec_date', 'lab_test_parameter_id', 'reading', 'high_val', 'notes')
            ->get()
            ->toArray();

        $patientLabDetails   = [];
        $labInc = 0;
        foreach ($patient_lab1 as $key => $value) {
            $lab_test_id = $value['lab_test_id'];
            $date = strtotime($value['lab_date']);
            $parameter_id = !empty($value['labs_parameters'][0]['id']) ? $value['labs_parameters'][0]['id'] : 0;
            $parameter = !empty($value['labs_parameters'][0]['parameter']) ? $value['labs_parameters'][0]['parameter'] : 0;
            $patientLabDetails[$date][$lab_test_id]['date'] =  $value['date'];
            $patientLabDetails[$date][$lab_test_id]['lab_date'] =  $value['lab_date'];
            $patientLabDetails[$date][$lab_test_id]['lab_date_exist'] =  $value['lab_date_exist'];
            $patientLabDetails[$date][$lab_test_id]['notes'] =  $value['notes'];
            $patientLabDetails[$date][$lab_test_id]['lab_name'] =  $value['lab_test']['description'];
            $patientLabDetails[$date][$lab_test_id]['lab_details'][$parameter]['reading'] =  $value['reading'];
            $patientLabDetails[$date][$lab_test_id]['lab_details'][$parameter]['high_val'] =  $value['high_val'];
            $labInc++;
        }
        $patient_enroll_date = PatientServices::latest_module($uid, $module_id);
        $patient_services    = PatientHealthServices::where("patient_id", $uid)

            ->whereMonth('updated_at', '>=', Carbon::now()) //->subMonth()->month)
            ->whereYear('updated_at', '>=', Carbon::now())
            ->select(DB::raw('DATE(updated_at) AS date'), 'type', 'specify', 'purpose', 'brand', 'frequency', 'service_start_date', 'service_end_date', 'notes')
            ->get();
        $patient_services1   = PatientHealthServices::where("patient_id", $uid)
            ->whereMonth('updated_at', '>=', Carbon::now()) //->subMonth()->month)
            ->whereYear('updated_at', '>=', Carbon::now())
            ->select(DB::raw("to_char(updated_at, 'YYYY-MM-DD') AS dateval"))->distinct()
            ->get();
        // foreach($patient_services1 as $key=>$value) {
        //     $d        = $value->dateval;
        //     $i        = 0;
        //     $patient_services = PatientHealthServices::leftjoin('ren_core.health_services as rhs','patients.patient_healthcare_services.hid', '=', 'rhs.id')
        //                         ->select("DATE(patients.patient_healthcare_services.updated_at) as newdate,
        //                         to_char(patients.patient_healthcare_services.service_start_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY') as service_start_dt,
        //                         to_char(patients.patient_healthcare_services.service_end_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY') as service_end_dt",
        //                         'patients.patient_healthcare_services.type as type', 'patients.patient_healthcare_services.specify', 'patients.patient_healthcare_services.purpose',
        //                         'patients.patient_healthcare_services.brand', 'patients.patient_healthcare_services.frequency', 'patients.patient_healthcare_services.notes')
        //                         ->where("patients.patient_healthcare_services.patient_id",$uid)
        //                         ->where("patients.patient_healthcare_services.status",1)
        //                         ->whereDate('patients.patient_healthcare_services.updated_at','=',$d)
        //                         ->get();
        //     $patient_services1[$key]->dateval = $patient_services;
        //     $patient_services1[$key]->displaydate = $d;
        //     $i++;
        // }
        foreach ($patient_services1 as $key => $value) {
            $d = $value->dateval;
            $i = 0;
            $patient_services = PatientHealthServices::leftjoin('ren_core.health_services as rhs', 'patients.patient_healthcare_services.hid', '=', 'rhs.id')
                ->select(
                    DB::raw('DATE(patients.patient_healthcare_services.updated_at) as newdate'),
                    DB::raw("to_char(patients.patient_healthcare_services.service_start_date at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY') as service_start_dt"),
                    DB::raw("to_char(patients.patient_healthcare_services.service_end_date at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY') as service_end_dt"),
                    'patients.patient_healthcare_services.type as type',
                    'patients.patient_healthcare_services.specify',
                    'patients.patient_healthcare_services.purpose',
                    'patients.patient_healthcare_services.brand',
                    'patients.patient_healthcare_services.frequency',
                    'patients.patient_healthcare_services.notes'
                )
                ->where("patients.patient_healthcare_services.patient_id", $uid)
                ->where("patients.patient_healthcare_services.status", 1)
                ->whereDate('patients.patient_healthcare_services.updated_at', '=', $d)
                ->get();

            $patient_services1[$key]->dateval = $patient_services;
            $patient_services1[$key]->displaydate = $d;
            $i++;
        }

        $services   = Module::where('patients_service', 1)->where('status', 1)->get();
        return view(
            'Ccm::print-care-plan.print-care-plan-pdf-body-problem',
            compact(
                'subModule',
                'patient',
                'patientDiagnosisDetails',
                'PatientDiagnosis',
                'PatientDiag',
                'patient_cmnt',
                'patient_providersusers',
                'caremanager',
                'PatientAllergy1',
                'PatientMedication1',
                'last_time_spend',
                'patient_demographics',
                'patient_providers',
                'medication',
                'patient_enroll_date',
                'services',
                'patient_vitals',
                'patientLabDetails',
                'patient_lab1',
                'patient_services1',
                'patient_healthdata',
                'patient_imaging'
            )
        );
    }

    public function listMonthalyMonitoringPatients(Request $request)
    {
        if ($request->ajax()) {
            $module_id = getPageModuleName();
            $submodule_id = getPageSubModuleName();
            $configTZ = config('app.timezone');
            $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
            $patient_id = !empty(sanitizeVariable($request->route('id'))) ? sanitizeVariable($request->route('id')) : '';
            $data         = DB::select("select id, fname, lname, mname, profile_img, mob, home_number, dob, created_by_user, created_by,
            to_char(last_modified_at at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY HH24:MI:SS') as last_modified_at,
            to_char(last_contact_date at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY HH24:MI:SS') as last_contact_date
             from ccm.patient_listing_search($module_id,$patient_id,'" . $configTZ . "', '" . $userTZ . "')");
            Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="monthly-monitoring/' . $row->id . '" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('Ccm::monthly-monitoring.patient-list');
    }

    public function listMonthalyMonitoringPatientsSearch(Request $request)
    {
        $patient_id = sanitizeVariable($request->route('id'));
        if ($request->ajax()) {
            $module_id    = getPageModuleName();
            $submodule_id = getPageSubModuleName();
            $configTZ     = config('app.timezone');
            $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
            $data         = DB::select("select id, fname, lname, mname, profile_img, mob, home_number, dob, created_by_user, created_by,
            to_char(last_modified_at at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY HH24:MI:SS') as last_modified_at,
            to_char(last_contact_date at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY HH24:MI:SS') as last_contact_date
             from ccm.patient_listing_search($module_id,$patient_id,'" . $configTZ . "', '" . $userTZ . "')");
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('patientName', function ($row) {
                    $PatientService = PatientServices::select("*")
                        ->with('module')
                        ->whereHas('module', function ($q) {
                            $q->where('module', '=', 'RPM'); // '=' is optional
                        })
                        ->where('patient_id', $row->id)
                        ->where('status', 1)
                        ->exists();
                    $avatar = asset('assets/images/faces/avatar.png');
                    if ($PatientService == true) {
                        if ($row->profile_img == '' || $row->profile_img == null) {
                            $btn = "<a href='/rpm/monthly-monitoring/" . $row->id . "' onclick='util.displayLoader()'><img src={$avatar} width='50px' class='user-image' />" . ' ' . $row->fname . ' ' . $row->mname . ' ' . $row->lname . '</a>';
                        } else {
                            $btn = "<a href='/rpm/monthly-monitoring/" . $row->id . "' onclick='util.displayLoader()'><img src='" . $row->profile_img . "' width='40px' height='25px' class='user-image' />" . ' ' . $row->fname . ' ' . $row->mname . ' ' . $row->lname . '</a>';
                        }
                    } else {
                        if ($row->profile_img == '' || $row->profile_img == null) {
                            $btn = "<a href='/ccm/monthly-monitoring/" . $row->id . "' onclick='util.displayLoader()'><img src={$avatar} width='50px' class='user-image' />" . ' ' . $row->fname . ' ' . $row->mname . ' ' . $row->lname . '</a>';
                        } else {
                            $btn = "<a href='/ccm/monthly-monitoring/" . $row->id . "' onclick='util.displayLoader()'><img src='" . $row->profile_img . "' width='40px' height='25px' class='user-image' />" . ' ' . $row->fname . ' ' . $row->mname . ' ' . $row->lname . '</a>';
                        }
                    }
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $PatientService = PatientServices::select("*")
                        ->with('module')
                        ->whereHas('module', function ($q) {
                            $q->where('module', '=', 'RPM'); // '=' is optional
                        })
                        ->where('patient_id', $row->id)
                        ->where('status', 1)
                        ->exists();
                    if ($PatientService == true) {
                        $btn = '<a href="/rpm/monthly-monitoring/' . $row->id . '" onclick="util.displayLoader()" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                    } else {
                        $btn = '<a href="/ccm/monthly-monitoring/' . $row->id . '" onclick="util.displayLoader()" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['patientName', 'action'])
                ->make(true);
        }
        return view('Ccm::monthly-monitoring.patient-list');
    }

    public function listSpMonthlyMonitoringPatientsSearch(Request $request)
    {
        $patient_id = sanitizeVariable($request->route('id'));
        if ($request->ajax()) {
            $module_id    = getPageModuleName();
            $submodule_id = getPageSubModuleName();
            $data = SpMonthlyMonitoringPatientListing::find($module_id, $patient_id);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="/ccm/monthly-monitoring/' . $row->id . '" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function RenderQuestion(Request $request)
    {
        $uid          = 722641701;
        $module_id    = getPageModuleName();
        $component_id = getPageSubModuleName();
        $SID          = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'General Question');
        $decisionTree = QuestionnaireTemplate::where('module_id', $module_id)->where('status', 1)->where('stage_code', getFormStepId($module_id, $component_id, $SID, 'General Question'))->where('template_type_id', 6)->get();
        $genQuestion  = QuestionnaireTemplatesUsageHistory::where('patient_id', $uid)->where('module_id', $module_id)->where('contact_via', 'decisiontree')->get();
        return view('Ccm::monthly-monitoring.question', compact('decisionTree', 'genQuestion'));
    }

    public function saveEnrolleddate(Request $request)
    {
        $patient_id = sanitizeVariable($request->patient_id);
        $module_id    = getPageModuleName();
        $date = sanitizeVariable($request->date_enrolled);
        $userid = session()->get('userid');
        $mytime = Carbon::now();
        $t =  explode(" ", $mytime);
        $currentdate = $t[0];
        $currenttime = $t[1];
        if ($date_enrolled == $currenttime) {
            $date_enrolled = Carbon::now();
        } else {
            $date_enrolled = $date_enrolled . " " . $currenttime;
        }
        $check = PatientServices::where('patient_id', $patient_id)->where('module_id', $module_id)->get();
        if (!is_null($check)) {
            $updata = array('date_enrolled' => $date_enrolled, 'updated_by' => $userid);
            $updatequery =  PatientServices::where('patient_id', $patient_id)->where('module_id', $module_id)->update($updata);
        } else {
            $cdata = array('patient_id' => $patient_id, 'module_id' => $module_id, 'uid' => $patient_id, 'date_enrolled' => $date_enrolled, 'created_by' => $userid);
            $insertquery = PatientServices::create($cdata);
        }
    }

    public function populateEnrolleddate($patient_id)
    {
        $patient_id   = sanitizeVariable($patient_id);
        $module_id    = getPageModuleName();
        $configTZ = config('app.timezone');
        $userTZ   = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        $activitydata =  PatientServices::where('patient_id', $patient_id)->where('module_id', $module_id)->get('date_enrolled');
        $result['enrolleddateform'] = $activitydata;
        return $result;
    }

    public function fetchMonthlyMonitoringPatientDetails(Request $request)
    {
        $patient_id   = sanitizeVariable($request->route('id'));
        // $module_id    = getPageModuleName();
        // $component_id = getPageSubModuleName();
        // $SID = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'General Question');

        // $last_time_spend                = CommonFunctionController::getCcmNetTime($patient_id, $module_id);;
        // //$decisionTree = QuestionnaireTemplate::where('module_id', $module_id )->where('status',1)->where('stage_id',$SID)->where('template_type_id', 6)->orderBy('stage_code', 'ASC')->orderBy('sequence','ASC')->get()->toArray();

        // $enrollinRPM = 1;
        // if (PatientServices::where('patient_id', $patient_id)->where('module_id', 3)->where('status', 1)->exists() && PatientServices::where('patient_id', $patient_id)->where('module_id', 2)->where('status', 1)->exists()) {
        //     $enrollinRPM = 2;
        // }

        // $enrollCount = PatientServices::where('patient_id', $patient_id)->where('status', 1)->count();
        // $ccmModule = Module::where('module', 'CCM')->where('status', 1)->get('id');
        // $ccmModule = (isset($ccmModule) && ($ccmModule->isNotEmpty())) ? $ccmModule[0]->id : 0;
        // $ccmSubModule = ModuleComponents::where('components', "Monthly Monitoring")->where('module_id', $ccmModule)->where('status', 1)->get('id');
        // $ccmSubModule = (isset($ccmSubModule) && ($ccmSubModule->isNotEmpty())) ? $ccmSubModule[0]->id : 0;
        // $ccmSID = getFormStageId($ccmModule, $ccmSubModule, 'General Question');
        // if ($enrollinRPM > 1) {
        //     $decisionTree = QuestionnaireTemplate::where('module_id', $ccmModule)->where('status', 1)->where('stage_id', $ccmSID)->orderBy('stage_code', 'ASC')->orderBy('sequence', 'ASC')->get()->toArray();
        //     $dtsteps = StageCode::where('stage_id', $ccmSID)->get();
        // } else {
        //     $decisionTree = QuestionnaireTemplate::where('module_id', $module_id)->where('status', 1)->where('stage_id', $SID)->orderBy('stage_code', 'ASC')->orderBy('sequence', 'ASC')->get()->toArray();
        //     $dtsteps = StageCode::where('stage_id', $SID)->get();
        // }

        // $stepWiseDecisionTree = [];
        // $i = -1;
        // foreach ($decisionTree as $key => $value) {
        //     if (array_key_exists($value['stage_code'], $stepWiseDecisionTree)) {
        //         $i++;
        //     } else {
        //         $i = 0;
        //     }
        //     $stepWiseDecisionTree[$value['stage_code']][$i] = $value;
        // }
        // if ($enrollinRPM > 1) {
        //     $genQuestion = QuestionnaireTemplatesUsageHistory::where('patient_id', $patient_id)->where('contact_via', 'decisiontree')->where('step_id', 0)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->get();
        // } else {
        //     $genQuestion = QuestionnaireTemplatesUsageHistory::where('patient_id', $patient_id)->where('module_id', $module_id)->where('contact_via', 'decisiontree')->where('step_id', 0)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->get();
        // }
        // $patient             = Patients::where('id', $patient_id)->first();
        // $patient_providers   = PatientProvider::where('patient_id', $patient_id)->with('practice')->with('provider')->with('users')->where('provider_type_id', 1)
        //     ->where('is_active', 1)->orderby('id', 'desc')->first();
        // if ($patient_providers == null || $patient_providers == '' || $patient_providers == " ") {
        //     $billable = null;
        // } else {
        //     $billable            =  $patient_providers->practice['billable'];
        // }
        // $patient_enroll_date = PatientServices::latest_module($patient_id, $module_id);
        // if (PatientServices::where('patient_id', $patient_id)->where('module_id', 2)->exists()) {
        //     $enroll_in_rpm   = 1;
        // } else {
        //     $enroll_in_rpm   = 0;
        // }
        // $PatientDevices = PatientDevices::where('patient_id', $patient_id)->where('status', 1)->orderby('id', 'desc')->get();

        // $devices   = Devices::where('status', '1')->orderby('id', 'asc')->get();
        // $deviceid = 1;
        // $patient_assign_device = "";
        // $patient_assign_deviceid = "";
        // if (!empty($PatientDevices[0])) {
        //     $data = json_decode($PatientDevices[0]->vital_devices);
        //     $show_device = "";
        //     $show_device_id = "";
        //     if (isset($data)) {
        //         foreach ($data as $dev_data) {
        //             $dev =  Devices::where('id', $dev_data->vid)->where('status', '1')->orderby('id', 'asc')->first();
        //             $show_device .= $dev->device_name . ", ";
        //             $show_device_id .= $dev->id . ", ";
        //         }
        //         $patient_assign_device = rtrim($show_device, ', ');
        //         $patient_assign_deviceid = rtrim($show_device_id, ', ');
        //     } else {
        //         $patient_assign_device = "";
        //         $patient_assign_deviceid = "";
        //     }
        // }
        // Inertia::setRootView('Theme::inertia-layouts/master');
        return Inertia::render('MonthlyMonitoring/PatientDetails', [
            'patientId' => $patient_id,
            'moduleId' => 3,
            'componentId' => 19,
        ]);
        // return view(
        //     'Ccm::monthly-monitoring.patient-details',
        //     compact(
        //         'last_time_spend',
        //         'patient_enroll_date',
        //         'enroll_in_rpm',
        //         'billable',
        //         'devices',
        //         'deviceid',
        //         'patient',
        //         'patient_providers',
        //         'genQuestion',
        //         'stepWiseDecisionTree',
        //         'dtsteps',
        //         'PatientDevices',
        //         'patient_assign_device',
        //         'patient_assign_deviceid',
        //         'ccmModule',
        //         'ccmSubModule',
        //         'enrollinRPM',
        //         'enrollCount'
        //     )
        // );

    }
    public function getFollowupTaskListData($patient_id, $module_id)
    {
        $login_user = Session::get('userid');
        $configTZ   = config('app.timezone');
        $userTZ     = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        $patient_id = sanitizeVariable($patient_id);
        $module_id = sanitizeVariable($module_id);
        $query = "select
            todo.id, todo.task_time,todo.status,todo.status_flag,todo.notes,todo.task_completed_at,
            to_char(todo.task_date at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY HH12:MI:SS') as tt,
            todo.module_id, todo.component_id, todo.stage_id, todo.step_id, todo.task_notes, todo.patient_id, todo.created_at,
            todo.enrolled_service_id, m.module, c.components,ft.task,todo.created_by,usr.f_name,
            usr.l_name
            from task_management.to_do_list as todo
            left join patients.patient as patient on patient.id = todo.patient_id
            left join ren_core.modules as m on m.id = todo.module_id
            left join ren_core.module_components as c on c.id = todo.component_id and c.module_id = m.id
            left join ren_core.followup_tasks as ft on ft.id = todo.select_task_category
            left join ren_core.users usr on usr.id=todo.created_by
            where 1=1 and todo.etl_flag = 0 -- and todo.assigned_to = '" . $login_user . "'
             and todo.patient_id = '" . $patient_id . "' --and todo.module_id = '" . $module_id . "'
            and(((todo.status_flag in (0,1,2,3) ) or extract (month FROM todo.task_completed_at) = extract (month FROM CURRENT_DATE)
             and patient_id='" . $patient_id . "' --and todo.assigned_to = '" . $login_user . "'
             )
            or (todo.status_flag in ('0','1','2','3') and patient_id='" . $patient_id . "' --and todo.assigned_to = '" . $login_user . "'
            ) )
            order by todo.status_flag ASC,todo.task_date ASC";

        $data = DB::select($query);
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                if ($row->status_flag == '1') {
                    $btn = '<input class="change_status_flag" name="change_status_flag" data-id="' . $row->id . '" data-module-id="' . $row->module_id . '" data-component-id="' . $row->component_id . '" data-stage-id="' . $row->stage_id . '" data-step-id="' . $row->step_id . '" type="checkbox" value="1" checked>';
                } else {
                    $btn = '<input class="change_status_flag" name="change_status_flag" data-id="' . $row->id . '" data-module-id="' . $row->module_id . '" data-component-id="' . $row->component_id . '" data-stage-id="' . $row->stage_id . '" data-step-id="' . $row->step_id . '" type="checkbox" value="0">';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function SaveFollowupEditData(Request $request)
    {
        $notes = sanitizeVariable($request->notes);
        $get_topic = sanitizeVariable($request->topic);
        $status_flag = sanitizeVariable($request->status_flag);
        $task_date = !empty(sanitizeVariable($request->task_date)) ? sanitizeVariable($request->task_date . '  12:00:00') : null;
        $id = sanitizeVariable($request->id);
        $fetch_topic = ToDoList::where('id', $id)->get();
        $task_date_cw = $fetch_topic[0]->task_date;
        $task_time = $fetch_topic[0]->task_time;
        if (!empty($task_date_cw)) {
            $date = explode(' ', $task_date);
            $t_date = '- Scheduled on -' . $date[0] . ' ' . $task_time;
        } else {
            $t_date = '';
        }
        $assigned_on = $fetch_topic[0]->assigned_on;
        if (!empty($assigned_on)) {
            $date = explode(' ', $assigned_on);
            $a_date = date('m-d-Y', strtotime($date[0]));
        } else {
            $a_date = '';
        }
        $task = $fetch_topic[0]->task_notes;
        $task_id = $fetch_topic[0]->id;
        $patient_id = $fetch_topic[0]->patient_id;
        $mid = $fetch_topic[0]->module_id;
        $cid = $fetch_topic[0]->component_id;
        $task_date_formate_change = date('m-d-Y');
        $sequence              = 7;
        $last_sub_sequence     = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', $sequence)->max('sub_sequence');
        $new_sub_sequence      = $last_sub_sequence + 1;
        $callwrapup_topic = 'Follow Up Task : ' . $task . ' - Created on -' . $a_date . $t_date; //$task_date_formate_change;
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $uid          = sanitizeVariable($request->id);
        $module_id    = $mid;
        $component_id = $cid; //sanitizeVariable(getPageSubModuleName());
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = 0;
        $billable     = 1;
        $form_name    = sanitizeVariable($request->form_name);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            if ($status_flag == 1) {
                $check = CallWrap::where('task_id', $task_id)->exists();
                if ($check == true) {
                    CallWrap::where('task_id', $task_id)->delete();
                }
                $status = 'Completed';
                $task_completed_at = Carbon::now();
                $callWrapUp = array(
                    'uid'                 => $patient_id,
                    'record_date'         => Carbon::now(),
                    'topic'               => $callwrapup_topic,
                    'notes'               => $notes,
                    'created_by'          => session()->get('userid'),
                    'update_by'           => session()->get('userid'),
                    'patient_id'          => $patient_id,
                    'sequence'            => $sequence,
                    'sub_sequence'        => $new_sub_sequence,
                    'task_id'             => $task_id
                );
                CallWrap::create($callWrapUp);
                $todo_data = array(
                    'status_flag' => $status_flag,
                    'status' => $status,
                    'notes' => $notes,
                    'task_date' => $task_date,
                    'task_completed_at' => $task_completed_at,
                    'created_by' => session()->get('userid'),
                    'updated_by' => session()->get('userid')
                );
                ToDoList::where('id', $id)->update($todo_data);
                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name, $form_start_time, $form_save_time);
            } else {
                $check = CallWrap::where('task_id', $task_id)->exists();
                if ($check == true) {
                    CallWrap::where('task_id', $task_id)->delete();
                }
                $status = 'Pending';
                $task_completed_at = null;
                $todo_data = array(
                    'status_flag' => $status_flag,
                    'status' => $status,
                    'notes' => $notes,
                    'task_date' => $task_date,
                    'task_completed_at' => $task_completed_at,
                    'created_by' => session()->get('userid'),
                    'updated_by' => session()->get('userid')
                );
                ToDoList::where('id', $id)->update($todo_data);
                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name, $form_start_time, $form_save_time);
            }
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function changeTodoStatusFlag(Request $request)
    {
        $id = sanitizeVariable($request->id);
        $fetch_topic = ToDoList::where('id', $id)->get();
        $task_date = $fetch_topic[0]->task_date;
        $task_time = $fetch_topic[0]->task_time;
        $task_id = $fetch_topic[0]->id;
        $date = explode(' ', $task_date);
        $task_date_formate_change = date('m-d-Y');
        $task_date = $fetch_topic[0]->task_date;
        if (!empty($task_date)) {
            $date = explode(' ', $task_date);
            $t_date = '- Scheduled on -' . date('m-d-Y', strtotime($date[0])) . ' ' . $task_time;
        } else {
            $t_date = '';
        }
        $assigned_on = $fetch_topic[0]->assigned_on;
        if (!empty($assigned_on)) {
            $date = explode(' ', $assigned_on);
            $a_date = date('m-d-Y', strtotime($date[0]));
        } else {
            $a_date = '';
        }
        $task = $fetch_topic[0]->task_notes;
        $notes = $fetch_topic[0]->notes;
        $patient_id = $fetch_topic[0]->patient_id;
        $mid = $fetch_topic[0]->module_id;
        $change_status_flag = $fetch_topic[0]->status_flag;
        $sequence              = 7;
        $last_sub_sequence     = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', $sequence)->max('sub_sequence');
        $new_sub_sequence      = $last_sub_sequence + 1;
        $callwrapup_topic = 'Follow Up Task : ' . $task . ' - Created on -' . $a_date . $t_date; //
        DB::beginTransaction();
        try {
            if ($change_status_flag == 1) {
                $check = CallWrap::where('task_id', $task_id)->exists();
                if ($check == true) {
                    CallWrap::where('task_id', $task_id)->delete();
                }
                $todo_data = array(
                    'status_flag' => 0,
                    'status' => 'Pending',
                    'task_completed_at' => null,
                    'updated_by' => session()->get('userid')
                );

                ToDoList::where('id', $id)->update($todo_data);
            } else {
                $check = CallWrap::where('task_id', $task_id)->exists();
                if ($check == true) {
                    CallWrap::where('task_id', $task_id)->delete();
                }
                $callWrapUp = array(
                    'uid'                 => $patient_id,
                    'record_date'         => Carbon::now(),
                    'topic'               => $callwrapup_topic,
                    'notes'               => $notes,
                    'created_by'          => session()->get('userid'),
                    'update_by'           => session()->get('userid'),
                    'patient_id'          => $patient_id,
                    'sequence'            => $sequence,
                    'sub_sequence'        => $new_sub_sequence,
                    'task_id'             => $task_id
                );
                CallWrap::create($callWrapUp);
                $todo_data = array(
                    'status_flag' => 1,
                    'status' => 'Completed',
                    'task_completed_at' => Carbon::now(),
                    'updated_by' => session()->get('userid')
                );
                ToDoList::where('id', $id)->update($todo_data);
            }
            $start_time   = sanitizeVariable($request->timer_start);
            $end_time     = sanitizeVariable($request->timer_paused);
            $uid          = sanitizeVariable($request->id);
            $module_id    = sanitizeVariable($request->module_id);
            $component_id = sanitizeVariable($request->component_id); //sanitizeVariable(getPageSubModuleName());
            $stage_id     = sanitizeVariable($request->stage_id); //$request->stage_id
            $step_id      = sanitizeVariable($request->step_id);
            $form_start_time = sanitizeVariable($request->startTime);
            $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
            $billable     = 1;
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, 'follow-up-mark-as-completed-task', $form_start_time, $form_save_time);
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public static function populateFollowupNotes($id, $patientId)
    {
        $patientId          = sanitizeVariable($patientId);
        $id                 = sanitizeVariable($id);

        $check  = ToDoList::with('master_followuptask')->where('patient_id', $patientId)->where('id', $id)->get();
        $result['followup_task_edit_notes'] = $check;
        return $result;
    }

    public function getCallWrapUp($id)
    {
        $id    = sanitizeVariable($id);
        $year  = date('Y');
        $month = date('m');

        // $data  = DB::select(DB::raw( "(select id as \"DT_RowId\", topic, notes,  status, created_at, id, sequence, sub_sequence
        //     from ccm.ccm_topics
        //     where id in (select max(id)
        //     FROM ccm.ccm_topics
        //     WHERE patient_id='".$id."'  And topic NOT LIKE 'EMR Monthly Summary' And topic NOT LIKE 'Summary notes added on%'
        //     And topic NOT LIKE 'Additional Services%'
        //     AND EXTRACT(Month from record_date) = '".$month."' AND EXTRACT(YEAR from record_date) = '".$year."'
        //     group by topic) order by sequence, sub_sequence ASC)
        //     union
        //     (select id as \"DT_RowId\", topic, notes, status, created_at, id, sequence, sub_sequence
        //         from ccm.ccm_emr_monthly_summary WHERE patient_id='".$id."' And status = 1
        //         AND EXTRACT(Month from record_date) = '".$month."' AND EXTRACT(YEAR from record_date) = '".$year."' order by sequence, sub_sequence ASC)


        //     "
        //  )  );

        $data  = DB::select("(select id as \"DT_RowId\", id, topic, ct.notes , sequence , sub_sequence, question_sequence, question_sub_sequence
from ccm.ccm_topics ct
where patient_id = '" . $id . "'  and status = 1
and EXTRACT(Month from record_date) = '" . $month . "' AND EXTRACT(YEAR from record_date) = '" . $year . "'  and id in (select max(id)
        FROM ccm.ccm_topics
        WHERE patient_id='" . $id . "'
        AND EXTRACT(Month from record_date) = '" . $month . "' AND EXTRACT(YEAR from record_date) = '" . $year . "'
        group by topic)
order by sequence , sub_sequence, question_sequence, question_sub_sequence)
");

        /* $data  = DB::select(DB::raw( "(select id as \"DT_RowId\", topic, notes,  status, created_at, id, sequence, sub_sequence
        from ccm.ccm_topics
        where id in (select max(id)
        FROM ccm.ccm_topics
        WHERE patient_id='".$id."'  And topic NOT LIKE 'EMR Monthly Summary%' And topic NOT LIKE 'Summary notes added on%'
        And topic NOT LIKE 'Additional Services%'
        AND EXTRACT(Month from record_date) = '".$month."' AND EXTRACT(YEAR from record_date) = '".$year."'
        group by topic) order by sequence, sub_sequence ASC)
        union
        (select id as \"DT_RowId\", topic, notes, status, created_at, id, sequence, sub_sequence
         from ccm.ccm_topics WHERE patient_id='".$id."' And status = 1 And topic LIKE 'EMR Monthly Summary%'
         AND EXTRACT(Month from record_date) = '".$month."' AND EXTRACT(YEAR from record_date) = '".$year."' order by sequence, sub_sequence ASC)
         union
         (select id as \"DT_RowId\", topic, notes, status, created_at, id, sequence, sub_sequence
         from ccm.ccm_topics WHERE patient_id='".$id."' And status = 1 And topic  LIKE 'Summary notes added on%'
         AND EXTRACT(Month from record_date) = '".$month."' AND EXTRACT(YEAR from record_date) = '".$year."' order by sequence, sub_sequence ASC)
         union
            (select id as \"DT_RowId\", topic, notes,  status, created_at, id, sequence, sub_sequence
            from ccm.ccm_topics
            where id in (select max(id)
            FROM ccm.ccm_topics
            WHERE patient_id='".$id."'
            And topic LIKE 'Additional Services :%' And status = 1
            AND EXTRACT(Month from record_date) = '".$month."' AND EXTRACT(YEAR from record_date) = '".$year."'
            group by topic) order by sequence, sub_sequence ASC)


        "
     )  ); */




        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="delete_callwrap" id="deactive"><i class="i-Closee i-Close"  title="Delete"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function downloadAbleFile($id)
    {
        $id  = sanitizeVariable($id);
        $year  = date('Y');
        $month = date('m');
        $patient = Patients::all();

        // $data  = DB::select(DB::raw("select id as \"DT_RowId\", topic, notes, action_taken, status, created_at, id, sequence, sub_sequence
        //     from ccm.ccm_topics
        //     where id in (select max(id)
        //     FROM ccm.ccm_topics
        //     WHERE patient_id='".$id."'
        //     AND EXTRACT(Month from record_date) = '".$month."' AND EXTRACT(YEAR from record_date) = '".$year."'
        //     group by topic)
        //     order by sequence, sub_sequence ASC
        //     "));

        $data  = DB::select(
            "(select id as \"DT_RowId\", topic, notes, action_taken, status, created_at, id, sequence, sub_sequence
            from ccm.ccm_topics
            where id in (select max(id)
            FROM ccm.ccm_topics
            WHERE patient_id='" . $id . "'  And topic NOT LIKE 'EMR Monthly Summary%' And topic NOT LIKE 'Summary notes added on%'
            And topic NOT LIKE 'Additional Services%'
            AND EXTRACT(Month from record_date) = '" . $month . "' AND EXTRACT(YEAR from record_date) = '" . $year . "'
            group by topic) order by sequence, sub_sequence ASC)
            union
            (select id as \"DT_RowId\", topic, notes, action_taken, status, created_at, id, sequence, sub_sequence
             from ccm.ccm_topics WHERE patient_id='" . $id . "' And status = 1 And topic LIKE 'EMR Monthly Summary%'
             AND EXTRACT(Month from record_date) = '" . $month . "' AND EXTRACT(YEAR from record_date) = '" . $year . "' order by sequence, sub_sequence ASC)
             union
             (select id as \"DT_RowId\", topic, notes, action_taken, status,  created_at, id, sequence, sub_sequence
             from ccm.ccm_topics WHERE patient_id='" . $id . "' And status = 1 And topic  LIKE 'Summary notes added on%'
             AND EXTRACT(Month from record_date) = '" . $month . "' AND EXTRACT(YEAR from record_date) = '" . $year . "' order by sequence, sub_sequence ASC)
             union
                (select id as \"DT_RowId\", topic, notes, action_taken, status, created_at, id, sequence, sub_sequence
                from ccm.ccm_topics
                where id in (select max(id)
                FROM ccm.ccm_topics
                WHERE patient_id='" . $id . "'
                And topic LIKE 'Additional Services :%' And status = 1
                AND EXTRACT(Month from record_date) = '" . $month . "' AND EXTRACT(YEAR from record_date) = '" . $year . "'
                group by topic) order by sequence, sub_sequence ASC)


            "
        );


        $headers = array(
            "Content-type"        => "text/html",
            "Content-Disposition" => "attachment;Filename=Call Manager Notes for Review and Approval.doc"
        );
        $content =  View::make('Ccm::monthly-monitoring.sub-steps.call-sub-steps.call-wrap-up-docs', compact('data', 'patient'))->render();
        return Response::make($content, 200, $headers);
    }

    public function DeleteCallWrapupNotes(Request $request)
    {
        $id = sanitizeVariable($request->id);
        $mid = sanitizeVariable(getPageModuleName());
        $fetch_topic = CallWrap::where('id', $id)->get();
        $patient_id = $fetch_topic[0]->patient_id;
        $check_activity = PatientActivity::where('callwrap_id', $id)->exists();
        if ($check_activity == true) {
            PatientActivity::where('callwrap_id', $id)->delete();
            CallWrap::where('id', $id)->delete();
        } else {
            CallWrap::where('id', $id)->delete();
        }
        $start_time   = sanitizeVariable($request->timer_start);
        $end_time     = sanitizeVariable($request->timer_paused);
        $uid          = sanitizeVariable($request->id);
        $module_id    = $mid;
        $component_id = sanitizeVariable(getPageSubModuleName());
        $stage_id     = 9;
        $step_id      = 0;
        $billable     = 1;
        $form_name    = 'callwrapup_delete';
        $form_start_time = sanitizeVariable($request->form_start_time);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        // $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name, $form_start_time, $form_save_time);
        // $last_time_spend      = CommonFunctionController::getNetTimeBasedOnModule($patient_id, $mid);
        return $form_save_time;
    }

    public function SaveCallSatus(CallAddRequest $request)
    {
        $errormsg = '';
        $id                          = sanitizeVariable($request->patient_id);
        $contact_via                 = "Call";
        $call_status                 = sanitizeVariable($request->call_status);
        $template_type_id            = sanitizeVariable($request->template_type_id);
        $call_continue_status        = sanitizeVariable($request->call_continue_status);
        $answer_followup_date        = sanitizeVariable($request->answer_followup_date);
        $answer_followup_time        = sanitizeVariable($request->hourtime);
        $voice_mail                  = sanitizeVariable($request->voice_mail);
        $call_followup_date          = sanitizeVariable($request->call_followup_date);
        $type                        = "content";
        $content_title               = sanitizeVariable($request->content_title);
        $text_msg                    = sanitizeVariable($request->text_msg);
        $mon_0               = sanitizeVariable($request->mon_0);
        $mon_1               = sanitizeVariable($request->mon_1);
        $mon_2               = sanitizeVariable($request->mon_2);
        $mon_3               = sanitizeVariable($request->mon_3);
        $mon_any             = sanitizeVariable($request->mon_any);
        $tue_0               = sanitizeVariable($request->tue_0);
        $tue_1               = sanitizeVariable($request->tue_1);
        $tue_2               = sanitizeVariable($request->tue_2);
        $tue_3               = sanitizeVariable($request->tue_3);
        $tue_any             = sanitizeVariable($request->tue_any);
        $wed_0               = sanitizeVariable($request->wed_0);
        $wed_1               = sanitizeVariable($request->wed_1);
        $wed_2               = sanitizeVariable($request->wed_2);
        $wed_3               = sanitizeVariable($request->wed_3);
        $wed_any             = sanitizeVariable($request->wed_any);
        $thu_0               = sanitizeVariable($request->thu_0);
        $thu_1               = sanitizeVariable($request->thu_1);
        $thu_2               = sanitizeVariable($request->thu_2);
        $thu_3               = sanitizeVariable($request->thu_3);
        $thu_any             = sanitizeVariable($request->thu_any);
        $fri_0               = sanitizeVariable($request->fri_0);
        $fri_1               = sanitizeVariable($request->fri_1);
        $fri_2               = sanitizeVariable($request->fri_2);
        $fri_3               = sanitizeVariable($request->fri_3);
        $fri_any             = sanitizeVariable($request->fri_any);
        if ($call_status == 1) {
            $step_id = sanitizeVariable($request->call_answered_step_id);
        } else {
            $step_id = sanitizeVariable($request->call_notanswered_step_id);
        }
        //record time
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $uid          = sanitizeVariable($id);
        $module_id    = sanitizeVariable($request->module_id);
        $component_id = sanitizeVariable($request->component_id);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = $step_id;
        $billable     = sanitizeVariable($request->billable);
        $form_name    = sanitizeVariable($request->form_name);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            if ($call_status == '1') {
                $template_id                  = sanitizeVariable($request->call_answer_template_id);
                $content                      = sanitizeVariable($request->call_answer_template);
                $phone_no                     = "";
                $answer_followup_date         = ($request->answer_followup_date != '') ? date("Y-m-d H:i:s", strtotime(sanitizeVariable($request->answer_followup_date))) : null;
                $call_followup_date           =  null;
                $ctopic = 'Call Answered : Call Continue No';
                $voice_mail = 0;
            } else if ($call_status == '2') {
                $template_id                  = 2;
                $content                      = '';
                $phone_no                     = sanitizeVariable($request->phone_no);
                $template_type_id             = 2;
                $call_followup_date           = ($request->call_followup_date != '') ? date("Y-m-d H:i:s", strtotime(sanitizeVariable($request->call_followup_date))) : null;
                $answer_followup_date         = null;
                $answer_followup_time         = null;
                if ($voice_mail == 1) {
                    $ctopic = 'Call Not Answered : Left Voice Mail';
                    $content =  sanitizeVariable($request->voice_template);
                } else {
                    $ctopic = 'Call Not Answered : No Voice Mail';
                }
                if ($voice_mail == '3') {
                    $content  = sanitizeVariable($text_msg);
                    if (sanitizeVariable($request->has('phone_no'))) {
                        $errormsg = sendTextMessage($phone_no, $content, $id, $module_id, $stage_id);
                    } else {
                        $errormsg = sanitizeVariable($request->error);
                        return response($errormsg, 406);
                    }
                    $ctopic = 'Call Not Answered : Send Text Message';
                }
            }
            $template  = array(
                'content'            => $content,
                'phone_no'           => $phone_no,
                'content_title'      => $content_title
            );
            $contentTemplateUsageHistoryData = array(
                'contact_via'   => $contact_via,
                'template_type' => $template_type_id,
                'uid'           => $uid,
                'module_id'     => $module_id,
                'component_id'  => $component_id,
                'template_id'   => $template_id,
                'template'      => json_encode($template),
                'stage_id'      => $stage_id,
                'patient_id'    => $id
            );
            $history_id = ContentTemplateUsageHistory::create($contentTemplateUsageHistoryData);
            $hid        = $history_id->id;
            $contact    = array(
                'type'        => $type,
                'template_id' => $template_id,
                'history_id'  => $hid
            );
            $data = array(
                'uid'                      => $uid,
                'rec_date'                 => Carbon::now(),
                'phone_no'                 => $phone_no,
                'call_action_template'     => json_encode($contact),
                'call_continue_status'     => $call_continue_status,
                'call_status'              => $call_status,
                'voice_mail'               => $voice_mail,
                'ccm_call_followup_date'   => $call_followup_date,
                'ccm_answer_followup_date' => $answer_followup_date,
                'ccm_answer_followup_time' => $answer_followup_time,
                'text_msg'                 => $content,
                'patient_id'               => $id,
                'module_id'                => $module_id,
                'component_id'             => $component_id
            );

            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $id, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name, $form_start_time, $form_save_time);
            if (isset($call_followup_date) && ($call_followup_date != "")) {
                $follow_up_date = $call_followup_date;
                $to_do = array(
                    'uid'                         => $id,
                    'module_id'                   => $module_id,
                    'component_id'                => $component_id,
                    'stage_id'                    => $stage_id,
                    'step_id'                     => $step_id,
                    'task_notes'                  => "CCM monthly followup call",
                    'task_date'                   => $follow_up_date,
                    'assigned_to'                 => session()->get('userid'),
                    'assigned_on'                 => Carbon::now(),
                    'status'                      => 'Pending',
                    'status_flag'                 => 0,
                    'created_by'                  => session()->get('userid'),
                    'patient_id'                  => $id
                );
                $insert = ToDoList::create($to_do);
            }
            if (isset($answer_followup_date) && ($answer_followup_date != "")) {
                $follow_up_date = $answer_followup_date;
                $to_do = array(
                    'uid'                         => $id,
                    'module_id'                   => $module_id,
                    'component_id'                => $component_id,
                    'stage_id'                    => $stage_id,
                    'step_id'                     => $step_id,
                    'task_notes'                  => "Return phone call",
                    'task_date'                   => $follow_up_date,
                    'task_time'                   => $answer_followup_time,
                    'assigned_to'                 => session()->get('userid'),
                    'assigned_on'                 => Carbon::now(),
                    'status'                      => 'Pending',
                    'status_flag'                 => 0,
                    'created_by'                  => session()->get('userid'),
                    'patient_id'                  => $id
                );
                $insert = ToDoList::create($to_do);
            }
            $check_exist_for_month  = CallStatus::where('patient_id', $id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->exists();
            $data['created_by'] = session()->get('userid');
            $data['updated_by'] = session()->get('userid');
            $insert_query = CallStatus::create($data);
            if ($call_continue_status == '0' && $answer_followup_date != '') {
                $content = "Next Call Continue " . date("m-d-Y", strtotime($answer_followup_date)) . ' and ' . $answer_followup_time;
            }
            $call_not_answar_note = array(
                'uid'                 => $uid,
                'record_date'         => Carbon::now(),
                'topic'               => $ctopic,
                'notes'               => $content,
                'patient_id'          => $id,
                'sequence'            => "1",
                'sub_sequence'        => "11",
                'created_by'          => session()->get('userid')
            );
            if ($call_status == '2' || $call_continue_status == '0') {
                if (CallWrap::where('patient_id', $id)->where('sequence', '1')->where('sub_sequence', '11')->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->exists()) {
                    CallWrap::where('patient_id', $id)->where('sequence', '1')->where('sub_sequence', '11')->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->orderBy('id', 'desc')->first()->update($call_not_answar_note);
                } else {
                    CallWrap::create($call_not_answar_note);
                }
            }
            $patient_contact_time_data = array(
                'mon_0'                 => $mon_0 == null ? 0 : $mon_0,
                'mon_1'                 => $mon_1 == null ? 0 : $mon_1,
                'mon_2'                 => $mon_2 == null ? 0 : $mon_2,
                'mon_3'                 => $mon_3 == null ? 0 : $mon_3,
                'mon_any'               => $mon_any == null ? 0 : $mon_any,
                'tue_0'                 => $tue_0 == null ? 0 : $tue_0,
                'tue_1'                 => $tue_1 == null ? 0 : $tue_1,
                'tue_2'                 => $tue_2 == null ? 0 : $tue_2,
                'tue_3'                 => $tue_3 == null ? 0 : $tue_3,
                'tue_any'               => $tue_any == null ? 0 : $tue_any,
                'wed_0'                 => $wed_0 == null ? 0 : $wed_0,
                'wed_1'                 => $wed_1 == null ? 0 : $wed_1,
                'wed_2'                 => $wed_2 == null ? 0 : $wed_2,
                'wed_3'                 => $wed_3 == null ? 0 : $wed_3,
                'wed_any'               => $wed_any == null ? 0 : $wed_any,
                'thu_0'                 => $thu_0 == null ? 0 : $thu_0,
                'thu_1'                 => $thu_1 == null ? 0 : $thu_1,
                'thu_2'                 => $thu_2 == null ? 0 : $thu_2,
                'thu_3'                 => $thu_3 == null ? 0 : $thu_3,
                'thu_any'               => $thu_any == null ? 0 : $thu_any,
                'fri_0'                 => $fri_0 == null ? 0 : $fri_0,
                'fri_1'                 => $fri_1 == null ? 0 : $fri_1,
                'fri_2'                 => $fri_2 == null ? 0 : $fri_2,
                'fri_3'                 => $fri_3 == null ? 0 : $fri_3,
                'fri_any'               => $fri_any == null ? 0 : $fri_any,
            );
            $patientcontact = PatientContactTime::where('patient_id', $id)->get();
            if (count($patientcontact) > 0) {
                $patient_contact_time_data['updated_by'] = session()->get('userid');
                PatientContactTime::where('patient_id', $id)->update($patient_contact_time_data);
            } else {
                $patient_contact_time_data['patient_id'] = $id;
                $patient_contact_time_data['created_by'] = session()->get('userid');
                $patient_contact_time_data['updated_by'] = session()->get('userid');
                PatientContactTime::create($patient_contact_time_data);
            }
            DB::commit();
            return response(['form_start_time' => $form_save_time, 'errormsg' => $errormsg]);
            // return $errormsg;
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function getTotalTimeSpent($patient_id, $module_id, $stage_id)
    {
        $patient_id         = sanitizeVariable($patient_id);
        $module_id          = sanitizeVariable($module_id);
        $stage_id          = sanitizeVariable($stage_id);
        $year       = sanitizeVariable(date('Y', strtotime(Carbon::now())));
        $month      = sanitizeVariable(date('m', strtotime(Carbon::now())));
        $query = DB::select("select sum(net_time) as net_time from patients.patient_time_records
                        WHERE patient_id='" . $patient_id . "'
                        AND module_id in (2,3,8)
                        AND stage_id ='" . $stage_id . "'
                        AND EXTRACT(Month from record_date) = '" . $month . "'
                        AND EXTRACT(YEAR from record_date) = '" . $year . "'
                        ");
        return $query;
    }

    public function SaveCallPreparation(PreparationAddRequest $request)
    {
        $uid                           = sanitizeVariable($request->uid);
        $patient_id                    = sanitizeVariable($request->patient_id);
        $condition_requirnment1        = empty(sanitizeVariable($request->condition_requirnment1)) ? '0' : sanitizeVariable($request->condition_requirnment1);
        $condition_requirnment2        = empty(sanitizeVariable($request->condition_requirnment2)) ? '0' : sanitizeVariable($request->condition_requirnment2);
        $condition_requirnment3        = empty(sanitizeVariable($request->condition_requirnment3)) ? '0' : sanitizeVariable($request->condition_requirnment3);
        $condition_requirnment4        = empty(sanitizeVariable($request->condition_requirnment4)) ? '0' : sanitizeVariable($request->condition_requirnment4);
        $report_requirnment1           = empty(sanitizeVariable($request->report_requirnment1)) ? '0' : sanitizeVariable($request->report_requirnment1);
        $report_requirnment2           = empty(sanitizeVariable($request->report_requirnment2)) ? '0' : sanitizeVariable($request->report_requirnment2);
        $report_requirnment3           = empty(sanitizeVariable($request->report_requirnment3)) ? '0' : sanitizeVariable($request->report_requirnment3);
        $report_requirnment4           = empty(sanitizeVariable($request->report_requirnment4)) ? '0' : sanitizeVariable($request->report_requirnment4);
        $report_requirnment5           = empty(sanitizeVariable($request->report_requirnment5)) ? '0' : sanitizeVariable($request->report_requirnment5);
        $patient_relationship_building = sanitizeVariable($request->patient_relationship_building);
        $condition_requirnment_notes   = sanitizeVariable($request->condition_requirnment_notes);
        $newofficevisit                = sanitizeVariable($request->newofficevisit);
        $nov_notes                     = sanitizeVariable($request->nov_notes);
        $newdiagnosis                  = sanitizeVariable($request->newdiagnosis);
        $nd_notes                      = sanitizeVariable($request->nd_notes);
        $report_requirnment_notes      = sanitizeVariable($request->report_requirnment_notes);
        $med_added_or_discon           = sanitizeVariable($request->med_added_or_discon);
        $med_added_or_discon_notes     = sanitizeVariable($request->med_added_or_discon_notes);
        $anything_else                 = sanitizeVariable($request->anything_else);
        $start_time                    = sanitizeVariable($request->start_time);
        $end_time                      = sanitizeVariable($request->end_time);
        // dd($request->timearr['form_start_time']);
        $form_start_time               = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time                = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        // dd($form_save_time);
        $module_id                     = sanitizeVariable($request->module_id);
        $component_id                  = sanitizeVariable($request->component_id);
        $stage_id                      = sanitizeVariable($request->stage_id);
        $step_id                       = sanitizeVariable($request->step_id);
        $form_name                     = sanitizeVariable($request->form_name);
        $billable                      = 1;
        $currentMonth                  = date('m');
        $currentYear                   = date('Y');
        $check_exist_for_month         = CallPreparation::where('patient_id', $patient_id)->whereMonth('updated_at', $currentMonth)->whereYear('updated_at', $currentYear)->exists();
        if (isset($request->pcp_reviwewd)) {
            $pcp_reviwewd = sanitizeVariable($request->pcp_reviwewd);
        } else {
            $pcp_reviwewd = NULL;
        }
        if (isset($request->submited_to_emr)) {
            $submited_to_emr = sanitizeVariable($request->submited_to_emr);
        } else {
            $submited_to_emr = NULL;
        }
        if ($condition_requirnment1 == '1') {
            $cr1  = 1;
            $cr_1 = 'New Hospitalization, ';
        } else {
            $cr1  = 0;
            $cr_1 = '';
        }
        if ($condition_requirnment2 == '1') {
            $cr2  = 1;
            $cr_2 = 'ER Visits, ';
        } else {
            $cr2  = 0;
            $cr_2 = '';
        }
        if ($condition_requirnment3 == '1') {
            $cr3  = 1;
            $cr_3 = 'Urgent Care, ';
        } else {
            $cr3  = 0;
            $cr_3 = '';
        }
        if ($condition_requirnment4 == '1') {
            $cr4  = 1;
            $cr_4 = 'None';
        } else {
            $cr4  = 0;
            $cr_4 = '';
        }
        if ($report_requirnment1    == '1') {
            $rr1  = 1;
            $rr_1 = 'New Labs, ';
        } else {
            $rr1  = 0;
            $rr_1 = '';
        }
        if ($report_requirnment2    == '1') {
            $rr2  = 1;
            $rr_2 = 'Diagnostic Imaging, ';
        } else {
            $rr2  = 0;
            $rr_2 = '';
        }
        if ($report_requirnment3    == '1') {
            $rr3  = 1;
            $rr_3 = 'None';
        } else {
            $rr3  = 0;
            $rr_3 = '';
        }
        if ($report_requirnment4    == '1') {
            $rr4  = 1;
            $rr_4 = 'Health Data, ';
        } else {
            $rr4  = 0;
            $rr_4 = '';
        }
        if ($report_requirnment5    == '1') {
            $rr5  = 1;
            $rr_5 = 'Vitals Data, ';
        } else {
            $rr5  = 0;
            $rr_5 = '';
        }
        $data = array(
            'uid'                           => $uid,
            'prep_date'                     => Carbon::now(),
            'patient_relationship_building' => $patient_relationship_building,
            'condition_requirnment1'        => $cr1,
            'condition_requirnment_notes'   => $condition_requirnment_notes,
            'newofficevisit'                => $newofficevisit,
            'nov_notes'                     => $nov_notes,
            'newdiagnosis'                  => $newdiagnosis,
            'nd_notes'                      => $nd_notes,
            'report_requirnment_notes'      => $report_requirnment_notes,
            'med_added_or_discon'           => $med_added_or_discon,
            'med_added_or_discon_notes'     => $med_added_or_discon_notes,
            'anything_else'                 => $anything_else,
            'patient_id'                    => $patient_id,
            'condition_requirnment2'        => $cr2,
            'condition_requirnment3'        => $cr3,
            'condition_requirnment4'        => $cr4,
            'report_requirnment1'           => $rr1,
            'report_requirnment2'           => $rr2,
            'report_requirnment3'           => $rr3,
            'report_requirnment4'           => $rr4,
            'report_requirnment5'           => $rr5,
            'pcp_reviwewd'                  => $pcp_reviwewd,
            'submited_to_emr'               => $submited_to_emr
        );
        if (isset($cr1) || isset($cr2) || isset($cr3)) {
            $cr = $cr_1 . $cr_2 . $cr_3 . '. ' . $condition_requirnment_notes;
            $pos = strpos($cr, ', .');
            $sub = substr_replace($cr, '', $pos, 2);
            $cr = $sub;
        } else {
            $cr = $cr_4;
        }
        if ($cr === ', .' || $cr === '.' || $cr == '') {
            $cr = "None";
        }
        $note_0 = array(
            'uid'                 => $uid,
            'record_date'         => Carbon::now(),
            'topic'               => 'New Hospitalization / ER visit / Urgent Care',
            'notes'               => $cr,
            'patient_id'          => $patient_id
        );
        if (isset($rr1) || isset($rr2) || isset($rr4) || isset($rr5)) {
            $rr = $rr_1 . $rr_2 . $rr_4 . $rr_5 . '.'; //. '. '. $request->report_requirnment_notes;
            $pos = strpos($rr, ', .');
            $sub = substr_replace($rr, '', $pos, 2);
            $rr = $sub;
        } else {
            $rr = $rr_3;
        }
        if ($rr === ', . ' || $rr === '.' || $rr == '') {
            $rr = "None";
        }
        $note_1 = array(
            'uid'                 => $uid,
            'record_date'         => Carbon::now(),
            'topic'               => 'New Labs / Diagnostic Imaging / Health Data / Vitals Data',
            'notes'               => $rr,
            'patient_id'          => $patient_id
        );
        $new_office_visit = sanitizeVariable($request->newofficevisit);
        if ($new_office_visit == '1') {
            $office_visit_notes = 'Yes' . '. ' . sanitizeVariable($request->nov_notes);
        } else {
            $office_visit_notes = 'No';
        }
        $note_2 = array(
            'uid'                 => $uid,
            'record_date'         => Carbon::now(),
            'topic'               => 'New Office Visits(any Doctor)',
            'notes'               => $office_visit_notes,
            'patient_id'          => $patient_id
        );
        $new_condition = sanitizeVariable($request->newdiagnosis);
        if ($new_condition == '1') {
            $condition_notes = 'Yes' . '. ' . sanitizeVariable($request->nd_notes);
        } else {
            $condition_notes = 'No';
        }
        $note_3 = array(
            'uid'                 => $uid,
            'record_date'         => Carbon::now(),
            'topic'               => 'New Diagnosis',
            'notes'               => $condition_notes,
            'patient_id'          => $patient_id
        );
        $new_medication = sanitizeVariable($request->med_added_or_discon);
        if ($new_medication == '1') {
            $medication_notes = 'Yes' . '. ' . $med_added_or_discon_notes;
        } else {
            $medication_notes = 'No';
        }
        $note_4 = array(
            'uid'                 => $uid,
            'record_date'         => Carbon::now(),
            'topic'               => 'Medications added or discontinued',
            'notes'               => $medication_notes,
            'patient_id'          => $patient_id
        );
        $july_jan_question = sanitizeVariable($request->pcp_reviwewd);
        if ($july_jan_question == '1') {
            $radio_opt = 'Yes';
        } else if ($july_jan_question == '0') {
            $radio_opt = 'No';
        } else {
            $radio_opt = '';
        }
        $note_5 = array(
            'uid'                 => $uid,
            'record_date'         => Carbon::now(),
            'topic'               => 'Is a current copy of the Care Plan signed by the PCP and in the EMR?',
            'notes'               => $radio_opt,
            'patient_id'          => $patient_id
        );
        // DB::beginTransaction();
        // try {
        //record time
        $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name, $form_start_time, $form_save_time);
        if (CallWrap::where('patient_id', $patient_id)->where('topic', 'New Hospitalization / ER visit / Urgent Care')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->exists()) {
            $note_0['updated_by'] = session()->get('userid');
            CallWrap::where('patient_id', $patient_id)->where('topic', 'New Hospitalization / ER visit / Urgent Care')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->update($note_0);
        } else {
            $note_0['sequence'] = "1";
            $note_0['sub_sequence'] = "1";
            $note_0['created_by'] = session()->get('userid');
            CallWrap::create($note_0);
        }
        if (CallWrap::where('patient_id', $patient_id)->where('topic', 'New Labs / Diagnostic Imaging / Health Data / Vitals Data')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->exists()) {
            $note_1['updated_by'] = session()->get('userid');
            CallWrap::where('patient_id', $patient_id)->where('topic', 'New Labs / Diagnostic Imaging / Health Data / Vitals Data')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->update($note_1);
        } else {
            $note_1['sequence'] = "1";
            $note_1['sub_sequence'] = "2";
            $note_1['created_by'] = session()->get('userid');
            CallWrap::create($note_1);
        }
        if (CallWrap::where('patient_id', $patient_id)->where('topic', 'New Office Visits(any Doctor)')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->exists()) {
            $note_2['updated_by'] = session()->get('userid');
            CallWrap::where('patient_id', $patient_id)->where('topic', 'New Office Visits(any Doctor)')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->update($note_2);
        } else {
            $note_2['sequence'] = "1";
            $note_2['sub_sequence'] = "3";
            $note_2['created_by'] = session()->get('userid');
            CallWrap::create($note_2);
        }
        if (CallWrap::where('patient_id', $patient_id)->where('topic', 'New Diagnosis')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->exists()) {
            $note_3['updated_by'] = session()->get('userid');
            CallWrap::where('patient_id', $patient_id)->where('topic', 'New Diagnosis')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->update($note_3);
        } else {
            $note_3['sequence'] = "1";
            $note_3['sub_sequence'] = "4";
            $note_3['created_by'] = session()->get('userid');
            CallWrap::create($note_3);
        }
        if (CallWrap::where('patient_id', $patient_id)->where('topic', 'Medications added or discontinued')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->exists()) {
            $note_4['updated_by'] = session()->get('userid');
            CallWrap::where('patient_id', $patient_id)->where('topic', 'Medications added or discontinued')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->update($note_4);
        } else {
            $note_4['sequence'] = "1";
            $note_4['sub_sequence'] = "5";
            $note_4['created_by'] = session()->get('userid');
            CallWrap::create($note_4);
        }
        if (CallWrap::where('patient_id', $patient_id)->where('topic', 'Is a current copy of the Care Plan signed by the PCP and in the EMR?')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->exists()) {
            $note_5['updated_by'] = session()->get('userid');
            CallWrap::where('patient_id', $patient_id)->where('topic', 'Is a current copy of the Care Plan signed by the PCP and in the EMR?')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->update($note_5);
        } else {
            $note_5['sequence'] = "1";
            $note_5['sub_sequence'] = "6";
            $note_5['created_by'] = session()->get('userid');
            CallWrap::create($note_5);
        }
        if ($check_exist_for_month == true) {
            $data['updated_by'] = session()->get('userid');
            $update_query = CallPreparation::where('patient_id', $patient_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->orderBy('id', 'desc')->first()->update($data);
        } else {
            $data['created_by'] = session()->get('userid');
            $insert_query = CallPreparation::create($data);
        }
        $patient_questionnaire_data = array(
            'patient_id'    => $patient_id,
            'questionnaire' => json_encode($patient_relationship_building),
        );
        if (PatientQuestionnaire::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists()) {
            PatientQuestionnaire::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->update($patient_questionnaire_data);
        } else {
            PatientQuestionnaire::create($patient_questionnaire_data);
        }

        return response(['form_start_time' => $form_save_time]);
        //     DB::commit();
        // } catch(\Exception $ex) {
        //     DB::rollBack();
        //     // return $ex;
        //     return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        // }
    }


    public function DraftSaveCallPreparation(PreparationDraftAddRequest $request)
    {
        $uid                           = sanitizeVariable($request->uid);
        $patient_id                    = sanitizeVariable($request->patient_id);
        $condition_requirnment1        = empty(sanitizeVariable($request->condition_requirnment1)) ? '0' : sanitizeVariable($request->condition_requirnment1);
        $condition_requirnment2        = empty(sanitizeVariable($request->condition_requirnment2)) ? '0' : sanitizeVariable($request->condition_requirnment2);
        $condition_requirnment3        = empty(sanitizeVariable($request->condition_requirnment3)) ? '0' : sanitizeVariable($request->condition_requirnment3);
        $condition_requirnment4        = empty(sanitizeVariable($request->condition_requirnment4)) ? '0' : sanitizeVariable($request->condition_requirnment4);
        $report_requirnment1           = empty(sanitizeVariable($request->report_requirnment1)) ? '0' : sanitizeVariable($request->report_requirnment1);
        $report_requirnment2           = empty(sanitizeVariable($request->report_requirnment2)) ? '0' : sanitizeVariable($request->report_requirnment2);
        $report_requirnment3           = empty(sanitizeVariable($request->report_requirnment3)) ? '0' : sanitizeVariable($request->report_requirnment3);
        $report_requirnment4           = empty(sanitizeVariable($request->report_requirnment4)) ? '0' : sanitizeVariable($request->report_requirnment4);
        $report_requirnment5           = empty(sanitizeVariable($request->report_requirnment5)) ? '0' : sanitizeVariable($request->report_requirnment5);
        $patient_relationship_building = sanitizeVariable($request->patient_relationship_building);
        $condition_requirnment_notes   = sanitizeVariable($request->condition_requirnment_notes);
        $newofficevisit                = sanitizeVariable($request->newofficevisit);
        $nov_notes                     = sanitizeVariable($request->nov_notes);
        $newdiagnosis                  = sanitizeVariable($request->newdiagnosis);
        $nd_notes                      = sanitizeVariable($request->nd_notes);
        $report_requirnment_notes      = sanitizeVariable($request->report_requirnment_notes);
        $med_added_or_discon           = sanitizeVariable($request->med_added_or_discon);
        $med_added_or_discon_notes     = sanitizeVariable($request->med_added_or_discon_notes);
        $anything_else                 = sanitizeVariable($request->anything_else);
        $start_time                    = sanitizeVariable($request->start_time);
        $end_time                      = sanitizeVariable($request->end_time);
        $module_id                     = sanitizeVariable($request->module_id);
        $component_id                  = sanitizeVariable($request->component_id);
        $stage_id                      = sanitizeVariable($request->stage_id);
        $step_id                       = sanitizeVariable($request->step_id);
        $form_name                     = sanitizeVariable($request->form_name);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        $billable                      = 1;
        $currentMonth                  = date('m');
        $currentYear                   = date('Y');
        $check_exist_for_month         = CallPreparation::where('patient_id', $patient_id)->whereMonth('updated_at', $currentMonth)->whereYear('updated_at', $currentYear)->exists();
        if ($condition_requirnment1 == '1') {
            $cr1  = 1;
            $cr_1 = 'New Hospitalization, ';
        } else {
            $cr1  = 0;
            $cr_1 = '';
        }
        if ($condition_requirnment2 == '1') {
            $cr2  = 1;
            $cr_2 = 'ER Visits, ';
        } else {
            $cr2  = 0;
            $cr_2 = '';
        }
        if ($condition_requirnment3 == '1') {
            $cr3  = 1;
            $cr_3 = 'Urgent Care, ';
        } else {
            $cr3  = 0;
            $cr_3 = '';
        }
        if ($condition_requirnment4 == '1') {
            $cr4  = 1;
            $cr_4 = 'None';
        } else {
            $cr4  = 0;
            $cr_4 = '';
        }
        if ($report_requirnment1    == '1') {
            $rr1  = 1;
            $rr_1 = 'New Labs, ';
        } else {
            $rr1  = 0;
            $rr_1 = '';
        }
        if ($report_requirnment2    == '1') {
            $rr2  = 1;
            $rr_2 = 'Diagnostic Imaging, ';
        } else {
            $rr2  = 0;
            $rr_2 = '';
        }
        if ($report_requirnment3    == '1') {
            $rr3  = 1;
            $rr_3 = 'None';
        } else {
            $rr3  = 0;
            $rr_3 = '';
        }
        if ($report_requirnment4    == '1') {
            $rr4  = 1;
            $rr_4 = 'Health Data, ';
        } else {
            $rr4  = 0;
            $rr_4 = '';
        }
        if ($report_requirnment5    == '1') {
            $rr5  = 1;
            $rr_5 = 'Vitals Data, ';
        } else {
            $rr5  = 0;
            $rr_5 = '';
        }
        $data = array(
            'uid'                           => $uid,
            'prep_date'                     => Carbon::now(),
            'patient_relationship_building' => $patient_relationship_building,
            'condition_requirnment1'        => $cr1,
            'condition_requirnment_notes'   => $condition_requirnment_notes,
            'newofficevisit'                => $newofficevisit,
            'nov_notes'                     => $nov_notes,
            'newdiagnosis'                  => $newdiagnosis,
            'nd_notes'                      => $nd_notes,
            'report_requirnment_notes'      => $report_requirnment_notes,
            'med_added_or_discon'           => $med_added_or_discon,
            'med_added_or_discon_notes'     => $med_added_or_discon_notes,
            'anything_else'                 => $anything_else,
            'patient_id'                    => $patient_id,
            'condition_requirnment2'        => $cr2,
            'condition_requirnment3'        => $cr3,
            'condition_requirnment4'        => $cr4,
            'report_requirnment1'           => $rr1,
            'report_requirnment2'           => $rr2,
            'report_requirnment3'           => $rr3,
            'report_requirnment4'           => $rr4,
            'report_requirnment5'           => $rr5
        );
        if (isset($cr1) || isset($cr2) || isset($cr3) || isset($cr4)) {
            $cr = $cr_1 . $cr_2 . $cr_3 . $cr_4 . '. ' . $condition_requirnment_notes;
            $pos = strpos($cr, ', .');
            $sub = substr_replace($cr, '', $pos, 2);
            if ($sub == "ne. ") {
                $cr = 'None.';
            } else {
                $cr = $sub;
            }
        } else {
            $cr = '';
        }
        $note_0 = array(
            'uid'                 => $uid,
            'record_date'         => Carbon::now(),
            'topic'               => 'New Hospitalization / ER visit / Urgent Care',
            'notes'               => $cr,
            'patient_id'          => $patient_id
        );
        if (isset($rr1) || isset($rr2) || isset($rr4) || isset($rr5) || isset($rr3)) {
            $rr = $rr_1 . $rr_2 . $rr_4 . $rr_5 . $rr_3 . '. ' . $request->report_requirnment_notes;
            $pos = strpos($rr, ', .');
            $sub = substr_replace($rr, '', $pos, 2);
            if ($sub == "ne. ") {
                $rr = 'None.';
            } else {
                $rr = $sub;
            }
        } else {
            $rr = '';
        }
        $note_1 = array(
            'uid'                 => $uid,
            'record_date'         => Carbon::now(),
            'topic'               => 'New Labs / Diagnostic Imaging / Health Data / Vitals Data',
            'notes'               => $rr,
            'patient_id'          => $patient_id
        );
        $new_office_visit = sanitizeVariable($request->newofficevisit);
        if ($new_office_visit == '1') {
            $office_visit_notes = 'Yes' . '. ' . sanitizeVariable($request->nov_notes);
        } else if ($new_office_visit == '0') {
            $office_visit_notes = 'No';
        } else {
            $office_visit_notes = '';
        }
        if ($new_office_visit != '') {
            $note_2 = array(
                'uid'                 => $uid,
                'record_date'         => Carbon::now(),
                'topic'               => 'New Office Visits(any Doctor)',
                'notes'               => $office_visit_notes,
                'patient_id'          => $patient_id
            );
        } else {
            $note_2 = '';
        }
        $new_condition = sanitizeVariable($request->newdiagnosis);
        if ($new_condition == '1') {
            $condition_notes = 'Yes' . '. ' . sanitizeVariable($request->nd_notes);
        } else if ($new_condition == '0') {
            $condition_notes = 'No';
        } else {
            $condition_notes = '';
        }
        if ($condition_notes != '') {
            $note_3 = array(
                'uid'                 => $uid,
                'record_date'         => Carbon::now(),
                'topic'               => 'New Diagnosis',
                'notes'               => $condition_notes,
                'patient_id'          => $patient_id
            );
        } else {
            $note_3 = '';
        }
        $new_medication = sanitizeVariable($request->med_added_or_discon);
        if ($new_medication == '1') {
            $medication_notes = 'Yes' . '. ' . $med_added_or_discon_notes;
        } else if ($new_medication == '0') {
            $medication_notes = 'No';
        } else {
            $medication_notes = '';
        }
        if ($medication_notes != '') {
            $note_4 = array(
                'uid'                 => $uid,
                'record_date'         => Carbon::now(),
                'topic'               => 'Medications added or discontinued',
                'notes'               => $medication_notes,
                'patient_id'          => $patient_id
            );
        } else {
            $note_4 = '';
        }
        $july_jan_question = sanitizeVariable($request->pcp_reviwewd);
        if ($july_jan_question == '1') {
            $radio_opt = 'Yes';
        } else if ($july_jan_question == '0') {
            $radio_opt = 'No';
        } else {
            $radio_opt = '';
        }
        $note_5 = array(
            'uid'                 => $uid,
            'record_date'         => Carbon::now(),
            'topic'               => 'Is a current copy of the Care Plan signed by the PCP and in the EMR?',
            'notes'               => $radio_opt,
            'patient_id'          => $patient_id
        );
        DB::beginTransaction();
        try {
            //record time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name, $form_start_time, $form_save_time);
            $chk1_exist = CallWrap::where('patient_id', $patient_id)->where('topic', 'New Hospitalization / ER visit / Urgent Care')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->exists();
            if ($cr != '' && $chk1_exist == false) {
                $note_0['sequence'] = "1";
                $note_0['sub_sequence'] = "1";
                $note_0['created_by'] = session()->get('userid');
                CallWrap::create($note_0);
            } else {
                $note_0['updated_by'] = session()->get('userid');
                CallWrap::where('patient_id', $patient_id)->where('topic', 'New Hospitalization / ER visit / Urgent Care')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->update($note_0);
            }
            $chk2_exist = CallWrap::where('patient_id', $patient_id)->where('topic', 'New Labs / Diagnostic Imaging / Health Data / Vitals Data')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->exists();
            if ($rr != '' && $chk2_exist == false) {
                $note_1['sequence'] = "1";
                $note_1['sub_sequence'] = "2";
                $note_1['created_by'] = session()->get('userid');
                CallWrap::create($note_1);
            } else {
                $note_1['updated_by'] = session()->get('userid');
                CallWrap::where('patient_id', $patient_id)->where('topic', 'New Labs / Diagnostic Imaging / Health Data / Vitals Data')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->update($note_1);
            }

            if ($note_2 != '') {
                if (CallWrap::where('patient_id', $patient_id)->where('topic', 'New Office Visits(any Doctor)')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->exists()) {
                    $note_2['updated_by'] = session()->get('userid');
                    CallWrap::where('patient_id', $patient_id)->where('topic', 'New Office Visits(any Doctor)')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->update($note_2);
                } else {
                    $note_2['sequence'] = "1";
                    $note_2['sub_sequence'] = "3";
                    $note_2['created_by'] = session()->get('userid');
                    CallWrap::create($note_2);
                }
            }

            if ($note_3 != '') {
                if (CallWrap::where('patient_id', $patient_id)->where('topic', 'New Diagnosis')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->exists()) {
                    $note_3['updated_by'] = session()->get('userid');
                    CallWrap::where('patient_id', $patient_id)->where('topic', 'New Diagnosis')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->update($note_3);
                } else {
                    $note_3['sequence'] = "1";
                    $note_3['sub_sequence'] = "4";
                    $note_3['created_by'] = session()->get('userid');
                    CallWrap::create($note_3);
                }
            }
            if ($note_4 != '') {
                if (CallWrap::where('patient_id', $patient_id)->where('topic', 'Medications added or discontinued')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->exists()) {
                    $note_4['updated_by'] = session()->get('userid');
                    CallWrap::where('patient_id', $patient_id)->where('topic', 'Medications added or discontinued')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->update($note_4);
                } else {
                    $note_4['sequence'] = "1";
                    $note_4['sub_sequence'] = "5";
                    $note_4['created_by'] = session()->get('userid');
                    CallWrap::create($note_4);
                }
            }
            if ($note_5 != '') {
                if (CallWrap::where('patient_id', $patient_id)->where('topic', 'Is a current copy of the Care Plan signed by the PCP and in the EMR?')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->exists()) {
                    $note_5['updated_by'] = session()->get('userid');
                    CallWrap::where('patient_id', $patient_id)->where('topic', 'Is a current copy of the Care Plan signed by the PCP and in the EMR?')->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->update($note_5);
                } else {
                    $note_5['sequence'] = "1";
                    $note_5['sub_sequence'] = "6";
                    $note_5['created_by'] = session()->get('userid');
                    CallWrap::create($note_5);
                }
            }
            if ($check_exist_for_month == true) {
                $data['updated_by'] = session()->get('userid');
                $update_query = CallPreparation::where('patient_id', $patient_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->orderBy('id', 'desc')->first()->update($data);
            } else {
                $data['created_by'] = session()->get('userid');
                $insert_query = CallPreparation::create($data);
            }
            $patient_questionnaire_data = array(
                'patient_id'    => $patient_id,
                'questionnaire' => json_encode($patient_relationship_building),
            );
            if (PatientQuestionnaire::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists()) {
                PatientQuestionnaire::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->update($patient_questionnaire_data);
            } else {
                PatientQuestionnaire::create($patient_questionnaire_data);
            }

            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }


    public function getRelationQuestion($patient_id)
    {
        $patient_id = sanitizeVariable($patient_id);
        $patientqut = PatientQuestionnaire::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->orderBy('created_at', 'desc')->first();
        $patientsRelationBuildingQuestionnaire = QuestionnaireTemplatesUsageHistory::where('patient_id', $patient_id)->where('template_id', 0)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->orderBy('created_at', 'desc')->first();
        $patient_building_template_exists = 0;
        $patient_building_questionnaire = array();
        if (isset($patientsRelationBuildingQuestionnaire->template) && $patientsRelationBuildingQuestionnaire->template != "") {
            $patient_building_questionnaire = json_decode($patientsRelationBuildingQuestionnaire->template, true);
        }
        if ($patientqut != '') {
            $pq =  json_decode($patientqut->questionnaire);
            if (array_key_exists($pq, $patient_building_questionnaire)) {
                $patient_building_template_exists = 1;
            }
            echo '<strong><spam id="patient-relationship-building">' . $pq . '</spam></strong>';
            echo '<textarea class="form-control col-md-8" name="patient_relationship_building' . '[question][' . $pq . ']" >' . ($patient_building_template_exists ? $patient_building_questionnaire[$pq] : '') . '</textarea>';
        }
    }

    public function SaveCallHippa(HippaAddRequest $request)
    {
        $patient_id   = sanitizeVariable($request->uid);
        $uid          = sanitizeVariable($request->uid);
        $notes        = sanitizeVariable($request->notes);
        $verification = sanitizeVariable($request->verification);
        $module_id    = sanitizeVariable($request->module);
        $component_id = sanitizeVariable($request->component);
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = sanitizeVariable($request->step_id);
        $form_name    = sanitizeVariable($request->form_name);
        $billable     = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        $check_id     = CallHipaaVerification::where('patient_id', $patient_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->exists();
        DB::beginTransaction();
        try {
            $data         = array(
                'uid'          => $uid,
                'v_date'       => Carbon::now(),
                'notes'        => $notes,
                'verification' => $verification,
                'patient_id'   => $patient_id,
                'module_id'    => $module_id,
                'component_id' => $component_id
            );
            //record time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name, $form_start_time, $form_save_time);

            if ($check_id == true) {
                $data['updated_by'] = session()->get('userid');
                $update_query = CallHipaaVerification::where('patient_id', $patient_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->orderBy('id', 'desc')->first()->update($data);
            } else {
                $data['created_by'] = session()->get('userid');
                $insert_query = CallHipaaVerification::create($data);
            }
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            // return $ex;
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function SaveCallRelationship(RelationshipAddRequest $request)
    {
        $patient_id   = sanitizeVariable($request->patient_id);
        $uid          = sanitizeVariable($request->uid);
        $sequence     = 2;
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $module_id    = sanitizeVariable($request->module_id);
        $component_id = sanitizeVariable($request->component_id);
        $form_name    = sanitizeVariable($request->form_name);
        $billable     = 1;
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        $taken_stage_id = sanitizeVariable($request->stage_id);
        if ($taken_stage_id == 0) {
            $stage_id = sanitizeVariable($request->hid_stage_id);
            $step_id = 0;
        } else {
            $stage_id = sanitizeVariable($request->stage_id);
            $step_id      = sanitizeVariable($request->step_id);
        }
        $steps        = StageCode::where('module_id', $module_id)->where('submodule_id', $component_id)->where('stage_id', $stage_id)->get();
        DB::beginTransaction();
        try {
            foreach ($steps as $step) {
                $step_name             = $step->description;
                $step_name = preg_replace('/[^A-Za-z0-9\-]/', '', trim($step_name));
                $step_name_trimmed     = str_replace(' ', '_', trim($step_name));
                if (!$request->$step_name_trimmed) {
                    continue;
                }
                $request_step_data     = sanitizeVariable($request->$step_name_trimmed);
                $template_id           = $request_step_data['template_id'];
                CallWrap::where('patient_id', $patient_id)->where('template_type', 'qs' . $template_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->delete();
            }
            CallWrap::where('patient_id', $patient_id)->where('template_type', 'qs0')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->delete();
            $last_sub_sequence = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', $sequence)->max('sub_sequence');
            $new_sub_sequence = $last_sub_sequence + 1;
            if (isset($request->patient_relationship_building['question'])) {
                $buildingdata = array(
                    'contact_via'   => 'questionnaire',
                    'template_type' => '0',
                    'uid'           => $uid,
                    'module_id'     => $module_id,
                    'component_id'  => $component_id,
                    'template_id'   => 0,
                    'template'      => sanitizeVariable(json_encode($request->patient_relationship_building['question'])),
                    'monthly_notes' => '',
                    'stage_id'      => 0,
                    'stage_code'    => 0,
                    'created_by'    => session()->get('userid'),
                    'patient_id'    => $patient_id
                );
                QuestionnaireTemplatesUsageHistory::create($buildingdata);
                foreach ($request->patient_relationship_building['question'] as $buildingkey => $buildingvalue) {
                    $buildingnotes = array(
                        'uid'                 => $uid,
                        'record_date'         => Carbon::now(),
                        'topic'               => 'Relationship Building : ' . $buildingkey,
                        'notes'               => sanitizeVariable($buildingvalue),
                        'created_by'          => session()->get('userid'),
                        'patient_id'          => $patient_id,
                        'template_type'       => 'qs0',
                        'sequence'            => $sequence,
                        'sub_sequence'        => $new_sub_sequence
                    );
                    if ($buildingvalue != '') {
                        CallWrap::create($buildingnotes);
                        $new_sub_sequence++;
                    }
                }
            }
            foreach ($steps as $step) {
                $new_stage_id          = $step->stage_id;
                $steps_id              = $step->id;
                $step_name             = $step->description;
                $step_name = preg_replace('/[^A-Za-z0-9\-]/', '', trim($step_name));
                $step_name_trimmed     = str_replace(' ', '_', trim($step_name));
                if (!$request->$step_name_trimmed) {
                    continue;
                }
                $request_step_data     = sanitizeVariable($request->$step_name_trimmed);
                $template_id           = $request_step_data['template_id'];
                $template_type         = $request_step_data['template_type_id'];
                $current_monthly_notes = $request_step_data['current_monthly_notes'];
                $data = array(
                    'contact_via'   => 'questionnaire',
                    'template_type' => $template_type,
                    'uid'           => $uid,
                    'module_id'     => $module_id,
                    'component_id'  => $component_id,
                    'template_id'   => $template_id,
                    'template'      => sanitizeVariable(json_encode($request_step_data['question'])),
                    'monthly_notes' => $current_monthly_notes,
                    'stage_id'      => $new_stage_id,
                    'stage_code'    => $steps_id,
                    'created_by'    => session()->get('userid'),
                    'patient_id'    => $patient_id
                );
                foreach ($request_step_data['question'] as $key => $value) {
                    $checkboxVal = '';
                    if (is_array($value)) {
                        foreach ($value as $k  => $v) {
                            if ($v) {
                                $checkboxVal = $checkboxVal . str_replace('_', ' ', $k) . ',';
                            }
                        }
                        $value = substr($checkboxVal, 0, -1);
                    }
                    $tp = str_replace('_', ' ', $key);
                    if (str_replace('_', ' ', $key) == "score") {
                        $tp = $step_name . ' ' . str_replace('_', ' ', $key);
                    }
                    $notes = array(
                        'uid'                 => $uid,
                        'record_date'         => Carbon::now(),
                        'topic'               => $tp,
                        'notes'               => $value,
                        'created_by'          => session()->get('userid'),
                        'patient_id'          => $patient_id,
                        'template_type'       => 'qs' . $template_id,
                        'sequence'            => $sequence,
                        'sub_sequence'        => $new_sub_sequence
                    );
                    if ($value != '') {
                        CallWrap::create($notes);
                        $new_sub_sequence++;
                    }
                }
                $notes1 = array(
                    'uid'                 => $uid,
                    'record_date'         => Carbon::now(),
                    'topic'               => $step_name . " Notes",
                    'notes'               => $current_monthly_notes,
                    'created_by'          => session()->get('userid'),
                    'patient_id'          => $patient_id,
                    'template_type'       => 'qs' . $template_id,
                    'sequence'            => $sequence,
                    'sub_sequence'        => $new_sub_sequence
                );
                if ($current_monthly_notes != '') {
                    CallWrap::create($notes1);
                }
                $insert_query = QuestionnaireTemplatesUsageHistory::create($data);
                //$record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name);
            }
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name, $form_start_time, $form_save_time);
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }


    public function  SaveCallClose(CallCloseAddRequest $request)
    { //dd($request);
        $next_month_date     = sanitizeVariable($request->q2_datetime);
        $start_time          = sanitizeVariable($request->start_time);
        $end_time            = sanitizeVariable($request->end_time);
        $patient_id          = sanitizeVariable($request->patient_id);
        $uid                 = sanitizeVariable($request->uid);
        $module_id           = sanitizeVariable($request->module_id);
        $component_id        = sanitizeVariable($request->component_id);
        $stage_id            = sanitizeVariable($request->stage_id);
        $step_id             = sanitizeVariable($request->step_id);
        $form_name           = sanitizeVariable($request->form_name);
        $query1              = sanitizeVariable($request->query1);
        $q1_notes            = sanitizeVariable($request->q1_notes);
        $query2              = sanitizeVariable($request->query2);
        $q2_time             = sanitizeVariable($request->q2_time);
        $q2_datetime         = sanitizeVariable($request->q2_datetime);
        $q2_notes            = sanitizeVariable($request->q2_notes);
        $mon_0               = sanitizeVariable($request->mon_0);
        $mon_1               = sanitizeVariable($request->mon_1);
        $mon_2               = sanitizeVariable($request->mon_2);
        $mon_3               = sanitizeVariable($request->mon_3);
        $mon_any             = sanitizeVariable($request->mon_any);
        $tue_0               = sanitizeVariable($request->tue_0);
        $tue_1               = sanitizeVariable($request->tue_1);
        $tue_2               = sanitizeVariable($request->tue_2);
        $tue_3               = sanitizeVariable($request->tue_3);
        $tue_any             = sanitizeVariable($request->tue_any);
        $wed_0               = sanitizeVariable($request->wed_0);
        $wed_1               = sanitizeVariable($request->wed_1);
        $wed_2               = sanitizeVariable($request->wed_2);
        $wed_3               = sanitizeVariable($request->wed_3);
        $wed_any             = sanitizeVariable($request->wed_any);
        $thu_0               = sanitizeVariable($request->thu_0);
        $thu_1               = sanitizeVariable($request->thu_1);
        $thu_2               = sanitizeVariable($request->thu_2);
        $thu_3               = sanitizeVariable($request->thu_3);
        $thu_any             = sanitizeVariable($request->thu_any);
        $fri_0               = sanitizeVariable($request->fri_0);
        $fri_1               = sanitizeVariable($request->fri_1);
        $fri_2               = sanitizeVariable($request->fri_2);
        $fri_3               = sanitizeVariable($request->fri_3);
        $fri_any             = sanitizeVariable($request->fri_any);
        $billable            = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        $current_month_date  = date('Y-m-d');
        DB::beginTransaction();
        try {
            $check_id   = CallClose::where('patient_id', $patient_id)->where('component_id', $component_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->exists();
            $data = array(
                'uid'          => $uid,
                'rec_date'     => Carbon::now(),
                'query1'       => $query1,
                'q1_notes'     => $q1_notes,
                'query2'       => $query2,
                'q2_time'      => $q2_time,
                'q2_datetime'  => ($q2_datetime != '') ? date("Y-m-d H:i:s", strtotime($q2_datetime . ' ' . $q2_time)) : null,
                'q2_notes'     => $q2_notes,
                'updated_by'   => session()->get('userid'),
                'module_id'    => $module_id,
                'component_id' => $component_id,
                'patient_id'   => $patient_id,
            );
            //record time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $request->patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            if (isset($q2_datetime) && ($q2_datetime != "")) {
                $to_do = array(
                    'uid'                         => $uid,
                    'module_id'                   => $module_id,
                    'component_id'                => $component_id,
                    'stage_id'                    => $stage_id,
                    'step_id'                     => $step_id,
                    'task_notes'                  => "Next month call",
                    'task_date'                   => ($q2_datetime != '') ? date("Y-m-d H:i:s", strtotime($q2_datetime . ' ' . $q2_time)) : null,
                    'task_time'                   => $q2_time,
                    'assigned_to'                 => session()->get('userid'),
                    'assigned_on'                 => Carbon::now(),
                    'status'                      => 'Pending',
                    'status_flag'                 => 0,
                    'created_by'                  => session()->get('userid'),
                    'patient_id'                  => $patient_id
                );
                $insert = ToDoList::create($to_do);
            }
            if ($check_id == true) {
                $data['updated_by'] = session()->get('userid');
                $update_query = CallClose::where('patient_id', $patient_id)->where('component_id', $component_id)
                    ->whereMonth('updated_at', date('m'))
                    ->whereYear('updated_at', date('Y'))
                    ->orderBy('id', 'desc')->first()->update($data);
            } else {
                $data['created_by'] = session()->get('userid');
                $insert_query = CallClose::create($data);
            }
            $patient_contact_time_data = array(
                'mon_0'                 => $mon_0 == null ? 0 : $mon_0,
                'mon_1'                 => $mon_1 == null ? 0 : $mon_1,
                'mon_2'                 => $mon_2 == null ? 0 : $mon_2,
                'mon_3'                 => $mon_3 == null ? 0 : $mon_3,
                'mon_any'               => $mon_any == null ? 0 : $mon_any,
                'tue_0'                 => $tue_0 == null ? 0 : $tue_0,
                'tue_1'                 => $tue_1 == null ? 0 : $tue_1,
                'tue_2'                 => $tue_2 == null ? 0 : $tue_2,
                'tue_3'                 => $tue_3 == null ? 0 : $tue_3,
                'tue_any'               => $tue_any == null ? 0 : $tue_any,
                'wed_0'                 => $wed_0 == null ? 0 : $wed_0,
                'wed_1'                 => $wed_1 == null ? 0 : $wed_1,
                'wed_2'                 => $wed_2 == null ? 0 : $wed_2,
                'wed_3'                 => $wed_3 == null ? 0 : $wed_3,
                'wed_any'               => $wed_any == null ? 0 : $wed_any,
                'thu_0'                 => $thu_0 == null ? 0 : $thu_0,
                'thu_1'                 => $thu_1 == null ? 0 : $thu_1,
                'thu_2'                 => $thu_2 == null ? 0 : $thu_2,
                'thu_3'                 => $thu_3 == null ? 0 : $thu_3,
                'thu_any'               => $thu_any == null ? 0 : $thu_any,
                'fri_0'                 => $fri_0 == null ? 0 : $fri_0,
                'fri_1'                 => $fri_1 == null ? 0 : $fri_1,
                'fri_2'                 => $fri_2 == null ? 0 : $fri_2,
                'fri_3'                 => $fri_3 == null ? 0 : $fri_3,
                'fri_any'               => $fri_any == null ? 0 : $fri_any,
            );
            $patientcontact = PatientContactTime::where('patient_id', $patient_id)->get();
            if (count($patientcontact) > 0) {
                $patient_contact_time_data['updated_by'] = session()->get('userid');
                PatientContactTime::where('patient_id', $patient_id)->update($patient_contact_time_data);
            } else {
                $patient_contact_time_data['patient_id'] = $patient_id;
                $patient_contact_time_data['created_by'] = session()->get('userid');
                $patient_contact_time_data['updated_by'] = session()->get('userid');
                PatientContactTime::create($patient_contact_time_data);
            }
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    // public function SaveCallWrapUp(CallwrapAddRequest $request) {
    //     $uid                 = sanitizeVariable($request->uid);
    //     $patient_id          = sanitizeVariable($request->patient_id);
    //     $sequence            = 5;
    //     $emr_entry_completed = empty(sanitizeVariable($request->emr_entry_completed)) ?'0' : sanitizeVariable($request->emr_entry_completed);
    //     $emr_monthly_summary = sanitizeVariable($request->emr_monthly_summary);
    //     $emr_monthly_summary_date = sanitizeVariable($request->emr_monthly_summary_date);
    //     //record time
    //     $start_time          = sanitizeVariable($request->start_time);
    //     $end_time            = sanitizeVariable($request->end_time);
    //     $module_id           = sanitizeVariable($request->module_id);
    //     $component_id        = sanitizeVariable($request->component_id);
    //     $stage_id            = sanitizeVariable($request->stage_id);
    //     $step_id             = sanitizeVariable($request->step_id);
    //     $form_name           = sanitizeVariable($request->form_name);
    //     $schedule_office_appointment = sanitizeVariable($request->schedule_office_appointment);
    //     $resources_for_medication = sanitizeVariable($request->resources_for_medication);
    //     $medical_renewal = sanitizeVariable($request->medical_renewal);
    //     $called_office_patientbehalf = sanitizeVariable($request->called_office_patientbehalf);
    //     $referral_support = sanitizeVariable($request->referral_support);
    //     $no_other_services = sanitizeVariable($request->no_other_services);
    //     $currentmonth = date('m');
    //     $currentyear  = date('Y');
    //     $record_date  = Carbon::now();
    //     $billable            = 1;
    //     $last_sub_sequence   = CallWrap::where('patient_id',$patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', $sequence)->max('sub_sequence');
    //     $new_sub_sequence    = $last_sub_sequence + 1;
    //     DB::beginTransaction();
    //     try {
    //        $v = 'Summary notes added on';
    //        $c= CallWrap::where('patient_id', $patient_id)->where('topic', 'EMR Monthly Summary')->orWhere('topic', 'like', $v.'%')
    //             ->whereMonth('created_at', date('m'))
    //             ->whereYear('created_at', date('Y'))
    //             ->delete();
    //         foreach($emr_monthly_summary as $key => $emr_monthly_summary_notes){
    //             if($key==0){
    //                 $emr_monthly_summary_data = array(
    //                     'uid'                       => $uid,
    //                     'record_date'               => Carbon::now(),
    //                     'topic'                     => 'EMR Monthly Summary',
    //                     'notes'                     => $emr_monthly_summary_notes,
    //                     'emr_entry_completed'       => $emr_entry_completed,
    //                     'emr_monthly_summary'       => $emr_monthly_summary_notes,
    //                     'created_by'                => session()->get('userid') ,
    //                     'patient_id'                => $patient_id,
    //                 );
    //             }else{
    //                 $d= explode("-",$emr_monthly_summary_date[$key-1]);
    //                 $summary='Summary notes added on '.$d[1]."-".$d[2]."-".$d[0];
    //                 $emr_monthly_summary_data = array(
    //                     'uid'                       => $uid,
    //                     'record_date'               => Carbon::now(),
    //                     'topic'                     => $summary,
    //                     'notes'                     => $emr_monthly_summary_notes,
    //                     'emr_entry_completed'       => $emr_entry_completed,
    //                     'emr_monthly_summary'       => $emr_monthly_summary_notes,
    //                     'created_by'                => session()->get('userid') ,
    //                     'patient_id'                => $patient_id,
    //                     'emr_monthly_summary_date'  => $emr_monthly_summary_date[$key-1]
    //                 );
    //             }
    //         $emr_monthly_summary_data['sequence']     = $sequence;
    //         $emr_monthly_summary_data['sub_sequence'] = $new_sub_sequence;
    //         CallWrap::create($emr_monthly_summary_data);
    //     }
    //     $d = array(
    //             'emr_entry_completed' => $emr_entry_completed,
    //             'schedule_office_appointment'  => $schedule_office_appointment,
    //             'resources_for_medication' => $resources_for_medication,
    //             'medical_renewal' => $medical_renewal,
    //             'called_office_patientbehalf' => $called_office_patientbehalf,
    //             'referral_support' => $referral_support,
    //             'no_other_services' => $no_other_services,
    //             'created_by' => session()->get('userid'),
    //             'updated_by' => session()->get('userid'),
    //             'record_date' => $record_date,
    //             'patient_id'  => $patient_id
    //         );

    //         $check =  CallWrapupChecklist::where('patient_id',$patient_id)->whereMonth('record_date',$currentmonth)->whereYear('record_date',$currentyear)->exists();
    //         if($check==true){
    //             CallWrapupChecklist::where('patient_id',$patient_id)->whereDate('record_date', '=', $record_date)->update($d);
    //         }else{
    //             CallWrapupChecklist::create($d);
    //         }
    //         $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name);
    //         DB::commit();
    //     } catch(\Exception $ex) {
    //         DB::rollBack();
    //         return $ex;
    //         return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
    //     }
    // }

    public function emrSummary(Request $request)
    {
        $uid                 = sanitizeVariable($request->uid);
        $patient_id          = sanitizeVariable($request->patient_id);
        $sequence            = 5;
        $emr_entry_completed = empty(sanitizeVariable($request->emr_entry_completed)) ? '0' : sanitizeVariable($request->emr_entry_completed);
        $emr_monthly_summary = sanitizeVariable($request->emr_monthly_summary);
        $emr_monthly_summary_date = sanitizeVariable($request->emr_monthly_summary_date);
        //record time
        $start_time          = sanitizeVariable($request->start_time);
        $end_time            = sanitizeVariable($request->end_time);
        $module_id           = sanitizeVariable($request->module_id);
        $component_id        = sanitizeVariable($request->component_id);
        $stage_id            = sanitizeVariable($request->stage_id);
        $step_id             = sanitizeVariable($request->step_id);
        $form_name           = sanitizeVariable($request->form_name);

        $currentmonth = date('m');
        $currentyear  = date('Y');
        $record_date  = Carbon::now();
        $billable            = 1;
        $last_sub_sequence   = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', $sequence)->max('sub_sequence');
        $new_sub_sequence    = $last_sub_sequence + 1;

        $routine_response = sanitizeVariable($request->routine_response);
        $urgent_emergent_response = sanitizeVariable($request->urgent_emergent_response);
        $referral_order_support = sanitizeVariable($request->referral_order_support);
        $medication_support = sanitizeVariable($request->medication_support);
        $verbal_education_review_with_patient = sanitizeVariable($request->verbal_education_review_with_patient);
        $mailed_documents = sanitizeVariable($request->mailed_documents);
        $resource_support = sanitizeVariable($request->resource_support);
        $veterans_services = sanitizeVariable($request->veterans_services);
        $authorized_cm_only = sanitizeVariable($request->authorized_cm_only);
        $no_additional_services_provided = sanitizeVariable($request->no_additional_services_provided);

        $routineresponse = sanitizeVariable($request->routineresponse);
        $urgentemergentresponse = sanitizeVariable($request->urgentemergentresponse);
        $referralordersupport = sanitizeVariable($request->referralordersupport);
        $medicationsupport = sanitizeVariable($request->medicationsupport);
        $verbaleducationreviewwithpatient = sanitizeVariable($request->verbaleducationreviewwithpatient);
        $maileddocuments = sanitizeVariable($request->maileddocuments);
        $resourcesupport = sanitizeVariable($request->resourcesupport);
        $veteransservices = sanitizeVariable($request->veteransservices);
        $authorizedcmonly = sanitizeVariable($request->authorizedcmonly);

        $servicesdata1 = '';
        $servicesdata2 = '';
        $servicesdata3 = '';
        $servicesdata4 = '';
        $servicesdata5 = '';
        $servicesdata6 = '';
        $servicesdata7 = '';
        $servicesdata8 = '';
        $servicesdata9 = '';
        $servicesdata10 = '';

        $additionalservices1 = '';
        $additionalservices2 = '';
        $additionalservices3 = '';
        $additionalservices4 = '';
        $additionalservices5 = '';
        $additionalservices6 = '';
        $additionalservices7 = '';
        $additionalservices8 = '';
        $additionalservices9 = '';
        $additionalservices10 = '';

        DB::beginTransaction();
        try {


            $v = 'Summary notes added on';

            $c = CallWrap::where('patient_id', $patient_id)
                ->whereMonth('created_at',  date('m'))
                ->whereYear('created_at',  date('Y'))
                ->where(function ($query) use ($v) {
                    $query->where('topic', 'EMR Monthly Summary')->orWhere('topic', 'like', $v . '%');
                })->update([
                    'status' => 0,
                    'updated_at' => Carbon::now()
                ]);

            $e =    EmrMonthlySummary::where('patient_id', $patient_id)
                ->whereMonth('created_at', date('m'))
                ->whereYear('created_at',  date('Y'))
                ->where(function ($query) use ($v) {
                    $query->where('topic', 'EMR Monthly Summary')->orWhere('topic', 'like', $v . '%');
                })->update([
                    'status' => 0,
                    'updated_at' => Carbon::now()
                ]);
            foreach ($emr_monthly_summary as $key => $emr_monthly_summary_notes) {
                if ($key == 0) {
                    $emr_monthly_summary_data = array(
                        'uid'                       => $uid,
                        'record_date'               => Carbon::now(),
                        'topic'                     => 'EMR Monthly Summary',
                        'notes'                     => $emr_monthly_summary_notes,
                        'emr_entry_completed'       => $emr_entry_completed,
                        'created_by'                => session()->get('userid'),
                        'patient_id'                => $patient_id,
                        'sequence'                  => $sequence,
                        'sub_sequence'              => $new_sub_sequence

                    );
                    $monthlydate =  Carbon::now();
                    $emr_type = 1;
                    // $is_old_emr = 1;

                } else {
                    $d = explode("-", $emr_monthly_summary_date[$key - 1]);
                    $summary = 'Summary notes added on ' . $d[1] . "-" . $d[2] . "-" . $d[0];
                    $emr_monthly_summary_data = array(
                        'uid'                       => $uid,
                        'record_date'               => Carbon::now(),
                        'topic'                     => $summary,
                        'notes'                     => $emr_monthly_summary_notes,
                        'emr_entry_completed'       => $emr_entry_completed,
                        'created_by'                => session()->get('userid'),
                        'patient_id'                => $patient_id,
                        'sequence'                  => $sequence,
                        'sub_sequence'              => $new_sub_sequence

                    );

                    $currentdatetime =  Carbon::now();
                    $dt1 = DatesTimezoneConversion::userTimeStamp($currentdatetime);
                    $datetimearray = explode(" ", $dt1);
                    $currenttime = $datetimearray[1];
                    $monthlydate = $emr_monthly_summary_date[$key - 1] . " " . $currenttime;
                    $emr_type = 2;
                }

                /*******ccm-emr-monthly-summarytable-start************/

                $emr_monthly_summary_data['record_date'] = $monthlydate;
                $emr_monthly_summary_data['status'] = 1;
                $emr_monthly_summary_data['emr_type'] = $emr_type;

                $e = EmrMonthlySummary::create($emr_monthly_summary_data);


                /*******ccm-emr-monthly-summarytable-end************/


                $emr_monthly_summary_data['uid']     = $uid;
                $emr_monthly_summary_data['emr_monthly_summary'] = $emr_monthly_summary_notes;
                $emr_monthly_summary_data['emr_monthly_summary_date']     = $monthlydate;

                //some fields are added seperately bcz these fields are not present in ccm_emr_monthly_summary

                CallWrap::create($emr_monthly_summary_data);
            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return $ex;
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    //please donot remove this function = created and modified ashwini 19th sept 2022
    // public function emrSummary(Request $request){
    //     $uid                 = sanitizeVariable($request->uid);
    //         $patient_id          = sanitizeVariable($request->patient_id);
    //         $sequence            = 5;
    //         $emr_entry_completed = empty(sanitizeVariable($request->emr_entry_completed)) ?'0' : sanitizeVariable($request->emr_entry_completed);
    //         $emr_monthly_summary = sanitizeVariable($request->emr_monthly_summary);
    //         $emr_monthly_summary_date = sanitizeVariable($request->emr_monthly_summary_date);
    //         //record time
    //         $start_time          = sanitizeVariable($request->start_time);
    //         $end_time            = sanitizeVariable($request->end_time);
    //         $module_id           = sanitizeVariable($request->module_id);
    //         $component_id        = sanitizeVariable($request->component_id);
    //         $stage_id            = sanitizeVariable($request->stage_id);
    //         $step_id             = sanitizeVariable($request->step_id);
    //         $form_name           = sanitizeVariable($request->form_name);

    //         $currentmonth = date('m');
    //         $currentyear  = date('Y');
    //         $record_date  = Carbon::now();
    //         $billable            = 1;
    //         $last_sub_sequence   = CallWrap::where('patient_id',$patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', $sequence)->max('sub_sequence');
    //         $new_sub_sequence    = $last_sub_sequence + 1;

    //         $routine_response = sanitizeVariable($request->routine_response);
    //         $urgent_emergent_response = sanitizeVariable($request->urgent_emergent_response);
    //         $referral_order_support = sanitizeVariable($request->referral_order_support);
    //         $medication_support = sanitizeVariable($request->medication_support);
    //         $verbal_education_review_with_patient = sanitizeVariable($request->verbal_education_review_with_patient);
    //         $mailed_documents = sanitizeVariable($request->mailed_documents);
    //         $resource_support = sanitizeVariable($request->resource_support);
    //         $veterans_services = sanitizeVariable($request->veterans_services);
    //         $authorized_cm_only = sanitizeVariable($request->authorized_cm_only);
    //         $no_additional_services_provided = sanitizeVariable($request->no_additional_services_provided);

    //         $routineresponse = sanitizeVariable($request->routineresponse);
    //         $urgentemergentresponse = sanitizeVariable($request->urgentemergentresponse);
    //         $referralordersupport = sanitizeVariable($request->referralordersupport);
    //         $medicationsupport = sanitizeVariable($request->medicationsupport);
    //         $verbaleducationreviewwithpatient = sanitizeVariable($request->verbaleducationreviewwithpatient);
    //         $maileddocuments = sanitizeVariable($request->maileddocuments);
    //         $resourcesupport = sanitizeVariable($request->resourcesupport);
    //         $veteransservices = sanitizeVariable($request->veteransservices);
    //         $authorizedcmonly = sanitizeVariable($request->authorizedcmonly);

    //         $servicesdata1 = '';
    //         $servicesdata2 = '';
    //         $servicesdata3 = '';
    //         $servicesdata4 = '';
    //         $servicesdata5 = '';
    //         $servicesdata6 = '';
    //         $servicesdata7 = '';
    //         $servicesdata8 = '';
    //         $servicesdata9 = '';
    //         $servicesdata10 = '';

    //         $additionalservices1 = '';
    //         $additionalservices2 = '';
    //         $additionalservices3 = '';
    //         $additionalservices4 = '';
    //         $additionalservices5 = '';
    //         $additionalservices6 = '';
    //         $additionalservices7 = '';
    //         $additionalservices8 = '';
    //         $additionalservices9 = '';
    //         $additionalservices10 = '';

    //         DB::beginTransaction();
    //         try {


    //            $v = 'Summary notes added on';

    //            $c= CallWrap::where('patient_id', $patient_id)
    //                 ->whereMonth('created_at',  date('m'))
    //                 ->whereYear('created_at',  date('Y'))
    //                 ->where(function ($query) use ($v){
    //                     $query->where('topic', 'EMR Monthly Summary')->orWhere('topic', 'like', $v.'%');
    //                 })->update([
    //                     'status' => 0,
    //                     'updated_at' =>Carbon::now()
    //                 ]);

    //             $e =    EmrMonthlySummary::where('patient_id', $patient_id)
    //                     ->whereMonth('created_at', date('m'))
    //                     ->whereYear('created_at',  date('Y'))
    //                     ->where(function ($query) use ($v){
    //                         $query->where('topic', 'EMR Monthly Summary')->orWhere('topic', 'like', $v.'%');
    //                     })->update([
    //                         'status' => 0,
    //                         'updated_at' =>Carbon::now()
    //                     ]);
    //                     foreach($emr_monthly_summary as $key => $emr_monthly_summary_notes)
    //                     {
    //                         if($key==0){
    //                             $emr_monthly_summary_data = array(
    //                                 'uid'                       => $uid,
    //                                 'record_date'               => Carbon::now(),
    //                                 'topic'                     => 'EMR Monthly Summary',
    //                                 'notes'                     => $emr_monthly_summary_notes,
    //                                 'emr_entry_completed'       => $emr_entry_completed,
    //                                 'created_by'                => session()->get('userid') ,
    //                                 'patient_id'                => $patient_id,
    //                                 'sequence'                  => $sequence,
    //                                 'sub_sequence'              => $new_sub_sequence

    //                             );
    //                             $monthlydate =  Carbon::now();
    //                             $emr_type = 1;
    //                             // $is_old_emr = 1;

    //                         }else{
    //                             $d= explode("-",$emr_monthly_summary_date[$key-1]);
    //                             $summary='Summary notes added on '.$d[1]."-".$d[2]."-".$d[0];
    //                             $emr_monthly_summary_data = array(
    //                                 'uid'                       => $uid,
    //                                 'record_date'               => Carbon::now(),
    //                                 'topic'                     => $summary,
    //                                 'notes'                     => $emr_monthly_summary_notes,
    //                                 'emr_entry_completed'       => $emr_entry_completed,
    //                                 'created_by'                => session()->get('userid'),
    //                                 'patient_id'                => $patient_id,
    //                                 'sequence'                  => $sequence,
    //                                 'sub_sequence'              => $new_sub_sequence

    //                             );

    //                             $currentdatetime =  Carbon::now();
    //                             $dt1 = DatesTimezoneConversion::userTimeStamp($currentdatetime);
    //                             $datetimearray = explode(" ", $dt1);
    //                             $currenttime = $datetimearray[1];
    //                             $monthlydate = $emr_monthly_summary_date[$key-1]." ".$currenttime;
    //                             $emr_type = 2;

    //                         }

    //                          /*******ccm-emr-monthly-summarytable-start************/

    //                          $emr_monthly_summary_data['record_date'] = $monthlydate;
    //                          $emr_monthly_summary_data['status'] = 1;
    //                          $emr_monthly_summary_data['emr_type'] = $emr_type;

    //                          $e = EmrMonthlySummary::create($emr_monthly_summary_data);


    //                           /*******ccm-emr-monthly-summarytable-end************/


    //                         $emr_monthly_summary_data['uid']     = $uid;
    //                         $emr_monthly_summary_data['emr_monthly_summary'] = $emr_monthly_summary_notes;
    //                         $emr_monthly_summary_data['emr_monthly_summary_date']     = $monthlydate;

    //                         //some fields are added seperately bcz these fields are not present in ccm_emr_monthly_summary

    //                         CallWrap::create($emr_monthly_summary_data);

    //                     }

    //                     DB::commit();
    //                 } catch(\Exception $ex) {
    //                     DB::rollBack();
    //                     return $ex;
    //                     return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
    //                 }

    // }

    public function SaveCallWrapUp(CallwrapAddRequest $request)
    {
        $uid                 = sanitizeVariable($request->uid);
        $patient_id          = sanitizeVariable($request->patient_id);
        $sequence            = 5;
        $emr_entry_completed = empty(sanitizeVariable($request->emr_entry_completed)) ? '0' : sanitizeVariable($request->emr_entry_completed);
        $emr_monthly_summary = sanitizeVariable($request->emr_monthly_summary);
        $emr_monthly_summary_date = sanitizeVariable($request->emr_monthly_summary_date);
        //record time
        $start_time          = sanitizeVariable($request->start_time);
        $end_time            = sanitizeVariable($request->end_time);
        $module_id           = sanitizeVariable($request->module_id);
        $component_id        = sanitizeVariable($request->component_id);
        $stage_id            = sanitizeVariable($request->stage_id);
        $step_id             = sanitizeVariable($request->step_id);
        $form_name           = sanitizeVariable($request->form_name);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);

        $currentmonth = date('m');
        $currentyear  = date('Y');
        $record_date  = Carbon::now();
        $billable            = 1;
        $last_sub_sequence   = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', $sequence)->max('sub_sequence');
        $new_sub_sequence    = $last_sub_sequence + 1;

        $routine_response = sanitizeVariable($request->routine_response);
        $urgent_emergent_response = sanitizeVariable($request->urgent_emergent_response);
        $referral_order_support = sanitizeVariable($request->referral_order_support);
        $medication_support = sanitizeVariable($request->medication_support);
        $verbal_education_review_with_patient = sanitizeVariable($request->verbal_education_review_with_patient);
        $mailed_documents = sanitizeVariable($request->mailed_documents);
        $resource_support = sanitizeVariable($request->resource_support);
        $veterans_services = sanitizeVariable($request->veterans_services);
        $authorized_cm_only = sanitizeVariable($request->authorized_cm_only);
        $no_additional_services_provided = sanitizeVariable($request->no_additional_services_provided);

        $routineresponse = sanitizeVariable($request->routineresponse);
        $urgentemergentresponse = sanitizeVariable($request->urgentemergentresponse);
        $referralordersupport = sanitizeVariable($request->referralordersupport);
        $medicationsupport = sanitizeVariable($request->medicationsupport);
        $verbaleducationreviewwithpatient = sanitizeVariable($request->verbaleducationreviewwithpatient);
        $maileddocuments = sanitizeVariable($request->maileddocuments);
        $resourcesupport = sanitizeVariable($request->resourcesupport);
        $veteransservices = sanitizeVariable($request->veteransservices);
        $authorizedcmonly = sanitizeVariable($request->authorizedcmonly);

        $servicesdata1 = '';
        $servicesdata2 = '';
        $servicesdata3 = '';
        $servicesdata4 = '';
        $servicesdata5 = '';
        $servicesdata6 = '';
        $servicesdata7 = '';
        $servicesdata8 = '';
        $servicesdata9 = '';
        $servicesdata10 = '';

        $additionalservices1 = '';
        $additionalservices2 = '';
        $additionalservices3 = '';
        $additionalservices4 = '';
        $additionalservices5 = '';
        $additionalservices6 = '';
        $additionalservices7 = '';
        $additionalservices8 = '';
        $additionalservices9 = '';
        $additionalservices10 = '';

        DB::beginTransaction();
        try {


            $v = 'Summary notes added on';

            $c = CallWrap::where('patient_id', $patient_id)
                ->whereMonth('created_at',  date('m'))
                ->whereYear('created_at',  date('Y'))
                ->where(function ($query) use ($v) {
                    $query->where('topic', 'EMR Monthly Summary')->orWhere('topic', 'like', $v . '%');
                })->update([
                    'status' => 0,
                    'updated_at' => Carbon::now()
                ]);

            $e =    EmrMonthlySummary::where('patient_id', $patient_id)
                ->whereMonth('created_at', date('m'))
                ->whereYear('created_at',  date('Y'))
                ->where(function ($query) use ($v) {
                    $query->where('topic', 'EMR Monthly Summary')->orWhere('topic', 'like', $v . '%');
                })->update([
                    'status' => 0,
                    'updated_at' => Carbon::now()
                ]);


            foreach ($emr_monthly_summary as $key => $emr_monthly_summary_notes) {
                if ($key == 0) {
                    $emr_monthly_summary_data = array(
                        'uid'                       => $uid,
                        'record_date'               => Carbon::now(),
                        'topic'                     => 'EMR Monthly Summary',
                        'notes'                     => $emr_monthly_summary_notes,
                        'emr_entry_completed'       => $emr_entry_completed,
                        'created_by'                => session()->get('userid'),
                        'patient_id'                => $patient_id,
                        'sequence'                  => $sequence,
                        'sub_sequence'              => $new_sub_sequence

                    );
                    $monthlydate =  Carbon::now();
                    $emr_type = 1;
                    // $is_old_emr = 1;

                } else {
                    $d = explode("-", $emr_monthly_summary_date[$key - 1]);
                    $summary = 'Summary notes added on ' . $d[1] . "-" . $d[2] . "-" . $d[0];
                    $emr_monthly_summary_data = array(
                        'uid'                       => $uid,
                        'record_date'               => Carbon::now(),
                        'topic'                     => $summary,
                        'notes'                     => $emr_monthly_summary_notes,
                        'emr_entry_completed'       => $emr_entry_completed,
                        'created_by'                => session()->get('userid'),
                        'patient_id'                => $patient_id,
                        'sequence'                  => $sequence,
                        'sub_sequence'              => $new_sub_sequence

                    );

                    $currentdatetime =  Carbon::now();
                    $dt1 = DatesTimezoneConversion::userTimeStamp($currentdatetime);
                    $datetimearray = explode(" ", $dt1);
                    $currenttime = $datetimearray[1];
                    $monthlydate = $emr_monthly_summary_date[$key - 1] . " " . $currenttime;
                    $emr_type = 2;
                }

                /*******ccm-emr-monthly-summarytable-start************/

                $emr_monthly_summary_data['record_date'] = $monthlydate;
                $emr_monthly_summary_data['status'] = 1;
                $emr_monthly_summary_data['emr_type'] = $emr_type;

                $e = EmrMonthlySummary::create($emr_monthly_summary_data);


                /*******ccm-emr-monthly-summarytable-end************/


                $emr_monthly_summary_data['uid']     = $uid;
                $emr_monthly_summary_data['emr_monthly_summary'] = $emr_monthly_summary_notes;
                $emr_monthly_summary_data['emr_monthly_summary_date']     = $monthlydate;

                //some fields are added seperately bcz these fields are not present in ccm_emr_monthly_summary

                CallWrap::create($emr_monthly_summary_data);
            }


            if ($routine_response == true) {
                foreach ($routineresponse as $key => $value) {
                    if ($value == 1) {
                        $s1 = str_replace('_', ' ', $key);
                        $servicesdata1 = $servicesdata1 . $s1 . ", ";
                    }
                }
                $additionalservices1 = "Routine Response:" . $servicesdata1 . ";";
            }
            if ($urgent_emergent_response == true) {
                foreach ($urgentemergentresponse as $key => $value) {
                    if ($value == 1) {
                        $s2 = str_replace('_', ' ', $key);
                        $servicesdata2 = $servicesdata2 . $s2 . ", ";
                    }
                }
                $additionalservices2 = "Urgent/Emergent Response:" . $servicesdata2 . ";";
            }
            if ($referral_order_support == true) {
                foreach ($referralordersupport as $key1 => $value1) {
                    if ($value1 == 1) {
                        $s3 = str_replace('_', ' ', $key1);
                        $servicesdata3 = $servicesdata3 . $s3 . ", ";
                    }
                }
                $additionalservices3 = "Referral/Order Support:" . $servicesdata3 . ";";
            }

            if ($medication_support == true) {
                foreach ($medicationsupport as $key => $value) {
                    if ($value == 1) {
                        $s4 = str_replace('_', ' ', $key);
                        $servicesdata4 = $servicesdata4 . $s4 . ", ";
                    }
                    $additionalservices5 = "Verbal Education/Review with Patient:" . $servicesdata5 . ";";
                }
                $additionalservices4 = "Medication Support:" . $servicesdata4 . ";";
            }
            if ($verbal_education_review_with_patient == true) {
                foreach ($verbaleducationreviewwithpatient as $key => $value) {
                    if ($value == 1) {
                        $s5 = str_replace('_', ' ', $key);
                        $servicesdata5 = $servicesdata5 . $s5 . ", ";
                    }
                    $additionalservices6 = "Mailed Documents:" . $servicesdata6 . ";";
                }
                $additionalservices5 = "Verbal Education/Review with Patient :" . $servicesdata5 . ";";
            }
            if ($mailed_documents == true) {
                foreach ($maileddocuments as $key => $value) {
                    if ($value == 1) {
                        $s6 = str_replace('_', ' ', $key);
                        $servicesdata6 = $servicesdata6 . $s6 . ", ";
                    }
                }
                $additionalservices6 = "Mailed Documents :" . $servicesdata6 . ";";
            }
            if ($resource_support == true) {
                foreach ($resourcesupport as $key => $value) {
                    if ($value == 1) {
                        $s7 = str_replace('_', ' ', $key);
                        $servicesdata7 = $servicesdata7 . $s7 . ", ";
                    }
                    $additionalservices7 = "Resource Support:" . $servicesdata7 . ";";
                }
                $additionalservices7 = "Resource Support :" . $servicesdata7 . ";";
            }


            if ($veterans_services == true) {
                foreach ($veteransservices as $key => $value) {
                    if ($value == 1) {
                        $s8 = str_replace('_', ' ', $key);
                        $servicesdata8 = $servicesdata8 . $s8 . ", ";
                    }

                    $additionalservices8 = "Veterans Services:" . $servicesdata8 . ";";
                }


                if ($authorized_cm_only == true) {
                    foreach ($authorizedcmonly as $key => $value) {
                        if ($value == 1) {
                            $s9 = str_replace('_', ' ', $key);
                            $servicesdata9 = $servicesdata9 . $s9 . ", ";
                        }
                    }
                    $additionalservices9 = "Authorized CM Only:" . $servicesdata9 . ";";
                }


                if ($no_additional_services_provided == true) {
                    $additionalservices10 = "No Additional Services Provided";
                    $servicedata =   $additionalservices10;
                } else {
                    $servicedata = $additionalservices1 . " " . $additionalservices2 . " " . $additionalservices3 . " " . $additionalservices4 . " " . $additionalservices5 . " " . $additionalservices6 . " " . $additionalservices7 . " " . $additionalservices8 . " " . $additionalservices9;
                }


                $additional_services_data = array(
                    'uid'                       => $uid,
                    'record_date'               => Carbon::now(),
                    'topic'                     => 'Additional Services:',
                    'notes'                     => $servicedata,
                    'created_by'                => session()->get('userid'),
                    'patient_id'                => $patient_id,
                    'status'                    => 1
                );

                $cd =  CallWrap::where('patient_id', $patient_id)
                    ->where('topic', 'like', 'Additional Services :%')
                    ->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'))
                    ->update(['status' => 0]);
                CallWrap::create($additional_services_data);
                $record_time  = CommonFunctionController::recordTimeSpent(
                    $start_time,
                    $end_time,
                    $patient_id,
                    $module_id,
                    $component_id,
                    $stage_id,
                    $billable,
                    $uid,
                    $step_id,
                    $form_name,
                    $form_start_time,
                    $form_save_time
                );
                if ($additionalservices6 != '') {
                    $form_name = $form_name . '_additional_services';
                    $check =  PatientTimeRecords::where('patient_id', $patient_id)
                        ->whereMonth('record_date', $currentmonth)->whereYear('record_date', $currentyear)
                        ->where('form_name', $form_name)->exists();
                    if ($check != true) {
                        // print_r($start_time .'====='. $end_time); die;
                        $start_time = "00:00:00";
                        $time2 = "00:04:00";
                        $st = strtotime("00-00-0000 00:00:00");
                        $et = strtotime("00-00-0000 00:04:00");
                        $form_start_time1 =  date("m-d-Y H:i:s", $st);
                        $form_save_time1 =  date("m-d-Y H:i:s", $et);
                        $secs = strtotime($time2) - strtotime($start_time);  //strtotime("00:00:00");
                        $end_time = date("H:i:s", strtotime($start_time) + $secs);
                        $record_time  = CommonFunctionController::recordTimeSpent(
                            $start_time,
                            $end_time,
                            $patient_id,
                            $module_id,
                            $component_id,
                            $stage_id,
                            $billable,
                            $uid,
                            $step_id,
                            $form_name,
                            $form_start_time1,
                            $form_save_time1
                        );
                    }
                }
                DB::commit();
                return response(['form_start_time' => $form_save_time]);
            }
            // catch (\Exception $ex) {
            // DB::rollBack();
            // // return $ex;
            //     return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
            // }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            // return $ex;
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }


    public function SaveFollowup(FollowupAddRequest $request)
    {
        $patient_id            = sanitizeVariable($request->input('patient_id'));
        $emr_complete          = empty(sanitizeVariable($request->emr_complete)) ? '0' : sanitizeVariable($request->emr_complete); //($request->emr_complete == false ) ? '0' : sanitizeVariable($request->emr_complete);
        $task_name             = sanitizeVariable($request->task_name);
        $followupmaster_task   = sanitizeVariable($request->followupmaster_task);
        $selected_task_name    = sanitizeVariable($request->selected_task_name);
        $notes                 = sanitizeVariable($request->notes);
        $task_date             = sanitizeVariable($request->task_date);
        $status_flag           = sanitizeVariable($request->status_flag);
        $start_time            = sanitizeVariable($request->start_time);
        $end_time              = sanitizeVariable($request->end_time);
        $uid                   = sanitizeVariable($request->uid);
        $module_id             = sanitizeVariable($request->module_id);
        $component_id          = sanitizeVariable($request->component_id);
        $stage_id              = sanitizeVariable($request->stage_id);
        $step_id               = sanitizeVariable($request->step_id);
        $form_name             = sanitizeVariable($request->form_name);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        $notes                 = sanitizeVariable($request->notes);
        $billable              = 1;
        $sequence              = 7;
        $last_sub_sequence     = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', $sequence)->max('sub_sequence');
        $new_sub_sequence      = $last_sub_sequence + 1;
        DB::beginTransaction();
        try {
            if ($followupmaster_task[0] != '' && $emr_complete == '1') {
                foreach ($followupmaster_task as $i => $value) {
                    if (!empty($status_flag[$i]) == '0') {
                        $status = 'Pending';
                        $task_completed_at = null;
                    }
                    if (!empty($status_flag[$i]) == '1') {
                        $status = 'Completed';
                        $task_completed_at = Carbon::now();
                    }
                    $to_do = array(
                        'uid'                         => $patient_id,
                        'module_id'                   => $module_id,
                        'component_id'                => $component_id,
                        'stage_id'                    => $stage_id,
                        'step_id'                     => $step_id,
                        'task_notes'                  => $task_name[$i],
                        'notes'                       => $notes[$i],
                        'assigned_to'                 => session()->get('userid'),
                        'task_date'                   => empty($task_date[$i]) ? null : $task_date[$i],
                        'assigned_on'                 => Carbon::now(),
                        'status'                      => $status,
                        'task_completed_at'           => $task_completed_at,
                        'select_task_category'        => $followupmaster_task[$i],
                        'status_flag'                 => isset($status_flag[$i]) ? $status_flag[$i] : '0',
                        'created_by'                  => session()->get('userid'),
                        'patient_id'                  => $patient_id
                    );
                    if ($value != "") {
                        $insert = ToDoList::create($to_do);
                        $last_insert_id = $insert->id;
                        if (!empty($status_flag[$i]) == '1') {
                            $status = 'Completed';
                            $task_completed_at = Carbon::now();
                            $callWrapUp = array(
                                'uid'                 => $patient_id,
                                'record_date'         => Carbon::now(),
                                'topic'               => 'Follow Up Task : ' . $task_name[$i] . ' - Created on ' . date("m-d-Y", strtotime($task_date[$i])) . ' - Scheduled on ' . date("m-d-Y", strtotime($task_date[$i])), //('.$selected_task_name[$i].')
                                'notes'               => $notes[$i],
                                'created_by'          => session()->get('userid'),
                                'update_by'           => session()->get('userid'),
                                'patient_id'          => $patient_id,
                                'sequence'            => $sequence,
                                'sub_sequence'        => $new_sub_sequence,
                                'task_id'             => $last_insert_id
                            );
                            CallWrap::create($callWrapUp);
                        }
                    } //end value if
                } //end foreach
                //checkbox array
                $data = array(
                    'uid'                 => $patient_id,
                    'rec_date'            => Carbon::now(),
                    'emr_complete'        => $emr_complete,
                    'patient_id'          => $patient_id,
                    'update_status'       => 1,
                );
                $check_id = FollowUp::where('patient_id', $patient_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->exists();
                if ($check_id == true) {
                    $data['updated_by'] = session()->get('userid');
                    $update_query = FollowUp::where('patient_id', $patient_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->orderBy('id', 'desc')->first()->update($data);
                } else {
                    $data['created_by'] = session()->get('userid');
                    $insert_query = FollowUp::create($data);
                }
                //record time
                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name, $form_start_time, $form_save_time);
            } else if ($followupmaster_task[0] != '' || $emr_complete == '1') {
                if ($followupmaster_task[0] != '') {
                    foreach ($followupmaster_task as $i => $value) {
                        if (!empty($status_flag[$i]) == '0') {
                            $status = 'Pending';
                            $task_completed_at = null;
                        }
                        if (!empty($status_flag[$i]) == '1') {
                            $status = 'Completed';
                            $task_completed_at = Carbon::now();
                        }
                        $to_do = array(
                            'uid'                         => $patient_id,
                            'module_id'                   => $module_id,
                            'component_id'                => $component_id,
                            'stage_id'                    => $stage_id,
                            'step_id'                     => $step_id,
                            'task_notes'                  => $task_name[$i],
                            'notes'                       => $notes[$i],
                            'assigned_to'                 => session()->get('userid'),
                            'task_date'                   => empty($task_date[$i]) ? null : $task_date[$i],
                            'assigned_on'                 => Carbon::now(),
                            'status'                      => $status,
                            'task_completed_at'           => $task_completed_at,
                            'select_task_category'        => $followupmaster_task[$i],
                            'status_flag'                 => isset($status_flag[$i]) ? $status_flag[$i] : '0',
                            'created_by'                  => session()->get('userid'),
                            'patient_id'                  => $patient_id
                        );
                        if ($value != "") {
                            $insert = ToDoList::create($to_do);
                            $last_insert_id = $insert->id;
                            if (!empty($status_flag[$i]) == '1') {
                                $status = 'Completed';
                                $task_completed_at = Carbon::now();
                                $callWrapUp = array(
                                    'uid'                 => $patient_id,
                                    'record_date'         => Carbon::now(),
                                    'topic'               => 'Follow Up Task : ' . $task_name[$i] . ' - Created on ' . date("m-d-Y", strtotime($task_date[$i])) . ' - Scheduled on ' . date("m-d-Y", strtotime($task_date[$i])), //('.$selected_task_name[$i].')
                                    'notes'               => $notes[$i],
                                    'created_by'          => session()->get('userid'),
                                    'update_by'           => session()->get('userid'),
                                    'patient_id'          => $patient_id,
                                    'sequence'            => $sequence,
                                    'sub_sequence'        => $new_sub_sequence,
                                    'task_id'             => $last_insert_id
                                );
                                CallWrap::create($callWrapUp);
                            }
                        } //end value if
                    } //end foreach
                    $data = array(
                        'uid'                 => $patient_id,
                        'rec_date'            => Carbon::now(),
                        'emr_complete'        => $emr_complete,
                        'patient_id'          => $patient_id,
                        'update_status'       => 1,
                    );
                    $check_id = FollowUp::where('patient_id', $patient_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->exists();
                    if ($check_id == true) {
                        $data['updated_by'] = session()->get('userid');
                        $update_query = FollowUp::where('patient_id', $patient_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->orderBy('id', 'desc')->first()->update($data);
                    } else {
                        $data['created_by'] = session()->get('userid');
                        $insert_query = FollowUp::create($data);
                    }
                    //record time
                    $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name, $form_start_time, $form_save_time);
                } else {
                    $data = array(
                        'uid'                 => $patient_id,
                        'rec_date'            => Carbon::now(),
                        'emr_complete'        => $emr_complete,
                        'patient_id'          => $patient_id,
                        'update_status'       => 1,
                    );
                    $check_id = FollowUp::where('patient_id', $patient_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->exists();
                    if ($check_id == true) {
                        $data['updated_by'] = session()->get('userid');
                        $update_query = FollowUp::where('patient_id', $patient_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->orderBy('id', 'desc')->first()->update($data);
                    } else {
                        $data['created_by'] = session()->get('userid');
                        $insert_query = FollowUp::create($data);
                    }
                    //record time
                    $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name, $form_start_time, $form_save_time);
                }
            } else {
                return 'blank form';
            } //end else
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function UpdateCallWrapUpInline(Request $request, $id)
    {
        $patient_id = sanitizeVariable($id);
        $all_row    = sanitizeVariable($request->all());
        $table_id   = array_keys($all_row['data']);
        $row_id     = sanitizeVariable($table_id[0]);
        $callwrapdata = CallWrap::where("id", $row_id)->get();
        $data = array();
        if (isset($all_row['data'][$row_id]['notes'])) {
            $notes = sanitizeVariable($all_row['data'][$row_id]['notes']);
            $data['notes'] = $notes;
            if ($callwrapdata[0]->topic == "EMR Monthly Summary") { // added by pranali on 27Oct2020
                $data['emr_monthly_summary'] = $notes;
            }
        }
        if (isset($all_row['data'][$row_id]['action_taken'])) {
            $action_taken = sanitizeVariable($all_row['data'][$row_id]['action_taken']);
            $data['action_taken'] = $action_taken;
        }
        $data['updated_by']    = session()->get('userid');
        $data['id']            = $row_id;
        $update_data           = CallWrap::where("patient_id", $patient_id)->where("id", $row_id)->update($data);
        $data1['id']           = $callwrapdata[0]->id;
        $data1['action_taken'] = $callwrapdata[0]->action_taken;
        $data1['notes']        = $notes; // updated by pranali on 27Oct2020
        $data1['topic']        = $callwrapdata[0]->topic;
        $data1['DT_RowId']     = $callwrapdata[0]->id;
        $d['data'][0]          = $data1;
        return $d;
    }

    public function SaveText(TextAddRequest $request)
    {
        $msg                = '';
        $contact_via        = "Text";
        $patient_id         = sanitizeVariable($request->patient_id);
        $template_type_id   = sanitizeVariable($request->input('template_type_id'));
        $template_id        = sanitizeVariable($request->input('template'));
        $stage_id           = sanitizeVariable($request->input('stage_id'));
        $text_msg           = sanitizeVariable($request->input('message'));
        $contact_no         = sanitizeVariable($request->input('contact_no'));
        $start_time         = sanitizeVariable($request->start_time);
        $end_time           = sanitizeVariable($request->end_time);
        $uid                = sanitizeVariable($request->uid);
        $module_id          = sanitizeVariable($request->module_id);
        $component_id       = sanitizeVariable($request->component_id);
        $stage_id           = sanitizeVariable($request->stage_id);
        $step_id            = sanitizeVariable($request->step_id);
        $form_name          = sanitizeVariable($request->form_name);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        $billable           = 1;
        DB::beginTransaction();
        try {
            $template = array(
                'content'            => $text_msg,
                'phone_no'           => $contact_no,
                'content_title'      => $template_id
            );
            $contenthistory = array(
                'contact_via'   => $contact_via,
                'uid'           => $uid,
                'template_id'   => $template_id,
                'module_id'     => $module_id,
                'component_id'  => $component_id,
                'template_type' => $template_type_id,
                'template'      => json_encode($template),
                'stage_id'      => $stage_id,
                'created_by'    => session()->get('userid'),
                'patient_id'    => $patient_id
            );
            $insert_content = ContentTemplateUsageHistory::create($contenthistory);
            $msg = sendTextMessage($contact_no, $text_msg, $patient_id, $module_id, $stage_id);
            $history_id = $insert_content->id;
            $text_temp  = array('template_id' => $template_id, 'history_id' => $history_id);
            //record time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name, $form_start_time, $form_save_time);
            $data = array(
                'uid'          => $uid,
                'rec_date'     => Carbon::now(),
                'contact_no'   => $contact_no,
                'template_id'  => $template_id,
                'msg'          => $text_msg,
                'template'     => json_encode($text_temp),
                'patient_id'   => $patient_id,
                'updated_by'   => session()->get('userid'),
                'created_by'   => session()->get('userid'),
                'response_msg' => $msg
            );

            $insert_query  = TextMsg::create($data);
            $text_note = array(
                'uid'                 => $uid,
                'record_date'         => Carbon::now(),
                'topic'               => 'Text Message Sent',
                'notes'               => $text_msg,
                'patient_id'          => $patient_id,
                'sequence'            => "12",
                'sub_sequence'        => "11",
                'created_by'          => session()->get('userid')
            );
            if (CallWrap::where('patient_id', $patient_id)->where('sequence', '12')->where('sub_sequence', '11')->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->exists()) {
                CallWrap::where('patient_id', $patient_id)->where('sequence', '12')->where('sub_sequence', '11')->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->orderBy('id', 'desc')->first()->update($text_note);
            } else {
                CallWrap::create($text_note);
            }
            DB::commit();
            return response(['form_start_time' => $form_save_time, 'msg' => $msg]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function additionalDeviceEmail(Request $request)
    {
        $patientId = sanitizeVariable($request->patient_id);
        $module_id = sanitizeVariable($request->module_id);
        $component_id = sanitizeVariable($request->component_id);
        $from_email = sanitizeVariable($request->email_from);
        $subject = sanitizeVariable($request->email_sub);
        $email_content = sanitizeVariable($request->mail_content);
        $stage_id = sanitizeVariable($request->stage_id);
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $device_ids = sanitizeVariable($request->device_ids);
        $step_id = 0;
        $form_name = 'patient_add_device_form';
        $billable = 1;
        $to_email = Partner::where('category', '0')->orderBy('created_at', 'desc')->first();
        $to = $to_email->email;
        $patient_device = PatientDevices::where('patient_id', $patientId)->where('status', 1)->latest()->first();
        $pdevices = array();
        $rpdevices = array();
        if (isset($patient_device->vital_devices)) {
            $dv = $patient_device->vital_devices;
            $js = json_decode($dv);
            $pdevices = $js;
        }
        if (isset($patient_device->device_code)) {
            if (strlen($patient_device->device_code) > 0 && strlen($patient_device->device_code) <= 10) {
                $sub_device_code = ' for ' . $patient_device->device_code;
            } else {
                $sub_device_code = '';
            }
        } else {
            $sub_device_code = '';
        }
        if (sanitizeVariable($request->add_replace_device) == 1) {
            $subject = "Additional device" . $sub_device_code;
            foreach ($device_ids as $key => $val) {
                if ($val == 1) {
                    $pd = Partner_Devices::where('device_id', $key)->where('created_by', 1)->get();
                    $pdevices[] = array('vid' => $key, 'pid' => $pd[0]->partner_id, 'pdid' => $pd[0]->id);
                }
            }
            $patientdevicedata = array(
                'created_by' => session()->get('userid'),
                'vital_devices' => $pdevices
            );
            PatientDevices::where('id', $patient_device->id)->update($patientdevicedata);
        } else {
            $subject = "Replace device" . $sub_device_code;
            $already_exit = array();
            foreach ($device_ids as $key => $val) {
                if ($val == 1) {
                    array_push($already_exit, $key);
                }
            }
            foreach ($pdevices as $value) {
                if (in_array($value->vid, $already_exit)) {
                } else {
                    $rpdevices[] = array('vid' => $value->vid, 'pid' => $value->pid, 'pdid' => $value->pdid);
                }
            }
            $patientdevicedata = array(
                'created_by' => session()->get('userid'),
                'vital_devices' => $rpdevices
            );
            PatientDevices::where('id', $patient_device->id)->update($patientdevicedata);
        }
        $msg = $email_content;
        Mail::send(array(), array(), function ($message) use ($msg, $to, $subject) {
            $message->from('renova@d-insights.global', 'Renova System');
            $message->to($to);
            $message->subject($subject);
            $message->setBody($msg, 'text/html');
        });
        if (count(Mail::failures()) == 0) {
            echo 'Mail Send ' . Carbon::now();
        }
        $data = array(
            'patient_id' => $patientId,
            'module_id' => $module_id,
            'stage_id' => $stage_id,
            'from_email' => $from_email,
            'to_email' => $to,
            'email_subject' => $subject,
            'email_content' => $email_content,
            'email_date' => Carbon::now(),
            'status' => 1,
            'created_by' => session()->get('userid'),
            'updated_by' => session()->get('userid')
        );
        EmailLog::create($data);
        $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patientId, $module_id, $component_id, $stage_id, $billable, $patientId, $step_id, $form_name);
    }

    public function listMessageHistory($id)
    {
        $dateS = Carbon::now()->startOfMonth()->subMonth(1);
        $dateE = Carbon::now();
        $call_history = MessageLog::select('patient_id', 'status', 'created_at', 'module_id', 'message_date', 'status', 'message_date', 'id', 'message')
            ->where('patient_id', $id)
            ->whereBetween('created_at', [$dateS, $dateE])
            ->orderBy('created_at', 'desc')->get();
        foreach ($call_history as $callhistory) {
            echo "<li>";
            if ($callhistory->status == "received") {
                echo "<h5> Incoming Response (" . $callhistory->created_at . ")</h5>";
                echo  "<b>SMS: </b>" . $callhistory->message;
            } else {
                echo "<h5> Sent Messages (" . $callhistory->created_at . ")</h5>";
                echo  "<b>SMS: </b>" . $callhistory->message;
            }
            echo  "</li>";
        }
    }

    public function getCcmMonthlyData($patientId)
    {
        $patientId          = sanitizeVariable($patientId);
        $mid                = sanitizeVariable(getPageModuleName());
        $submodule_id       = sanitizeVariable(getPageSubModuleName());
        $component_id       = ModuleComponents::where('module_id', $mid)->where('components', 'Monthly Monitoring')->get('id');
        $callp              = (CallPreparation::latest($patientId) ? CallPreparation::latest($patientId)->population() : "");
        $callstatus         = (CallStatus::latest($patientId) ? CallStatus::latest($patientId)->population() : "");
        $hippa              = (CallHipaaVerification::latest($patientId) ? CallHipaaVerification::latest($patientId)->population() : "");
        // $callClose          = (CallClose::latest($patientId,$component_id[0]->id) ? CallClose::latest($patientId,$component_id[0]->id)->population() : "");
        $callClose          = (CallClose::latest($patientId) ? CallClose::latest($patientId)->population() : "");
        $followUp           = (FollowUp::latest($patientId) ? FollowUp::latest($patientId)->population() : "");
        $patientContactTime = PatientContactTime::where('patient_id', $patientId)->first();
        $callWrapUp         = (CallWrap::latest($patientId) ? CallWrap::latest($patientId)->population() : "");  //added by ashvini 28June2022
        $result['call_preparation_preparation_followup_form']   = $callp;
        $result['research_follow_up_preparation_followup_form'] = "";
        if (CallHipaaVerification::where('patient_id', $patientId)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists()) {
            $result['hippa_form']['static']['verification'] = $hippa['static']['verification'];
        }
        // dd($callClose);
        if (!empty($patientContactTime)) {
            $patientContactTime = $patientContactTime->population();
            if (!empty($callClose['static'])) {
                $callClose['static'] = array_merge($callClose['static'], $patientContactTime['static']);
            } else {
                $callClose = [];
                $callClose['static'] = $patientContactTime['static'];
            }
            $result['call_close_form'] = $callClose;
        }
        if (!empty($patientContactTime)) {
            if (!empty($callstatus['static'])) {
                $callstatus['static'] = array_merge($callstatus['static'], $patientContactTime['static']);
            } else {
                $callstatus = [];
                $callstatus['static'] = $patientContactTime['static'];
            }
        }
        $result['followup_form']   = $followUp;
        $result['callwrapup_form'] = $callWrapUp;  //added by ashvini 28June2022
        return $result;
    }


    public function getCcmMonthlyReasearchFollowupData($patientId)
    {
        $year  = date('Y');
        $month = date('m');
        $patientId          = sanitizeVariable($patientId);
        $mid                = sanitizeVariable(getPageModuleName());
        $component_id       = ModuleComponents::where('module_id', $mid)->where('components', 'Monthly Monitoring')->get('id');
        $callp              = (CallPreparation::latest($patientId) ? CallPreparation::latest($patientId)->population() : "");
        $callstatus         = (CallStatus::latest($patientId) ? CallStatus::latest($patientId)->population() : "");
        $hippa              = (CallHipaaVerification::latest($patientId) ? CallHipaaVerification::latest($patientId)->population() : "");
        // $callClose          = (CallClose::latest($patientId,$component_id[0]->id) ? CallClose::latest($patientId,$component_id[0]->id)->population() : "");
        $callClose          = (CallClose::latest($patientId) ? CallClose::latest($patientId)->population() : "");
        $followUp           = (FollowUp::latest($patientId) ? FollowUp::latest($patientId)->population() : "");
        $callWrapUp         = (CallWrap::latest($patientId) ? CallWrap::latest($patientId)->population() : "");  //added by ashvini 28june2022
        $result['call_preparation_preparation_followup_form'] = $callp;
        $result['research_follow_up_preparation_followup_form'] = $callp;
        $result['hippa_form'] = $hippa;
        $rpmReviewData = (VitalsObservationNotes::latest($patientId) ? VitalsObservationNotes::latest($patientId)->population() : "");
        $result['rpm_review_form'] = $rpmReviewData;
        $result['followup_form'] = $followUp;
        $result['callwrapup_form'] = $callWrapUp;  //added by 28thjune2022

        if (CallWrap::where('patient_id', $patientId)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists()) {
            $EmrMonthlySummary = EmrMonthlySummary::where('patient_id', $patientId)
                ->where('sequence', 5)
                ->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->where('status', 1)
                ->where('emr_type', 1)
                // ->select("notes,topic,record_date")
                ->select(['notes', 'topic', 'record_date'])
                ->get();


            if (count($EmrMonthlySummary) == 0) {
                $EmrMonthlySummary = CallWrap::where('patient_id', $patientId)
                    ->where('sequence', 5)
                    ->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'))
                    ->where('status', 1)
                    ->where('topic', 'like', 'EMR Monthly Summary%')
                    // ->select("topic,notes,emr_entry_completed,record_date")
                    ->select(['topic', 'notes', 'emr_entry_completed', 'record_date'])
                    ->get();
            }


            $Summary =          EmrMonthlySummary::where('patient_id', $patientId)
                ->where('sequence', 5)
                ->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->where('status', 1)
                ->where('emr_type', 2)
                // ->select("topic,notes,record_date")
                ->select(['topic', 'notes', 'record_date'])
                ->get();
            // dd($Summary);

            if (count($Summary) == 0) {
                $Summary =       CallWrap::where('patient_id', $patientId)
                    ->where('sequence', 5)
                    ->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'))
                    ->where('status', 1)
                    ->where('topic', 'like', 'Summary notes added on%')
                    // ->select("topic,notes,record_date,emr_entry_completed")
                    ->select(['topic', 'notes', 'emr_entry_completed', 'record_date'])
                    ->get();
            }





            // dd($EmrMonthlySummary);

            if (isset($EmrMonthlySummary[0]->notes)) {
                $result['callwrapup_form']['static']['emr_monthly_summary'] = $EmrMonthlySummary;
            } else {
                $result['callwrapup_form']['static']['emr_monthly_summary'] = ' ';
            }




            if (isset($EmrMonthlySummary[0]->emr_entry_completed)) {
                $result['callwrapup_form']['static']['emr_entry_completed'] = $EmrMonthlySummary[0]->emr_entry_completed;
            } else {
                $result['callwrapup_form']['static']['emr_entry_completed'] = ' ';
            }

            if (isset($Summary[0]->notes)) {
                $result['callwrapup_form']['static']['summary'] = $Summary;
            } else {
                $result['callwrapup_form']['static']['summary'] = ' ';
            }


            $callwrapupchecklistdata = CallWrapupChecklist::where('patient_id', $patientId)->latest()->first();

            $result['callwrapup_form']['static']['checklist_data'] = $callwrapupchecklistdata;

            $callwrapupadditionalservices = DB::select("select id,topic, notes, action_taken, status, created_at,
                                            sequence, sub_sequence
                                            from ccm.ccm_topics
                                            where id in (select max(id)
                                            FROM ccm.ccm_topics
                                            WHERE patient_id='" . $patientId . "' And topic LIKE 'Additional Services%'
                                            AND EXTRACT(Month from record_date) = '" . $month . "' AND EXTRACT(YEAR from record_date) = '" . $year . "'
                                            )
                                            ");
            $result['callwrapup_form']['static']['additional_services'] = $callwrapupadditionalservices;
        }

        if (CallWrap::where('patient_id', $patientId)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists()) {
            $CallWrap = DB::select("select ct.topic,ct.notes from ccm.ccm_topics ct
                                        inner join ren_core.followup_tasks rft on ct.topic = rft.task
                                         WHERE ct.patient_id ='" . $patientId . "'
                                         AND ct.created_at >= date_trunc('month', current_date)
                                         AND ct.created_at >= date_trunc('year', current_date)
                                         AND ct.sequence =5
                                        ");
            $result['callwrapdata_form']['static']['call_wrap_followup_task'] = $CallWrap;
        }
        $PatientVitalsNumberTracking = (PatientVitalsData::latest($patientId) ? PatientVitalsData::latest($patientId)->population() : ""); //added by priya 12Nov 2020
        $result['number_tracking_vitals_form'] = $PatientVitalsNumberTracking;
        if (PatientImaging::where('patient_id', $patientId)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists()) {
            $PatientImaging = PatientImaging::where('patient_id', $patientId)
                // ->select("distinct imaging_details, date(imaging_date)")
                ->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->groupBy('imaging_details', 'imaging_date')->get('imaging_details')->toArray();
            $result['number_tracking_imaging_form']['static']['imaging'] = '["' . implode('","', array_column($PatientImaging, 'imaging_details')) . '"]';
            $result['number_tracking_imaging_form']['static']['imaging_date'] = '["' . implode('","', array_column($PatientImaging, 'date')) . '"]';
        }
        if (PatientHealthData::where('patient_id', $patientId)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists()) {
            $PatientHealthData = PatientHealthData::where('patient_id', $patientId)
                // ->select("distinct health_data, date(health_date)")
                ->whereMonth('updated_at', date('m'))
                ->whereYear('updated_at', date('Y'))
                ->groupBy('health_data', 'health_date')->get('health_data')->toArray();
            $result['number_tracking_healthdata_form']['static']['healthdata'] = '["' . implode('","', array_column($PatientHealthData, 'health_data')) . '"]';
            $result['number_tracking_healthdata_form']['static']['health_date'] = '["' . implode('","', array_column($PatientHealthData, 'date')) . '"]';
        }
        return $result;
    }

    public function getCallScriptsById(Request $request)
    {
        $uid     = sanitizeVariable($request->uid);
        $id      = sanitizeVariable($request->id);
        $scripts = ContentTemplate::where('id', $id)->where('status', 1)->get(); //
        $patient_providers = PatientProvider::where('patient_id', $uid)->where('is_active', 1)
            ->with('practice')->with('provider')->with('users')->where('provider_type_id', 1)->orderby('id', 'desc')->first();
        $patient = Patients::where('id', $uid)->get();
        $PatientDevices = PatientDevices::where('patient_id', $uid)->where('status', 1)->latest()->first();
        $nin = array();
        if (isset($PatientDevices->vital_devices)) {
            $dv = $PatientDevices->vital_devices;
            $js = json_decode($dv);
            foreach ($js as $val) {
                if (isset($val->vid)) {
                    array_push($nin, $val->vid);
                }
            }
        }
        $device = Devices::whereIn('id', $nin)->pluck('device_name')->implode(', ');
        if (isset($PatientDevices->device_code)) {
            $devicecode = $PatientDevices->device_code;
        } else {
            $devicecode = "";
        }
        $intro = get_object_vars(json_decode($scripts[0]->content));
        $provider_data = (array)$patient_providers;
        $provider_name = empty($patient_providers->provider['name']) ? '[provider]' : $patient_providers->provider['name'];
        $practice_name = empty($patient_providers['practice']['name']) ? '' : $patient_providers['practice']['name'];
        $replace_provider = str_replace("[provider]", $provider_name, $intro['message']);
        $replace_practice_name = str_replace("[practice_name]", $practice_name, $replace_provider);

        $replace_user = str_replace("[users_name]", Session::get('f_name') . " " . Session::get('l_name'), $replace_practice_name);
        $replace_pt = str_replace("[patient_name]", $patient[0]->fname . ' ' . $patient[0]->lname, $replace_user);
        $replace_id = str_replace("[patientid]", $patient[0]->id, $replace_pt);
        $replace_primary = str_replace("[primary_contact_number]", $patient[0]->mob, $replace_id);
        $data_emr = str_replace("[EMR]", $patient_providers['practice_emr'], $replace_primary);
        $replace_secondary = str_replace("[secondary_contact_number]", $patient[0]->home_number, $data_emr);
        $replace_devicelist = str_replace("[device_list]", $device, $replace_secondary);
        $replace_final = str_replace("[devicecode]", $devicecode, $replace_devicelist);
        $scripts['finaldata'] = $replace_final;
        return $scripts;
    }

    public  function generalQuestion(Request $request)
    {
        $sequence     = 4;
        $moduleid     = sanitizeVariable($request->module_id);
        $componentid  = sanitizeVariable($request->component_id);
        //$template_id  = sanitizeVariable($request->template_id);
        $step_id      = sanitizeVariable($request->step_id);
        $form_name    = sanitizeVariable($request->form_name);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        $patient_id   = sanitizeVariable($request->patient_id);
        if (isset($request->template_id)) {
            $template_id  = sanitizeVariable($request->template_id);
        }
        $uid          = sanitizeVariable($request->patient_id);
        //$template_id  = sanitizeVariable($request->template_id);
        $start_time   = sanitizeVariable($request->timer_start);
        $end_time     = sanitizeVariable($request->timer_paused);

        $stage_id     = sanitizeVariable($request->stage_id);
        $billable     = 1;
        $ins = '';
        $m_id = sanitizeVariable($request->m_id);
        $c_id = sanitizeVariable($request->c_id);
        $module_id    = $m_id;
        $component_id = $c_id;
        /*DB::beginTransaction();
        try {*/
        $steps        = StageCode::where('module_id', $m_id)->where('submodule_id', $c_id)->where('id', $step_id)->get();
        QuestionnaireTemplatesUsageHistory::where('stage_code', $step_id)->where('patient_id', $patient_id)->update(['step_id' => 1]);
        if ($request->que_step_id) {
            $question_step = sanitizeVariable($request->que_step_id);
            //dd($question_step);
            foreach ($question_step as $keys => $step) {
                $step_name             = $steps[0]->description;
                $step_name = preg_replace('/[^A-Za-z0-9\-]/', '', trim($step_name));
                $step_name_trimmed     = str_replace(' ', '_', trim($step_name)) . '' . $keys;
                $step_name_trimmed     = str_replace("/", "_", $step_name_trimmed);
                $request_step_data     = sanitizeVariable($request->$step_name_trimmed);

                $qtemplate_id           = $request_step_data['template_id'];
                $template_type         = $request_step_data['template_type_id'];
                $current_monthly_notes = $request_step_data['current_monthly_notes'];
                $content_title = $request_step_data['content_title'];
                if (isset($request_step_data['question'])) {
                    $data = array(
                        'contact_via'   => 'questionnaire',
                        'template_type' => $template_type,
                        'uid'           => $uid,
                        'module_id'     => sanitizeVariable($request->mid),
                        'component_id'  => sanitizeVariable($request->cid),
                        'template_id'   => $qtemplate_id,
                        'template'      => sanitizeVariable(json_encode($request_step_data['question'])),
                        'monthly_notes' => $current_monthly_notes,
                        'stage_id'      => $stage_id,
                        'stage_code'    => $step,
                        'created_by'    => session()->get('userid'),
                        'patient_id'    => $patient_id,
                        'step_id'       => 0
                    );

                    $last_sub_sequence = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', $sequence)->max('sub_sequence');
                    $new_sub_sequence = $last_sub_sequence + 1;
                    $score = 0;
                    $seqindex = 1;
                    //dd($request_step_data['question']);
                    foreach ($request_step_data['question'] as $key => $value) {
                        $checkboxVal = '';
                        if (is_array($value)) {
                            foreach ($value as $k  => $v) {
                                if ($v) {
                                    $checkboxVal = $checkboxVal . str_replace('_', ' ', $k) . ',';
                                }
                            }
                            $value = substr($checkboxVal, 0, -1);
                        }
                        $tp = str_replace('_', ' ', $key);

                        if (str_replace('_', ' ', $key) == "score") {
                            $tp = $content_title . ' ' . str_replace('_', ' ', $key);
                            // print_r($tp);
                            //$tp = $step_name.' '.str_replace('_',' ', $key);
                            // $score = $score + $value;
                            $value = $value;
                        }
                        $notes = array(
                            'uid'                 => $uid,
                            'record_date'         => Carbon::now(),
                            'topic'               => $tp,
                            'notes'               => $value,
                            'created_by'          => session()->get('userid'),
                            'patient_id'          => $patient_id,
                            'template_type'       => 'qs' . $qtemplate_id,
                            'sequence'            => $sequence,
                            'sub_sequence'        => $new_sub_sequence,
                            'question_sequence'   => $qtemplate_id,
                            'question_sub_sequence' => sanitizeVariable($request->qseq[$qtemplate_id][$seqindex])
                        );
                        if ($value != "") {
                            CallWrap::create($notes);
                        }
                        $new_sub_sequence++;
                        $seqindex++;
                    }
                    $notes1 = array(
                        'uid'                 => $uid,
                        'record_date'         => Carbon::now(),
                        'topic'               => $content_title . " Notes",
                        'notes'               => $current_monthly_notes,
                        'created_by'          => session()->get('userid'),
                        'patient_id'          => $patient_id,
                        'template_type'       => 'qs' . $qtemplate_id,
                        'sequence'            => $sequence,
                        'sub_sequence'        => $new_sub_sequence
                    );
                    if ($current_monthly_notes != '') {
                        CallWrap::create($notes1);
                    }
                    $insert_query = QuestionnaireTemplatesUsageHistory::create($data);
                }
            }
        }
        if (isset($request->template_id)) {
            foreach ($template_id as $key => $value) {
                CallWrap::where('patient_id', $patient_id)->where('template_type', 'dt' . $value)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->delete();
            }
        }
        CallWrap::where('patient_id', $patient_id)->where('topic', 'Last Month follow up')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->delete();
        CallWrap::where('patient_id', $patient_id)->where('topic', 'Greatest health concern')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->delete();
        $t = array("Upcoming office visits", "Other issues?", "Preferred day/time?", "Annual screenings/vaccinations?", "Topic's for next month");
        foreach ($t as $k => $v) {
            CallWrap::where('patient_id', $patient_id)->where('topic', $v)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->delete();
        }
        if (isset($follow_up_date->rec_date)) {
            $fud = $follow_up_date->rec_date;
            $datetime = explode(" ", $fud);
            $follow_up = $datetime[0];
        } else {
            $follow_up = '';
        }
        $last_sub_sequence = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', $sequence)->max('sub_sequence');
        $new_sub_sequence = $last_sub_sequence + 1;
        $note = array(
            'uid'                 => $uid,
            'record_date'         => Carbon::now(),
            'topic'               => 'Last Month follow up',
            'notes'               => $follow_up,
            'created_by'          => session()->get('userid'),
            'patient_id'          => $patient_id,
            'sequence'            => $sequence,
            'sub_sequence'        => $new_sub_sequence
        );
        if (CallWrap::where('patient_id', $patient_id)->where('topic', 'Last Month follow up')->exists()) {
            CallWrap::where('patient_id', $patient_id)->where('topic', 'Last Month follow up')->update($note);
        } else {
            CallWrap::create($note);
        }
        $new_sub_sequence = $new_sub_sequence + 1;
        $note1 = array(
            'uid'                 => $uid,
            'record_date'         => Carbon::now(),
            'topic'               => 'Greatest health concern',
            'notes'               => null,
            'created_by'          => session()->get('userid'),
            'patient_id'          => $patient_id,
            'sequence'            => $sequence,
            'sub_sequence'        => $new_sub_sequence
        );
        if (CallWrap::where('patient_id', $patient_id)->where('topic', 'Greatest health concern')->exists()) {
            CallWrap::where('patient_id', $patient_id)->where('topic', 'Greatest health concern')->update($note1);
        } else {
            CallWrap::create($note1);
        }
        if (isset($request->template_id)) {
            foreach ($template_id as $key => $value) {
                CallWrap::where('patient_id', $patient_id)->where('template_type', 'dt' . $value)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->delete();
                $DT_topics0 = [];
                $dti = sanitizeMultiDimensionalArray($request['DT' . $key]);
                $i = 0;
                while (isset($dti['qs'])) {
                    if ($i == 0) {
                        $qtn = $dti['qs']['q'];
                        if (isset($dti['qs']['opt'])) {
                            $ins = 1;
                            foreach ($dti['qs']['opt'] as $keyin => $val) {
                                if (isset($dti['qs'])) {
                                    $DT_topics0[$qtn] = $dti['qs']['opt'][$keyin]['val'];
                                    for ($nextindex = 1; $nextindex <= 6; $nextindex++) {
                                        if (isset($dti['qs']['opt'][$keyin + $nextindex]['val'])) {
                                            $DT_topics0[$qtn] .= ", " . $dti['qs']['opt'][$keyin + $nextindex]['val'];
                                        }
                                    }
                                    $dti = $dti['qs']['opt'][$keyin];
                                }
                            }
                        } else {
                            $ins = 0;
                        }
                    } else {
                        $l = 0;
                        for ($qsl = 1; $qsl <= 6; $qsl++) {
                            if (isset($dti['qs'][$qsl]['opt'])) {
                                $l = $qsl;
                            }
                        }
                        if (isset($dti['qs'][$l]['q'])) {
                            $qtn = $dti['qs'][$l]['q'];
                        }
                        if (isset($dti['qs'][$l]['opt'])) {
                            foreach ($dti['qs'][$l]['opt'] as $keyin => $val) {
                                if (isset($dti['qs'])) {
                                    $DT_topics0[$qtn] = $dti['qs'][$l]['opt'][$keyin]['val'];
                                    for ($nextindex = 1; $nextindex <= 6; $nextindex++) {
                                        if (isset($dti['qs'][$l]['opt'][$keyin + $nextindex]['val'])) {
                                            $DT_topics0[$qtn] .= ", " . $dti['qs'][$l]['opt'][$keyin + $nextindex]['val'];
                                        }
                                    }
                                    $dti = $dti['qs'][$l]['opt'][$keyin];
                                }
                            }
                        } else {
                            break;
                        }
                    }
                    $i++;
                }
                $sqind = 0;
                foreach ($DT_topics0 as $inkey => $invalue) {
                    $new_sub_sequence = $new_sub_sequence + 1;
                    $notes = array(
                        'uid'                 => $uid,
                        'record_date'         => Carbon::now(),
                        'topic'               => $inkey,
                        'notes'               => $invalue,
                        'created_by'          => session()->get('userid'),
                        'patient_id'          => $patient_id,
                        'template_type'       => 'dt' . $value,
                        'sequence'            => $sequence,
                        'sub_sequence'        => $new_sub_sequence,
                        'question_sequence'   => $value,
                        'question_sub_sequence' => sanitizeVariable($request->sq[$value][$sqind])
                    );
                    //print_r($notes);
                    if ($invalue != '') {
                        CallWrap::create($notes);
                    }
                    $sqind++;
                }
                $data = array(
                    'contact_via'   => 'decisiontree',
                    'template_type' => 6,
                    'uid'           => $uid,
                    'module_id'     => $moduleid[$key],
                    'component_id'  => $componentid[$key],
                    'template_id'   => $value,
                    'template'      => json_encode(sanitizeMultiDimensionalArray($request['DT' . $key])),
                    'created_by'    => session()->get('userid'),
                    'patient_id'    => $patient_id,
                    'monthly_notes' => sanitizeVariable($request->monthly_notes[$key]),
                    'stage_code'    => sanitizeVariable($request->stage_code[$key]),
                    'step_id'       => 0,
                );
                $new_sub_sequence = $new_sub_sequence + 1;
                $monthly_notes_insert = array(
                    'uid'                 => $uid,
                    'record_date'         => Carbon::now(),
                    'topic'               => sanitizeVariable($request->monthly_topic[$key]),
                    'notes'               => sanitizeVariable($request->monthly_notes[$key]),
                    'created_by'          => session()->get('userid'),
                    'patient_id'          => $patient_id,
                    'template_type'       => 'dt' . $value,
                    'sequence'            => $sequence,
                    'sub_sequence'        => $new_sub_sequence
                );
                if (sanitizeVariable($request->monthly_notes[$key]) != "") {
                    CallWrap::create($monthly_notes_insert);
                }
                if ($ins == 1) {
                    QuestionnaireTemplatesUsageHistory::create($data);
                }
                $module_id    = sanitizeVariable($moduleid[$key]);
                $component_id = sanitizeVariable($componentid[$key]);
            }
        }
        //record time
        $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name, $form_start_time, $form_save_time);
        foreach ($t as $k => $v) {
            $new_sub_sequence = $new_sub_sequence + 1;
            $note2 = array(
                'uid'                 => $uid,
                'record_date'         => Carbon::now(),
                'topic'               => str_replace('_', ' ', $v),
                'notes'               => null,
                'created_by'          => session()->get('userid'),
                'patient_id'          => $patient_id,
                'sequence'            => $sequence,
                'sub_sequence'        => $new_sub_sequence
            );

            if (CallWrap::where('patient_id', $patient_id)->where('topic', $v)->exists()) {
                CallWrap::where('patient_id', $patient_id)->where('topic', $v)->update($note2);
            } else {
                CallWrap::create($note2);
            }
        }
        //return response()->json(['success' => "Added successfully."]);
        return response(['form_start_time' => $form_save_time]);
        /*  DB::commit();
        } catch(\Exception $ex) {
            DB::rollBack();
            // return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }*/
    }
}
