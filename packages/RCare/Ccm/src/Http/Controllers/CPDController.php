<?php

namespace RCare\Ccm\Http\Controllers;
use App\Http\Controllers\Controller;
use RCare\System\Http\Controllers\CommonFunctionController;
// use RCare\Ccm\Models\CallPreparation;
// use RCare\Ccm\Models\CallStatus;
// use RCare\Ccm\Models\CallHipaaVerification;
// use RCare\Ccm\Models\CallHomeServiceVerification;
// use RCare\Ccm\Models\CallClose;
// use RCare\Ccm\Models\CallWrap;
// use RCare\Ccm\Models\FollowUp;
// use RCare\Ccm\Models\TextMsg;

use RCare\Patients\Models\PatientAllergy;
// use RCare\Org\OrgPackages\Providers\src\Models\ProviderSubtype;
use RCare\Patients\Models\PatientHealthServices;
use RCare\Patients\Models\PatientFamily;
use RCare\Patients\Models\Patients;
// use RCare\Patients\Models\PatientAddress;
use RCare\Patients\Models\PatientVitalsData;
// use RCare\Patients\Models\PatientLabRecs; 
use RCare\Patients\Models\PatientDemographics;
use RCare\Patients\Models\PatientProvider;
use RCare\Patients\Models\PatientMedication;
use RCare\Org\OrgPackages\Medication\src\Models\Medication;
use RCare\Org\OrgPackages\Diagnosis\src\Models\Diagnosis;
use RCare\Patients\Models\PatientDiagnosis;
// use RCare\Patients\Models\PatientTravel;
// use RCare\Patients\Models\PatientHobbies;
// use RCare\Patients\Models\PatientPet;
use RCare\Patients\Models\PatientServices;
// use RCare\Org\OrgPackages\Diagnosis\src\Models\DiagnosisCode;
 use RCare\Patients\Models\PatientHealthData;
 use RCare\Patients\Models\PatientImaging;
// use RCare\Ccm\src\Http\Requests\AllergiesAddRequest;
// use RCare\Ccm\src\Http\Requests\ServicesAddRequest;
// use RCare\Ccm\src\Http\Requests\PatientsFamilyAddRequest;
// use RCare\Ccm\src\Http\Requests\PatientsDataAddRequest;
// use RCare\Ccm\src\Http\Requests\PatientsVitalsDataAddRequest;
// use RCare\Ccm\src\Http\Requests\PatientsProviderAddRequest;
// use RCare\Ccm\src\Http\Requests\PatientsMedicationAddRequest;
// use RCare\Ccm\src\Http\Requests\PatientsTravelAddRequest;
// use RCare\Ccm\src\Http\Requests\PatientsHobbiesAddRequest;
// use RCare\Ccm\src\Http\Requests\PatientsPetAddRequest;
// use RCare\Ccm\src\Http\Requests\PatientsDiagnosisRequest;
// use RCare\Ccm\src\Http\Requests\PatientsLabRequest;
use RCare\Org\OrgPackages\Modules\src\Models\Module;
use RCare\Org\OrgPackages\StageCodes\src\Models\StageCode;
use RCare\Org\OrgPackages\HealthServices\src\Models\HealthServices;
use RCare\Org\OrgPackages\CarePlanTemplate\src\Models\CarePlanTemplate;
// use RCare\Ccm\Models\ContentTemplateUsageHistory;
use RCare\Org\OrgPackages\QCTemplates\src\Models\QuestionnaireTemplate;
// use RCare\Ccm\Models\QuestionnaireTemplatesUsageHistory;
use RCare\Org\OrgPackages\QCTemplates\src\Models\ContentTemplate;
use RCare\Rpm\Models\Observation_Oxymeter;
use RCare\Rpm\Models\Observation_BP;
use RCare\Rpm\Models\Observation_Heartrate;
use RCare\Rpm\Models\Observation_Weight;
use RCare\Rpm\Models\Observation_Temp;
use RCare\Rpm\Models\Observation_Spirometer;
use RCare\Rpm\Models\Observation_Glucose;
use RCare\TaskManagement\Models\UserPatients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use DataTables;
use Carbon\Carbon;
use PDF;
use Session; 
use Auth;
use RCare\Ccm\Http\Controllers\CcmController;

class CPDController extends Controller
{

    public function rpmDeviceReport(Request $Request) {
         $fdt ='2021-04-01 00:00:00'; 
         $tdt ='2021-04-30 23:59:59';
         $patient_id =1605781862;
         $patientid= 1605781862;

       $query="select distinct x.effdatetime,x.patient_id,bp.systolic_qty, bp.diastolic_qty, hr.resting_heartrate, wt.weight, ox.oxy_qty, temp.bodytemp, glc.value, spt.fev_value,spt.pef_value  from 
       ((select distinct effdatetime,patient_id from rpm.observations_heartrate where effdatetime between '$fdt' and '$tdt' and patient_id=$patientid) 
         UNION 
        (select distinct effdatetime,patient_id from rpm.observations_oxymeter where effdatetime between '$fdt' and '$tdt' and patient_id=$patientid )
        union
        (select distinct effdatetime,patient_id from rpm.observations_bp where effdatetime between '$fdt' and '$tdt' and patient_id=$patientid )
        union
        (select distinct effdatetime,patient_id from rpm.observations_weight where effdatetime between '$fdt' and '$tdt' and patient_id=$patientid )
        union
        (select distinct effdatetime,patient_id from rpm.observations_temp where effdatetime between '$fdt' and '$tdt' and patient_id=$patientid )
        union
        (select distinct effdatetime,patient_id from rpm.observations_glucose where effdatetime between '$fdt' and '$tdt' and patient_id=$patientid )
        union
        (select distinct effdatetime,patient_id from rpm.observations_spirometer where effdatetime between '$fdt' and '$tdt' and patient_id=$patientid )
        ) x 
       left join rpm.observations_bp bp on bp.effdatetime =x.effdatetime 
       left join rpm.observations_heartrate hr on hr.effdatetime =x.effdatetime 
       left join rpm.observations_weight wt on wt.effdatetime =x.effdatetime 
       left join rpm.observations_oxymeter ox on ox.effdatetime =x.effdatetime
       left join rpm.observations_temp temp on temp.effdatetime =x.effdatetime
       left join rpm.observations_glucose glc on glc.effdatetime =x.effdatetime
       left join rpm.observations_spirometer spt on spt.effdatetime =x.effdatetime
        where x.effdatetime between '$fdt' and '$tdt' and x.patient_id=$patientid";
   
  $data  = DB::select( DB::raw($query) );

            $datetime1 = Observation_BP::where('patient_id',$patient_id)->pluck('effdatetime');
            $arraydtetime1 = $datetime1->toArray();

            $datetime2 = Observation_Heartrate::orderBy('effdatetime')->where('patient_id',$patient_id)->pluck('effdatetime');
            $arraydtetime2 = $datetime2->toArray();

            $uniArray = array_unique(array_merge($arraydtetime1,$arraydtetime2));

            $readingSys = Observation_BP::where('patient_id',$patient_id)->pluck('systolic_qty'); 
            $arrayreading1 = $readingSys->toArray();
            $label1 = "Systolic(mmHg)";

            $readingDys = Observation_BP::where('patient_id',$patient_id)->pluck('diastolic_qty'); 
            $arrayreading2 = $readingDys->toArray();
            $label2 = "Diastolic(mmHg)";

            $readingHrt = Observation_Heartrate::where('patient_id',$patient_id)->pluck('resting_heartrate'); 
            $arrayreading3 = $readingHrt->toArray();
            $label3 = "Heart-Rate(beats/minute)";
            $countHrt = count($arrayreading3);
  // dd($data);
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'verdana']);
         $pdf = PDF::loadView('Rpm::monthlyService.device-data-report-pdf',

            //$pdf = PDF::loadView('Rpm::chart-new.newchartfile_2_daily',
            compact('data','uniArray','arrayreading1','arrayreading2','arrayreading3','label1','label2','label3'));
          $pdf->setOptions(['enable-javascript'=>true]);
          $pdf->setOptions(['javascript-delay' => 5000]);
        return $pdf->stream('device-data-report.pdf', array('Attachment'=>0));
    }
    public function Services_Dme_list(Request $Request) {
        $id          = sanitizeVariable($Request->route('id'));
        $servicetype = sanitizeVariable($Request->route('servicetype'));     
        $lastmonth   = date('m', strtotime(date('m')." -1 month"));
        $dataexist   = PatientHealthServices::where("patient_id", $id)->where("hid",$servicetype)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->exists();
        if($dataexist==true) {
            $data = PatientHealthServices::where("patient_id", $id)->where("hid",$servicetype)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->get();
        } else {
            $data = PatientHealthServices::where("patient_id", $id)->where("hid",$servicetype)->whereMonth('created_at', $lastmonth)->whereYear('created_at', date('Y'))->get();
        }
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($row){
        $btn ='<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="editservice" title="Edit"><i class=" editform i-Pen-4"></i></a>';  
        return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
 
    //print care plaan
        public function cpdpatientCarePlan($patientId) {
       // $this->middleware(Auth::login());
       if(Session::has('role')){
        
        }else{
           $url = url('').'/rcare-login';
            echo '<script>window.location.href="/rcare-login";</script>';
        }
        $uid          = sanitizeVariable($patientId);
        $module_id    = getPageModuleName();
        $component_id = getPageSubModuleName();       
        $patient      = Patients::with('patientServices', 'patientServices.module')->where('id',$uid)->get();
        $PatientDiagnosis = DB::select( DB::raw("select distinct code,condition,
        max(updated_at) as date
        FROM patients.patient_diagnosis_codes 
        where updated_at >= date_trunc('month', current_date)  
        AND  updated_at >= date_trunc('year', current_date)
        AND patient_id = '".$uid."'
        AND status = 1 
        group  by code,condition
        order by date desc 
        "));
        $PatientDiag =DB::select(DB::raw("select distinct code,condition,jsonb(goals) as goals,jsonb(symptoms) as symptoms ,jsonb(tasks) as tasks,
        max(updated_at) as date
        from patients.patient_diagnosis_codes
        WHERE  updated_at >= date_trunc('month', current_date)  
        AND  updated_at >= date_trunc('year', current_date) 
        AND patient_id = '".$uid."'
        AND status = 1 
        group  by code,condition,jsonb(patient_diagnosis_codes.goals),jsonb(patient_diagnosis_codes.symptoms),jsonb(patient_diagnosis_codes.tasks)
        order by date desc"));
        $patient_cmnt =DB::select(DB::raw("select distinct comments,updated_at,
        max(updated_at) as date
        FROM patients.patient_diagnosis_codes 
        WHERE  updated_at >= date_trunc('month', current_date)  
        AND  updated_at >= date_trunc('year', current_date)  
        AND patient_id = '".$uid."' 
        AND status = 1 
        group  by comments,updated_at
        order by updated_at desc"));

        $PatientAllergy1= PatientAllergy::where('patient_id', $uid)
        ->whereMonth('updated_at','>=',Carbon::now())//->subMonth()->month
        ->whereYear('updated_at','>=',Carbon::now())
        ->where('status', '=', 1) //add by priya on 13th may2021
        ->select(DB::raw('DATE(updated_at)date'))->distinct()
        ->get()->toArray();
        foreach($PatientAllergy1 as $key=>$value){
            $d = $value['date'];
            $i=0;                              
            $PatientAllergy= PatientAllergy::where('patient_id', $uid)
            ->whereDate('created_at','=',$value['date'])
            // ->where('specify','!=','no Allergies')
            ->where('status', '=', 1) //add by priya on 13th may2021
            ->select(DB::raw('DATE(created_at)date'),'allergy_type','specify','type_of_reactions','severity','course_of_treatment','allergy_status')
            ->get()->toArray();

            $PatientAllergy1[$key]['date'] =  $PatientAllergy;
            $PatientAllergy1[$key]['displaydate'] = $d;
            $i++;
        }
          $updated_sign = //PatientDiagnosis::with('users_updated_by')->where('patient_id',$uid)->orderBy('id', 'desc')->limit(1)->get();
          \DB::table('patients.patient_diagnosis_codes as pdc')
          ->leftjoin('ren_core.users as u','pdc.updated_by','=','u.id')
          ->select('u.f_name','u.l_name')
          ->where('patient_id',$uid)
          ->where('status',1)
          ->orderby('pdc.id','desc')->limit(1)->get();
          // dd($updated_sign); 
          $created_sign = //PatientDiagnosis::with('users_created_by')->where('patient_id',$uid)->orderBy('id', 'desc')->get();
          \DB::table('patients.patient_diagnosis_codes as pdc')
          ->leftjoin('ren_core.users as u','pdc.created_by','=','u.id') 
          ->select('u.f_name','u.l_name')
          ->where('patient_id',$uid)
          ->where('status',1) 
          ->orderby('pdc.id','desc')->limit(1)->get();
           // dd($created_sign);
          $electronic_sign = isset($updated_sign) ? $created_sign : $updated_sign ;
          // dd($electronic_sign);
        // $updated_sign = PatientDiagnosis::with('users_updated_by')->where('patient_id',$uid)->orderBy('id', 'desc')->get();
        // $created_sign = PatientDiagnosis::with('users_created_by')->where('patient_id',$uid)->orderBy('id', 'desc')->get();
        // $electronic_sign = isset($updated_sign) ? $updated_sign : $created_sign;
        $PatientMedication1 =DB::select(DB::raw("select distinct med_id,rm.description ,purpose,dosage,frequency,route,
        max(pm2.updated_at) as date
        from patients.patient_medication pm2 
        left join ren_core.medication rm on pm2.med_id = rm.id
        WHERE  pm2.created_at >= date_trunc('month', current_date)
        AND  pm2.created_at >= date_trunc('year', current_date)  AND patient_id = '".$uid."'
        AND pm2.status =1
        group by med_id,rm.description,purpose,dosage,frequency,route order by rm.description asc
        "));
        
        $last_time_spend = CommonFunctionController::getCcmNetTime($uid, $module_id);
        $patient_demographics = PatientDemographics::where('patient_id', $uid)->get();
        $patient_providers = PatientProvider::where('patient_id', $uid)->where('status',1)
        ->with('practice')
        ->with('provider')
        ->with('users')
        ->where('provider_type_id',1)->orderby('id','desc')->get();
        $patient_providersusers = PatientProvider::where('patient_id', $uid)->where('status',1)
        ->with('users')->get();
        // $caremanager =  \DB::table('patients.patient_diagnosis_codes as pdc')
        // ->join('ren_core.users as u','pdc.updated_by','=','u.id') 
        // ->where('patient_id',$uid)
        // ->orderby('pdc.id','desc')->get();

         $caremanager =  \DB::table('task_management.user_patients as up')
        ->join('ren_core.users as u','up.user_id','=','u.id') 
        ->where('patient_id',$uid)
        ->where('up.status',1)
        ->orderby('up.id','desc')->get();

        $medication = Medication::where("status", 1)->get();
        //vitals and Lab Data
        $dateS = Carbon::now()->startOfMonth()->subMonth(1);
        $dateE = Carbon::now()->endOfMonth(); 
        $patient_vitals = DB::select(DB::raw("select distinct height ,weight ,bmi,bp,o2,pulse_rate,diastolic,oxygen,notes, max(updated_at ) as date from patients.patient_vitals pv 
        WHERE  created_at >= date_trunc('month', current_date)
        AND  created_at >= date_trunc('year', current_date)  AND patient_id = '".$uid."'
        group by height ,weight ,bmi,bp,o2,pulse_rate,diastolic,oxygen,notes order by date desc"));
        $patient_healthdata =PatientHealthData::where('patient_id', $patientId)
        // ->where('created_at', PatientHealthData::max('created_at'))
        //->select(DB::raw("distinct health_data, max(updated_at) as updated_at, health_date"))//,max(updated_at) as updated_at
        ->select(DB::raw("distinct health_data,health_date"))
        ->whereMonth('updated_at','=', date('m')) 
        ->whereYear('updated_at','=', date('Y'))
        ->groupBy('health_data','health_date')
        ->orderBy('health_date','desc')->get();
        $patient_imaging =PatientImaging::where('patient_id', $patientId)
        // ->where('created_at', PatientImaging::max('created_at'))
        // ->select(DB::raw("distinct imaging_details,  max(updated_at) as updated_at, imaging_date"))//,max(updated_at) as updated_at
        ->select(DB::raw("distinct imaging_details,imaging_date"))
        ->whereMonth('updated_at','=', date('m'))
        ->whereYear('updated_at','=', date('Y')) 
        ->groupBy('imaging_details','imaging_date')
        ->orderBy('imaging_date','desc')->get();
        $patient_lab1= DB::select(DB::raw("select distinct  max(DATE(plr.created_at))as date,plr.lab_test_id,(case when plr.lab_date is null then plr.rec_date else plr.lab_date end) as lab_date,(case when rlt.description is null then 'Other' else rlt.description end)  from patients.patient_lab_recs plr
        left join ren_core.rcare_lab_tests rlt on rlt.id=lab_test_id 
        where plr.lab_test_id is not null and EXTRACT(Month from plr.created_at) = EXTRACT(MONTH FROM CURRENT_DATE) AND EXTRACT(YEAR from plr.created_at) = EXTRACT(YEAR FROM CURRENT_DATE) and plr.patient_id='".$uid."' group  by plr.lab_test_id,plr.lab_test_parameter_id,plr.lab_date,plr.rec_date,rlt.description order by lab_date desc"));
        foreach($patient_lab1 as $key=>$value) {
            $d = $value->date;                          
            $i=0; 
            $patient_lab = DB::select(DB::raw("select rlt.description,rltpr.parameter,(case when plr.lab_date is null then plr.rec_date else plr.lab_date end) as lab_date,plr.reading ,plr.high_val,plr.notes,(case when plr.lab_date is null then '0' else '1' end) as lab_date_exist from patients.patient_lab_recs plr
            left join ren_core.rcare_lab_tests rlt on rlt.id=plr.lab_test_id 
            left join ren_core.rcare_lab_test_param_range rltpr on plr.lab_test_parameter_id = rltpr.id and rltpr.status=1
            where EXTRACT(Month from plr.created_at) = EXTRACT(MONTH FROM CURRENT_DATE) AND EXTRACT(YEAR from plr.created_at) = EXTRACT(YEAR FROM CURRENT_DATE) and rlt.status=1 and
            date(plr.created_at)='".$value->date."' and plr.lab_test_id ='".$value->lab_test_id."' and plr.patient_id ='".$uid."' order By lab_date desc"));

            $labdate='';
            if(isset($patient_lab[0]->lab_date)) {
                $labdate=$patient_lab[0]->lab_date;
            }
            $notes=''; 
            if(isset($patient_lab[0]->notes)) {
                $notes=$patient_lab[0]->notes;
            }
            $patient_lab1[$key]->date =  $patient_lab;
            $patient_lab1[$key]->displaydate=$labdate;
            $patient_lab1[$key]->notes=$notes;
            if(isset($patient_lab[0]->lab_date_exist)){
                $patient_lab1[$key]->lab_date_exist=$patient_lab[0]->lab_date_exist;
               }else{
                $patient_lab1[$key]->lab_date_exist= 0;
               }           
            $i++; 
        }
        //========basic info
        $patient_enroll_date = PatientServices::latest_module($uid, $module_id);
        $patient_services = DB::table('patients.patient_healthcare_services')
        ->where("patient_id",$uid)
        ->where('status',1)
        ->whereMonth('updated_at','>=',Carbon::now())//->subMonth()->month)
        ->whereYear('updated_at','>=',Carbon::now())
        ->select(DB::raw('DATE(updated_at)date'),'type','specify','purpose','brand','frequency','service_start_date','service_end_date','notes')
        ->get();
        $patient_services1 = DB::table('patients.patient_healthcare_services')
        ->where("patient_id",$uid)
        ->where('status',1)
        ->whereMonth('updated_at','>=',Carbon::now())//->subMonth()->month)
        ->whereYear('updated_at','>=',Carbon::now())
        ->select(DB::raw('DATE(updated_at)date'))->distinct() 
        // ->orderBy('updated_at','desc')
        ->get();
        foreach($patient_services1 as $key=>$value) {
            $configTZ = config('app.timezone');
            $userTZ = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
            $d = $value->date;
            $i=0; 
            $patient_services = DB::table('patients.patient_healthcare_services as phs')
            ->leftjoin('ren_core.health_services as rhs','phs.hid', '=', 'rhs.id')
            ->where("patient_id",$uid) 
            ->where('status',1)
            ->whereDate('phs.updated_at','=',$value->date)
            ->select(DB::raw("DATE(phs.updated_at) as newdate,to_char(phs.service_start_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') 
            as service_start_date,to_char(phs.service_end_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') 
            as service_end_date"), 
            'phs.type as type','rhs.type as name','specify','purpose','brand','frequency','notes')
            ->orderBy('phs.updated_at','desc') 
            ->get()->toArray();
            $patient_services1[$key]->date = $patient_services;
            $patient_services1[$key]->displaydate = $d;
            $i++;
        }
        // ===care team=== 
        $PatientFamily             = PatientFamily::latest($patientId,'spouse');
        $PatientCareGiver          = PatientFamily::latest($patientId,'care-giver');
        $PatientEmergencyContact   = PatientFamily::latest($patientId,'emergency-contact');
        $configTZ                  = config('app.timezone');
        $userTZ                    = Session::get('timezone') ? Session::get('timezone') : config('app.timezone');
        $PatientSpecialistProvider = DB::select(DB::raw("select distinct p1.name as practice_name,p2.name as provider_name,
                                      pp.phone_no ,pp.address, to_char(pp.last_visit_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') 
                                      as last_visit_date 
                                      FROM patients.patient_providers pp 
                                      left join ren_core.practices p1 on pp.practice_id =p1.id
                                      left join ren_core.providers p2 on pp.provider_id =p2.id
                                      WHERE  pp.updated_at >= date_trunc('month', current_date)
                                      AND  pp.updated_at >= date_trunc('year', current_date) 
                                      and pp.provider_type_id =2 and pp.patient_id='".$patientId."'
                                      and pp.status =1
                                      "));
        $PatientDentistProvider    = DB::select(DB::raw("select distinct p1.name as practice_name,p2.name as provider_name,
                                      pp.phone_no ,pp.address,pp.id,to_char(pp.last_visit_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') 
                                      as last_visit_date  
                                      FROM patients.patient_providers pp 
                                      left join ren_core.practices p1 on pp.practice_id =p1.id
                                      left join ren_core.providers p2 on pp.provider_id =p2.id
                                      WHERE  pp.updated_at >= date_trunc('month', current_date)
                                      AND  pp.updated_at >= date_trunc('year', current_date) 
                                      and pp.provider_type_id =4
                                      AND patient_id = '".$patientId."'
                                      AND pp.status =1
                                      group by p1.name,p2.name,pp.phone_no ,pp.address, pp.last_visit_date ,pp.id 
                                      order by pp.id desc limit 1
                                      "));

        $PatientVisionProvider     = DB::select(DB::raw("select distinct p1.name as practice_name,p2.name as provider_name,
                                      pp.phone_no ,pp.address ,pp.id ,to_char(pp.last_visit_date at time zone '".$configTZ."' at time zone '".$userTZ."', 'MM-DD-YYYY HH24:MI:SS') 
                                      as last_visit_date 
                                      FROM patients.patient_providers pp 
                                      left join ren_core.practices p1 on pp.practice_id =p1.id
                                      left join ren_core.providers p2 on pp.provider_id =p2.id
                                      WHERE  pp.updated_at >= date_trunc('month', current_date)
                                      AND  pp.updated_at >= date_trunc('year', current_date) 
                                      and pp.provider_type_id =3
                                      AND patient_id = '".$patientId."'
                                      AND pp.status =1
                                      group by p1.name,p2.name,pp.phone_no ,pp.address, pp.last_visit_date ,pp.id 
                                      order by pp.id desc limit 1
                                      "));
        $services                  = Module::where('patients_service',1)->where('status',1)->get();

        PDF::setOptions(['dpi' => 96, 'defaultFont' => 'serif','fontHeightRatio' => 1.3]);
        // $pdf                       = PDF::loadView('Ccm::care-plan-development.cpd-patient-care-plan',
        // compact('patient_providersusers','caremanager','patient','PatientDiagnosis','PatientDiag',
        // 'electronic_sign','PatientAllergy1','PatientMedication1','last_time_spend','patient_demographics','patient_providers','medication',
        // 'patient_enroll_date','services','patient_vitals','patient_lab1','PatientFamily','PatientCareGiver',
        // 'PatientEmergencyContact','PatientSpecialistProvider','PatientDentistProvider','PatientVisionProvider',
        // 'patient_services1','patient_cmnt',
        // 'patient_healthdata','patient_imaging'));   
        $pdf                       = PDF::loadView('Ccm::care-plan-development.cpd-patient-care-plan',
        compact('patient_providersusers','caremanager','patient','PatientDiagnosis','PatientDiag',
        'electronic_sign','PatientAllergy1','PatientMedication1','last_time_spend','patient_demographics','patient_providers','medication',
        'patient_enroll_date','services','patient_vitals','patient_lab1','PatientFamily','PatientCareGiver',
        'PatientEmergencyContact','PatientSpecialistProvider','PatientDentistProvider','PatientVisionProvider',
        'patient_services1','patient_cmnt',
        'patient_healthdata','patient_imaging'));   
        return $pdf->stream('careplandevelopment.pdf', array('Attachment'=>0));                
    }

    public function cpdpdfview($patientId){
        $uid                  = sanitizeVariable($patientId);
        $module_id            = getPageModuleName();
        $component_id         = getPageSubModuleName();       
        $patient              = Patients::with('patientServices', 'patientServices.module')->where('id',$uid)->get();
        $PatientDiagnosis     = DB::select( DB::raw("select distinct (code), condition, patient_id  from patients.patient_diagnosis_codes where patient_id = ".$uid) );
        $PatientDiag          = PatientDiagnosis::where('patient_id',$uid)->orderBy('id', 'desc')->get();
        $PatientAllergy       = PatientAllergy::where('patient_id', $uid)->get();
        $PatientMedication    = PatientMedication::with('medication')->where('patient_id', $uid)->get();
        $last_time_spend      = CommonFunctionController::getCcmNetTime($uid, $module_id);
        $patient_demographics = PatientDemographics::where('patient_id', $uid)->get();
        $patient_providers    = PatientProvider::where('patient_id', $uid)->get();
        $medication           = Medication::where("status", 1)->get();
        $patient_enroll_date  = PatientServices::latest_module($uid, $module_id);
        $services             = Module::where('patients_service',1)->where('status',1)->get();
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'verdana']);
        $pdf                  = PDF::loadView('Ccm::care-plan-development.cpdPDF', compact('patient','PatientDiagnosis','PatientDiag','PatientAllergy','PatientMedication','last_time_spend','patient_demographics','patient_providers','medication','patient_enroll_date','services'));
        return $pdf->stream('careplandevelopment.pdf', array('Attachment'=>0));
    }

    public function CreateModifyPatientCarePlan($patientId,$id) {
        $uid                  = sanitizeVariable($patientId);
        $module_id            = getPageModuleName();
        $component_id         = getPageSubModuleName();       
        $patient              = Patients::with('patientServices', 'patientServices.module')->where('id',$uid)->get();
        $PatientDiagnosis     = DB::select( DB::raw("select distinct (code), condition, patient_id  from patients.patient_diagnosis_codes where patient_id = ".$uid) );
        $PatientDiag          = PatientDiagnosis::where('patient_id',$uid)->orderBy('id', 'desc')->get();
        $PatientAllergy       = PatientAllergy::where('patient_id', $uid)->get();
        $PatientMedication    = PatientMedication::with('medication')->where('patient_id', $uid)->get();
        $last_time_spend      = CommonFunctionController::getCcmNetTime($uid, $module_id);
        $patient_demographics = PatientDemographics::where('patient_id', $uid)->get();
        $patient_providers    = PatientProvider::where('patient_id', $uid)->get();
        $medication           = Medication::where("status", 1)->get();
        //vitals and Lab Data
        $patient_vitals       = PatientVitalsData::where("patient_id",$uid)->get();
        //========basic info
        $patient_enroll_date  = PatientServices::latest_module($uid, $module_id);
        $services             = Module::where('patients_service',1)->where('status',1)->get();
        PDF::setOptions(['dpi' => 96, 'defaultFont' => 'serif','fontHeightRatio' => 1.3]);
        $pdf                  = PDF::loadView('Ccm::care-plan-development.patient-care-plan',compact('patient','PatientDiagnosis','PatientDiag','PatientAllergy','PatientMedication','last_time_spend','patient_demographics','patient_providers','medication','patient_enroll_date','services','patient_vitals','patient_lab_test_name','patient_lab'));
        return $pdf->stream('careplandevelopment.pdf', array('Attachment'=>0));
    }
}
?>