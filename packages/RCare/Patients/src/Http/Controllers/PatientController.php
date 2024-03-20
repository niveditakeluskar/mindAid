<?php

namespace RCare\Patients\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RCare\Rpm\Models\Devices;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Org\OrgPackages\Modules\src\Models\ModuleComponents;
use RCare\TaskManagement\Models\UserPatients;
use RCare\Ccm\Models\CallPreparation;
use RCare\Org\OrgPackages\StageCodes\src\Models\StageCode;
use RCare\Org\OrgPackages\QCTemplates\src\Models\ContentTemplate;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate;
use RCare\Ccm\Models\QuestionnaireTemplatesUsageHistory;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use RCare\Org\OrgPackages\Users\src\Models\OrgUserRole;
use RCare\Org\OrgPackages\Roles\src\Models\Roles;
use RCare\Org\OrgPackages\Practices\src\Models\Practices;
use RCare\Org\OrgPackages\Practices\src\Models\PracticesGroup;
use RCare\Org\OrgPackages\Providers\src\Models\Providers;
use RCare\Patients\Models\PatientFinNumber;
use RCare\Rpm\Models\PatientTimeRecordPatients;
use RCare\Org\OrgPackages\Threshold\src\Models\GroupThreshold;
use RCare\Ccm\Models\PatientCareplanCarertool;
use RCare\Ccm\Models\PatientConditionCaretool;
use RCare\TaskManagement\Models\ToDoList;
use RCare\Org\OrgPackages\Partner\src\Models\PartnerDevices;
use RCare\Ccm\Models\CallStatus;
use RCare\Patients\Models\PatientDevices;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientDiagnosis;
use RCare\Patients\Models\PatientMedication;
use RCare\Patients\Models\PatientOrders;
use RCare\Patients\Models\PatientPartResearchStudy;
use RCare\Patients\Models\PatientPersonalNotes;
use RCare\Patients\Models\PatientThreshold;
use RCare\Patients\Models\PatientDemographics;
use RCare\Patients\Models\PatientAddress;
use RCare\Patients\Models\PatientFamily;
use RCare\Patients\Models\PatientPoa;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientInsurance;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\PatientContactTime;
use RCare\Patients\Models\PatientTimeRecords;
use RCare\Patients\Models\PatientIdEditHistory;
use RCare\Patients\Models\PatientActiveDeactiveHistory;
use RCare\Patients\Http\Requests\PatientPersonalNotesAddRequest;
use RCare\Patients\Http\Requests\PatientResearchstudyAddRequest;
//use RCare\Patients\Http\Requests\PatientThresholdAddRequest;
use RCare\Org\OrgPackages\Practices\src\Models\PracticeThreshold;
use RCare\Org\OrgPackages\Practices\src\Models\OrgThreshold;
use RCare\Patients\Http\Requests\PatientAddRequest;
use RCare\Patients\Http\Requests\PatientEditRequest;
use RCare\Patients\Http\Requests\PatientProfileImage;
use RCare\Patients\Http\Requests\ActiveDeactiveAddRequest;
use RCare\Patients\Http\Requests\RecaptchaRequest;
use RCare\Patients\Http\Requests\MasterDevicesRequest;
use RCare\Patients\Http\Requests\FinNumberRequest;
use RCare\Org\OrgPackages\Protocol\src\Models\RPMProtocol;
use RCare\API\Http\Controllers\ECGAPIController;
use RCare\API\Models\ApiException;
use RCare\Org\OrgPackages\Practices\src\Models\Document;
use RCare\Patients\Models\PatientCareplanLastUpdateandReview;
use Session;
use Hash;
use Validator, Redirect, Response;
use DataTables;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use File, DB;
use Illuminate\Support\Facades\Cache;


class PatientController extends Controller
{


    //all functions are cleaned by ashvini (15dec2020) 
    public function savepatientfinnumber(FinNumberRequest $request)
    {

        $id                 = sanitizeVariable($request->uid);
        $rowid              = sanitizeVariable($request->idd);
        $patient_id         = sanitizeVariable($request->patient_id);
        $module_id          = sanitizeVariable($request->module_id);
        $currentMonth       = date('m');
        $currentYear        = date('Y');
        $fin_number        = sanitizeVariable($request->fin_number);
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $component_id = sanitizeVariable($request->component_id);
        $stage_id     = sanitizeVariable($request->stage_id);
        $billable     = 1;
        $form_name    = sanitizeVariable($request->form_name);
        $step_id      = 0;
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        $check_fn = Patients::where('id', $patient_id)->exists();

        $data = array(
            'fin_number'  => $fin_number
        );

        $patientfin = array(
            'patient_id'    => $patient_id,
            'status'        => '1',
            'fin_number'    => $fin_number
        );

        if ($check_fn == true) {
            $data['updated_by'] = session()->get('userid');
            $update = Patients::where('id', $patient_id)->update($data);
            // ->where('uid', $id)
        }

        $check_exist_for_month = PatientFinNumber::where('patient_id', $patient_id)->whereMonth('updated_at', $currentMonth)
            ->whereYear('updated_at', $currentYear)->exists();
        if ($check_exist_for_month == true) {
            $patientfin['updated_at'] = Carbon::now();
            $patientfin['updated_by'] = session()->get('userid');
            $update_query = PatientFinNumber::where('patient_id', $patient_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))
                ->orderBy('id', 'desc')->first()->update($patientfin);
        } else {
            $patientfin['created_at'] = Carbon::now();
            $patientfin['created_by'] = session()->get('userid');
            $insert_query = PatientFinNumber::create($patientfin);
        }
        $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
        return response(['form_start_time' => $form_save_time]);
    }

    public function fetchVeteranServiceData($patientId)
    {
        // return getVTreeData($patientId);
        $id = $patientId;
        $module_id    = getPageModuleName();
        $SID = getFormStageId(8, 9, 'Veteran');
        $patient_demographics = PatientDemographics::where('patient_id', $id)->get();
        // dd($patient_demographics);
        if (isset($patient_demographics[0]->template)) {
            $patient_questionnaire1 = json_decode($patient_demographics[0]->template, true);
            if (isset($patient_questionnaire1["template_id"])) {
                $veteranQuestion = QuestionnaireTemplate::where('id', $patient_questionnaire1["template_id"])->where('stage_id', $SID)->where('template_type_id', 5)->latest()->first();
            } else {
                $veteranQuestion = QuestionnaireTemplate::where('status', 1)->where('stage_id', $SID)->where('template_type_id', 5)->latest()->first();
            }
        } else {
            $veteranQuestion = QuestionnaireTemplate::where('status', 1)->where('stage_id', $SID)->where('template_type_id', 5)->latest()->first();
        }

        // dd($veteranQuestion);

        if ($veteranQuestion != null) {
            $queData = json_decode($veteranQuestion['question']);
            if (isset($queData->question->q)) {
                $questionnaire = $queData->question->q;

                if (isset($patient_demographics[0]->template)) {
                    $patient_questionnaire = json_decode($patient_demographics[0]->template, true);
                    foreach ($questionnaire as $value) {
                        $questionTitle = trim($value->questionTitle);
                        $questionExist = 0;
                        if ($patient_questionnaire != '' || $patient_questionnaire != null) {
                            if (array_key_exists($questionTitle, $patient_questionnaire)) {
                                $questionExist = 1;
                            }
                        }
                        $que_val = trim(preg_replace('/\s+/', ' ', $value->questionTitle));
                        echo '<label>' . $value->questionTitle . '</label> : ';
                        echo '<b>';
                        if ($questionExist == 1) {
                            if (property_exists($value, 'label') && $value->answerFormat == '4') {
                                foreach ($value->label as $labels) {
                                    if (isset($patient_questionnaire[$questionTitle][str_replace(' ', '_', $labels)])) {
                                        if ($patient_questionnaire[$questionTitle][str_replace(' ', '_', $labels)] == 1) {
                                            echo $labels;
                                        }
                                    }
                                }
                            } else {
                                echo $patient_questionnaire[$questionTitle];
                            }
                        }

                        echo '</b>';
                        echo '<br>';
                    }
                } else {
                    return '';
                }
            }
        }
    }

    public function populateFinNumberData($id)
    {
        $id = sanitizeVariable($id);
        $data = DB::select("select fin_number from patients.patient where id = '" . $id . "'");
        $result['fin_number_form'] = $data;
        return $result;
    }

    public function getPatientRegistrationForm()
    {
        $module_id    = getPageModuleName();
        $SID = getFormStageId(getPageModuleName(),     9, 'Veteran');
        $veteranQuestion = QuestionnaireTemplate::where('module_id', $module_id)->where('status', 1)->where('stage_id', $SID)->where('template_type_id', 5)->latest()->first();

        $services = Module::where('patients_service', 1)->where('status', 1)->get();
        return view('Patients::patient.patient-registration', compact('services', 'veteranQuestion'));
    }

    public function fetchSystemThreshold(Request $request)
    {
        $uid       = sanitizeVariable($request->route('patient_id'));
        $module_id = sanitizeVariable($request->route('module_id'));
        //dd($uid);
        $patient_providers = PatientProvider::where('patient_id', $uid)
            // ->with('practice')->with('provider')->with('users')
            ->where('provider_type_id', 1)->where('is_active', 1)->orderby('id', 'desc')
            ->first();
        // dd($patient_providers);
        $practice_id = empty($patient_providers->practice_id) ? '' : $patient_providers->practice_id;

        if (!empty($practice_id)) {
            $practices = Practices::where('id', $practice_id)->where('is_active', 1)->orderby('id', 'desc')
                ->first();
            $practice_group_id = empty($practices) ? '' : $practices->practice_group;
        } else {
            $practice_group_id = '';
        }

        if (!empty($practice_id)) {   //dd("hii"); 
            $practice_threshold_exist = PracticeThreshold::where('practice_id', $practice_id)->orderby('id', 'desc')->limit(1)->first();
        } else if (!empty($practice_group_id)) {
            $org_threshold_exist = org_threshold::where('org_id', $practice_group_id)->orderby('id', 'desc')->limit(1)->first();
        }
        // dd($practice_threshold_exist);
        if (!empty($practice_threshold_exist)) {
            $threshold = $practice_threshold_exist;
            $heading = "Practice Threshold";
        } else if (!empty($org_threshold_exist)) {
            $threshold = $org_threshold_exist;
            $heading = "Org Threshold";
        } else {
            $admin_threshold_exist = GroupThreshold::orderby('id', 'desc')->limit(1)->first();
            $threshold = $admin_threshold_exist;
            $heading = "Renova Threshold";
        }
        // dd($threshold);
        return [
            'heading' => $heading,
            'threshold' => $threshold,
        ];
        // return response()->json([$threshold, $heading]);
    }

    public function getOrgName($id)
    {
        $pid = Practices::where('id', $id)->get();
        if (isset($pid[0]->practice_group)) {
            $orgname = PracticesGroup::where('id', $pid[0]->practice_group)->get();
            // print_r($orgname[0]->practice_name);
            echo  $orgname[0]->practice_name;
        } else {
            echo "Empty";
        }
    }

    public function fetchPatientModuleStatus(Request $request)
    {
        $patient_id = sanitizeVariable($request->route('patient_id'));
        $module_id = sanitizeVariable($request->route('module_id'));
        $patientService = PatientServices::where('patient_id', $patient_id)->where('module_id', $module_id)->get();
        return $patientService;
    }

    // public function cmassignpatient(Request $request)
    // { //dd("working");
    //     $login_user = Session::get('userid');
    //     $configTZ   = config('app.timezone');
    //     $userTZ     = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
    //     $patient_id = sanitizeVariable($request->route('patient'));
    //     $practice_id = sanitizeVariable($request->route('practice'));

    //     // $data = "select distinct p.id,p.fname ,p.lname ,p.dob ,p2.name as practice, usr.id,usr.f_name ,usr.l_name, m.id ,m.module  from patients.patient p
    //     // inner join task_management.user_patients up on up.patient_id =p.id and up.status=1
    //     // inner join ren_core.users usr on usr.id=up.user_id 
    //     // LEFT JOIN patients.patient_providers pp ON pp.patient_id = p.id AND pp.is_active = 1 AND pp.provider_type_id = 1 
    //     // LEFT JOIN ren_core.practices p2 ON p2.id = pp.practice_id
    //     // inner join patients.patient_services ps on ps.patient_id = p.id and  ps.status in (0,1)
    //     // inner join ren_core.modules as m on m.id = ps.module_id  
    //     // where usr.id = $login_user";

    //     //for rpm enrolled patient link 
    //     $query = DB::table('patients.patient as p')
    //     ->select('p.id', 'p.fname', 'p.lname', 'p.dob', 'p2.name as practice', 'usr.id as user_id',
    //         'usr.f_name as user_fname', 'usr.l_name as user_lname', 'm.id as module_id', 'm.module')
    //     ->leftJoin('task_management.user_patients as up', function ($join) {
    //         $join->on('up.patient_id', '=', 'p.id')->where('up.status', '=', 1);
    //     })
    //     ->leftJoin('ren_core.users as usr', 'usr.id', '=', 'up.user_id')
    //     ->leftJoin('patients.patient_providers as pp', function ($join) {
    //         $join->on('pp.patient_id', '=', 'p.id')
    //             ->where('pp.is_active', '=', 1)
    //             ->where('pp.provider_type_id', '=', 1);
    //     })
    //     ->leftJoin('ren_core.practices as p2', 'p2.id', '=', 'pp.practice_id')
    //     ->leftJoin('patients.patient_services as ps', function ($join) {
    //         $join->on('ps.patient_id', '=', 'p.id')->whereIn('ps.status', [0, 1]);
    //     })
    //     ->join('ren_core.modules as m', 'm.id', '=', 'ps.module_id')
    //     ->where('usr.id', '=', $login_user);

    //     if ($practice_id && $practice_id != 'null' && $practice_id != 0) {
    //         $query->where('pp.practice_id', '=', $practice_id);
    //     }

    //     if ($patient_id && $patient_id != 'null' && $patient_id != 0) {
    //         $query->where('p.id', '=', $patient_id);
    //     }

    //     $query = $query->get();

    //     return view('Patients::patient.cm-assigned-patient-right', compact('query'));
    // }


    public function fetchPatientModule(Request $request)
    {
        $patient_id = sanitizeVariable($request->route('patient_id'));
        $patientService = PatientServices::with('module')->where('patient_id', $patient_id)->distinct()->get() //->pluck('module_id')
            ->toArray();
        //PatientServices::with('module')->where('patient_id',$patient_id)->select('module_id,module.module')->get()toArray();  
        return $patientService;
    }


    public function fetchPatientDetails(Request $request)
    {
        $uid       = sanitizeVariable($request->route('patient_id'));
        $module_id = sanitizeVariable($request->route('module_id'));
        $patient =  Patients::with('patientServices', 'patientServices.module')->where('id', $uid)->get();
        $patient_services = PatientServices::with('module')->where('patient_id', $uid)->distinct()->get() //->pluck('module_id')
            ->toArray();
        $age = empty($patient[0]->dob) ? '' : age($patient[0]->dob);
        $patient_demographics = PatientDemographics::where('patient_id', $uid)->latest()->first();
        $gender = isset($patient_demographics) ? $patient_demographics->gender : '';
        $military_status = isset($patient_demographics) ? $patient_demographics->military_status : '';
        $PatientAddress = PatientAddress::where('patient_id', $uid)->latest()->first();
        $consent_to_text = $patient[0]->consent_to_text;
        // dd($PatientAddress);
        $add_1 = empty($PatientAddress) ? '' : $PatientAddress->add_1;
        $add_2 = empty($PatientAddress) ? '' : $PatientAddress->add_2;
        $city = empty($PatientAddress) ? '' : $PatientAddress->city;
        $state = empty($PatientAddress) ? '' : $PatientAddress->state;
        $zipcode = empty($PatientAddress) ? '' : $PatientAddress->zipcode;
        $services = Module::where('patients_service', 1)->get();
        $UserPatients = UserPatients::with('users_assign_to')->where('patient_id', $uid)->where('status', 1)->latest()->first();

        $caremanager_name = empty($UserPatients['users_assign_to']) ? '' : $UserPatients['users_assign_to']->f_name . ' ' . $UserPatients['users_assign_to']->l_name;
        $patient_providers = PatientProvider::where('patient_id', $uid)
            ->with('practice')->with('provider')->with('users')
            ->where('provider_type_id', 1)->where('is_active', 1)->orderby('id', 'desc')
            ->first();
        $practice_id = empty($patient_providers->practice_id) ? '' : $patient_providers->practice_id;
        $billable    = empty($patient_providers->practice['billable']) ? 0 : $patient_providers->practice['billable'];
        $practice_threshold_exist = empty($practice_id) ? '' : PracticeThreshold::where('practice_id', $practice_id)->orderby('id', 'desc')->limit(1)->first();
        $practice_emr  = empty($patient_providers->practice_emr) ? '' : $patient_providers->practice_emr;
        $practice_name = empty($patient_providers['practice']->name) ? '' : $patient_providers['practice']->name;
        $provider_name = empty($patient_providers['provider']->name) ? '' : $patient_providers['provider']->name;
        $patient_enroll_date = PatientServices::latest_module($uid, $module_id);
        $suspended_from = empty($patient_enroll_date[0]->suspended_from) || $patient_enroll_date[0]->suspended_from == 'null' ? '' : date('m-d-Y', strtotime($patient_enroll_date[0]->suspended_from));
        $suspended_to = empty($patient_enroll_date[0]->suspended_to) || $patient_enroll_date[0]->suspended_to == 'null' ? '' : date('m-d-Y', strtotime($patient_enroll_date[0]->suspended_to));
        $date_enrolled = empty($patient_enroll_date[0]->date_enrolled) ? '' : strtok($patient_enroll_date[0]->date_enrolled, ' '); //date('m-d-Y',strtotime($patient_enroll_date[0]->date_enrolled));
        $previous_month_time_spend = CommonFunctionController::getCcmPreviousMonthNetTime($uid, $module_id);
        $last_time_spend                = CommonFunctionController::getCcmNetTime($uid, $module_id);
        $non_billabel_time_db              = CommonFunctionController::getNonBillabelTime($uid, $module_id);
        $billable_time_db                = CommonFunctionController::getCcmMonthlyNetTime($uid, $module_id);
        $non_billabel_time = empty($non_billabel_time_db) ? '00:00:00' : $non_billabel_time_db;
        $billable_time = empty($billable_time_db) ? '00:00:00' : $billable_time_db;
        $personal_notes = (PatientPersonalNotes::latest($uid, 'patient_id') ? PatientPersonalNotes::latest($uid, 'patient_id')->population() : "");
        $research_study = (PatientPartResearchStudy::latest($uid, 'patient_id') ? PatientPartResearchStudy::latest($uid, 'patient_id')->population() : "");
        $patient_threshold = (PatientThreshold::latest($uid, 'patient_id') ? PatientThreshold::latest($uid, 'patient_id')->population() : "");
        //dd($patient_threshold);
        $systolichigh = empty($patient_threshold) ? '' : $patient_threshold['static']['systolichigh'];
        $systoliclow = empty($patient_threshold) ? '' : $patient_threshold['static']['systoliclow'];

        $diastolichigh = empty($patient_threshold) ? '' : $patient_threshold['static']['diastolichigh'];
        $diastoliclow = empty($patient_threshold) ? '' : $patient_threshold['static']['diastoliclow'];

        $bpmhigh = empty($patient_threshold) ? '' : $patient_threshold['static']['bpmhigh'];
        $bpmlow = empty($patient_threshold) ? '' : $patient_threshold['static']['bpmlow'];

        $oxsathigh = empty($patient_threshold) ? '' : $patient_threshold['static']['oxsathigh'];
        $oxsatlow = empty($patient_threshold) ? '' : $patient_threshold['static']['oxsatlow'];

        $glucosehigh = empty($patient_threshold) ? '' : $patient_threshold['static']['glucosehigh'];
        $glucoselow = empty($patient_threshold) ? '' : $patient_threshold['static']['glucoselow'];

        $temperaturehigh = empty($patient_threshold) ? '' : $patient_threshold['static']['temperaturehigh'];
        $temperaturelow = empty($patient_threshold) ? '' : $patient_threshold['static']['temperaturelow'];

        $weighthigh = empty($patient_threshold) ? '' : $patient_threshold['static']['weighthigh'];
        $weightlow = empty($patient_threshold) ? '' : $patient_threshold['static']['weightlow'];

        $spirometerfevhigh = empty($patient_threshold) ? '' : $patient_threshold['static']['spirometerfevhigh'];
        $spirometerfevlow = empty($patient_threshold) ? '' : $patient_threshold['static']['spirometerfevlow'];

        $spirometerpefhigh = empty($patient_threshold) ? '' : $patient_threshold['static']['spirometerpefhigh'];
        $spirometerpeflow = empty($patient_threshold) ? '' : $patient_threshold['static']['spirometerpeflow'];

        if (PatientDevices::where('patient_id', $uid)->where('status', 1)->exists()) {
            $allreadydevice = 1;
        } else {
            $allreadydevice = 0;
        }

        $PatientDevices = PatientDevices::where('patient_id', $uid)->orderby('id', 'desc')->first();

        $device_code = empty($PatientDevices->device_code) ? '' : $PatientDevices->device_code;

        $device_status = empty($PatientDevices->shipping_status) ? '' : $PatientDevices->shipping_status;


        $rpmDevices = (PatientDevices::with('devices')->where('patient_id', $uid)->where('status', 1) ? PatientDevices::with('devices')->where('patient_id', $uid)->where('status', 1)->orderBy('created_at', 'desc')->get() : " ");

        if (isset($rpmDevices[0]->vital_devices)) {

            $data = json_decode($rpmDevices[0]->vital_devices);
            $show_device = "";

            for ($j = 0; $j < count($data); $j++) {

                if (property_exists($data[$j], "vid")) {
                    // Access properties using -> operator since $data[$j] is an object
                    $dev = Devices::where('id', $data[$j]->vid)->where('status', '1')->orderby('id', 'asc')->first();
                    if (!empty($dev)) {
                        $parts = explode(" ", $dev->device_name);
                        $devices = implode('-', $parts);

                        $filename = RPMProtocol::where("device_id", $data[$j]->vid)->where('status', '1')->first();
                        if (!empty($filename)) {
                            $filenames = $filename->file_name;
                            $btn = '<a href="' . $filenames . '" target="_blank" title="Start" id="detailsbutton">Protocol</a>';

                            $show_device .= $dev->device_name . " (" . $btn . "), ";
                        }
                    }
                }
            }

            $patient_assign_device = rtrim($show_device, ', ');
        } else {
            $patient_assign_device = "";
        }

        //dd($practice_threshold_exist->systolichigh);

        if (PatientServices::where('patient_id', $uid)->where('status', 1)->where('module_id', 2)->exists()) {
            $enroll_in_rpm   = 1;
        } else {
            $enroll_in_rpm   = 0;
        }

        return [
            'patient'               => $patient,
            'patient_services'      => $patient_services,
            'gender'                => $gender,
            'military_status'       => $military_status,
            'age'                   => $age,
            'PatientAddress'        => $PatientAddress,
            'add_1'                 => $add_1,
            'add_2'                 => $add_2,
            'city'                  => $city,
            'state'                 => $state,
            'zipcode'               => $zipcode,
            'date_enrolled'         => $date_enrolled,
            'suspended_from'        => $suspended_from,
            'suspended_to'          => $suspended_to,
            'provider_name'         => $provider_name,
            'practice_emr'          => $practice_emr,
            'practice_name'         => $practice_name,
            // 'UserPatients' => $UserPatients,
            'caremanager_name'      => $caremanager_name,
            'patient_enroll_date'   => $patient_enroll_date,
            'device_code'           => $device_code,
            'device_status'         => $device_status,
            'non_billabel_time'     => $non_billabel_time,
            'billable_time'         => $billable_time,
            'personal_notes'        => $personal_notes,
            'research_study'        => $research_study,
            // 'patient_threshold'=>$patient_threshold
            'systolichigh'          => $systolichigh,
            'systoliclow'           => $systoliclow,
            'diastolichigh'         => $diastolichigh,
            'diastoliclow'          => $diastoliclow,
            'bpmhigh'               => $bpmhigh,
            'bpmlow'                => $bpmlow,
            'oxsathigh'             => $oxsathigh,
            'oxsatlow'              => $oxsatlow,
            'glucosehigh'           => $glucosehigh,
            'glucoselow'            => $glucoselow,
            'weighthigh'            => $weighthigh,
            'weightlow'             => $weightlow,
            'temperaturehigh'       => $temperaturehigh,
            'temperaturelow'        => $temperaturelow,
            'spirometerfevhigh'     => $spirometerfevhigh,
            'spirometerfevlow'      => $spirometerfevlow,
            'spirometerpefhigh'     => $spirometerpefhigh,
            'spirometerpeflow'      => $spirometerpeflow,
            'patient_assign_device' => $patient_assign_device,
            'consent_to_text'       => $consent_to_text,
            'allreadydevice'        => $allreadydevice,
            'billable'              => $billable,
            'enroll_in_rpm'         => $enroll_in_rpm
        ];
    }

    // public function fetchPatientDetailsinModel($patient_id,$module_id){ 
    //     $uid       = sanitizeVariable($patient_id);
    //     $module_id = sanitizeVariable($module_id);
    //     $patient =  Patients::with('patientServices', 'patientServices.module')->where('id',$uid)->get();
    //     $age = empty($patient[0]->dob) ?'' : age($patient[0]->dob);
    //     $patient_demographics = PatientDemographics::where('patient_id', $uid)->latest()->first();
    //     $gender = isset($patient_demographics)?$patient_demographics->gender:'';
    //     $PatientAddress = PatientAddress::where('patient_id', $uid)->latest()->first();
    //     // dd($PatientAddress);
    //     $add_1 = empty($PatientAddress)?'':$PatientAddress->add_1;
    //     $add_2 = empty($PatientAddress)?'':$PatientAddress->add_2;
    //     $city = empty($PatientAddress)?'':$PatientAddress->city;
    //     $state = empty($PatientAddress)?'':$PatientAddress->state;
    //     $services = Module::where('patients_service',1)->get();
    //     $UserPatients = UserPatients::with('users_assign_to')->where('patient_id',$uid)->latest()->first();
    //     $caremanager_name = empty($UserPatients) ? '' : $UserPatients['users_assign_to']->f_name .' '. $UserPatients['users_assign_to']->l_name;
    //     $patient_providers = PatientProvider::where('patient_id', $uid)
    //                          ->with('practice')->with('provider')->with('users')
    //                          ->where('provider_type_id',1)->where('status',1)->orderby('id','desc')
    //                          ->first();
    //     $practice_id = empty($patient_providers->practice_id)?'':$patient_providers->practice_id;       

    //     $practice_threshold_exist = empty($practice_id)?'':PracticeThreshold::where('practice_id',$practice_id)->orderby('id','desc')->limit(1)->first();
    //     $practice_emr  = empty($patient_providers->practice_emr)?'':$patient_providers->practice_emr;
    //     $practice_name = empty($patient_providers['practice']->name)?'':$patient_providers['practice']->name;
    //     $provider_name = empty($patient_providers['provider']->name)?'':$patient_providers['provider']->name;
    //     $patient_enroll_date = PatientServices::latest_module($uid, $module_id);
    //     $suspended_from = empty($patient_enroll_date[0]->suspended_from) || $patient_enroll_date[0]->suspended_from=='null'?'':date('m-d-Y',strtotime($patient_enroll_date[0]->suspended_from));
    //     $suspended_to = empty($patient_enroll_date[0]->suspended_to) || $patient_enroll_date[0]->suspended_to=='null'?'':date('m-d-Y',strtotime($patient_enroll_date[0]->suspended_to));
    //     $date_enrolled = empty($patient_enroll_date[0]->date_enrolled)?'':date('m-d-Y',strtotime($patient_enroll_date[0]->date_enrolled));  
    //     $previous_month_time_spend = CommonFunctionController::getCcmPreviousMonthNetTime($uid, $module_id);
    //     $last_time_spend                = CommonFunctionController::getCcmNetTime($uid, $module_id);
    //     $non_billabel_time_db              = CommonFunctionController::getNonBillabelTime($uid, $module_id);
    //     $billable_time_db                = CommonFunctionController::getCcmMonthlyNetTime($uid, $module_id);        
    //     $non_billabel_time = empty($non_billabel_time_db)?'00:00:00':$non_billabel_time_db;
    //     $billable_time = empty($billable_time_db)?'00:00:00':$billable_time_db;
    //     $personal_notes = (PatientPersonalNotes::latest($uid,'patient_id') ? PatientPersonalNotes::latest($uid,'patient_id')->population() : "");
    //     $research_study = (PatientPartResearchStudy::latest($uid,'patient_id') ? PatientPartResearchStudy::latest($uid,'patient_id')->population() : "");
    //     $patient_threshold = (PatientThreshold::latest($uid,'patient_id') ? PatientThreshold::latest($uid,'patient_id')->population() : "");
    //     $systolichigh = empty($patient_threshold)?'': $patient_threshold['static']['systolichigh'];
    //     $systoliclow = empty($patient_threshold)?'': $patient_threshold['static']['systoliclow'];

    //     $diastolichigh = empty($patient_threshold)?'': $patient_threshold['static']['diastolichigh'];
    //     $diastoliclow = empty($patient_threshold)?'': $patient_threshold['static']['diastoliclow'];

    //     $bpmhigh = empty($patient_threshold)?'': $patient_threshold['static']['bpmhigh'];
    //     $bpmlow = empty($patient_threshold)?'': $patient_threshold['static']['bpmlow'];

    //     $oxsathigh = empty($patient_threshold)?'': $patient_threshold['static']['oxsathigh'];
    //     $oxsatlow = empty($patient_threshold)?'': $patient_threshold['static']['oxsatlow'];

    //     $glucosehigh = empty($patient_threshold)?'': $patient_threshold['static']['glucosehigh'];
    //     $glucoselow = empty($patient_threshold)?'': $patient_threshold['static']['glucoselow'];

    //     $temperaturehigh = empty($patient_threshold)?'': $patient_threshold['static']['temperaturehigh'];
    //     $temperaturelow = empty($patient_threshold)?'': $patient_threshold['static']['temperaturelow'];

    //     $weighthigh = empty($patient_threshold)?'': $patient_threshold['static']['weighthigh'];
    //     $weightlow = empty($patient_threshold)?'': $patient_threshold['static']['weightlow'];

    //     $PatientDevices = PatientDevices::where('patient_id',$uid)->orderby('id','desc')->first();
    //     $device_code = empty($PatientDevices->device_id)?'':$PatientDevices->device_id;

    //     // dd($patient);
    //     return view('Patients::patient.patient-Ajaxbasic-info-model',['patient'=>$patient],
    //      compact('patient',
    //        'gender', 
    //        'age', 
    //        'PatientAddress', 
    //        'add_1',
    //        'add_2',
    //        'city', 
    //        'state', 
    //        'date_enrolled',
    //        'suspended_from',
    //        'suspended_to',
    //        'provider_name',
    //        'practice_emr',
    //        'practice_name',
    //        // 'UserPatients' => $UserPatients,
    //        'caremanager_name',
    //        'patient_enroll_date',
    //        'PatientDevices',
    //        'device_code',
    //        'non_billabel_time',
    //        'billable_time',
    //        'personal_notes',
    //        'research_study',
    //        'systolichigh',
    //        'systoliclow',
    //        'diastolichigh',
    //        'diastoliclow',
    //        'bpmhigh',
    //        'bpmlow',
    //        'oxsathigh',
    //        'oxsatlow',
    //        'glucosehigh',
    //        'glucoselow',
    //        'weighthigh',
    //        'weightlow',
    //        'temperaturehigh',
    //        'temperaturelow',
    //        'temperaturehigh',
    //        'temperaturelow',
    //        'temperaturehigh',
    //        'temperaturelow'
    //        ));     

    // } 


    public function assignedPatients(Request $request)
    {
        $active_pracs = Practices::activePcpPractices();
        $inative_pracs = Practices::InactivePcpPractices();
        return view('Patients::patient.assigned-patient-list', compact('active_pracs', 'inative_pracs'));
    }

    // 
    public function saveUnsubscribe(RecaptchaRequest $request)
    {
        $contactno = sanitizeVariable($request->contact_no);
        $patient_id = sanitizeVariable($request->patientid);
        $unsubscribe = sanitizeVariable($request->unsubscribe);

        $contact1 = substr($contactno, 0, 3);
        $contact2 = substr($contactno, 3, 3);
        $contact3 = substr($contactno, 6, 4);
        $phoneno = "(" . $contact1 . ") " . $contact2 . "-" . $contact3;

        if ($patient_id != "") {
            $checkexist = patients::where('id', $patient_id)->where('mob', $phoneno)->orWhere('home_number', $phoneno)->exists();
            if ($checkexist == true) {
                if ($unsubscribe == '0') {
                    patients::where('id', $patient_id)->update(['consent_to_text' => $unsubscribe]);
                    return "1";
                }
            } else {
                return "Patient Phone No. doesn't exist!";
            }
        } else {
            return "Patient doesn't exist!";
        }
    }

    //created by Priya for deactivation patient 
    public function savePatientActiveDeactive(ActiveDeactiveAddRequest $request)
    {
        $patient_id     = sanitizeVariable($request->patient_id);
        if ($patient_id == '') {
            $patient_id = sanitizeVariable($request->patientid);
        }
        $from_date      = sanitizeVariable($request->activedeactivefromdate);
        if ($from_date != '') {
            $fromdate       = $from_date;
        } else {
            $fromdate = date('Y-m-d h:i:s');
        }
        $deceased_date  = sanitizeVariable($request->deceasedfromdate);
        $todate         = sanitizeVariable($request->activedeactivetodate);
        $comments       = sanitizeVariable($request->deactivation_reason);
        $module_id      = sanitizeVariable($request->module_id);
        // if($module_id==8){ 
        //     $select_module = 3;
        // }else{
        //    $select_module = $module_id; 
        // } no more used
        $select_module = sanitizeVariable($request->modules);

        $deactivation_drpdwn = sanitizeVariable($request->deactivation_drpdwn);
        // dd($select_module); 
        $start_time     = sanitizeVariable($request->start_time); //echo "<br>";
        $end_time       = sanitizeVariable($request->end_time);
        $component_id   = sanitizeVariable($request->component_id);
        $stage_id       = 0;
        $step_id        = 0;
        $form_name      = sanitizeVariable($request->form_name);
        $billable       = 1;
        $patient_status = sanitizeVariable($request->status);
        $form_start_time = isset($request->timearr['form_start_time']) ? sanitizeVariable($request->timearr['form_start_time']) : null;

        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        $activedataInsert   = array(
            'patient_id'        => $patient_id,
            'from_date'         => $fromdate,
            'to_date'           => $todate,
            'module_id'         => $select_module,
            'comments'          => $comments,
            'activation_status' => $patient_status,
            'created_by'        => session()->get('userid'),
            'updated_by'        => session()->get('userid')
        );

        if ($patient_status == '3') {
            $status_value = 'Deceased';
            $service_data = array(
                'status'                => '3',
                'status_value'          => $status_value,
                'suspended_from'        => $deceased_date, //date('Y-m-d h:i:s'),
                'deactivation_reason'   => $comments,
                'updated_by'            => session()->get('userid')
            );

            PatientServices::where('patient_id', $patient_id)->update($service_data);
            // PatientServices::where('patient_id',$patient_id)->get(); 

            $data = array(
                'status'                => '3',
                'service_count'         => 0,
                'status_value'          => $status_value,
                'from_date'             => $deceased_date, //date('Y-m-d h:i:s'),  
                'updated_by'            => session()->get('userid')
            );

            Patients::where('id', $patient_id)->update($data);
            PatientActiveDeactiveHistory::create($activedataInsert);
        } else {

            $check_patient_status_from_master = Patients::where('id', $patient_id)->where('status', 3)->exists();
            $total_services = PatientServices::where('patient_id', $patient_id)->distinct()->pluck('module_id')->whereNotIn('status', [2, 3])->count();
            if ($patient_status != '' && $patient_status == '1') {
                $status_value = 'Active';
                $check_given_services = 0;
            } else if ($patient_status != '' && $patient_status == '0') {
                $status_value = 'Suspended';
                $check_given_services = 0;
            } else if ($patient_status != '' && $patient_status == '2') {
                $status_value = 'Deactive';
                $check_given_services = 1;
            } else {
                // $status_value ='Deceased';
            }
            $service_count  = $total_services - $check_given_services;
            if ($service_count == 0) {
                $depend_patient_status = 2;
                $depend_status_value = 'Deactive';
            }
            if ($service_count > 0) {
                $depend_patient_status = 1;
                $depend_status_value = 'Active';
            }

            $data = array(
                'suspended_from'        => $fromdate,
                'suspended_to'          => $todate,
                'status'                => $patient_status,
                'status_value'          => $status_value,
                'deactivation_reason'   => $comments,
                'deactivation_drpdwn'   => $deactivation_drpdwn,
                'updated_by'            => session()->get('userid')
            );
            $patient_data = array(
                'status'                => $depend_patient_status,
                'service_count'         => $service_count,
                'status_value'          => $depend_status_value,
                'from_date'             => date('Y-m-d h:i:s'),
                'updated_by'            => session()->get('userid')
            );

            if ($check_patient_status_from_master == true) {
                $enrolled_module = PatientServices::where('patient_id', $patient_id)->distinct()->pluck('module_id')->toArray();

                foreach ($enrolled_module as $old) {
                    $last_lates_rec = PatientActiveDeactiveHistory::where('patient_id', $patient_id)
                        ->where('module_id', $old)
                        ->orderBy('id', 'desc')->get();

                    if (!$last_lates_rec->isEmpty()) {
                        $exist_data_revert = array(
                            // 'patient_id'            => $last_lates_rec [0]->$last_lates_rec[0];
                            'suspended_from'        => $last_lates_rec[0]->fromdate,
                            'suspended_to'          => $last_lates_rec[0]->todate,
                            'status'                => $last_lates_rec[0]->activation_status,
                            'status_value'          => $last_lates_rec[0]->status_value,
                            'deactivation_reason'   => $last_lates_rec[0]->comments,
                            'updated_by'            => session()->get('userid')
                        );

                        PatientServices::where('patient_id', $patient_id)
                            ->where('module_id', '!=', $select_module)->update($exist_data_revert);
                    }

                    PatientServices::where('patient_id', $patient_id)
                        ->where('module_id', $select_module)->update($data);
                }
                PatientActiveDeactiveHistory::create($activedataInsert);
                Patients::where('id', $patient_id)->update($patient_data);
            } else {
                // echo "string";die;
                PatientServices::where('patient_id', $patient_id)->where('module_id', $select_module)->update($data);
                PatientActiveDeactiveHistory::create($activedataInsert);
                Patients::where('id', $patient_id)->update($patient_data);
            }
        } //end else

        $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
        return response(['form_start_time' => $form_save_time]);
    }

    public function getPatientActiveDeactive(Request $request)
    {
        $patient_id                     = sanitizeVariable($request->route('patient_id'));
        $getdata                        = Patients::where('id', $patient_id)->get();
        $result['extend_deactive_form'] = $getdata;
        return $result;
    }

    public function testApi(Request $request)
    {
        $orderid           = sanitizeVariable($request->order_id);
        $mrn               = sanitizeVariable($request->MSP_MRN);
        $patient_id        = sanitizeVariable($request->REN_MRN);
        $devicename        = sanitizeVariable($request->Devices['name']);
        $Hub               = sanitizeVariable($request->Hub);
        $shipped           = sanitizeVariable($request->shipping_details['shipped']);
        $carrier_name      = sanitizeVariable($request->shipping_details['carrier-name']);
        $trackingno        = sanitizeVariable($request->shipping_details['tracking_no']);
        if ($shipped == 'Y') {
            $shipped       = 1;
        } else {
            $shipped       = 0;
        }
        $orderdate         = date('Y-m-d');
        $insertdata = array(
            'patient_id'   => $patient_id,
            'partner_mrn'  => $mrn,
            'device'       => $devicename,
            'shipped'      => $shipped,
            'hub'          => $Hub,
            'carrier_name' => $carrier_name,
            'tracking_no'  => $trackingno
        );
        PatientOrders::create($insertdata);
        return "Patient order updated successfully!";
    }

    public function assignedPatientsSearch(Request $request)
    {

        $practices = sanitizeVariable($request->route('practiceid'));
        $provider = sanitizeVariable($request->route('providerid'));
        $time  = sanitizeVariable($request->route('time'));
        $care_manager_id  = sanitizeVariable($request->route('care_manager_id'));
        $patient_status = sanitizeVariable($request->route('patientstatus'));
        $timeoption = sanitizeVariable($request->route('timeoption'));
        $currentdate = sanitizeVariable(date('Y-m-d HH:mm:ss'));

        $monthly = Carbon::now();
        $monthlyto = Carbon::now();

        $year = date('Y', strtotime($monthly));
        $month = date('m', strtotime($monthly));
        $configTZ = config('app.timezone');
        $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');

        $query = " select distinct p.id, p.fname, p.lname, p.mname, pp.practice_emr, p.profile_img, pra.id as practice_id, concat(pra.name || ' ' || pra.location ) as cmname, p.dob, pra.is_active as practice_status,
              pr.name,usr.f_name,usr.l_name,usr.id as uid,ptr.totaltime, array_to_string(array_agg(distinct m.module), ',')  as module,
            to_char(ccs.last_date at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY HH24:MI:SS') as last_date,
            CASE
                WHEN ptr.totaltime IS NULL THEN '00:00:00'
                ELSE ptr.totaltime
                END  
              from patients.patient p 
              left JOIN patients.patient_services ps 
            on p.id = ps.patient_id and ps.status = 1 
            left JOIN ren_core.modules m on ps.module_id = m.id
            left join task_management.user_patients up on up.patient_id =p.id and up.status=1
            left join ren_core.users usr on usr.id=up.user_id ";

        $query .= " LEFT JOIN (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
            from patients.patient_providers pp1
            inner join (select patient_id, max(id) as max_pat_practice 
            from patients.patient_providers  where provider_type_id = 1 and is_active=1
            group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice
            and pp1.provider_type_id = 1 and pp1.is_active=1 ) pp                 
            on p.id = pp.patient_id  ";
        // }

        /* $query.=" left join (select count(us.patient_id) as patient_count, pp.practice_id, us.user_id 
            from task_management.user_patients us
            LEFT JOIN (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
            from patients.patient_providers pp1
            inner join (select patient_id, max(created_at) as created_date 
            from patients.patient_providers  where provider_type_id = 1 
            group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.created_at = pp2.created_date
            and pp1.provider_type_id = 1 ) pp                 
            on us.patient_id = pp.patient_id  
             where us.status=1  GROUP BY us.user_id,pp.practice_id) cmc on cmc.user_id=usr.id and cmc.practice_id=pp.practice_id ";*/

        $query .= "left join ren_core.practices pra on pp.practice_id=pra.id
             left join ren_core.providers pr on pp.provider_id=pr.id

             LEFT JOIN (SELECT distinct patient_id, MAX(created_at) as last_date
                       FROM ccm.ccm_call_status
                      WHERE call_status = '1'
                      GROUP BY patient_id
                    ) ccs on ccs.patient_id =  ps.patient_id  

            LEFT JOIN (select distinct pt.patient_id,pt1.timeone,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone ) as totaltime
            from  patients.patient_time_records pt 

            LEFT JOIN (SELECT distinct patient_id,sum(net_time) AS timeone FROM patients.patient_time_records WHERE 
            adjust_time =1 and (EXTRACT(Month from record_date) = '" . $month . "')
             AND (EXTRACT(YEAR from record_date) = '" . $year . "') GROUP BY patient_id) pt1 
            ON  pt1.patient_id = pt.patient_id 

            LEFT JOIN (SELECT distinct patient_id, sum(net_time) AS timetwo FROM patients.patient_time_records WHERE 
            adjust_time =0 and  (EXTRACT(Month from record_date) ='" . $month . "')
             AND (EXTRACT(YEAR from record_date) = '" . $year . "') GROUP BY patient_id) pt2 
            ON  pt2.patient_id = pt.patient_id 

            where   
            (EXTRACT(Month from pt.record_date) = '" . $month . "') 
            AND (EXTRACT(YEAR from pt.record_date) = '" . $year . "') 
            ) ptr
            on ps.patient_id = ptr.patient_id  where 1=1 "; //" where ptr.totaltime ".$timeoption." '".$time."' ";

        if ($time != "null" && $timeoption == "=" && $time == "00:00:00") {
            $query .= " AND ptr.totaltime IS NULL ";
        } else if ($time != "null" && $timeoption == "0" && $time == "00:00:00") {
        } else {
            $query .= " AND ptr.totaltime " . $timeoption . " '" . $time . "'";
        }


        if ($practices != "null" && $practices != '0' && $practices != '') {
            $query .= " AND pp.practice_id = '" . $practices . "' ";
        } else {
            $query .= " AND pra.is_active = '1' ";
        }

        if ($provider != "null" && $provider != '0') {
            $query .= " AND pp.provider_id = '" . $provider . "' ";
        }

        if ($care_manager_id != 'null' && $care_manager_id != '0') {
            $query .= " AND usr.id = '" . $care_manager_id . "'";
        }
        if ($care_manager_id == '0') {
            $query .= " and p.id not in (select up1.patient_id from task_management.user_patients up1 where up1.status=1)";
        }
        if ($practices == '0') {
            $query .= " AND pp.practice_id is null ";
        }
        if ($provider == '0') {
            $query .= " AND pp.provider_id is null ";
        }
        if ($patient_status != "null" && $patient_status != "") {
            $query .= " AND p.status = " . $patient_status . " ";
        } else {
            $query .= " AND p.status in (0,1) ";
        }

        $query .= " group by concat(pra.name || ' ' || pra.location ),pra.id, pr.name,usr.f_name,usr.l_name,ptr.totaltime,last_date,p.id,usr.id,pp.practice_emr";

        // dd($query);
        $data = DB::select($query);
        //$careManager = DB::table('ren_core.users')->where('role', '=', 5)->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('patient_count', function ($row) {
                if ($row->practice_id == '' or $row->practice_id == null) {
                    $practice_cond =  " and (pp.practice_id is null )";
                } else {
                    $practice_cond =  " and pp.practice_id = $row->practice_id";
                }
                if ($row->uid) {
                    $patientcount = "select count(us.patient_id) as patient_count, pp.practice_id, us.user_id 
              from task_management.user_patients us
              LEFT JOIN (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
              from patients.patient_providers pp1
              inner join (select patient_id, max(id) as created_date 
              from patients.patient_providers  where provider_type_id = 1 and is_active=1
              group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.created_date
              and pp1.provider_type_id = 1 and is_active=1) pp                 
              on us.patient_id = pp.patient_id  
              where us.status=1 and user_id = $row->uid   $practice_cond
              GROUP BY us.user_id, pp.practice_id";

                    $patient_count = DB::select($patientcount);
                    if (!empty($patient_count)) {
                        return $patient_count[0]->patient_count;
                    } else {
                        return 0;
                    }
                } else {
                    return 0;
                }
            })
            ->addColumn('action', function ($row) {
                if ($row->practice_id == '') {
                    $careManager =   DB::table('ren_core.users')
                        ->where('role', '=', 5)
                        ->get();
                } else {
                    $careManager =   DB::table('ren_core.users')
                        ->where('role', '=', 5)
                        ->whereIn('id', DB::table('ren_core.user_practices')->where('practice_id', $row->practice_id)->pluck('user_id'))
                        ->get();
                }
                $btn = '';
                $cm = '';
                if ($row->practice_status == '1') {
                    $cm = "<input type='hidden' name='patient[]' value='" . $row->id . "'><select  onchange='assignPatient(" . $row->id . ", this.value)'><option>select CM</option>
                    <option value=''>None</option>";
                    foreach ($careManager as $key => $value) {
                        if ($value->id == $row->uid) {
                            $select = 'selected';
                        } else {
                            $select = '';
                        }
                        $cm .= "<option value='" . $value->id . "' " . $select . ">" . $value->f_name . ' ' . $value->l_name . "</option>";
                    }
                    $cm .= "</select>";
                }

                $btn .= $cm;
                return $btn;
            })
            ->rawColumns(['action', 'patient_count'])


            ->make(true);
    }

    public function CareManagerPatientSearch(Request $request)
    {
        // dd($request);
        $practices = sanitizeVariable($request->route('practiceid'));
        $provider = sanitizeVariable($request->route('providerid'));
        $time  =    sanitizeVariable($request->route('time'));
        $care_manager_id  = sanitizeVariable($request->route('care_manager_id'));
        $timeoption = $request->sanitizeVariable(route('timeoption'));
        $currentdate = date('Y-m-d HH:mm:ss');

        $monthly = Carbon::now();
        $monthlyto = Carbon::now();

        $year = date('Y', strtotime($monthly));
        $month = date('m', strtotime($monthly));
        $configTZ = config('app.timezone');
        $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');

        $query = " select distinct p.id, p.fname, p.lname, p.mname, p.profile_img, pra.name as cmname, p.dob,
                  pr.name,usr.f_name,usr.l_name,ptr.totaltime, array_to_string(array_agg(distinct m.module), ',')  as module,
                to_char(ccs.last_date at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY HH24:MI:SS') as last_date,
                CASE
                    WHEN ptr.totaltime IS NULL THEN '00:00:00'
                    ELSE ptr.totaltime
                    END  
                  from patients.patient p 
                  left JOIN patients.patient_services ps 
                on p.id = ps.patient_id and ps.status = 1 
                left JOIN ren_core.modules m on ps.module_id = m.id
                left join task_management.user_patients up on up.patient_id =p.id and up.status=1
                left join ren_core.users usr on usr.id=up.user_id ";

        $query .= " LEFT JOIN (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                from patients.patient_providers pp1
                inner join (select patient_id, max(created_at) as created_date 
                from patients.patient_providers  where provider_type_id = 1 
                group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.created_at = pp2.created_date
                and pp1.provider_type_id = 1 ) pp                 
                on p.id = pp.patient_id  ";
        // }

        $query .= "left join ren_core.practices pra on pp.practice_id=pra.id
                 left join ren_core.providers pr on pp.provider_id=pr.id

                 LEFT JOIN (SELECT distinct patient_id, MAX(created_at) as last_date
                           FROM ccm.ccm_call_status
                          WHERE call_status = '1'
                          GROUP BY patient_id
                        ) ccs on ccs.patient_id =  ps.patient_id  

            LEFT JOIN (select distinct pt.patient_id,pt1.timeone,pt2.timetwo,COALESCE(pt1.timeone-pt2.timetwo,pt1.timeone ) as totaltime
                from  patients.patient_time_records pt 
    
                LEFT JOIN (SELECT distinct patient_id,sum(net_time) AS timeone FROM patients.patient_time_records WHERE 
                adjust_time =1 and (EXTRACT(Month from record_date) = '" . $month . "')
                 AND (EXTRACT(YEAR from record_date) = '" . $year . "') GROUP BY patient_id) pt1 
                ON  pt1.patient_id = pt.patient_id 
    
                LEFT JOIN (SELECT distinct patient_id, sum(net_time) AS timetwo FROM patients.patient_time_records WHERE 
                adjust_time =0 and  (EXTRACT(Month from record_date) ='" . $month . "')
                 AND (EXTRACT(YEAR from record_date) = '" . $year . "') GROUP BY patient_id) pt2 
                ON  pt2.patient_id = pt.patient_id 
    
                where   
                (EXTRACT(Month from pt.record_date) = '" . $month . "') 
                AND (EXTRACT(YEAR from pt.record_date) = '" . $year . "') 
                ) ptr
                on ps.patient_id = ptr.patient_id where 1=1 "; //" where ptr.totaltime ".$timeoption." '".$time."' ";

        if ($time != "null" && $timeoption == "=" && $time == "00:00:00") {
            $query .= " AND ptr.totaltime IS NULL ";
        }
        // elseif($time=='' || $time=="null")
        // {
        //   $query.=" AND ptr.totaltime IS NULL ";      
        // }
        else {
            $query .= " AND ptr.totaltime " . $timeoption . " '" . $time . "'";
        }



        if ($practices != "null" && $practices != 0) {
            $query .= " AND pp.practice_id = '" . $practices . "' ";
        }
        if ($provider != "null" && $provider != 0) {
            $query .= " AND pp.provider_id = '" . $provider . "' ";
        }

        if ($care_manager_id != 'null' && $care_manager_id != '0') {
            $query .= " AND usr.id = '" . $care_manager_id . "'";
        }

        $query .= " group by pra.name, pr.name,usr.f_name,usr.l_name,ptr.totaltime,last_date,p.id";

        // dd($query);
        $data = DB::select($query);
        return Datatables::of($data)
            ->addIndexColumn()

            ->rawColumns(['action'])


            ->make(true);
    }

    public function patientCaretool($patient_id, $module_id)
    {
        $dateS = Carbon::now()->startOfMonth()->subMonth(3);
        // dd($dateS);
        $dateE = Carbon::now()->endOfMonth();
        // dd($dateE);
        $caretool = PatientCareplanCarertool::where('uid', $patient_id)->where('generalnotes', '!=', null)->whereBetween('monthyear', [$dateS, $dateE])->orderby('monthyear', 'desc')->get();
        $Condition = PatientConditionCaretool::where('uid', $patient_id)->get();
        return view('Patients::patient.patient-caretool', compact('caretool', 'Condition'));
    }

    public function practiceDocument($id)
    {
        $id = sanitizeVariable($id);
        $documents = Document::where('id', $id)->get();
        return view('Patients::patient.prac-upload-doc', compact('documents'));
    }

    public function patientStatus($patient_id, $module_id)
    {
        // dd($patient_id,$module_id); 
        //$chronicCondition = (PatientDiagnosis::with('diagnosis')->where('patient_id',$patient_id) ? PatientDiagnosis::with('diagnosis')->where('patient_id',$patient_id)->get() :"");
        $patient_id = sanitizeVariable($patient_id);
        $module_id = sanitizeVariable($module_id);
        $formonth     = date('m');
        $foryear      = date("Y");
        $patient_providers = PatientProvider::where('patient_id', $patient_id)
            ->with('practice')->with('provider')->with('users')
            ->where('provider_type_id', 1)->where('is_active', 1)->orderby('id', 'desc')
            ->first();
        //    dd($patient_providers.",patient_id".$patient_id);
        if ($patient_providers) {
            $documents = Document::where('provider_id', $patient_providers->provider_id)->where('practice_id', $patient_providers->practice_id)->where('status', 1)->get();
        } else {
            $documents = [];
        }
        //dd($documents);
        $comp = ModuleComponents::where('module_id', $module_id)->where('components', 'Care Plan Development')->get('id');
        if (!empty($comp[0])) {
            $component_id = $comp[0]->id;
        } else {
            $component_id = "0";
        }
        $Condition = DB::select("select distinct diagnosis, 
                        max(updated_at) as date
                        FROM patients.patient_diagnosis_codes 
                        where updated_at >= date_trunc('month', current_date)  
                        AND  updated_at >= date_trunc('year', current_date)
                        AND patient_id = '" . $patient_id . "'
                        AND status = 1 
                        group by diagnosis 
                        ");

        $chronicCondition = empty($Condition) ? '' : $Condition;
        $patientdiagnosis = PatientDiagnosis::where('status', 1)->where('patient_id', $patient_id)->latest()->first();
        $patientdiagnosislastmodified = PatientDiagnosis::with('users_created_by')->where('status', 1)->where('patient_id', $patient_id)->latest()->first();

        if ($chronicCondition == "" || $chronicCondition == null) {
        } else {

            foreach ($chronicCondition as $chronic) {

                $datapatientdiagnosis = PatientDiagnosis::where('status', 1)->where('patient_id', $patient_id)->where('diagnosis', $chronic->diagnosis)->first();
                $newpatientdiagnosislastmodified = PatientDiagnosis::with('users_created_by')->where('status', 1)->where('patient_id', $patient_id)->where('diagnosis', $chronic->diagnosis)->first();
                $lastupdateandreviewdate  = PatientCareplanLastUpdateandReview::where('diagnosis_id', $chronic->diagnosis)->where('patient_id', $patient_id)->where('status', 1)->first();
                $chronic->code = $datapatientdiagnosis->code;
                $chronic->condition = $datapatientdiagnosis->condition;
                $chronic->userfname = $patientdiagnosislastmodified['users_created_by']->f_name;
                $chronic->userlname = $patientdiagnosislastmodified['users_created_by']->l_name;

                if ($lastupdateandreviewdate == null  || $lastupdateandreviewdate == '') {

                    // $chronicdate = explode(' ',$chronic->date);
                    // $chronic->update_date = $chronicdate[0];
                    $chronic->update_date = null;
                    $chronic->review_date = null;
                } else {

                    $updatedate = date('Y-m-d', strtotime($lastupdateandreviewdate->update_date));
                    $reviewdate = date('Y-m-d', strtotime($lastupdateandreviewdate->review_date));

                    $chronic->update_date = $updatedate;
                    $chronic->review_date = $reviewdate;
                }
            }
        }

        $rpmDevices = (PatientDevices::with('devices')->where('patient_id', $patient_id)->where('status', 1) ? PatientDevices::with('devices')->where('patient_id', $patient_id)->where('status', 1)->orderBy('created_at', 'desc')->get() : " ");

        //dd($rpmDevices[0]->vital_devices);
        //code by anand
        if (isset($rpmDevices[0]->vital_devices)) {
            $data = json_decode($rpmDevices[0]->vital_devices, true);
            $show_device = "";

            if (is_array($data)) {
                foreach ($data as $item) {
                    if (array_key_exists("vid", $item)) {
                        $dev = Devices::where('id', $item['vid'])
                            ->where('status', '1')
                            ->orderBy('id', 'asc')
                            ->first();

                        if (!empty($dev)) {
                            $parts = explode(" ", $dev->device_name);
                            $devices = implode('-', $parts);

                            $filename = RPMProtocol::where("device_id", $item['vid'])
                                ->where('status', '1')
                                ->first();

                            if (!empty($filename)) {
                                $filenames = $filename->file_name;
                                $btn = '<a href="' . $filenames . '" target="_blank" title="Start" id="detailsbutton">Protocol</a>';
                                $show_device .= $dev->device_name . " (" . $btn . "), ";
                            }
                        }
                    }
                }
            }

            $patient_assign_device = rtrim($show_device, ', ');
        } else {
            $patient_assign_device = "";
        }




        $education_training = DB::select("select 
                                          max(record_date) as date
                                          FROM rpm.device_education_training 
                                          where patient_id = '" . $patient_id . "'
                                          AND status = 1 
                                          ");

        $device_education_training = (empty($education_training[0]->date) || $education_training[0]->date == null) ? '' : date("m-d-Y", strtotime($education_training[0]->date));
        $medication_data = DB::select("select med_id,pm1.id,pm1.description,purpose,strength,duration,dosage,frequency,route,
                                        rm.description as name, pm1.updated_at as updated_at
                                        from patients.patient_medication pm1 
                                        left join ren_core.medication rm on rm.id = pm1.med_id 
                                        where pm1.status = 1 AND pm1.id in (select max(pm.id) from patients.patient_medication pm 
                                            where pm.patient_id = '" . $patient_id . "' 
                                            AND EXTRACT(Month from pm.created_at)= '" . $formonth . "'
                                            AND EXTRACT(YEAR from pm.created_at) = '" . $foryear . "' 
                                            group by pm.med_id) 
                                        order by rm.description asc");

        $medication = empty($medication_data) ? '' : $medication_data;

        $lastContactDate = (CallStatus::where('patient_id', $patient_id)->where('call_continue_status', 1)->where('call_status', 1) ?
            CallStatus::where('patient_id', $patient_id)->where('call_continue_status', 1)->where('call_status', 1)->orderBy('updated_at', 'desc')->get('rec_date') : "");
        // dd($lastContactDate);
        $ellapsedTime = CommonFunctionController::getNetTimeBasedOnModule($patient_id, $module_id);
        $currentEllapsedTime = CommonFunctionController::getCcmNetTime($patient_id, $module_id);
        $previousEllapsedTime = CommonFunctionController::getCcmPreviousMonthNetTime($patient_id, $module_id);
        /*$non_billable_time = DB::select( DB::raw("select * from patients.sp_non_billabel_net_time($patient_id, $module_id, $component_id, $formonth, $foryear)"));*/
        $non_billable_time = DB::select("select * from patients.sp_non_billabel_net_time($patient_id, $module_id, $component_id, $formonth, $foryear)");
        $ps = QuestionnaireTemplate::where('add_to_patient_status', 1)->where('status', 1)->get('id');
        if (isset($ps[0]->id)) {
            $questionnaire_status = QuestionnaireTemplatesUsageHistory::where('template_id', $ps[0]->id)->where('patient_id', $patient_id)->latest()->first();
        } else {
            $questionnaire_status = '';
        }
        $careplan_finalization_date = PatientServices::latest_module($patient_id, $module_id);
        $patient = (Patients::where('id', $patient_id) ? Patients::where('id', $patient_id)->get() : "");
        $personal_notes = (PatientPersonalNotes::latest($patient_id, 'patient_id') ? PatientPersonalNotes::latest($patient_id, 'patient_id')->population() : "");
        $all_personal_notes = (PatientPersonalNotes::with('users')->where('patient_id', $patient_id)->get() ? PatientPersonalNotes::where('patient_id', $patient_id)->orderby('id', 'desc')->get() : '');
        $research_study = (PatientPartResearchStudy::latest($patient_id, 'patient_id') ? PatientPartResearchStudy::latest($patient_id, 'patient_id')->population() : "");
        $all_research_study = (PatientPartResearchStudy::with('users')->where('patient_id', $patient_id)->get() ? PatientPartResearchStudy::where('patient_id', $patient_id)->orderby('id', 'desc')->get() : '');
        if ($module_id == '3') {
            return view('Patients::patient.patient-status-right', ['patient' => $patient], compact('documents', 'patientdiagnosislastmodified', 'patientdiagnosis', 'chronicCondition', 'rpmDevices', 'medication', 'lastContactDate', 'ellapsedTime', 'currentEllapsedTime', 'previousEllapsedTime', 'questionnaire_status', 'personal_notes', 'all_personal_notes', 'research_study', 'all_research_study', 'non_billable_time', 'patient_assign_device', 'device_education_training', 'careplan_finalization_date'));
        } else if ($module_id == '2') {
            return view('Patients::patient.patient-status-right', ['patient' => $patient], compact('documents', 'patientdiagnosislastmodified', 'patientdiagnosis', 'chronicCondition', 'rpmDevices', 'medication', 'lastContactDate', 'ellapsedTime', 'currentEllapsedTime', 'previousEllapsedTime', 'questionnaire_status', 'personal_notes', 'all_personal_notes', 'research_study', 'all_research_study', 'non_billable_time', 'patient_assign_device', 'device_education_training', 'careplan_finalization_date'));
            //return view('Ccm::monthly-monitoring.patient-details',['patient'=>$patient], compact('chronicCondition','rpmDevices','medication','lastContactDate','ellapsedTime','currentEllapsedTime','previousEllapsedTime','personal_notes','research_study'));
        } else {
            return view('Patients::patient.traning-checklist', ['patient' => $patient], compact('documents', 'patientdiagnosislastmodified', 'patientdiagnosis', 'chronicCondition', 'rpmDevices', 'medication', 'patient_assign_device', 'device_education_training'));
        }
    }

    public function savePatientPartResearchStudy(PatientResearchstudyAddRequest $request)
    {
        $id           = sanitizeVariable($request->patient_id);
        $patient_id   = sanitizeVariable($request->patient_id);
        $currentMonth = date('m');
        $currentYear  = date('Y');
        //record time
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $module_id    = sanitizeVariable($request->module_id);
        $component_id = sanitizeVariable($request->component_id);
        $stage_id     = sanitizeVariable($request->stage_id);
        $billable     = 1;
        $form_name    = sanitizeVariable($request->form_name);
        $step_id      = 0;
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);

        $data = array(
            'patient_id' => $patient_id,
            'uid'        => $patient_id,
            'rec_date'   => Carbon::now(),
            'part_of_research_study'   => $request->part_of_research_study,
            'module_id'      => $module_id,
            'component_id'   => $request->component_id
        );

        $check_exist_for_month  = PatientPartResearchStudy::where('uid', $id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->exists();
        if ($check_exist_for_month == true) {
            $data['updated_by'] = session()->get('userid');
            $update_query = PatientPartResearchStudy::where('uid', $id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->orderBy('id', 'desc')->first()->update($data);
        } else {
            $data['created_by'] = session()->get('userid');
            $insert_query = PatientPartResearchStudy::create($data);
        }
        $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
        return response(['form_start_time' => $form_save_time]);
    }

    public function getPartnerdevicedId($partnerid)
    {
        $partnerid = sanitizeVariable($partnerid);
        // $physicians = Providers::all()->where("practice_id", $practice);
        $partnerid1 = PartnerDevices::where('partner_id', $partnerid)->get();

        return response()->json($partnerid1);
        // return $partnerid;
    }

    public function savePatientPersonalNotes(PatientPersonalNotesAddRequest $request)
    {
        // dd('dasdasdsadas');
        $id             = sanitizeVariable($request->patient_id);
        $patient_id     = sanitizeVariable($request->patient_id);
        $module_id      = sanitizeVariable($request->module_id);
        $personal_notes = sanitizeVariable($request->personal_notes);
        $currentMonth   = date('m');
        $currentYear    = date('Y');
        //record time
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $component_id = sanitizeVariable($request->component_id);
        $stage_id     = sanitizeVariable($request->stage_id);
        $billable     = 1;
        $form_name    = sanitizeVariable($request->form_name);
        $step_id      = 0;
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);


        $data = array(
            'patient_id'     => $patient_id,
            'uid'            => $patient_id,
            'rec_date'       => Carbon::now(),
            'personal_notes' => $personal_notes,
            'module_id'      => $module_id,
            'component_id'   => $request->component_id
        );
        $check_exist_for_month  = PatientPersonalNotes::where('uid', $id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->exists();
        if ($check_exist_for_month == true) {
            $data['updated_by'] = session()->get('userid');
            $update_query = PatientPersonalNotes::where('uid', $id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->orderBy('id', 'desc')->first()->update($data);
        } else {
            $data['created_by'] = session()->get('userid');
            $insert_query = PatientPersonalNotes::create($data);
        }
        $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
        return response(['form_start_time' => $form_save_time]);
    }

    public function savepatientdevices(MasterDevicesRequest $request)
    { //dd($request);
        $pdevices = array();
        $id                 = sanitizeVariable($request->uid);
        $rowid              = sanitizeVariable($request->idd);
        // dd($rowid);
        $patient_id         = sanitizeVariable($request->patient_id);
        $module_id          = sanitizeVariable($request->module_id);
        $currentMonth       = date('m');
        $currentYear        = date('Y');
        $form_name          = sanitizeVariable($request->form_name);
        $device_code        = sanitizeVariable($request->device_id);
        //$device_id          = sanitizeVariable($request->devices);
        $partners  = sanitizeVariable($request->partner_id);
        $partner_device_id           = sanitizeVariable($request->partner_devices_id);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);

        $query = PartnerDevices::where('id', $partner_device_id)->select('device_id')->get();
        foreach ($query as $row) {
            $device_id = $row->device_id;
        }
        //dd($device_id);
        $pdevices[]         = array('vid' => $device_id, 'pid' => $partners, 'pdid' => $partner_device_id);
        $vital_devices      = json_encode($pdevices);
        // dd($pdevices);

        $data = array(
            'patient_id'     => $patient_id,
            //partner_device_id
            //device_code,status, activation_date, deactivation_date, device_attr, created_by, updated_by, created_at, updated_at, order_id, mrn_no, source_id, vital_devices, device_id
            // 'created_at'     => Carbon::now(),
            'updated_at'     => Carbon::now(),
            // 'created_by' => session()->get('userid'),
            'updated_by' => session()->get('userid'),
            'device_code'    => $device_code,
            'device_id'      => $device_id,
            'partner_device_id' => $partner_device_id,
            'vital_devices' => $vital_devices,
            'partner_id'    => $partners
        );

        $checkquery  = PatientDevices::where('id', $rowid)->exists();
        // dd($checkquery);
        if ($checkquery == true) {
            $update = PatientDevices::where('id', $rowid)->update($data);
            //dd($update);
            if ($update == '1') {
                return ' Devices Succesfully update!';
            } else {
                return 'Something went wrong,Please try again.';
            }
        } else {
            $data['status'] = '1';
            $data['created_at'] = Carbon::now();
            $data['created_by'] = session()->get('userid');

            $insert = PatientDevices::insert($data);
            if ($insert == '1') {
                return ' Devices Succesfully add!';
            } else {
                return 'Something went wrong! Please try again.';
            }
        }
    }

    public function acticeinactivedevice(Request $request)
    {
        $id = sanitizeVariable($request->id);
        $data = PatientDevices::where('id', $id)->get();
        $status = $data[0]->status;
        if ($status == 1) {
            $status = array('status' => 0, 'updated_by' => session()->get('userid'));
            $update_query = PatientDevices::where('id', $id)->orderBy('id', 'desc')->update($status);
            return $update_query;
            // return "inactive";
            // return view('Holiday::holiday');
        } else {
            $status = array('status' => 1, 'updated_by' => session()->get('userid'));
            $update_query = PatientDevices::where('id', $id)->orderBy('id', 'desc')->update($status);
            return $update_query;
            // return "active";
            // return view('Holiday::holiday');
        }
    }

    public function populateDeviceData($id)
    {
        $id = sanitizeVariable($id);
        $data = DB::select("select pd.id,pd.patient_id,pd.device_code ,pd.updated_by ,pdd.device_name,p.name ,pdd.id as pdevice_id ,p.id as partner_id
            from patients.patient_devices pd 
            left join ren_core.users as u on pd.created_by = u.id 
            inner join ren_core.partners as p on p.id = pd.partner_id
            inner join ren_core.partner_devices_listing as pdd on pdd.id = pd.partner_device_id
            where pd.id  = '" . $id . "'");
        // dd($data);  inner join ren_core.devices as d  on d.id = pd.device_id
        $result['devices_form'] = $data;
        return $result;
    }

    public function getdeviceslist($id)
    { //dd("working");
        $id           = sanitizeVariable($id);
        $configTZ = config('app.timezone');
        $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');

        // $data = PatientDevices::where("patient_id", $id)->orderBy('created_at', 'desc')->get(['vital_devices->vid as vid','vital_devices->pid as pid','vital_devices->pdid as pdid', 'id as id','status as status', 'device_code as device_code','updated_by as updated_by','updated_at as updated_at']);
        $query = "select u.f_name,u.l_name,pd.id,pd.patient_id,pd.device_code ,pd.updated_by ,pdd.device_name,p.name , pd.status,
            to_char(pd.updated_at  at time zone '" . $configTZ . "' at time zone '" . $userTZ . "', 'MM-DD-YYYY HH24:MI:SS') as updated_at 
            from patients.patient_devices pd 
            left join ren_core.users as u on pd.updated_by=u.id
            inner join ren_core.partners as p on p.id = pd.partner_id
            left join ren_core.partner_devices_listing as pdd on pdd.id = pd.partner_device_id
            where patient_id  = '" . $id . "'";
        //dd($query);
        $data = DB::select($query);

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="editDevicesdata" title="Edit"><i class=" editform i-Pen-4"></i></a>';
                if ($row->status == 1) {
                    $btn = $btn . '<a href="javascript:void(0)" data-id="' . $row->id . '" class="change_device_status_active1" id="active"><i class="i-Yess i-Yes" title="Active"></i></a>';
                } else {
                    $btn = $btn . '<a href="javascript:void(0)" data-id="' . $row->id . '" class="change_device_status_deactive1" id="deactive"><i class="i-Closee i-Close"  title="Deactive"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getPatentDeviceVL(Request $request)
    {
        $id     = sanitizeVariable($request->patientid);
        $add_replace_device = sanitizeVariable($request->add_replace_device);
        //$patient_device = PatientDevices::where('patient_id', $id)->get(['vital_devices'])->latest()->first();
        $patient_device = PatientDevices::where('patient_id', $id)->where('status', 1)->latest()->first();

        $nin = array();
        if (isset($patient_device->vital_devices)) {
            $dv = $patient_device->vital_devices;
            $js = json_decode($dv);
            //print_r($nin);
            foreach ($js as $val) {
                if (isset($val->vid)) {
                    //echo '1';
                    array_push($nin, $val->vid);
                    //print_r($nin);
                }
            }
        }
        $device = "";
        if ($add_replace_device == 1) {
            $device = Devices::whereNotIn('id', $nin)->where('status', '1')->get();
        } else {
            $device = Devices::whereIn('id', $nin)->where('status', '1')->get();
        }

        return $device;
    }

    public function getPatentDevice(Request $request)
    {
        $id     = sanitizeVariable($request->patientid);
        $add_replace_device = sanitizeVariable($request->add_replace_device);
        //$patient_device = PatientDevices::where('patient_id', $id)->get(['vital_devices'])->latest()->first();
        $patient_device = PatientDevices::where('patient_id', $id)->where('status', 1)->latest()->first();

        $nin = array();
        if (isset($patient_device->vital_devices)) {
            $dv = $patient_device->vital_devices;
            $js = json_decode($dv);
            //print_r($nin);
            foreach ($js as $val) {
                if (isset($val->vid)) {
                    //echo '1';
                    array_push($nin, $val->vid);
                    //print_r($nin);
                }
            }
        }

        if ($add_replace_device == 1) {
            $device = Devices::whereNotIn('id', $nin)->where('status', '1')->get();
        } else {
            $device = Devices::whereIn('id', $nin)->where('status', '1')->get();
        }

        foreach ($device as $device) {

            echo '<li>
                <label class="forms-element checkbox checkbox-outline-primary"> 
                <input class="ckbox" name ="device_ids[' . $device->id . ']"  id ="' . $device->device_name . '" value="' . $device->id . '" type="checkbox" onChange=getDevice(this) >
                <span class="">' . $device->device_name . '</span><span class="checkmark"></span>             
                </label> 
                </li>';
        }
        //return $device;
    }

    // Patient Threshold added on 16-06-21
    //public function savePatientThreshold(PatientThresholdAddRequest $request){
    public function savePatientThreshold(Request $request)
    {    //dd('dadasdasdasd');
        $id             = sanitizeVariable($request->patient_id);
        $patient_id     = sanitizeVariable($request->patient_id);
        $bpmhigh        = sanitizeVariable($request->bpmhigh);
        $bpmlow         = sanitizeVariable($request->bpmlow);
        $glucosehigh    = sanitizeVariable($request->glucosehigh);
        $glucoselow     = sanitizeVariable($request->glucoselow);
        $diastolichigh  = sanitizeVariable($request->diastolichigh);
        $diastoliclow   = sanitizeVariable($request->diastoliclow);
        $systolichigh   = sanitizeVariable($request->systolichigh);
        $systoliclow    = sanitizeVariable($request->systoliclow);
        $oxsathigh      = sanitizeVariable($request->oxsathigh);
        $oxsatlow       = sanitizeVariable($request->oxsatlow);
        $temperaturehigh = sanitizeVariable($request->temperaturehigh);
        $temperaturelow = sanitizeVariable($request->temperaturelow);
        $weighthigh     = sanitizeVariable($request->weighthigh);
        $weightlow      = sanitizeVariable($request->weightlow);
        $spirometerfevhigh = sanitizeVariable($request->spirometerfevhigh);
        $spirometerfevlow = sanitizeVariable($request->spirometerfevlow);
        $spirometerpefhigh = sanitizeVariable($request->spirometerpefhigh);
        $spirometerpeflow = sanitizeVariable($request->spirometerpeflow);

        $form_name    = sanitizeVariable($request->form_name);
        $module_id      = sanitizeVariable($request->module_id);
        $personal_notes = sanitizeVariable($request->personal_notes);
        $currentMonth   = date('m');
        $currentYear    = date('Y');
        $device_code    = sanitizeVariable($request->device_code);

        //record time
        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $component_id = sanitizeVariable($request->component_id);
        $stage_id     = sanitizeVariable($request->stage_id);
        $step_id      = 0;
        $billable     = 1;
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        //         $count = PatientThreshold::select('bpmhigh','bpmlow','diastolichigh','diastoliclow','systolichigh','systoliclow','oxsathigh','oxsatlow','temperaturehigh','temperaturelow','glucosehigh','glucoselow')
        //                  ->where('patient_id',$patient_id)->whereRaw("bpmhigh > 0 or bpmlow > 0 or glucosehigh > 0 or glucoselow > 0 or oxsathigh > 0 or oxsatlow > 0 or diastolichigh > 0 or diastoliclow > 0 or temperaturehigh > 0 or temperaturelow > 0 or systolichigh > 0 or systoliclow > 0")->get();
        // dd($count);
        $data = array(
            'patient_id'     => $patient_id,
            'uid'            => $patient_id,
            'eff_date'        => Carbon::now(),
            'bpmhigh'         => $bpmhigh,
            'bpmlow'          => $bpmlow,
            'glucosehigh'     => $glucosehigh,
            'glucoselow'      => $glucoselow,
            'diastolichigh'   => $diastolichigh,
            'diastoliclow'    => $diastoliclow,
            'systolichigh'    => $systolichigh,
            'systoliclow'     => $systoliclow,
            'oxsathigh'       => $oxsathigh,
            'oxsatlow'        => $oxsatlow,
            'temperaturehigh' => $temperaturehigh,
            'temperaturelow'  => $temperaturelow,
            'weighthigh'      => $weighthigh,
            'weightlow'       => $weightlow,
            'spirometerfevhigh'      => $spirometerfevhigh,
            'spirometerfevlow'       => $spirometerfevlow,
            'spirometerpefhigh'      => $spirometerpefhigh,
            'spirometerpeflow'       => $spirometerpeflow
        );
        // dd($data);
        //--------------update patient thresold on api----------------------------
        $vital = array("BPM", "Glucose", "Diastolic", "Systolic", "Oxygen", "Thermometer");
        //dd(count($vital));
        for ($i = 0; $i < count($vital); $i++) {
            if ($vital[$i] == "BPM") {
                $max = $bpmhigh;
                $min = $bpmlow;
            } else if ($vital[$i] == "Glucose") {
                $max = $glucosehigh;
                $min = $glucoselow;
            } else if ($vital[$i] == "Diastolic") {
                $max = $diastolichigh;
                $min = $diastoliclow;
            } else if ($vital[$i] == "Systolic") {
                $max = $systolichigh;
                $min = $systoliclow;
            } else if ($vital[$i] == "Oxygen") {
                $max = $oxsathigh;
                $min = $oxsatlow;
            } else if ($vital[$i] == "Thermometer") {
                $max = $temperaturehigh;
                $min = $temperaturelow;
            }

            $GroupName = config('global.GroupNameInECG');
            $deviceid = $device_code;

            $apidata = '{
                  "vitalType": "' . $vital[$i] . '",
                  "maximum": "' . $max . '",
                  "minimum": "' . $min . '"
                }';


            ECGAPIController::getAuthorization();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, config('global.authurl') . 'groups/' . $GroupName . '/devices/' . $deviceid . '/thresholds');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                [
                    "Content-Type: application/json",
                    "Authorization: Bearer " . session()->get('TokenId')
                ]
            );
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $apidata);

            $response = curl_exec($ch);
            // dd($response);
            if (!$response) {
                return false;
            }

            $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($errno = curl_errno($ch)) {
                $error_message = curl_strerror($errno);
                return "cURL error ({$errno}):\n {$error_message}";
                die();
            }
            curl_close($ch);
        } // For loop end
        //---------------------------end--------------------------------------
        // print_r(explode(" ",$data));
        $check_data_exist  = PatientThreshold::where('patient_id', $id)->whereDate('created_at', '=', Carbon::today()->toDateString())->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->exists();
        if ($check_data_exist == true) {
            $data['updated_by'] = session()->get('userid');
            $update_query = PatientThreshold::where('patient_id', $id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->orderBy('id', 'desc')->first()->update($data);
        } else {
            $data['created_by'] = session()->get('userid');
            $insert_query = PatientThreshold::create($data);
        }
        $record_time = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id, $step_id, $form_name, $form_start_time, $form_save_time);
        return response(['form_start_time' => $form_save_time]);
    }


    //     public function savePatientThresholdold(PatientThresholdAddRequest $request){
    //         $id             = sanitizeVariable($request->uid);
    //         $patient_id     = sanitizeVariable($request->patient_id);
    //         $bpmhigh        = sanitizeVariable($request->bpmhigh);
    //         $bpmlow         = sanitizeVariable($request->bpmlow);
    //         $glucosehigh    = sanitizeVariable($request->glucosehigh);
    //         $glucoselow     = sanitizeVariable($request->glucoselow);
    //         $diastolichigh  = sanitizeVariable($request->diastolichigh);
    //         $diastoliclow   = sanitizeVariable($request->diastoliclow);
    //         $systolichigh   = sanitizeVariable($request->systolichigh);
    //         $systoliclow    = sanitizeVariable($request->systoliclow);
    //         $oxsathigh      = sanitizeVariable($request->oxsathigh);
    //         $oxsatlow       = sanitizeVariable($request->oxsatlow);
    //         $temperaturehigh= sanitizeVariable($request->temperaturehigh);
    //         $temperaturelow = sanitizeVariable($request->temperaturelow);
    //         $module_id      = sanitizeVariable($request->module_id);
    //         $personal_notes = sanitizeVariable($request->personal_notes);
    //         $currentMonth   = date('m');
    //         $currentYear    = date('Y');
    //         //record time
    //         $start_time   = sanitizeVariable($request->start_time);
    //         $end_time     = sanitizeVariable($request->end_time);
    //         $component_id = sanitizeVariable($request->component_id);
    //         $stage_id     = 0;
    //         $billable     = 1;

    // //         $count = PatientThreshold::select('bpmhigh','bpmlow','diastolichigh','diastoliclow','systolichigh','systoliclow','oxsathigh','oxsatlow','temperaturehigh','temperaturelow','glucosehigh','glucoselow')
    // //                  ->where('patient_id',$patient_id)->whereRaw("bpmhigh > 0 or bpmlow > 0 or glucosehigh > 0 or glucoselow > 0 or oxsathigh > 0 or oxsatlow > 0 or diastolichigh > 0 or diastoliclow > 0 or temperaturehigh > 0 or temperaturelow > 0 or systolichigh > 0 or systoliclow > 0")->get();
    // // dd($count);
    //         $data = array(
    //             'patient_id'     => $patient_id,
    //             'uid'            => $patient_id,
    //            'eff_date'        => Carbon::now(),
    //            'bpmhigh'         => $bpmhigh,
    //            'bpmlow'          => $bpmlow,
    //            'glucosehigh'     => $glucosehigh,
    //            'glucoselow'      => $glucoselow,
    //            'diastolichigh'   => $diastolichigh,
    //            'diastoliclow'    => $diastoliclow,
    //            'systolichigh'    => $systolichigh,
    //            'systoliclow'     => $systoliclow,
    //            'oxsathigh'       => $oxsathigh,
    //            'oxsatlow'        => $oxsatlow,
    //            'temperaturehigh' => $temperaturehigh,
    //            'temperaturelow'  => $temperaturelow
    //         );
    //         // dd($data);
    // //--------------update patient thresold on api----------------------------
    //         // $vital = array("BPM","Glucose","Diastolic","Systolic","Oxygen","Thermometer");

    //         // for($i=0;$i>=count($vital);$i++){

    //         //     if($vital[$i]=="BPM"){
    //         //         $max=$bpmhigh;
    //         //         $min=$bpmlow;
    //         //     }else if($vital[$i]=="Glucose"){
    //         //          $max=$glucosehigh;
    //         //         $min=$glucoselow;
    //         //     }else if($vital[$i]=="Diastolic"){
    //         //          $max=$diastolichigh;
    //         //         $min=$diastoliclow;
    //         //     }else if($vital[$i]=="Systolic"){
    //         //          $max=$systolichigh;
    //         //         $min=$systoliclow;
    //         //     }else if($vital[$i]=="Oxygen"){
    //         //          $max=$oxsathigh;
    //         //         $min=$oxsatlow;
    //         //     }else if($vital[$i]=="Thermometer"){
    //         //          $max=$temperaturehigh;
    //         //         $min=$temperaturelow;
    //         //     }

    //         //  $GroupName=config('global.GroupNameInECG');
    //         //  $deviceid='';

    //         //  $apidata='{
    //         //           "vitalType": "'.$vital.'",
    //         //           "maximum": "'.$max.'",
    //         //           "minimum": "'.$min.'"
    //         //         }';
    //         //  ECGAPIController::getAuthorization();
    //         // $ch = curl_init();
    //         //       curl_setopt($ch, CURLOPT_URL, 'https://dev.ecg-api.com/groups/'.$GroupName.'/devices/'.$deviceid.'/thresholds');
    //         //       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //         //        curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //         //        "Content-Type: application/json",
    //         //        "Authorization: Bearer ".session()->get('TokenId')]
    //         //      );
    //         //       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    //         //       curl_setopt($ch, CURLOPT_POSTFIELDS,$apidata);

    //         //         $response = curl_exec($ch);

    //         //         if (!$response) 
    //         //         {
    //         //             return false;
    //         //         }                

    //         //          $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    //         //          if($errno = curl_errno($ch)) {
    //         //           $error_message = curl_strerror($errno);
    //         //           return "cURL error ({$errno}):\n {$error_message}";
    //         //           die();
    //         //       }
    //         //        curl_close($ch);
    //         // } // For loop end
    // //---------------------------end--------------------------------------
    //         // print_r(explode(" ",$data));
    //         $check_data_exist  = PatientThreshold::where('patient_id', $id)->whereDate('created_at', '=', Carbon::today()->toDateString())->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->exists();
    //         if ($check_data_exist == true) {
    //             $data['updated_by'] = session()->get('userid');
    //             $update_query = PatientThreshold::where('patient_id', $id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->orderBy('id', 'desc')->first()->update($data);
    //         } else {
    //             $data['created_by'] = session()->get('userid');
    //             $insert_query = PatientThreshold::create($data);
    //         }
    //         $record_time  = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $component_id, $stage_id, $billable, $patient_id);
    //     }

    public function viewMorePersonalNotes($id)
    {
        $id = sanitizeVariable($id);
        $data = PatientPersonalNotes::with('users')->where("patient_id", $id)->orderBy('created_at', 'desc')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function viewMorePartOfResearchStudy($id)
    {
        $id = sanitizeVariable($id);
        $data = PatientPartResearchStudy::with('users')->where("patient_id", $id)->orderBy('created_at', 'desc')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function deviceTraning($id)
    {
        /* $license =$_GET['org_id'];*/
        $softwarep = ContentTemplate::where('id', 122)->get();
        $usage  = ContentTemplate::where('id', 123)->get();
        $traning  = ContentTemplate::where('id', 124)->get();
        $patient = Patients::where('id', $id)->get();
        return view('Patients::deviceTraning.device-traning', compact('softwarep', 'usage', 'traning', 'patient'));
    }

    //Device Traning viewsiew
    public function rpmDevicelist()
    {
        return view('Patients::deviceTraning.patient-device-list');
    }


    public function rpmfetchPatients(Request $request)
    {
        if ($request->ajax()) {
            $data = Patients::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="device-traning/' . $row->id . '" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('deviceTraning/device');
    }

    public function getActiveRelationshipList()
    {
        $relativeList = [];
        $relativeList = PatientFamily::activeRelationship();
        return response()->json($relativeList);
    }

    public function practicePatients($practice, $moduleId)
    {
        $practice = sanitizeVariable($practice);
        $moduleId = sanitizeVariable($moduleId);
        $patients = [];
        $module = Module::where('id', $moduleId)->where('patients_service', 1)->count();
        if ($practice == 'null' &&  $module > 0) {
            $patients = DB::select("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  
                from patients.patient as p, patients.patient_services as ps, patients.patient_providers
                as q where p.id = ps.patient_id and ps.module_id ='" . $moduleId . "' 
                and p.id = q.patient_id
                and q.is_active=1 order by p.fname ");
        } else {
            if ($practice != 'null' && $module > 0) {
                $patients = DB::select("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  
                    from patients.patient p, patients.patient_services ps, patients.patient_providers
                    as q where p.id = ps.patient_id and ps.module_id = '" . $moduleId . "' 
                    and p.id = q.patient_id  and q.practice_id = '" . $practice . "'
                    and q.is_active=1 order by p.fname ");
            } else {
                $patients = DB::select("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  
                    from patients.patient p, patients.patient_providers
                    as q where  p.id = q.patient_id and q.practice_id = '" . $practice . "'
                    and q.is_active=1 order by p.fname ");
            }
        }
        return response()->json($patients);
    }



    public function practicePatientsAssignDevice($practice, $moduleId)
    {
        $practice = sanitizeVariable($practice);
        $moduleId = sanitizeVariable($moduleId);
        $patients = [];
        $module = Module::where('id', $moduleId)->where('patients_service', 1)->count();
        if ($practice == 'null' &&  $module > 0) {

            $patients = DB::select("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  
                from patients.patient as p, patients.patient_services as ps, patients.patient_providers as q, patients.patient_devices as d 
                where p.id = ps.patient_id and ps.module_id ='" . $moduleId . "' 
                and p.id = q.patient_id and p.id = d.patient_id and d.status=1
                and q.is_active=1 order by p.fname ");
        } else {
            if ($practice != 'null' && $module > 0) {

                $patients = DB::select("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  
                    from patients.patient p, patients.patient_services ps, patients.patient_providers as q, patients.patient_devices as d
                     where p.id = ps.patient_id and ps.module_id = '" . $moduleId . "' 
                    and p.id = q.patient_id  and q.practice_id = '" . $practice . "' and p.id = d.patient_id and d.status=1
                    and q.is_active=1 order by p.fname ");
            } else {

                $patients = DB::select("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  
                    from patients.patient p, patients.patient_providers as q , patients.patient_devices as d
                     where  p.id = q.patient_id and q.practice_id = '" . $practice . "' and p.id = d.patient_id and d.status=1
                    and q.is_active=1 order by p.fname ");
            }
        }
        return response()->json($patients);
    }


    /*  public function practicePatientsNew($practice) //modified by ashvini on 10th nov 2020
    {
        $patients = [];
        $cid = session()->get('userid');
        $usersdetails = Users::where('id', $cid)->get();
        $roleid = $usersdetails[0]->role;

        if ($practice == "null" || $practice == 0) {
            if ($roleid == 2) {
                //admin
                $patients = DB::select("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  
                from patients.patient p
                inner join patients.patient_services ps  on p.id = ps.patient_id
                where  1=1 order by p.fname ");
            } else if ($roleid == 5) {
                //caremanager
                $patients = DB::select("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  
                from patients.patient p 
                INNER JOIN task_management.user_patients up
                on p.id = up.patient_id and up.status = 1               
                inner join ren_core.users u  on u.id = up.user_id
                inner join patients.patient_providers pp                 
               on p.id = pp.patient_id and pp.is_active = 1 and pp.provider_type_id = 1
               inner join patients.patient_services ps 
               on p.id = ps.patient_id                
               where    up.user_id = '" . $cid . "' order by p.fname ");
            } else {

                //teamlead, senior care manager and manager
                $patients = DB::select("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  
                from patients.patient p 

               inner join patients.patient_providers pp                 
               on p.id = pp.patient_id and pp.is_active =1 and pp.provider_type_id= 1
               inner join patients.patient_services ps 
               on p.id = ps.patient_id                
               where 
               pp.practice_id in (SELECT practice_id FROM ren_core.user_practices where user_id = '" . $cid . "') order by p.fname ");
            }
        } else {
            if ($roleid == 2) {
                $patients = DB::select("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  
                from patients.patient p
                inner join patients.patient_services ps
                on   p.id = ps.patient_id 
                inner join patients.patient_providers pp                 
                on p.id = pp.patient_id and pp.provider_type_id = 1 and pp.is_active = 1
                where    pp.practice_id = '" . $practice . "' order by p.fname ");
            } else if ($roleid == 5) {
                $patients = DB::select("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  
                from patients.patient p
                INNER JOIN task_management.user_patients up
                on p.id = up.patient_id and up.status = 1               
                inner join ren_core.users u  on u.id = up.user_id
                inner join patients.patient_services ps
                on   p.id = ps.patient_id 
                inner join patients.patient_providers  pp                 
                on p.id = pp.patient_id and pp.is_active = 1 and pp.provider_type_id=1
                where  pp.practice_id = '" . $practice . "' and up.user_id = '" . $cid . "' order by p.fname ");
            } else {
                $patients = DB::select("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  
                from patients.patient p 

               inner join patients.patient_providers pp
			   on p.id = pp.patient_id and pp.is_active=1 and pp.provider_type_id = 1
			   and pp.practice_id = '" . $practice . "'
               where    
               pp.practice_id in (SELECT practice_id FROM ren_core.user_practices where user_id = '" . $cid . "') order by p.fname ");
            }
        }

        return response()->json($patients);
    } */


    //optimized query and cached for 24Hrs
    public function practicePatientsNew($practice)
    {

        $cid = session()->get('userid');
        $usersDetails = Users::where('id', $cid)->first();
        $roleid = $usersDetails->role;

        $query = DB::table('patients.patient')
            ->select('patients.patient.id', 'patients.patient.fname', 'patients.patient.lname', 'patients.patient.mname', 'patients.patient.dob', 'patients.patient.mob')
            ->distinct()
            ->join('patients.patient_services', 'patients.patient.id', '=', 'patients.patient_services.patient_id')
            ->join('patients.patient_providers as pp', 'patients.patient.id', '=', 'pp.patient_id')
            ->where('pp.provider_type_id', 1)
            ->where('pp.is_active', 1)
            ->orderBy('patients.patient.fname');

        if ($practice != "null" && $practice != 0) {
            $query->where('pp.practice_id', $practice);
        }

        if ($roleid == 5) {
            $query->join('task_management.user_patients as up', function ($join) use ($cid) {
                $join->on('patients.patient.id', '=', 'up.patient_id')
                    ->where('up.status', 1)
                    ->where('up.user_id', $cid);
            });
        } elseif ($roleid != 2) {
            $query->whereIn('pp.practice_id', function ($subQuery) use ($cid) {
                $subQuery->select('practice_id')->from('ren_core.user_practices')->where('user_id', $cid);
            });
        }

        $patients = $query->get();
        return response()->json($patients);
    }

    public function assignpatientlist($practice)
    {
        $cid = session()->get('userid');
        $usersdetails = Users::where('id', $cid)->get();
        $roleid = $usersdetails[0]->role;

        if ($practice == "null" || $practice == 0) {
            $patients = DB::table('patients.patient as p')
                ->select('p.id', 'p.fname', 'p.lname', 'p.mname', 'p.dob', 'p.mob')
                ->distinct()
                ->join('task_management.user_patients as up', function ($join) {
                    $join->on('p.id', '=', 'up.patient_id')
                        ->where('up.status', '=', 1);
                })
                ->join('ren_core.users as u', 'u.id', '=', 'up.user_id')
                ->join('patients.patient_providers as pp', function ($join) {
                    $join->on('p.id', '=', 'pp.patient_id')
                        ->where('pp.is_active', '=', 1)
                        ->where('pp.provider_type_id', '=', 1);
                })
                ->join('patients.patient_services as ps', 'p.id', '=', 'ps.patient_id')
                ->where('up.user_id', $cid)
                ->orderBy('p.fname')
                ->get();
        } else {
            $patients = DB::table('patients.patient as p')
                ->select('p.id', 'p.fname', 'p.lname', 'p.mname', 'p.dob', 'p.mob')
                ->distinct()
                ->join('task_management.user_patients as up', function ($join) {
                    $join->on('p.id', '=', 'up.patient_id')
                        ->where('up.status', '=', 1);
                })
                ->join('ren_core.users as u', 'u.id', '=', 'up.user_id')
                ->join('patients.patient_services as ps', 'p.id', '=', 'ps.patient_id')
                ->join('patients.patient_providers as pp', function ($join) use ($practice) {
                    $join->on('p.id', '=', 'pp.patient_id')
                        ->where('pp.is_active', '=', 1)
                        ->where('pp.provider_type_id', '=', 1)
                        ->where('pp.practice_id', '=', $practice);
                })
                ->where('up.user_id', $cid)
                ->orderBy('p.fname')
                ->get();
        }

        return response()->json($patients);
    }

    //created by ashvini 12 jan 2021  
    public function practiceRPMPatients($practice)
    {
        $patients = [];
        $practice = sanitizeVariable($practice);
        // dd($practice); 
        $patients = \DB::table('patients.patient as p')
            ->join('patients.patient_services as ps', 'p.id', '=', 'ps.patient_id')
            ->join('patients.patient_providers as pp', 'p.id', '=', 'pp.patient_id')
            ->where('ps.module_id', 2)
            ->where('pp.is_active', 1)
            ->where('pp.practice_id', $practice)
            ->select('p.id', 'p.fname', 'p.lname', 'p.mname', 'p.dob', 'p.mob')
            ->distinct('p.id')->get();

        return response()->json($patients);
    }

    public function EmrOnPractice(Request $request)
    {
        $patient_id = sanitizeVariable($request->route('patientid'));
        $practice_id = sanitizeVariable($request->route('practiceid'));
        $emr = [];
        if ($practice_id == null) {
            if ($patient_id == "null" || $patient_id == "") {
                $emr =  patientProvider::where("provider_type_id", 1)->where("is_active", 1)->get();
            } else {
                $emr =  patientProvider::where('patient_id', $patient_id)->where("provider_type_id", 1)->where("is_active", 1)->get();
            }
        } else {
            if ($patient_id == "null" || $patient_id == "") {
                $emr =  patientProvider::where("practice_id", $practice_id)->where("provider_type_id", 1)->where("is_active", 1)->get();
            } else {
                $emr =  patientProvider::where("practice_id", $practice_id)->where('patient_id', $patient_id)->where("provider_type_id", 1)->where("is_active", 1)->get();
            }
        }
        return response()->json($emr);
    }

    public function practiceOnEmr($emr)
    {
        $emr = sanitizeVariable($emr);
        // dd($emr);
        $practice = [];
        if ($emr == null) {
            $practice =  DB::table('ren_core.practices')->get();
            foreach ($practice as $p) {

                $id = $p->id;

                $pat = \DB::select("select count(distinct p.id) from patients.patient p 
                left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                from patients.patient_providers pp1  
                inner join (select patient_id, max(id) as max_pat_practice 
                from patients.patient_providers  where provider_type_id = 1  and is_active =1  
                group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
                on p.id = pp.patient_id
                left join ren_core.practices rp on rp.id = pp.practice_id where rp.id = " . $id . " ");


                $patientcount = $pat[0]->count;

                $p->count  = $patientcount;
                // dd($p);

            }
        } else {
            $practice = DB::table('ren_core.practices')
                ->whereIn('id', DB::table('patients.patient_providers')->where('practice_emr', $emr)->pluck('practice_id'))
                ->get();
            foreach ($practice as $p) {

                $id = $p->id;

                $pat = \DB::select("select count(distinct p.id) from patients.patient p 
            left join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
            from patients.patient_providers pp1  
            inner join (select patient_id, max(id) as max_pat_practice 
            from patients.patient_providers  where provider_type_id = 1  and is_active =1  
            group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice) pp                 
            on p.id = pp.patient_id
            left join ren_core.practices rp on rp.id = pp.practice_id where rp.id = " . $id . " ");


                $patientcount = $pat[0]->count;

                $p->count  = $patientcount;
                // dd($p);

            }
            //dd($practice);
        }
        return response()->json($practice);
    }

    public function patientOnEmr($emr, $practiceId, $module_id)
    {
        $providerpatients = [];
        $emr = sanitizeVariable($emr);
        $practiceId = sanitizeVariable($practiceId);
        $module_id = sanitizeVariable($module_id);

        if ($practiceId == 'null') {
            //dd($practiceId);
            $patients = DB::select("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  
                from patients.patient p
                inner join patients.patient_services ps
                on   p.id = ps.patient_id 
                inner join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                from patients.patient_providers pp1
                inner join (select patient_id, max(id) as max_pat_practice  
                from patients.patient_providers  where provider_type_id = 1 
                group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice
                and pp1.provider_type_id = 1 ) pp                 
                on p.id = pp.patient_id
                where ps.module_id =  '" . $module_id . "' and  pp.practice_emr ='" . $emr . "' order by p.fname ");
            return response()->json($patients);
        } else {
            // dd($practiceId);
            $patients = DB::select("select distinct p.id, p.fname, p.lname,p.mname, p.dob, p.mob  
                from patients.patient p
                inner join patients.patient_services ps
                on   p.id = ps.patient_id 
                inner join (select pp1.patient_id , pp1.practice_id, pp1.provider_id, pp1.practice_emr 
                from patients.patient_providers pp1
                inner join (select patient_id, max(id) as max_pat_practice  
                from patients.patient_providers  where provider_type_id = 1 
                group by patient_id  ) as pp2 on pp1.patient_id = pp2.patient_id and pp1.id = pp2.max_pat_practice
                and pp1.provider_type_id = 1 ) pp                 
                on p.id = pp.patient_id
                where ps.module_id =  '" . $module_id . "' and  pp.practice_emr ='" . $emr . "' and pp.practice_id = '" . $practiceId . "' order by p.fname ");
            return response()->json($patients);
        }
    }
    public function providerRPMPatients($providerid)
    {
        $providerpatients = [];
        $providerid = sanitizeVariable($providerid);
        $providerpatients = \DB::table('patients.patient as p')
            ->join('patients.patient_services as ps', 'p.id', '=', 'ps.patient_id')
            ->join('patients.patient_providers as pp', 'p.id', '=', 'pp.patient_id')
            ->where('ps.module_id', 2)
            ->where('pp.is_active', 1)
            ->where('pp.provider_id', $providerid)
            ->select('p.id', 'p.fname', 'p.lname', 'p.mname', 'p.dob', 'p.mob')
            ->distinct('p.id')->get();
        return response()->json($providerpatients);
    }

    public function fetchPatientDeviceDetails($id)
    {
        $id = sanitizeVariable($id);
        $data = Patients::all()->where("id", $id);
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="device-traning/' . $row->id . '" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function Patientprofileimage(PatientProfileImage $request)
    {
        if (isset($_FILES) && !empty($_FILES)) {
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $fname = $request->input('fname');
                $lname = $request->input('lname');
                $original_name = preg_replace("/\s+/", "_", $image->getClientOriginalName());
                $file_extension = $image->getClientOriginalExtension();
                $new_name = time() . '_' . $fname . "_" . $lname . "." . $file_extension;
                $image = $image->move(public_path('patientProfileImage'), $new_name);
                $img_path = '/patientProfileImage/' . $new_name;
                return $img_path;
            }
        }
    }

    public function patientRegistration(PatientAddRequest $request)
    {

        $review   = "0";
        $uid      = "0";
        $img_path = "";
        $fname    = sanitizeVariable($request->fname);
        $mname = sanitizeVariable($request->mname);
        $lname    = sanitizeVariable($request->lname);
        $email    = sanitizeVariable($request->email);
        $dob      = sanitizeVariable($request->dob);
        $fin_number = sanitizeVariable($request->fin_number);
        $home_number = sanitizeVariable($request->home_number);
        $mob = sanitizeVariable($request->mob);
        $contact_preference_calling   = sanitizeVariable($request->contact_preference_calling);
        $contact_preference_sms = sanitizeVariable($request->contact_preference_sms);
        $contact_preference_email = sanitizeVariable($request->contact_preference_email);
        $contact_preference_letter = sanitizeVariable($request->contact_preference_letter);
        $no_email                  = sanitizeVariable($request->no_email);
        $preferred_contact         = sanitizeVariable($request->preferred_contact);
        $profile_img              = sanitizeVariable($request->image_path);
        $country_code               = sanitizeVariable($request->country_code);
        $secondary_country_code     = sanitizeVariable($request->secondary_country_code);
        $gender                     = sanitizeVariable($request->gender);

        $marital_status             = sanitizeVariable($request->marital_status);
        $education                  = sanitizeVariable($request->education);
        $ethnicity                  = sanitizeVariable($request->ethnicity);
        $occupation                 = sanitizeVariable($request->occupation);
        $occupation_description     = sanitizeVariable($request->occupation_description);
        $other_contact_name         = sanitizeVariable($request->other_contact_name);
        $other_contact_relationship = sanitizeVariable($request->other_contact_relationship);
        $other_contact_phone_number = sanitizeVariable($request->other_contact_phone_number);
        $other_contact_email        = sanitizeVariable($request->other_contact_email);
        $military_status            = sanitizeVariable($request->military_status);
        $ethnicity_2                = sanitizeVariable($request->ethnicity_2);
        $add_1                      = sanitizeVariable($request->add_1);
        $add_2                      = sanitizeVariable($request->add_2);
        $state                      = sanitizeVariable($request->state);
        $zipcode                    = sanitizeVariable($request->zipcode);
        $city                       = sanitizeVariable($request->city);
        $poa_first_name             = sanitizeVariable($request->poa_first_name);
        $poa_last_name              = sanitizeVariable($request->poa_last_name);
        $poa_relationship           = sanitizeVariable($request->poa_relationship);
        $poa_phone_2                = sanitizeVariable($request->poa_phone_2);
        $poa_email                  = sanitizeVariable($request->poa_email);
        $poa                       = sanitizeVariable($request->poa);
        $primary_cell_phone         = sanitizeVariable($request->primary_cell_phone);
        $secondary_cell_phone       = sanitizeVariable($request->secondary_cell_phone);
        $consent_to_text            = sanitizeVariable($request->consent_to_text);
        //$entrollment_form            = sanitizeVariable($request->entrollment_form);
        $entrollment_form            = sanitizeVariable($request->entrollment_from);
        $currentMonth       = date('m');
        $currentYear        = date('Y');
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);

        if ($military_status == '0') {
            $vtemplate = sanitizeVariable(json_encode($request->question['question']));
        } else {
            $vtemplate = null;
        }


        $data     = DB::select("SELECT patients.generate_patient_uid('" . $fname . "' , '" . $dob . "', '" . $lname . "') AS id");
        $uid      = $data[0]->id;
        $patient_id = $uid;
        DB::beginTransaction();
        try {
            $patientfin = array(
                'patient_id'    => $patient_id,
                'status'        => '1',
                'fin_number'    => $fin_number
            );
            if ($fin_number != null || $fin_number != '') {
                $check_exist_for_month  = PatientFinNumber::where('patient_id', $patient_id)->whereMonth('updated_at', $currentMonth)->whereYear('updated_at', $currentYear)->exists();

                if ($check_exist_for_month == true) {
                    $patientfin['updated_at'] = Carbon::now();
                    $patientfin['updated_by'] = session()->get('userid');
                    $update_query = PatientFinNumber::where('patient_id', $patient_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->orderBy('id', 'desc')->first()->update($patientfin);
                } else {
                    $patientfin['created_at'] = Carbon::now();
                    $patientfin['created_by'] = session()->get('userid');
                    $insert_query = PatientFinNumber::create($patientfin);
                }
            }

            $patient_data = array(
                'id'                         => $uid,
                'fname'                      => $fname,
                'mname'                      => $mname,
                'lname'                      => $lname,
                'email'                      => $email,
                'uid'                        => $uid,
                'home_number'                => $home_number,
                'mob'                        => $mob,
                'dob'                        => $dob,
                'fin_number'                 => $fin_number,
                'review'                     => $review,
                'org_id'                     => session()->get('org_id'),
                'created_by'                 => session()->get('userid'),
                'contact_preference_calling' =>  $contact_preference_calling,
                'contact_preference_sms'     =>  $contact_preference_sms,
                'contact_preference_email'   => $contact_preference_email,
                'contact_preference_letter'  => $contact_preference_letter,
                'no_email'                   => $no_email,
                'preferred_contact'          => $preferred_contact,
                'profile_img'                => $profile_img,
                'country_code'               => $country_code,
                'secondary_country_code'     => $secondary_country_code,
                'status'                     => 1,
                'created_by'                 => session()->get('userid'),
                'primary_cell_phone'         => $primary_cell_phone,
                'secondary_cell_phone'       => $secondary_cell_phone,
                'consent_to_text'            => $consent_to_text,
                'entrollment_form'          => $entrollment_form
            );
            $patient    = Patients::createFromRequest($patient_data);
            $patient_id = $patient->id;
            //////////////// Demographics//////////////////////////
            $patient_demographics_data = array(
                'gender'                     => $gender,
                'marital_status'             => $marital_status,
                'education'                  => $education,
                'ethnicity'                  => $ethnicity,
                'occupation'                 => $occupation,
                'height'                     => '',
                'weight'                     => '',
                'employer'                   => '',
                'occupation_description'     => $occupation_description,
                'other_contact_name'         => $other_contact_name,
                'other_contact_relationship' => $other_contact_relationship,
                'other_contact_phone_number' => $other_contact_phone_number,
                'other_contact_email'        => $other_contact_email,
                'military_status'            => $military_status,
                'ethnicity_2'                => $ethnicity_2,
                'uid'                        => $uid,
                'patient_id'                 => $patient_id,
                'created_by'                 => session()->get('userid'),
                'template'                   => $vtemplate
            );
            $patient_demographics = PatientDemographics::create($patient_demographics_data);

            $patient_address_data = array(
                'add_1'              => $add_1,
                'add_2'              => $add_2,
                'state'              => $state,
                'zipcode'            => $zipcode,
                'city'               => $city,
                'uid'                => $uid,
                'patient_id'         => $patient_id,
                'created_by'         => session()->get('userid')
            );
            $patient_address = PatientAddress::create($patient_address_data);

            $patient_poa_data = array(
                'poa_first_name'        => $poa_first_name,
                'poa_last_name'         => $poa_last_name,
                'poa_age'               => 0,
                'poa_relationship'      => $poa_relationship,
                'poa_mobile'            => '0000',
                'poa_phone_2'           => $poa_phone_2,
                'poa_email'             => $poa_email,
                'poa'                   => $poa,
                'uid'                   => $uid,
                'patient_id'            => $patient_id,
                'created_by'            => session()->get('userid')
            );
            $patient_poa = PatientPoa::create($patient_poa_data);

            if (isset($request->enroll)) {
                $enrollarr = sanitizeVariable($request->enroll);
                $j = sizeof($request->enroll);

                foreach ($enrollarr as $key => $value) {
                    if ($value) {
                        $patient_enroll_data = array(
                            'uid'               => $uid,
                            'module_id'         =>  $key,
                            'date_enrolled'     => date("Y-m-d"),
                            'created_by'        => session()->get('userid'),

                        );
                        if ($request->has('edit')) {
                            $patient_id = sanitizeVariable($request->patient_id);
                            $patientServiceExist = PatientServices::where('patient_id', $patient_id)->where('module_id', $key)->get();
                            if ($patientServiceExist->count() == 0) {
                                $patient_enroll_data['patient_id'] = $patient_id;
                                $patient_enroll_data['created_by'] = session()->get('userid');
                                continue;
                            } else {
                                if ($patientServiceExist[0]->status == 0) {
                                    $patient_enroll_data = array(
                                        'status'     => 1,
                                        'updated_by' => session()->get('userid')
                                    );
                                }
                                continue;
                            }
                        } else {
                            $patient_enroll_data['patient_id'] = $patient_id;
                            $patient_enroll_data['created_by'] = session()->get('userid');
                            continue;
                        }
                    } else {
                        if ($request->has('edit')) {
                            $patientServiceExist = PatientServices::where('patient_id', $request->patient_id)->where('module_id', $key)->get();
                            if ($patientServiceExist) {
                                $patient_enroll_data = array(
                                    'status'               => 0,
                                    'updated_by' => session()->get('userid')
                                );
                                continue;
                            }
                        }
                    }
                }
            }
            $ins_provider = sanitizeVariable($request->ins_provider);
            $ins_id       = sanitizeVariable($request->ins_id);
            $ins_type     = sanitizeVariable($request->ins_type);
            $p = sanitizeVariable($request->patient_id);
            $pr = sanitizeVariable($request->provider_name);
            $prac = sanitizeVariable($request->practice_id);
            $pracemr = sanitizeVariable($request->practice_emr);


            foreach ($ins_type as $key => $value) {
                if (isset($ins_provider[$key]) && ($ins_provider[$key] != "") && $value != '') {
                    $patient_insurance_data = array(
                        'ins_code'      => '0000',
                        'ins_type'      => $value,
                        'ins_id'        => $ins_id[$key],
                        'ins_provider'  => $ins_provider[$key],
                        'ins_plan'      => "",
                        'uid'           => $uid,
                        'created_by'    => session()->get('userid')
                    );
                    if ($request->has('edit')) {
                        PatientInsurance::where('patient_id', $p)->whereIn('ins_type', [$value])->delete();
                        $patient_insurance_data['updated_by'] = session()->get('userid');
                    }
                    // else{
                    $patient_insurance_data['patient_id'] = $patient_id;
                    $patient_insurance_data['created_by'] = session()->get('userid');
                    // $patient_poa = PatientInsurance::createFromRequest($patient_insurance_data);                  
                    $patient_insurance = PatientInsurance::create($patient_insurance_data);
                    // }
                }
            }
            if ($request->provider_id == '0') {
                $insert_provider_id = array(
                    'name' => $pr,
                    'practice_id' => $prac,
                    'provider_type_id' => 1,
                    'created_by' => session()->get('userid')
                );
                $new_provider_id = Providers::create($insert_provider_id);
                $provider_id = $new_provider_id->id;
            } else {
                $provider_id = $request->provider_id;
            }
            $patient_physician_data = array(
                'provider_id'               => $provider_id,
                'provider_type_id'          => 1, // for pcp provider
                'provider_subtype_id'       => NULL,
                'practice_id'               => $prac,
                'address'                   => NULL,
                'phone_no'                  => '',
                'last_visit_date'           => NULL,
                'review'                    => NULL,
                'provider_name'             => NULL,
                'practice_emr'              => $pracemr,
                'is_active'                 => 1,
                'status'                    => 1
            );

            if ($request->has('edit')) {
                $PatientProvider = PatientProvider::where('patient_id', $patient_id)->where('is_active', 1)->where('provider_type_id', 1)->orderby('id', 'desc')->exists(); //get();
                if ($PatientProvider == true) {
                    $is_Active['updated_by'] = session()->get('userid');
                    $is_Active['is_active'] = 0;
                    $update_isActive = PatientProvider::where('patient_id', $patient_id)->where('is_active', 1)->where('provider_type_id', 1)->update($is_Active);
                    $patient_physician_data['patient_id'] = $patient_id;
                    $patient_physician_data['created_by'] = session()->get('userid');
                    PatientProvider::create($patient_physician_data);
                } else {
                    $patient_physician_data['patient_id'] = $patient_id;
                    $patient_physician_data['created_by'] = session()->get('userid');
                    $patient_physician = PatientProvider::create($patient_physician_data);
                }
            } else {
                $patient_physician_data['patient_id'] = $patient_id;
                $patient_physician_data['created_by'] = session()->get('userid');
                $patient_physician = PatientProvider::create($patient_physician_data);
            }

            $mon_0                 = sanitizeVariable($request->mon_0);
            $mon_1                 = sanitizeVariable($request->mon_1);
            $mon_2                 = sanitizeVariable($request->mon_2);
            $mon_3                 = sanitizeVariable($request->mon_3);
            $mon_any               = sanitizeVariable($request->mon_any);
            $tue_0                 = sanitizeVariable($request->tue_0);
            $tue_1                 = sanitizeVariable($request->tue_1);
            $tue_2                 = sanitizeVariable($request->tue_2);
            $tue_3                 = sanitizeVariable($request->tue_3);
            $tue_any               = sanitizeVariable($request->tue_any);
            $wed_0                 = sanitizeVariable($request->wed_0);
            $wed_1                 = sanitizeVariable($request->wed_1);
            $wed_2                 = sanitizeVariable($request->wed_2);
            $wed_3                 = sanitizeVariable($request->wed_3);
            $wed_any               = sanitizeVariable($request->wed_any);
            $thu_0                 = sanitizeVariable($request->thu_0);
            $thu_1                 = sanitizeVariable($request->thu_1);
            $thu_2                 = sanitizeVariable($request->thu_2);
            $thu_3                 = sanitizeVariable($request->thu_3);
            $thu_any               = sanitizeVariable($request->thu_any);
            $fri_0                 = sanitizeVariable($request->fri_0);
            $fri_1                 = sanitizeVariable($request->fri_1);
            $fri_2                 = sanitizeVariable($request->fri_2);
            $fri_3                 = sanitizeVariable($request->fri_3);
            $fri_any               = sanitizeVariable($request->fri_any);

            $patient_contact_time_data = array(
                'mon_0'                 => $mon_0,
                'mon_1'                 => $mon_1,
                'mon_2'                 => $mon_2,
                'mon_3'                 => $mon_3,
                'mon_any'               => $mon_any,
                'tue_0'                 => $tue_0,
                'tue_1'                 => $tue_1,
                'tue_2'                 => $tue_2,
                'tue_3'                 => $tue_3,
                'tue_any'               => $tue_any,
                'wed_0'                 => $wed_0,
                'wed_1'                 => $wed_1,
                'wed_2'                 => $wed_2,
                'wed_3'                 => $wed_3,
                'wed_any'               => $wed_any,
                'thu_0'                 => $thu_0,
                'thu_1'                 => $thu_1,
                'thu_2'                 => $thu_2,
                'thu_3'                 => $thu_3,
                'thu_any'               => $thu_any,
                'fri_0'                 => $fri_0,
                'fri_1'                 => $fri_1,
                'fri_2'                 => $fri_2,
                'fri_3'                 => $fri_3,
                'fri_any'               => $fri_any,
                'uid'                   => $uid,
                'patient_id'            => $patient_id,
                'created_by'            => session()->get('userid')
            );
            PatientContactTime::create($patient_contact_time_data);

            //log time
            $start_time         = sanitizeVariable($request->timer_start);
            $end_time           = sanitizeVariable($request->timer_end);
            $module_id          = sanitizeVariable($request->module_id);
            $component_id_array = ModuleComponents::where('module_id', $module_id)->where('components', 'Patient Enrollment')->get('id');  //queryEscape("SELECT id from ren_core.module_components where components = 'Patient Enrollment'");
            $component_id       = $component_id_array[0]->id;
            $submodule          = sanitizeVariable($request->submodule_id);
            $stage_id           = 0;
            $step_id            = 0;
            $form_name          = sanitizeVariable($request->form_name);
            $billable           = 1;
            $record_time        = CommonFunctionController::recordTimeSpent($start_time, $end_time, $patient_id, $module_id, $submodule, $stage_id, $billable, $uid, $step_id, $form_name, $form_start_time, $form_save_time);

            DB::commit();
            return $patient_id;
        } catch (\Exception $ex) {
            DB::rollBack();
            // throw $ex;
            return $ex;
            // return response(['message'=>'Something went wrong, please try again or contact administrator.!!'], 400);
        }
    }

    public function updatePatientRegistration(PatientEditRequest $request)
    {
        $review = "0";
        $uid = "0";
        $img_path = "";
        //error_reporting(E_ALL); 
        $patient_id = $request->patient_id;
        $review   = "0";
        $uid      = "0";
        $img_path = "";
        $fname    = sanitizeVariable($request->fname);
        $mname    = sanitizeVariable($request->mname);
        $lname    = sanitizeVariable($request->lname);
        $email    = sanitizeVariable($request->email);
        $dob      = sanitizeVariable($request->dob);
        $fin_number = sanitizeVariable($request->fin_number);
        $home_number = sanitizeVariable($request->home_number);
        $mob         = sanitizeVariable($request->mob);
        $contact_preference_calling = sanitizeVariable($request->contact_preference_calling);
        $contact_preference_sms    = sanitizeVariable($request->contact_preference_sms);
        $contact_preference_email  = sanitizeVariable($request->contact_preference_email);
        $contact_preference_letter = sanitizeVariable($request->contact_preference_letter);
        $no_email                  = sanitizeVariable($request->no_email);
        $preferred_contact         = sanitizeVariable($request->preferred_contact);
        $profile_img              = sanitizeVariable($request->image_path);
        $country_code               = sanitizeVariable($request->country_code);
        $secondary_country_code     = sanitizeVariable($request->secondary_country_code);
        $gender                     = sanitizeVariable($request->gender);
        $marital_status             = sanitizeVariable($request->marital_status);
        $education                  = sanitizeVariable($request->education);
        $ethnicity                  = sanitizeVariable($request->ethnicity);
        $occupation                 = sanitizeVariable($request->occupation);
        $occupation_description     = sanitizeVariable($request->occupation_description);
        $other_contact_name         = sanitizeVariable($request->other_contact_name);
        $other_contact_relationship = sanitizeVariable($request->other_contact_relationship);
        $other_contact_phone_number = sanitizeVariable($request->other_contact_phone_number);
        $other_contact_email        = sanitizeVariable($request->other_contact_email);
        $military_status            = sanitizeVariable($request->military_status);
        $ethnicity_2                = sanitizeVariable($request->ethnicity_2);
        $add_1                      = sanitizeVariable($request->add_1);
        $add_2                      = sanitizeVariable($request->add_2);
        $state                      = sanitizeVariable($request->state);
        $zipcode                    = sanitizeVariable($request->zipcode);
        $city                       = sanitizeVariable($request->city);
        $poa_first_name             = sanitizeVariable($request->poa_first_name);
        $poa_last_name              = sanitizeVariable($request->poa_last_name);
        $poa_relationship           = sanitizeVariable($request->poa_relationship);
        $poa_phone_2                = sanitizeVariable($request->poa_phone_2);
        $poa_email                  = sanitizeVariable($request->poa_email);
        $poa                       = sanitizeVariable($request->poa);
        $mon_0                 = sanitizeVariable($request->mon_0);
        $mon_1                 = sanitizeVariable($request->mon_1);
        $mon_2                 = sanitizeVariable($request->mon_2);
        $mon_3                 = sanitizeVariable($request->mon_3);
        $mon_any               = sanitizeVariable($request->mon_any);
        $tue_0                 = sanitizeVariable($request->tue_0);
        $tue_1                 = sanitizeVariable($request->tue_1);
        $tue_2                 = sanitizeVariable($request->tue_2);
        $tue_3                 = sanitizeVariable($request->tue_3);
        $tue_any               = sanitizeVariable($request->tue_any);
        $wed_0                 = sanitizeVariable($request->wed_0);
        $wed_1                 = sanitizeVariable($request->wed_1);
        $wed_2                 = sanitizeVariable($request->wed_2);
        $wed_3                 = sanitizeVariable($request->wed_3);
        $wed_any               = sanitizeVariable($request->wed_any);
        $thu_0                 = sanitizeVariable($request->thu_0);
        $thu_1                 = sanitizeVariable($request->thu_1);
        $thu_2                 = sanitizeVariable($request->thu_2);
        $thu_3                 = sanitizeVariable($request->thu_3);
        $thu_any               = sanitizeVariable($request->thu_any);
        $fri_0                 = sanitizeVariable($request->fri_0);
        $fri_1                 = sanitizeVariable($request->fri_1);
        $fri_2                 = sanitizeVariable($request->fri_2);
        $fri_3                 = sanitizeVariable($request->fri_3);
        $fri_any               = sanitizeVariable($request->fri_any);
        $primary_cell_phone        = sanitizeVariable($request->primary_cell_phone);
        $secondary_cell_phone      = sanitizeVariable($request->secondary_cell_phone);
        $consent_to_text            = sanitizeVariable($request->consent_to_text);
        $currentMonth       = date('m');
        $currentYear        = date('Y');

        if ($military_status == '0') {
            $vtemplate = sanitizeVariable(json_encode($request->question['question']));
        } else {
            $vtemplate = null;
        }

        if ($fin_number != null || $fin_number != '') {
            $patientfin = array(
                'patient_id'    => $patient_id,
                'status'        => '1',
                'fin_number'    => $fin_number
            );

            $check_exist_for_month         = PatientFinNumber::where('patient_id', $patient_id)->whereMonth('updated_at', $currentMonth)->whereYear('updated_at', $currentYear)->exists();

            if ($check_exist_for_month == true) {
                $patientfin['updated_at'] = Carbon::now();
                $patientfin['updated_by'] = session()->get('userid');
                $update_query = PatientFinNumber::where('patient_id', $patient_id)->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->orderBy('id', 'desc')->first()->update($patientfin);
            } else {
                $patientfin['created_at'] = Carbon::now();
                $patientfin['created_by'] = session()->get('userid');
                $insert_query = PatientFinNumber::create($patientfin);
            }
        }

        $patient_data = array(

            'fname'                      => $fname,
            'mname'                      => $mname,
            'lname'                      => $lname,
            'fin_number'                 => $fin_number,
            'email'                     => $email,
            'dob'                       => $dob,
            'home_number'                => $home_number,
            'mob'                        => $mob,
            'review'                     => $review,
            'org_id'                     => session()->get('org_id'),
            'created_by'                 => session()->get('userid'),
            'contact_preference_calling' => $contact_preference_calling,
            'contact_preference_sms'     => $contact_preference_sms,
            'contact_preference_email'   => $contact_preference_email,
            'contact_preference_letter'  => $contact_preference_letter,
            'no_email'                   => $no_email,
            'preferred_contact'          => $preferred_contact,
            'profile_img'                => $profile_img,
            'country_code'               => $country_code,
            'secondary_country_code'     => $secondary_country_code,
            'primary_cell_phone'         => $primary_cell_phone,
            'secondary_cell_phone'       => $secondary_cell_phone,
            'consent_to_text'            => $consent_to_text
        );

        // print_r($patient_data);
        $check_data = Patients::where('id', $request->patient_id)->where('fname', $fname)->where('lname', $lname)->where('mname', $mname)->where('dob', $dob)->exists();

        // dd($check_data);
        if ($check_data == false) {
            $check_data = Patients::where('id', $request->patient_id)->select('fname', 'lname', 'mname', 'dob')->first();

            $new_record = array(
                'patient_id' => $request->patient_id,
                'fname'      => $check_data->fname,
                'lname'      => $check_data->lname,
                'mname'      => $check_data->mname,
                'dob'        => $check_data->dob,
                'created_by' => session()->get('userid'),
                'updated_by' => session()->get('userid')
            );
            PatientIdEditHistory::create($new_record);
            $uid = $request->patient_id;
            $patient_data['updated_by'] = session()->get('userid');
            Patients::where('id', $request->patient_id)->update($patient_data);
        }
        $uid = $request->patient_id;
        $patient_data['updated_by'] = session()->get('userid');
        Patients::where('id', $request->patient_id)->update($patient_data);



        //////////////// Demographics//////////////////////////
        $patient_demographics_data = array(
            'gender'                     => $gender,
            'marital_status'             => $marital_status,
            'education'                  => $education,
            'ethnicity'                  => $ethnicity,
            'occupation'                 => $occupation,
            'height'                     => '',
            'weight'                     => '',
            'employer'                   => '',
            'occupation_description'     => $occupation_description,
            'other_contact_name'         => $other_contact_name,
            'other_contact_relationship' => $other_contact_relationship,
            'other_contact_phone_number' => $other_contact_phone_number,
            'other_contact_email'        => $other_contact_email,
            'military_status'            => $military_status,
            'ethnicity_2'                => $ethnicity_2,
            'template'                   => $vtemplate
        );
        $demographics = PatientDemographics::where('patient_id', $request->patient_id)->get();
        // dd($demographics); 
        if (count($demographics) > 0) {
            $patient_demographics_data['updated_by'] = session()->get('userid');
            PatientDemographics::where('patient_id', $request->patient_id)->update($patient_demographics_data);
        } else {
            $patient_demographics_data['created_by'] = session()->get('userid');
            $patient_demographics_data['patient_id'] = $patient_id;
            // $patient_demographics = PatientDemographics::createFromRequest($patient_demographics_data);
            $patient_demographics = PatientDemographics::create($patient_demographics_data);
        }


        $patient_address_data = array(
            'add_1'              => $add_1,
            'add_2'              => $add_2,
            'state'              => $state,
            'zipcode'            => $zipcode,
            'city'               => $city,
        );

        $patientAddress = PatientAddress::where('patient_id', $patient_id)->get();

        if (count($patientAddress) > 0) {
            $patient_address_data['updated_by'] = session()->get('userid');
            PatientAddress::where('patient_id', $request->patient_id)->update($patient_address_data);
        } else {
            $patient_address_data['patient_id'] = $patient_id;
            $patient_address_data['created_by'] = session()->get('userid');
            // $patient_address = PatientAddress::createFromRequest($patient_address_data);
            $patient_address = PatientAddress::create($patient_address_data);
        }

        $patient_poa_data = array(
            'poa_first_name'        => $poa_first_name,
            'poa_last_name'         => $poa_last_name,
            'poa_age'               => 0,
            'poa_relationship'      => $poa_relationship,
            'poa_mobile'            => '0000',
            'poa_phone_2'           => $poa_phone_2,
            'poa_email'             => $poa_email,
            'poa'                   => $poa,
        );

        $PatientPoa = PatientPoa::where('patient_id', $patient_id)->get();

        if (count($PatientPoa) > 0) {
            $patient_poa_data['updated_by'] = session()->get('userid');
            PatientPoa::where('patient_id', $request->patient_id)->update($patient_poa_data);
        } else {
            $patient_poa_data['patient_id'] = $patient_id;
            $patient_poa_data['created_by'] = session()->get('userid');
            // $patient_poa = PatientPoa::createFromRequest($patient_poa_data);
            $patient_poa = PatientPoa::create($patient_poa_data);
        }

        if (isset($request->enroll)) {
            $enrollarr = $request->enroll;
            $j = sizeof($request->enroll);

            foreach ($enrollarr as $key => $value) {
                if ($value) {
                    $patient_enroll_data = array(
                        'uid'               => $uid,
                        'module_id'         =>  $key,
                        'date_enrolled'     => date("Y-m-d"),
                        'created_by'        => session()->get('userid')
                    );


                    if ($request->has('edit')) {
                        $patientServiceExist = PatientServices::where('patient_id', $request->patient_id)->where('module_id', $key)->get();
                        if ($patientServiceExist->count() == 0) {
                            $patient_enroll_data['patient_id'] = $request->patient_id;
                            $patient_enroll_data['created_by'] = session()->get('userid');
                            continue;
                        } else {
                            if ($patientServiceExist[0]->status == 0) {
                                $patient_enroll_data = array(
                                    'status'    => 1,
                                    'updated_by' => session()->get('userid')
                                );
                            }
                            continue;
                        }
                    } else {
                        $patient_enroll_data['patient_id'] = $patient_id;
                        $patient_enroll_data['created_by'] = session()->get('userid');
                        continue;
                    }
                } else {
                    if ($request->has('edit')) {
                        $patientServiceExist = PatientServices::where('patient_id', $request->patient_id)->where('module_id', $key)->get();
                        if ($patientServiceExist) {
                            $patient_enroll_data = array(
                                'status'  => 0,
                                'updated_by' => session()->get('userid')
                            );

                            continue;
                        }
                    }
                }
            }
        }
        $ins_provider = sanitizeVariable($request->ins_provider);
        $ins_id       = sanitizeVariable($request->ins_id);
        $ins_type     = sanitizeVariable($request->ins_type);
        $name = sanitizeVariable($request->provider_name);
        $prac = sanitizeVariable($request->practice_id);
        $pracemr = sanitizeVariable($request->practice_emr);




        foreach ($ins_type as $key => $value) {
            if (isset($ins_provider[$key]) && ($ins_provider[$key] != "") && $value != '') {
                $patient_insurance_data = array(
                    'ins_code'      => '0000',
                    'ins_type'      => $value,
                    'ins_id'        => $ins_id[$key],
                    'ins_provider'  => $ins_provider[$key],
                    'ins_plan'      => "",
                );


                if ($request->has('edit')) {
                    PatientInsurance::where('patient_id', $request->patient_id)->whereIn('ins_type', [$value])->delete();
                    $patient_insurance_data['updated_by'] = session()->get('userid');
                }
                // else{
                $patient_insurance_data['patient_id'] = $patient_id;
                $patient_insurance_data['created_by'] = session()->get('userid');
                // $patient_poa = PatientInsurance::createFromRequest($patient_insurance_data);                  
                $patient_insurance = PatientInsurance::create($patient_insurance_data);
                // }
            }
        }

        if ($request->provider_id == '0') {
            $insert_provider_id = array(
                'name' => $name,
                'practice_id' => $prac,
                'provider_type_id' => 1,
                'created_by' => session()->get('userid')
            );
            $new_provider_id = Providers::create($insert_provider_id);
            $provider_id = $new_provider_id->id;
        } else {
            $provider_id = sanitizeVariable($request->provider_id);
        }
        $patient_physician_data = array(
            'provider_id'               => $provider_id,
            'provider_type_id'          => 1, // for pcp provider
            'provider_subtype_id'       => NULL,
            'practice_id'               => $prac,
            'address'                   => NULL,
            'phone_no'                  => '',
            'last_visit_date'           => NULL,
            'review'                    => NULL,
            'provider_name'             => NULL,
            'practice_emr'              => $pracemr,
            'is_active'                 => 1,
            'status'                    => 1
        );
        $PatientProvider = PatientProvider::where('patient_id', $patient_id)->where('is_active', 1)->where('provider_type_id', 1)->orderby('id', 'desc')->exists(); //get();
        // if(count($PatientProvider)>0){
        if ($PatientProvider == true) {
            $is_Active['updated_by'] = session()->get('userid');
            $is_Active['is_active'] = 0;
            $update_isActive = PatientProvider::where('patient_id', $patient_id)->where('is_active', 1)->where('provider_type_id', 1)->update($is_Active);
            $patient_physician_data['patient_id'] = $patient_id;
            $patient_physician_data['created_by'] = session()->get('userid');
            PatientProvider::create($patient_physician_data);
        } else {
            $patient_physician_data['patient_id'] = $patient_id;
            $patient_physician_data['created_by'] = session()->get('userid');
            $patient_physician = PatientProvider::create($patient_physician_data);
        }

        $patient_contact_time_data = array(
            'mon_0'                 => $mon_0,
            'mon_1'                 => $mon_1,
            'mon_2'                 => $mon_2,
            'mon_3'                 => $mon_3,
            'mon_any'               => $mon_any,
            'tue_0'                 => $tue_0,
            'tue_1'                 => $tue_1,
            'tue_2'                 => $tue_2,
            'tue_3'                 => $tue_3,
            'tue_any'               => $tue_any,
            'wed_0'                 => $wed_0,
            'wed_1'                 => $wed_1,
            'wed_2'                 => $wed_2,
            'wed_3'                 => $wed_3,
            'wed_any'               => $wed_any,
            'thu_0'                 => $thu_0,
            'thu_1'                 => $thu_1,
            'thu_2'                 => $thu_2,
            'thu_3'                 => $thu_3,
            'thu_any'               => $thu_any,
            'fri_0'                 => $fri_0,
            'fri_1'                 => $fri_1,
            'fri_2'                 => $fri_2,
            'fri_3'                 => $fri_3,
            'fri_any'               => $fri_any,
            'uid'                   => $uid,
            'patient_id'            => $patient_id,
            'created_by'            => session()->get('userid')
        );

        $patientcontact = PatientContactTime::where('patient_id', $patient_id)->get();
        if (count($patientcontact) > 0) {
            $patient_contact_time_data['updated_by'] = session()->get('userid');
            PatientContactTime::where('patient_id', $request->patient_id)->update($patient_contact_time_data);
        } else {
            $patient_contact_time_data['patient_id'] = $patient_id;
            $patient_contact_time_data['updated_by'] = session()->get('userid');
            PatientContactTime::create($patient_contact_time_data);
        }

        $start_time   = sanitizeVariable($request->start_time);
        $end_time     = sanitizeVariable($request->end_time);
        $billable     = 1;
        $module_id    = sanitizeVariable($request->module_id);
        $component_id = sanitizeVariable($request->submodule_id);
        $stage_id     = empty(sanitizeVariable($request->stage_id)) ? 0 : sanitizeVariable($request->stage_id);
        $step_id      = empty(sanitizeVariable($request->step_id)) ? 0 : sanitizeVariable($request->step_id);
        $form_name    = sanitizeVariable($request->form_name);
        $form_start_time = sanitizeVariable($request->timearr['form_start_time']);
        $form_save_time = date("m-d-Y H:i:s", $_SERVER['REQUEST_TIME']);
        $record_time = CommonFunctionController::recordTimeSpent($start_time, $end_time, $request->patient_id, $module_id, $component_id, $stage_id, $billable, $uid, $step_id, $form_name,  $form_start_time, $form_save_time);

        $module_query = DB::select("SELECT module from ren_core.modules where id = " . $module_id);
        $module       = strtolower($module_query[0]->module);
        $url_module   = str_replace(' ', '-', $module);

        $submodule_query = DB::select("SELECT components from ren_core.module_components where id = " . $component_id);
        $submodule       = strtolower($submodule_query[0]->components);
        $url_submodule   = str_replace(' ', '-', $submodule);

        $enroll_service   = sanitizeVariable($request->enroll_service);
        $apend_service_id = ($enroll_service == 0) ? "" : "/" . $enroll_service;

        if ($url_submodule == "registration") {
            $url_submodule = "registered-patient-list";
            echo $url_module . "/" . $url_submodule;
        } else {
            echo $url_module . "/" . $url_submodule . "/" . $request->patient_id . $apend_service_id;
        }
    }

    public function physician($practice)
    {
        $physicians = [];
        $practice = sanitizeVariable($practice);
        $physicians = Providers::all()->where("practice_id", $practice);
        return response()->json($physicians);
    }

    public function getPartnerId($practice)
    {

        $practice = sanitizeVariable($practice);
        // $physicians = Providers::all()->where("practice_id", $practice);
        $partnerid = Practices::where('id', $practice)->get('partner_id');

        return response()->json($partnerid);
        // return $partnerid;
    }


    public function fetchRegisteredPatients(Request $request)
    {
        if ($request->ajax()) {
            $module    = Module::where('module', 'Patients')->get();
            $mid       = $module[0]->id;
            $component = ModuleComponents::where('module_id', $mid)->where('components', 'Registration')->get();
            $cid       = $component[0]->id;
            $data      = Patients::with('patientServices', 'patientServices.module')
                ->orderby('patient.pid', 'DESC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($mid, $cid) {
                    $btn = '<a href="registerd-patient-edit/' . $row->id . '/' . $mid . '/' . $cid . '/0"  data-toggle="tooltip" data-original-title="Edit" title="Edit" ><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function fetchLastInsertPatient(Request $request)
    {
        $fname = sanitizeVariable($request->fname);
        $lname = sanitizeVariable($request->lname);
        $dob = sanitizeVariable($request->dob);
        $data      = DB::select("select patients.generate_patient_uid('" . $fname . "' , '" . $dob . "', '" . $lname . "') as id");
        $patientId = DB::select("select * from patients.patient where id = " . $data[0]->id);
        $response  = [];
        if (count($patientId) > 0) {
            $response = $data[0]->id;
            //echo json_encode($response);
        }
        return $response;
    }

    public function registeredPatientsSearch(Request $request)
    {
        $patient_id = sanitizeVariable($request->route('id'));
        $module     = Module::where('module', 'Patients')->get();
        $mid        = $module[0]->id;
        $component  = ModuleComponents::where('module_id', $mid)->where('components', 'Registration')->get();
        $cid        = $component[0]->id;

        if ($request->ajax()) {
            $data = Patients::with('patientServices', 'patientServices.module')
                ->where('patient.id', '=', $patient_id)
                ->orderby('patient.pid', 'DESC')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($mid, $cid) {
                    $btn = '<a href="registerd-patient-edit/' . $row->id . '/' . $mid . '/' . $cid . '/0" title="Edit" ><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function populatePatientData($patientId)
    {
        $patientId = sanitizeVariable($patientId);
        $patient            = Patients::find($patientId);
        $patientdemo        = PatientDemographics::where('patient_id', $patientId)->first();
        $patientAddresss    = PatientAddress::where('patient_id', $patientId)->first();
        $patientInsurance   = PatientInsurance::where('patient_id', $patientId)->first();
        $patientPoa         = PatientPoa::where('patient_id', $patientId)->first();
        $patientProvider    = PatientProvider::where('patient_id', $patientId)
            ->with('practice')
            ->with('provider')
            ->with('users')
            ->where('is_active', 1)
            ->where('provider_type_id', 1)
            ->orderby('id', 'desc')->first();
        $patientContactTime = PatientContactTime::where('patient_id', $patientId)->first();
        $PatientFamily = PatientFamily::where('patient_id', $patientId)->where('relationship', 'emergency-contact')->where('tab_name', 'emergency-contact')->orderBy('id', 'desc')->get()->toArray();
        // dd($PatientFamily);
        // $practice_details=Practices::where('id',$patientProvider->practice_id)->first();
        //dd($PatientFamily);
        $patient = $patient->population();
        if ($patientdemo) {
            $pdemo = $patientdemo->population();
            $patient['static'] = array_merge($patient['static'], $pdemo['static']);
        }
        if ($patientAddresss) {
            $paddress = $patientAddresss->population();
            $patient['static'] = array_merge($patient['static'], $paddress['static']);
        }
        if ($patientInsurance) {
            $patientInsurance = $patientInsurance->population();
            $patient['static'] = array_merge($patient['static'], $patientInsurance['static']);
        }
        if ($patientPoa) {
            $patientPoa = $patientPoa->population();
            $patient['static'] = array_merge($patient['static'], $patientPoa['static']);
        }
        if ($patientProvider) {
            $patientProvider = $patientProvider->population();
            $patient['static'] = array_merge($patient['static'], $patientProvider['static']);
        }
        if ($patientContactTime) {
            $patientContactTime = $patientContactTime->population();
            $patient['static'] = array_merge($patient['static'], $patientContactTime['static']);
        }
        if ($PatientFamily) {
            $patientFamily =   $PatientFamily;
            $patient['dynamic'] =  $patientFamily;
        }

        // if($patient){             
        //     $patientEmail = $patient->population();
        //     $patient['static'] = array_merge($patient['static'], $patient['static']);            
        // }

        // if($practice_details){            
        //     $practice_details = $practice_details->population();
        //     $patient['static'] = array_merge($patient['static'], $practice_details['static']);            
        // }
        $result['patient_registration_form'] = $patient;
        return $result;
    }

    public function patientRegisteration($id, $mid)
    {
        $id = sanitizeVariable($id);
        $mid = sanitizeVariable($mid);
        $patient              = Patients::where('id', $id)->get();
        $patientAddress       = PatientAddress::where('patient_id', $id)->get();
        $patientInsurance     = PatientInsurance::where('patient_id', $id)->get();
        $patientPoa           = PatientPoa::where('patient_id', $id)->get();
        $patientProvider      = PatientProvider::where('patient_id', $id)->where('is_active', 1)->get();
        $patientContactTime   = PatientContactTime::where('patient_id', $id)->get();
        $patientServices      = PatientServices::where('patient_id', $id)->where('status', 1)->get();
        $services             = Module::where('patients_service', 1)->where('status', 1)->get();
        $patient_demographics = PatientDemographics::where('patient_id', $id)->get();
        $last_time_spend      = CommonFunctionController::getNetTimeBasedOnModule($id, $mid);


        $module_id    = getPageModuleName();
        $SID = getFormStageId(getPageModuleName(),     9, 'Veteran');
        if (isset($patient_demographics[0]->template)) {
            $patient_questionnaire = json_decode($patient_demographics[0]->template, true);
            if (isset($patient_questionnaire["template_id"])) {
                $veteranQuestion = QuestionnaireTemplate::where('module_id', $module_id)->where('id', $patient_questionnaire["template_id"])->where('stage_id', $SID)->where('template_type_id', 5)->latest()->first();
            } else {
                $veteranQuestion = QuestionnaireTemplate::where('module_id', $module_id)->where('status', 1)->where('stage_id', $SID)->where('template_type_id', 5)->latest()->first();
            }
        } else {
            $veteranQuestion = QuestionnaireTemplate::where('module_id', $module_id)->where('status', 1)->where('stage_id', $SID)->where('template_type_id', 5)->latest()->first();
        }



        $PatientDevices = PatientDevices::where('patient_id', $id)->orderby('id', 'desc')->get();
        return view('Patients::patient.edit-patient-registration', compact('patient', 'services', 'patient_demographics', 'patientServices', 'last_time_spend', 'veteranQuestion', 'PatientDevices'));
    }

    public function patientContactTime($id)
    {
        $id = sanitizeVariable($id);
        $patientContactTime = PatientContactTime::where('patient_id', $id)->get();
        return $patientContactTime;
    }

    public static function checkPatientUid(Request $request)
    {
        $fname     = sanitizeVariable($request->fName);
        $lname     = sanitizeVariable($request->lName);
        $dob       = sanitizeVariable($request->dob);
        $data      = DB::select("select patients.generate_patient_uid('" . $fname . "' , '" . $dob . "', '" . $lname . "') as id");
        $patientId = DB::select("select * from patients.patient where id = " . $data[0]->id);
        $response  = [];
        if (count($patientId) > 0) {
            $response['error'] = "Patient with this First Name, Last Name and DOB already exist.";
            echo json_encode($response);
        } else {
            $response['uid'] = $data[0]->id;
            echo json_encode($response);
        }
    }

    public function Nonassignedpatients(Request $request)
    {
        //createdby ashvini -19thoct2020
        if ($request->ajax()) {
            $data = DB::select("select * from patients.patient p where p.id NOT IN (SELECT up.patient_id FROM task_management.user_patients up where up.status = 1) ");
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // $btn ='<a href="/task-management/patient-details/'.$row->id.'" title="Start" ><label class="checkbox"><input type="checkbox" id=""><span></span><span class="checkmark"></span></label></a>';//<i class="text-20 i-Next1" style="color: #2cb8ea;"></i>
                    // return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $patientCallHistoryHTML .= '</ul><div class="d-flex justify-content-center"></div></div></div></div>';
        return $patientCallHistoryHTML;
    }

    public function fetchPatientRelationshipQuestionnaire(Request $request)
    {
        $patientId   = sanitizeVariable($request->route('patient_id'));
        $moduleId    = sanitizeVariable($request->route('module_id'));
        $componentId = sanitizeVariable($request->route('sub_module_id'));
        $patientRelationshipQuestionnaire = getRelationshipQuestionnaire($patientId, $moduleId, $componentId);
        return $patientRelationshipQuestionnaire;
    }

    public function fetchPatientCallHistoryData(Request $request)
    {
        $patientCallHistoryHTML  = "";
        $patientId   = sanitizeVariable($request->route('patient_id'));
        $patientCallHistoryData = getCallHistory($patientId);
        $patientCallHistoryHTML = '<div class="container mt-5 mb-5"><div class="row"><div class="col-md-7" id="history_list"><h4>Call History</h4><ul class="timeline">';
        foreach ($patientCallHistoryData as $callhistory) {
            $patientCallHistoryHTML .= '<li>';
            if ($callhistory->phone_no == "received") {
                $patientCallHistoryHTML .= "<h5> Incoming Response (" . $callhistory->created_at . ")</h5>";
                $patientCallHistoryHTML .= "<b>SMS: </b>" . $callhistory->text_msg;
            } else {
                if ($callhistory->call_status == 1) {
                    $patientCallHistoryHTML .= '<h5>Call Answered (' . $callhistory->created_at . ')</h5><table><tr><th>Call Continue Status</th>';
                    if ($callhistory->call_continue_status == 0) {
                        $patientCallHistoryHTML .= '<th>Call Follow-up date</th><th>Call Follow-up Time</th>';
                    }
                    $ccs = 'Yes';
                    if ($callhistory->call_continue_status == 0) {
                        $ccs = 'No';
                    }
                    $patientCallHistoryHTML .= '</tr><tr><td>' . $ccs . '</td>';
                    if ($callhistory->call_continue_status == 0) {
                        $patientCallHistoryHTML .= '<td>' . str_replace('00:00:00', '', $callhistory->ccm_answer_followup_date) . '</td><td>' . $callhistory->ccm_answer_followup_time . '</td>';
                    }
                    $patientCallHistoryHTML .= '</tr></table>';
                } else {
                    $patientCallHistoryHTML .= '<h5>Call Not Answered (' . $callhistory->created_at . ')</h5><table><tr><th>Call Follow-up date</th></tr><tr><td>' . str_replace('00:00:00', '', $callhistory->ccm_call_followup_date) . '</td></tr></table>';
                }
                $patientCallHistoryHTML .= '<b>';
                if ($callhistory->call_status == "1") {
                    $patientCallHistoryHTML .= 'Call Script:';
                } else {
                    if ($callhistory->voice_mail == "1") {
                        $patientCallHistoryHTML .= 'Left Voice Mail:';
                    } else if ($callhistory->voice_mail == "2") {
                        $patientCallHistoryHTML .= 'No Voice Mail';
                    } else {
                        $patientCallHistoryHTML .= 'SMS Sent';
                    }
                }
                $patientCallHistoryHTML .= '</b>' . $callhistory->text_msg . '</li>';
            }
        }
        $patientCallHistoryHTML .= '</ul><div class="d-flex justify-content-center"></div></div></div></div>';
        return $patientCallHistoryHTML;
    }

    public function cmassignpatient(Request $request)
    {
        $login_user = Session::get('userid');
        $configTZ   = config('app.timezone');
        $userTZ     = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        $patient_id = sanitizeVariable($request->route('patient'));
        $practice_id = sanitizeVariable($request->route('practice'));

        // $data = "select distinct p.id,p.fname ,p.lname ,p.dob ,p2.name as practice, usr.id,usr.f_name ,usr.l_name, m.id ,m.module  from patients.patient p
        // inner join task_management.user_patients up on up.patient_id =p.id and up.status=1
        // inner join ren_core.users usr on usr.id=up.user_id 
        // LEFT JOIN patients.patient_providers pp ON pp.patient_id = p.id AND pp.is_active = 1 AND pp.provider_type_id = 1 
        // LEFT JOIN ren_core.practices p2 ON p2.id = pp.practice_id
        // inner join patients.patient_services ps on ps.patient_id = p.id and  ps.status in (0,1)
        // inner join ren_core.modules as m on m.id = ps.module_id  
        // where usr.id = $login_user";

        //for rpm enrolled patient link 
        $data = "SELECT * FROM (SELECT p.id,p.fname,p.lname,p.dob,p2.name AS practice,usr.id AS user_id,
                usr.f_name AS user_fname,usr.l_name AS user_lname,m.id AS module_id, m.module,ROW_NUMBER() OVER(PARTITION BY p.id ORDER BY m.id) AS row_num
                FROM patients.patient p
                LEFT JOIN task_management.user_patients up ON up.patient_id = p.id AND up.status = 1
                LEFT JOIN ren_core.users usr ON usr.id = up.user_id 
                LEFT JOIN patients.patient_providers pp ON pp.patient_id = p.id AND pp.is_active = 1 AND pp.provider_type_id = 1 
                LEFT JOIN ren_core.practices p2 ON p2.id = pp.practice_id
                LEFT JOIN patients.patient_services ps ON ps.patient_id = p.id AND ps.status IN (0, 1)
                INNER JOIN ren_core.modules AS m ON m.id = ps.module_id  
                WHERE usr.id = $login_user";



        if (($practice_id == "null" || $practice_id == 0) && ($patient_id == "null" || $patient_id == 0)) {
            $query = [];
        } else if (($practice_id == "null" || $practice_id == 0)) {
            $data .= "  and p.id = '" . $patient_id . "' ) AS subquery WHERE subquery.row_num = 1";
            $query = DB::select($data);
        } else if (($patient_id == "null" || $patient_id == 0)) {
            $data .= "  and pp.practice_id = '" . $practice_id . "' ) AS subquery WHERE subquery.row_num = 1 ";
            $query = DB::select($data);
        } else {
            $data .= "  and pp.practice_id = '" . $practice_id . "' and p.id = '" . $patient_id . "' ) AS subquery WHERE subquery.row_num = 1";
            $query = DB::select($data);
        }

        // dd($data);
        return view('Patients::patient.cm-assigned-patient-right', compact('query'));
    }
}
