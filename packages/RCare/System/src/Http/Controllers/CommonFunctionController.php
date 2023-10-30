<?php
namespace RCare\System\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RCare\Patients\Models\PatientTimeRecords;
use RCare\Patients\Models\PatientTimeButtonLogs;
use RCare\System\Http\Requests\ManuallyAdjustTimeRequest;
use RCare\Patients\Models\PatientDiagnosis;
use RCare\Patients\Models\PatientMedication;
use RCare\Patients\Models\PatientAllergy;
use RCare\Patients\Models\PatientVitalsData;
use RCare\Patients\Models\PatientLabRecs;
use RCare\Patients\Models\PatientHealthServices;
use RCare\Patients\Models\PatientHealthData;
use RCare\Patients\Models\PatientImaging;
use RCare\Patients\Models\PatientServices;
use Illuminate\Support\Facades\Log;
use RCare\Messaging\Models\MessageLog;
use RCare\System\Models\MfaTextingLog;
use RCare\Org\OrgPackages\DomainFeatures\src\Models\DomainFeatures;
use RCare\Patients\Models\Patients; 
use RCare\Patients\Models\PatientDevices;
use RCare\Rpm\Models\Devices;
use RCare\Patients\Models\PatientProvider;
use RCare\Org\OrgPackages\QCTemplates\src\Models\ContentTemplate;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\DB;

class CommonFunctionController extends Controller
{
    //get net time
    public static function getCcmMonthlyNetTime($patient_id, $module_id)
    {
        $year       = sanitizeVariable(date('Y', strtotime(Carbon::now())));
        $month      = sanitizeVariable(date('m', strtotime(Carbon::now())));
        $patient_id = sanitizeVariable($patient_id);
        $module_id  = sanitizeVariable($module_id);
       

        $query = "select * from patients.sp_monthly_get_patient_net_time(".$patient_id.", ".$module_id.", 0, '".$month."', '".$year."');"; 
        $data  = DB::select(DB::raw($query));
        if(empty($data)) {
            $last_time_spend = "00:00:00";
        } else {
            $last_time_spend = $data[0]->totaltime;
        }
        return $last_time_spend;
    }

    public function recordUpdatedTime($patientID, $billable, $moduleId)
    {
        $year       = sanitizeVariable(date('Y', strtotime(Carbon::now())));
        $month      = sanitizeVariable(date('m', strtotime(Carbon::now())));
        $patient_id = sanitizeVariable($patientID);
        $module_id  = sanitizeVariable($moduleId);
        $billable   = sanitizeVariable($billable);
        if($billable == 1){
            return $this->getCcmMonthlyNetTime($patientID, $moduleId);
        }else{
            return $this->getNonBillabelTime($patientID, $moduleId);
        }
    }

    public function finalizeDate($patientID, $moduleId)
    {
       
        $service = PatientServices::where('patient_id', $patientID)->where("module_id", $moduleId)->where('finalize_cpd','1')->orderBy('created_at', 'desc')->take(1)->get();
       if(isset($service[0]->finalize_date)){
        return $service[0]->finalize_date;
       }else{
           return '';
       }
        
    }

    public static function getNonBillabelTime($patient_id, $module_id)
    {
        $year       = sanitizeVariable(date('Y', strtotime(Carbon::now())));
        $month      = sanitizeVariable(date('m', strtotime(Carbon::now())));
        $patient_id = sanitizeVariable($patient_id);
        $module_id  = sanitizeVariable($module_id);
       

        $query = "select * from patients.sp_non_billabel_net_time(".$patient_id.", ".$module_id.", 0, '".$month."', '".$year."');"; 
        $data  = DB::select(DB::raw($query));
        if(empty($data)) {
            $last_time_spend = "00:00:00";
        } else {
            $last_time_spend = $data[0]->totaltime;
        }
        return $last_time_spend;
    }

    public static function getCcmNetTime($patient_id, $module_id)
    {
        $year       = sanitizeVariable(date('Y', strtotime(Carbon::now())));
        $month      = sanitizeVariable(date('m', strtotime(Carbon::now())));
        $patient_id = sanitizeVariable($patient_id);
        $module_id  = sanitizeVariable($module_id);
        // $query = "select distinct pt.patient_id,pt1.timeone,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone ) as totaltime
        // from  patients.patient_time_records pt 

        // LEFT JOIN
        // (SELECT distinct patient_id,sum(net_time) AS timeone FROM patients.patient_time_records WHERE 
        //  patient_id = $patient_id and adjust_time =1 and module_id in (".$module_id.", 8) and (EXTRACT(Month from record_date) = '".$month."')
        //  AND (EXTRACT(YEAR from record_date) = '".$year."') GROUP BY patient_id) pt1 
        // ON  pt1.patient_id = pt.patient_id 

        // LEFT JOIN (SELECT distinct patient_id, sum(net_time) AS timetwo FROM patients.patient_time_records WHERE 
        // patient_id = $patient_id and adjust_time =0 and module_id in (".$module_id.", 8) and  (EXTRACT(Month from record_date) ='".$month."')
        //  AND (EXTRACT(YEAR from record_date) = '".$year."') and patient_id = $patient_id GROUP BY patient_id) pt2 
        // ON  pt2.patient_id = pt.patient_id 

        // where   
        // (EXTRACT(Month from pt.record_date) = '".$month."') 
        // AND (EXTRACT(YEAR from record_date) = '".$year."') and  module_id in (".$module_id.", 8)
        // and pt.patient_id = $patient_id";

        $query = "select * from patients.sp_get_patient_net_time(".$patient_id.", ".$module_id.", 0, '".$month."', '".$year."');"; 
        $data  = DB::select(DB::raw($query));
        if(empty($data)) {
            $last_time_spend = "00:00:00";
        } else {
            $last_time_spend = $data[0]->totaltime;
        }
        return $last_time_spend;
    }

    //get previous month net time
    public static function getCcmPreviousMonthNetTime($patient_id, $module_id)
    {   $str = sanitizeVariable(date('Y-m-d', strtotime(date('Y-m')." -1 month")));
        $Get_year_month = explode("-",sanitizeVariable($str));
        $year       = sanitizeVariable($Get_year_month[0]);
        $month      = sanitizeVariable($Get_year_month[1]);
        $patient_id = sanitizeVariable($patient_id);
        $module_id  = sanitizeVariable($module_id);
        // print_r($year); echo "<pre>";
        // print_r($month); echo "<pre>";
        // print_r($patient_id); echo "<pre>";
        // print_r($module_id); die;
        // $query = "select distinct pt.patient_id,pt1.timeone,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone ) as totaltime
        // from  patients.patient_time_records pt 

        // LEFT JOIN
        // (SELECT distinct patient_id,sum(net_time) AS timeone FROM patients.patient_time_records WHERE 
        //  patient_id = $patient_id and adjust_time =1 and module_id in (".$module_id.", 8) and (EXTRACT(Month from record_date) = '".$month."')
        //  AND (EXTRACT(YEAR from record_date) = '".$year."') GROUP BY patient_id) pt1 
        // ON  pt1.patient_id = pt.patient_id 

        // LEFT JOIN (SELECT distinct patient_id, sum(net_time) AS timetwo FROM patients.patient_time_records WHERE 
        // patient_id = $patient_id and adjust_time =0 and module_id in (".$module_id.", 8) and  (EXTRACT(Month from record_date) ='".$month."')
        //  AND (EXTRACT(YEAR from record_date) = '".$year."') and patient_id = $patient_id GROUP BY patient_id) pt2 
        // ON  pt2.patient_id = pt.patient_id 

        // where   
        // (EXTRACT(Month from pt.record_date) = '".$month."') 
        // AND (EXTRACT(YEAR from record_date) = '".$year."') and  module_id in (".$module_id.", 8)
        // and pt.patient_id = $patient_id";
        $query = "select * from patients.sp_get_patient_net_time(".$patient_id.", ".$module_id.", 0, '".$month."', '".$year."');"; 
        $data = DB::select(DB::raw($query));
        if(empty($data)) {
            $last_time_spend = "00:00:00";
        } else {
            $last_time_spend = $data[0]->totaltime;
        }
        return $last_time_spend;
    }
    
    //get net time
    public static function getNetTimeBasedOnModule($patient_id, $module_id)
    {
        $year       = sanitizeVariable(date('Y', strtotime(Carbon::now())));
        $month      = sanitizeVariable(date('m', strtotime(Carbon::now())));
        $patient_id = sanitizeVariable($patient_id);
        $module_id  = sanitizeVariable($module_id);

        // $query = "select distinct pt.patient_id,pt1.timeone,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone ) as totaltime
        // from  patients.patient_time_records pt 

        // LEFT JOIN
        // (SELECT distinct patient_id,sum(net_time) AS timeone FROM patients.patient_time_records WHERE 
        //  patient_id = $patient_id and adjust_time =1 and module_id in (".$module_id.", 8) and (EXTRACT(Month from record_date) = '".$month."')
        //  AND (EXTRACT(YEAR from record_date) = '".$year."') GROUP BY patient_id) pt1 
        // ON  pt1.patient_id = pt.patient_id 

        // LEFT JOIN (SELECT distinct patient_id, sum(net_time) AS timetwo FROM patients.patient_time_records WHERE 
        // patient_id = $patient_id and adjust_time =0 and module_id in (".$module_id.", 8) and  (EXTRACT(Month from record_date) ='".$month."')
        //  AND (EXTRACT(YEAR from record_date) = '".$year."') and patient_id = $patient_id GROUP BY patient_id) pt2 
        // ON  pt2.patient_id = pt.patient_id 

        // where   
        // (EXTRACT(Month from pt.record_date) = '".$month."') 
        // AND (EXTRACT(YEAR from record_date) = '".$year."') and  module_id in (".$module_id.", 8)
        // and pt.patient_id = $patient_id";
        $query = "select * from patients.sp_get_patient_net_time(".$patient_id.", ".$module_id.", 0, '".$month."', '".$year."');"; 
        $data = DB::select(DB::raw($query));
        if(empty($data)) {
            $last_time_spend = "00:00:00";
        } else {
            $last_time_spend = $data[0]->totaltime;
        }
        return $last_time_spend;
    }

    //get net time
    public static function getNetTimeBasedOnModuleSubmodule($patient_id, $module_id, $component_id)
    { 
        $year         = sanitizeVariable(date('Y', strtotime(Carbon::now())));
        $month        = sanitizeVariable(date('m', strtotime(Carbon::now())));
        $patient_id   = sanitizeVariable($patient_id);
        $module_id    = sanitizeVariable($module_id);
        $component_id = sanitizeVariable($component_id);

        // $query = "select distinct pt.patient_id,pt1.timeone,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone ) as totaltime
        // from  patients.patient_time_records pt 

        // LEFT JOIN
        // (SELECT distinct patient_id,sum(net_time) AS timeone FROM patients.patient_time_records WHERE 
        //  patient_id = $patient_id and adjust_time =1 and module_id in (".$module_id.", 8) and component_id =".$component_id." and (EXTRACT(Month from record_date) = '".$month."')
        //  AND (EXTRACT(YEAR from record_date) = '".$year."') GROUP BY patient_id) pt1 
        // ON  pt1.patient_id = pt.patient_id 

        // LEFT JOIN (SELECT distinct patient_id, sum(net_time) AS timetwo FROM patients.patient_time_records WHERE 
        // patient_id = $patient_id and adjust_time =0 and module_id in (".$module_id.", 8) and component_id =".$component_id." and (EXTRACT(Month from record_date) ='".$month."')
        //  AND (EXTRACT(YEAR from record_date) = '".$year."') and patient_id = $patient_id GROUP BY patient_id) pt2 
        // ON  pt2.patient_id = pt.patient_id 

        // where   
        // (EXTRACT(Month from pt.record_date) = '".$month."') 
        // AND (EXTRACT(YEAR from record_date) = '".$year."') and  module_id in (".$module_id.", 8) and component_id =".$component_id." 
        // and pt.patient_id = $patient_id";
        $query = "select * from patients.sp_get_patient_net_time(".$patient_id.", ".$module_id.", ".$component_id.", '".$month."', '".$year."');"; 
        $data = DB::select(DB::raw($query));
        if(empty($data)) {
            $last_time_spend = "00:00:00";
        } else {
            $last_time_spend = $data[0]->totaltime;
        }
        return $last_time_spend;
    }
    //recode message

    public static function recordMessages($patient_id, $module_id, $stage_id, $sid, $from, $to, $status, $text)
    {
        $data = array(
            "message_id" => sanitizeVariable($sid),
            "patient_id" => sanitizeVariable($patient_id),
            "module_id" => sanitizeVariable($module_id),
            "stage_id" => sanitizeVariable($stage_id),
            "from_phone" => sanitizeVariable($from),
            "to_phone" => sanitizeVariable($to),
            "status" => sanitizeVariable($status),
            "message_date" => Carbon::now(),
            "message" => $text,
            "created_by"  => session()->get('userid'),
            "updated_by" => session()->get('userid')
        );
        $insert = MessageLog::create($data);
    }

    public static function resendMessages($old_sid, $sid, $status){
        $data = array('message_id'=>sanitizeVariable($sid),'status'=>sanitizeVariable($status),'status_update'=> 0);
        MessageLog::where('message_id',sanitizeVariable($old_sid))->update($data);
    }

    public static function updateStatus($mid, $status){
        $data = array('status'=>sanitizeVariable($status),'status_update'=> 1);
        MessageLog::where('message_id',sanitizeVariable($mid))->update($data);
    }
	
	 public static function MFAupdateStatus($mid, $status){
        $data = array('status'=>sanitizeVariable($status),'status_update'=> 1);
        Log::info($mid."fetch status is ".$status);   
        MfaTextingLog::where('message_id',sanitizeVariable($mid))->update($data);
    }
    
    //record time  
    public static function recordTimeSpent($start_time = null, $end_time = null, $patient_id = null, $module_id = null, $component_id = null, $stage_id = null, $billable = null, $uid = null, $step_id = null, $form_name = null,$callwrap_id=null,$activity=null,$activity_id=null,$comment=null)
    {

        // dd($stage_id,$step_id);   
        $start_time   = sanitizeVariable($start_time);
        $end_time     = sanitizeVariable($end_time);
        $patient_id   = sanitizeVariable($patient_id); 
        $module_id    = sanitizeVariable($module_id);
        $component_id = sanitizeVariable($component_id);
        $stage_id     = sanitizeVariable($stage_id);
        $billable     = sanitizeVariable($billable); 
        $uid          = sanitizeVariable($uid);
        $step_id      = sanitizeVariable($step_id);
        $form_name    = sanitizeVariable($form_name);
        $activity   = sanitizeVariable($activity);
         $activityid   = sanitizeVariable($activity_id);
         $callwrapid= sanitizeVariable($callwrap_id);
          $comments= sanitizeVariable($comment);
        if($start_time == null || $start_time == "" || $start_time == 'undefined') { $start_time = '00:00:00'; }
        if($end_time == null || $end_time == "" || $end_time == 'undefined') { $end_time = '00:00:00'; }

        $net_time   = sanitizeVariable(getNetTime($start_time, $end_time));
        $timer_data = array(
            'uid'          => $patient_id,
            'patient_id'   => $patient_id,
            'record_date'  => Carbon::now(), 
            'module_id'    => $module_id,
            'component_id' => $component_id,
            'timer_on'     => $start_time,
            'timer_off'    => $end_time,
            'net_time'     => $net_time,
            'billable'     => $billable,
            'stage_code'   => $step_id,
            'form_name'    => $form_name,
            'created_by'   => session()->get('userid'),
            // 'status'       => 1,
            'stage_id'     => $stage_id,
            'callwrap_id' => $callwrapid,
            'activity'    => $activity,
            'activity_id' => $activityid,
            'comment'=>$comments
        );
        $insert_query = PatientTimeRecords::create($timer_data);  
        $assignpatient = assingSessionUser($patient_id);
        
    }

    //record time manually (record time spent on particular model)
    public static function recordTimeSpentManually(Request $request)
    {
        //record time
        $start_time   = sanitizeVariable($request->timerStart);
        $end_time     = sanitizeVariable($request->timerEnd);
        $uid          = sanitizeVariable($request->uId);
        $module_id    = sanitizeVariable($request->moduleId);
        $component_id = sanitizeVariable($request->subModuleId);
        $stage_id     = sanitizeVariable($request->stageId);
        $billable     = sanitizeVariable($request->billable);
        $patient_id   = sanitizeVariable($request->patientId); 
        $step_id      = sanitizeVariable($request->stepId);
        $form_name    = sanitizeVariable($request->formName);

        if($start_time == null || $start_time == "" || $start_time == 'undefined') { $start_time = '00:00:00'; }
        if($end_time == null || $end_time == "" || $end_time == 'undefined') { $end_time = '00:00:00'; }

        $net_time   = getNetTime($start_time, $end_time);
        
        $timer_data = array(
            'uid'          => $uid,
            'patient_id'   => $patient_id,
            'record_date'  => Carbon::now(),
            'module_id'    => $module_id,
            'component_id' => $component_id,
            'timer_on'     => $start_time,
            'timer_off'    => $end_time,
            'net_time'     => $net_time,
            'billable'     => $billable,
            'stage_code'   => $step_id,
            'form_name'    => $form_name,
            'created_by'   => session()->get('userid'),
            // 'status'       => 1,
            'stage_id'     => $stage_id
        );
        $insert_query = PatientTimeRecords::create($timer_data);
  
        if($insert_query) {
            return $end_time;
        } else {
            return "";
        }
    }

    //manually adjust time(user can increase or decrease time manually)
    public static function manuallyAdjustTime(ManuallyAdjustTimeRequest $request)
    {
        //record time
        $module_id       = sanitizeVariable($request->module_id);
        $component_id    = sanitizeVariable($request->submodule_id);
        $stage_id        = sanitizeVariable(0);
        $step_id         = sanitizeVariable(0);
        $form_name       = sanitizeVariable($request->form_name);
        $patient_id      = sanitizeVariable($request->id); 
        $uid             = sanitizeVariable($request->id);
        $start_time      = sanitizeVariable(str_replace("-","",$request->start_time));
        $time            = sanitizeVariable($request->time);
        $time_to         = sanitizeVariable($request->time_to);
        $billable         = sanitizeVariable($request->billable);
        $comment         = sanitizeVariable($request->comment);
        $start           = sanitizeVariable(strtotime($start_time)); 
        $end             = sanitizeVariable(strtotime($time)); 
       // dd($billable);
        if($time_to==0){
            $totaltime   = ($start - $end); 
            // $str_time_seconds = convertTimeToSeceonds($start_time);
            // $end_time_seconds = convertTimeToSeceonds($start_time);
            // $time_diff        = ($str_time_seconds - $end_time_seconds);
            // $t                = round($time_diff);
            // $totaltime        = sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
            // $end_time         = $totaltime;
        } else if($time_to==1){
            $totaltime   = ($start + $end); 
        }
        $end_time        = sanitizeVariable(date("H:i:s",$totaltime));
        //$billable        = sanitizeVariable(1);
        $care_manager_id = sanitizeVariable($request->care_manager_id);
        $totaltime       = sanitizeVariable($totaltime);

        if($start_time == null || $start_time == "" || $start_time == 'undefined') { $start_time = '00:00:00'; }
        if($end_time == null || $end_time == "" || $end_time == 'undefined') { $end_time = '00:00:00'; }

        $timer_data = array(
            'uid'          => $uid,
            'patient_id'   => $patient_id,
            'record_date'  => Carbon::now(),
            'module_id'    => $module_id,
            'component_id' => $component_id,
            'timer_on'     => $start_time,
            'timer_off'    => $end_time,
            'net_time'     => $time,
            'billable'     => $billable,
            'created_by'   => $care_manager_id, // session()->get('userid'),
            // 'status'       => 1,
            'comment'      => $comment,
            'stage_id'     => $stage_id,
            'stage_code'   => $step_id,
            'form_name'    => $form_name,
            'adjust_time'  => sanitizeVariable($request->time_to)
        );
        $insert_query = PatientTimeRecords::create($timer_data); 
        $tid        = $insert_query->id;
        $data = array(
            'comment'      => $comment,
            'adjust_time'  => $request->time_to
        );
        $update_query = PatientTimeRecords::where('id', $tid)->where('uid', $uid)->update($data);
        if($insert_query) {
            return "Saved Successfully.";
        } else {
            return "";
        }
    }

    //Check if this month’s data exists for PatientDiagnosis; If not, copy from last month
    public static function checkPatientDiagnosisDataExistForCurrentMonthOrCopyFromLastMonth($patient_id)
    {
        $check_exist_code  = PatientDiagnosis::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
        if (isset($check_exist_code) && ($check_exist_code == false || $check_exist_code == null || $check_exist_code == "" )) {
        // if($check_exist_code) {
            $getMaxDateForPreviousPatientDiagnosisData = PatientDiagnosis::where('patient_id', $patient_id)->where('status',1)->max('created_at');
            $month = Carbon::parse($getMaxDateForPreviousPatientDiagnosisData)->month;
            $year = Carbon::parse($getMaxDateForPreviousPatientDiagnosisData)->year;
            $user_id = session()->get('userid'); 
            $current_timestamp = Carbon::now();
            //remove comments column as per the Juliets email on 21st May 21 --priya on 6th jun 2021
            $lastMonthPatientDiagnosisQuery = 'INSERT INTO patients.patient_diagnosis_codes ( "code", "status", "condition", "goals", "symptoms", "tasks", "support","patient_id", "uid", "diagnosis", "created_by", "created_at", "updated_at" )
            ( SELECT "code", "status", "condition", "goals", "symptoms", "tasks", "support","patient_id", "uid", "diagnosis", \''.$user_id.'\', \''.$current_timestamp.'\', \''.$current_timestamp.'\' FROM patients.patient_diagnosis_codes WHERE "patient_id" = '.$patient_id.' and EXTRACT(MONTH FROM "created_at") = '.$month.' and EXTRACT(year FROM "created_at") = '.$year.' )';
            $executeLastMonthPatientDiagnosisQuery = queryEscape($lastMonthPatientDiagnosisQuery);
            // $lastMonthPatientDiagnosis = PatientDiagnosis::where('patient_id', $patient_id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
            // if($lastMonthPatientDiagnosis) {
            //     foreach ($lastMonthPatientDiagnosis as $data) {
            //         $diagnosisData   = array(
            //             'code'       => $data->code,
            //             'condition'  => $data->condition,
            //             'goals'      => $data->goals,
            //             'symptoms'   => $data->symptoms,
            //             'tasks'      => $data->tasks,
            //             'support'    => $data->support,
            //             'comments'   => $data->comments,
            //             'patient_id' => $patient_id,
            //             'uid'        => $patient_id,
            //             'diagnosis'  => $data->diagnosis,
            //             'created_by' => session()->get('userid')
            //         ); 
            //         PatientDiagnosis::create($diagnosisData);
            //     }
            // }
        }
    }

    //Check if this month’s data exists for Medication; If not, copy from last month
    public static function checkPatientMedicationDataExistForCurrentMonthOrCopyFromLastMonth($patient_id)
    {
        $check_exist_medication  = PatientMedication::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
        // echo "=>".$check_exist_medication."<=";print_r($check_exist_medication);die;
        // dd($check_exist_medication);
        if(isset($check_exist_medication) && ($check_exist_medication == false || $check_exist_medication == null || $check_exist_medication == "" )) {
        // if($check_exist_medication) { 
            $getMaxDateForPreviousPatientMedicationData = PatientMedication::where('patient_id', $patient_id)->where('status',1)->max('created_at');
            $month = Carbon::parse($getMaxDateForPreviousPatientMedicationData)->month;
            $year = Carbon::parse($getMaxDateForPreviousPatientMedicationData)->year;
            $user_id = session()->get('userid');
            $current_timestamp = Carbon::now(); 
            $lastMonthPatientMedicationQuery = 'INSERT INTO patients.patient_medication ( "med_id","status", "purpose", "description", "strength", "dosage", "frequency", "route", "patient_id", "uid", "duration", "drug_reaction", "med_name", "pharmacogenetic_test", "created_by", "created_at", "updated_at" )
            (SELECT "med_id","status", "purpose", "description", "strength", "dosage", "frequency", "route", "patient_id", "uid", "duration", "drug_reaction", "med_name", "pharmacogenetic_test", \''.$user_id.'\', \''.$current_timestamp.'\', \''.$current_timestamp.'\' FROM patients.patient_medication WHERE "patient_id" = '.$patient_id.' and EXTRACT(MONTH FROM "created_at") = '.$month.' and EXTRACT(year FROM "created_at") = '.$year.' )';
            $executeLastMonthPatientMedicationQuery = queryEscape($lastMonthPatientMedicationQuery);
            // $lastMonthMedication = PatientMedication::where('patient_id', $patient_id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
            // if($lastMonthMedication) {
            //     foreach ($lastMonthMedication as $medData) {
            //         $insert_medicationData = array(
            //             'patient_id'           => $patient_id,
            //             'uid'                  => $patient_id,
            //             'med_id'               => $medData->med_id,
            //             'purpose'              => $medData->purpose,
            //             'description'          => $medData->description,
            //             'strength'             => $medData->strength,
            //             'dosage'               => $medData->dosage,
            //             'frequency'            => $medData->frequency,
            //             'route'                => $medData->route,
            //             'duration'             => $medData->duration,
            //             'drug_reaction'        => $medData->drug_reaction,
            //             'med_name'             => $medData->med_name,
            //             'pharmacogenetic_test' => $medData->pharmacogenetic_test,
            //             'created_by'           => session()->get('userid')
            //         );
            //         PatientMedication::create($insert_medicationData);
            //     }
            // }
        }
    }

    //Check if this month’s data exists for PatientAllergy; If not, copy from last month
    public static function checkPatientAllergyDataExistForCurrentMonthOrCopyFromLastMonth($patient_id){
        $check_exist_allergy  = PatientAllergy::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
        if (isset($check_exist_allergy) && ($check_exist_allergy == false || $check_exist_allergy == null || $check_exist_allergy == "" )) {
        // if($check_exist_allergy) {
            $getMaxDateForPreviousPatientAllergyData = PatientAllergy::where('patient_id', $patient_id)->where('status',1)->max('created_at');
            $month = Carbon::parse($getMaxDateForPreviousPatientAllergyData)->month;
            $year = Carbon::parse($getMaxDateForPreviousPatientAllergyData)->year;
            $user_id = session()->get('userid');
            $current_timestamp = Carbon::now();
            $lastMonthPatientAllergyQuery = 'INSERT INTO patients.patient_allergy ( "allergy_type", "type_of_reactions", "severity", "course_of_treatment", "notes","status","specify", "patient_id", "uid", "created_by", "created_at", "updated_at" )
            ( SELECT "allergy_type", "type_of_reactions", "severity", "course_of_treatment", "notes","status","specify", "patient_id", "uid", \''.$user_id.'\', \''.$current_timestamp.'\', \''.$current_timestamp.'\' FROM patients.patient_allergy WHERE "patient_id" = '.$patient_id.' and EXTRACT(MONTH FROM "created_at") = '.$month.' and EXTRACT(year FROM "created_at") = '.$year.' )';
            $executeLastMonthPatientAllergyQuery = queryEscape($lastMonthPatientAllergyQuery);
            // $lastMonthAllergy = PatientAllergy::where('patient_id', $patient_id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
            // if($lastMonthAllergy) {
            //     foreach ($lastMonthAllergy as $allergyData) {
            //         $insert_allergy = array(
            //             'patient_id'          => $patient_id,
            //             'uid'                 => $patient_id,
            //             'allergy_type'        => $allergyData->allergy_type,
            //             'type_of_reactions'   => $allergyData->type_of_reactions,
            //             'severity'            => $allergyData->severity,
            //             'course_of_treatment' => $allergyData->course_of_treatment,
            //             'notes'               => $allergyData->notes,
            //             'specify'             => $allergyData->specify,
            //             'created_by'          => session()->get('userid')
            //         );
            //         PatientAllergy::create($insert_allergy);
            //     }
            // }
        }
    }

    //Check if this month’s data exists for PatientVitalsData; If not, copy from last month
    public static function checkPatientVitalsDataExistForCurrentMonthOrCopyFromLastMonth($patient_id)
    {
        $check_exist_patient_vital  = PatientVitalsData::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
        if (isset($check_exist_patient_vital) && ($check_exist_patient_vital == false || $check_exist_patient_vital == null || $check_exist_patient_vital == "" )) {
        // if($check_exist_patient_vital){
            // $getMaxDateForPreviousPatientVitalsDataData = PatientVitalsData::where('patient_id', $patient_id)->max('created_at');
            // $month = Carbon::parse($getMaxDateForPreviousPatientVitalsDataData)->month;
            // $year = Carbon::parse($getMaxDateForPreviousPatientVitalsDataData)->year;
            /*$user_id = session()->get('userid');
            $current_timestamp = Carbon::now();
            $lastMonthPatientVitalsDataQuery = 'INSERT INTO patients.patient_vitals ( "rec_date", "height", "weight", "bmi", "bp", "diastolic", "o2", "pulse_rate", "other_vitals", "patient_id", "uid", "created_by", "created_at", "updated_at" )
            ( SELECT \''.$current_timestamp.'\', "height", "weight", "bmi", "bp", "diastolic", "o2", "pulse_rate", "other_vitals", "patient_id", "uid", \''.$user_id.'\', \''.$current_timestamp.'\', \''.$current_timestamp.'\' FROM patients.patient_vitals WHERE "patient_id" = '.$patient_id.' order by id desc limit 1 )';
            $executeLastMonthPatientVitalsDataQuery = queryEscape($lastMonthPatientVitalsDataQuery);*/
            // $lastMonthPatientVitalsData= PatientVitalsData::where('patient_id', $patient_id)->get()->last();
            // if($lastMonthPatientVitalsData) {
            //     $insert_vital   = array(
            //         'patient_id'    => $patient_id,
            //         'uid'           => $patient_id,
            //         'rec_date'      => Carbon::now(),
            //         'height'        => $lastMonthPatientVitalsData["height"],
            //         'weight'        => $lastMonthPatientVitalsData["weight"],
            //         'bmi'           => $lastMonthPatientVitalsData["bmi"],
            //         'bp'            => $lastMonthPatientVitalsData["bp"],
            //         'diastolic'     => $lastMonthPatientVitalsData["diastolic"],
            //         'o2'            => $lastMonthPatientVitalsData["o2"],
            //         'pulse_rate'    => $lastMonthPatientVitalsData["pulse_rate"],
            //         'other_vitals'  => $lastMonthPatientVitalsData["other_vitals"],
            //         'created_by'    => session()->get('userid')
            //     );
            //     PatientVitalsData::create($insert_vital);
            // }
        }
    }

    //Check if this month’s data exists for PatientLabRecs; If not, copy from last month
    public static function checkPatientLabRecsDataExistForCurrentMonthOrCopyFromLastMonth($patient_id)
    {
        $check_exist_patient_labs  = PatientLabRecs::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
        if ($check_exist_patient_labs == false) {
   
            $getMaxDateForPreviousPatientLabRecsData = PatientLabRecs::where('patient_id', $patient_id)->max('created_at');
            $month = Carbon::parse($getMaxDateForPreviousPatientLabRecsData)->month;
            $year = Carbon::parse($getMaxDateForPreviousPatientLabRecsData)->year;
            $user_id = session()->get('userid');
            $current_timestamp = Carbon::now();
           
              $lastMonthPatientLabRecsData = PatientLabRecs::select('lab_test_id', DB::raw('max(DATE(patients.patient_lab_recs.created_at))as date'))
                    ->whereMonth('created_at', $month)->whereYear('created_at', $year)
                    ->where('patient_id',$patient_id)
                    ->groupBy('lab_test_id')
                    ->get();
                              
            foreach ($lastMonthPatientLabRecsData as $labData) {
               
               $lastMonthPatientLabRecsData1= PatientLabRecs::where('patient_id', $patient_id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->where('lab_test_id',$labData->lab_test_id)->get();
                
                  foreach ($lastMonthPatientLabRecsData1 as $labDataParameter) {                    

                    $insert_lab =array(
                        'patient_id'            => $patient_id,
                        'uid'                   => $patient_id,
                        'rec_date'              => $labDataParameter->rec_date,
                        'lab_test_id'           => $labData->lab_test_id,
                        'lab_test_parameter_id' => $labDataParameter->lab_test_parameter_id,
                        'reading'               => $labDataParameter->reading,
                        'high_val'              => $labDataParameter->high_val,
                        'notes'                 => $labDataParameter->notes,
                        'created_by'            => session()->get('userid'),
                        'lab_date'              => $labDataParameter->lab_date
                      );     

                  PatientLabRecs::create($insert_lab);
                }
             }         
        }
    }

    //Check if this month's data exist for PatientAllergies; If not, copy from last month
    public static function checkPatientAllergyDataExistForCurrentMonthOrCopyFromLastMonthBasedOnAllergyType($patient_id, $allergyType) {
        $dataexist = PatientAllergy::where("patient_id", $patient_id)
        ->where("allergy_type",$allergyType)
        ->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
        // dd($dataexist);
        $getMaxDateForPreviousPatientAllergyData = PatientAllergy::where('patient_id', $patient_id)->where("allergy_type",$allergyType)->where("status",1)->max('created_at');
        //dd($getMaxDateForPreviousPatientAllergyData);
        $month = Carbon::parse($getMaxDateForPreviousPatientAllergyData)->month;
        $year = Carbon::parse($getMaxDateForPreviousPatientAllergyData)->year;
        if(isset($dataexist) && ($dataexist==true || $dataexist != null || $dataexist != "" )) {
        // if($dataexist){
            $data = PatientAllergy::with('users')->where("patient_id", $patient_id)
            ->where("allergy_type",$allergyType)->where("status",1)->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))->orderBy('created_at','desc')->get();
        } else {
            $data = PatientAllergy::with('users')->where("patient_id", $patient_id)
            ->where("allergy_type",$allergyType)->where("status",1)->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)->get();
            // dd($data);
            if($data) {
                foreach ($data as $allergyData) {
                    $insert_allergy = array(
                        'uid'                => $patient_id,
                        'patient_id'         => $patient_id,
                        'allergy_type'       => $allergyData->allergy_type,
                        'type_of_reactions'  => $allergyData->type_of_reactions,
                        'severity'           => $allergyData->severity,
                        'course_of_treatment'=> $allergyData->course_of_treatment,
                        'notes'              => $allergyData->notes,
                        'specify'            => $allergyData->specify,
                        'status'             => 1
                    );
                    PatientAllergy::create($insert_allergy);
                }
            }
        }
        return $data;
    }

    //Check if this month's data exist for PatientHealthServices; If not, copy from last month
    public static function checkPatientHealthServicesDataExistForCurrentMonthOrCopyFromLastMonthBasedOnHealthServicesType($patient_id, $servicetype) {
        $dataexist = PatientHealthServices::where("patient_id", $patient_id)->where("hid",$servicetype)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
        // dd($dataexist);
        $getMaxDateForPreviousPatientHealthServicesData = PatientHealthServices::where('patient_id', $patient_id)->max('created_at');
        $month = Carbon::parse($getMaxDateForPreviousPatientHealthServicesData)->month;
        $year = Carbon::parse($getMaxDateForPreviousPatientHealthServicesData)->year;
        $lastMonthService=""; 
        if(isset($dataexist) && ($dataexist==true || $dataexist != null || $dataexist != "" )) { 
            $data = PatientHealthServices::where("patient_id", $patient_id)->where('status',1)->where("hid",$servicetype)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->orderBy('created_at', 'desc')->with('users')->get();
        } else {
            $data = PatientHealthServices::with('users')->where('patient_id', $patient_id)->where('status',1)->where("hid",$servicetype)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
            if(isset($data)) {
                foreach ($data as $serviceData) {
					$service_end_year = date('Y', strtotime($serviceData['service_end_date']));
					$service_start_year = date('Y', strtotime($serviceData['service_start_date']));
					
                    if($serviceData['service_end_date']== '' || $service_end_year == '1969' || $service_end_year == '1970' ){
                            $service_end_date = NULL;
                    } else {
                            $service_end_date = $serviceData['service_end_date'];
                    }
                    if($serviceData['service_start_date'] == '' || $service_start_year = '1969' || $service_start_year == '1970'){
                        $service_start_date = NULL; //$serviceData->service_start_date.' 00:00:00';
                    } else {
                        $service_start_date = $serviceData['service_start_date'];
                    }
                    $insert_serviceData = array(
                        'patient_id'           => $patient_id,
                        'uid'                  => $patient_id,
                        'hid'                  => $servicetype,
                        'type'                 => $serviceData['type'],
                        'from_whom'            => $serviceData['from_whom'],
                        'from_where'           => $serviceData['from_where'], 
                        'frequency'            => $serviceData['frequency'],
                        'duration'             => $serviceData['duration'],
                        'brand'                => $serviceData['brand'],
                        'purpose'              => $serviceData['purpose'],
                        'specify'              => $serviceData['specify'],
                        'notes'                => $serviceData['notes'],
                        'created_by'           => session()->get('userid'),
                        'service_start_date'   => $service_start_date,
                        'service_end_date'     => $service_end_date,
                        'status'               => 1
                    );
                    PatientHealthServices::create($insert_serviceData);
                }
            }
        }
        return $data;
    }

    //Check if this month’s data exists for PatientImaging; If not, copy from last month
    public static function checkPatientImagingDataExistForCurrentMonthOrCopyFromLastMonth($patient_id)
    {
        $check_exist_patient_imaging  = PatientImaging::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
        if(isset($check_exist_patient_imaging) && ($check_exist_patient_imaging == false || $check_exist_patient_imaging == null || $check_exist_patient_imaging == "" )) {
            $getMaxDateForPreviousPatientImagingData = PatientImaging::where('patient_id', $patient_id)->max('created_at');
            $month = Carbon::parse($getMaxDateForPreviousPatientImagingData)->month;
            $year = Carbon::parse($getMaxDateForPreviousPatientImagingData)->year;
            $user_id = session()->get('userid');
            $current_timestamp = Carbon::now();
            $lastMonthPatientImagingDataQuery = 'INSERT INTO patients.patient_imaging ( "imaging_details","imaging_date", "patient_id", "uid", "created_by", "created_at", "updated_at" )
            ( SELECT "imaging_details", "imaging_date", "patient_id", "uid", \''.$user_id.'\', \''.$current_timestamp.'\', \''.$current_timestamp.'\' FROM patients.patient_imaging WHERE "patient_id" = '.$patient_id.' and EXTRACT(MONTH FROM "created_at") = '.$month.' and EXTRACT(year FROM "created_at") = '.$year.' )';
            $executeLastMonthPatientImagingDataQuery = queryEscape($lastMonthPatientImagingDataQuery);
            // $lastMonthPatientImagingData= PatientImaging::where('patient_id', $patient_id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
            // if($lastMonthPatientImagingData) {
            //     foreach ($lastMonthPatientImagingData as $imagingData) {
            //         // echo "(".$imagingData.")";
            //         $insert_imaging =array(
            //             'patient_id'            => $patient_id,
            //             'uid'                   => $patient_id,
            //             'imaging_details'       => $imagingData->imaging_details,
            //             'created_by'            => session()->get('userid')
            //         );
            //         PatientImaging::create($insert_imaging);
            //     }
            // }
        }
    }

    //Check if this month’s data exists for PatientHealthData; If not, copy from last month
    public static function checkPatientHealthDataExistForCurrentMonthOrCopyFromLastMonth($patient_id)
    {   
        $check_exist_patient_health_data  = PatientHealthData::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
        if(isset($check_exist_patient_health_data) && ($check_exist_patient_health_data == false || $check_exist_patient_health_data == null || $check_exist_patient_health_data == "" )) {
            $getMaxDateForPreviousPatientHealthDataData = PatientHealthData::where('patient_id', $patient_id)->max('created_at');
            $month = Carbon::parse($getMaxDateForPreviousPatientHealthDataData)->month;
            $year = Carbon::parse($getMaxDateForPreviousPatientHealthDataData)->year;
            $user_id = session()->get('userid');
            $current_timestamp = Carbon::now(); 
            $lastMonthPatientHealthDataDataQuery = 'INSERT INTO patients.patient_health_data ("health_data","health_date", "patient_id","created_by", "created_at", "updated_at" )
            ( SELECT "health_data", "health_date", "patient_id", \''.$user_id.'\', \''.$current_timestamp.'\', \''.$current_timestamp.'\' FROM patients.patient_health_data WHERE "patient_id" = '.$patient_id.' and EXTRACT(MONTH FROM "created_at") = '.$month.' and EXTRACT(year FROM "created_at") = '.$year.' )';
            $executeLastMonthPatientHealthDataDataQuery = queryEscape($lastMonthPatientHealthDataDataQuery);
            // $lastMonthPatientHealthData= PatientHealthData::where('patient_id', $patient_id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
            // if($lastMonthPatientHealthData) {
            //     foreach ($lastMonthPatientHealthData as $healthData) { 
            //         $insert_health =array(
            //             'patient_id'            => $patient_id,
            //             'uid'                   => $patient_id,
            //             'health_data'           => $healthData->health_data,
            //             'created_by'            => session()->get('userid')
            //         );
            //         PatientHealthData::create($insert_health);
            //     }
            // }
        }
    }


    // //Check if this month’s data exists for PatientCareGiverData; If not, copy from last month
    // public static function checkPatientCareGiverDataExistForCurrentMonthOrCopyFromLastMonth($patient_id)
    // {
    //     $check_exist_patient_care_giver_data  = PatientImaging::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
    //     if(isset($check_exist_patient_care_giver_data) && ($check_exist_patient_care_giver_data == false || $check_exist_patient_care_giver_data == null || $check_exist_patient_care_giver_data == "" )) {
    //         $getMaxDateForPreviousPatientCareGiverData = PatientHealthData::where('patient_id', $patient_id)->max('created_at');
    //         $month = Carbon::parse($getMaxDateForPreviousPatientCareGiverData)->month;
    //         $year = Carbon::parse($getMaxDateForPreviousPatientCareGiverData)->year;
    //         $lastMonthPatientCareGiverData= PatientHealthData::where('patient_id', $patient_id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
    //         if($lastMonthPatientCareGiverData) {
    //             foreach ($lastMonthPatientCareGiverData as $servicesData) {
    //                 $insert_health =array(
    //                     'patient_id'            => $patient_id,
    //                     'uid'                   => $patient_id,
    //                     'imaging_details'       => $healthData->imaging_details,
    //                     'created_by'            => session()->get('userid')
    //                 );
    //                 PatientHealthData::create($insert_health);
    //             }
    //         }
    //     }
    // }

    public function getTotalBillableAndNonBillableTime($patientID, $moduleId){
        $patient_id                     = sanitizeVariable($patientID);
        $module_id                      = sanitizeVariable($moduleId);
        $timeArray                      = [];

        $billableTime                   = $this->getCcmMonthlyNetTime($patient_id, $module_id);
        $checkBillableTime              = (isset($billableTime) && ($billableTime!='0')) ? $billableTime : '00:00:00';
        $timeArray['billable_time']     = $checkBillableTime;

        $nonBillableTime                = $this->getNonBillabelTime($patient_id, $module_id);
        $checkNonBillableTime           = (isset($nonBillableTime) && ($nonBillableTime!='0')) ? $nonBillableTime : '00:00:00';
        $timeArray['non_billable_time'] = $checkNonBillableTime;

        $totalTime                      = date("H:i:s",strtotime($checkBillableTime)+strtotime($checkNonBillableTime));
        $returnTotalTime                = (isset($totalTime) && ($totalTime!='0')) ? $totalTime : '00:00:00';
        $timeArray['total_time']        = $returnTotalTime;

        return $timeArray;
    }

    //log which timer button is clicked with time 
    public static function logTimerButtonAction(Request $request)
    {
        $start_time   = sanitizeVariable($request->timer_on);
        $end_time     = sanitizeVariable($request->timer_off);
        $module_id    = sanitizeVariable($request->module_id);
        $component_id = sanitizeVariable($request->component_id);
        $patient_id   = sanitizeVariable($request->patient_id); 
        $uid          = sanitizeVariable($request->uid);
        $action_taken = sanitizeVariable($request->action_taken);

        if($start_time == null || $start_time == "" || $start_time == 'undefined') { $start_time = '00:00:00'; }
        if($end_time == null || $end_time == "" || $end_time == 'undefined') { $end_time = '00:00:00'; }

        $net_time   = getNetTime($start_time, $end_time);
        $timer_data = array(
            'timer_on'     => $start_time,
            'timer_off'    => $end_time,
            'net_time'     => $net_time,
            'module_id'    => $module_id,
            'component_id' => $component_id,
            'patient_id'   => $patient_id,
            'uid'          => $uid,
            'action_taken' => $action_taken,
            'created_by'   => session()->get('userid'),
        );
        $insert_query = PatientTimeButtonLogs::create($timer_data);
        if($insert_query) {
            return $end_time;
        } else {
            return "";
        }
    }

    //get session logout time with popup time
    public static function getSessionLogoutTimeWithPopupTime(Request $request)
    {
        $data = DomainFeatures::getSessionLogoutTimeWithPopupTime();
        if($data) {
            return $data;
        } else {
            return "";
        }
    }

    public static function sentSchedulMessage($module_id,$uid,$stage_id){
        $scripts = ContentTemplate::where('stage_id', $stage_id)->where('status', 1)->get();
        $patient_providers = PatientProvider::where('patient_id', $uid)->where('is_active',1)
        ->with('practice')->with('provider')->with('users')->where('provider_type_id',1)->orderby('id','desc')->first();
        $patient = Patients::where('id', $uid)->get();
        $PatientDevices = PatientDevices::where('patient_id', $uid)->where('status',1)->latest()->first();
        $nin = array();
        if(isset($PatientDevices->vital_devices)){
            $dv = $PatientDevices->vital_devices;
            $js = json_decode($dv);
            foreach($js as $val){
                if(isset($val->vid)){
                    array_push($nin,$val->vid);
                }
            }
        }
        $device = Devices::whereIn('id',$nin)->pluck('device_name')->implode(', ');
        if(isset($PatientDevices->device_code)){
            $devicecode = $PatientDevices->device_code;
        }else{
            $devicecode = "";
        }
        $intro = get_object_vars(json_decode($scripts[0]->content));
        $provider_data = (array)$patient_providers; 
        $provider_name = empty($patient_providers->provider['name']) ? '[provider]' : $patient_providers->provider['name'];
        $practice_name = empty($patient_providers['practice']['name']) ? '' : $patient_providers['practice']['name'];
        $replace_provider = str_replace("[provider]", $provider_name, $intro['message']);
        $replace_practice_name = str_replace("[practice_name]", $practice_name, $replace_provider);
    
        $replace_user = str_replace("[users_name]", Session::get('f_name')." ".Session::get('l_name'), $replace_practice_name);
        $replace_pt = str_replace("[patient_name]",$patient[0]->fname.' '.$patient[0]->lname, $replace_user);
        $replace_id = str_replace("[patientid]",$patient[0]->id, $replace_pt);
        $replace_primary = str_replace("[primary_contact_number]",$patient[0]->mob, $replace_id);
        $data_emr = str_replace("[EMR]",$patient_providers['practice_emr'],$replace_primary);
        $replace_secondary = str_replace("[secondary_contact_number]",$patient[0]->home_number, $data_emr);
        $replace_devicelist = str_replace("[device_list]",$device, $replace_secondary);
        $replace_final = str_replace("[devicecode]", $devicecode, $replace_devicelist);
        $replace_final = strip_tags($replace_final);

        if($patient[0]->consent_to_text == 1){ 
            if($patient[0]->primary_cell_phone == 1){
                $phn = $patient[0]->country_code.''.$patient[0]->mob;
                $errormsg = sendTextMessage($phn, $replace_final, $uid, $module_id, $stage_id);
            }else{
                $phn = $patient[0]->secondary_country_code.''.$patient[0]->home_number;
                $errormsg = sendTextMessage($phn, $replace_final, $uid, $module_id, $stage_id);
            }
        }
    }

    public function copyPreviousMonthDataToThisMonth(Request $request) {
		//
        $patient_id                  = sanitizeVariable($request->patient_id); 
        $moduleId                   = sanitizeVariable($request->module_id); 
		// check in patient_previous_data_copy_status if status was updated. if not only then execute following steps
		
        // Check if this month’s data exists for PatientDiagnosis; If not, copy from last month
        $check_exist_code           = $this->checkPatientDiagnosisDataExistForCurrentMonthOrCopyFromLastMonth($patient_id);

        //Check if this month’s data exists for Medication; If not, copy from last month
        $check_exist_medication     = $this->checkPatientMedicationDataExistForCurrentMonthOrCopyFromLastMonth($patient_id);  
        
        //Check if this month’s data exists for PatientAllergy; If not, copy from last month
        $check_exist_allergy        = $this->checkPatientAllergyDataExistForCurrentMonthOrCopyFromLastMonth($patient_id);
        $checkdrugexist             =  $this->checkPatientAllergyDataExistForCurrentMonthOrCopyFromLastMonthBasedOnAllergyType($patient_id,'drug');  
        $checkfoodexist             =  $this->checkPatientAllergyDataExistForCurrentMonthOrCopyFromLastMonthBasedOnAllergyType($patient_id,'food');
        $checkenvironmentexist      =  $this->checkPatientAllergyDataExistForCurrentMonthOrCopyFromLastMonthBasedOnAllergyType($patient_id,'enviromental');
        $checkinsectexist           =  $this->checkPatientAllergyDataExistForCurrentMonthOrCopyFromLastMonthBasedOnAllergyType($patient_id,'insect');
        $checklatexexist            =  $this->checkPatientAllergyDataExistForCurrentMonthOrCopyFromLastMonthBasedOnAllergyType($patient_id,'latex');
        $checkpetxexist             =  $this->checkPatientAllergyDataExistForCurrentMonthOrCopyFromLastMonthBasedOnAllergyType($patient_id,'petrelated');
        $checkotherxexist           =  $this->checkPatientAllergyDataExistForCurrentMonthOrCopyFromLastMonthBasedOnAllergyType($patient_id,'other');
        //Check if this month’s data exists for PatientVitalsData; If not, copy from last month
        $check_exist_patient_vital  = $this->checkPatientVitalsDataExistForCurrentMonthOrCopyFromLastMonth($patient_id);
        
        //Check if this month’s data exists for PatientLabRecs; If not, copy from last month
        $check_exist_patient_labs   = $this->checkPatientLabRecsDataExistForCurrentMonthOrCopyFromLastMonth($patient_id);

        //Check if this month’s data exists for PatientImaging; If not, copy from last month
        $check_exist_patient_labs  = CommonFunctionController::checkPatientImagingDataExistForCurrentMonthOrCopyFromLastMonth($patient_id);
        
        //Check if this month’s data exists for PatientHealthData; If not, copy from last month
        $check_exist_patient_labs  = CommonFunctionController::checkPatientHealthDataExistForCurrentMonthOrCopyFromLastMonth($patient_id);
        
        for($i = 1;  $i <= 7; $i++) { 
            //Check if this month’s data exists for PatientHealthServices; If not, copy from last month
            $check_exist_patient_labs  = CommonFunctionController::checkPatientHealthServicesDataExistForCurrentMonthOrCopyFromLastMonthBasedOnHealthServicesType($patient_id, $i);
        }
		//dd(\DB::getQueryLog());
        // $drugscnt                   = count($checkdrugexist);
        // $foodcount                  = count($checkfoodexist);
        // $envcount                   = count($checkenvironmentexist);
        // $insectcount                = count($checkinsectexist);
        // $latexcount                 = count($checklatexexist);
        // $petcount                   = count($checkpetxexist);
        // $othercount                 = count($checkotherxexist);

        // $countArray                 = [];
        // $countArray['drugscnt']     = $drugscnt;
        // $countArray['foodcount']    = $foodcount;
        // $countArray['envcount']     = $envcount;
        // $countArray['insectcount']  = $insectcount;
        // $countArray['latexcount']   = $latexcount;
        // $countArray['petcount']     = $petcount;
        // $countArray['othercount']   = $othercount;
        
        // return $countArray;
    }
}