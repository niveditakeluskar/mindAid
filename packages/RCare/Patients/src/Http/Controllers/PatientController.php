<?php

namespace RCare\Patients\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RCare\Rpm\Models\Devices;
use RCare\Org\OrgPackages\QCTemplates\src\Models\ContentTemplate;
use RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate;
use RCare\Ccm\Models\QuestionnaireTemplatesUsageHistory;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use RCare\Patients\Models\Patients;
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
 
    public function index() {
        return view('Patients::patient.patient-list'); 
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
        // dd(is_null($PatientAddress->add_2));
        $add_1 = empty($PatientAddress) ? '' : $PatientAddress->add_1;
        $add_2 = (empty($PatientAddress) or is_null($PatientAddress->add_2) )? '' : $PatientAddress->add_2;		
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
    public function fetchPatients(Request $request)
    {
            $data = Patients::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . $row->id . '" title="Start" ><i class="text-20 i-Next1" style="color: #2cb8ea;"></i></a>';
                    $btn = $btn. '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit" 
                    title="info"><i class=" editform i-eyes"></i></a>';  
        
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
    } 
    
    public function fetching(Request $request){
        $patient_id   = sanitizeVariable($request->route('id'));
        $patient = Patients::where('id',$patient_id)->get();
        return view('Patients::patient.patient-details', compact('patient'));
    }
 
 

    public function fetchPatientRelationshipQuestionnaire(Request $request)
    {
        $patientId   = sanitizeVariable($request->route('patient_id'));
        $moduleId    = sanitizeVariable($request->route('module_id'));
        $componentId = sanitizeVariable($request->route('sub_module_id'));
        $patientRelationshipQuestionnaire = getRelationshipQuestionnaire($patientId, $moduleId, $componentId);
        return $patientRelationshipQuestionnaire;
    }

}
