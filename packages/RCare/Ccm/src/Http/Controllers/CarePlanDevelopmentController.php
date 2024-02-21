<?php

namespace RCare\Ccm\Http\Controllers;

use App\Http\Controllers\Controller;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Ccm\Models\CallPreparation;
use RCare\Ccm\Models\CallStatus;
use RCare\Ccm\Models\CallHipaaVerification;
use RCare\Ccm\Models\CallHomeServiceVerification;
use RCare\Ccm\Models\CallClose;
use RCare\Ccm\Models\CallWrap;
use RCare\Ccm\Models\FollowUp;
use RCare\Ccm\Models\TextMsg;
use RCare\Ccm\Models\PatientDataStatus;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
use RCare\Patients\Models\PatientPartResearchStudy;
use RCare\Patients\Models\PatientPersonalNotes;
use RCare\Patients\Models\PatientThreshold;
use RCare\Patients\Models\PatientAllergy;
use RCare\Patients\Models\PatientHealthServices;
use RCare\Patients\Models\PatientFamily;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientAddress;
use RCare\Patients\Models\PatientContactTime;
use RCare\Patients\Models\PatientVitalsData;
use RCare\Patients\Models\PatientHealthData;
use RCare\Patients\Models\PatientLabRecs;
use RCare\Org\OrgPackages\Labs\src\Models\LabsParam;
use RCare\Patients\Models\PatientDemographics;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\PatientMedication;
use RCare\Org\OrgPackages\Medication\src\Models\Medication;
use RCare\Org\OrgPackages\Diagnosis\src\Models\Diagnosis;
use RCare\Org\OrgPackages\Providers\src\Models\Providers;
use RCare\Org\OrgPackages\Practices\src\Models\Practices;
use RCare\Patients\Models\PatientDiagnosis;
use RCare\Patients\Models\PatientTravel;
use RCare\Patients\Models\PatientHobbies;
use RCare\Patients\Models\PatientPet;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientDevices;
use RCare\Patients\Models\PatientImaging;
use RCare\Patients\Models\CarePlanUpdateLogs;
use RCare\Org\OrgPackages\Diagnosis\src\Models\DiagnosisCode;
use RCare\Patients\Models\PatientFirstReview;
use RCare\Ccm\Http\Requests\AllergiesAddRequest;
use RCare\Ccm\Http\Requests\ServicesAddRequest;
use RCare\Ccm\Http\Requests\PatientsFamilyAddRequest;
use RCare\Ccm\Http\Requests\PatientsDataAddRequest;
use RCare\Ccm\Http\Requests\PatientsVitalsDataAddRequest;
use RCare\Ccm\Http\Requests\PatientsProvidersAddRequest;
use RCare\Ccm\Http\Requests\PatientsProviderSpecilistAddRequest;
use RCare\Ccm\Http\Requests\PatientsMedicationAddRequest;
use RCare\Ccm\Http\Requests\PatientsTravelAddRequest;
use RCare\Ccm\Http\Requests\PatientsHobbiesAddRequest;
use RCare\Ccm\Http\Requests\PatientsPetAddRequest;
use RCare\Ccm\Http\Requests\PatientsDiagnosisRequest;
use RCare\Ccm\Http\Requests\PatientsLabRequest;
use RCare\Ccm\Http\Requests\PatientsImagingRequest;
use RCare\Ccm\Http\Requests\PatientsRelativeAddRequest;
use RCare\Ccm\Http\Requests\PatientsHealthDataRequest;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Org\OrgPackages\StageCodes\src\Models\StageCode;
use RCare\Org\OrgPackages\HealthServices\src\Models\HealthServices;
use RCare\Org\OrgPackages\CarePlanTemplate\src\Models\CarePlanTemplate;
use RCare\Ccm\Models\ContentTemplateUsageHistory;
use RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate;
use RCare\Ccm\Models\QuestionnaireTemplatesUsageHistory;
use RCare\Org\OrgPackages\QCTemplates\src\Models\ContentTemplate;
use RCare\Patients\Models\PatientCareplanLastUpdateandReview;
use RCare\Patients\Models\View_Patient_Diagnosis_Age;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use DataTables;
use Carbon\Carbon;
use PDF;
use Session;

class CarePlanDevelopmentController extends Controller
{
    // created by radha 7dec2020
    public function getLabData(Request $request)
    {
        $patientId = sanitizeVariable($request->route('patientid'));
        $month     = date('m');
        $year      = date("Y");
        $dateS = Carbon::now()->startOfMonth()->subMonth(6);
        $dateE = Carbon::now()->endOfMonth();
        $component_name = sanitizeVariable($request->route('component_name'));
        // dd($component_name);
        $qry       = "select plr.patient_id,plr.lab_test_id, (case when plr.lab_test_id=0 then 'Other' else rlt.description end) as description,plr.lab_date, (case when rlt.description='COVID-19' then STRING_AGG (
                      plr.reading,
                      ',' ) else STRING_AGG (
                      rltpr.parameter || ' : ' || plr.high_val,
                      ',' ) end) as labparameter,plr.notes,(case when plr.lab_date is null then '0' else '1' end) as labdateexist
                      from patients.patient_lab_recs plr
                      left join ren_core.rcare_lab_tests rlt on rlt.id=plr.lab_test_id 
                      left join ren_core.rcare_lab_test_param_range rltpr on plr.lab_test_parameter_id = rltpr.id
                      where plr.lab_date is not null and plr.lab_test_id is not null and plr.patient_id=" . $patientId . "
                      and plr.lab_date::timestamp between '" . $dateS . "' and '" . $dateE . "' 
                      group  by plr.lab_date ,rlt.description,plr.patient_id,plr.lab_test_id,plr.notes
                      union 
                      select plr.patient_id,plr.lab_test_id, (case when plr.lab_test_id=0 then 'Other' else rlt.description end) as  description,plr.rec_date ,(case when rlt.description='COVID-19' then STRING_AGG (
                      plr.reading,
                      ',' ) else STRING_AGG (
                      rltpr.parameter || ' : ' || plr.high_val,
                      ',' ) end) as labparameter,plr.notes,(case when plr.lab_date is null then '0' else '1' end) as labdateexist
                      from patients.patient_lab_recs plr
                      left join ren_core.rcare_lab_tests rlt on rlt.id=plr.lab_test_id 
                      left join ren_core.rcare_lab_test_param_range rltpr on plr.lab_test_parameter_id = rltpr.id and rltpr.status=1
                      where plr.lab_date is null and plr.lab_test_id is not null and plr.patient_id =" . $patientId . "
                      and plr.lab_date::timestamp between '" . $dateS . "' and '" . $dateE . "' 
                      group by plr.rec_date ,rlt.description,plr.patient_id,plr.lab_test_id,plr.notes,plr.lab_date";
        $data = DB::select($qry);
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use($component_name) {
                if($component_name == 'monthly-monitoring'){
                    $btn = '<a href="javascript:void(0)" data-toggle ="tooltip" onclick=editlabsformnew("' . date('m-d-Y', strtotime($row->lab_date)) . '","' . $row->patient_id . '","' . $row->lab_test_id . '","' . $row->labdateexist . '") ><i class=" i-Pen-4" style="color: #2cb8ea;"></i></a>';
                    $btn = $btn . '<i id="labdelid" class="i-Close" onclick=deleteLabs("' . date('m-d-Y', strtotime($row->lab_date)) . '","' . $row->patient_id . '","' . $row->lab_test_id . '","' . $row->labdateexist . '") title="Delete Labs" style="color: red;cursor: pointer;"></i>';
                    return $btn;
                }else{
                    $btn = '<a href="javascript:void(0)" data-toggle ="tooltip" onclick=carePlanDevelopment.editlabsformnew("' . date('m-d-Y', strtotime($row->lab_date)) . '","' . $row->patient_id . '","' . $row->lab_test_id . '","' . $row->labdateexist . '") ><i class=" i-Pen-4" style="color: #2cb8ea;"></i></a>';
                    $btn = $btn . '<i id="labdelid" class="i-Close" onclick=carePlanDevelopment.deleteLabs("' . date('m-d-Y', strtotime($row->lab_date)) . '","' . $row->patient_id . '","' . $row->lab_test_id . '","' . $row->labdateexist . '") title="Delete Labs" style="color: red;cursor: pointer;"></i>';
                    return $btn;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getImagingData(Request $request)
    {
        $patientId = sanitizeVariable($request->route('patientid'));
        $dateS = Carbon::now()->startOfMonth()->subMonth(6);
        $dateE = Carbon::now()->endOfMonth();
        $configTZ = config('app.timezone');
        $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        /*$data = PatientVitalsData::where('patient_id',$patientId)->whereNotNull('rec_date')->where('status',1)
                            ->whereBetween('created_at', [$dateS, $dateE])->orderby('id','desc')->get();*/
        $qry = "select distinct imaging_details, to_char( max(updated_at) at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY HH24:MI:SS') as updated_at, imaging_date
            from patients.patient_imaging
            where  patient_id =" . $patientId . "
            and imaging_date::timestamp between '" . $dateS . "' and '" . $dateE . "' 
            group by imaging_details,imaging_date order by updated_at desc";
        $data = DB::select($qry);
        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function getVitalData(Request $request)
    {
        $patientId = sanitizeVariable($request->route('patientid'));
        $dateS = Carbon::now()->startOfMonth()->subMonth(6);
        $dateE = Carbon::now()->endOfMonth();
        $configTZ = config('app.timezone');
        $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        /*$data = PatientVitalsData::where('patient_id',$patientId)->whereNotNull('rec_date')->where('status',1)
                            ->whereBetween('created_at', [$dateS, $dateE])->orderby('id','desc')->get();*/
        $qry = "select distinct rec_date ,height,weight,bmi,bp,o2,pulse_rate,
            diastolic,other_vitals,oxygen,notes,pain_level
            from patients.patient_vitals
            where rec_date is not null and patient_id =" . $patientId . "
            and rec_date::timestamp between '" . $dateS . "' and '" . $dateE . "' 
            order by rec_date desc";
        $data = DB::select($qry);
        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    //created by radha(2020-12-17)
    public function deletePatientlab(Request $request)
    {
        $patientId    = sanitizeVariable($request->patientid);
        $labdate      = sanitizeVariable($request->labdate);
        $labid        = sanitizeVariable($request->labid);
        $labdateexist = sanitizeVariable($request->labdateexist);
        $form_start_time = sanitizeVariable($request->form_start_time);
        $start_time            = sanitizeVariable($request->start_time);
        $end_time              = sanitizeVariable($request->end_time);
        $module_id             = sanitizeVariable($request->module_id);
        $component_id          = sanitizeVariable($request->component_id);
        $stage_id              = sanitizeVariable($request->stage_id);
        $step_id               = sanitizeVariable($request->step_id);
        $form_name             = sanitizeVariable($request->form_name);
        $billable              = sanitizeVariable($request->billable);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);

        DB::beginTransaction();
        try {
            $name_lab = DB::table('ren_core.rcare_lab_tests')->where('id', $labid)->get();
            $LabName = '';
            if (isset($name_lab[0]->description)) {
                $LabName = $name_lab[0]->description . '(' . $labdate . ')';
            } else {
                $LabName = 'Other (' . $labdate . ')';
            }
            $topic = 'Lab Data : ' . $LabName;

            $topic_name_exist  = callwrap::where('patient_id', $patientId)->where('topic', $topic)
                ->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
            if ($labdateexist == '1') {
                $lab_exit = PatientLabRecs::where('patient_id', $patientId)->where('lab_date', $labdate)
                    ->where('lab_test_id', $labid)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
                if ($lab_exit == true) {
                    PatientLabRecs::where('patient_id', $patientId)->where('lab_date', $labdate)->where('lab_test_id', $labid)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->delete();
                    //delete from Callwrap-table
                    callwrap::where('patient_id', $patientId)->where('topic', $topic) //->where('topic_id',$labid)
                        ->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->delete();
                } else {
                    PatientLabRecs::where('patient_id', $patientId)->where('rec_date', $labdate)->where('lab_test_id', $labid)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->delete();
                    //delete from Callwrap-table
                    callwrap::where('patient_id', $patientId)->where('topic', $topic) //->where('topic_id',$labid)
                        ->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->delete();
                }
            } else {
                $lab_exit = PatientLabRecs::where('patient_id', $patientId)->where('rec_date', $labdate)->where('lab_test_id', $labid)
                    ->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
                if ($lab_exit == true) {
                    PatientLabRecs::where('patient_id', $patientId)->where('rec_date', $labdate)->where('lab_test_id', $labid)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->delete();
                    //delete from Callwrap-table
                    callwrap::where('patient_id', $patientId)->where('topic', $topic) //->where('topic_id',$labid)
                        ->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->delete();
                }
            }
            $record_time   = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patientId, $module_id, $component_id, $stage_id, $billable, $patientId, $step_id, $form_name, $form_start_time, $form_save_time);
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    // created by radha 7dec2020
    public function PopulateLabsData(Request $request)
    {
        $patientId    = sanitizeVariable($request->route('patientid'));
        $labdate      = sanitizeVariable($request->route('labdate'));
        $labid        = sanitizeVariable($request->route('labid'));
        $labdateexist = sanitizeVariable($request->route('labdateexist'));
        $result       = array();
        if ($labdateexist == '1') {
            if (PatientLabRecs::where('patient_id', $patientId)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists()) {
                $PatientLab = PatientLabRecs::where('patient_id', $patientId)->where('lab_date', $labdate)->where('lab_test_id', $labid)
                    ->distinct() //->where('created_at', PatientLabRecs::max('created_at'))
                    ->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))
                    ->get('lab_test_id')->toArray();
                $result['number_tracking_labs_form']['static']['lab'] = '["' . implode('","', array_column($PatientLab, 'lab_test_id')) . '"]';
                $rec_date = PatientLabRecs::where('patient_id', $patientId)->max('rec_date');
                $number_tracking_labs_details = DB::table('patients.patient_lab_recs')
                    ->select(
                        'patients.patient_lab_recs.lab_test_id',
                        'patients.patient_lab_recs.lab_test_parameter_id',
                        'patients.patient_lab_recs.reading',
                        'patients.patient_lab_recs.high_val',
                        'patients.patient_lab_recs.notes',
                        'ren_core.rcare_lab_test_param_range.parameter',
                        'patients.patient_lab_recs.lab_date'
                    )
                    ->leftjoin('ren_core.rcare_lab_test_param_range', function ($join) {
                        $join->on('patients.patient_lab_recs.lab_test_id', '=', 'ren_core.rcare_lab_test_param_range.lab_test_id');
                        $join->on('patients.patient_lab_recs.lab_test_parameter_id', '=', 'ren_core.rcare_lab_test_param_range.id')->where('ren_core.rcare_lab_test_param_range.status', '1');
                    })
                    ->where('patients.patient_lab_recs.lab_date', $labdate)
                    ->where('patients.patient_lab_recs.patient_id', $patientId)
                    ->where('patients.patient_lab_recs.lab_test_id', $labid)
                    ->whereNotNull('patients.patient_lab_recs.lab_date')
                    ->whereMonth('patients.patient_lab_recs.created_at', date('m'))->whereYear('patients.patient_lab_recs.created_at', date('Y'))
                    ->get();
                $result['number_tracking_labs_details']['dynamic']['lab'] = $number_tracking_labs_details;
            }
        } else {
            if (PatientLabRecs::where('patient_id', $patientId)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists()) {
                $PatientLab = PatientLabRecs::where('patient_id', $patientId)->where('rec_date', $labdate)->where('lab_test_id', $labid)
                    ->distinct() //->where('created_at', PatientLabRecs::max('created_at'))
                    ->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))
                    ->get('lab_test_id')->toArray();
                $result['number_tracking_labs_form']['static']['lab'] = '["' . implode('","', array_column($PatientLab, 'lab_test_id')) . '"]';
                $rec_date = PatientLabRecs::where('patient_id', $patientId)->max('rec_date');
                $number_tracking_labs_details = DB::table('patients.patient_lab_recs')
                    ->select(
                        'patients.patient_lab_recs.lab_test_id',
                        'patients.patient_lab_recs.lab_test_parameter_id',
                        'patients.patient_lab_recs.reading',
                        'patients.patient_lab_recs.high_val',
                        'patients.patient_lab_recs.notes',
                        'ren_core.rcare_lab_test_param_range.parameter',
                        'patients.patient_lab_recs.lab_date',
                        'patients.patient_lab_recs.rec_date'
                    )
                    ->leftjoin('ren_core.rcare_lab_test_param_range', function ($join) {
                        $join->on('patients.patient_lab_recs.lab_test_id', '=', 'ren_core.rcare_lab_test_param_range.lab_test_id');
                        $join->on('patients.patient_lab_recs.lab_test_parameter_id', '=', 'ren_core.rcare_lab_test_param_range.id')->where('ren_core.rcare_lab_test_param_range.status', '1');
                    })
                    ->where('patients.patient_lab_recs.rec_date', $labdate)
                    ->where('patients.patient_lab_recs.patient_id', $patientId)
                    ->where('patients.patient_lab_recs.lab_test_id', $labid)
                    ->whereNull('patients.patient_lab_recs.lab_date')
                    ->whereMonth('patients.patient_lab_recs.created_at', date('m'))->whereYear('patients.patient_lab_recs.created_at', date('Y'))
                    ->get();
                $result['number_tracking_labs_details']['dynamic']['lab'] = $number_tracking_labs_details;
            }
        }
        return $result;
    }

    public function deleteCarePlan(Request $request)
    {
        $id           = sanitizeVariable($request->id);
        $module_id    = sanitizeVariable($request->module_id);
        $component_id = sanitizeVariable($request->component_id);
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $patient_id   = sanitizeVariable($request->patient_id);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = sanitizeVariable($request->step_id);
        $form_name    = sanitizeVariable($request->form_name);
        $billable     = 1;
        $form_start_time = sanitizeVariable($request->form_start_time);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            if (!empty($id) || $id != '') {
                $update_diagnosis['status'] = 0;
                $update_diagnosis['updated_by'] = session()->get('userid');
                $update_diagnosis['created_by'] = session()->get('userid');
                $p = PatientDiagnosis::where('id', $id)->get();
                $diagnosis_id = $p[0]->diagnosis;
                $patient_id = $p[0]->patient_id;
                PatientDiagnosis::where('id', $id)->update($update_diagnosis);

                $careplandata = CarePlanUpdateLogs::where('patient_diagnosis_id', $id)->where('patient_id', $patient_id)->where('diagnosis_id', $diagnosis_id)->exists();
                if ($careplandata == true) { //checking bcz previous-month data donot have entry in careplanlog and reviewtable
                    CarePlanUpdateLogs::where('patient_diagnosis_id', $id)->where('diagnosis_id', $diagnosis_id)->update($update_diagnosis);
                    PatientCareplanLastUpdateandReview::where('diagnosis_id', $diagnosis_id)->where('patient_id', $patient_id)->update($update_diagnosis);
                }
                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            }
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function deleteServices(Request $request)
    {
        $id           = sanitizeVariable($request->id);
        $patient_id   = sanitizeVariable($request->patient_id);
        $module_id    = sanitizeVariable($request->module_id);
        $component_id = sanitizeVariable($request->component_id);
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = sanitizeVariable($request->step_id);
        $billable     = sanitizeVariable($request->billable);
        $form_name    = sanitizeVariable($request->form_name);
        $form_start_time = sanitizeVariable($request->form_start_time);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            if (!empty($id) || $id != '') {
                $update_PatientHealthServices['status'] = 0;
                $update_PatientHealthServices['updated_by'] = session()->get('userid');
                $update_PatientHealthServices['created_by'] = session()->get('userid');
                PatientHealthServices::where('id', $id)->update($update_PatientHealthServices);
                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            }
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function deleteAllergies(Request $request)
    {
        $id           = sanitizeVariable($request->id);
        $patient_id   = sanitizeVariable($request->patient_id);
        $module_id    = sanitizeVariable($request->module_id);
        $component_id = sanitizeVariable($request->component_id);
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = sanitizeVariable($request->step_id);
        $form_name    = sanitizeVariable($request->form_name);
        $billable     = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->form_start_time);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            if (!empty($id) || $id != '') {
                $update_allergy['status'] = 0;
                $update_allergy['updated_by'] = session()->get('userid');
                $update_allergy['created_by'] = session()->get('userid');
                PatientAllergy::where('id', $id)->update($update_allergy);
                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            }
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function addnewcode(Request $request)
    {
        $conditionid = sanitizeVariable($request->condition);
        $code        = sanitizeVariable($request->code);
        $data   = array(
            'code'         => $code,
            'diagnosis_id' => $conditionid,
            'created_by'   => session()->get('userid')
        );
        $insert_query = DiagnosisCode::create($data);
    }

    public function getAllergies(Request $request)
    {
        $id          = sanitizeVariable($request->route('id'));
        $allergytype = sanitizeVariable($request->route('allergytype'));
        $data        = CommonFunctionController::checkPatientAllergyDataExistForCurrentMonthOrCopyFromLastMonthBasedOnAllergyType($id, $allergytype);

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip" class="editallergyother" onclick=editAllergy("' . $row->id . '","' . $row->allergy_type . '",this) data-original-title="Edit" title="Edit"><i class=" editform i-Pen-4"></i></a>';
                $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip" class="deletetabAllergies" onclick=deleteAllergies("' . $row->id . '","' . $row->allergy_type . '","' . $row->patient_id . '",this) data-original-title="delete" class="deletetabAllergies" title="Delete"><i class="i-Close" title="Delete" style="color: red;cursor: pointer;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getAllergiesOther(Request $request)
    {
        $id                  = sanitizeVariable($request->id);
        $allergy_type        = sanitizeVariable($request->route('allergytype'));
        $PatientOtherAllergy = (PatientAllergy::self($id) ? PatientAllergy::self($id)->population() : "");
        if ($allergy_type == 'petrelated') {
            $result['allergy_pet_related_form'] = $PatientOtherAllergy;
            $result['review_allergy_pet_related_form'] = $PatientOtherAllergy;
        } elseif ($allergy_type == 'latex') {
            $result['allergy_latex_form'] = $PatientOtherAllergy;
            $result['review_allergy_latex_form'] = $PatientOtherAllergy;
        } elseif ($allergy_type == 'insect') {
            $result['allergy_insect_form'] = $PatientOtherAllergy;
            $result['review_allergy_insect_form'] = $PatientOtherAllergy;
        } elseif ($allergy_type == 'enviromental') {
            $result['allergy_enviromental_form'] = $PatientOtherAllergy;
            $result['review_allergy_enviromental_form'] = $PatientOtherAllergy;
        } elseif ($allergy_type == 'drug') {
            $result['allergy_drug_form'] = $PatientOtherAllergy;
            $result['review_allergy_drug_form'] = $PatientOtherAllergy;
        } elseif ($allergy_type == 'food') {
            $result['allergy_food_form'] = $PatientOtherAllergy;
            $result['review_allergy_food_form'] = $PatientOtherAllergy;
        } elseif ($allergy_type == 'other') {
            $result['allergy_other_allergy_form'] = $PatientOtherAllergy;
            $result['review_allergy_other_allergy_form'] = $PatientOtherAllergy;
        }
        return $result;
    }

    // public function getImagingData(Request $Request)
    // {
    //     $patientId               = sanitizeVariable($Request->route('patientid'));
    //     $lastMonthImaging = "";
    //     $dataexist        = PatientImaging::where('patient_id', $patientId)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
    //     if ($dataexist == true) {
    //         $lastMonthImaging = PatientImaging::where('patient_id', $patientId)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))
    //             ->orderBy('created_at', 'desc')->get();
    //     } else {
    //         $lastMonthImaging = PatientImaging::where('patient_id', $patientId)->where('created_at', '>=', Carbon::now()->subMonth())->get();
    //     }
    //     return Datatables::of($lastMonthImaging)
    //         ->addIndexColumn()
    //         ->addColumn('action', function ($row) {
    //             $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  onclick=editImaging("' . $row->id . '") data-original-title="Edit" class="editimaging" title="Edit"><i class=" editform i-Pen-4"></i></a>';
    //             $btn = $btn . '<a href="javascript:void(0)" class="deleteServices" onclick=deleteServices("' . $row->id . '",this) data-toggle="tooltip" title ="Delete"><i class="i-Close" title="Delete" style="color: red;cursor: pointer;"></i></a>';
    //             return $btn;
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);
    // }

    public function Services_list(Request $Request)
    {
        $id               = sanitizeVariable($Request->route('id'));
        $servicetype      = sanitizeVariable($Request->route('servicetype'));
        $lastMonthService = "";
        $dataexist        = PatientHealthServices::where("patient_id", $id)->where("hid", $servicetype)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
        if ($dataexist == true) {
            $lastMonthService = PatientHealthServices::where("patient_id", $id)->where('status', 1)->where("hid", $servicetype)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->orderBy('created_at', 'desc')->with('users')->get();
        } else {
            $lastMonthService = PatientHealthServices::with('users')->where('patient_id', $id)->where('status', 1)->where("hid", $servicetype)->where('created_at', '>=', Carbon::now()->subMonth())->get();
        }
        return Datatables::of($lastMonthService)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  onclick=editService("' . $row->id . '") data-original-title="Edit" class="editservice" title="Edit"><i class=" editform i-Pen-4"></i></a>';
                $btn = $btn . '<a href="javascript:void(0)" class="deleteServices" onclick=deleteServices("' . $row->id . '",this) data-toggle="tooltip" title ="Delete"><i class="i-Close" title="Delete" style="color: red;cursor: pointer;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getServices(Request $request)
    {
        $id                    = sanitizeVariable($request->id);
        $PatientHealthServices = (PatientHealthServices::self($id) ? PatientHealthServices::self($id)->population() : "");
        $hid                   = $PatientHealthServices['static']['hid'];
        if ($hid == '1') {
            $result['service_dme_form'] = $PatientHealthServices;
            $result['review_service_dme_form'] = $PatientHealthServices;
        } elseif ($hid == '2') {
            $result['service_home_health_form'] = $PatientHealthServices;
            $result['review_service_home_health_form'] = $PatientHealthServices;
        } elseif ($hid == '3') {
            $result['service_dialysis_form'] = $PatientHealthServices;
            $result['review_service_dialysis_form'] = $PatientHealthServices;
        } elseif ($hid == '4') {
            $result['service_therapy_form'] = $PatientHealthServices;
            $result['review_service_therapy_form'] = $PatientHealthServices;
        } elseif ($hid == '5') {
            $result['service_social_form'] = $PatientHealthServices;
            $result['review_service_social_form'] = $PatientHealthServices;
        } elseif ($hid == '6') {
            $result['service_medical_supplies_form'] = $PatientHealthServices;
            $result['review_service_medical_supplies_form'] = $PatientHealthServices;
        } elseif ($hid == '7') {
            $result['service_other_health_form'] = $PatientHealthServices;
            $result['review_service_other_health_form'] = $PatientHealthServices;
        }
        return $result;
    }

    public function populateFormVizCarePlanDevelopmentData($patientId, $form)
    {
        $patientId                        = sanitizeVariable($patientId);
        $form                             = sanitizeVariable($form);

        if (isset($form) && (($form == "family_info") || ($form == "all"))) {
            $patientInfo                      = Patients::find($patientId) ? Patients::find($patientId)->population() : "";
            $PatientFamily                    = (PatientFamily::latest($patientId, 'spouse') ? PatientFamily::latest($patientId, 'spouse')->population() : "");
            $PatientCareGiver                 = (PatientFamily::latest($patientId, 'care-giver') ? PatientFamily::latest($patientId, 'care-giver')->population() : "");
            $PatientEmergencyContact          = (PatientFamily::latest($patientId, 'emergency-contact') ? PatientFamily::latest($patientId, 'emergency-contact')->population() : "");
            $patientAddresss                  = PatientAddress::latest($patientId);
            $result['review_family_patient_data_form'] = $patientInfo;
            $result['review_family_spouse_form'] = $PatientFamily;
            $result['review_family_care_giver_form'] = $PatientCareGiver;
            $result['review_family_emergency_contact_form'] = $PatientEmergencyContact;
            if (!empty($patientAddresss->add_1)) {
                $result['review_family_patient_data_form']['static']['add_1'] = $patientAddresss->add_1;
            }
        }

        if (isset($form) && (($form == "provider_info") || ($form == "all"))) {
            $PatientPcpProvider               = (PatientProvider::latest($patientId, '1') ? PatientProvider::latest($patientId, '1')->population() : "");
            $PatientVisionProvider            = (PatientProvider::latest($patientId, '3') ? PatientProvider::latest($patientId, '3')->population() : "");
            $PatientDentistProvider           = (PatientProvider::latest($patientId, '4') ? PatientProvider::latest($patientId, '4')->population() : "");
            $result['review_provider_pcp_form'] = $PatientPcpProvider;
            $result['review_provider_vision_form'] = $PatientVisionProvider;
            $result['review_provider_dentist_form'] = $PatientDentistProvider;
        }
        return $result;
    }

    public function populateCarePlanDevelopmentData($patientId)
    {
        $patientId                        = sanitizeVariable($patientId);
        $mid                              = getPageModuleName();
        $component_id                     = ModuleComponents::where('module_id', $mid)->where('components', 'Care Plan Development')->get('id');
        $patient                          = Patients::find($patientId);
        $patientInfo                      = Patients::find($patientId) ? Patients::find($patientId)->population() : "";
        $personal_notes                   = (PatientPersonalNotes::latest($patientId, 'patient_id') ? PatientPersonalNotes::latest($patientId, 'patient_id')->population() : "");
        $research_study                   = (PatientPartResearchStudy::latest($patientId, 'patient_id') ? PatientPartResearchStudy::latest($patientId, 'patient_id')->population() : "");
        $patient_threshold                = (PatientThreshold::latest($patientId, 'patient_id') ? PatientThreshold::latest($patientId, 'patient_id')->population() : "");
        $patientAddresss                  = PatientAddress::latest($patientId);
        $PatientFamily                    = (PatientFamily::latest($patientId, 'spouse') ? PatientFamily::latest($patientId, 'spouse')->population() : "");
        $PatientCareGiver                 = (PatientFamily::latest($patientId, 'care-giver') ? PatientFamily::latest($patientId, 'care-giver')->population() : "");
        $PatientEmergencyContact          = (PatientFamily::latest($patientId, 'emergency-contact') ? PatientFamily::latest($patientId, 'emergency-contact')->population() : "");
        $PatientFoodAllergy               = (PatientAllergy::latest($patientId, 'food') ? PatientAllergy::latest($patientId, 'food')->population() : "");
        $PatientDrugAllergy               = (PatientAllergy::latest($patientId, 'drug') ? PatientAllergy::latest($patientId, 'drug')->population() : "");
        $PatientEnviromentalAllergy       = (PatientAllergy::latest($patientId, 'enviromental') ? PatientAllergy::latest($patientId, 'enviromental')->population() : "");
        $PatientInsectAllergy             = (PatientAllergy::latest($patientId, 'insect') ? PatientAllergy::latest($patientId, 'insect')->population() : "");
        $PatientLatexAllergy              = (PatientAllergy::latest($patientId, 'latex') ? PatientAllergy::latest($patientId, 'latex')->population() : "");
        $PatientPreRelatedAllergy         = (PatientAllergy::latest($patientId, 'petrelated') ? PatientAllergy::latest($patientId, 'petrelated')->population() : "");
        $PatientDmeServices               = (PatientHealthServices::latest($patientId, '1') ? PatientHealthServices::latest($patientId, '1')->population() : "");
        $PatientFirstReview               = (PatientFirstReview::latest($patientId) ? PatientFirstReview::latest($patientId)->population() : "");
        $PatientHomePatientHealthServices = (PatientHealthServices::latest($patientId, '2') ? PatientHealthServices::latest($patientId, '2')->population() : "");
        $PatientDialysisServices          = (PatientHealthServices::latest($patientId, '3') ? PatientHealthServices::latest($patientId, '3')->population() : "");
        $PatientTherapyServices           = (PatientHealthServices::latest($patientId, '4') ? PatientHealthServices::latest($patientId, '4')->population() : "");
        $PatientSocialServices            = (PatientHealthServices::latest($patientId, '5') ? PatientHealthServices::latest($patientId, '5')->population() : "");
        $PatientMedicalSuppliesServices   = (PatientHealthServices::latest($patientId, '6') ? PatientHealthServices::latest($patientId, '6')->population() : "");
        $PatientPcpProvider               = (PatientProvider::latest($patientId, '1') ? PatientProvider::latest($patientId, '1')->population() : "");
        $PatientVisionProvider            = (PatientProvider::latest($patientId, '3') ? PatientProvider::latest($patientId, '3')->population() : "");
        $PatientDentistProvider           = (PatientProvider::latest($patientId, '4') ? PatientProvider::latest($patientId, '4')->population() : "");
        $PatientVitalsNumberTracking      = (PatientVitalsData::latest($patientId) ? PatientVitalsData::latest($patientId)->population() : "");
        $hippa                            = (CallHipaaVerification::latest($patientId) ? CallHipaaVerification::latest($patientId)->population() : "");
        $homeService                      = (CallHomeServiceVerification::latest($patientId) ? CallHomeServiceVerification::latest($patientId)->population() : "");
        $PatientDiagnosis                 = (PatientDiagnosis::latest($patientId) ? PatientDiagnosis::latest($patientId)->population() : "");
        $patientReviewLiveWith            = (PatientFamily::latest($patientId, 'live-with') ? PatientFamily::latest($patientId, 'live-with')->population() : "");
        $PatientReviewGrandchildren       = (PatientFamily::latest($patientId, 'grandchildren') ? PatientFamily::latest($patientId, 'grandchildren')->population() : "");
        $PatientReviewChildren            = (PatientFamily::latest($patientId, 'children') ? PatientFamily::latest($patientId, 'children')->population() : "");
        $PatientReviewSibling             = (PatientFamily::latest($patientId, 'sibling') ? PatientFamily::latest($patientId, 'sibling')->population() : "");
        // $callClose                        = (CallClose::latest($patientId,$component_id[0]->id) ? CallClose::latest($patientId,$component_id[0]->id)->population() : "");
        $callClose                        = (CallClose::latest($patientId) ? CallClose::latest($patientId)->population() : "");
        $patientContactTime               = PatientContactTime::where('patient_id', $patientId)->first();

        $PatientPet = (PatientPet::latest($patientId) ? PatientPet::latest($patientId)->population() : "");
        $PatientTravel = (PatientTravel::latest($patientId) ? PatientTravel::latest($patientId)->population() : "");
        $PatientHobbies = (PatientHobbies::latest($patientId) ? PatientHobbies::latest($patientId)->population() : "");

        if (PatientImaging::where('patient_id', $patientId)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists()) {
            $PatientImaging = PatientImaging::where('patient_id', $patientId)
                ->select(DB::raw("distinct imaging_details, date(imaging_date),comment"))
                ->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->groupBy('imaging_details', 'imaging_date', 'comment')->get('imaging_details')->toArray();
            $result['number_tracking_imaging_form']['static']['imaging'] = '["' . implode('","', array_column($PatientImaging, 'imaging_details')) . '"]';
            $result['number_tracking_imaging_form']['static']['imaging_date'] = '["' . implode('","', array_column($PatientImaging, 'date')) . '"]';
            $result['number_tracking_imaging_form']['static']['comment'] = '["' . implode('","', array_column($PatientImaging, 'comment')) . '"]';
        }
        if (PatientHealthData::where('patient_id', $patientId)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists()) {
            $PatientHealthData = PatientHealthData::where('patient_id', $patientId)
                ->select(DB::raw("distinct health_data, date(health_date),comment"))
                ->whereMonth('updated_at', date('m'))
                ->whereYear('updated_at', date('Y'))
                ->groupBy('health_data', 'health_date', 'comment')->get('health_data')->toArray();
            $result['number_tracking_healthdata_form']['static']['healthdata'] = '["' . implode('","', array_column($PatientHealthData, 'health_data')) . '"]';
            $result['number_tracking_healthdata_form']['static']['health_date'] = '["' . implode('","', array_column($PatientHealthData, 'date')) . '"]';
            $result['number_tracking_healthdata_form']['static']['comment'] = '["' . implode('","', array_column($PatientHealthData, 'comment')) . '"]';
        }
        if (CallWrap::where('patient_id', $patientId)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists()) {
            $CallWrap = CallWrap::where('patient_id', $patientId)->where('topic', 'care plan developement notes')
                ->where('sequence', 0)
                ->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))->latest()->get('notes');
            if (isset($CallWrap[0]->notes)) {
                $result['callwrapup_form']['static']['notes'] = $CallWrap[0]->notes;
            } else {
                $result['callwrapup_form']['static']['notes'] = '';
            }
        }
        $result['personal_notes_form']         = $personal_notes;
        $result['part_of_research_study_form'] = $research_study;
        $result['patient_threshold_form']      = $patient_threshold;
        $result['family_patient_data_form']    = $patientInfo;
        if (!empty($patientAddresss->add_1)) {
            $result['family_patient_data_form']['static']['add_1'] = $patientAddresss->add_1;
        }
        $result['family_spouse_form'] = $PatientFamily;
        $result['family_care_giver_form'] = $PatientCareGiver;
        $result['family_emergency_contact_form'] = $PatientEmergencyContact;
        $result['allergy_food_form'] = $PatientFoodAllergy;
        $result['allergy_drug_form'] = $PatientDrugAllergy;
        $result['allergy_enviromental_form'] = $PatientEnviromentalAllergy;
        $result['allergy_insect_form'] = $PatientInsectAllergy;
        $result['allergy_latex_form'] = $PatientLatexAllergy;
        $result['allergy_pet_related_form'] = $PatientPreRelatedAllergy;
        $result['provider_pcp_form'] = $PatientPcpProvider;
        $result['provider_vision_form'] = $PatientVisionProvider;
        $result['provider_dentist_form'] = $PatientDentistProvider;
        $result['number_tracking_vitals_form'] = $PatientVitalsNumberTracking;
        $result['review_family_patient_data_form'] = $patientInfo;
        if (!empty($patientAddresss->add_1)) {
            $result['review_family_patient_data_form']['static']['add_1'] = $patientAddresss->add_1;
        }
        $result['review_family_spouse_form'] = $PatientFamily;
        $result['review_family_care_giver_form'] = $PatientCareGiver;
        $result['review_family_emergency_contact_form'] = $PatientEmergencyContact;
        $result['review_do_you_live_with_anyone_form'] = $patientReviewLiveWith;
        $result['grandchildren_form'] = $PatientReviewGrandchildren;
        $result['children_form'] = $PatientReviewChildren;
        $result['sibling_form'] = $PatientReviewSibling;
        $result['review_allergy_food_form'] = $PatientFoodAllergy;
        $result['review_allergy_drug_form'] = $PatientDrugAllergy;
        $result['review_allergy_enviromental_form'] = $PatientEnviromentalAllergy;
        $result['review_allergy_insect_form'] = $PatientInsectAllergy;
        $result['review_allergy_latex_form'] = $PatientLatexAllergy;
        $result['review_allergy_pet_related_form'] = $PatientPreRelatedAllergy;
        $result['review_provider_pcp_form'] = $PatientPcpProvider;
        $result['review_provider_vision_form'] = $PatientVisionProvider;
        $result['review_provider_dentist_form'] = $PatientDentistProvider;
        $result['review_service_dme_form'] = $PatientDmeServices;
        $result['review_service_home_health_form'] = $PatientHomePatientHealthServices;
        $result['review_service_dialysis_form'] = $PatientDialysisServices;
        $result['review_service_therapy_form'] = $PatientTherapyServices;
        $result['review_service_social_form'] = $PatientSocialServices;
        $result['review_service_medical_supplies_form'] = $PatientMedicalSuppliesServices;
        $result['homeservice_form'] = $homeService;
        $result['hippa_form'] = $hippa;
        $result['review_pets_form'] = $PatientPet;
        $result['review_hobbies_form'] = $PatientHobbies;
        $result['review_travel_form'] = $PatientTravel;

        if (!empty($patientContactTime)) {
            $patientContactTime = $patientContactTime->population();
            if (!empty($callClose['static'])) {
                $callClose['static'] = array_merge($callClose['static'], $patientContactTime['static']);
            } else {
                $callClose = [];
                $callClose['static'] = $patientContactTime['static'];
            }
        }
        $result['call_close_form'] = $callClose;
        $result['callstatus_form'] = $callClose;
        $result['diagnosis_code_form'] = $PatientDiagnosis;
        $result['patient_first_review'] = $PatientFirstReview;
        return $result;
    }

    public function populateRelationshipData(Request $request)
    {
        $patientId                                     = sanitizeVariable($request->id);
        $patientReviewLiveWith                         = (PatientFamily::latest($patientId, 'live-with') ? PatientFamily::latest($patientId, 'live-with')->population() : "");
        $PatientReviewGrandchildren                    = (PatientFamily::latest($patientId, 'grandchildren') ? PatientFamily::latest($patientId, 'grandchildren')->population() : "");
        $PatientReviewChildren                         = (PatientFamily::latest($patientId, 'children') ? PatientFamily::latest($patientId, 'children')->population() : "");
        $PatientReviewSibling                          = (PatientFamily::latest($patientId, 'sibling') ? PatientFamily::latest($patientId, 'sibling')->population() : "");
        $result['sibling_form']                        = $sibling;
        $result['review_do_you_live_with_anyone_form'] = $live_with;
        $result['children_form']                       = $children;
        $result['grandchildren_form']                  = $grandchildren;
        return $result;
    }

    public function getCodesById(Request $request)
    {
        $patientId         = sanitizeVariable($request->patient_id);
        $id                = sanitizeVariable($request->id);
        $check_exist_code  = PatientDiagnosis::where('patient_id', $patientId)->where('status', 1)->where('diagnosis', $id)
            ->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
        if ($check_exist_code == true) {
            $diagnosis_data = (PatientDiagnosis::selfDiagnosis($id, $patientId) ? PatientDiagnosis::selfDiagnosis($id, $patientId)->population() : "");
            if ($diagnosis_data['static']['code'] == null) {
                $diagnosis_codedata = DiagnosisCode::where('diagnosis_id', $id)->latest('updated_at')->first();
                $count =  $diagnosis_codedata->count();
                if ($count > 0) {
                    $diagnosis_data['static']['code'] =  $diagnosis_codedata['code'];
                } else {
                    $d  =  Diagnosis::where('id', $id)->latest('updated_at')->first();
                    $diagnosis_data['static']['code'] =  $d['code'];
                }
            }
            $result['care_plan_form'] = $diagnosis_data;
            $result['diagnosis_code_form'] = $diagnosis_data;
            $result['review_diagnosis_code_form'] = $diagnosis_data;
        } else {
            $check_exist_diagnosis_data  = CarePlanTemplate::where('diagnosis_id', $id)->exists();
            $diagnosisExists = DiagnosisCode::where('diagnosis_id', $id)->exists();
            if ($check_exist_diagnosis_data == true) {
                $diagnosis_data = (CarePlanTemplate::diagnosisCode($id) ? CarePlanTemplate::diagnosisCode($id)->population() : "");
                if ($diagnosis_data['static']['code'] == null) {
                    $diagnosis_codedata = DiagnosisCode::where('diagnosis_id', $id)->latest('updated_at')->first();
                    $count =  $diagnosis_codedata->count();
                    if ($count > 0) {
                        $diagnosis_data['static']['code'] =  $diagnosis_codedata['code'];
                    } else {
                        $d  =  Diagnosis::where('id', $id)->latest('updated_at')->first();
                        $diagnosis_data['static']['code'] =  $d['code'];
                    }
                }
                $result['care_plan_form'] = $diagnosis_data;
                $result['diagnosis_code_form'] = $diagnosis_data;
                $result['review_diagnosis_code_form'] = $diagnosis_data;
            } else if ($diagnosisExists == true) {
                $diagnosis_data = (DiagnosisCode::latest($id) ? DiagnosisCode::latest($id)->population() : "");
                $result['care_plan_form'] = $diagnosis_data;
                $result['diagnosis_code_form'] = $diagnosis_data;
                $result['review_diagnosis_code_form'] = $diagnosis_data;
            } else {
                $diagnosis_data = (Diagnosis::latest($id) ? Diagnosis::latest($id)->population() : ""); //
                $result['care_plan_form'] = $diagnosis_data;
                $result['diagnosis_code_form'] = $diagnosis_data;
                $result['review_diagnosis_code_form'] = $diagnosis_data;
            }
        }
        return $result;
    }

    public function getPatientDiagnosisCodesById(Request $request)
    {
        $id                   = sanitizeVariable($request->id);
        $allDiagnosisPatients = PatientDiagnosis::where('id', $id)->get(); //  
        return $allDiagnosisPatients;
    }

    public function getPatientFamilyById(Request $request)
    {
        $id            = sanitizeVariable($request->id);
        $relation      = sanitizeVariable($request->relation);
        $sibling       = (PatientFamily::self($id, $relation) ? PatientFamily::self($id, $relation)->population() : "");
        $live_with     = (PatientFamily::self($id, $relation) ? PatientFamily::self($id, $relation)->population() : "");
        $children      = (PatientFamily::self($id, $relation) ? PatientFamily::self($id, $relation)->population() : "");
        $grandchildren = (PatientFamily::self($id, $relation) ? PatientFamily::self($id, $relation)->population() : "");
        $result['sibling_form']                        = $sibling;
        $result['review_do_you_live_with_anyone_form'] = $live_with;
        $result['children_form']                       = $children;
        $result['grandchildren_form']                  = $grandchildren;
        return $result;
    }

    public function deletePatientFamilyById(Request $request)
    {
        $id            = sanitizeVariable($request->id);
        $module_id     = sanitizeVariable($request->module_id);
        $component_id  = sanitizeVariable($request->component_id);
        $tab_name      = sanitizeVariable($request->tab_name);
        $start_time    = sanitizeVariable($request->start_time);
        $end_time      = sanitizeVariable($request->end_time);
        $patient_id    = sanitizeVariable($request->patient_id);
        $stage_id      = sanitizeVariable($request->stage_id);
        $step_id       = sanitizeVariable($request->step_id);
        $form_name     = sanitizeVariable($request->form_name);
        $billable      = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->form_start_time);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            if (!empty($id) || $id != '') {
                PatientFamily::where('id', $id)->delete();
                $record_time   = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
                $count         = PatientFamily::where('patient_id', $patient_id)->where('tab_name', $tab_name)->count();
                $msg = '';
                if ($count == 0) {
                    $msg =  $tab_name;
                }
            }
            DB::commit();
            return response(['form_start_time' => $form_save_time, 'msg' => $msg]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function getPatientPetById(Request $request)
    {
        $id                         = sanitizeVariable($request->id);
        $allPetPatients             = (PatientPet::self($id) ? PatientPet::self($id)->population() : "");
        $result['review_pets_form'] = $allPetPatients;
        return $result;
    }

    public function deletePatientPetById(Request $request)
    {
        $id            = sanitizeVariable($request->id);
        $module_id     = sanitizeVariable($request->module_id);
        $component_id  = sanitizeVariable($request->component_id);
        $start_time    = sanitizeVariable($request->start_time);
        $end_time      = sanitizeVariable($request->end_time);
        $patient_id    = sanitizeVariable($request->patient_id);
        $pet_status    = sanitizeVariable($request->pet_status);
        $stage_id      = sanitizeVariable($request->stage_id);
        $step_id       = sanitizeVariable($request->step_id);
        $form_name     = sanitizeVariable($request->form_name);
        $billable      = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->form_start_time);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            if (!empty($id) || $id != '') {
                PatientPet::where('id', $id)->delete();
                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            }
            DB::commit();
            $count   = PatientPet::where('patient_id', $patient_id)->where('pet_status', $pet_status)->count();
            $msg = '';
            if ($count == 0) {
                $msg = 'nothing';
            }
            return response(['form_start_time' => $form_save_time, 'msg' => $msg]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function getPatientHobbieById(Request $request)
    {
        $id                            = sanitizeVariable($request->id);
        $allHobbiePatients             = (PatientHobbies::self($id) ? PatientHobbies::self($id)->population() : "");
        $result['review_hobbies_form'] = $allHobbiePatients;
        return $result;
    }

    public function deletePatientHobbieById(Request $request)
    {
        $id             = sanitizeVariable($request->id);
        $module_id      = sanitizeVariable($request->module_id);
        $component_id   = sanitizeVariable($request->component_id);
        $start_time     = sanitizeVariable($request->start_time);
        $end_time       = sanitizeVariable($request->end_time);
        $patient_id     = sanitizeVariable($request->patient_id);
        $hobbies_status = sanitizeVariable($request->hobbies_status);
        $stage_id       = sanitizeVariable($request->stage_id);
        $step_id        = sanitizeVariable($request->step_id);
        $form_name      = sanitizeVariable($request->form_name);
        $billable       = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->form_start_time);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            if (!empty($id) || $id != '') {
                PatientHobbies::where('id', $id)->delete();
                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            }
            DB::commit();
            $count = PatientHobbies::where('patient_id', $patient_id)->where('hobbies_status', $hobbies_status)->count();
            $msg = '';
            if ($count == 0) {
                $msg = 'nothing';
            }
            return response(['form_start_time' => $form_save_time, 'msg' => $msg]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function getPatientTravelById(Request $request)
    {
        $id                           = sanitizeVariable($request->id);
        $allTravelPatients            = (PatientTravel::self($id) ? PatientTravel::self($id)->population() : "");
        $result['review_travel_form'] = $allTravelPatients;
        return $result;
    }

    public function deletePatientTravelById(Request $request)
    {
        $id            = sanitizeVariable($request->id);
        $module_id     = sanitizeVariable($request->module_id);
        $component_id  = sanitizeVariable($request->component_id);
        $start_time    = sanitizeVariable($request->start_time);
        $end_time      = sanitizeVariable($request->end_time);
        $patient_id    = sanitizeVariable($request->patient_id);
        $travel_status = sanitizeVariable($request->travel_status);
        $stage_id      = sanitizeVariable($request->stage_id);
        $step_id       = sanitizeVariable($request->step_id);
        $form_name     = sanitizeVariable($request->form_name);
        $billable      = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->form_start_time);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            if (!empty($patient_id) || $patient_id != '') {
                PatientTravel::where('id', $id)->delete();
                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            }
            DB::commit();
            $count = PatientTravel::where('patient_id', $patient_id)->where('travel_status', $travel_status)->count();
            $msg = '';
            if ($count == 0) {
                $msg = 'nothing';
            }
            return response(['form_start_time' => $form_save_time, 'msg' => $msg]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function getPatientProviderSpecialistById(Request $request)
    {
        $id                                         = sanitizeVariable($request->id);
        $allProviderSpecialistPatients              = (PatientProvider::self($id) ? PatientProvider::self($id)->population() : "");
        $result['provider_specialists_form']        = $allProviderSpecialistPatients;
        $result['review_provider_specialists_form'] = $allProviderSpecialistPatients;
        return $result;
    }

    public function deletePatientProviderSpecialistById(Request $request)
    {
        $id           = sanitizeVariable($request->id);
        $module_id    = sanitizeVariable($request->module_id);
        $component_id = sanitizeVariable($request->component_id);
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $patient_id   = sanitizeVariable($request->patient_id);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = sanitizeVariable($request->step_id);
        $form_name    = sanitizeVariable($request->form_name);
        $billable     = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->form_start_time);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            if (!empty($id) || $id != '') {
                $update_provider['is_active'] = 0;
                $update_provider['updated_by'] = session()->get('userid');
                $update_provider['created_by'] = session()->get('userid');
                PatientProvider::where('id', $id)->update($update_provider);
                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            }
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function getSelectedMedicationsPatientById(Request $request)
    {
        $med_id    = sanitizeVariable($request->med_id);
        $patientId = sanitizeVariable($request->patientId);
        $check_id  = PatientMedication::where('med_id', $med_id)->where('status', 1)->where('patient_id', $patientId)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->exists();
        if ($check_id == true) {
            $get_id = PatientMedication::where('med_id', $med_id)->where('status', 1)->where('patient_id', $patientId)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->get();
            $id = $get_id[0]->id;
            $allMedicationsPatients            = (PatientMedication::self($id) ? PatientMedication::self($id)->population() : ""); // 
            $result['medications_form']        = $allMedicationsPatients;
            $result['review_medications_form'] = $allMedicationsPatients;
            return $result;
        }
    }

    public function getPatientMedicationsById(Request $request)
    {
        $id                                = sanitizeVariable($request->id);
        $allMedicationsPatients            = (PatientMedication::self($id) ? PatientMedication::self($id)->population() : ""); // 
        $result['medications_form']        = $allMedicationsPatients;
        $result['review_medications_form'] = $allMedicationsPatients;
        return $result;
    }

    public function deletePatientMedicationsById(Request $request)
    {
        $id           = sanitizeVariable($request->id);
        $patient_id   = sanitizeVariable($request->patient_id);
        $module_id    = sanitizeVariable($request->module_id);
        $component_id = sanitizeVariable($request->component_id);
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = sanitizeVariable($request->step_id);
        $form_name    = sanitizeVariable($request->form_name);
        $billable     = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->form_start_time);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            if (!empty($id) || $id != '') {
                $update_medication['status'] = 0;
                $update_medication['updated_by'] = session()->get('userid');
                $update_medication['created_by'] = session()->get('userid');
                PatientMedication::where('id', $id)->update($update_medication);
                $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            }
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function diagnosis_code_list_old($id)
    {
        $id           = sanitizeVariable($id);
        $data =         PatientDiagnosis::with('diagnosis')->with('users')->where("patient_id", $id)->where('status', 1)
            ->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))
            ->orderBy('created_at', 'desc')->get();
        $currentmonth     = date('m');
        $currentyear      = date("Y");
        foreach ($data as $d) {
            $condition = $d->condition;
            $code      = $d->code;
            $p = PatientCareplanLastUpdateandReview::where('diagnosis_id', $d->diagnosis)->where("patient_id", $id)->where('status', 1)->first();
            // dd($p);
            if (isset($p)) {
                $bunchdata = PatientDiagnosis::where('patient_id', $id)
                    ->where('diagnosis', $p->diagnosis_id)
                    ->first();
                $var = $p->review_date;
                // print_r($var);
                $v = date("Y-m-d", strtotime($var));
                $currentdate = date("Y-m-d");
                $datetime1 = date_create($currentdate);
                $currentdatetime = new \DateTime($currentdate);

                $rd = $p->review_date;
                $review_date = date("Y-m-d ", strtotime($rd));
                $reviewdatetime = new \DateTime($review_date);
                $intervalforreview = $reviewdatetime->diff($currentdatetime);
                $diffIndaysfor_Reviewdate = $intervalforreview->days;
                $diffInMonths  = $intervalforreview->m;
                $diffInYearsfor_Reviewdate  = $intervalforreview->y;
                $final_reviewdate  = date("m-d-Y ", strtotime($rd));
                $up = $p->update_date;
                $final_updatedate  = date("m-d-Y ", strtotime($up));
                $d['review_date'] = $final_reviewdate;
                $d['update_date'] = $final_updatedate;

                // dd($final_reviewdate,$final_updatedate );


                if ($diffIndaysfor_Reviewdate <= 30) {
                    $iconcolor = 'green';
                    $d['iconcolor'] = $iconcolor;
                } else if ($diffIndaysfor_Reviewdate > 30 && $diffIndaysfor_Reviewdate <= 91) {
                    $iconcolor = 'green';
                    $d['iconcolor'] = $iconcolor;
                } else if ($diffIndaysfor_Reviewdate > 91 && $diffIndaysfor_Reviewdate <= 152) {
                    $iconcolor = 'yellow';
                    $d['iconcolor'] = $iconcolor;
                } else if ($diffIndaysfor_Reviewdate > 152) {
                    $iconcolor = 'red';
                    $d['iconcolor'] = $iconcolor;
                }
            } else {
                $d['iconcolor'] = 'white';
                $d['review_date'] = null;
                $d['update_date'] = null;
            }
        }
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                if ($row->iconcolor == 'green') {
                    $btn1 = '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Edit"  title="Edit" onclick = carePlanDevelopment.editPatientDignosis("' . $row->id . '",this) ><i class="i-Closee  i-Data-Yes" style="color: #33ff33; "cursor: pointer;"></i></a>';
                } else if ($row->iconcolor == 'yellow') {
                    $btn1 = '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Edit"  title="Edit" onclick = carePlanDevelopment.editPatientDignosis("' . $row->id . '",this) ><i class="i-Closee  i-Data-Yes" style="color: yellow; "cursor: pointer;"></i></a>';
                } else if ($row->iconcolor == 'red') {
                    $btn1 = '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Edit"  title="Edit" onclick = carePlanDevelopment.editPatientDignosis("' . $row->id . '",this) ><i class="i-Closee  i-Data-Yes" style="color: red; "cursor: pointer;"></i></a>';
                } else {
                    $btn1 = '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Edit"  title="Edit" onclick = carePlanDevelopment.editPatientDignosis("' . $row->id . '",this) ><i class="i-Closee  i-Data-Yes" style="color: black; "cursor: pointer;"></i></a>';
                }
                // $btn ='<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Edit" onclick = carePlanDevelopment.editPatientDignosis("'.$row->id.'",this) title="Edit"><i class="i-Pen-4" style="color: #2cb8ea;cursor: pointer;"></i></a>';  
                $btn = $btn1 . '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Delete" onclick = carePlanDevelopment.deletePatientDignosis("' . $row->id . '",this) title="Delete"><i class="i-Close" style="color: red;cursor: pointer;"></i>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function diagnosis_code_list($id)
    {
        $id           = sanitizeVariable($id);
        $data =         PatientDiagnosis::with('diagnosis')->with('users')->where("patient_id", $id)->where('status', 1)
            ->whereMonth('updated_at', date('m'))
            ->whereYear('updated_at', date('Y'))
            ->orderBy('created_at', 'desc')->get();
        $currentmonth     = date('m');
        $currentyear      = date("Y");
        $addtnl = [];


        //dd($data);
        foreach ($data as $d) {
            $condition = $d->condition;
            $code      = $d->code;

            $p = View_Patient_Diagnosis_Age::where('diagnosis_id', $d->diagnosis)->where("patient_id", $id)->first();
            // $p=View_Patient_Diagnosis_Age::where('diagnosis_id',120)->where("patient_id", $id)->first();

            if (isset($p)) {

                // dd($p->review_date);
                $review_year_age = $p->review_year_age;
                $review_month_age = $p->review_month_age;
                $d['review_date'] = $p->review_date;
                $d['update_date'] = $p->update_date;


                // if($review_year_age < 1 && ($review_month_age < 4)){ // less than 4
                //     $iconcolor = 'green';          
                //     $d['iconcolor'] = $iconcolor;
                // } else if($review_year_age < 1 && ($review_month_age < 5)) { // 3.1-4.9 months 
                //     $iconcolor = 'yellow';
                //     $d['iconcolor'] = $iconcolor;
                // } else if($review_year_age > 1 || $review_month_age > 5 ) {  //more than 5 months 
                //     $iconcolor = 'red';
                //     $d['iconcolor'] = $iconcolor;
                // }

                // if($review_year_age < 1 && ($review_month_age < 3)){ // less than 3
                //     $iconcolor = 'green';          
                //     $d['iconcolor'] = $iconcolor;
                // } else if($review_year_age < 1 && ($review_month_age > 3 && $review_month_age < 12 )) { // 3.1-11.9 months 
                //     $iconcolor = 'yellow';
                //     $d['iconcolor'] = $iconcolor;
                // } else if($review_year_age > 1 || $review_month_age > 12 ) {  //more than 12 months 
                //     $iconcolor = 'red';
                //     $d['iconcolor'] = $iconcolor;
                // }



                //this condition has changed on 22nd dec 2022
                if ($review_year_age < 1 && ($review_month_age < 6)) { // less than 6
                    $iconcolor = 'green';
                    $d['iconcolor'] = $iconcolor;
                } else if ($review_year_age < 1 && ($review_month_age >= 6 && $review_month_age < 12)) { // 6-11.9 months 
                    $iconcolor = 'yellow';
                    $d['iconcolor'] = $iconcolor;
                } else if ($review_year_age >= 1  || $review_month_age >= 12) {  //more than 12 months 
                    $iconcolor = 'red';
                    $d['iconcolor'] = $iconcolor;
                }
            } else {
                $d['iconcolor'] = 'white';
                $d['review_date'] = null;
                $d['update_date'] = null;
            }
        }
        //dd($data);
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('review_date', function ($row) {
                return $row->review_date;
            })
            ->addColumn('action', function ($row) {

                if ($row->iconcolor == 'green') {
                    $btn1 = '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Edit"  title="Edit" onclick = carePlanDevelopment.editPatientDignosis("' . $row->id . '",this) ><i class="i-Closee  i-Data-Yes" style="color: #33ff33; "cursor: pointer;"></i></a>';
                } else if ($row->iconcolor == 'yellow') {
                    $btn1 = '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Edit"  title="Edit" onclick = carePlanDevelopment.editPatientDignosis("' . $row->id . '",this) ><i class="i-Closee  i-Data-Yes" style="color: yellow; "cursor: pointer;"></i></a>';
                } else if ($row->iconcolor == 'red') {
                    $btn1 = '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Edit"  title="Edit" onclick = carePlanDevelopment.editPatientDignosis("' . $row->id . '",this) ><i class="i-Closee  i-Data-Yes" style="color: red; "cursor: pointer;"></i></a>';
                } else {
                    $btn1 = '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Edit"  title="Edit" onclick = carePlanDevelopment.editPatientDignosis("' . $row->id . '",this) ><i class="i-Closee  i-Data-Yes" style="color: black; "cursor: pointer;"></i></a>';
                }
                // $btn ='<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Edit" onclick = carePlanDevelopment.editPatientDignosis("'.$row->id.'",this) title="Edit"><i class="i-Pen-4" style="color: #2cb8ea;cursor: pointer;"></i></a>';  
                $btn = $btn1 . '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Delete" onclick = carePlanDevelopment.deletePatientDignosis("' . $row->id . '",this) title="Delete"><i class="i-Close" style="color: red;cursor: pointer;"></i>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public static function populatePatientDiagnosis($id, $patientId)
    {
        $id           = sanitizeVariable($id);
        $patientId    = sanitizeVariable($patientId);
        $check_exist_code  = PatientDiagnosis::where('patient_id', $patientId)->where('status', 1)->where('id', $id)->exists();
        if ($check_exist_code == true) {
            $diagnosis_data = (PatientDiagnosis::selfDiagnosisByEditId($patientId, $id) ? PatientDiagnosis::selfDiagnosisByEditId($patientId, $id)->population() : "");
            $result['care_plan_form'] = $diagnosis_data;
            $result['diagnosis_code_form'] = $diagnosis_data;
            $result['review_diagnosis_code_form'] = $diagnosis_data;
        } else {
            $diagnosis_data = (CarePlanTemplate::diagnosisCode($patientId) ? CarePlanTemplate::diagnosisCode($patientId)->population() : "");
            $result['care_plan_form'] = $diagnosis_data;
            $result['diagnosis_code_form'] = $diagnosis_data;
            $result['review_diagnosis_code_form'] = $diagnosis_data;
        }
        return $result;
    }

    public function Provider_Specilist_list($id)
    {
        $id      = sanitizeVariable($id);
        $data    = PatientProvider::with('provider_subtype')->with('provider')->with('users')->with('specility')->with('practice')->where('patient_id', $id)->where('provider_type_id', 2)->where('is_active', 1)->orderBy('created_at', 'desc')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip" onclick=carePlanDevelopment.editSpecialistProviderPatient("' . $row->id . '") data-original-title="Edit" title="Edit"><i class=" editform i-Pen-4"></i></a>';
                $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip" onclick=carePlanDevelopment.deleteSpecialistProviderPatient("' . $row->id . '",this) data-original-title="Delete" title="Delete"><i class="i-Close-Window" style="color:red;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function Medications_list($id)
    {
        $id        = sanitizeVariable($id);
        $str = sanitizeVariable(date('Y-m-d', strtotime(date('Y-m') . " -1 month")));
        $Get_year_month = explode("-", sanitizeVariable($str));
        $prev_year       = sanitizeVariable($Get_year_month[0]);
        $prev_month      = sanitizeVariable($Get_year_month[1]);
        $current_month = date('m');
        $current_year  = date('Y');
        $configTZ     = config('app.timezone');
        $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        $dataexist = PatientMedication::with('medication')->where("patient_id", $id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
        if ($dataexist == true) {
            $data = DB::select("select med_id,pm1.id,pm1.description,purpose,strength,duration,dosage,frequency,route,pharmacy_name,pharmacy_phone_no,rm.description as name,concat(u.f_name,' ', u.l_name) as users,to_char(pm1.updated_at at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY HH24:MI:SS') as updated_at
                                        from patients.patient_medication pm1 
                                        left join ren_core.medication rm on rm.id = pm1.med_id 
                                        left join ren_core.users u on u.id = pm1.created_by
                                        where pm1.status = 1 AND pm1.id in (select max(pm.id) from patients.patient_medication pm 
                                            where pm.patient_id = '" . $id . "' 
                                            AND EXTRACT(Month from pm.created_at)= '" . $current_month . "'
                                            AND EXTRACT(YEAR from pm.created_at) = '" . $current_year . "' group by pm.med_id) 
                                        order by pm1.updated_at desc");
        } else {
            $data = DB::select("select med_id,pm1.id,pm1.description,purpose,strength,duration,dosage,frequency,route,pharmacy_name,pharmacy_phone_no,rm.description as name,concat(u.f_name,' ', u.l_name) as users,to_char(pm1.updated_at at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY HH24:MI:SS') as updated_at
                                        from patients.patient_medication pm1
                                        left join ren_core.medication rm on rm.id = pm1.med_id 
                                        left join ren_core.users u on u.id = pm1.created_by
                                        where pm1.status = 1 AND  pm1.id in (select max(pm.id) from patients.patient_medication pm 
                                            where pm.patient_id = '" . $id . "'
                                            AND EXTRACT(Month from pm.created_at) = '" . $prev_month . "'
                                            AND EXTRACT(YEAR from pm.created_at) = '" . $prev_year . "' group by pm.med_id) 
                                        order by pm1.updated_at desc");
        }
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Edit" onclick=editMedications("' . $row->id . '") title="Edit"><i class="editform i-Pen-4"></i></a>';
                $btn = $btn . '<a href="javascript:void(0)" onclick=deleteMedications("' . $row->id . '",this)  title="Delete" data-id="' . $row->id . '" ><i class="i-Close-Window" style="color:red;"></i></a>';
                return $btn; //editMedicationsPatient //deleteMedicationsPatient
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // tables in review
    public function getSiblingList($id, $tab_name)
    {
        $id        = sanitizeVariable($id);
        $tab_name  = sanitizeVariable($tab_name);
        $data      = PatientFamily::where("patient_id", $id)->where('tab_name', $tab_name)->where('relational_status', 1)->with('users')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Edit" onclick=carePlanDevelopment.editSiblingData("' . $row->id . '","' . $row->tab_name . '",this) title="Edit"><i class=" editform i-Pen-4"></i></a>';
                $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip" title="Delete" data-original-title="Delete" onclick=carePlanDevelopment.deleteSiblingData("' . $row->id . '",this) title="Delete"><i class="i-Close-Window" style="color:red;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getHobbiesList($id)
    {
        $id      = sanitizeVariable($id);
        $data    = PatientHobbies::with('users')->where("patient_id", $id)->where("hobbies_status", 1)->orderBy('created_at', 'desc')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  onclick=carePlanDevelopment.editHobbiesData("' . $row->id . '",this) data-original-title="Edit" title="Edit"><i class=" editform i-Pen-4"></i></a>';
                $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip"  onclick=carePlanDevelopment.deleteHobbiesData("' . $row->id . '",this) data-original-title="Delete" title="Delete"><i class="i-Close-Window" style="color:red;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getPetList($id)
    {
        $id      = sanitizeVariable($id);
        $data    = PatientPet::with('users')->where("patient_id", $id)->where("pet_status", 1)->orderBy('created_at', 'desc')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip" onclick=carePlanDevelopment.editPetData("' . $row->id . '",this) data-original-title="Edit" title="Edit"><i class=" editform i-Pen-4"></i></a>';
                $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip" onclick=carePlanDevelopment.deletePetData("' . $row->id . '",this) data-original-title="Delete"  title="Delete"><i class="i-Close-Window" style="color:red;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getTravelList($id)
    {
        $id      = sanitizeVariable($id);
        $data    = PatientTravel::with('users')->where("patient_id", $id)->where('travel_status', 1)->orderBy('created_at', 'desc')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip" onclick=carePlanDevelopment.editTravelData("' . $row->id . '",this) data-original-title="Edit" title="Edit"><i class=" editform i-Pen-4"></i></a>';
                $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip" onclick=carePlanDevelopment.deleteTraveltData("' . $row->id . '",this) data-original-title="Delete" title="Delete"><i class="i-Close-Window" style="color:red;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function listCarePlanDevelopmentPatients(Request $request)
    {
        if ($request->ajax()) {
            $module_id    = getPageModuleName();
            $components   = ModuleComponents::where('module_id', $module_id)->where('components', 'Care Plan Development')->get('id');
            $component_id = $components[0]->id;
            $configTZ     = config('app.timezone');
            $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
            $patient_id   = !empty(sanitizeVariable($request->route('id'))) ? sanitizeVariable($request->route('id')) : '';
            $data         = DB::select("select * from ccm.cpd_patient_listing_search($module_id,$component_id,'" . $configTZ . "', '" . $userTZ . "')");
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="care-plan-development/' . $row->id . '" data-toggle="tooltip" data-original-title="Start" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('Ccm::care-plan-development.patient-list');
    }

    public function listCarePlanDevelopmentPatientsSearch(Request $request)
    {
        $patient_id       = sanitizeVariable($request->route('id'));
        if ($request->ajax()) {
            $module_id    = getPageModuleName();
            $components   = ModuleComponents::where('module_id', $module_id)->where('components', 'Care Plan Development')->get('id');
            $component_id = $components[0]->id;
            $configTZ     = config('app.timezone');
            $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
            $data         = DB::select("select id, fname, lname, mname, profile_img, mob, home_number, dob, created_by_user, created_by, 
            to_char(last_modified_at at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY HH24:MI:SS') as last_modified_at
             from ccm.cpd_patient_listing_search($module_id,$component_id,$patient_id,'" . $configTZ . "', '" . $userTZ . "')");
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
                            $btn = "<a href='/rpm/care-plan-development/" . $row->id . "' onclick='util.displayLoader()'><img src={$avatar} width='50px' class='user-image' />" . ' ' . $row->fname . ' ' . $row->mname . ' ' . $row->lname . '</a>';
                        } else {
                            $btn = "<a href='/rpm/care-plan-development/" . $row->id . "' onclick='util.displayLoader()'><img src='" . $row->profile_img . "' width='40px' height='25px' class='user-image' />" . ' ' . $row->fname . ' ' . $row->mname . ' ' . $row->lname . '</a>';
                        }
                    } else {
                        if ($row->profile_img == '' || $row->profile_img == null) {
                            $btn = "<a href='/ccm/care-plan-development/" . $row->id . "' onclick='util.displayLoader()'><img src={$avatar} width='50px' class='user-image' />" . ' ' . $row->fname . ' ' . $row->mname . ' ' . $row->lname . '</a>';
                        } else {
                            $btn = "<a href='/ccm/care-plan-development/" . $row->id . "' onclick='util.displayLoader()'><img src='" . $row->profile_img . "' width='40px' height='25px' class='user-image' />" . ' ' . $row->fname . ' ' . $row->mname . ' ' . $row->lname . '</a>';
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
                        $btn = '<a href="/rpm/care-plan-development/' . $row->id . '" onclick="util.displayLoader()" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                    } else {
                        $btn = '<a href="/ccm/care-plan-development/' . $row->id . '" onclick="util.displayLoader()" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['patientName', 'action'])
                ->make(true);
        }
        return view('Ccm::care-plan-development.patient-list');
    }

    public function fetchCarePlanDevelopmentPatientDetails(Request $request)
    {
        $cid                 = session()->get('userid');
        $patient_id          = sanitizeVariable($request->route('id'));
        $module_id           = getPageModuleName();
        $last_time_spend     = CommonFunctionController::getCcmNetTime($patient_id, $module_id);
        if ($last_time_spend != '00:00:00') {
            //$last_time_spend = $last_time_spend-$non_billabel_time;
        }
        $patient             = Patients::where('id', $patient_id)->get();
        $patient_providers   = PatientProvider::where('patient_id', $patient_id)
            ->with('practice')->where('provider_type_id', 1)->where('is_active', 1)->orderby('id', 'desc')->first();
        $billable            =  ($patient_providers != null) ? $patient_providers->practice['billable'] : "";
        $patient_enroll_date = PatientServices::latest_module($patient_id, $module_id);
        if (PatientServices::where('patient_id', $patient_id)->where('status', 1)->where('module_id', 2)->exists()) {
            $enroll_in_rpm   = 1;
        } else {
            $enroll_in_rpm   = 0;
        }
        return  view(
            'Ccm::care-plan-development.patient-details',
            ['patient' => $patient],
            compact(
                'last_time_spend',
                'patient_enroll_date',
                'billable',
                'enroll_in_rpm',
                'patient_providers'
            )
        );
    }

    public function saveAllergy(AllergiesAddRequest $request)
    { // 
        // dd($request);
        $uid                 = sanitizeVariable($request->uid);
        $patient_id          = sanitizeVariable($request->patient_id);
        $allergy_type        = sanitizeVariable($request->allergy_type);
        $tab                 = sanitizeVariable($request->tab);
        $allergyid           = sanitizeVariable($request->id);
        $type_of_reactions   = sanitizeVariable($request->type_of_reactions);
        $severity            = sanitizeVariable($request->severity);
        $course_of_treatment = sanitizeVariable($request->course_of_treatment);
        $notes               = sanitizeVariable($request->notes);
        $specify             = sanitizeVariable($request->specify);
        $start_time          = sanitizeVariable($request->start_time);
        $end_time            = sanitizeVariable($request->end_time);
        $module_id           = sanitizeVariable($request->module_id);
        $component_id        = sanitizeVariable($request->component_id);
        $stage_id            = sanitizeVariable($request->stage_id);
        $step_id             = sanitizeVariable($request->step_id);
        $form_name           = sanitizeVariable($request->form_name);
        $billable            = sanitizeVariable($request->billable);
        $allergy_status      = sanitizeVariable($request->allergy_status);
        $noallergymsg        = sanitizeVariable($request->noallergymsg);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            if ($allergy_status == 'on') {
                $noallergymsg = sanitizeVariable($request->noallergymsg);
            } else {
                $noallergymsg = '';
            }
            // dd($noallergymsg);
            $insert_allergy = array(
                'uid'                => $uid,
                'patient_id'         => $patient_id,
                'allergy_type'       => $allergy_type,
                'type_of_reactions'  => $type_of_reactions,
                'severity'           => $severity,
                'course_of_treatment' => $course_of_treatment,
                'notes'              => $notes,
                'specify'            => $specify,
                'allergy_status'     => $noallergymsg,
                'status'             => 1
            );
            if ($tab == 'review-allergy') {
                $insert_allergy['review'] = 1;
            }
            //record time 
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            if ($allergyid == '' || $allergyid == 'null') {
                $check = PatientAllergy::where('patient_id', $patient_id)->where('status', 1)->where('allergy_type', $allergy_type)->where('allergy_status', '!=', "")->get();
                if (count($check) > 0) {
                    $update_allergy['status'] = 0;
                    $update_allergy['updated_by'] = session()->get('userid');
                    $update_allergy['created_by'] = session()->get('userid');
                    PatientAllergy::where('id', $check[0]->id)->update($update_allergy);
                }
                $insert_allergy['updated_by'] = session()->get('userid');
                $insert_allergy['created_by'] = session()->get('userid');
                $insert_query                 = PatientAllergy::create($insert_allergy);
            } else {
                $insert_allergy['updated_by'] = session()->get('userid');
                $update_query                 = PatientAllergy::where('id', $allergyid)->where('status', 1)->where('allergy_type', $allergy_type)->update($insert_allergy);
            }
            $this->patientDataStatus($patient_id, $module_id, $component_id, $stage_id, $step_id);
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function count_Allergies_Inside_Table($id, $allergy_type)
    {
        $id = sanitizeVariable($id);
        $allergy_type = sanitizeVariable($allergy_type);
        $currentmonth = date('m');
        $currentyear  = date('Y');
        $query = "select * from patients.patient_allergy where patient_id ='" . $id . "' and allergy_type ='" . $allergy_type . "' 
        and status = 1 and extract(month from created_at) = '" . $currentmonth . "'
        and extract(year from created_at) = '" . $currentyear . "' and (allergy_status = '' or allergy_status is null)";
        $allergy = DB::select($query);
        $count_allergy = count($allergy);
        return $count_allergy;
    }

    public function savePatientData(PatientsDataAddRequest $request)
    {
        $patient_id   = sanitizeVariable($request->patient_id);
        $tab_name     = sanitizeVariable($request->tab_name);
        $mob          = sanitizeVariable($request->mob);
        $home_number  = sanitizeVariable($request->home_number);
        $add_1        = sanitizeVariable($request->add_1);
        $email        = sanitizeVariable($request->email);
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $module_id    = sanitizeVariable($request->module_id);
        $component_id = sanitizeVariable($request->component_id);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = sanitizeVariable($request->step_id);
        $form_name    = sanitizeVariable($request->form_name);
        $billable     = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            if ($tab_name == 'review-patient') {
                $insert['review'] = 1;
            }
            $insert = array(
                'mob'         => $mob,
                'home_number' => $home_number,
                'email'       => $email,
            );
            //record time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            $this->patientDataStatus($patient_id, $module_id, $component_id, $stage_id, $step_id);

            $insert_query = Patients::where('id', $patient_id)->update($insert);
            if ($insert_query == 1) {
                $dataExist = PatientAddress::where('patient_id', $patient_id)->exists();
                $insert_address = array(
                    'uid'        => $patient_id,
                    'patient_id' => $patient_id,
                    'add_1'      => $add_1
                );
                if ($dataExist == true) {
                    $insert_address['updated_by'] = session()->get('userid');
                    $update_query                 = PatientAddress::where('patient_id', $patient_id)->orderBy('id', 'desc')->first()->update($insert_address);
                } else {
                    $insert_address['updated_by'] = session()->get('userid');
                    $insert_address['created_by'] = session()->get('userid');
                    $insert_query                 = PatientAddress::create($insert_address);
                }
            }
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function savePatientpersonalfamilyData(PatientsFamilyAddRequest $request)
    {
        $id           = sanitizeVariable($request->id);
        $uid          = sanitizeVariable($request->uid);
        $patient_id   = sanitizeVariable($request->patient_id);
        $fname        = sanitizeVariable($request->fname);
        $mobile       = sanitizeVariable($request->mobile);
        $phone_2      = sanitizeVariable($request->phone_2);
        $address      = sanitizeVariable($request->address);
        $email        = sanitizeVariable($request->email);
        $lname        = sanitizeVariable($request->lname);
        $tab          = sanitizeVariable($request->tab);
        $tab_name     = sanitizeVariable($request->tab_name);
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $module_id    = sanitizeVariable($request->module_id);
        $component_id = sanitizeVariable($request->component_id);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = sanitizeVariable($request->step_id);
        $form_name    = sanitizeVariable($request->form_name);
        $billable     = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            $insert_familyPersonalData = array(
                'uid'               => $uid,
                'patient_id'        => $patient_id,
                'fname'             => $fname,
                'lname'             => $lname,
                'mobile'            => $mobile,
                'phone_2'           => $phone_2,
                'email'             => $email,
                'address'           => $address,
                'tab_name'          => $tab_name,
                'relationship'      => $tab_name,
                'relational_status' => 1
            );
            if ($tab == 'review-patient') {
                $insert_familyData['review'] = 1;
            }
            $dataExist = PatientFamily::where('patient_id', $patient_id)->where('tab_name', $tab_name)->exists();
            if ($dataExist == 1) {
                $insert_familyPersonalData['updated_by'] = session()->get('userid');
                $update_query                            = PatientFamily::where('patient_id', $patient_id)->where('id', $id)->where('tab_name', $tab_name)->orderBy('id', 'desc')->update($insert_familyPersonalData);
            } else {
                $insert_familyPersonalData['updated_by'] = session()->get('userid');
                $insert_familyPersonalData['created_by'] = session()->get('userid');
                $insert_query = PatientFamily::create($insert_familyPersonalData);
            }
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            $this->patientDataStatus($patient_id, $module_id, $component_id, $stage_id, $step_id);
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return $ex;
            // return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function patientDataStatus($patient_id, $module_id, $component_id, $stage_id, $step_id)
    {
        $patient_id   = sanitizeVariable($patient_id);
        $module_id    = sanitizeVariable($module_id);
        $component_id = sanitizeVariable($component_id);
        $stage_id     = sanitizeVariable($stage_id);
        $step_id      = sanitizeVariable($step_id);
        // DB::beginTransaction();
        // try {
        if ($step_id == 0 && $stage_id == 31) {
            // dd("HELLO");
        } else {
            $check_exist_data  = PatientDataStatus::where('patient_id', $patient_id)->where('stag_id', $stage_id)->where('stage_code_id', $step_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
            if ($check_exist_data == false) {
                $patientdata = array(
                    'patient_id'      => $patient_id,
                    'module_id'       => $module_id,
                    'component_id'    => $component_id,
                    'stag_id'         => $stage_id,
                    'stage_code_id'   => $step_id,
                    'created_by'      => session()->get('userid')
                );
                PatientDataStatus::create($patientdata);
            } else {
                return "does not exist";
            }
        }
        //     DB::commit();
        // } catch(\Exception $ex) {
        //     DB::rollBack();
        //     // return $ex;
        //     return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        // }            
    }

    public function getCompletedPatientData(Request $request)
    {
        $patient_id = sanitizeVariable($request->patient_id);
        $data = sanitizeVariable($request->finaldata);
        $finaldata  = array();
        for ($i = 0; $i < count($data); $i++) {
            $check_exist_data  = PatientDataStatus::where('patient_id', $patient_id)->whereIn('stage_code_id', $data[$i])->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
            if ($check_exist_data == true) {
                array_push($finaldata, $i);
            }
        }
        return $finaldata;
    }

    public function savePatientrelativeData(PatientsRelativeAddRequest $request)
    {
        $hidden_id         = sanitizeVariable($request->id);
        $uid               = sanitizeVariable($request->patient_id);
        $patient_id        = sanitizeVariable($request->patient_id);
        $relationshipval   = sanitizeVariable($request->relationship);
        $age               = sanitizeVariable($request->age);
        $fname             = sanitizeVariable($request->fname);
        $lname             = sanitizeVariable($request->lname);
        $tab               = sanitizeVariable($request->tab);
        $tab_name          = sanitizeVariable($request->tab_name);
        $mobile            = sanitizeVariable($request->mobile);
        $phone_2           = sanitizeVariable($request->phone_2);
        $email             = sanitizeVariable($request->email);
        $address           = sanitizeVariable($request->address);
        $additional_notes  = sanitizeVariable($request->additional_notes);
        $relational_status = sanitizeVariable($request->relational_status);
        $start_time        = sanitizeVariable($request->start_time);
        $end_time          = sanitizeVariable($request->end_time);
        $module_id         = sanitizeVariable($request->module_id);
        $component_id      = sanitizeVariable($request->component_id);
        $stage_id          = sanitizeVariable($request->stage_id);
        $step_id           = sanitizeVariable($request->step_id);
        $form_name         = sanitizeVariable($request->form_name);
        $billable          = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);

        DB::beginTransaction();
        try {
            foreach ($fname as $key => $value) {
                if ($tab_name == 'sibling' || $tab_name == 'children' || $tab_name == 'grandchildren') {
                    $relationship =  sanitizeVariable($request->relationship);
                }
                if ($tab_name == 'live-with') {
                    if ($relationshipval[$key] == '0') {
                        $relationship = sanitizeVariable($request->relationship_txt[$key]);
                    } else {
                        $relationship = sanitizeVariable($request->relationship[$key]);
                    }
                }
                if ($age == null) {
                    $age[$key] = null;
                } else if ($age[$key] == '') {
                    $age[$key] = null;
                }

                if ($address == null) {
                    $address[$key] = null;
                } else if ($address[$key] == '') {
                    $address[$key] = null;
                }

                $insert_familyData = array(
                    'relational_status' => $relational_status,
                    'patient_id'       => $patient_id,
                    'fname'            => $fname[$key],
                    'lname'            => $lname[$key],
                    'address'          => $address[$key],
                    'relationship'     => $relationship,
                    'age'              => $age[$key],
                    'tab_name'         => $tab_name,
                    'additional_notes' => $additional_notes[$key],
                );

                if ($tab == 'review-patient') {
                    $insert_familyData['review'] = 1;
                }
                $dataExist = PatientFamily::where('id', $hidden_id)->exists();
                if ($dataExist == true) {
                    $insert_familyData['updated_by'] = session()->get('userid');
                    $update_query = PatientFamily::where('id', $hidden_id)->orderBy('id', 'desc')->first()->update($insert_familyData);
                } else {
                    if ($insert_familyData['relational_status'] == 1) {
                        $delete = PatientFamily::where('patient_id', $patient_id)->where('tab_name', $tab_name)->where('relational_status', 0)->delete();
                    }
                    $insert_familyData['updated_by'] = session()->get('userid');
                    $insert_familyData['created_by'] = session()->get('userid');
                    $insert_query = PatientFamily::create($insert_familyData);
                }
            }
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function savePatientprovidersData(PatientsProvidersAddRequest $request)
    {
        $id                  = sanitizeVariable($request->id);
        $patient_id          = sanitizeVariable($request->patient_id);
        $provider_type_id    = sanitizeVariable($request->provider_type_id);
        $provider_name       = sanitizeVariable($request->provider_name);
        $provider_id         = sanitizeVariable($request->provider_id);
        $provider_subtype_id = sanitizeVariable($request->provider_subtype_id);
        $practice_name       = sanitizeVariable($request->practice_name);
        $practice_id         = sanitizeVariable($request->practice_id);
        $address             = sanitizeVariable($request->address);
        $phone_no            = sanitizeVariable($request->phone_no);
        $tab                 = sanitizeVariable($request->tab);
        $last_visit_date     = sanitizeVariable($request->last_visit_date);
        $specialist_id       = sanitizeVariable($request->specialist_id);
        $practice_emr        = sanitizeVariable($request->practice_emr);
        $start_time          = sanitizeVariable($request->start_time);
        $end_time            = sanitizeVariable($request->end_time);
        $module_id           = sanitizeVariable($request->module_id);
        $component_id        = sanitizeVariable($request->component_id);
        $stage_id            = sanitizeVariable($request->stage_id);
        $step_id             = sanitizeVariable($request->step_id);
        $form_name           = sanitizeVariable($request->form_name);
        $billable            = sanitizeVariable($request->billable);
        $practice_type       = sanitizeVariable($request->practice_type);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            if ($practice_id == '0') {
                $insert_practice_id = array(
                    'name'        => $practice_name,
                    'practice_type' => $practice_type,
                    'created_by'  => session()->get('userid'),
                    'is_active'   => 1
                );
                $new_practice_id    = Practices::create($insert_practice_id);
                $practice_id        = $new_practice_id->id;
            }
            if ($provider_id == '0') {
                $insert_provider_id = array(
                    'name'        => $provider_name,
                    'practice_id' => $practice_id,
                    'created_by'  => session()->get('userid'),
                    'provider_subtype_id' => $provider_subtype_id,
                    'provider_id'         => $provider_id,
                    'specialist_id'       => $specialist_id,
                    'is_active'   => 1
                );
                $new_provider_id    = Providers::create($insert_provider_id);
                $provider_id        = $new_provider_id->id;
            }
            $insert_providersData = array(
                'patient_id'          => $patient_id,
                'uid'                 => $patient_id,
                'provider_id'         => $provider_id,
                'provider_subtype_id' => $provider_subtype_id,
                'practice_id'         => $practice_id,
                'address'             => $address,
                'phone_no'            => $phone_no,
                'last_visit_date'     => $last_visit_date . ' 00:00:00', //(!empty($request->last_visit_date) || $request->last_visit_date!='')?date("Y-m-d H:i:s", strtotime($request->last_visit_date)):null,
                'provider_type_id'    => $provider_type_id,
                'specialist_id'       => $specialist_id,
                'practice_emr'        => $practice_emr,
                'is_active'           => 1
            );
            if ($tab == 'review-provider') {
                $insert_providersData['review'] = 1;
            }
            $dataExist = PatientProvider::where('patient_id', $patient_id)->where('is_active', 1)->where('provider_type_id', $provider_type_id)->exists();
            if ($dataExist == true && ($id == null || $id == '')) {
                $insert_providersData['updated_by'] = session()->get('userid');
                $insert_providersData['created_by'] = session()->get('userid');
                if ($provider_type_id == 1) {
                    $is_Active['is_active']             = 0;
                    PatientProvider::where('patient_id', $patient_id)->where('is_active', 1)->where('provider_type_id', $provider_type_id)->update($is_Active);
                    $insert_providersData['is_active']  = 1;
                    $insert_query = PatientProvider::create($insert_providersData);
                } else {

                    $insert_query = PatientProvider::create($insert_providersData);
                }
            } else if ($dataExist == true && $id != null) {
                $insert_providersData['updated_by'] = session()->get('userid');
                $update_query = PatientProvider::where('id', $id)->where('is_active', 1)->orderBy('id', 'desc')->first()->update($insert_providersData);
            } else {
                $insert_providersData['updated_by'] = session()->get('userid');
                $insert_providersData['created_by'] = session()->get('userid');
                $insert_query = PatientProvider::create($insert_providersData);
            }
            // record_time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            $this->patientDataStatus($patient_id, $module_id, $component_id, $stage_id, $step_id);
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }


    public function savePatientdiagnosisData(PatientsDiagnosisRequest $request)
    {

        // $hidden_id          = sanitizeVariable($request->diagnosis_id); // 4th aug 2022
        $hidden_id          = sanitizeVariable($request->diagnosis); // 4th aug 2022
        $patient_id         = sanitizeVariable($request->patient_id);
        $code               = sanitizeVariable($request->code);
        $goals              = sanitizeVariable($request->goals);
        $condition          = sanitizeVariable($request->diagnosis);
        $condition_name     = sanitizeVariable($request->condition);
        $support            = sanitizeVariable($request->support);
        $comments           = sanitizeVariable($request->comments);
        $symptoms           = sanitizeVariable($request->symptoms);
        $tasks              = sanitizeVariable($request->tasks);
        $tab_name           = sanitizeVariable($request->tab_name);
        $new_code           = sanitizeVariable($request->new_code);
        $start_time         = sanitizeVariable($request->start_time);
        $end_time           = sanitizeVariable($request->end_time);
        $module_id          = sanitizeVariable($request->module_id);
        $component_id       = sanitizeVariable($request->component_id);
        $stage_id           = sanitizeVariable($request->stage_id);
        $step_id            = sanitizeVariable($request->step_id);
        $billable           = sanitizeVariable($request->billable);
        $form_name          = sanitizeVariable($request->form_name);
        $createdby          = session()->get('userid');
        $hiddenenablebutton = sanitizeVariable($request->hiddenenablebutton);
        $editdiagnoid       = sanitizeVariable($request->editdiagnoid);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        // dd($editdiagnoid);





        $is_update_and_review_data = 0;
        if ($billable == 0 || $billable == 1) {
            $billable = $billable;
        } else {
            $billable = 1;
        }
        // DB::beginTransaction();
        // try {
        if ($code == '0' && $code != null) {
            $code = $new_code;
        } else {
            $code = sanitizeVariable($request->code);
        }
        $diagnosisData  = array(
            'code'      => $code,
            'condition' => $condition_name,
            'goals'     => json_encode($goals),
            'symptoms'  => json_encode($symptoms),
            'tasks'     => json_encode($tasks),
            'support'   => $support,
            'comments'  => $comments,
            'patient_id' => $patient_id,
            'uid'       => $patient_id,
            'diagnosis' => $condition,
        );
        if ($tab_name == '1') {
            $diagnosisData['review'] = 1;
        }
        //record time
        $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);


        if (!empty($code)) {
            $check_code  = DiagnosisCode::where('diagnosis_id', $condition)->where('code', $code)->exists();
            if ($check_code == false) {
                $create_code = array(
                    'diagnosis_id' => $condition,
                    'code' => $code,
                    'status' => 1
                );
                $create_code['created_by'] = session()->get('userid');
                $create_code['updated_by'] = session()->get('userid');
                $creatediagnosis = DiagnosisCode::create($create_code);
            }


            $check_exist_code  = PatientDiagnosis::where('patient_id', $patient_id)
                ->where('diagnosis', $condition)
                ->where('created_by', session()->get('userid'))
                ->where('code', $code)
                ->where('status', 1)
                ->whereDate('created_at', '=', Carbon::today()->toDateString())
                ->exists();


            $check_exist_diagnosis_data  = PatientDiagnosis::where('patient_id', $patient_id)
                ->where('diagnosis', $condition)
                // ->where('created_by',session()->get('userid')) 
                ->where('code', $code)
                ->where('status', 1)
                ->whereDate('created_at', '=', Carbon::today()->toDateString())
                ->get();


            if ($hidden_id != '' && isset($hidden_id)) {
                // dd("if");

                // 4th aug 2022 changed variable name to check
                $check = PatientDiagnosis::where('diagnosis', $hidden_id)->where('patient_id', $patient_id)->where('status', 1)->exists();
                $checkforcurrentdate = PatientDiagnosis::where('diagnosis', $hidden_id)->where('patient_id', $patient_id)->whereDate('created_at', '=', Carbon::today()->toDateString())->where('status', 1)->exists();
                // dd($checkforcurrentdate);

                if ($check == true &&  $checkforcurrentdate == true) {
                    //    dd("if inside");
                    //this loop is for previous month or current month update and review..                   
                    // dd("check if");


                    $diagnosisData['updated_by'] = session()->get('userid');

                    $check_exist_diagnosis_data_widout_currentdate  = PatientDiagnosis::where('patient_id', $patient_id)
                        ->where('diagnosis', $condition)
                        // ->where('created_by',session()->get('userid')) 
                        ->where('code', $code)
                        ->where('status', 1)
                        ->orderBy('created_at', 'desc')
                        ->skip(0)->take(1)
                        ->get();
                    // dd($check_exist_diagnosis_data_widout_currentdate);
                    //place this $check_exist_diagnosis_data_widout_currentdate before $update_query to compare array of symptons,goals,tasks
                    // dd($hiddenenablebutton);

                    if ($hiddenenablebutton == 0) {

                        $is_update_and_review_data = 0;
                        $action = 'reviewed';
                    } else if ($hiddenenablebutton == 1) {

                        $is_update_and_review_data = 1;
                        $action = 'modified';
                    } else {
                        $is_update_and_review_data = null;
                        $action = 'created';
                    }

                    // dd($action);
                    // dd($editdiagnoid);  

                    /******************************************added by me on 19th sept********************************/
                    $update_query = PatientDiagnosis::where('diagnosis', $hidden_id)->where('patient_id', $patient_id)
                        ->where('status', 1)
                        // ->whereDate('created_at', '=', Carbon::today()->toDateString())
                        ->where('id', $editdiagnoid) //added for updating only particular diagnosis on 7th september 2022--mail from juliet It created duplicates of my previous Diabetes entries, kept the previous entries, and overwrote all their dates and times to today
                        ->update($diagnosisData);


                    $latestrecord = PatientDiagnosis::where('diagnosis', $hidden_id)
                        ->where('patient_id', $patient_id)
                        ->where('status', 1)
                        // ->whereDate('created_at', '=', Carbon::today()->toDateString())   
                        ->where('id', $editdiagnoid) //added for updating only particular diagnosis on 7th september 2022--mail from juliet It created duplicates of my previous Diabetes entries, kept the previous entries, and overwrote all their dates and times to today
                        ->orderBy('created_at', 'desc')
                        ->skip(0)->take(1)->get();


                    $patient_diagnosis_id = $latestrecord[0]->id;
                    $new_diagnosis_id = $latestrecord[0]->diagnosis;

                    /******************************************added by me on 19th sept********************************/




                    if (($action == 'reviewed') && ($is_update_and_review_data == 0)) {

                        // dd("if review");

                        $action = 'reviewed';
                        $c = CarePlanUpdateLogs::where('diagnosis_id', $new_diagnosis_id)
                            ->where('patient_diagnosis_id', $patient_diagnosis_id)
                            ->where('patient_id', $patient_id)
                            ->where('status', 1)
                            ->orderBy('created_at', 'desc')
                            ->skip(0)->take(1)->get();



                        if (count($c) > 0) {
                            $update_date = $c[0]->update_date;
                        } else {


                            $update_date =  $latestrecord[0]->updated_at;
                            $update_datetime_arr = explode(" ", $update_date);
                            $update_date_array = explode("-", $update_datetime_arr[0]);
                            $update_date = $update_date_array[2] . "-" . $update_date_array[0] . "-" . $update_date_array[1] . " " . $update_datetime_arr[1];
                        }




                        $review_date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();


                        $careplanlogsdata = array(
                            'patient_id' => $patient_id,
                            'diagnosis_id' => $new_diagnosis_id,
                            'patient_diagnosis_id' => $patient_diagnosis_id,
                            'created_by' => $createdby,
                            'updated_by' => $createdby,
                            'status' => 1
                        );
                    } else {

                        // dd("else modified");
                        $action = 'modified';
                        $update_date =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();; // Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();               
                        $review_date =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();// Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();

                    }


                    $careplanid  = $patient_diagnosis_id; // 4th aug 2022
                    $careplanlogsdata = array(
                        'patient_id' => $patient_id,
                        'diagnosis_id' => $new_diagnosis_id,
                        'patient_diagnosis_id' => $careplanid,
                        'created_by' => $createdby,
                        'updated_by' => $createdby,
                        'update_date' => $update_date,
                        'review_date' => $review_date,
                        'action' => $action,
                        'status' => 1
                    );
                    // dd($careplanlogsdata);
                    $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
                    $diag_id = $new_diagnosis_id;
                } else {

                    // dd("check else");

                    $diagnosisData['created_by'] = session()->get('userid');
                    $diagnosisData['updated_by'] = session()->get('userid');
                    $insert_query = PatientDiagnosis::create($diagnosisData);
                    $update_date  =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();//Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();
                    $review_date  =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();
                    $careplanlogsdata = array(
                        'patient_id' => $patient_id,
                        'diagnosis_id' => $insert_query->diagnosis,
                        'patient_diagnosis_id' => $insert_query->id,
                        'created_by' => $createdby,
                        'updated_by' => $createdby,
                        'update_date' => $update_date,
                        'review_date' => $review_date,
                        'action' => 'created',
                        'status' => 1
                    );
                    //dd($careplanlogsdata);
                    $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
                    $diag_id = $insert_query->diagnosis;
                }
            } else if ($check_exist_code == true && $hidden_id == '') {
                // dd(" first else if");      


                $diagnosisData['updated_by'] = session()->get('userid');


                $update_query = PatientDiagnosis::where('patient_id', $patient_id)
                    ->where('status', 1)
                    ->where('diagnosis', $condition)
                    ->where('code', $code)->whereDate('updated_at', '=', Carbon::today()->toDateString())
                    ->update($diagnosisData);


                $latestrecord = PatientDiagnosis::where('patient_id', $patient_id)
                    ->where('diagnosis', $condition)
                    ->where('code', $code)
                    ->where('status', 1)
                    ->whereDate('updated_at', '=', Carbon::today()->toDateString())
                    ->orderBy('created_at', 'desc')
                    ->skip(0)->take(1)->get();

                $patient_diagnosis_id = $latestrecord[0]->id;
                $new_diagnosis_id = $latestrecord[0]->diagnosis;

                if ($hiddenenablebutton == 0) {

                    $is_update_and_review_data = 0;
                    $action = 'reviewed';
                    $c = CarePlanUpdateLogs::where('patient_diagnosis_id', $patient_diagnosis_id)->where('diagnosis_id', $new_diagnosis_id)
                        ->where('status', 1)
                        ->where('patient_id', $patient_id)
                        ->orderBy('created_at', 'desc')
                        ->skip(0)->take(1)->get();

                    if (count($c) > 0) {
                        $update_date = $c[0]->update_date;
                    } else {
                        $update_date =  $latestrecord[0]->updated_at;

                        $update_datetime_arr = explode(" ", $update_date);
                        $update_date_array = explode("-", $update_datetime_arr[0]);
                        $update_date = $update_date_array[2] . "-" . $update_date_array[0] . "-" . $update_date_array[1] . " " . $update_datetime_arr[1];
                    }
                    $review_date  =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();
                    $careplanlogsdata = array(
                        'patient_id' => $patient_id,
                        'patient_diagnosis_id' => $patient_diagnosis_id,
                        'diagnosis_id' => $new_diagnosis_id,
                        'created_by' => $createdby,
                        'updated_by' => $createdby,
                        'status' => 1
                    );
                } else {

                    $is_update_and_review_data = 1;
                    $action = 'modified';
                    $update_date =   Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();               
                    $review_date =   Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();
                }
                $careplanlogsdata = array(
                    'patient_id' => $patient_id,
                    'diagnosis_id' => $new_diagnosis_id,
                    'patient_diagnosis_id' => $patient_diagnosis_id,
                    'created_by' => $createdby,
                    'updated_by' => $createdby,
                    'update_date' => $update_date,
                    'review_date' => $review_date,
                    'action' => $action,
                    'status' => 1
                );


                //dd($careplanlogsdata);
                $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
                $diag_id = $new_diagnosis_id;
            } else if ($check_exist_code == true) {
                // dd(" second else if");


                $diagnosisData['updated_by'] = session()->get('userid');
                $update_query = PatientDiagnosis::where('patient_id', $patient_id)->where('code', $code)->where('diagnosis', $condition)->whereDate('updated_at', '=', Carbon::today()->toDateString())->update($diagnosisData);
                $latestrecord = PatientDiagnosis::where('patient_id', $patient_id)
                    ->where('code', $code)
                    ->where('diagnosis', $condition)
                    ->where('status', 1)
                    ->whereDate('updated_at', '=', Carbon::today()->toDateString())
                    ->orderBy('created_at', 'desc')
                    ->skip(0)->take(1)->get();

                $patient_diagnosis_id = $latestrecord[0]->id;
                $new_diagnosis_id = $latestrecord[0]->diagnosis;





                if ($hiddenenablebutton == 0) {
                    $is_update_and_review_data = 0;
                    $action = 'reviewed';
                    $c = CarePlanUpdateLogs::where('diagnosis_id', $new_diagnosis_id)
                        ->where('patient_diagnosis_id', $patient_diagnosis_id)
                        ->where('patient_id', $patient_id)
                        ->where('status', 1)
                        ->orderBy('created_at', 'desc')
                        ->skip(0)->take(1)->get();
                    if (count($c) > 0) {
                        $update_date = $c[0]->update_date;
                    } else {
                        $update_date =  $latestrecord[0]->updated_at;

                        $update_datetime_arr = explode(" ", $update_date);
                        $update_date_array = explode("-", $update_datetime_arr[0]);
                        $update_date = $update_date_array[2] . "-" . $update_date_array[0] . "-" . $update_date_array[1] . " " . $update_datetime_arr[1];
                    }
                    $review_date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();    //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();
                    $careplanlogsdata = array(
                        'patient_id' => $patient_id,
                        'patient_diagnosis_id' => $patient_diagnosis_id,
                        'diagnosis_id' => $new_diagnosis_id,
                        'created_by' => $createdby,
                        'updated_by' => $createdby,
                        'status' => 1
                    );
                } else {
                    $is_update_and_review_data = 1;
                    $action = 'modified';
                    $update_date =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s');//Carbon::now();               
                    $review_date =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s');//Carbon::now();
                }
                $careplanlogsdata = array(
                    'patient_id' => $patient_id,
                    'diagnosis_id' => $new_diagnosis_id,
                    'patient_diagnosis_id' => $patient_diagnosis_id,
                    'created_by' => $createdby,
                    'updated_by' => $createdby,
                    'update_date' => $update_date,
                    'review_date' => $review_date,
                    'action' => $action,
                    'status' => 1
                );
                $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
                $diag_id = $new_diagnosis_id;
            } else {
                // dd("last else");
                $diagnosisData['updated_by'] = session()->get('userid');
                $diagnosisData['created_by'] = session()->get('userid');
                $insert_query = PatientDiagnosis::create($diagnosisData);
                $update_date  =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s');//Carbon::now();
                $review_date  =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s');//Carbon::now();
                $careplanlogsdata = array(
                    'patient_id' => $patient_id,
                    'patient_diagnosis_id' => $insert_query->id,
                    'diagnosis_id' => $insert_query->diagnosis,
                    'action' => 'created',
                    'created_by' => $createdby,
                    'updated_by' => $createdby,
                    'update_date' => $update_date,
                    'review_date' => $review_date,
                    'status' => 1
                );


                $diag_id = $insert_query->diagnosis;
                $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
            }
            $mydata = CarePlanUpdateLogs::where('patient_id', $patient_id)
                ->where('status', 1)
                ->orderBy('created_at', 'desc')
                ->skip(0)->take(1)->get();

            if (count($mydata) > 0) {


                $finaldata = array(
                    'patient_id' => $patient_id,
                    'diagnosis_id' => $mydata[0]->diagnosis_id,
                    'created_by' => $createdby,
                    'updated_by' => $createdby,
                    'update_date' => $mydata[0]->update_date,
                    'review_date' => $mydata[0]->review_date,
                    'status' => 1
                );
                $diag_id = $mydata[0]->diagnosis_id;
            }
            $checkexsits = PatientCareplanLastUpdateandReview::where('patient_id', $patient_id)->where('diagnosis_id', $diag_id)->where('status', 1)->exists();

            // dd($finaldata);    


            if ($checkexsits == true) {
                $insert_finaldata =  PatientCareplanLastUpdateandReview::where('patient_id', $patient_id)->where('diagnosis_id', $diag_id)->where('status', 1)->update($finaldata);
            } else {
                $insert_finaldata =  PatientCareplanLastUpdateandReview::create($finaldata);
            }
        }
        $last_sequence = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->max('sequence'); //->where('created_at', CallWrap::max('created_at'))
        $new_sequence = $last_sequence + 1;
        $topic_name = $condition_name . " specific general notes";
        $condition_data = array(
            'uid'                 => $patient_id,
            // 'record_date'         => Carbon::now()->format('Y-m-d H:i:s'),
            'record_date'         => Carbon::now(),
            'topic'               => $topic_name,
            'notes'               => $comments,
            'emr_entry_completed' => null,
            'created_by'          => session()->get('userid'),
            'patient_id'          => $patient_id,
            'template_type'       => ''
        );
        $checkTopicExist = CallWrap::where('patient_id', $patient_id)->where('topic', $topic_name)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();

        if ($checkTopicExist == true) {
            CallWrap::where('patient_id', $patient_id)->where('topic', $topic_name)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->update($condition_data);
        } else {
            $condition_data['sequence'] = $new_sequence;
            CallWrap::create($condition_data);
        }
        $this->patientDataStatus($patient_id, $module_id, $component_id, $stage_id, $step_id);
        return response(['form_start_time' => $form_save_time]);
        //     DB::commit();
        // } catch(\Exception $ex) {
        //     DB::rollBack();
        //     // return $ex;
        //     return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        // }
    }



    //**********************************************this one is not in use bcz of arry_diff instead using hiddenenablebutton boolean in above function  */
    // public function savePatientdiagnosisData_5thsept2022_backup_for_array_diff(PatientsDiagnosisRequest $request)
    // {


    //     $hiddenenablebutton = sanitizeVariable($request->hiddenenablebutton);
    //     // $hidden_id          = sanitizeVariable($request->diagnosis_id); // 4th aug 2022
    //     $hidden_id          = sanitizeVariable($request->diagnosis); // 4th aug 2022
    //     $patient_id         = sanitizeVariable($request->patient_id);
    //     $code               = sanitizeVariable($request->code);
    //     $goals              = sanitizeVariable($request->goals);
    //     $condition          = sanitizeVariable($request->diagnosis);
    //     $condition_name     = sanitizeVariable($request->condition);
    //     $support            = sanitizeVariable($request->support);
    //     $comments           = sanitizeVariable($request->comments);
    //     $symptoms           = sanitizeVariable($request->symptoms);
    //     $tasks              = sanitizeVariable($request->tasks);
    //     $tab_name           = sanitizeVariable($request->tab_name);
    //     $new_code           = sanitizeVariable($request->new_code);
    //     $start_time         = sanitizeVariable($request->start_time);
    //     $end_time           = sanitizeVariable($request->end_time);
    //     $module_id          = sanitizeVariable($request->module_id);
    //     $component_id       = sanitizeVariable($request->component_id);
    //     $stage_id           = sanitizeVariable($request->stage_id);
    //     $step_id            = sanitizeVariable($request->step_id);
    //     $billable           = sanitizeVariable($request->billable);
    //     $form_name          = sanitizeVariable($request->form_name);
    //     $createdby          = session()->get('userid');
    //     $hiddenenablebutton = sanitizeVariable($request->hiddenenablebutton);





    //     $is_update_and_review_data = 0;
    //     if ($billable == 0 || $billable == 1) {
    //         $billable = $billable;
    //     } else {
    //         $billable = 1;
    //     }
    //     // DB::beginTransaction();
    //     // try {
    //     if ($code == '0' && $code != null) {
    //         $code = $new_code;
    //     } else {
    //         $code = sanitizeVariable($request->code);
    //     }
    //     $diagnosisData  = array(
    //         'code'      => $code,
    //         'condition' => $condition_name,
    //         'goals'     => json_encode($goals),
    //         'symptoms'  => json_encode($symptoms),
    //         'tasks'     => json_encode($tasks),
    //         'support'   => $support,
    //         'comments'  => $comments,
    //         'patient_id' => $patient_id,
    //         'uid'       => $patient_id,
    //         'diagnosis' => $condition,
    //     );
    //     if ($tab_name == '1') {
    //         $diagnosisData['review'] = 1;
    //     }
    //     //record time
    //     $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name);


    //     if (!empty($code)) {
    //         $check_code  = DiagnosisCode::where('diagnosis_id', $condition)->where('code', $code)->exists();
    //         if ($check_code == false) {
    //             $create_code = array(
    //                 'diagnosis_id' => $condition,
    //                 'code' => $code,
    //                 'status' => 1
    //             );
    //             $create_code['created_by'] = session()->get('userid');
    //             $create_code['updated_by'] = session()->get('userid');
    //             $creatediagnosis = DiagnosisCode::create($create_code);
    //         }


    //         $check_exist_code  = PatientDiagnosis::where('patient_id', $patient_id)
    //             ->where('diagnosis', $condition)
    //             ->where('created_by', session()->get('userid'))
    //             ->where('code', $code)
    //             ->where('status', 1)
    //             ->whereDate('created_at', '=', Carbon::today()->toDateString())
    //             ->exists();


    //         $check_exist_diagnosis_data  = PatientDiagnosis::where('patient_id', $patient_id)
    //             ->where('diagnosis', $condition)
    //             // ->where('created_by',session()->get('userid')) 
    //             ->where('code', $code)
    //             ->where('status', 1)
    //             ->whereDate('created_at', '=', Carbon::today()->toDateString())
    //             ->get();


    //         if ($hidden_id != '' && isset($hidden_id)) {
    //             // dd("if");

    //             // 4th aug 2022 changed variable name to check
    //             $check = PatientDiagnosis::where('diagnosis', $hidden_id)->where('patient_id', $patient_id)->where('status', 1)->exists();
    //             $checkforcurrentdate = PatientDiagnosis::where('diagnosis', $hidden_id)->where('patient_id', $patient_id)->whereDate('created_at', '=', Carbon::today()->toDateString())->where('status', 1)->exists();
    //             // dd($checkforcurrentdate);

    //             if ($check == true &&  $checkforcurrentdate == true) {
    //                 //    dd("if inside");
    //                 //this loop is for previous month or current month update and review..                   
    //                 // dd("check if");


    //                 $diagnosisData['updated_by'] = session()->get('userid');

    //                 $check_exist_diagnosis_data_widout_currentdate  = PatientDiagnosis::where('patient_id', $patient_id)
    //                     ->where('diagnosis', $condition)
    //                     // ->where('created_by',session()->get('userid')) 
    //                     ->where('code', $code)
    //                     ->where('status', 1)
    //                     ->orderBy('created_at', 'desc')
    //                     ->skip(0)->take(1)
    //                     ->get();
    //                 // dd($check_exist_diagnosis_data_widout_currentdate);
    //                 //place this $check_exist_diagnosis_data_widout_currentdate before $update_query to compare array of symptons,goals,tasks
    //                 // dd($hiddenenablebutton);

    //                 if ($hiddenenablebutton == 0) {

    //                     $is_update_and_review_data = 0;
    //                     $action = 'reviewed';
    //                 } else if ($hiddenenablebutton == 1) {

    //                     $is_update_and_review_data = 1;
    //                     $action = 'modified';
    //                 } else {
    //                     $is_update_and_review_data = null;
    //                     $action = 'created';
    //                 }

    //                 // dd($action);
    //                 // dd($editdiagnoid);  

    //                 /******************************************added by me on 19th sept********************************/
    //                 $update_query = PatientDiagnosis::where('diagnosis', $hidden_id)->where('patient_id', $patient_id)
    //                     ->where('status', 1)
    //                     // ->whereDate('created_at', '=', Carbon::today()->toDateString())
    //                     ->where('id', $editdiagnoid) //added for updating only particular diagnosis on 7th september 2022--mail from juliet It created duplicates of my previous Diabetes entries, kept the previous entries, and overwrote all their dates and times to today
    //                     ->update($diagnosisData);


    //                 $latestrecord = PatientDiagnosis::where('diagnosis', $hidden_id)
    //                     ->where('patient_id', $patient_id)
    //                     ->where('status', 1)
    //                     // ->whereDate('created_at', '=', Carbon::today()->toDateString())   
    //                     ->where('id', $editdiagnoid) //added for updating only particular diagnosis on 7th september 2022--mail from juliet It created duplicates of my previous Diabetes entries, kept the previous entries, and overwrote all their dates and times to today
    //                     ->orderBy('created_at', 'desc')
    //                     ->skip(0)->take(1)->get();


    //                 $patient_diagnosis_id = $latestrecord[0]->id;
    //                 $new_diagnosis_id = $latestrecord[0]->diagnosis;

    //                 /******************************************added by me on 19th sept********************************/




    //                 if (($action == 'reviewed') && ($is_update_and_review_data == 0)) {

    //                     // dd("if review");

    //                     $action = 'reviewed';
    //                     $c = CarePlanUpdateLogs::where('diagnosis_id', $new_diagnosis_id)
    //                         ->where('patient_diagnosis_id', $patient_diagnosis_id)
    //                         ->where('patient_id', $patient_id)
    //                         ->where('status', 1)
    //                         ->orderBy('created_at', 'desc')
    //                         ->skip(0)->take(1)->get();



    //                     if (count($c) > 0) {
    //                         $update_date = $c[0]->update_date;
    //                     } else {


    //                         $update_date =  $latestrecord[0]->updated_at;
    //                         $update_datetime_arr = explode(" ", $update_date);
    //                         $update_date_array = explode("-", $update_datetime_arr[0]);
    //                         $update_date = $update_date_array[2] . "-" . $update_date_array[0] . "-" . $update_date_array[1] . " " . $update_datetime_arr[1];
    //                     }




    //                     $review_date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();


    //                     $careplanlogsdata = array(
    //                         'patient_id' => $patient_id,
    //                         'diagnosis_id' => $new_diagnosis_id,
    //                         'patient_diagnosis_id' => $patient_diagnosis_id,
    //                         'created_by' => $createdby,
    //                         'updated_by' => $createdby,
    //                         'status' => 1
    //                     );
    //                 } else {

    //                     // dd("else modified");
    //                     $action = 'modified';
    //                     $update_date =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();; // Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();               
    //                     $review_date =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();// Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();

    //                 }


    //                 $careplanid  = $patient_diagnosis_id; // 4th aug 2022
    //                 $careplanlogsdata = array(
    //                     'patient_id' => $patient_id,
    //                     'diagnosis_id' => $new_diagnosis_id,
    //                     'patient_diagnosis_id' => $careplanid,
    //                     'created_by' => $createdby,
    //                     'updated_by' => $createdby,
    //                     'update_date' => $update_date,
    //                     'review_date' => $review_date,
    //                     'action' => $action,
    //                     'status' => 1
    //                 );
    //                 // dd($careplanlogsdata);
    //                 $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
    //                 $diag_id = $new_diagnosis_id;
    //             } else {

    //                 // dd("check else");

    //                 $diagnosisData['created_by'] = session()->get('userid');
    //                 $diagnosisData['updated_by'] = session()->get('userid');
    //                 $insert_query = PatientDiagnosis::create($diagnosisData);
    //                 $update_date  =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();//Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();
    //                 $review_date  =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();
    //                 $careplanlogsdata = array(
    //                     'patient_id' => $patient_id,
    //                     'diagnosis_id' => $insert_query->diagnosis,
    //                     'patient_diagnosis_id' => $insert_query->id,
    //                     'created_by' => $createdby,
    //                     'updated_by' => $createdby,
    //                     'update_date' => $update_date,
    //                     'review_date' => $review_date,
    //                     'action' => 'created',
    //                     'status' => 1
    //                 );
    //                 //dd($careplanlogsdata);
    //                 $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
    //                 $diag_id = $insert_query->diagnosis;
    //             }
    //         } else if ($check_exist_code == true && $hidden_id == '') {
    //             // dd(" first else if");      


    //             $diagnosisData['updated_by'] = session()->get('userid');


    //             $update_query = PatientDiagnosis::where('patient_id', $patient_id)
    //                 ->where('status', 1)
    //                 ->where('diagnosis', $condition)
    //                 ->where('code', $code)->whereDate('updated_at', '=', Carbon::today()->toDateString())
    //                 ->update($diagnosisData);


    //             $latestrecord = PatientDiagnosis::where('patient_id', $patient_id)
    //                 ->where('diagnosis', $condition)
    //                 ->where('code', $code)
    //                 ->where('status', 1)
    //                 ->whereDate('updated_at', '=', Carbon::today()->toDateString())
    //                 ->orderBy('created_at', 'desc')
    //                 ->skip(0)->take(1)->get();

    //             $patient_diagnosis_id = $latestrecord[0]->id;
    //             $new_diagnosis_id = $latestrecord[0]->diagnosis;

    //             if ($hiddenenablebutton == 0) {

    //                 $is_update_and_review_data = 0;
    //                 $action = 'reviewed';
    //                 $c = CarePlanUpdateLogs::where('patient_diagnosis_id', $patient_diagnosis_id)->where('diagnosis_id', $new_diagnosis_id)
    //                     ->where('status', 1)
    //                     ->where('patient_id', $patient_id)
    //                     ->orderBy('created_at', 'desc')
    //                     ->skip(0)->take(1)->get();

    //                 if (count($c) > 0) {
    //                     $update_date = $c[0]->update_date;
    //                 } else {
    //                     $update_date =  $latestrecord[0]->updated_at;

    //                     $update_datetime_arr = explode(" ", $update_date);
    //                     $update_date_array = explode("-", $update_datetime_arr[0]);
    //                     $update_date = $update_date_array[2] . "-" . $update_date_array[0] . "-" . $update_date_array[1] . " " . $update_datetime_arr[1];
    //                 }
    //                 $review_date  =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();
    //                 $careplanlogsdata = array(
    //                     'patient_id' => $patient_id,
    //                     'patient_diagnosis_id' => $patient_diagnosis_id,
    //                     'diagnosis_id' => $new_diagnosis_id,
    //                     'created_by' => $createdby,
    //                     'updated_by' => $createdby,
    //                     'status' => 1
    //                 );
    //             } else {

    //                 $is_update_and_review_data = 1;
    //                 $action = 'modified';
    //                 $update_date =   Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();               
    //                 $review_date =   Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();
    //             }
    //             $careplanlogsdata = array(
    //                 'patient_id' => $patient_id,
    //                 'diagnosis_id' => $new_diagnosis_id,
    //                 'patient_diagnosis_id' => $patient_diagnosis_id,
    //                 'created_by' => $createdby,
    //                 'updated_by' => $createdby,
    //                 'update_date' => $update_date,
    //                 'review_date' => $review_date,
    //                 'action' => $action,
    //                 'status' => 1
    //             );


    //             //dd($careplanlogsdata);
    //             $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
    //             $diag_id = $new_diagnosis_id;
    //         } else if ($check_exist_code == true) {
    //             // dd(" second else if");


    //             $diagnosisData['updated_by'] = session()->get('userid');
    //             $update_query = PatientDiagnosis::where('patient_id', $patient_id)->where('code', $code)->where('diagnosis', $condition)->whereDate('updated_at', '=', Carbon::today()->toDateString())->update($diagnosisData);
    //             $latestrecord = PatientDiagnosis::where('patient_id', $patient_id)
    //                 ->where('code', $code)
    //                 ->where('diagnosis', $condition)
    //                 ->where('status', 1)
    //                 ->whereDate('updated_at', '=', Carbon::today()->toDateString())
    //                 ->orderBy('created_at', 'desc')
    //                 ->skip(0)->take(1)->get();

    //             $patient_diagnosis_id = $latestrecord[0]->id;
    //             $new_diagnosis_id = $latestrecord[0]->diagnosis;





    //             if ($hiddenenablebutton == 0) {
    //                 $is_update_and_review_data = 0;
    //                 $action = 'reviewed';
    //                 $c = CarePlanUpdateLogs::where('diagnosis_id', $new_diagnosis_id)
    //                     ->where('patient_diagnosis_id', $patient_diagnosis_id)
    //                     ->where('patient_id', $patient_id)
    //                     ->where('status', 1)
    //                     ->orderBy('created_at', 'desc')
    //                     ->skip(0)->take(1)->get();
    //                 if (count($c) > 0) {
    //                     $update_date = $c[0]->update_date;
    //                 } else {
    //                     $update_date =  $latestrecord[0]->updated_at;

    //                     $update_datetime_arr = explode(" ", $update_date);
    //                     $update_date_array = explode("-", $update_datetime_arr[0]);
    //                     $update_date = $update_date_array[2] . "-" . $update_date_array[0] . "-" . $update_date_array[1] . " " . $update_datetime_arr[1];
    //                 }
    //                 $review_date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();    //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();
    //                 $careplanlogsdata = array(
    //                     'patient_id' => $patient_id,
    //                     'patient_diagnosis_id' => $patient_diagnosis_id,
    //                     'diagnosis_id' => $new_diagnosis_id,
    //                     'created_by' => $createdby,
    //                     'updated_by' => $createdby,
    //                     'status' => 1
    //                 );
    //             } else {
    //                 $is_update_and_review_data = 1;
    //                 $action = 'modified';
    //                 $update_date =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s');//Carbon::now();               
    //                 $review_date =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s');//Carbon::now();
    //             }
    //             $careplanlogsdata = array(
    //                 'patient_id' => $patient_id,
    //                 'diagnosis_id' => $new_diagnosis_id,
    //                 'patient_diagnosis_id' => $patient_diagnosis_id,
    //                 'created_by' => $createdby,
    //                 'updated_by' => $createdby,
    //                 'update_date' => $update_date,
    //                 'review_date' => $review_date,
    //                 'action' => $action,
    //                 'status' => 1
    //             );
    //             $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
    //             $diag_id = $new_diagnosis_id;
    //         } else {
    //             // dd("last else");
    //             $diagnosisData['updated_by'] = session()->get('userid');
    //             $diagnosisData['created_by'] = session()->get('userid');
    //             $insert_query = PatientDiagnosis::create($diagnosisData);
    //             $update_date  =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s');//Carbon::now();
    //             $review_date  =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s');//Carbon::now();
    //             $careplanlogsdata = array(
    //                 'patient_id' => $patient_id,
    //                 'patient_diagnosis_id' => $insert_query->id,
    //                 'diagnosis_id' => $insert_query->diagnosis,
    //                 'action' => 'created',
    //                 'created_by' => $createdby,
    //                 'updated_by' => $createdby,
    //                 'update_date' => $update_date,
    //                 'review_date' => $review_date,
    //                 'status' => 1
    //             );


    //             $diag_id = $insert_query->diagnosis;
    //             $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
    //         }
    //         $mydata = CarePlanUpdateLogs::where('patient_id', $patient_id)
    //             ->where('status', 1)
    //             ->orderBy('created_at', 'desc')
    //             ->skip(0)->take(1)->get();

    //         if (count($mydata) > 0) {


    //             $finaldata = array(
    //                 'patient_id' => $patient_id,
    //                 'diagnosis_id' => $mydata[0]->diagnosis_id,
    //                 'created_by' => $createdby,
    //                 'updated_by' => $createdby,
    //                 'update_date' => $mydata[0]->update_date,
    //                 'review_date' => $mydata[0]->review_date,
    //                 'status' => 1
    //             );
    //             $diag_id = $mydata[0]->diagnosis_id;
    //         }
    //         $checkexsits = PatientCareplanLastUpdateandReview::where('patient_id', $patient_id)->where('diagnosis_id', $diag_id)->where('status', 1)->exists();

    //         // dd($finaldata);    


    //         if ($checkexsits == true) {
    //             $insert_finaldata =  PatientCareplanLastUpdateandReview::where('patient_id', $patient_id)->where('diagnosis_id', $diag_id)->where('status', 1)->update($finaldata);
    //         } else {
    //             $insert_finaldata =  PatientCareplanLastUpdateandReview::create($finaldata);
    //         }
    //     }
    //     $last_sequence = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->max('sequence'); //->where('created_at', CallWrap::max('created_at'))
    //     $new_sequence = $last_sequence + 1;
    //     $topic_name = $condition_name . " specific general notes";
    //     $condition_data = array(
    //         'uid'                 => $patient_id,
    //         // 'record_date'         => Carbon::now()->format('Y-m-d H:i:s'),
    //         'record_date'         => Carbon::now(),
    //         'topic'               => $topic_name,
    //         'notes'               => $comments,
    //         'emr_entry_completed' => null,
    //         'created_by'          => session()->get('userid'),
    //         'patient_id'          => $patient_id,
    //         'template_type'       => ''
    //     );
    //     $checkTopicExist = CallWrap::where('patient_id', $patient_id)->where('topic', $topic_name)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();

    //     if ($checkTopicExist == true) {
    //         CallWrap::where('patient_id', $patient_id)->where('topic', $topic_name)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->update($condition_data);
    //     } else {
    //         $condition_data['sequence'] = $new_sequence;
    //         CallWrap::create($condition_data);
    //     }
    //     $this->patientDataStatus($patient_id, $module_id, $component_id, $stage_id, $step_id);
    //     //     DB::commit();
    //     // } catch(\Exception $ex) {
    //     //     DB::rollBack();
    //     //     // return $ex;
    //     //     return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
    //     // }
    // }



    //**********************************************this one is not in use bcz of arry_diff instead using hiddenenablebutton boolean in above function  */
    // public function savePatientdiagnosisData_5thsept2022_backup_for_array_diff(PatientsDiagnosisRequest $request)
    // {


    //     $hiddenenablebutton = sanitizeVariable($request->hiddenenablebutton);
    //     // $hidden_id          = sanitizeVariable($request->diagnosis_id); // 4th aug 2022
    //     $hidden_id          = sanitizeVariable($request->diagnosis); // 4th aug 2022
    //     $patient_id         = sanitizeVariable($request->patient_id);
    //     $code               = sanitizeVariable($request->code);
    //     $goals              = sanitizeVariable($request->goals);
    //     $condition          = sanitizeVariable($request->diagnosis);
    //     $condition_name     = sanitizeVariable($request->condition);
    //     $support            = sanitizeVariable($request->support);
    //     $comments           = sanitizeVariable($request->comments);
    //     $symptoms           = sanitizeVariable($request->symptoms);
    //     $tasks              = sanitizeVariable($request->tasks);
    //     $tab_name           = sanitizeVariable($request->tab_name);
    //     $new_code           = sanitizeVariable($request->new_code);
    //     $start_time         = sanitizeVariable($request->start_time);
    //     $end_time           = sanitizeVariable($request->end_time);
    //     $module_id          = sanitizeVariable($request->module_id);
    //     $component_id       = sanitizeVariable($request->component_id);
    //     $stage_id           = sanitizeVariable($request->stage_id);
    //     $step_id            = sanitizeVariable($request->step_id);
    //     $billable           = sanitizeVariable($request->billable);
    //     $form_name          = sanitizeVariable($request->form_name);
    //     $createdby          = session()->get('userid');
    //     $hiddenenablebutton = sanitizeVariable($request->hiddenenablebutton);





    //     $is_update_and_review_data = 0;
    //     if ($billable == 0 || $billable == 1) {
    //         $billable = $billable;
    //     } else {
    //         $billable = 1;
    //     }
    //     // DB::beginTransaction();
    //     // try {
    //     if ($code == '0' && $code != null) {
    //         $code = $new_code;
    //     } else {
    //         $code = sanitizeVariable($request->code);
    //     }
    //     $diagnosisData  = array(
    //         'code'      => $code,
    //         'condition' => $condition_name,
    //         'goals'     => json_encode($goals),
    //         'symptoms'  => json_encode($symptoms),
    //         'tasks'     => json_encode($tasks),
    //         'support'   => $support,
    //         'comments'  => $comments,
    //         'patient_id' => $patient_id,
    //         'uid'       => $patient_id,
    //         'diagnosis' => $condition,
    //     );
    //     if ($tab_name == '1') {
    //         $diagnosisData['review'] = 1;
    //     }
    //     //record time
    //     $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name);


    //     if (!empty($code)) {
    //         $check_code  = DiagnosisCode::where('diagnosis_id', $condition)->where('code', $code)->exists();
    //         if ($check_code == false) {
    //             $create_code = array(
    //                 'diagnosis_id' => $condition,
    //                 'code' => $code,
    //                 'status' => 1
    //             );
    //             $create_code['created_by'] = session()->get('userid');
    //             $create_code['updated_by'] = session()->get('userid');
    //             $creatediagnosis = DiagnosisCode::create($create_code);
    //         }


    //         $check_exist_code  = PatientDiagnosis::where('patient_id', $patient_id)
    //             ->where('diagnosis', $condition)
    //             ->where('created_by', session()->get('userid'))
    //             ->where('code', $code)
    //             ->where('status', 1)
    //             ->whereDate('created_at', '=', Carbon::today()->toDateString())
    //             ->exists();


    //         $check_exist_diagnosis_data  = PatientDiagnosis::where('patient_id', $patient_id)
    //             ->where('diagnosis', $condition)
    //             // ->where('created_by',session()->get('userid')) 
    //             ->where('code', $code)
    //             ->where('status', 1)
    //             ->whereDate('created_at', '=', Carbon::today()->toDateString())
    //             ->get();


    //         if ($hidden_id != '' && isset($hidden_id)) {
    //             // dd("if");

    //             // 4th aug 2022 changed variable name to check
    //             $check = PatientDiagnosis::where('diagnosis', $hidden_id)->where('patient_id', $patient_id)->where('status', 1)->exists();

    //             if ($check == true) {
    //                 //this loop is for previous month update and review..                   
    //                 // dd("check if");
    //                 $diagnosisData['updated_by'] = session()->get('userid');

    //                 $check_exist_diagnosis_data_widout_currentdate  = PatientDiagnosis::where('patient_id', $patient_id)
    //                     ->where('diagnosis', $condition)
    //                     // ->where('created_by',session()->get('userid')) 
    //                     ->where('code', $code)
    //                     ->where('status', 1)
    //                     ->orderBy('created_at', 'desc')
    //                     ->skip(0)->take(1)
    //                     ->get();
    //                 // dd($check_exist_diagnosis_data_widout_currentdate);
    //                 //place this $check_exist_diagnosis_data_widout_currentdate before $update_query to compare array of symptons,goals,tasks

    //                 $it_1 = json_decode($check_exist_diagnosis_data_widout_currentdate[0]->symptoms, TRUE);
    //                 $it_2 = $symptoms;
    //                 $result_array_1 = array_diff($it_1, $it_2);

    //                 $it_3 = json_decode($check_exist_diagnosis_data_widout_currentdate[0]->goals, TRUE);
    //                 $it_4 = $goals;
    //                 $result_array_2 = array_diff($it_3, $it_4);

    //                 $it_5 = json_decode($check_exist_diagnosis_data_widout_currentdate[0]->tasks, TRUE);
    //                 $it_6 = $tasks;
    //                 $result_array_3 = array_diff($it_5, $it_6);

    //                 if ((count($result_array_1) == 0) && (count($result_array_2) == 0) && (count($result_array_3) == 0)) {
    //                     //    dd("if");
    //                     $is_update_and_review_data = 0;
    //                     $action = 'reviewed';
    //                 } else {
    //                     // dd("else");
    //                     $is_update_and_review_data = 1;
    //                     $action = 'modified';
    //                 }



    //                 $update_query = PatientDiagnosis::where('diagnosis', $hidden_id)->where('patient_id', $patient_id)
    //                     ->where('status', 1)
    //                     // ->whereDate('created_at', '=', Carbon::today()->toDateString())
    //                     ->update($diagnosisData);


    //                 $latestrecord = PatientDiagnosis::where('diagnosis', $hidden_id)
    //                     ->where('patient_id', $patient_id)
    //                     ->where('status', 1)
    //                     // ->whereDate('created_at', '=', Carbon::today()->toDateString())
    //                     ->orderBy('created_at', 'desc')
    //                     ->skip(0)->take(1)->get();


    //                 $patient_diagnosis_id = $latestrecord[0]->id;
    //                 $new_diagnosis_id = $latestrecord[0]->diagnosis;

    //                 // dd($action);
    //                 if (($action == 'reviewed') && ($is_update_and_review_data == 0)) {

    //                     // dd("if review");
    //                     $action = 'reviewed';
    //                     $c = CarePlanUpdateLogs::where('diagnosis_id', $new_diagnosis_id)
    //                         ->where('patient_diagnosis_id', $patient_diagnosis_id)
    //                         ->where('patient_id', $patient_id)
    //                         ->where('status', 1)
    //                         ->orderBy('created_at', 'desc')
    //                         ->skip(0)->take(1)->get();



    //                     if (count($c) > 0) {
    //                         $update_date = $c[0]->update_date;
    //                     } else {


    //                         $update_date =  $latestrecord[0]->updated_at;
    //                         $update_datetime_arr = explode(" ", $update_date);
    //                         $update_date_array = explode("-", $update_datetime_arr[0]);
    //                         $update_date = $update_date_array[2] . "-" . $update_date_array[0] . "-" . $update_date_array[1] . " " . $update_datetime_arr[1];
    //                     }




    //                     $review_date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();


    //                     $careplanlogsdata = array(
    //                         'patient_id' => $patient_id,
    //                         'diagnosis_id' => $new_diagnosis_id,
    //                         'patient_diagnosis_id' => $patient_diagnosis_id,
    //                         'created_by' => $createdby,
    //                         'updated_by' => $createdby,
    //                         'status' => 1
    //                     );
    //                 } else {
    //                     // dd("else modified");
    //                     $action = 'modified';
    //                     $update_date =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();; // Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();               
    //                     $review_date =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();// Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();
    //                 }

    //                 $careplanid  = $patient_diagnosis_id; // 4th aug 2022
    //                 $careplanlogsdata = array(
    //                     'patient_id' => $patient_id,
    //                     'diagnosis_id' => $new_diagnosis_id,
    //                     'patient_diagnosis_id' => $careplanid,
    //                     'created_by' => $createdby,
    //                     'updated_by' => $createdby,
    //                     'update_date' => $update_date,
    //                     'review_date' => $review_date,
    //                     'action' => $action,
    //                     'status' => 1
    //                 );
    //                 // dd($careplanlogsdata);
    //                 $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
    //                 $diag_id = $new_diagnosis_id;
    //             } else {

    //                 // dd("check else");

    //                 $diagnosisData['created_by'] = session()->get('userid');
    //                 $diagnosisData['updated_by'] = session()->get('userid');
    //                 $insert_query = PatientDiagnosis::create($diagnosisData);
    //                 $update_date  =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();//Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();
    //                 $review_date  =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();
    //                 $careplanlogsdata = array(
    //                     'patient_id' => $patient_id,
    //                     'diagnosis_id' => $insert_query->diagnosis,
    //                     'patient_diagnosis_id' => $insert_query->id,
    //                     'created_by' => $createdby,
    //                     'updated_by' => $createdby,
    //                     'update_date' => $update_date,
    //                     'review_date' => $review_date,
    //                     'action' => 'created',
    //                     'status' => 1
    //                 );
    //                 //dd($careplanlogsdata);
    //                 $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
    //                 $diag_id = $insert_query->diagnosis;
    //             }
    //         } else if ($check_exist_code == true && $hidden_id == '') {
    //             // dd(" first else if");      


    //             $diagnosisData['updated_by'] = session()->get('userid');


    //             $update_query = PatientDiagnosis::where('patient_id', $patient_id)
    //                 ->where('status', 1)
    //                 ->where('diagnosis', $condition)
    //                 ->where('code', $code)->whereDate('updated_at', '=', Carbon::today()->toDateString())
    //                 ->update($diagnosisData);


    //             $latestrecord = PatientDiagnosis::where('patient_id', $patient_id)
    //                 ->where('diagnosis', $condition)
    //                 ->where('code', $code)
    //                 ->where('status', 1)
    //                 ->whereDate('updated_at', '=', Carbon::today()->toDateString())
    //                 ->orderBy('created_at', 'desc')
    //                 ->skip(0)->take(1)->get();

    //             $patient_diagnosis_id = $latestrecord[0]->id;
    //             $new_diagnosis_id = $latestrecord[0]->diagnosis;


    //             $it_1 = json_decode($check_exist_diagnosis_data[0]->symptoms, TRUE);
    //             $it_2 = $symptoms;
    //             $result_array_1 = array_diff($it_1, $it_2);

    //             $it_3 = json_decode($check_exist_diagnosis_data[0]->goals, TRUE);
    //             $it_4 = $goals;
    //             $result_array_2 = array_diff($it_3, $it_4);

    //             $it_5 = json_decode($check_exist_diagnosis_data[0]->tasks, TRUE);
    //             $it_6 = $tasks;
    //             $result_array_3 = array_diff($it_5, $it_6);

    //             if (empty($result_array_1[0]) && empty($result_array_2[0]) && empty($result_array_3[0])) {

    //                 $is_update_and_review_data = 0;
    //                 $action = 'reviewed';
    //                 $c = CarePlanUpdateLogs::where('patient_diagnosis_id', $patient_diagnosis_id)->where('diagnosis_id', $new_diagnosis_id)
    //                     ->where('status', 1)
    //                     ->where('patient_id', $patient_id)
    //                     ->orderBy('created_at', 'desc')
    //                     ->skip(0)->take(1)->get();

    //                 if (count($c) > 0) {
    //                     $update_date = $c[0]->update_date;
    //                 } else {
    //                     $update_date =  $latestrecord[0]->updated_at;

    //                     $update_datetime_arr = explode(" ", $update_date);
    //                     $update_date_array = explode("-", $update_datetime_arr[0]);
    //                     $update_date = $update_date_array[2] . "-" . $update_date_array[0] . "-" . $update_date_array[1] . " " . $update_datetime_arr[1];
    //                 }
    //                 $review_date  =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();
    //                 $careplanlogsdata = array(
    //                     'patient_id' => $patient_id,
    //                     'patient_diagnosis_id' => $patient_diagnosis_id,
    //                     'diagnosis_id' => $new_diagnosis_id,
    //                     'created_by' => $createdby,
    //                     'updated_by' => $createdby,
    //                     'status' => 1
    //                 );
    //             } else {

    //                 $is_update_and_review_data = 1;
    //                 $action = 'modified';
    //                 $update_date =   Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();               
    //                 $review_date =   Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();
    //             }
    //             $careplanlogsdata = array(
    //                 'patient_id' => $patient_id,
    //                 'diagnosis_id' => $new_diagnosis_id,
    //                 'patient_diagnosis_id' => $patient_diagnosis_id,
    //                 'created_by' => $createdby,
    //                 'updated_by' => $createdby,
    //                 'update_date' => $update_date,
    //                 'review_date' => $review_date,
    //                 'action' => $action,
    //                 'status' => 1
    //             );


    //             //dd($careplanlogsdata);
    //             $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
    //             $diag_id = $new_diagnosis_id;
    //         } else if ($check_exist_code == true) {
    //             // dd(" second else if");


    //             $diagnosisData['updated_by'] = session()->get('userid');
    //             $update_query = PatientDiagnosis::where('patient_id', $patient_id)->where('code', $code)->where('diagnosis', $condition)->whereDate('updated_at', '=', Carbon::today()->toDateString())->update($diagnosisData);
    //             $latestrecord = PatientDiagnosis::where('patient_id', $patient_id)
    //                 ->where('code', $code)
    //                 ->where('diagnosis', $condition)
    //                 ->where('status', 1)
    //                 ->whereDate('updated_at', '=', Carbon::today()->toDateString())
    //                 ->orderBy('created_at', 'desc')
    //                 ->skip(0)->take(1)->get();

    //             $patient_diagnosis_id = $latestrecord[0]->id;
    //             $new_diagnosis_id = $latestrecord[0]->diagnosis;


    //             $it_1 = json_decode($check_exist_diagnosis_data[0]->symptoms, TRUE);
    //             $it_2 = $symptoms;
    //             $result_array_1 = array_diff($it_1, $it_2);

    //             $it_3 = json_decode($check_exist_diagnosis_data[0]->goals, TRUE);
    //             $it_4 = $goals;
    //             $result_array_2 = array_diff($it_3, $it_4);

    //             $it_5 = json_decode($check_exist_diagnosis_data[0]->tasks, TRUE);
    //             $it_6 = $tasks;
    //             $result_array_3 = array_diff($it_5, $it_6);


    //             if (empty($result_array_1[0]) && empty($result_array_2[0]) && empty($result_array_3[0])) {
    //                 $is_update_and_review_data = 0;
    //                 $action = 'reviewed';
    //                 $c = CarePlanUpdateLogs::where('diagnosis_id', $new_diagnosis_id)
    //                     ->where('patient_diagnosis_id', $patient_diagnosis_id)
    //                     ->where('patient_id', $patient_id)
    //                     ->where('status', 1)
    //                     ->orderBy('created_at', 'desc')
    //                     ->skip(0)->take(1)->get();
    //                 if (count($c) > 0) {
    //                     $update_date = $c[0]->update_date;
    //                 } else {
    //                     $update_date =  $latestrecord[0]->updated_at;

    //                     $update_datetime_arr = explode(" ", $update_date);
    //                     $update_date_array = explode("-", $update_datetime_arr[0]);
    //                     $update_date = $update_date_array[2] . "-" . $update_date_array[0] . "-" . $update_date_array[1] . " " . $update_datetime_arr[1];
    //                 }
    //                 $review_date = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();    //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now();
    //                 $careplanlogsdata = array(
    //                     'patient_id' => $patient_id,
    //                     'patient_diagnosis_id' => $patient_diagnosis_id,
    //                     'diagnosis_id' => $new_diagnosis_id,
    //                     'created_by' => $createdby,
    //                     'updated_by' => $createdby,
    //                     'status' => 1
    //                 );
    //             } else {
    //                 $is_update_and_review_data = 1;
    //                 $action = 'modified';
    //                 $update_date =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s');//Carbon::now();               
    //                 $review_date =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s');//Carbon::now();
    //             }
    //             $careplanlogsdata = array(
    //                 'patient_id' => $patient_id,
    //                 'diagnosis_id' => $new_diagnosis_id,
    //                 'patient_diagnosis_id' => $patient_diagnosis_id,
    //                 'created_by' => $createdby,
    //                 'updated_by' => $createdby,
    //                 'update_date' => $update_date,
    //                 'review_date' => $review_date,
    //                 'action' => $action,
    //                 'status' => 1
    //             );
    //             $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
    //             $diag_id = $new_diagnosis_id;
    //         } else {
    //             // dd("last else");
    //             $diagnosisData['updated_by'] = session()->get('userid');
    //             $diagnosisData['created_by'] = session()->get('userid');
    //             $insert_query = PatientDiagnosis::create($diagnosisData);
    //             $update_date  =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s');//Carbon::now();
    //             $review_date  =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), config('app.timezone'))->setTimezone(Session::get('timezone'));  //Carbon::now()->format('Y-m-d H:i:s'); //Carbon::now(); //Carbon::now()->format('Y-m-d H:i:s');//Carbon::now();
    //             $careplanlogsdata = array(
    //                 'patient_id' => $patient_id,
    //                 'patient_diagnosis_id' => $insert_query->id,
    //                 'diagnosis_id' => $insert_query->diagnosis,
    //                 'action' => 'created',
    //                 'created_by' => $createdby,
    //                 'updated_by' => $createdby,
    //                 'update_date' => $update_date,
    //                 'review_date' => $review_date,
    //                 'status' => 1
    //             );


    //             $diag_id = $insert_query->diagnosis;
    //             $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
    //         }
    //         $mydata = CarePlanUpdateLogs::where('patient_id', $patient_id)
    //             ->where('status', 1)
    //             ->orderBy('created_at', 'desc')
    //             ->skip(0)->take(1)->get();

    //         if (count($mydata) > 0) {


    //             $finaldata = array(
    //                 'patient_id' => $patient_id,
    //                 'diagnosis_id' => $mydata[0]->diagnosis_id,
    //                 'created_by' => $createdby,
    //                 'updated_by' => $createdby,
    //                 'update_date' => $mydata[0]->update_date,
    //                 'review_date' => $mydata[0]->review_date,
    //                 'status' => 1
    //             );
    //             $diag_id = $mydata[0]->diagnosis_id;
    //         }
    //         $checkexsits = PatientCareplanLastUpdateandReview::where('patient_id', $patient_id)->where('diagnosis_id', $diag_id)->where('status', 1)->exists();

    //         // dd($finaldata);    


    //         if ($checkexsits == true) {
    //             $insert_finaldata =  PatientCareplanLastUpdateandReview::where('patient_id', $patient_id)->where('diagnosis_id', $diag_id)->where('status', 1)->update($finaldata);
    //         } else {
    //             $insert_finaldata =  PatientCareplanLastUpdateandReview::create($finaldata);
    //         }
    //     }
    //     $last_sequence = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->max('sequence'); //->where('created_at', CallWrap::max('created_at'))
    //     $new_sequence = $last_sequence + 1;
    //     $topic_name = $condition_name . " specific general notes";
    //     $condition_data = array(
    //         'uid'                 => $patient_id,
    //         // 'record_date'         => Carbon::now()->format('Y-m-d H:i:s'),
    //         'record_date'         => Carbon::now(),
    //         'topic'               => $topic_name,
    //         'notes'               => $comments,
    //         'emr_entry_completed' => null,
    //         'created_by'          => session()->get('userid'),
    //         'patient_id'          => $patient_id,
    //         'template_type'       => ''
    //     );
    //     $checkTopicExist = CallWrap::where('patient_id', $patient_id)->where('topic', $topic_name)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();

    //     if ($checkTopicExist == true) {
    //         CallWrap::where('patient_id', $patient_id)->where('topic', $topic_name)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->update($condition_data);
    //     } else {
    //         $condition_data['sequence'] = $new_sequence;
    //         CallWrap::create($condition_data);
    //     }
    //     $this->patientDataStatus($patient_id, $module_id, $component_id, $stage_id, $step_id);
    //     //     DB::commit();
    //     // } catch(\Exception $ex) {
    //     //     DB::rollBack();
    //     //     // return $ex;
    //     //     return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
    //     // }
    // }

    public function savePatientdiagnosisData_before_5thjuly(PatientsDiagnosisRequest $request)
    {
        $hiddenenablebutton = sanitizeVariable($request->hiddenenablebutton);
        $hidden_id          = sanitizeVariable($request->diagnosis_id);
        $patient_id         = sanitizeVariable($request->patient_id);
        $code               = sanitizeVariable($request->code);
        $goals              = sanitizeVariable($request->goals);
        $condition          = sanitizeVariable($request->diagnosis);
        $condition_name     = sanitizeVariable($request->condition);
        $support            = sanitizeVariable($request->support);
        $comments           = sanitizeVariable($request->comments);
        $symptoms           = sanitizeVariable($request->symptoms);
        $tasks              = sanitizeVariable($request->tasks);
        $tab_name           = sanitizeVariable($request->tab_name);
        $new_code           = sanitizeVariable($request->new_code);
        $start_time         = sanitizeVariable($request->start_time);
        $end_time           = sanitizeVariable($request->end_time);
        $module_id          = sanitizeVariable($request->module_id);
        $component_id       = sanitizeVariable($request->component_id);
        $stage_id           = sanitizeVariable($request->stage_id);
        $step_id            = sanitizeVariable($request->step_id);
        $billable           = sanitizeVariable($request->billable);
        $form_name          = sanitizeVariable($request->form_name);
        $createdby          = session()->get('userid');
        $is_update_and_review_data = 0;
        if ($billable == 0 || $billable == 1) {
            $billable = $billable;
        } else {
            $billable = 1;
        }
        // DB::beginTransaction();
        // try {
        if ($code == '0' && $code != null) {
            $code = $new_code;
        } else {
            $code = sanitizeVariable($request->code);
        }
        $diagnosisData  = array(
            'code'      => $code,
            'condition' => $condition_name,
            'goals'     => json_encode($goals),
            'symptoms'  => json_encode($symptoms),
            'tasks'     => json_encode($tasks),
            'support'   => $support,
            'comments'  => $comments,
            'patient_id' => $patient_id,
            'uid'       => $patient_id,
            'diagnosis' => $condition,
        );
        if ($tab_name == '1') {
            $diagnosisData['review'] = 1;
        }
        //record time
        $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name);
        if (!empty($code)) {
            $check_code  = DiagnosisCode::where('diagnosis_id', $condition)->where('code', $code)->exists();
            if ($check_code == false) {
                $create_code = array(
                    'diagnosis_id' => $condition,
                    'code' => $code,
                    'status' => 1
                );
                $create_code['created_by'] = session()->get('userid');
                $create_code['updated_by'] = session()->get('userid');
                DiagnosisCode::create($create_code);
            }
            $check_exist_code  = PatientDiagnosis::where('patient_id', $patient_id)
                ->where('diagnosis', $condition)
                ->where('created_by', session()->get('userid'))
                ->where('code', $code)
                ->where('status', 1)
                ->whereDate('created_at', '=', Carbon::today()->toDateString())
                ->exists();
            $check_exist_diagnosis_data  = PatientDiagnosis::where('patient_id', $patient_id)
                ->where('diagnosis', $condition)
                ->where('created_by', session()->get('userid'))
                ->where('code', $code)
                ->where('status', 1)
                ->whereDate('created_at', '=', Carbon::today()->toDateString())
                ->get();
            if ($hidden_id != '' && isset($hidden_id)) {
                $check_exist_diagnosis_data = PatientDiagnosis::where('diagnosis', $hidden_id)->where('patient_id', $patient_id)->where('status', 1)->exists();
                if ($check_exist_diagnosis_data == true) {
                    $diagnosisData['updated_by'] = session()->get('userid');
                    $update_query = PatientDiagnosis::where('diagnosis', $hidden_id)->where('patient_id', $patient_id)->where('status', 1)->whereDate('created_at', '=', Carbon::today()->toDateString())->update($diagnosisData);
                    $latestrecord = PatientDiagnosis::where('diagnosis', $hidden_id)
                        ->where('patient_id', $patient_id)
                        ->where('status', 1)
                        ->whereDate('created_at', '=', Carbon::today()->toDateString())
                        ->orderBy('created_at', 'desc')
                        ->skip(0)->take(1)->get();
                    $patient_diagnosis_id = $latestrecord[0]->id;
                    $new_diagnosis_id = $latestrecord[0]->diagnosis;
                    $it_1 = json_decode($check_exist_diagnosis_data[0]->symptoms, TRUE);
                    $it_2 = $symptoms;
                    $result_array_1 = array_diff($it_1, $it_2);

                    $it_3 = json_decode($check_exist_diagnosis_data[0]->goals, TRUE);
                    $it_4 = $goals;
                    $result_array_2 = array_diff($it_3, $it_4);

                    $it_5 = json_decode($check_exist_diagnosis_data[0]->tasks, TRUE);
                    $it_6 = $tasks;
                    $result_array_3 = array_diff($it_5, $it_6);

                    if (empty($result_array_1[0]) && empty($result_array_2[0]) && empty($result_array_3[0])) {
                        $is_update_and_review_data = 0;
                        $action = 'reviewed';
                        $c = CarePlanUpdateLogs::where('diagnosis_id', $new_diagnosis_id)
                            ->where('patient_diagnosis_id', $patient_diagnosis_id)
                            ->where('patient_id', $patient_id)
                            ->where('status', 1)
                            ->orderBy('created_at', 'desc')
                            ->skip(0)->take(1)->get();
                        if (count($c) > 0) {
                            $update_date = $c[0]->update_date;
                        } else {
                            $update_date =  $latestrecord[0]->updated_at;
                        }
                        $review_date = Carbon::now();
                        $careplanlogsdata = array(
                            'patient_id' => $patient_id,
                            'diagnosis_id' => $new_diagnosis_id,
                            'patient_diagnosis_id' => $patient_diagnosis_id,
                            'created_by' => $createdby,
                            'updated_by' => $createdby,
                            'status' => 1
                        );
                    } else {
                        $is_update_and_review_data = 1;
                        $action = 'modified';
                        $update_date = Carbon::now()->format('Y-m-d H:i:s');
                        $review_date = Carbon::now()->format('Y-m-d H:i:s');
                    }
                    $careplanlogsdata = array(
                        'patient_id' => $patient_id,
                        'diagnosis_id' => $new_diagnosis_id,
                        'patient_diagnosis_id' => $careplanid,
                        'created_by' => $createdby,
                        'updated_by' => $createdby,
                        'update_date' => $update_date,
                        'review_date' => $review_date,
                        'action' => $action,
                        'status' => 1
                    );
                    $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
                    $diag_id = $new_diagnosis_id;
                } else {
                    $diagnosisData['created_by'] = session()->get('userid');
                    $diagnosisData['updated_by'] = session()->get('userid');
                    $insert_query = PatientDiagnosis::create($diagnosisData);
                    $update_date  = Carbon::now()->format('Y-m-d H:i:s');
                    $review_date  = Carbon::now()->format('Y-m-d H:i:s');
                    $careplanlogsdata = array(
                        'patient_id' => $patient_id,
                        'diagnosis_id' => $insert_query->diagnosis,
                        'patient_diagnosis_id' => $insert_query->id,
                        'created_by' => $createdby,
                        'updated_by' => $createdby,
                        'update_date' => $update_date,
                        'review_date' => $review_date,
                        'action' => 'created',
                        'status' => 1
                    );
                    $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
                    $diag_id = $insert_query->diagnosis;
                }
            } else if ($check_exist_code == true && $hidden_id == '') {
                $diagnosisData['updated_by'] = session()->get('userid');
                $update_query = PatientDiagnosis::where('patient_id', $patient_id)
                    ->where('status', 1)
                    ->where('diagnosis', $condition)
                    ->where('code', $code)->whereDate('updated_at', '=', Carbon::today()->toDateString())
                    ->update($diagnosisData);
                $latestrecord = PatientDiagnosis::where('patient_id', $patient_id)
                    ->where('diagnosis', $condition)
                    ->where('code', $code)
                    ->where('status', 1)
                    ->whereDate('updated_at', '=', Carbon::today()->toDateString())
                    ->orderBy('created_at', 'desc')
                    ->skip(0)->take(1)->get();
                $patient_diagnosis_id = $latestrecord[0]->id;
                $new_diagnosis_id = $latestrecord[0]->diagnosis;
                $it_1 = json_decode($check_exist_diagnosis_data[0]->symptoms, TRUE);
                $it_2 = $symptoms;
                $result_array_1 = array_diff($it_1, $it_2);

                $it_3 = json_decode($check_exist_diagnosis_data[0]->goals, TRUE);
                $it_4 = $goals;
                $result_array_2 = array_diff($it_3, $it_4);

                $it_5 = json_decode($check_exist_diagnosis_data[0]->tasks, TRUE);
                $it_6 = $tasks;
                $result_array_3 = array_diff($it_5, $it_6);
                if (empty($result_array_1[0]) && empty($result_array_2[0]) && empty($result_array_3[0])) {
                    $is_update_and_review_data = 0;
                    $action = 'reviewed';
                    $c = CarePlanUpdateLogs::where('patient_diagnosis_id', $patient_diagnosis_id)->where('diagnosis_id', $new_diagnosis_id)
                        ->where('status', 1)
                        ->where('patient_id', $patient_id)
                        ->orderBy('created_at', 'desc')
                        ->skip(0)->take(1)->get();

                    if (count($c) > 0) {
                        $update_date = $c[0]->update_date;
                    } else {
                        $update_date =  $latestrecord[0]->updated_at;
                    }
                    $review_date  = Carbon::now();
                    $careplanlogsdata = array(
                        'patient_id' => $patient_id,
                        'patient_diagnosis_id' => $patient_diagnosis_id,
                        'diagnosis_id' => $new_diagnosis_id,
                        'created_by' => $createdby,
                        'updated_by' => $createdby,
                        'status' => 1
                    );
                } else {
                    $is_update_and_review_data = 1;
                    $action = 'modified';
                    $update_date = Carbon::now()->format('Y-m-d H:i:s');
                    $review_date = Carbon::now()->format('Y-m-d H:i:s');
                }
                $careplanlogsdata = array(
                    'patient_id' => $patient_id,
                    'diagnosis_id' => $new_diagnosis_id,
                    'patient_diagnosis_id' => $patient_diagnosis_id,
                    'created_by' => $createdby,
                    'updated_by' => $createdby,
                    'update_date' => $update_date,
                    'review_date' => $review_date,
                    'action' => $action,
                    'status' => 1
                );
                $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
                $diag_id = $new_diagnosis_id;
            } else if ($check_exist_code == true) {
                $diagnosisData['updated_by'] = session()->get('userid');
                $update_query = PatientDiagnosis::where('patient_id', $patient_id)->where('code', $code)->where('diagnosis', $condition)->whereDate('updated_at', '=', Carbon::today()->toDateString())->update($diagnosisData);
                $latestrecord = PatientDiagnosis::where('patient_id', $patient_id)
                    ->where('code', $code)
                    ->where('diagnosis', $condition)
                    ->where('status', 1)
                    ->whereDate('updated_at', '=', Carbon::today()->toDateString())
                    ->orderBy('created_at', 'desc')
                    ->skip(0)->take(1)->get();
                $patient_diagnosis_id = $latestrecord[0]->id;
                $new_diagnosis_id = $latestrecord[0]->diagnosis;
                $it_1 = json_decode($check_exist_diagnosis_data[0]->symptoms, TRUE);
                $it_2 = $symptoms;
                $result_array_1 = array_diff($it_1, $it_2);

                $it_3 = json_decode($check_exist_diagnosis_data[0]->goals, TRUE);
                $it_4 = $goals;
                $result_array_2 = array_diff($it_3, $it_4);

                $it_5 = json_decode($check_exist_diagnosis_data[0]->tasks, TRUE);
                $it_6 = $tasks;
                $result_array_3 = array_diff($it_5, $it_6);
                if (empty($result_array_1[0]) && empty($result_array_2[0]) && empty($result_array_3[0])) {
                    $is_update_and_review_data = 0;
                    $action = 'reviewed';
                    $c = CarePlanUpdateLogs::where('diagnosis_id', $new_diagnosis_id)
                        ->where('patient_diagnosis_id', $patient_diagnosis_id)
                        ->where('patient_id', $patient_id)
                        ->where('status', 1)
                        ->orderBy('created_at', 'desc')
                        ->skip(0)->take(1)->get();
                    if (count($c) > 0) {
                        $update_date = $c[0]->update_date;
                    } else {
                        $update_date =  $latestrecord[0]->updated_at;
                    }
                    $review_date = Carbon::now();
                    $careplanlogsdata = array(
                        'patient_id' => $patient_id,
                        'patient_diagnosis_id' => $patient_diagnosis_id,
                        'diagnosis_id' => $new_diagnosis_id,
                        'created_by' => $createdby,
                        'updated_by' => $createdby,
                        'status' => 1
                    );
                } else {
                    $is_update_and_review_data = 1;
                    $action = 'modified';
                    $update_date = Carbon::now();
                    $review_date = Carbon::now();
                }
                $careplanlogsdata = array(
                    'patient_id' => $patient_id,
                    'diagnosis_id' => $new_diagnosis_id,
                    'patient_diagnosis_id' => $patient_diagnosis_id,
                    'created_by' => $createdby,
                    'updated_by' => $createdby,
                    'update_date' => $update_date,
                    'review_date' => $review_date,
                    'action' => $action,
                    'status' => 1
                );
                $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
                $diag_id = $new_diagnosis_id;
            } else {
                $diagnosisData['updated_by'] = session()->get('userid');
                $diagnosisData['created_by'] = session()->get('userid');
                $insert_query = PatientDiagnosis::create($diagnosisData);
                $update_date  = Carbon::now()->format('Y-m-d H:i:s');
                $review_date  = Carbon::now()->format('Y-m-d H:i:s');
                $careplanlogsdata = array(
                    'patient_id' => $patient_id,
                    'patient_diagnosis_id' => $insert_query->id,
                    'diagnosis_id' => $insert_query->diagnosis,
                    'action' => 'created',
                    'created_by' => $createdby,
                    'updated_by' => $createdby,
                    'update_date' => $update_date,
                    'review_date' => $review_date,
                    'status' => 1
                );
                $diag_id = $insert_query->diagnosis;
                $insert_careplanlog = CarePlanUpdateLogs::create($careplanlogsdata);
            }
            $mydata = CarePlanUpdateLogs::where('patient_id', $patient_id)
                ->where('status', 1)
                ->orderBy('created_at', 'desc')
                ->skip(0)->take(1)->get();
            if (count($mydata) > 0) {
                $finaldata = array(
                    'patient_id' => $patient_id,
                    'diagnosis_id' => $mydata[0]->diagnosis_id,
                    'created_by' => $createdby,
                    'updated_by' => $createdby,
                    'update_date' => $mydata[0]->update_date,
                    'review_date' => $mydata[0]->review_date,
                    'status' => 1
                );
                $diag_id = $mydata[0]->diagnosis_id;
            }
            $checkexsits = PatientCareplanLastUpdateandReview::where('patient_id', $patient_id)->where('diagnosis_id', $diag_id)->where('status', 1)->exists();
            if ($checkexsits == true) {
                $insert_finaldata =  PatientCareplanLastUpdateandReview::where('patient_id', $patient_id)->where('diagnosis_id', $diag_id)->where('status', 1)->update($finaldata);
            } else {
                $insert_finaldata =  PatientCareplanLastUpdateandReview::create($finaldata);
            }
        }
        $last_sequence = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->max('sequence'); //->where('created_at', CallWrap::max('created_at'))
        $new_sequence = $last_sequence + 1;
        $topic_name = $condition_name . " specific general notes";
        $condition_data = array(
            'uid'                 => $patient_id,
            'record_date'         => Carbon::now(),
            'topic'               => $topic_name,
            'notes'               => $comments,
            'emr_entry_completed' => null,
            'created_by'          => session()->get('userid'),
            'patient_id'          => $patient_id,
            'template_type'       => ''
        );
        $checkTopicExist = CallWrap::where('patient_id', $patient_id)->where('topic', $topic_name)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();

        if ($checkTopicExist == true) {
            CallWrap::where('patient_id', $patient_id)->where('topic', $topic_name)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->update($condition_data);
        } else {
            $condition_data['sequence'] = $new_sequence;
            CallWrap::create($condition_data);
        }
        $this->patientDataStatus($patient_id, $module_id, $component_id, $stage_id, $step_id);
        //     DB::commit();
        // } catch(\Exception $ex) {
        //     DB::rollBack();
        //     // return $ex;
        //     return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        // }
    }

    public function savePatientmedicationData(PatientsMedicationAddRequest $request)
    {
        $id                   = sanitizeVariable($request->id);
        $patient_id           = sanitizeVariable($request->patient_id);
        $uid                  = sanitizeVariable($request->patient_id);
        $tab                  = sanitizeVariable($request->tab);
        $med_id               = sanitizeVariable($request->med_id);
        $med_description      = sanitizeVariable($request->med_description);
        $purpose              = sanitizeVariable($request->purpose);
        $description          = isset($request->description) ? sanitizeVariable($request->description) : '';
        $strength             = sanitizeVariable($request->strength);
        $dosage               = sanitizeVariable($request->dosage);
        $frequency            = sanitizeVariable($request->frequency);
        $route                = sanitizeVariable($request->route);
        $duration             = sanitizeVariable($request->duration);
        $pharmacy_name        = sanitizeVariable($request->pharmacy_name);
        $pharmacy_phone_no    = sanitizeVariable($request->pharmacy_phone_no);
        $drug_reaction        = isset($request->drug_reaction) ? sanitizeVariable($request->drug_reaction) : '';
        $pharmacogenetic_test = isset($request->pharmacogenetic_test) ? sanitizeVariable($request->pharmacogenetic_test) : '';
        $start_time           = sanitizeVariable($request->start_time);
        $end_time             = sanitizeVariable($request->end_time);
        $module_id            = sanitizeVariable($request->module_id);
        $component_id         = sanitizeVariable($request->component_id);
        $stage_id             = sanitizeVariable($request->stage_id);
        $step_id              = sanitizeVariable($request->step_id);
        $form_name            = sanitizeVariable($request->form_name);
        $billable             = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            if (isset($med_description)) {
                $insert_med_id  = array(
                    'description' => $med_description,
                    'created_by'  => session()->get('userid')
                );
                $new_med_id     = Medication::create($insert_med_id);
            }
            if ($med_id == 'other') {
                $med_id           = $new_med_id->id;
            }
            $insert_medicationData = array(
                'patient_id'           => $patient_id,
                'uid'                  => $uid,
                'med_id'               => $med_id,
                'purpose'              => $purpose,
                'description'          => $description,
                'strength'             => $strength,
                'dosage'               => $dosage,
                'frequency'            => $frequency,
                'route'                => $route,
                'duration'             => $duration,
                'drug_reaction'        => $drug_reaction,
                'pharmacy_name'        => $pharmacy_name,
                'pharmacy_phone_no'    => $pharmacy_phone_no,
                'pharmacogenetic_test' => $pharmacogenetic_test
            );
            if ($tab == "review-medication") {
                $insert_medicationData['review'] = 1;
            }
            //record time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            $check_med_id = PatientMedication::where('patient_id', $patient_id)->where('status', 1)->where('med_id', $med_id)->whereDate('created_at', '=', Carbon::today()->toDateString())->exists();
            if ($check_med_id == true) {
                $insert_medicationData['updated_by'] = session()->get('userid');
                $update_query = PatientMedication::where('patient_id', $patient_id)->where('status', 1)->where('med_id', $med_id)->whereDate('created_at', '=', Carbon::today()->toDateString())->orderBy('id', 'desc')->update($insert_medicationData);
            } else {
                $insert_medicationData['updated_by'] = session()->get('userid');
                $insert_medicationData['created_by'] = session()->get('userid');
                $insert_query                        = PatientMedication::create($insert_medicationData);
            }
            $this->patientDataStatus($patient_id, $module_id, $component_id, $stage_id, $step_id);
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function saveHealthServices(ServicesAddRequest $request)
    {
        $section_id           = sanitizeVariable($request->service_type);
        $patient_id           = sanitizeVariable($request->patient_id);
        $health               = HealthServices::where("alias", $section_id)->get();
        $health_id            = $health[0]->id;
        $tab                  = sanitizeVariable($request->tab);
        $id                   = sanitizeVariable($request->id);
        $service_end_date     = sanitizeVariable($request->service_end_date);
        $service_start_date   = sanitizeVariable($request->service_start_date);
        $frequency            = sanitizeVariable($request->frequency);
        $duration             = sanitizeVariable($request->duration);
        $brand                = sanitizeVariable($request->brand);
        $purpose              = sanitizeVariable($request->purpose);
        $specify              = sanitizeVariable($request->specify);
        $notes                = sanitizeVariable($request->notes);
        $service_start_date   = sanitizeVariable($request->service_start_date);
        $service_start_date   = sanitizeVariable($request->service_start_date);
        $start_time           = sanitizeVariable($request->start_time);
        $end_time             = sanitizeVariable($request->end_time);
        $module_id            = sanitizeVariable($request->module_id);
        $component_id         = sanitizeVariable($request->component_id);
        $stage_id             = sanitizeVariable($request->stage_id);
        $step_id              = sanitizeVariable($request->step_id);
        $form_name            = sanitizeVariable($request->form_name);
        $billable             = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            if ($service_end_date != '') {
                $service_end_date = //date("Y-m-d H:i:s", strtotime($service_end_date));
                    $service_end_date . ' 00:00:00';
            } else {
                $service_end_date = null;
            }
            if ($service_start_date != '') {
                $service_start_date = //date("Y-m-d H:i:s", strtotime($service_start_date));
                    $service_start_date . ' 00:00:00';
            } else {
                $service_start_date = null;
            }

            $data = array(
                'patient_id'           => $patient_id,
                'hid'                  => $health_id,
                'type'                 => $request->type,
                'service_start_date'   => $service_start_date,
                'service_end_date'     => $service_end_date,
                'frequency'            => $frequency,
                'duration'             => $duration,
                'brand'                => $brand,
                'purpose'              => $purpose,
                'specify'              => $specify,
                'notes'                => $notes,
                'status'               => 1
            );
            if ($tab == "review-services") {
                $data['review'] = 1;
            }
            //record time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            $dataExist    = PatientHealthServices::where('id', $id)->where('patient_id', $patient_id)->where('hid', $health_id)->where('status', 1)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->exists();
            if ($dataExist == true && $id != '') {
                $data['updated_by'] = session()->get('userid');
                $update_query       = PatientHealthServices::where('id', $id)->where('patient_id', $patient_id)->where('hid', $health_id)->where('status', 1)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->update($data);
            } else {
                $data['updated_by'] = session()->get('userid');
                $data['created_by'] = session()->get('userid');
                $insert_query       = PatientHealthServices::create($data);
            }
            $this->patientDataStatus($patient_id, $module_id, $component_id, $stage_id, $step_id);
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            // return $ex;
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function savePatientvitalData(PatientsVitalsDataAddRequest $request)
    {
        $patient_id           = sanitizeVariable($request->patient_id);
        $height               = sanitizeVariable($request->height);
        $weight               = sanitizeVariable($request->weight);
        $bmi                  = sanitizeVariable($request->bmi);
        $bp                   = sanitizeVariable($request->bp);
        $diastolic            = sanitizeVariable($request->diastolic);
        $o2                   = sanitizeVariable($request->o2);
        $pulse_rate           = sanitizeVariable($request->pulse_rate);
        $pain_level           = sanitizeVariable($request->pain_level);
        $other_vitals         = sanitizeVariable($request->other_vitals);
        $start_time           = sanitizeVariable($request->start_time);
        $end_time             = sanitizeVariable($request->end_time);
        $module_id            = sanitizeVariable($request->module_id);
        $component_id         = sanitizeVariable($request->component_id);
        $stage_id             = sanitizeVariable($request->stage_id);
        $step_id              = sanitizeVariable($request->step_id);
        $form_name            = sanitizeVariable($request->form_name);
        $billable             = sanitizeVariable($request->billable);
        $oxygen               = sanitizeVariable($request->oxygen);
        $notes                = sanitizeVariable($request->notes);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        // dd($pain_level);
        //DB::beginTransaction();
        // try {
        $cv1 = is_numeric($height);
        $cv2 = is_numeric($weight);
        $cv3 = is_numeric($bmi);
        $cv4 = is_numeric($bp);
        $cv5 = is_numeric($diastolic);
        $cv6 = is_numeric($o2);
        $cv7 = is_numeric($pulse_rate);
        $cv8 = is_numeric($pain_level);
        $cv9 = isset($oxygen);
        // dd($cv8);

        if (($cv1 || $cv2 || $cv3 || $cv4 || $cv5 || $cv6 || $cv7 || $cv8 || $cv9) == 'true') {
            $current_date = date('Y-m-d H:i:s');
            $data   = array(
                'patient_id'    => $patient_id,
                'uid'           => $patient_id,
                'rec_date'      => $current_date,
                'height'        => $height,
                'weight'        => $weight,
                'bmi'           => $bmi,
                'bp'            => $bp,
                'diastolic'     => $diastolic,
                'o2'            => $o2,
                'pulse_rate'    => $pulse_rate,
                'pain_level'    => $pain_level,
                'created_by'    => session()->get('userid'),
                'other_vitals'  => $other_vitals,
                'oxygen'        => $oxygen,
                'notes'         => $notes
            );

            $check = PatientVitalsData::where('patient_id', $patient_id)->whereDate('created_at', '=', Carbon::today()->toDateString())->exists();

            if ($check == 'true') {
                PatientVitalsData::where('patient_id', $patient_id)->whereDate('created_at', '=', Carbon::today()->toDateString())->update($data);
            } else {
                $insert_query = PatientVitalsData::create($data);
            }

            $cw_height               = !empty(sanitizeVariable($request->height)) ? 'height:' . sanitizeVariable($request->height) . ',' : '';
            $cw_weight               = !empty(sanitizeVariable($request->weight)) ? 'weight:' . sanitizeVariable($request->weight) . ',' : '';
            $cw_bmi                  = !empty(sanitizeVariable($request->bmi)) ? 'bmi:' . sanitizeVariable($request->bmi) . ',' : '';
            $cw_bp                   = !empty(sanitizeVariable($request->bp)) ? sanitizeVariable($request->bp) : '';
            $cw_diastolic            = !empty(sanitizeVariable($request->diastolic)) ? sanitizeVariable($request->diastolic) : '';
            if ($cw_bp == '' && $cw_diastolic == '') {
                $cw_blood_pressure       =  '';
            } else {
                $cw_blood_pressure       =  'Blood Pressure :' . $cw_bp . '/' . $cw_diastolic . ',';
            }
            $cw_o2                   = !empty(sanitizeVariable($request->o2)) ? 'o2:' . sanitizeVariable($request->o2) . ',' : '';
            $cw_pulse_rate           = !empty(sanitizeVariable($request->pulse_rate)) ? 'pulse rate:' . sanitizeVariable($request->pulse_rate) . ',' : '';
            $cw_pain_level           = !empty(sanitizeVariable($request->pain_level)) ? 'pain level:' . sanitizeVariable($request->pain_level) . ',' : '';
            if (sanitizeVariable($request->oxygen == 1)) {
                $cw_oxygen = 'Room Air';
            }
            if (sanitizeVariable($request->oxygen == 0)) {
                $cw_oxygen = 'Supplemental Oxygen Notes : ' . sanitizeVariable($request->notes) . ',';
            }
            $arraynama = implode(" ", [$cw_height, $cw_weight, $cw_bmi, $cw_blood_pressure, $cw_o2, $cw_pulse_rate, $cw_pain_level, $cw_oxygen]);
            $vitals_data_array = rtrim($arraynama, ", ");
            $last_sub_sequence = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', 1)->max('sub_sequence');
            $new_sub_sequence = $last_sub_sequence + 1;
            $vitals = array(
                'uid'                 => $patient_id,
                'record_date'         => Carbon::now(),
                'topic'               => 'Vitals Data',
                'notes'               => $vitals_data_array,
                'patient_id'          => $patient_id
            );



            $CallWrap_check = CallWrap::where('patient_id', $patient_id)->where('topic', 'Vitals Data')->whereDate('created_at', '=', Carbon::today()->toDateString())->exists();
            // if($module_id=='3' && $component_id=='19'){
            if ($CallWrap_check == 'true') {
                $vitals['sequence'] = "1";
                $vitals['sub_sequence'] = $last_sub_sequence;
                $vitals['updated_by'] = session()->get('userid');
                CallWrap::where('patient_id', $patient_id)->where('topic', 'Vitals Data')->whereDate('created_at', '=', Carbon::today()->toDateString())->update($vitals);
            } else {
                $vitals['sequence'] = "1";
                $vitals['sub_sequence'] = $last_sub_sequence;
                $vitals['created_by'] = session()->get('userid');
                $vitals['updated_by'] = session()->get('userid');
                $insert_vitalsDataCallwrapup =  CallWrap::create($vitals);
            }
            // }
            //record time
            $record_time      = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            $this->patientDataStatus($patient_id, $module_id, $component_id, $stage_id, $step_id);
            return response(['form_start_time' => $form_save_time]);
        } else {
            $vitals['sequence'] = "1";
            $vitals['sub_sequence'] = $last_sub_sequence;
            $vitals['created_by'] = session()->get('userid');
            $vitals['updated_by'] = session()->get('userid');
            $insert_vitalsDataCallwrapup =  CallWrap::create($vitals);
        }
        // }
        //record time
        $record_time      = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name);
        $this->patientDataStatus($patient_id, $module_id, $component_id, $stage_id, $step_id);
        // } else {
        //     echo "false";
        // }
        //DB::commit();
        //} catch(\Exception $ex) {
        //  DB::rollBack();
        // return $ex;
        //return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 406);
        //}
    }


    public function getLabParamRange(Request $request)
    {
        $params = "";
        $lab    = sanitizeVariable($request->lab);
        if ($lab != 0) {
            $param = LabsParam::where('lab_test_id', $lab)->where('status', 1)->get();
            $i = 0;
            foreach ($param as $value) {
                $params = $params . "<div class='col-md-6 mb-3'>";
                $params = $params . "<label>" . $value->parameter . " <span class='error'>*</span></label>";
                $params = $params . "<input type='hidden' name='lab_test_id[" . $lab . "][]'  value='" . $value->lab_test_id . "'>";
                $params = $params . "<input type='hidden' name='lab_params_id[" . $lab . "][]' value='" . $value->id . "'>";
                if ($value->parameter == "COVID-19") {
                    $params = $params . "<div class='form-row'><div class='col-md-5'><select class='forms-element form-control mr-1 pl-3' name='reading[" . $lab . "][]'><option value=''>Select Reading</option><option value='positive'>Positive</option><option value='negative'>Negative</option></select><div class='invalid-feedback' id='reading-" . $lab . "-" . $i . "'></div></div>";
                } else {
                    $params = $params . "<div class='form-row'><div class='col-md-5'><select class='forms-element form-control mr-1 pl-3 labreadingclass' name='reading[" . $lab . "][]'><option value=''>Select Reading</option><option value='high'>High</option><option value='normal'>Normal</option><option value='low'>Low</option><option value='test_not_performed'>Test not performed</option></select><div class='invalid-feedback' id='reading-" . $lab . "-" . $i . "'></div></div>";
                    $params = $params . "<div class='col-md-6'><input type='text' class='forms-element form-control' name='high_val[" . $lab . "][]' value='' /><div class='invalid-feedback' id='high_val-" . $lab . "-" . $i . "'></div></div>";
                }
                $params = $params . "</div></div>";
                $i++;
            }
            $params = $params . '<div class="col-md-12 mb-3"><label>Notes:</label><textarea class="forms-element form-control" name="notes[' . $lab . ']"></textarea><div class="invalid-feedback"></div></div>';
        } else {
            $params = $params . "<input type='hidden' name='lab_test_id[" . $lab . "][]'  value='" . $lab . "'>";
            $params = $params . "<input type='hidden' name='lab_params_id[" . $lab . "][]' value='0'>";
            $params = $params . '<div class="col-md-12 mb-3"><label>Notes:</label><textarea class="forms-element form-control" name="notes[' . $lab . ']"></textarea><div class="invalid-feedback"></div></div>';
        }
        return $params;
    }

    public function savePatientLabData(PatientsLabRequest $request)
    {
        $lab                   = sanitizeVariable($request->lab);
        $lab_test_id           = sanitizeVariable($request->lab_test_id);
        $lab_test_parameter_id = sanitizeVariable($request->lab_params_id);
        $reading               = sanitizeVariable($request->reading);
        $high_val              = sanitizeVariable($request->high_val);
        $notes                 = sanitizeVariable($request->notes);
        $patient_id            = sanitizeVariable($request->patient_id);
        $uid                   = sanitizeVariable($request->uid);
        $labdate               = sanitizeVariable($request->labdate);
        $editform              = sanitizeVariable($request->editform);
        $checklabdateexist     = sanitizeVariable($request->labdateexist);
        $olddate               = sanitizeVariable($request->olddate);
        $oldlab                = sanitizeVariable($request->oldlab);
        $start_time            = sanitizeVariable($request->start_time);
        $end_time              = sanitizeVariable($request->end_time);
        $module_id             = sanitizeVariable($request->module_id);
        $component_id          = sanitizeVariable($request->component_id);
        $stage_id              = sanitizeVariable($request->stage_id);
        $step_id               = sanitizeVariable($request->step_id);
        $form_name             = sanitizeVariable($request->form_name);
        $billable              = sanitizeVariable($request->billable);
        $module_name           = sanitizeVariable($request->module_name);
        $component_name        = sanitizeVariable($request->component_name);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            foreach ($lab  as $key => $labvalue) {
                $i = 0;
                if ($editform != 'edit') {
                    if (isset($lab_test_parameter_id) && $lab_test_parameter_id != null) {
                        $LabParameter = '';
                        foreach ($lab_test_parameter_id[$labvalue] as $test_param) {
                            $lbdate = '';
                            if (!empty($labdate[$labvalue][$i])) {
                                $lab_exit = PatientLabRecs::where('patient_id', $patient_id)->where('lab_date', $labdate[$labvalue][$i])->where('lab_test_id', $labvalue)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
                                if ($lab_exit == true) {
                                    PatientLabRecs::where('patient_id', $patient_id)->where('lab_date', $labdate[$labvalue][$i])->where('lab_test_id', $labvalue)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->delete();
                                }
                            }
                            $labdata = array(
                                'patient_id'            => $patient_id,
                                'uid'                   => $uid,
                                'rec_date'              => Carbon::now(),
                                'lab_test_id'           => $lab_test_id[$labvalue][$i],
                                'lab_test_parameter_id' => $test_param,
                                'reading'               => $reading[$labvalue][$i],
                                'high_val'              => $high_val[$labvalue][$i] ?? null,
                                'notes'                 => $notes[$labvalue],
                                'lab_date'              => $labdate[$labvalue][0]
                                //'lab_date'              =>Carbon::now() 
                            );
                            $name_param = DB::table('ren_core.rcare_lab_test_param_range')->where('id', $test_param)->get();
                            if (isset($name_param[0]->parameter)) {
                                $LabParameter .= $name_param[0]->parameter . '(' . $reading[$labvalue][$i] . ')' . ' : ' . $high_val[$labvalue][$i] ?? null . ', ';
                            }
                            $labdata['updated_by'] = session()->get('userid');
                            $labdata['created_by'] = session()->get('userid');
                            $insertData = PatientLabRecs::create($labdata);
                            $i++;
                        } //end foreach;
                        //callwrap up 
                        if (($module_name == 'ccm' && $component_name == 'monthly-monitoring') || ($module_name == 'rpm' && $component_name == 'monthly-monitoring')) {
                            $name_lab = DB::table('ren_core.rcare_lab_tests')->where('id', $lab)->get();
                            $LabName = '';
                            if (isset($name_lab[0]->description)) {
                                $LabName = $name_lab[0]->description . '(' . date('m-d-Y', strtotime($labdate[$labvalue][0])) . ')';
                            } else {
                                $LabName = 'Other (' . date('m-d-Y', strtotime($labdate[$labvalue][0])) . ')';
                            }
                            $topic = 'Lab Data : ' . $LabName;
                            $notes = !empty($LabParameter) ? rtrim($LabParameter, ", ") : $notes[$labvalue];
                            $callwrapData = array(
                                'uid'                 => $patient_id,
                                'record_date'         => Carbon::now(),
                                'topic'               => $topic,
                                'notes'               => $notes,
                                'patient_id'          => $patient_id
                            );
                            $check = CallWrap::where('patient_id', $patient_id)->where('topic', $topic)->whereDate('created_at', '=', Carbon::today()->toDateString())->exists();
                            $last_sub_sequence = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', 1)->max('sub_sequence');
                            if ($check == true) {
                                $callwrapData['sequence'] = "1";
                                $callwrapData['sub_sequence'] = $last_sub_sequence;
                                $callwrapData['updated_by'] = session()->get('userid');
                                CallWrap::where('patient_id', $patient_id)->where('topic', $topic)->whereDate('created_at', '=', Carbon::today()->toDateString())->update($callwrapData);
                            } else {
                                $callwrapData['sequence'] = "1";
                                $callwrapData['sub_sequence'] = $last_sub_sequence;
                                $callwrapData['created_by'] = session()->get('userid');
                                $callwrapData['updated_by'] = session()->get('userid');
                                CallWrap::create($callwrapData);
                            }
                        } //callwrap Data
                    }
                } else {
                    $LabParameter = '';
                    $name_lab = DB::table('ren_core.rcare_lab_tests')->where('id', $lab[0])->get();
                    $LabName = '';
                    if (isset($name_lab[0]->description)) {
                        $LabName = $name_lab[0]->description . '(' . date('m-d-Y', strtotime($checklabdateexist)) . ')';
                    } else {
                        $LabName = 'Other (' . date('m-d-Y', strtotime($checklabdateexist)) . ')';
                    }
                    $topic = 'Lab Data : ' . $LabName;
                    $check_callwrap_exist = callwrap::where('patient_id', $patient_id)->where('topic', $topic)
                        ->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->exists();
                    if ($checklabdateexist == '') {
                        $lab_exit = PatientLabRecs::where('patient_id', $patient_id)->where('rec_date', $olddate)->where('lab_test_id', $oldlab)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
                        if ($lab_exit == true) {
                            PatientLabRecs::where('patient_id', $patient_id)->where('rec_date', $olddate)->where('lab_test_id', $oldlab)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->delete();
                        }
                        if ($check_callwrap_exist == true) {
                            callwrap::where('patient_id', $patient_id)->where('topic', $topic)
                                ->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->delete();
                        }
                    } else {
                        $lab_exit = PatientLabRecs::where('patient_id', $patient_id)->where('lab_date', $olddate)->where('lab_test_id', $oldlab)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
                        if ($lab_exit == true) {
                            PatientLabRecs::where('patient_id', $patient_id)->where('lab_date', $olddate)->where('lab_test_id', $oldlab)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->delete();
                        }
                        if ($check_callwrap_exist == true) {
                            callwrap::where('patient_id', $patient_id)->where('topic', $topic)
                                ->whereMonth('record_date', date('m'))->whereYear('record_date', date('Y'))->delete();
                        }
                    }
                    if ($lab_test_parameter_id == null || $lab_test_parameter_id == '' || $lab_test_parameter_id == '0') {
                        $labdata = array(
                            'patient_id'            => $patient_id,
                            'uid'                   => $uid,
                            'rec_date'              => Carbon::now(),
                            'lab_test_id'           => 0,
                            'lab_test_parameter_id' => 0,
                            'reading'               => '',
                            'high_val'              => '',
                            'notes'                 => sanitizeVariable($request->notes[0]),
                            'lab_date'              => $labdate[$labvalue][0]
                        );
                        $labdata['updated_by'] = session()->get('userid');
                        $labdata['created_by'] = session()->get('userid');
                        PatientLabRecs::create($labdata);
                        if (($module_name == 'ccm' && $component_name == 'monthly-monitoring') || ($module_name == 'rpm' && $component_name == 'monthly-monitoring')) {

                            $LabName = 'Other (' . date('m-d-Y', strtotime($labdate[$labvalue][0])) . ')';
                            $topic = 'Lab Data : ' . $LabName;
                            $notes = !empty(sanitizeVariable($request->notes[0])) ? rtrim(sanitizeVariable($request->notes[0]), ", ") : sanitizeVariable($request->notes[0]);

                            $callwrapData = array(
                                'uid'                 => $patient_id,
                                'record_date'         => Carbon::now(),
                                'topic'               => $topic,
                                'notes'               => $notes,
                                'patient_id'          => $patient_id
                            );
                            $check = CallWrap::where('patient_id', $patient_id)->where('topic', $topic)->whereDate('created_at', '=', Carbon::today()->toDateString())->exists();
                            $last_sub_sequence = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', 1)->max('sub_sequence');
                            if ($check == true) {
                                $callwrapData['sequence'] = "1";
                                $callwrapData['sub_sequence'] = $last_sub_sequence;
                                $callwrapData['updated_by'] = session()->get('userid');
                                CallWrap::where('patient_id', $patient_id)->where('topic', $topic)->whereDate('created_at', '=', Carbon::today()->toDateString())->update($callwrapData);
                            } else {
                                $callwrapData['sequence'] = "1";
                                $callwrapData['sub_sequence'] = $last_sub_sequence;
                                $callwrapData['created_by'] = session()->get('userid');
                                $callwrapData['updated_by'] = session()->get('userid');
                                CallWrap::create($callwrapData);
                            }
                        }
                    }
                    if (isset($lab_test_parameter_id) && $lab_test_parameter_id != null) {
                        foreach ($lab_test_parameter_id[$labvalue] as $test_param) {
                            $labdata = array(
                                'patient_id'            => $patient_id,
                                'uid'                   => $uid,
                                'rec_date'              => Carbon::now(),
                                'lab_test_id'           => $lab_test_id[$labvalue][$i],
                                'lab_test_parameter_id' => $test_param,
                                'reading'               => $reading[$labvalue][$i],
                                'high_val'              => $high_val[$labvalue][$i] ?? null,
                                'notes'                 => $notes[$labvalue],
                                'lab_date'              => $labdate[$labvalue][0]
                            );
                            $name_param = DB::table('ren_core.rcare_lab_test_param_range')->where('id', $test_param)->get();
                            if (isset($name_param[0]->parameter)) {
                                $LabParameter .= $name_param[0]->parameter . '(' . $reading[$labvalue][$i] . ')' . ' : ' . $high_val[$labvalue][$i] ?? null . ', ';
                            }
                            $labdata['updated_by'] = session()->get('userid');
                            $labdata['created_by'] = session()->get('userid');
                            PatientLabRecs::create($labdata);
                            $i++;
                        } //end foreach
                        //callwrap up
                        if (($module_name == 'ccm' && $component_name == 'monthly-monitoring') || ($module_name == 'rpm' && $component_name == 'monthly-monitoring')) {
                            $name_lab = DB::table('ren_core.rcare_lab_tests')->where('id', $lab)->get();
                            $LabName = '';
                            if (isset($name_lab[0]->description)) {
                                $LabName = $name_lab[0]->description . '(' . date('m-d-Y', strtotime($labdate[$labvalue][0])) . ')';
                            } else {
                                $LabName = 'Other (' . date('m-d-Y', strtotime($labdate[$labvalue][0])) . ')';
                            }
                            $topic = 'Lab Data : ' . $LabName;
                            $notes = !empty($LabParameter) ? rtrim($LabParameter, ", ") : $notes[$labvalue];

                            $callwrapData = array(
                                'uid'                 => $patient_id,
                                'record_date'         => Carbon::now(),
                                'topic'               => $topic,
                                'notes'               => $notes,
                                'patient_id'          => $patient_id
                            );
                            $check = CallWrap::where('patient_id', $patient_id)->where('topic', $topic)->whereDate('created_at', '=', Carbon::today()->toDateString())->exists();
                            $last_sub_sequence = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', 1)->max('sub_sequence');
                            if ($check == true) {
                                $callwrapData['sequence'] = "1";
                                $callwrapData['sub_sequence'] = $last_sub_sequence;
                                $callwrapData['updated_by'] = session()->get('userid');
                                CallWrap::where('patient_id', $patient_id)->where('topic', $topic)->whereDate('created_at', '=', Carbon::today()->toDateString())->update($callwrapData);
                            } else {
                                $callwrapData['sequence'] = "1";
                                $callwrapData['sub_sequence'] = $last_sub_sequence;
                                $callwrapData['created_by'] = session()->get('userid');
                                $callwrapData['updated_by'] = session()->get('userid');
                                CallWrap::create($callwrapData);
                            }
                        } //callwrap Data
                    }
                }
            } //endforeach
            //record time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            $this->patientDataStatus($patient_id, $module_id, $component_id, $stage_id, $step_id);
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return $ex;
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function savePatientImagingData(PatientsImagingRequest $request)
    {
        $imaging              = sanitizeVariable($request->imaging);
        $imaging_date         = sanitizeVariable($request->imaging_date);
        $patient_id           = sanitizeVariable($request->patient_id);
        $start_time           = sanitizeVariable($request->start_time);
        $end_time             = sanitizeVariable($request->end_time);
        $module_id            = sanitizeVariable($request->module_id);
        $component_id         = sanitizeVariable($request->component_id);
        $stage_id             = sanitizeVariable($request->stage_id);
        $step_id              = sanitizeVariable($request->step_id);
        $form_name            = sanitizeVariable($request->form_name);
        $billable             = sanitizeVariable($request->billable);
        $comment             = sanitizeVariable($request->comment);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            // $DelPatientImaging = PatientImaging::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->get(['id']);
            // PatientImaging::destroy($DelPatientImaging->toArray());
            $imaging_array_data = '';
            $last_sub_sequence = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', 1)->max('sub_sequence');
            $new_sub_sequence = $last_sub_sequence + 1;
            foreach ($imaging as $key => $values) {
                if ($imaging_date[$key] != '') {
                    $t = $imaging_date[$key] . ' 00:00:00';
                } else {
                    $t = null;
                }
                if ($values != '') {
                    $imaging_data = array(
                        'patient_id'      => $patient_id,
                        'uid'             => $patient_id,
                        'imaging_details' => $values,
                        'created_by'      => session()->get('userid'),
                        'imaging_date'    => $t,
                        'updated_by'      => session()->get('userid'),
                        'comment'        => $comment
                    );
                    PatientImaging::create($imaging_data);
                    $callwrapimaging_array = array(
                        'uid'                 => $patient_id,
                        'record_date'         => Carbon::now(),
                        'topic'               => 'Imaging Notes',
                        'notes'               => $comment,
                        'patient_id'          => $patient_id,
                        'sequence'            => "1",
                        'sub_sequence'        => $last_sub_sequence,
                        'created_by'          => session()->get('userid'),
                        'updated_by'          => session()->get('userid')
                    );
                    CallWrap::create($callwrapimaging_array);
                    $imaging_array_data .= $values . '(' . date('m-d-Y', strtotime($imaging_date[$key])) . ') , ';
                }
            } //end foreach
            //callwrapup Data
            if ($module_id == '3' && $component_id == '19') {
                $topic = 'Imaging Data';
                $imaging_array = array(
                    'uid'                 => $patient_id,
                    'record_date'         => Carbon::now(),
                    'topic'               => 'Imaging Data',
                    'notes'               => rtrim($imaging_array_data, ", "),
                    'patient_id'          => $patient_id
                );
                $check = CallWrap::where('patient_id', $patient_id)->where('topic', $topic)->whereDate('created_at', '=', Carbon::today()->toDateString())->exists();
                if ($check == true) {
                    $imaging_array['sequence'] = "1";
                    $imaging_array['sub_sequence'] = $last_sub_sequence;
                    $imaging_array['updated_by'] = session()->get('userid');
                    CallWrap::where('patient_id', $patient_id)->where('topic', $topic)->whereDate('created_at', '=', Carbon::today()->toDateString())->update($imaging_array);
                } else {
                    $imaging_array['sequence'] = "1";
                    $imaging_array['sub_sequence'] = $last_sub_sequence;
                    $imaging_array['created_by'] = session()->get('userid');
                    $imaging_array['updated_by'] = session()->get('userid');
                    CallWrap::create($imaging_array);
                }
            } //end call wrapup
            //record time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            $this->patientDataStatus($patient_id, $module_id, $component_id, $stage_id, $step_id);
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function savePatientHealthData(PatientsHealthDataRequest $request)
    {
        $other_vitals         = sanitizeVariable($request->health_data);
        $health_date          = sanitizeVariable($request->health_date);
        $patient_id           = sanitizeVariable($request->patient_id);
        $start_time           = sanitizeVariable($request->start_time);
        $end_time             = sanitizeVariable($request->end_time);
        $module_id            = sanitizeVariable($request->module_id);
        $component_id         = sanitizeVariable($request->component_id);
        $stage_id             = sanitizeVariable($request->stage_id);
        $step_id              = sanitizeVariable($request->step_id);
        $form_name            = sanitizeVariable($request->form_name);
        $billable             = sanitizeVariable($request->billable);
        $comment             = sanitizeVariable($request->comment);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            // $DelPatientHealthdata = PatientHealthData::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->get(['id']);
            // PatientHealthData::destroy($DelPatientHealthdata->toArray());
            $health_data_array = '';
            foreach ($other_vitals as $key => $values) {
                if ($health_date[$key] != '') {
                    $t = $health_date[$key] . ' 00:00:00';
                } else {
                    $t = null;
                }
                if ($values != '') {
                    $health_data = array(
                        'patient_id'      => $patient_id,
                        'health_data'     => $values,
                        'created_by'      => session()->get('userid'),
                        'updated_by'      => session()->get('userid'),
                        'health_date'     => $t,
                        'comment'         => $comment
                    );
                    PatientHealthData::create($health_data);
                    $health_data_array .= $values . '(' . date('m-d-Y', strtotime($health_date[$key])) . ') , ';
                }
            } //end foreach
            //callwrapup Data
            if ($module_id == '3' && $component_id == '19') {
                $last_sub_sequence = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', 1)->max('sub_sequence');
                $new_sub_sequence = $last_sub_sequence + 1;
                $topic = 'Health Data';
                $healthdata_array = array(
                    'uid'                 => $patient_id,
                    'record_date'         => Carbon::now(),
                    'topic'               => 'Health Data',
                    'notes'               => rtrim($health_data_array, ", "),
                    'patient_id'          => $patient_id
                );
                $check = CallWrap::where('patient_id', $patient_id)->where('topic', $topic)->whereDate('created_at', '=', Carbon::today()->toDateString())->exists();
                if ($check == true) {
                    $healthdata_array['sequence'] = "1";
                    $healthdata_array['sub_sequence'] = $last_sub_sequence;
                    $healthdata_array['updated_by'] = session()->get('userid');
                    CallWrap::where('patient_id', $patient_id)->where('topic', $topic)->whereDate('created_at', '=', Carbon::today()->toDateString())->update($healthdata_array);
                } else {
                    $healthdata_array['sequence'] = "1";
                    $healthdata_array['sub_sequence'] = $last_sub_sequence;
                    $healthdata_array['created_by'] = session()->get('userid');
                    $healthdata_array['updated_by'] = session()->get('userid');
                    CallWrap::create($healthdata_array);
                }
            } //end call wrapup
            //record time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            $this->patientDataStatus($patient_id, $module_id, $component_id, $stage_id, $step_id);
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function getHealthData(Request $request)
    {
        $patientId = sanitizeVariable($request->route('patientid'));
        $dateS = Carbon::now()->startOfMonth()->subMonth(6);
        $dateE = Carbon::now()->endOfMonth();
        $configTZ = config('app.timezone');
        $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        /*$data = PatientVitalsData::where('patient_id',$patientId)->whereNotNull('rec_date')->where('status',1)
                            ->whereBetween('created_at', [$dateS, $dateE])->orderby('id','desc')->get();*/
        $qry = "select distinct health_data, to_char( max(updated_at) at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY HH24:MI:SS') as updated_at, health_date
            from patients.patient_health_data
            where  patient_id =" . $patientId . "
            and created_at::timestamp between '" . $dateS . "' and '" . $dateE . "' 
            group by health_data,health_date order by updated_at desc";
        $data = DB::select($qry);
        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function saveTravelData(PatientsTravelAddRequest $request)
    {
        $id                   = sanitizeVariable($request->id);
        $patient_id           = sanitizeVariable($request->patient_id);
        $uid                  = sanitizeVariable($request->uid);
        $location             = sanitizeVariable($request->location);
        $travel_status        = sanitizeVariable($request->travel_status);
        $travel_type          = sanitizeVariable($request->travel_type);
        $frequency            = sanitizeVariable($request->frequency);
        $with_whom            = sanitizeVariable($request->with_whom);
        $upcoming_tips        = sanitizeVariable($request->upcoming_tips);
        $notes                = sanitizeVariable($request->notes);
        $start_time           = sanitizeVariable($request->start_time);
        $end_time             = sanitizeVariable($request->end_time);
        $module_id            = sanitizeVariable($request->module_id);
        $component_id         = sanitizeVariable($request->component_id);
        $stage_id             = sanitizeVariable($request->stage_id);
        $step_id              = sanitizeVariable($request->step_id);
        $form_name            = sanitizeVariable($request->form_name);
        $billable             = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            foreach ($location as $key => $value) {
                $data   = array(
                    'patient_id'    => $patient_id,
                    'uid'           => $uid,
                    'travel_status' => $travel_status,
                    'location'      => $location[$key],
                    'travel_type'   => $travel_type[$key],
                    'frequency'     => $frequency[$key],
                    'with_whom'     => $with_whom[$key],
                    'upcoming_tips' => $upcoming_tips[$key],
                    'notes'         => $notes[$key],
                );
                $dataExist = PatientTravel::where('id', $id)->exists();
                if ($dataExist == true) {
                    $data['updated_by'] = session()->get('userid');
                    $update_query       = PatientTravel::where('patient_id', $patient_id)->where('id', $id)->orderBy('id', 'desc')->first()->update($data);
                } else {
                    $data['updated_by'] = session()->get('userid');
                    $data['created_by'] = session()->get('userid');
                    $insert_query = PatientTravel::create($data);
                    if ($data['travel_status'] == 1) {
                        $delete = PatientTravel::where('patient_id', $patient_id)->where('travel_status', 0)->delete();
                    }
                }
            } // end Foreach 
            //record time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function savePetData(PatientsPetAddRequest $request)
    {
        $id                   = sanitizeVariable($request->id);
        $uid                  = sanitizeVariable($request->uid);
        $patient_id           = sanitizeVariable($request->patient_id);
        $pet_name             = sanitizeVariable($request->pet_name);
        $pet_type             = sanitizeVariable($request->pet_type);
        $notes                = sanitizeVariable($request->notes);
        $pet_status           = sanitizeVariable($request->pet_status);
        $start_time           = sanitizeVariable($request->start_time);
        $end_time             = sanitizeVariable($request->end_time);
        $module_id            = sanitizeVariable($request->module_id);
        $component_id         = sanitizeVariable($request->component_id);
        $stage_id             = sanitizeVariable($request->stage_id);
        $step_id              = sanitizeVariable($request->step_id);
        $form_name            = sanitizeVariable($request->form_name);
        $billable             = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            foreach ($pet_name as $key => $value) {
                $data   = array(
                    'patient_id'    => $patient_id,
                    'uid'           => $uid,
                    'pet_status'    => $pet_status,
                    'pet_name'      => $pet_name[$key],
                    'pet_type'      => $pet_type[$key],
                    'notes'         => $notes[$key],
                );
                $dataExist = PatientPet::where('id', $id)->exists();
                if ($dataExist == true) {
                    $data['updated_by'] = session()->get('userid');
                    $update_query       = PatientPet::where('patient_id', $patient_id)->where('id', $id)->orderBy('id', 'desc')->first()->update($data);
                } else {
                    $data['updated_by'] = session()->get('userid');
                    $data['created_by'] = session()->get('userid');
                    $insert_query       = PatientPet::create($data);
                    if ($data['pet_status'] == 1) {
                        $delete         = PatientPet::where('patient_id', $patient_id)->where('pet_status', 0)->delete();
                    }
                }
            } //endforeach
            //record time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function saveHobbiesData(PatientsHobbiesAddRequest $request)
    {
        $id                   = sanitizeVariable($request->id);
        $uid                  = sanitizeVariable($request->uid);
        $patient_id           = sanitizeVariable($request->patient_id);
        $hobbies_name         = sanitizeVariable($request->hobbies_name);
        $hobbies_status       = sanitizeVariable($request->hobbies_status);
        $location             = sanitizeVariable($request->location);
        $frequency            = sanitizeVariable($request->frequency);
        $with_whom            = sanitizeVariable($request->with_whom);
        $notes                = sanitizeVariable($request->notes);
        $start_time           = sanitizeVariable($request->start_time);
        $end_time             = sanitizeVariable($request->end_time);
        $module_id            = sanitizeVariable($request->module_id);
        $component_id         = sanitizeVariable($request->component_id);
        $stage_id             = sanitizeVariable($request->stage_id);
        $step_id              = sanitizeVariable($request->step_id);
        $form_name            = sanitizeVariable($request->form_name);
        $billable             = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            foreach ($hobbies_name as $key => $value) {
                $data   = array(
                    'patient_id'    => $patient_id,
                    'uid'           => $uid,
                    'hobbies_status' => $hobbies_status,
                    'hobbies_name'  => $hobbies_name[$key],
                    'location'      => $location[$key],
                    'frequency'     => $frequency[$key],
                    'with_whom'     => $with_whom[$key],
                    'notes'         => $notes[$key],
                );
                $dataExist = PatientHobbies::where('id', $id)->exists();
                if ($dataExist == true) {
                    $data['updated_by'] = session()->get('userid');
                    $update_query       = PatientHobbies::where('patient_id', $patient_id)->where('id', $id)->orderBy('id', 'desc')->first()->update($data);
                } else {
                    $data['updated_by'] = session()->get('userid');
                    $data['created_by'] = session()->get('userid');
                    $insert_query       = PatientHobbies::create($data);
                    if ($data['hobbies_status'] == 1) {
                        $delete         = PatientHobbies::where('patient_id', $patient_id)->where('hobbies_status', 0)->delete();
                    }
                }
            } //endforeach
            //record time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function  SaveCallClose(CallCloseAddRequest $request)
    {
        $uid                   = sanitizeVariable($request->uid);
        $preffered_day         = sanitizeVariable($request->preffered_day);
        $call_next_month_notes = sanitizeVariable($request->call_next_month_notes);
        $start_time            = sanitizeVariable($request->start_time);
        $end_time              = sanitizeVariable($request->end_time);
        $module_id             = sanitizeVariable($request->module_id);
        $component_id          = sanitizeVariable($request->component_id);
        $stage_id              = sanitizeVariable($request->stage_id);
        $step_id               = sanitizeVariable($request->step_id);
        $billable              = sanitizeVariable($request->billable);
        DB::beginTransaction();
        try {
            $data = CallClose::where('patient_id', $id)->exists();
            if ($data == 1) {
                $update = array(
                    'patient_id'            => $uid,
                    'uid'                   => $uid,
                    'rec_date'              => Carbon::now(),
                    'preffered_day'         => $preffered_day,
                    'call_next_month_notes' => $call_next_month_notes,
                    'updated_by'            => session()->get('userid'),
                );
                $update_query = CallClose::where('uid', $id)->update($update);
            } else {
                $call = array(
                    'uid'                   => $uid,
                    'patient_id'            => $id,
                    'rec_date'              => Carbon::now(),
                    'preffered_day'         => $preffered_day,
                    'call_next_month_notes' => $call_next_month_notes,
                    'created_by'            => session()->get('userid'),
                    'updated_by'            => session()->get('userid'),
                );
                $insert = CallClose::create($call);
            }
            //record time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $uid, $module_id, $component_id, $stage_id, $billable, $uid);
            return response()->json(['success' => "Added successfully."]);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }

    public function SaveCallWrapUp(Request $request)
    {
        $uid                  = sanitizeVariable($request->uid);
        $patient_id           = sanitizeVariable($request->uid);
        $topics               = 'care plan developement notes';
        $notes                = sanitizeVariable($request->notes);
        $emr_entry_completed  = 0;
        $sequence             = 0;
        $start_time           = sanitizeVariable($request->start_time);
        $end_time             = sanitizeVariable($request->end_time);
        $module_id            = sanitizeVariable($request->module_id);
        $component_id         = sanitizeVariable($request->component_id);
        $stage_id             = sanitizeVariable($request->stage_id);
        $step_id              = sanitizeVariable($request->step_id);
        $form_name            = sanitizeVariable($request->form_name);
        $billable             = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        $last_sub_sequence   = CallWrap::where('patient_id', $patient_id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('sequence', $sequence)->max('sub_sequence');
        $new_sub_sequence    = $last_sub_sequence + 1;
        DB::beginTransaction();
        try {
            $callwrapdata = array(
                'uid'                 => $uid,
                'record_date'         => date('Y-m-d', strtotime('+1 month')), //Carbon::now(),
                'topic'               => $topics,
                'notes'               => $notes,
                'sequence'            => $sequence,
                'sub_sequence'        => $new_sub_sequence,
                'emr_entry_completed' => $emr_entry_completed,
                'created_by'          => session()->get('userid'),
                'patient_id'          => $patient_id
            );
            if (!empty($notes)) {
                CallWrap::create($callwrapdata);
            }
            //record time
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $uid, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name, $form_start_time, $form_save_time);
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }






    public function finalizeCpd(Request $request)
    {
        $uid = sanitizeVariable($request->id);
        $finalize = sanitizeVariable($request->finalize);
        $status = array('finalize_cpd' => $finalize, 'finalize_date' => Carbon::now(), 'updated_by' => session()->get('userid'));
        PatientServices::where('patient_id', $uid)->update($status);
        if (PatientServices::where('patient_id', $uid)->where('status', 1)->where('module_id', 2)->exists()) {
            $enroll_in_rpm   = 1;
        } else {
            $enroll_in_rpm   = 0;
        }
        if ($finalize == 0 && sanitizeVariable($request->billabel) == 0 && $enroll_in_rpm == 0) {
            return 0;
        } else {
            return 1;
        }
    }

    public function SaveFiestReviewData(Request $request)
    {
        $uid                          = sanitizeVariable($request->uid);
        $independent_living_at_home   = sanitizeVariable($request->independent_living_at_home);
        $at_home_with_assistance      = sanitizeVariable($request->at_home_with_assistance);
        $assisted_living_group_living = sanitizeVariable($request->assisted_living_group_living);
        $other                        = sanitizeVariable($request->other);
        $description                  = sanitizeVariable($request->description);
        $start_time                   = sanitizeVariable($request->start_time);
        $end_time                     = sanitizeVariable($request->end_time);
        $module_id                    = sanitizeVariable($request->module_id);
        $component_id                 = sanitizeVariable($request->component_id);
        $stage_id                     = sanitizeVariable($request->stage_id);
        $step_id                      = sanitizeVariable($request->step_id);
        $form_name                    = sanitizeVariable($request->form_name);
        $billable                     = sanitizeVariable($request->billable);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        DB::beginTransaction();
        try {
            $data = array(
                'patient_id'                   => $uid,
                'independent_living_at_home'   => $independent_living_at_home,
                'at_home_with_assistance'      => $at_home_with_assistance,
                'assisted_living_group_living' => $assisted_living_group_living,
                'other'                        => $other,
                'description'                  => $description,
            );
            if (PatientFirstReview::where('patient_id', $uid)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->exists()) {
                PatientFirstReview::where('patient_id', $uid)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->update($data);
            } else {
                PatientFirstReview::create($data);
            }
            $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $uid, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name, $form_start_time, $form_save_time);
            DB::commit();
            return response(['form_start_time' => $form_save_time]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response(['message' => 'Something went wrong, please try again or contact administrator.!!'], 406);
        }
    }
}
