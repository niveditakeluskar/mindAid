<?php
namespace RCare\Ccm\Http\Controllers;

use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Org\OrgPackages\Medication\src\Models\Medication;
use RCare\Patients\Models\Patients;
use RCare\Patients\Models\PatientAllergy;
use RCare\Patients\Models\PatientFamily;
use RCare\Patients\Models\PatientHealthData;
use RCare\Patients\Models\PatientDemographics;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\PatientServices;
use RCare\Patients\Models\PatientImaging;
use RCare\Patients\Models\PatientDiagnosis;
use RCare\Patients\Models\PatientVitalsData;
use RCare\Patients\Models\PatientHealthServices;
use RCare\TaskManagement\Models\UserPatients;
use RCare\Patients\Models\PatientMedication;
use RCare\Patients\Models\PatientLabRecs;
use RCare\Org\OrgPackages\HealthServices\src\Models\HealthServices;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Request;
use PDF;
use View;
use Session;

class PrintCarePlanController extends Controller
{
    public function patientCarePlan($subModule="", $patientId) {

        if(Session::has('role')){

        } else {
            echo('Please Login into the Portal...');
        }

        $route        = Request::segment(3);
        $uid          = sanitizeVariable($patientId);
        $module_id    = getPageModuleName();
        $component_id = getPageSubModuleName();
        $configTZ     = config('app.timezone');
        $userTZ       = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');   

        // Check if this month’s data exists for PatientDiagnosis; If not, copy from last month
        $check_exist_code = CommonFunctionController::checkPatientDiagnosisDataExistForCurrentMonthOrCopyFromLastMonth($uid);

        //Check if this month’s data exists for Medication; If not, copy from last month
        $check_exist_medication = CommonFunctionController::checkPatientMedicationDataExistForCurrentMonthOrCopyFromLastMonth($uid);

        //Check if this month’s data exists for PatientAllergy; If not, copy from last month
        $allergyTypes = array("food","drug","enviromental","insect","latex","petrelated","other");
        foreach($allergyTypes as $key => $allergyType) {
            $check_exist_allergy = CommonFunctionController::checkPatientAllergyDataExistForCurrentMonthOrCopyFromLastMonthBasedOnAllergyType($uid, $allergyType);
        }

        //Check if this month’s data exists for PatientLabRecs; If not, copy from last month
        // $check_exist_patient_labs  = CommonFunctionController::checkPatientLabRecsDataExistForCurrentMonthOrCopyFromLastMonth($uid);

        //Check if this month’s data exists for PatientImaging; If not, copy from last month
        $check_exist_patient_imaging  = CommonFunctionController::checkPatientImagingDataExistForCurrentMonthOrCopyFromLastMonth($uid);

        //Check if this month’s data exists for PatientHealthData; If not, copy from last month
        $check_exist_patient_health_data  = CommonFunctionController::checkPatientHealthDataExistForCurrentMonthOrCopyFromLastMonth($uid);

        for($i = 1;  $i <= 7; $i++) {
            //Check if this month’s data exists for PatientHealthServices; If not, copy from last month
            $check_exist_patient_health_services = CommonFunctionController::checkPatientHealthServicesDataExistForCurrentMonthOrCopyFromLastMonthBasedOnHealthServicesType($uid, $i);
        }
        // $patient = Patients::where('id',$uid)->get();
        $patient = Patients::with('patientServices', 'patientServices.module')->where('id',$uid)->get();

        // $patientDiagnosisDetails = PatientDiagnosis::where("patient_id", $uid)
        //                                             ->select(DB::raw(" distinct
        //                                             max(updated_at) as date, *
        //                                             "
        //                                             ))
        //                                             ->whereMonth('updated_at', '>=', date('m'))
        //                                             ->whereYear('updated_at', '>=', date('Y'))
        //                                             // ->groupBy(DB::raw("
        //                                             // code,
        //                                             // condition,
        //                                             // jsonb(patient_diagnosis_codes.goals),
        //                                             // jsonb(patient_diagnosis_codes.symptoms),
        //                                             // jsonb(patient_diagnosis_codes.tasks
        //                                             // "))
        //                                             // ->groupBy('condition')
        //                                             // ->groupBy('goals')
        //                                             // ->groupBy('symptoms')
        //                                             // ->groupBy('tasks')
        //                                             // ->groupBy('created_at')
        //                                             ->orderBy('created_at','desc')
        //                                             ->get();
        $patientDiagnosisDetails    =  PatientDiagnosis:: where("patient_id", $uid)
                                        ->where('status',1)
                                        ->whereMonth('updated_at', '>=', date('m'))
                                        ->whereYear('updated_at', '>=', date('Y'))
                                        ->orderBy('updated_at', 'DESC')
                                        ->get(['code','condition','updated_at as date', 'goals', 'symptoms', 'tasks', 'comments'])
                                        ->unique('code');

                                        // $Condition = DB::select( DB::raw("select distinct diagnosis, 
                                        // max(updated_at) as date
                                        // FROM patients.patient_diagnosis_codes 
                                        // where updated_at >= date_trunc('month', current_date)  
                                        // AND  updated_at >= date_trunc('year', current_date)
                                        // AND patient_id = '".$uid."'
                                        // AND status = 1 
                                        // group by diagnosis 
                                        
                                        // "));

        // dd($patientDiagnosisDetails);

        $PatientDiagnosis  = PatientDiagnosis::where("patient_id", $uid)
                                            ->where('status',1)
                                            ->whereMonth('updated_at', '>=', date('m'))
                                            ->whereYear('updated_at', '>=', date('Y'))
                                            ->with('users')
                                            ->orderBy('created_at','desc')
                                            ->get(['code','condition','updated_at as date']);
        // print_r($PatientDiagnosis);
        // echo "<br/><br/><br/>-----<br/><br/><br/>";
        // $PatientDiagnosis = DB::select( DB::raw("select distinct code,condition,
        // max(updated_at) as date
        // FROM patients.patient_diagnosis_codes 
        // where updated_at >= date_trunc('month', current_date)  
        // AND  updated_at >= date_trunc('year', current_date)
        // AND patient_id = '".$uid."' 
        // group  by code,condition
        // order by date desc 
        // "));
        // echo "<pre>";
        // dd($PatientDiagnosis);

        $PatientDiag      = DB::select(DB::raw(
                                "select distinct code,condition,jsonb(goals) as goals,jsonb(symptoms) as symptoms ,jsonb(tasks) as tasks,
                                to_char(max(updated_at) at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') 
                                as date
                                from patients.patient_diagnosis_codes
                                WHERE  updated_at >= date_trunc('month', current_date)  
                                AND  updated_at >= date_trunc('year', current_date) 
                                AND patient_id = '".$uid."'
                                AND status =1 
                                group  by code,condition,jsonb(patient_diagnosis_codes.goals),jsonb(patient_diagnosis_codes.symptoms),jsonb(patient_diagnosis_codes.tasks)
                                order by date desc"
                            ));

        // $PatientDiag      = PatientDiagnosis::select(DB::raw("distinct code, condition, goals, symptoms, jsonb(tasks) as tasks, to_char(max(updated_at) at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as date"))
        //                     ->where("patient_id", $uid)
        //                     ->whereMonth('updated_at', '>=', date('m'))
        //                     ->whereYear('updated_at', '>=', date('Y'))
        //                     ->groupBy('code', 'condition', 'patient_diagnosis_codes.goals', 'patient_diagnosis_codes.symptoms', 'patient_diagnosis_codes.tasks')
        //                     ->orderBy('date','desc')
        //                     ->get(['code', 'condition', 'patient_diagnosis_codes.goals', 'patient_diagnosis_codes.symptoms', 'patient_diagnosis_codes.tasks']);
        
        // dd($PatientDi    ag);
        // echo "<br/><br/><br/>-----<br/><br/><br/>";

        // $patient_cmnt     = DB::select(DB::raw(
        //                         "select distinct comments,updated_at,
        //                         to_char(max(updated_at) at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') 
        //                         as date
        //                         FROM patients.patient_diagnosis_codes 
        //                         WHERE  updated_at >= date_trunc('month', current_date)  
        //                         AND  updated_at >= date_trunc('year', current_date)  
        //                         AND patient_id = '".$uid."' 
        //                         group  by comments,updated_at
        //                         order by updated_at desc"
        //                     ));

        $patient_cmnt     = PatientDiagnosis::select(DB::raw("distinct comments, updated_at, to_char(max(updated_at) at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as date"))
                                            ->where("patient_id", $uid)
                                            ->whereMonth('updated_at', '>=', date('m'))
                                            ->whereYear('updated_at', '>=', date('Y'))
                                            ->groupBy('comments', 'updated_at')
                                            ->orderBy('updated_at','desc')
                                            ->get(['code','condition','updated_at as date']);

        $PatientAllergy1  = PatientAllergy::where('patient_id', $uid)
                        
                        ->whereMonth('updated_at','>=',Carbon::now())//->subMonth()->month
                        ->whereYear('updated_at','>=',Carbon::now())
                        ->where('status',1)
                        ->select(DB::raw('DATE(updated_at)date'))->distinct()
                        ->get()->toArray();
        foreach($PatientAllergy1 as $key=>$value) {
            $d = $value['date'];
            $i = 0;                              
            $PatientAllergy = PatientAllergy::where('patient_id', $uid)
                            
                            ->whereDate('updated_at','=',$value['date'])
                            // ->where('specify','!=','no Allergies')
                            ->where('status',1)
                            ->select(DB::raw('DATE(updated_at)date'),'allergy_type','specify','type_of_reactions','severity','course_of_treatment','allergy_status')
                            ->get()->toArray();
            $PatientAllergy1[$key]['date'] =  $PatientAllergy;
            $PatientAllergy1[$key]['displaydate'] = $d;
            $i++;
        }
        
        // $updated_sign            = //PatientDiagnosis::with('users_updated_by')->where('patient_id',$uid)->orderBy('id', 'desc')->limit(1)->get(); 
        //                         \DB::table('patients.patient_diagnosis_codes as pdc')
        //                         ->leftjoin('ren_core.users as u','pdc.updated_by','=','u.id') 
        //                         ->where('patient_id',$uid)
        //                         ->orderby('pdc.id','desc')->limit(1)->get(); // query before changing to eloquont 

        // $updated_sign            = PatientDiagnosis::with('users_updated_by')->where('patient_id',$uid)->get(); //working but fetchin diagnisis data as well

        $updated_sign            = PatientDiagnosis::with('users_updated_by')->where('patient_id',$uid)->orderby('id','desc')->limit(1)->get();

        // $created_sign            = //PatientDiagnosis::with('users_created_by')->where('patient_id',$uid)->orderBy('id', 'desc')->get();
        //                         \DB::table('patients.patient_diagnosis_codes as pdc') 
        //                         ->leftjoin('ren_core.users as u','pdc.created_by','=','u.id') 
        //                         ->where('patient_id',$uid)
        //                         ->orderby('pdc.id','desc')->limit(1)->get();well

        $created_sign            = PatientDiagnosis::with('users_created_by')->where('patient_id',$uid)->orderby('id','desc')->limit(1)->get();

        $electronic_sign         = isset($updated_sign) ? $created_sign : $updated_sign ;


        // $PatientMedication1      = PatientMedication::with('medication')
        //                                             ->select('med_id', 'description', 'purpose', 'dosage', 'frequency', 'route', 'updated_at as date')
        //                                             ->distinct()
        //                                             ->whereMonth('updated_at','>=',Carbon::now())
        //                                             ->whereYear('updated_at','>=',Carbon::now())
        //                                             ->orderBy('updated_at', 'DESC')
        //                                             ->where('patient_id', $uid)
        //                                             ->get();
        
        $PatientMedication1     = DB::select(DB::raw("select med_id,pm1.id,pm1.description,purpose,strength,duration,dosage,frequency,route,pharmacy_name,pharmacy_phone_no,
                                        rm.description as name,pm1.updated_at as date
                                        from patients.patient_medication pm1 
                                        left join ren_core.medication rm on rm.id = pm1.med_id 
                                        where pm1.id in (select max(pm.id) from patients.patient_medication pm 
                                            where pm.patient_id = '".$uid."' 
                                            AND pm.created_at >= date_trunc('month', current_date)
                                            AND pm.created_at >= date_trunc('month', current_date) 
                                            AND pm.status = 1
                                            group by pm.med_id) 
                                        order by rm.description asc"));

        $last_time_spend        = CommonFunctionController::getCcmNetTime($uid, $module_id);

        $patient_demographics   = PatientDemographics::where('patient_id', $uid)->get();
        $patient_providers      = PatientProvider::where('patient_id', $uid)->with('practice')->with('provider')->with('users')->where('provider_type_id',1)->orderby('id','desc')->get(); 

        $patient_providersusers = PatientProvider::where('patient_id', $uid)->with('users')->get();

        // $caremanager            =  \DB::table('task_management.user_patients as up')
        //                         ->join('ren_core.users as u','up.user_id','=','u.id') 
        //                         ->where('patient_id',$uid)
        //                         ->where('up.status',1)
        //                         ->orderby('up.id','desc')->get();

        $caremanager            = UserPatients::with('users_assign_to')->where('patient_id',$uid)->orderby('id','desc')->limit(1)->get();
        //dd($caremanager);
        $medication             = Medication::where("status", 1)->get();
        // $patient_vitals         = DB::select(DB::raw("select distinct height ,weight ,bmi,bp,o2,pulse_rate,diastolic,oxygen,notes, to_char( max(updated_at) at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as date from patients.patient_vitals pv 
        //     WHERE  created_at >= date_trunc('month', current_date)
        //     AND  created_at >= date_trunc('year', current_date)  AND patient_id = '".$uid."'
        //     group by height ,weight ,bmi,bp,o2,pulse_rate,diastolic,oxygen,notes order by date desc
        //     "));

        $patient_vitals         = PatientVitalsData::where('patient_id',$uid)
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
                                ->get(['height', 'weight', 'bmi', 'bp', 'o2', 'pulse_rate', 'diastolic', 'oxygen', 'notes', 'updated_at as date','pain_level']);
        //   dd($patient_vitals);
            //  distinct  from patients.patient_vitals pv 
            // WHERE  created_at >= date_trunc('month', current_date)
            // AND  created_at >= date_trunc('year', current_date)  AND patient_id = '".$uid."'
            // group by height ,weight ,bmi,bp,o2,pulse_rate,diastolic,oxygen,notes order by date desc
            // "));
        $patient_healthdata     = PatientHealthData::where('patient_id', $uid)
                                ->select(DB::raw("distinct health_data, to_char( max(updated_at) at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as updated_at, health_date"))//,max(updated_at) as updated_at
                                ->whereMonth('updated_at','=', date('m'))
                                ->whereYear('updated_at','=', date('Y'))
                                ->groupBy('health_data','health_date')
                                ->orderBy('health_date','desc')->get();
                                

        $patient_imaging        = PatientImaging::where('patient_id', $uid)
                                ->select(DB::raw("distinct imaging_details, to_char( max(updated_at) at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as updated_at, imaging_date"))//,max(updated_at) as updated_at
                                ->whereMonth('created_at','=', date('m'))
                                ->whereYear('created_at','=', date('Y'))
                                ->groupBy('imaging_details','imaging_date')
                                ->orderBy('imaging_date','desc')->get();

        // $patient_lab1         = DB::select(DB::raw("select distinct  to_char( max(plr.created_at) at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as date,
        //                             plr.lab_test_id,(case when plr.lab_date is null then plr.rec_date else plr.lab_date end) as lab_date,
        //                             (case when rlt.description is null then 'Other' else rlt.description end)  from patients.patient_lab_recs plr
        //                         left join ren_core.rcare_lab_tests rlt on rlt.id=lab_test_id 
        //                         where plr.lab_test_id is not null and EXTRACT(Month from plr.created_at) = EXTRACT(MONTH FROM CURRENT_DATE) AND EXTRACT(YEAR from plr.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
        //                          and plr.patient_id='".$uid."' group  by plr.lab_test_id,plr.lab_test_parameter_id,plr.lab_date,plr.rec_date,rlt.description order by lab_date desc"));

        $patient_lab1        = PatientLabRecs::select(DB::raw("distinct to_char( max(created_at) at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') as date,
                                                                lab_test_id, (case when lab_date is null then rec_date else lab_date end) as lab_date, lab_test_parameter_id, reading, high_val,
                                                                (case when lab_date is null then '0' else '1' end) as lab_date_exist, notes"))
                                                ->where('patient_id', $uid)
                                                ->with(['labTest','labsParameters'])
                                                ->whereMonth('created_at','=', date('m'))
                                                ->whereYear('created_at','=', date('Y'))
                                                ->groupBy('lab_test_parameter_id')
                                                ->groupBy('reading')
                                                ->groupBy('high_val')
                                                ->groupBy('lab_test_id')
                                                ->groupBy('lab_date')
                                                ->groupBy('rec_date')
                                                ->groupBy('notes')
                                                ->get('id')->toArray();
        // dd($patient_lab1);
        $patientLabDetails   = [];
        $labInc = 0;
        foreach($patient_lab1 as $key => $value) {
            $lab_test_id = $value['lab_test_id'];
            $date = strtotime($value['lab_date']);
            $parameter_id = !empty($value['labs_parameters'][0]['id'])?$value['labs_parameters'][0]['id']:0;
            $parameter = !empty($value['labs_parameters'][0]['parameter'])?$value['labs_parameters'][0]['parameter']:0;
            $patientLabDetails[$date][$lab_test_id]['date'] =  $value['date'];
            $patientLabDetails[$date][$lab_test_id]['lab_date'] =  $value['lab_date'];
            $patientLabDetails[$date][$lab_test_id]['lab_date_exist'] =  $value['lab_date_exist'];
            $patientLabDetails[$date][$lab_test_id]['notes'] =  $value['notes'];
            $patientLabDetails[$date][$lab_test_id]['lab_name'] =  $value['lab_test']['description'];
            $patientLabDetails[$date][$lab_test_id]['lab_details'][$parameter]['reading'] =  $value['reading'];
            $patientLabDetails[$date][$lab_test_id]['lab_details'][$parameter]['high_val'] =  $value['high_val'];
            $labInc++;
        }
        //========basic info
        $patient_enroll_date = PatientServices::latest_module($uid, $module_id); 
        $patient_services    = PatientHealthServices::where("patient_id",$uid)
                                
                                ->whereMonth('updated_at','>=',Carbon::now())//->subMonth()->month)
                                ->whereYear('updated_at','>=',Carbon::now())
                                ->select(DB::raw('DATE(updated_at)date'),'type','specify','purpose','brand','frequency','service_start_date','service_end_date','notes')
                                ->get();
                                //dd($patient_services);

        $patient_services1   = PatientHealthServices::where("patient_id",$uid)
                                    
                                ->whereMonth('updated_at','>=',Carbon::now())//->subMonth()->month)
                                ->whereYear('updated_at','>=',Carbon::now())
                                ->select(DB::raw('DATE(updated_at) dateval'))->distinct() 
                                ->get();

        foreach($patient_services1 as $key=>$value) {
            $d        = $value->dateval;
            $i        = 0;
            // $patient_services = DB::table('patients.patient_healthcare_services as phs')
            //                     ->leftjoin('ren_core.health_services as rhs','phs.hid', '=', 'rhs.id')
            //                     ->where("patient_id",$uid)
            //                     ->whereDate('phs.updated_at','=',$value->date)
            //                     ->select(DB::raw("DATE(phs.updated_at) as newdate,
            //                     to_char(phs.service_start_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') 
            //                     as service_start_date,to_char(phs.service_end_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') 
            //                     as service_end_date"), 
            //                     'phs.type as type','rhs.type as name','specify','purpose','brand','frequency','notes')
            //                     ->get()->toArray();
            $patient_services = PatientHealthServices::leftjoin('ren_core.health_services as rhs','patients.patient_healthcare_services.hid', '=', 'rhs.id')
                                ->select(DB::raw("DATE(patients.patient_healthcare_services.updated_at) as newdate,                             
                                to_char(patients.patient_healthcare_services.service_start_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY') as service_start_dt,
                                to_char(patients.patient_healthcare_services.service_end_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY') as service_end_dt"), 
                                'patients.patient_healthcare_services.type as type', 'patients.patient_healthcare_services.specify', 'patients.patient_healthcare_services.purpose', 
                                'patients.patient_healthcare_services.brand', 'patients.patient_healthcare_services.frequency', 'patients.patient_healthcare_services.notes')                                
                                ->where("patients.patient_healthcare_services.patient_id",$uid)
                                ->where("patients.patient_healthcare_services.status",1)
                                ->whereDate('patients.patient_healthcare_services.updated_at','=',$d)
                                ->get();
                                //echo "07-05-2021".$patient_services;
                                //echo "==============================================================================";
            // $patient_services  = HealthServices::get()->toArray();
            // dd($patient_services);
            $patient_services1[$key]->dateval = $patient_services;
            $patient_services1[$key]->displaydate = $d; 
            $i++;
        }
        // ===care team=== 
        $PatientFamily             = PatientFamily::latest($uid,'spouse');
        $PatientCareGiver          = PatientFamily::latest($uid,'care-giver');
        $PatientEmergencyContact   = PatientFamily::latest($uid,'emergency-contact');

        $patientProviders          = PatientProvider::leftjoin('ren_core.practices as pract','practice_id','=','pract.id') 
                                                    ->leftjoin('ren_core.providers as pro','provider_id','=','pro.id') 
                                                    // with(array('Practices' => function($query) {
                                                    //     $query->select('name as practice_name');
                                                    // }))
                                                    // ->with(array('provider' => function($query) {
                                                    //     $query->select('name as provider_name');
                                                    // }))
                                                    ->select(DB::raw("distinct patients.patient_providers.last_visit_date as last_visit_date, pract.name as practice_name, pro.name as provider_name, patients.patient_providers.phone_no, patients.patient_providers.provider_type_id, patients.patient_providers.address, patients.patient_providers.id"))
                                                    ->where('patient_id',$uid)
                                                    ->where('patients.patient_providers.is_active',1)
                                                    //->where('patients.patient_providers.status',1)
                                                    ->whereMonth('patients.patient_providers.updated_at','>=',Carbon::now())//->subMonth()->month)
                                                    ->whereYear('patients.patient_providers.updated_at','>=',Carbon::now())
                                                    ->orderBy('patients.patient_providers.provider_type_id')
                                                    ->get();
        $services                  = Module::where('patients_service',1)->get();

        // dd($patientProviders );
        // dd($route);
        if($route =='generate-docx'){
            $html_view= View::make('Ccm::print-care-plan.print-care-plan-pdf',compact('subModule', 'patient', 
                        'patientDiagnosisDetails',
                        'PatientDiagnosis',
                        'PatientDiag', 
                        'patient_cmnt', 
                        'patient_providersusers', 'caremanager', 'electronic_sign', 
                        'PatientAllergy1', 'PatientMedication1', 'last_time_spend', 'patient_demographics', 
                        'patient_providers','medication','patient_enroll_date','services','patient_vitals',
                        'patientLabDetails',
                        'patient_lab1',
                        'PatientFamily','PatientCareGiver','PatientEmergencyContact',  'patientProviders', 
                        'patient_services1', 'patient_healthdata',
                        'patient_imaging',));
        $content='';
        $content.= preg_replace("/<img[^>]+\>/i", " ", $html_view); 
       // $file_name = strtotime(date('Y-m-d')).'-'.$uid.'-'.'careplan.doc';
        $file_name = strtotime(date('Y-m-d')).'-'.$uid.'-'.'careplandevelopment.doc';

        $headers = array(
            "Content-type"=>"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "Content-Disposition"=>"attachment;Filename=$file_name",
            "Content-Transfer-Encoding"=> "binary"
        );
        return Response::make($content,200, $headers);
        } else {
        
        PDF::setOptions(['dpi' => 96]);
        $pdf  = PDF::loadView('Ccm::print-care-plan.print-care-plan-pdf', compact('subModule', 'patient', 
                                'patientDiagnosisDetails',
                                'PatientDiagnosis',
                                'PatientDiag', 
                                'patient_cmnt', 
                                'patient_providersusers', 'caremanager', 'electronic_sign', 
                                'PatientAllergy1', 'PatientMedication1', 'last_time_spend', 'patient_demographics', 
                                'patient_providers','medication','patient_enroll_date','services','patient_vitals',
                                'patientLabDetails',
                                'patient_lab1',
                                'PatientFamily','PatientCareGiver','PatientEmergencyContact',  'patientProviders', 
                                // 'PatientSpecialistProvider',
                                // 'PatientDentistProvider', 
                                // 'PatientVisionProvider', 
                                'patient_services1', 'patient_healthdata',
                                'patient_imaging'
                            ));
        return $pdf->stream('careplandevelopment.pdf', array('Attachment'=>0));  

        // $data = [
        //     'subModule' => $subModule,
        //     'date' => date('m/d/Y')
        // ];
          
        // $pdf = PDF::loadView('myPDF', $data);
    
        // return $pdf->download('itsolutionstuff.pdf');








        }//end else
    }
}
