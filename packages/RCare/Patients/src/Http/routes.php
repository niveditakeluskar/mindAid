<?php

use RCare\Patients\Models\Patients;
use RCare\System\Support\Form;
/*
|--------------------------------------------------------------------------
| RCare / Patients Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/test1", "RCare\Patients\Http\Controllers\DashboardController@test")->name("home");
Route::middleware(["auth", "roleAccess", "web"])->group(function () {
    Route::prefix('patients')->group(function () {
        //patientStatus        

        // //to-do-list -> moved to task management
        // Route::get("/patient-to-do/{patientId}/{moduleId}/list", "RCare\Patients\Http\Controllers\PatientController@getToDoListData")->name("ajax.to.do.list");

        //###################################################################----Patient Enrollment New Screens----############################################################################//

        Route::get('/patient-enroll', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@fetchPatientsEnroll')->name('patients-enroll');

        //--------------------------------------Patient Registration--------------------------------------------------///////////
        // Route::view('/patient-registration', 'Patients::patient.patient-registration');
        Route::get('/patient-registration', 'RCare\Patients\Http\Controllers\PatientController@getPatientRegistrationForm')->name("patient.registration.form");

        // Route::view('/registered-patient-list', 'Patients::patient.registered-patient-list');
        // Route::get('/edit-patients-list', 'RCare\Patients\Http\Controllers\PatientController@fetchRegisteredPatients')->name('registered_patients_list');
        Route::get('/registered-patient-list', function () {
            return view('Patients::patient.registered-patient-list');
        })->name('patients-registered-patients');

        // Route::get("/ajax/patient/{practice}/patient", "RCare\Patients\Http\Controllers\PatientController@practicePatients")->name("ajax.practice.patient");



        ////////////////////////--------------- New Enrollment Routs----------------///////////////////////
        Route::get('/patient-enrollment/patients', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@listPatientEnrollmentPatients')->name('patient.enrollment.patients');

        // Route::get('/patients-assignment-status', 'RCare\Patients\Http\Controllers\PatientController@CareManagerPatients')->name('patients.assignment.status');
        // Route::get('/patients-assignment-status', function(){
        //     return view('Patients::patient.care-manager-patient-list');
        // })->name('patients.assignment.status');



        // Route::get('/patients-assignment', 'RCare\Patients\Http\Controllers\PatientController@assignedPatients')->name('patients.assignment');
        Route::get('/patients-assignment', function () {
            return view('Patients::patient.assigned-patient-list');
        })->name('patients.assignment');




        //------------------------------Mail Controller---------------------------------------------//
        // Route::get('/testJson', 'RCare\Patients\Http\Controllers\QuestionnaireController@testJsonArr')->name('testJson');

        // Route::get('/addTemplate', 'RCare\Patients\Http\Controllers\MailTemplateController@index')->name('mail');
        // Route::get('/listmailtemplate', 'RCare\Patients\Http\Controllers\MailTemplateController@ListMail')->name('listmailtemplate');
        // Route::get('deletemailtemplate/{id}', 'RCare\Patients\Http\Controllers\MailTemplateController@DeleteTemplate');
        // Route::get('updatemailtemplate/{id}', 'RCare\Patients\Http\Controllers\MailTemplateController@UpdateTemplate');
        // Route::post('SaveRpmTemplate', 'RCare\Patients\Http\Controllers\MailTemplateController@SaveTemplate')->name('SaveRpmTemplate');
        // Route::get('viewTemplateDetails/{id}', 'RCare\Patients\Http\Controllers\MailTemplateController@viewTemplateDetails');

        // Route::get('/rcareservices', 'RCare\Patients\Http\Controllers\MailTemplateController@CreateServices')->name('rcareservices');
        // Route::post('AddServices', 'RCare\Patients\Http\Controllers\MailTemplateController@AddServices')->name('AddServices');


        // Route::post('getOrg', 'RCare\Patients\Http\Controllers\RcareOrgsController@GetCount')->name('getOrg');
        // Route::post('getLic', 'RCare\Patients\Http\Controllers\RcareOrgsController@GetLic')->name('getLic');
        // Route::post('getRoles', 'RCare\Patients\Http\Controllers\RolesController@GetRolesCount')->name('getRoles');
        // Route::post('getUsers', 'RCare\Patients\Http\Controllers\UserController@GetUsersCount')->name('getUsers');
        // Route::get("/org-list", "RCare\Patients\Http\Controllers\RcareOrgsController@fetchRcareOrgs")->name("org_list");

        // Route::get('/questionnaire', 'RCare\Patients\Http\Controllers\QuestionnaireController@index');
        // Route::get('/RpmDecisionTree', 'RCare\Patients\Http\Controllers\QuestionnaireController@inshow');
        // Route::view('get-form', 'Patients::questionnaire.addQuestion');
        // Route::view('get-subform', 'Patients::questionnaire.questionnairetest'); 

        // Route::get('/listQuestionnaire', 'RCare\Patients\Http\Controllers\QuestionnaireController@listQuestionnaire')->name('listRpmQuestionnaire');
        // Route::get('viewQuestionnaireDetails/{id}', 'RCare\Patients\Http\Controllers\QuestionnaireController@viewQuestionnaireDetails');
        // Route::get('deleteQuestionnaire/{id}', 'RCare\Patients\Http\Controllers\QuestionnaireController@deleteQuestionnaire');
        // Route::post('SaveRpmQuestionnaire', 'RCare\Patients\Http\Controllers\QuestionnaireController@SaveQuestionnaire')->name('save-Patients-questionnaire');
        // Route::post('EditDecisionTree', 'RCare\Patients\Http\Controllers\QuestionnaireController@EditDecision')->name('edit-Patients-Decision');
        // Route::get('updateQuestionnaire/{id}', 'RCare\Patients\Http\Controllers\QuestionnaireController@updateQuestionnaire');
        // Route::get('/patients', 'RCare\Patients\Http\Controllers\PatientController@index');
        // Route::get('/patients-list', 'RCare\Patients\Http\Controllers\PatientController@fetchPatients')->name('patients_list');
        // Route::get('/traning-checklist/{id}', 'RCare\Patients\Http\Controllers\PatientController@traningChecklist')->name('traning_checklist');



        // Patient_enrollment-list
        /* commented old enrollment routes
        Route::get('/patients-enroll-list', 'RCare\Patients\Http\Controllers\PatientController@fetchPatientsEnroll')->name('patients_enroll_list');
        Route::get('/patient-enroll-details/{id}', 'RCare\Patients\Http\Controllers\PatientController@fetchPatientEnrollDetails')->name('patient_enroll_details');
        Route::get('/patient-enrollment','RCare\Patients\Http\Controllers\PatientController@patientEnroll')->name('patients-patient_enroll');
        Route::get('/patient-enrollment/{id}', 'RCare\Patients\Http\Controllers\PatientController@enrollTraningChecklist')->name('enroll_patient_checklist');
        Route::post('/fetch-email-contenet','RCare\Patients\Http\Controllers\PatientController@fetchEmailContent')->name('fetch_email_contenet');
        Route::post('/fetch-content','RCare\Patients\Http\Controllers\PatientController@fetchContent')->name('fetch_contenet'); 
        Route::get('/fetch-getCallScriptsById', 'RCare\Patients\Http\Controllers\PatientController@getCallScriptsById')->name('fetch_getCallScriptsById');
        Route::get('test_modal','RCare\Patients\Http\Controllers\MailTemplateController@test_modal')->name('test_modal');
        */
        // 
        // Route::get('/todolist', 'RCare\Patients\Http\Controllers\PatientController@toDolist');
        // Route::post('patient-enrollment-insert','RCare\Patients\Http\Controllers\PatientController@patientEnrollmentInsert')->name('patient_enrollment_insert');
        // Route::post('enrollment-service','RCare\Patients\Http\Controllers\PatientController@patientEnrollmentService')->name('enrollment_service');
        // Route::view('officeVisitModule', 'Patients::questionnaire.officeVisitModule');
        // Route::view('officeVisitModule', 'Patients::questionnaire.officeVisitModule');


        //  // Patients Device Traning
        //  Route::get('/device', 'RCare\Patients\Http\Controllers\PatientController@rpmDevicelist');
        //  Route::get('/step1', 'RCare\Patients\Http\Controllers\PatientController@traningins');
        //  Route::get('/step2', 'RCare\Patients\Http\Controllers\PatientController@traningstep');
        //  Route::get('/step3', 'RCare\Patients\Http\Controllers\PatientController@traningsteplast');
        //  Route::get('/device-patients-list', 'RCare\Patients\Http\Controllers\PatientController@rpmfetchPatients')->name('device_patients_list');

        // Route::get('/device-traning/{id}', 'RCare\Patients\Http\Controllers\PatientController@deviceTraning')->name('device-traning');  


        // Patients Device Traning ---- Aditya
        // Route::get('/device', 'RCare\Patients\Http\Controllers\DeviceTrainingController@rpmDevicelist');
        // Route::get('/step1', 'RCare\Patients\Http\Controllers\DeviceTrainingController@traningins');
        // Route::get('/step2', 'RCare\Patients\Http\Controllers\DeviceTrainingController@traningstep');
        // Route::get('/step3', 'RCare\Patients\Http\Controllers\DeviceTrainingController@traningsteplast');
        // Route::get('/device-patients-list', 'RCare\Patients\Http\Controllers\DeviceTrainingController@rpmfetchPatients')->name('device_patients_list');

        // Route::get('/device-traning/{id}', 'RCare\Patients\Http\Controllers\DeviceTrainingController@deviceTraning')->name('device-traning'); 
        // Route::post('getContent', 'RCare\Patients\Http\Controllers\DeviceTrainingController@getContent');
        // Route::post('saveDeviceTraining', 'RCare\Patients\Http\Controllers\DeviceTrainingController@saveDeviceTraining');
        // Route::post('savePatientTime', 'RCare\Patients\Http\Controllers\DeviceTrainingController@savePatientTime');
        // Route::get('getTimerData', 'RCare\Patients\Http\Controllers\DeviceTrainingController@getTimerData');
        // Route::get('getPatientData', 'RCare\Patients\Http\Controllers\DeviceTrainingController@getPatientData');



        //-------------------------------------------Monthly Service---------------------------------//
        // Route::get('/monthly-service', 'RCare\Patients\Http\Controllers\MonthlyServiceController@index');
        Route::get('/patient-device-details/{id}', 'RCare\Patients\Http\Controllers\PatientController@fetchPatientDeviceDetails')->name('patient_device_details');
        // Route::get('/fetchPatientsMonthlyService', 'RCare\Patients\Http\Controllers\MonthlyServiceController@fetchPatientsMonthlyService')->name('fetch_patients_monthly_service');
        // Route::get('/monthly-service/{id}', 'RCare\Patients\Http\Controllers\MonthlyServiceController@monthlyService')->name('monthly_service');

        // Route::get('/monthlyServicecontact/{id}', 'RCare\Patients\Http\Controllers\MonthlyServiceController@monthlyServicecontact')->name('monthly_service');

        // Route::get('getQuestionnaire', 'RCare\Patients\Http\Controllers\MonthlyServiceController@getQuestionnaire')->name('getQuestionnaire');
        // Route::get('getCallScripts', 'RCare\Patients\Http\Controllers\MonthlyServiceController@getCallScripts')->name('getCallScripts');
        // Route::get('getCallScriptsById', 'RCare\Patients\Http\Controllers\MonthlyServiceController@getCallScriptsById')->name('getCallScriptsById');
        // Route::post('/fetchEmailContentForMonthlyMonitoring','RCare\Patients\Http\Controllers\MonthlyServiceController@fetchEmailContent')->name('fetch_email_contenet');
        // Route::post('/fetchContentForMonthlyMonitoring','RCare\Patients\Http\Controllers\MonthlyServiceController@fetchContent')->name('fetch_contenet'); 
        // Route::view('test', 'Patients::monthlyService.test');
        // Route::post('/saveMonthlyService','RCare\Patients\Http\Controllers\MonthlyServiceController@saveMonthlyService')->name('ajax.save.monthly.service');
        // Route::post('/saveMonthlyServicewithiguidline','RCare\Patients\Http\Controllers\MonthlyServiceController@saveMonthlyServicewithiguidline');
        // Route::post('/saveMonthlyServicetext','RCare\Patients\Http\Controllers\MonthlyServiceController@saveMonthlyServicetext');
        // Route::post('/saveMonthlyServicecall','RCare\Patients\Http\Controllers\MonthlyServiceController@saveMonthlyServicecall');

        // Route::get('/getContente', 'RCare\Patients\Http\Controllers\MonthlyServiceController@fetchComponentid');

        // Route::get('/fetch-Componentid', 'RCare\Patients\Http\Controllers\MonthlyServiceController@fetchComponentid')->name('fetch_Componentid');
        // //theme2
        // Route::get('/monthlyService2/{id}', 'RCare\Patients\Http\Controllers\MonthlyServiceController@monthlyService')->name('monthlyService2');


        //CCM Monthly Monitering 

        // Route::get('/ccm/monthlymonitoring', 'RCare\Patients\Http\Controllers\MonthlyMonitoring@index');  
        // Route::get('/monthly-monitoring', 'RCare\Patients\Http\Controllers\MonthlyMonitoring@fetchPatientsMonthlyMonitoring')->name('fetch_patients_monthly_monitoring');
        // Route::get('ccm/monthly-monitoring/{id}', 'RCare\Patients\Http\Controllers\MonthlyMonitoring@monthlyMonitoring')->name('monthly_monitoring');


        // Route::get("/ajax/patient/allpatient", "RCare\Patients\Http\Controllers\PatientController@allapracticePatients")->name("ajax.practice.allpatient");
        Route::get('ajax/relationship/list', 'RCare\Patients\Http\Controllers\PatientController@getActiveRelationshipList')->name("ajax.relationship.list");



        //  Route::get("/test-api", "RCare\Patients\Http\Controllers\PatientController@testApi")->name("test.api"); 
        // Route::get('/testapi', function() { 

        //           $url = "http://api.plos.org/search?q=title:DNA";

        //           $json = json_decode(file_get_contents($url), true);

        //           dd($json);
        //       });
    });
});

Route::middleware(["auth", "web"])->group(function () {
    Route::prefix('patients')->group(function () {
        Route::get('/patientslist', function () {
            $patients = Patients::Allpatients(); // worklistPractices() fetches practices
            return response()->json($patients);
        });

        Route::get('/gettime', function () {
            $inputTimeData = Form::input('text', 'hh:mm:ss'); // worklistPractices() fetches practices
            return response()->json($inputTimeData);
        });

        Route::get("/worklist", "RCare\Patients\Http\Controllers\PatientWorklistController@getUserListData")->name("work.list");

        Route::post('/save-patient-fin-number', 'RCare\Patients\Http\Controllers\PatientController@savepatientfinnumber')->name('patient.savefinnumber');
        Route::get("/generate-careplan-age", "RCare\Patients\Http\Controllers\PatientWorklistController@addCarePlanAge")->name("generate.careplan.age");

        Route::get("/activity_time/{id}/{practice_id}", "RCare\Patients\Http\Controllers\PatientWorklistController@get_activitytime")->name("get.activitytime");
        Route::post("/patient-activity", "RCare\Patients\Http\Controllers\PatientWorklistController@savePatientActivity")->name("save.patient.activity");



        Route::get("/worklist/{practice_id}/{patient_id}/{module_id}/{timeoption}/{time}/{activedeactivestatus}", "RCare\Patients\Http\Controllers\PatientWorklistController@getUserListDataAll")->name("work.list.all");
        Route::get("/getuser-filters", "RCare\Patients\Http\Controllers\PatientWorklistController@getUserFilters")->name("user.filters");
        Route::post("/worklist/saveuser-filters/{practice_id}/{patient_id}/{module_id}/{timeoption}/{time}/{activedeactivestatus}", "RCare\Patients\Http\Controllers\PatientWorklistController@saveUserFilters")->name("save.user.filters");



        Route::get('/ajax/rpmpatientlist/{practiceId}/patientlist', 'RCare\Patients\Http\Controllers\PatientController@practiceRPMPatients')->name('ajax.rpmpatientlist.patientlist');
        Route::get('/ajax/rpmproviderpatientlist/{providerId}/providerpatientlist', 'RCare\Patients\Http\Controllers\PatientController@providerRPMPatients')->name('ajax.rpmproviderpatientlist.patientlist');
        Route::get('/enroll-patient-details/{id}', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@patientEnrollTraning')->name('enroll-patient-details');
        Route::post('/fetch-email-template', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@fetchEmailTemplate')->name('fetch-email-template');
        Route::post('patient-enrollment-save', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@patientEnrollmentSave')->name('patient-enrollment-save');
        Route::get('/fetchLatestScript', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@fetchLatestScript');

        Route::post('/ajax/submitRegistration', 'RCare\Patients\Http\Controllers\PatientController@patientRegistration')->name("ajax.patient.registration");
        Route::post('/ajax/submitEditRegistration', 'RCare\Patients\Http\Controllers\PatientController@updatePatientRegistration')->name("ajax.update.patient.registration");
        Route::post('/ajax/PatientuploadImage', 'RCare\Patients\Http\Controllers\PatientController@Patientprofileimage');
        Route::get('/ajax/populateForm/{patientId}', 'RCare\Patients\Http\Controllers\PatientController@populatePatientData')->name("ajax.populate.patient.data");
        // Route::post('/patients/patient-registration','RCare\Patients\Http\Controllers\PatientController@patientRegistration')->name("ajax.patient.registration");
        Route::get("/ajax/practice/{practice}/physicians", "RCare\Patients\Http\Controllers\PatientController@physician")->name("ajax.practice.physicians");
        Route::get("/ajax/practice/partner/{practice}/patient", "RCare\Patients\Http\Controllers\PatientController@getPartnerId")->name("ajax.practice.partner");





        Route::get('/registered-patient-list-search/{id}', 'RCare\Patients\Http\Controllers\PatientController@registeredPatientsSearch')->name('patients.registered.patients.search');
        Route::get('/edit-patients-list', 'RCare\Patients\Http\Controllers\PatientController@fetchRegisteredPatients')->name('registered_patients_list');
        Route::get('/registerd-patient-edit/{id}/{mid}/{cid}/{enroll_service}', 'RCare\Patients\Http\Controllers\PatientController@patientRegisteration')->name('enroll_patient_checklist');
        Route::get('/ajax/getEditPatientData/{id}', 'RCare\Patients\Http\Controllers\PatientController@patientContactTime');
        Route::post('/patient-uid/validate', 'RCare\Patients\Http\Controllers\PatientController@checkPatientUid');

        Route::get('/patient-enrollment/patients-search/{id}', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@listPatientEnrollmentPatientsSearch')->name('patient.enrollment.patients.search');
        Route::get('/patient-enrollment/{id}/{module_id}', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@fetchPatientEnrollmentPatientDetails')->name('patient.enrollment.patient.details');
        Route::post('/patient-enrollment-text', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@saveText')->name('patient.enrollment.text');
        Route::post('/patient-enrollment-email', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@saveEmail')->name('patient.enrollment.email');
        Route::post('/patient-enrollment-call-callstatus', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@saveCallSatus')->name('patient.enrollment.call.callstatus');

        Route::post('/patient-enrollment-call-callstatus-final', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@saveCallSatusFinal')->name('patient.enrollment.call.callstatus.final');

        Route::post('/patient-enrollment-call-enrollmentstatus', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@saveEnrollmentStatus')->name('patient.enrollment.call.enrollmentstatus');
        Route::post('/patient-enrollment-call-enrollservices', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@saveEnrollServices')->name('patient.enrollment.call.enrollservices');
        Route::post('/patient-enrollment-call-checklist', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@saveChecklist')->name('patient.enrollment.call.checklist');
        Route::post('/patient-enrollment-call-checkliststatus', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@saveChecklistStatus')->name('patient.enrollment.call.checkliststatus');
        Route::post('/patient-enrollment-call-finalisedchecklist', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@saveFinalisedChecklist')->name('patient.enrollment.call.finalisedchecklist');
        Route::get('/ajax/populate/{patientId}', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@populate')->name('ajax.populate');
        Route::get('/ajax/populatetimeslots/{patientId}', 'RCare\Patients\Http\Controllers\PatientEnrollmentController@populateTimeslots')->name('ajax.populatetimeslots');

        // Route::get('/care-manager-patients/search/{practiceid}/{providerid}/{time}/{care_manager_id}/{timeoption}', 'RCare\Patients\Http\Controllers\PatientController@CareManagerPatientSearch')->name('care.manager.patients.search');

        Route::get('/patients-assignment/search/{practiceid}/{providerid}/{time}/{care_manager_id}/{timeoption}/{patientstatus}', 'RCare\Patients\Http\Controllers\AssignPatientController@assignedPatientsSearch')->name('patients.assignment.search');

        Route::post('/task-management-user-form', 'RCare\Patients\Http\Controllers\AssignPatientController@SavePatientUser')->name('task.management.user');

        Route::get('/patients-assignment/nonassignedpatients/{practiceid}', 'RCare\Patients\Http\Controllers\PatientController@Nonassignedpatients')->name('patients.assignment.nonassigned');

        Route::get('/patients-assignment', 'RCare\Patients\Http\Controllers\PatientController@assignedPatients')->name('patients.assignment');
        //Worklist patient active deactive status----
        Route::post('/patient-active-deactive', 'RCare\Patients\Http\Controllers\PatientController@savePatientActiveDeactive')->name('patient.active.deactive');
        Route::get("/ajax/active_deactive_populate/{patient_id}", 'RCare\Patients\Http\Controllers\PatientController@getPatientActiveDeactive')->name('ajax.active.deactive.populate');
        Route::post('/patient-research-study', 'RCare\Patients\Http\Controllers\PatientController@savePatientPartResearchStudy')->name('patient.researchstudy');
        Route::post('/patient-personal-notes', 'RCare\Patients\Http\Controllers\PatientController@savePatientPersonalNotes')->name('patient.personalnotes');
        Route::post('/patient-threshold', 'RCare\Patients\Http\Controllers\PatientController@savePatientThreshold')->name('patient.threshold');
        Route::post('/vateran-service', 'RCare\Patients\Http\Controllers\PatientController@saveVateranService')->name('vateran.service');


        Route::get('/ajax/populatefinnumber/{id}', 'RCare\Patients\Http\Controllers\PatientController@populateFinNumberData')->name("ajax.populate.Fin.Number");

        Route::post('/master-devices', 'RCare\Patients\Http\Controllers\PatientController@savepatientdevices')->name('master.devices');
        Route::get('/patient-module-status/{patient_id}/{module_id}/patient-module-status', 'RCare\Patients\Http\Controllers\PatientController@fetchPatientModuleStatus')->name('patient_module_status');

        Route::get('/patient-module/{patient_id}/patient-module', 'RCare\Patients\Http\Controllers\PatientController@fetchPatientModule')->name('patient_module');

        Route::post('/delete-device/{id}', 'RCare\Patients\Http\Controllers\PatientController@acticeinactivedevice')->name('delete.devices');
        Route::get('/ajax/populatedevice/{id}', 'RCare\Patients\Http\Controllers\PatientController@populateDeviceData')->name("ajax.populate.devices.data");


        Route::get('/view-more-personal-notes/{id}', 'RCare\Patients\Http\Controllers\PatientController@viewMorePersonalNotes')->name('view.more.personal.notes');
        Route::get('/view-more-part-of-research-study/{id}', 'RCare\Patients\Http\Controllers\PatientController@viewMorePartOfResearchStudy')->name('view.more.part.of.research.study');
        Route::get('/patient-status/{patient_id}/{module_id}/status', 'RCare\Patients\Http\Controllers\PatientController@patientStatus')->name('patient_status');
        Route::get('/patient-details/{patient_id}/{module_id}/patient-details', 'RCare\Patients\Http\Controllers\PatientController@fetchPatientDetails')->name('patient_details');

        Route::get('/device-deviceslist/{id}', 'RCare\Patients\Http\Controllers\PatientController@getdeviceslist')->name('devices_list');

        // Route::get('/patient_details_model/{patient_id}/{module_id}/patient_details_model', 'RCare\Patients\Http\Controllers\PatientController@fetchPatientDetailsinModel')->name('patient_details_model');

        Route::get('/systemThresholdTab/{patient_id}/{module_id}', 'RCare\Patients\Http\Controllers\PatientController@fetchSystemThreshold')->name('system_threshold_tab');

        Route::get('/patient-caretool/{patient_id}/{module_id}/caretool', 'RCare\Patients\Http\Controllers\PatientController@patientCaretool')->name('patient_caretool');
        //------------------ practice-patients ajax------------------//
        Route::get("/ajax/practice/{practice}/{moduleId}/patient", "RCare\Patients\Http\Controllers\PatientController@practicePatients")->name("ajax.practice.patient");

        Route::get("/ajax/practice/{practice}/{moduleId}/assign-patient", "RCare\Patients\Http\Controllers\PatientController@practicePatientsAssignDevice")->name("ajax.practice.patient.assign");

        Route::get("/ajax/patientlist/{practice}/patientlist", "RCare\Patients\Http\Controllers\PatientController@practicePatientsNew")->name("ajax.practice.patient.new");
        Route::get("/ajax/practicelist/{emr}/practicelist", "RCare\Patients\Http\Controllers\PatientController@practiceOnEmr")->name("ajax.emrpractice");
        Route::get("/ajax/emrlist/{practiceid}/{patientid}", "RCare\Patients\Http\Controllers\PatientController@EmrOnPractice")->name("ajax.practiceemr");
        Route::get("/ajax/patientlist/{emr}/{practiceId}/{module_id}/patientlist", "RCare\Patients\Http\Controllers\PatientController@patientOnEmr")->name("ajax.emrpatient");
        Route::get("ajax/{partnerid}/practice/practiceId/moduleId/patient", "RCare\Patients\Http\Controllers\PatientController@getPartnerdevicedId")->name("ajax.practice.partnerdevices");
        Route::post('/getDevice', 'RCare\Patients\Http\Controllers\PatientController@getPatentDevice')->name('getDevice');

        Route::get('/patients-assignment-status', function () {
            return view('Patients::patient.care-manager-patient-list');
        })->name('patients.assignment.status');

        Route::get('/prac-docs/{id}/uplod-document', 'RCare\Patients\Http\Controllers\PatientController@practiceDocument')->name('prac.upload.doc');

        Route::get("/getCMtotaltime", "RCare\Patients\Http\Controllers\PatientWorklistController@getTotalTimeSpentByCM")->name("getCMtotaltime");

        Route::get("/getOrg/{id}", "RCare\Patients\Http\Controllers\PatientController@getOrgName")->name("getOrg");

        Route::get("/createTaskList", "RCare\Patients\Http\Controllers\PatientWorklistController@createTaskList")->name("createTaskList");

        Route::get("/createtasklistsp", "RCare\Patients\Http\Controllers\PatientWorklistController@createTaskList_sp")->name("create.task.list");
        Route::get("/reschduletaskssp", "RCare\Patients\Http\Controllers\PatientWorklistController@reschdule_tasks_sp")->name("reschdule.tasks");

        Route::get("/completedtasks", "RCare\Patients\Http\Controllers\PatientWorklistController@patientCompletedTasks")->name("completed.tasks");

        // 4Dec2023  by pranali
        Route::get('/patient-relationship-questionnaire/{patient_id}/{module_id}/{sub_module_id}/patient-relationship-questionnaire', 'RCare\Patients\Http\Controllers\PatientController@fetchPatientRelationshipQuestionnaire')->name('patient_relationship_questionnaire');
        // 5Dec2023  by pranali
        Route::get('/patient-fetch-call-history-data/{patient_id}/patient-call-history', 'RCare\Patients\Http\Controllers\PatientController@fetchPatientCallHistoryData')->name('patient_call_history');
    });
});
//testunscribed


Route::get("/testlink", function () {
    return view('Patients::patient.testunscribed');
})->name("test.sub");
Route::POST('/text-msg-unsubscribe', 'RCare\Patients\Http\Controllers\PatientController@saveUnsubscribe')->name('text.msg.unsubscribe');
Route::get("/text-unsubscribe", function () {
    return view('Patients::patient.unsubscribedform');
})->name("text.unsubscribe");
