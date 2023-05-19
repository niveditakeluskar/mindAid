<?php

/*
|--------------------------------------------------------------------------
| RCare / Rpm Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::prefix('rpm')->group(function () {
	Route::get('preview', 'RCare\Rpm\Http\Controllers\PDFController@preview');
    Route::get('download', 'RCare\Rpm\Http\Controllers\PDFController@download')->name('download');
	Route::get('/rpm-device-report-pdf', 'RCare\Ccm\Http\Controllers\CPDController@rpmDeviceReport')->name('rpm.device.report'); 
	
	Route::get('/no-readings-lastthreedays', 'RCare\Rpm\Http\Controllers\DailyReviewController@noReadingsLastthreedays')->name('no.readings.lastthreedays');

    Route::middleware(["auth", "web"])->group(function () {
        Route::middleware(["roleAccess"])->group(function () {
            Route::get('/device', 'RCare\Rpm\Http\Controllers\DeviceTrainingController@rpmDevicelist');
            Route::get('/monthly-service/patients', 'RCare\Rpm\Http\Controllers\MonthlyServiceController@index');
			Route::get('/daily-review', 'RCare\Rpm\Http\Controllers\DailyReviewController@getDailyReview')->name('get.daily.review');  
            
            Route::get('/monthly-monitoring/patients', 'RCare\Ccm\Http\Controllers\CcmController@listMonthalyMonitoringPatients')->name('monthly.monitoring.patients');
            Route::get('/care-plan-development-patients', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@listCarePlanDevelopmentPatients')->name('care.plan.development.patients'); 

        });

        Route::get('/daily-review/{id}/{deviceid}', 'RCare\Rpm\Http\Controllers\DailyReadingController@dailyReading')->name('daily-review'); 

        Route::get('/test', 'RCare\Rpm\Http\Controllers\QuestionnaireController@testJsonArr')->name('testJson');

        Route::get('/addTemplate', 'RCare\Rpm\Http\Controllers\MailTemplateController@index')->name('mail');
        Route::get('/listmailtemplate', 'RCare\Rpm\Http\Controllers\MailTemplateController@ListMail')->name('listmailtemplate');
        Route::get('deletemailtemplate/{id}', 'RCare\Rpm\Http\Controllers\MailTemplateController@DeleteTemplate');
        Route::get('updatemailtemplate/{id}', 'RCare\Rpm\Http\Controllers\MailTemplateController@UpdateTemplate');
        Route::post('SaveRpmTemplate', 'RCare\Rpm\Http\Controllers\MailTemplateController@SaveTemplate')->name('SaveRpmTemplate');
        Route::get('viewTemplateDetails/{id}', 'RCare\Rpm\Http\Controllers\MailTemplateController@viewTemplateDetails');

        Route::get('/rcareservices', 'RCare\Rpm\Http\Controllers\MailTemplateController@CreateServices')->name('rcareservices');
        Route::post('AddServices', 'RCare\Rpm\Http\Controllers\MailTemplateController@AddServices')->name('AddServices');

        
        Route::post('getOrg', 'RCare\Rpm\Http\Controllers\RcareOrgsController@GetCount')->name('getOrg');
        Route::post('getLic', 'RCare\Rpm\Http\Controllers\RcareOrgsController@GetLic')->name('getLic');
        Route::post('getRoles', 'RCare\Rpm\Http\Controllers\RolesController@GetRolesCount')->name('getRoles');
        Route::post('getUsers', 'RCare\Rpm\Http\Controllers\UserController@GetUsersCount')->name('getUsers');
        Route::get("/org-list", "RCare\Rpm\Http\Controllers\RcareOrgsController@fetchRcareOrgs")->name("org_list");

        Route::get('/questionnaire', 'RCare\Rpm\Http\Controllers\QuestionnaireController@index');
        Route::get('/RpmDecisionTree', 'RCare\Rpm\Http\Controllers\QuestionnaireController@inshow');
        Route::view('get-form', 'Rpm::questionnaire.addQuestion');
        Route::view('get-subform', 'Rpm::questionnaire.questionnairetest'); 
        
        Route::get('/listQuestionnaire', 'RCare\Rpm\Http\Controllers\QuestionnaireController@listQuestionnaire')->name('listRpmQuestionnaire');
        Route::get('/listDecision', 'RCare\Rpm\Http\Controllers\QuestionnaireController@listDecision')->name('listRpmDecision');
        Route::get('viewQuestionnaireDetails/{id}', 'RCare\Rpm\Http\Controllers\QuestionnaireController@viewQuestionnaireDetails');
        Route::get('deleteQuestionnaire/{id}', 'RCare\Rpm\Http\Controllers\QuestionnaireController@deleteQuestionnaire');
        Route::post('SaveRpmQuestionnaire', 'RCare\Rpm\Http\Controllers\QuestionnaireController@SaveQuestionnaire')->name('save-rpm-questionnaire');
        Route::post('EditDecisionTree', 'RCare\Rpm\Http\Controllers\QuestionnaireController@EditDecision')->name('edit-rpm-Decision');
        Route::get('updateQuestionnaire/{id}', 'RCare\Rpm\Http\Controllers\QuestionnaireController@updateQuestionnaire');
        Route::get('/patients', 'RCare\Rpm\Http\Controllers\PatientController@index');
        Route::get('/patients-list', 'RCare\Rpm\Http\Controllers\PatientController@fetchPatients')->name('patients_list');
        Route::get('/traning-checklist/{id}', 'RCare\Rpm\Http\Controllers\PatientController@traningChecklist')->name('traning_checklist');


        // Patient_enrollment-list
        Route::get('/patients-enroll-list', 'RCare\Rpm\Http\Controllers\PatientController@fetchPatientsEnroll')->name('patients_enroll_list');
        Route::get('/patient-enroll-details/{id}', 'RCare\Rpm\Http\Controllers\PatientController@fetchPatientEnrollDetails')->name('patient_enroll_details');
        Route::get('/patients-patient-enroll','RCare\Rpm\Http\Controllers\PatientController@patientEnroll')->name('patients-patient_enroll');
        Route::get('/enroll-patient-checklist/{id}', 'RCare\Rpm\Http\Controllers\PatientController@enrollTraningChecklist')->name('enroll_patient_checklist');
        Route::post('/fetch-email-contenet','RCare\Rpm\Http\Controllers\PatientController@fetchEmailContent')->name('fetch_email_contenet');
        Route::post('/fetch-content','RCare\Rpm\Http\Controllers\PatientController@fetchContent')->name('fetch_contenet'); 
        Route::get('/fetch-getCallScriptsById', 'RCare\Rpm\Http\Controllers\PatientController@getCallScriptsById')->name('fetch_getCallScriptsById');
        Route::get('test_modal','RCare\Rpm\Http\Controllers\MailTemplateController@test_modal')->name('test_modal');
        // 
        Route::get('/todolist', 'RCare\Rpm\Http\Controllers\PatientController@toDolist');
        Route::post('patient-enrollment-insert','RCare\Rpm\Http\Controllers\PatientController@patientEnrollmentInsert')->name('patient_enrollment_insert');
        Route::post('enrollment-service','RCare\Rpm\Http\Controllers\PatientController@patientEnrollmentService')->name('enrollment_service');
        // Route::view('officeVisitModule', 'Rpm::questionnaire.officeVisitModule');
        // Route::view('officeVisitModule', 'Rpm::questionnaire.officeVisitModule');


    //  // RPM Device Traning
    //  Route::get('/device', 'RCare\Rpm\Http\Controllers\PatientController@rpmDevicelist');
    //  Route::get('/step1', 'RCare\Rpm\Http\Controllers\PatientController@traningins');
    //  Route::get('/step2', 'RCare\Rpm\Http\Controllers\PatientController@traningstep');
    //  Route::get('/step3', 'RCare\Rpm\Http\Controllers\PatientController@traningsteplast');
    //  Route::get('/device-patients-list', 'RCare\Rpm\Http\Controllers\PatientController@rpmfetchPatients')->name('device_patients_list');

    // Route::get('/device-traning/{id}', 'RCare\Rpm\Http\Controllers\PatientController@deviceTraning')->name('device-traning');  


        // RPM Device Traning ---- Aditya
        Route::get('/device', 'RCare\Rpm\Http\Controllers\DeviceTrainingController@rpmDevicelist');
        Route::get('/step1', 'RCare\Rpm\Http\Controllers\DeviceTrainingController@traningins');
        Route::get('/step2', 'RCare\Rpm\Http\Controllers\DeviceTrainingController@traningstep');
        Route::get('/step3', 'RCare\Rpm\Http\Controllers\DeviceTrainingController@traningsteplast');
        Route::get('/device-patients-list', 'RCare\Rpm\Http\Controllers\DeviceTrainingController@rpmfetchPatients')->name('device_patients_list');
    
        Route::get('/device-training/{id}', 'RCare\Rpm\Http\Controllers\DeviceTrainingController@deviceTraning')->name('device-traning'); 
        Route::post('getContent', 'RCare\Rpm\Http\Controllers\DeviceTrainingController@getContent');
        Route::post('saveDeviceTraining', 'RCare\Rpm\Http\Controllers\DeviceTrainingController@saveDeviceTraining');
        Route::post('savePatientTime', 'RCare\Rpm\Http\Controllers\DeviceTrainingController@savePatientTime');
        Route::get('getTimerData', 'RCare\Rpm\Http\Controllers\DeviceTrainingController@getTimerData');
        Route::get('getPatientData', 'RCare\Rpm\Http\Controllers\DeviceTrainingController@getPatientData'); 
    


        //-------------------------------------------Monthly Service---------------------------------//
        
        
        //PatientStatus->Medicatin 13th Jan 2021 made by Priya
        
        // Route::get('/medication-details/{id}', 'RCare\Rpm\Http\Controllers\PatientStatusController@getPatientsMedication')->name('rpm_medication_details');
        // Route::post('/medication-savedetails', 'RCare\Rpm\Http\Controllers\PatientStatusController@savePatientmedicationData')->name('rpm.medication.savedetails');
        // Route::get('/get-selected-medications_patient-by-id/{patientId}/{med_id}/selectedmedicationspatient', 'RCare\Rpm\Http\Controllers\PatientStatusController@getSelectedMedicationsPatientById')->name('get.selectedmedications.patient.by.id');
        // Route::get('/get-all-medications_patient-by-id/{id}/medicationspatient', 'RCare\Rpm\Http\Controllers\PatientStatusController@getPatientMedicationsById')->name('get.all.medications.patient.by.id');
        // Route::post('/delete-medications_patient-by-id/{id}', 'RCare\Rpm\Http\Controllers\PatientStatusController@deletePatientMedicationsById')->name('delete.medications.patient.by.id'); 


        //changed PatientStatus to PatientMedication in patient package
        Route::get('/medication-details/{id}', 'RCare\Patients\Http\Controllers\PatientMedicationController@getPatientsMedication')->name('rpm_medication_details');
        Route::post('/medication-savedetails', 'RCare\Patients\Http\Controllers\PatientMedicationController@savePatientmedicationData')->name('rpm.medication.savedetails');
        Route::get('/get-selected-medications_patient-by-id/{patientId}/{med_id}/selectedmedicationspatient', 'RCare\Patients\Http\Controllers\PatientMedicationController@getSelectedMedicationsPatientById')->name('get.selectedmedications.patient.by.id');
        Route::get('/get-all-medications_patient-by-id/{id}/medicationspatient', 'RCare\Patients\Http\Controllers\PatientMedicationController@getPatientMedicationsById')->name('get.all.medications.patient.by.id');
        Route::post('/delete-medications_patient-by-id/{id}', 'RCare\Patients\Http\Controllers\PatientMedicationController@deletePatientMedicationsById')->name('delete.medications.patient.by.id');   
 
        //changed by ashvini13th jan 2021  
        Route::get('/patient-device-details/{id}', 'RCare\Rpm\Http\Controllers\DeviceTrainingController@fetchPatientDeviceDetails')->name('patient_device_details');
        Route::get('/patient-details/{id}', 'RCare\Rpm\Http\Controllers\MonthlyServiceController@fetchPatientDetails')->name('patient_details');
        Route::get('/fetchPatientsMonthlyService', 'RCare\Rpm\Http\Controllers\MonthlyServiceController@fetchPatientsMonthlyService')->name('fetch_patients_monthly_service');
        Route::get('/monthly-service/{id}', 'RCare\Rpm\Http\Controllers\MonthlyServiceController@monthlyService')->name('monthly_service');

        Route::get('/monthlyServicecontact/{id}', 'RCare\Rpm\Http\Controllers\MonthlyServiceController@monthlyServicecontact')->name('monthly_service');

        Route::get('getQuestionnaire', 'RCare\Rpm\Http\Controllers\MonthlyServiceController@getQuestionnaire')->name('getQuestionnaire');
        Route::get('getCallScripts', 'RCare\Rpm\Http\Controllers\MonthlyServiceController@getCallScripts')->name('getCallScripts');
        Route::get('getCallScriptsById', 'RCare\Rpm\Http\Controllers\MonthlyServiceController@getCallScriptsById')->name('getCallScriptsById');
        Route::post('/fetchEmailContentForMonthlyMonitoring','RCare\Rpm\Http\Controllers\MonthlyServiceController@fetchEmailContent')->name('fetch_email_contenet');
        Route::post('/fetchContentForMonthlyMonitoring','RCare\Rpm\Http\Controllers\MonthlyServiceController@fetchContent')->name('fetch_contenet'); 
        Route::view('test', 'Rpm::monthlyService.test');
        Route::post('/saveMonthlyService','RCare\Rpm\Http\Controllers\MonthlyServiceController@saveMonthlyService')->name('ajax.save.monthly.service');
        // Route::post('/saveMonthlyServicewithiguidline','RCare\Rpm\Http\Controllers\MonthlyServiceController@saveMonthlyServicewithiguidline');
        // Route::post('/saveMonthlyServicetext','RCare\Rpm\Http\Controllers\MonthlyServiceController@saveMonthlyServicetext');
        // Route::post('/saveMonthlyServicecall','RCare\Rpm\Http\Controllers\MonthlyServiceController@saveMonthlyServicecall');
        
        Route::get('/getContente', 'RCare\Rpm\Http\Controllers\MonthlyServiceController@fetchComponentid');

        Route::get('/fetch-Componentid', 'RCare\Rpm\Http\Controllers\MonthlyServiceController@fetchComponentid')->name('fetch_Componentid');
        //theme2
        Route::get('/monthlyService2/{id}', 'RCare\Rpm\Http\Controllers\MonthlyServiceController@monthlyService')->name('monthlyService2');


        //CCM Monthly Monitering 

    Route::get('/ccm/monthlymonitoring', 'RCare\Rpm\Http\Controllers\MonthlyMonitoring@index');  
    Route::get('/monthly-monitoring', 'RCare\Rpm\Http\Controllers\MonthlyMonitoring@fetchPatientsMonthlyMonitoring')->name('fetch_patients_monthly_monitoring');
    Route::get('ccm/monthly-monitoring/{id}', 'RCare\Rpm\Http\Controllers\MonthlyMonitoring@monthlyMonitoring')->name('monthly_monitoring');

    //------------------ practice-patients ajax------------------//
    // Route::get("/ajax/patient/{practice}/patient", "RCare\Rpm\Http\Controllers\PatientController@practicePatients")->name("ajax.practice.patient");



        //###################################################################----Patient Enrollment New Screens----############################################################################//

        Route::get('/patient-enroll','RCare\Rpm\Http\Controllers\PatientEnrollmentController@fetchPatientsEnroll')->name('patients-enroll');
        Route::get('/enroll-patient-details/{id}', 'RCare\Rpm\Http\Controllers\PatientEnrollmentController@patientEnrollTraning')->name('enroll-patient-details');
        Route::post('/fetch-email-template','RCare\Rpm\Http\Controllers\PatientEnrollmentController@fetchEmailTemplate')->name('fetch-email-template');
        Route::post('patient-enrollment-save','RCare\Rpm\Http\Controllers\PatientEnrollmentController@patientEnrollmentSave')->name('patient-enrollment-save');
        Route::get('/fetchLatestScript','RCare\Rpm\Http\Controllers\PatientEnrollmentController@fetchLatestScript');


    //--------------------------------------Patient Registration--------------------------------------------------///////////
        Route::view('/patients/patient-registration', 'Rpm::patient.patient-registration');
        Route::post('/ajax/submitRegistration','RCare\Rpm\Http\Controllers\PatientController@patientRegistration')->name("ajax.patient.registration");
        // Route::post('/patients/patient-registration','RCare\Rpm\Http\Controllers\PatientController@patientRegistration')->name("ajax.patient.registration");
        // Route::get("/ajax/practice/{practice}/physicians", "RCare\Rpm\Http\Controllers\PatientController@physician")->name("ajax.practice.physicians");
        // Route::view('/registered-patient-list', 'Rpm::patient.registered-patient-list');
        // Route::get('/edit-patients-list', 'RCare\Rpm\Http\Controllers\PatientController@fetchRegisteredPatients')->name('registered_patients_list');
        Route::get('/registered-patient-list','RCare\Rpm\Http\Controllers\PatientController@registeredPatients')->name('patients-registered-patients');
        Route::get('/edit-patients-list', 'RCare\Rpm\Http\Controllers\PatientController@fetchRegisteredPatients')->name('registered_patients_list');
        Route::get('/registerd-patient-edit/{id}', 'RCare\Rpm\Http\Controllers\PatientController@patientRegisteration')->name('enroll_patient_checklist');
        Route::get('/ajax/getEditPatientData/{id}', 'RCare\Rpm\Http\Controllers\PatientController@patientContactTime');


        //--------------------------------------Device Traning--------------------------------------------------///////////
        Route::post('/divice-traning-patient-traning', 'RCare\Rpm\Http\Controllers\DeviceTrainingController@SavePatientTraning')->name('divice.traning.patient.traning');
        Route::get('/patient-order', 'RCare\Rpm\Http\Controllers\PatientController@PatientOrderUpdate')->name('patient.order');
        Route::post('/patientorder', 'RCare\Rpm\Http\Controllers\PatientController@PatientOrderInJson')->name('patientorder');
        Route::post('/get-patient-order', 'RCare\Rpm\Http\Controllers\PatientController@getPatientOrderUpdate')->name('get.patient.order');

       //--------------------Rpm Daily Review--------------------------------------------------------------------------------------------------------//
       
       //    Route::get('/daily-review', function(){ return view('Rpm::daily-review.daily-review');  })->name('get.daily.review');      
      
       Route::get('/daily-review-list/search/{practicesgrp}/{practices}/{provider}/{patient}/{caremanagerid}/{fromeffdate}/{toeffdate}/{reviewedstatus}', 'RCare\Rpm\Http\Controllers\DailyReviewController@getDailyReviewData')->name('get.daily.review.list');
       Route::get('/daily-review-list-details/{patient}/{effdate}/{unittable}/{reviewedstatus}/{serialid}', 'RCare\Rpm\Http\Controllers\DailyReviewController@getDailyReviewChildData')->name('get.daily.review.child.list');
       Route::post('/daily-review-updatereviewstatus', 'RCare\Rpm\Http\Controllers\DailyReviewController@updateDailyReviewstatus')->name('daily.review.status'); 
       Route::post('/daily-review-updateaddressed', 'RCare\Rpm\Http\Controllers\DailyReviewController@updateDailyReviewAddress')->name('save.address.rpm.notes'); 

     //---------------------------RPM ALERT History-------------------------------------------------------------------------------------------------------------------------------//
        // Route::get('/alert-history', function(){ return view('Rpm::alert-history.alert-history');  })->name('get.alert.history');getAlertHistory

        Route::get('/alert-history', 'RCare\Rpm\Http\Controllers\AlertHistoryController@getAlertHistory')->name('get.alert.history'); 
        Route::get('/alert-history-list/search/{practicesgrp}/{practices}/{provider}/{timeframe}/{caremanagerid}/{patient}', 'RCare\Rpm\Http\Controllers\AlertHistoryController@getAlertHistoryData')->name('get.alert.history.list');
        Route::get('/patient-alert-data-list/{patientid}/{unit}/{deviceid}', 'RCare\Rpm\Http\Controllers\AlertHistoryController@getPatientDataAlert')->name('get.patient.data.alert.list');
        Route::get('/patient-alert-history-list/{patientid}/{fromdate}/{todate}/{deviceid}', 'RCare\Rpm\Http\Controllers\AlertHistoryController@getPatientDataAlertAccordingtoDevice')->name('get.patient.data.alert.list.device');
        
        Route::get('/patient-alert-history-list-device-link/{patientid}/{unit}/{fromdate}/{todate}', 'RCare\Rpm\Http\Controllers\ReviewDataLinkController@getPatientDataAlertAccordingtoDevice')->name('get.patient.data.alert.list.device.link');
        
        

        Route::post('/save-rpm-cm-notes', 'RCare\Rpm\Http\Controllers\AlertHistoryController@saveRpmNotess')->name('alerthistory.save.rpm.notes');
        Route::get('/ajax/populate-vital-alerts/{patientid}/{vital}/{csseffdate}/{reading}', 'RCare\Rpm\Http\Controllers\AlertHistoryController@populateVitalsAlertNotes')->name('populate.vital.alerts');  
             
     //------------------------------  RPM ACTIVE ALERT----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

   
    //  Route::get('/active-alert', function(){ return view('Rpm::active-alert.active-alert');  })->name('get.active.alert'); 
     Route::get('/active-alert', 'RCare\Rpm\Http\Controllers\ActiveAlertController@getActiveAlert')->name('get.active.alert');  
     Route::get('/active-alert-list/search/{practicesgrp}/{practices}/{provider}/{timeframe}/{caremanagerid}/{patient}', 'RCare\Rpm\Http\Controllers\ActiveAlertController@getActiveAlertData')->name('get.alert.list');
     Route::post('/save-rpm-cm-notes', 'RCare\Rpm\Http\Controllers\ActiveAlertController@saveRpmNotes')->name('save.rpm.notes');
     Route::post('/save-rpm-cm-device-link-notes', 'RCare\Rpm\Http\Controllers\ReviewDataLinkController@saveRpmNotes')->name('save.rpmdevicelink.notes');
     Route::post('/save-rpm-review-notes', 'RCare\Rpm\Http\Controllers\ReviewDataLinkController@saveRpmReviewNotes')->name('save.rpmreview.notes');
     Route::post('/rpm-history-cm-notes', 'RCare\Rpm\Http\Controllers\AlertHistoryController@saveRpmNotess')->name('save.rpm.alert.history.notes');
     
     //Route::post('/save-alert-rpm-notes', 'RCare\Rpm\Http\Controllers\ActiveAlertController@saveRpmAlertNotes')->name('save.alert.rpm.notes'); 
    Route::post('/save-alert-rpm-notes', 'RCare\Rpm\Http\Controllers\ActiveAlertController@saveRpmNotes')->name('save.alert.rpm.notes'); 


     Route::get('/monthly-monitoring/patients-search/{id}', 'RCare\Ccm\Http\Controllers\CcmController@listMonthalyMonitoringPatientsSearch')->name('rpm.monthly.monitoring.patients.search');
     Route::get('/care-plan-development-patients-search/{id}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@listCarePlanDevelopmentPatientsSearch')->name('rpm.care.plan.development.patients.search');
     
     //============================RPM API=======================================================================================
              // Route::get('/device-order-form', function(){
              //       return view('Rpm::patient.device-order');
              //   })->name('device.order.form');

               //  Route::get('/device-order','RCare\Rpm\Http\Controllers\RpmApiController@ViewDeviceOrder')->name("device.order.form");
 
            // Route::get('/device-order-form', function(){
            //         return view('Rpm::patient.device-order');
            //     })->name('device.order.form');
           Route::post('/place-device-orders', 'RCare\Rpm\Http\Controllers\RpmApiController@SavePlaceOrder')->name('place.device.orders');
           Route::get('/getPatientDetails/{patientid}','RCare\Patients\Http\Controllers\PatientController@populatePatientData')->name("ajax.populate.patient.data");

            Route::get('/device-order-list', function(){
                return view('Rpm::patient.device-order-list');
            })->name('device.order.list');
            Route::get('/order-list', function(){
                return view('Rpm::patient.order-list');
            })->name('order.list');
             
             Route::get('/order-details/{id}', function(){
                return view('Rpm::patient.order-details');
            })->name('order.details'); 
           
             Route::get('/render-device-order-list','RCare\Rpm\Http\Controllers\RpmApiController@renderDeviceOrderList')->name("render.device.order.list");
             Route::get('/render-order-list','RCare\Rpm\Http\Controllers\RpmApiController@renderDeviceOrderDetailsFromAPI')->name("render.order.list");
             Route::get('/device-order','RCare\Rpm\Http\Controllers\RpmApiController@getAddresslocationFromOrderDetails')->name('device.order');  
             Route::get('/observation-list','RCare\Rpm\Http\Controllers\RpmApiController@getObservationlist')->name('observation.list');
             Route::get('/getshippingdetails','RCare\Rpm\Http\Controllers\RpmApiController@getOfficedata')->name('getshippingdetails');            
                         
// ======================================Daily Reading
//===================================================================================================================================

// uncmnt priya
            Route::get('/read-chart/{patient_id}/{deviceid}', 'RCare\Rpm\Http\Controllers\ReadingChartController@readReadings')->name('read-chart');

        
            Route::post('/getCareNoteData', 'RCare\Rpm\Http\Controllers\DailyReadingController@getCareNoteData')->name('daily-review'); 

            Route::get('/daily-device-reading/{patient_id}/{deviceid}', 'RCare\Rpm\Http\Controllers\DailyReadingController@getDailyDeviceReading')->name('daily-device-reading');            
            Route::get('/test-curl', 'RCare\Rpm\Http\Controllers\RpmApiController@testcurlfunction')->name('test-curl'); 
            Route::post('/daily-reading-record-time', 'RCare\Rpm\Http\Controllers\DailyReadingController@recordTime')->name('daily.reading.record.time');
            Route::get("/populate-order-details/{id}", "RCare\Rpm\Http\Controllers\RpmApiController@getOrderDetails")->name("populate.order.details");
            Route::get('/monthly-review-list/{patientid}/{monthly}/{monthlyto}/{deviceid}', 'RCare\Rpm\Http\Controllers\DailyReadingController@getMonthlyReviewRecord')->name('get.monthly.review.list');
            Route::get('/monthly-review-list-details/{patient}/{effdate}/{unittable}/{reviewedstatus}/{serialid}', 'RCare\Rpm\Http\Controllers\DailyReadingController@getMonthlyReviewChildData')->name('get.monthly.review.child.list');
            Route::post('/rpm-reading-text', 'RCare\Rpm\Http\Controllers\DailyReadingController@SaveText')->name('rpm.reading.text');
            Route::post('/updatereadingreviewstatus', 'RCare\Rpm\Http\Controllers\DailyReadingController@updatereviewedstatus')->name('review.status'); 

            Route::post('/rpm-reading-carenote', 'RCare\Rpm\Http\Controllers\DailyReadingController@SaveCareNote')->name('rpm.reading.carenote');//anand
            //==========================================Api Exception =============================================================================================================
            Route::get('/api-exception', function(){ return view('Rpm::api-exception.api-exception');  })->name('get.api.exception'); 
            //Route::get('/reading-chart', function(){ return view('Rpm::daily-review.readingchart');  })->name('reading-chart');  
            //Route::get('/readingnew-chart', function(){ return view('Rpm::daily-review.newchartfile');  })->name('readingnew-chart');
            Route::get('/calender-chart/{patient_id}', 'RCare\Rpm\Http\Controllers\ReadingChartController@readCalender')->name('calender-reading');  
            //==========================================Rpm Review Data link =============================================================================================================
            Route::get('/getNumberOfReading/{day}/{patient_id}','RCare\Rpm\Http\Controllers\ReadingChartController@getNumberOfReading')->name('get.number.of.reading');
            Route::get('/calender-data/{patient_id}/{deviceid}', 'RCare\Rpm\Http\Controllers\ReadingChartController@getreadCalender')->name('calenderdata-reading');  
            Route::get('/readingnew-chart/{patient_id}/{deviceid}', 'RCare\Rpm\Http\Controllers\ChartsNewController@readReadings')->name('readingnew-chart');  
            Route::get('/graphreadingnew-chart/{patient_id}/{deviceid}/{month}/{year}/graphchart', 'RCare\Rpm\Http\Controllers\ReadingChartController@graphreadReadings')->name('readingnew-chart');  
            // Route::get('/graphreadingSpirometernew-chart/{patient_id}/{deviceid}/{month}/{year}/graphchart', 'RCare\Rpm\Http\Controllers\ReadingChartController@graphreadSpirometerReadings')->name('readingnew-chart');
            // Route::get('/patient-alert-data-list-chart/{patientid}', 'RCare\Rpm\Http\Controllers\ChartsController@getPatientReadData')->name('get.patient.data.alert.list.chart');
// 
////////////////// Chart Read

            Route::get('/grahchart-list', function(){ return view('Rpm::review-data-link.graph-data');  })->name('get.graphchart.list');  

            Route::get('/chart-list', function(){ return view('Rpm::chart-new.chart-list');  })->name('get.chart.list');  

            Route::get('/chartlist-list/search/{practicesgrp}/{practices}/{provider}/{timeframe}/{caremanagerid}', 'RCare\Rpm\Http\Controllers\ChartsNewController@getChartData')->name('get.chartlist.list');
            
            Route::get('/patient-alert-data-list-chartnew/{patientid}', 'RCare\Rpm\Http\Controllers\ChartsNewController@getPatientReadDataNew')->name('get.patient.data.alert.list.chart');

            Route::get('/chart-new/updatediv/{p}/{a1}', 'RCare\Rpm\Http\Controllers\ChartsNewController@getPatientDeviceDataNew')->name('updatediv'); 
            //Route::post('/rpm/chart-new/updatediv', function(){ return view('Rpm::chart-new.vitals-tab-new');  })->name('updatediv'); 

/////////////////////////

            //Review Data Link
             // Route::get('/review-data-link/{patientid}', function(){ return view('Rpm::review-data-link.review-data');  })->name('get.review.data');  

            Route::get('/review-data-link/{patientid}','RCare\Rpm\Http\Controllers\ReviewDataLinkController@getPatientDeviceDataNew')->name('get.review.data');  


            // Route::post('/updatereadingreviewstatus', 'RCare\Rpm\Http\Controllers\DailyReadingController@updatereviewedstatus')->name('review.status'); 
            // Route::get('/readingnewBP-chart/{patient_id}/{deviceid}', 'RCare\Rpm\Http\Controllers\readingChartController@readBP')->name('readingnewBP-chart');  
           
            Route::get('/api-exception/search/{fromdate}/{todate}/{exception_type}/{patientid}/{patientemr}/{exceptionemr}','RCare\Rpm\Http\Controllers\ApiExceptionController@viewApiException')->name('view.api.exception');   
            Route::get('/ajax/populateApiExceptionForm/{id}','RCare\Rpm\Http\Controllers\ApiExceptionController@populateApiExceptionData')->name("ajax.populate.exception.data"); 


            Route::get('/place-order-details','RCare\Rpm\Http\Controllers\RpmApiController@getPlaceOrderDetails')->name('place.order.details');  

              Route::POST('/api-reprocess-request','RCare\Rpm\Http\Controllers\ApiExceptionController@updateReprocessStatus')->name('view.api.exception'); 

            Route::get('/care-plan-development/{id}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@fetchCarePlanDevelopmentPatientDetails')->name('care.plan.development.patient.details');
            Route::get('/monthly-monitoring/{id}', 'RCare\Ccm\Http\Controllers\CcmController@fetchMonthlyMonitoringPatientDetails')->name('monthly.monitoring.patient.details');
            Route::get('/rpm-protocol/{deviceid}','RCare\Rpm\Http\Controllers\DailyReadingController@getProtocolFileName')->name('rpm.protocol');

              Route::get('/testfun', 'RCare\Rpm\Http\Controllers\ActiveAlertController@testfun')->name('testJson');

         Route::get('/exportreviewdata', 'RCare\Rpm\Http\Controllers\DailyReviewController@exportWorlistData')->name('exportreviewdata');

          Route::post('/daily-reviewdatalink-updatereviewstatus', 'RCare\Rpm\Http\Controllers\ReviewDataLinkController@updateDailyReviewstatus')->name('review.datalink.status'); 

         
         
             Route::get('/alert-history/{patientid}/{month}', 'RCare\Rpm\Http\Controllers\AlertHistoryController@getAlertHistory')->name('get.alert.history'); 
          Route::get('/alert-history-export/{patientid}/{month}', 'RCare\Rpm\Http\Controllers\AlertHistoryController@getAlertHistoryData')->name('alert.history.export');
      

    });
}); 
