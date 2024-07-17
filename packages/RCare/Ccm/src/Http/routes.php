<?php

/*
|--------------------------------------------------------------------------
| RCare / CCM Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(["auth", "roleAccess", "web"])->group(function () {
    Route::prefix('ccm')->group(function () {
        Route::get('/monthly-monitoring/patients', 'RCare\Ccm\Http\Controllers\CcmController@listMonthalyMonitoringPatients')->name('monthly.monitoring.patients');
        Route::get('/care-plan-development-patients', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@listCarePlanDevelopmentPatients')->name('care.plan.development.patients');
    });
});
// Authenticated user only routes
Route::middleware(["auth", "web"])->group(function () {
    Route::prefix('ccm')->group(function () {

        Route::get('/diagnosis-conditions', 'RCare\Ccm\Http\Controllers\CcmController@getDiagnosisConditions');
        Route::get('/activediagnosis-code', 'RCare\Ccm\Http\Controllers\CcmController@getActiveDiagnosiscode');

        Route::get('/get-stepquestion/{module_id}/{patient_id}/{step_id}/{componentId}/question_list', function (string $module_id, string $patient_id, string $step_id, string $componentId) {
            $d = getDecisionTree($module_id, $patient_id, $step_id, $componentId);
            return $d;
        });
        Route::get('/get-calltext/{module_id}/{patient_id}/{component_id}/call_message', function (string $module_id, string $patient_id, string $component_id) {
            $d = getSendTextMessage($module_id, $patient_id, $component_id);
            return $d;
        });

        Route::get('/enrolled/{patient_id}/{module_id}/{component_id}/ccm_enrolled', 'RCare\Ccm\Http\Controllers\CcmController@getEnrolledStatus');

        Route::get('/get_revew_notes/{patient_id}', 'RCare\Ccm\Http\Controllers\CcmController@getReviewNotes');
        Route::get('/get_device_list/{patient_id}/device_list', 'RCare\Ccm\Http\Controllers\CcmController@getDevice')->name('get.device.list');

        Route::get('/get-savedQuestion/{module_id}/{patient_id}/{step_id}/saved_question', 'RCare\Ccm\Http\Controllers\CcmController@getSavedGeneralQuestions')->name('get.saved.question');
        //     Route::get('/monthly-monitoring-patient-list', 'RCare\Ccm\Http\Controllers\CcmController@index')->name('monthly.monitoring.patient.list');
        ///////////////////////New Monthly Monitoring/////////////////////////////
        Route::get('/monthly-monitoring-call-wrap-up-activities/activities', 'RCare\Ccm\Http\Controllers\CcmController@callWrapUpActivities')->name('call.wrap.up.activities');

        Route::get('/testScheduler', 'RCare\Ccm\Http\Controllers\CcmController@testScheduler')->name('testScheduler');

        Route::post('/monthly-monitoring/enrolled-date', 'RCare\Ccm\Http\Controllers\CcmController@saveEnrolleddate')->name('save.enrolleddate');
        Route::get('/monthly-monitoring/getenrolled-date/{patient_id}', 'RCare\Ccm\Http\Controllers\CcmController@populateEnrolleddate')->name('populate.enrolleddate');

        Route::get('/monthly-monitoring/render-question', 'RCare\Ccm\Http\Controllers\CcmController@RenderQuestion')->name('render.question');
        Route::get('/monthly-monitoring/patients-search/{id}', 'RCare\Ccm\Http\Controllers\CcmController@listMonthalyMonitoringPatientsSearch')->name('monthly.monitoring.patients.search');
        //Route::get('/monthly-monitoring/patients-search/{id}', 'RCare\Ccm\Http\Controllers\CcmController@listSpMonthlyMonitoringPatientsSearch')->name('monthly.monitoring.patients.search');
        Route::get('/monthly-monitoring/{id}', 'RCare\Ccm\Http\Controllers\CcmController@fetchMonthlyMonitoringPatientDetails')->name('monthly.monitoring.patient.details');

        Route::get('/populate-monthly-monitoring-data/{id}', 'RCare\Ccm\Http\Controllers\CcmController@populateMonthlyMonitoringData')->name('populate.MonthlyMonitoring.data');

        // Route::get("/monthly-monitoring/{id}", function(){
        //         return view('Ccm::monthly-monitoring.patient-details');
        //     })->name('monthly.monitoring.patient.details');

        Route::get('/monthly-monitoring-call-wrap-up/{id}', 'RCare\Ccm\Http\Controllers\CcmController@getCallWrapUp')->name('monthly.monitoring.call.wrap.up');

        Route::post('/populate_preparation_notes', 'RCare\Ccm\Http\Controllers\CcmController@SaveCallPreparation')->name('monthly.monitoring.call.preparation');
        Route::get('/ajax/populate_preparation_notes/{patientId}/{month}', 'RCare\Ccm\Http\Controllers\CcmController@getCcmMonthlyData')->name('populate.preparation.notes');
        Route::get('/ajax/populate_research_followup_preparation_notes/{patientId}/{month}', 'RCare\Ccm\Http\Controllers\CcmController@getCcmMonthlyReasearchFollowupData')->name('populate.preparation.notes');
        Route::post('/monthly-monitoring-call-preparation-form', 'RCare\Ccm\Http\Controllers\CcmController@SaveCallPreparation')->name('monthly.monitoring.call.preparation');
        Route::post('/patient-threshold', 'RCare\Patients\Http\Controllers\PatientController@savePatientThreshold')->name('patient.threshold');
        Route::get('/systemThresholdTab/{patient_id}/{module_id}', 'RCare\Patients\Http\Controllers\PatientController@fetchSystemThreshold')->name('system_threshold_tab');
        Route::post('/save-patient-fin-number', 'RCare\Patients\Http\Controllers\PatientController@savepatientfinnumber')->name('patient.savefinnumber');
        Route::post('/monthly-monitoring-call-preparation-form-draft', 'RCare\Ccm\Http\Controllers\CcmController@DraftSaveCallPreparation')->name('monthly.monitoring.call.preparation.draft');

        // preparation total time spent
        Route::get("/getSpentTime/{patient_id}/{module_id}/{stage_id}/spent_total-time", "RCare\Ccm\Http\Controllers\CcmController@getTotalTimeSpent")->name("getSpentTime");
        Route::post('/monthly-monitoring-call-callstatus', 'RCare\Ccm\Http\Controllers\CcmController@SaveCallSatus')->name('monthly.monitoring.call.callstatus');
        Route::post('/monthly-monitoring-call-hippa', 'RCare\Ccm\Http\Controllers\CcmController@SaveCallHippa')->name('monthly.monitoring.call.hippa');
        Route::post('/monthly-monitoring-call-homeservice', 'RCare\Ccm\Http\Controllers\CcmController@SaveCallHomeService')->name('monthly.monitoring.call.homeservice');
        Route::post('/monthly-monitoring-call-relationship', 'RCare\Ccm\Http\Controllers\CcmController@SaveCallRelationship')->name('monthly.monitoring.call.relationship');
        Route::post('/monthly-monitoring-call-callclose', 'RCare\Ccm\Http\Controllers\CcmController@SaveCallClose')->name('monthly.monitoring.call.callclose');
        Route::post('/monthly-monitoring-call-callwrapup', 'RCare\Ccm\Http\Controllers\CcmController@SaveCallWrapUp')->name('monthly.monitoring.call.callwrapup');
        Route::post('/monthly-monitoring-followup', 'RCare\Ccm\Http\Controllers\CcmController@SaveFollowup')->name('monthly.monitoring.followup');
        Route::post('/monthly-monitoring-followup-inertia', 'RCare\Ccm\Http\Controllers\CcmController@SaveFollowUpInertia')->name('monthly.monitoring.followup.inertia');
        Route::post('/monthly-monitoring-text', 'RCare\Ccm\Http\Controllers\CcmController@SaveText')->name('monthly.monitoring.text');
        Route::post('/monthly-monitoring-update-callwrap-up/{id}', 'RCare\Ccm\Http\Controllers\CcmController@UpdateCallWrapUpInline')->name('update.call.wrap.up.inline');
        Route::post('/monthly-monitoring-update-callwrap-up-new/{id}', 'RCare\Ccm\Http\Controllers\CcmController@UpdateCallWrapUpInlineNew');
        Route::get('/get-call-scripts-by-id/{id}/{uid}/call-script', 'RCare\Ccm\Http\Controllers\CcmController@getCallScriptsById')->name('get.call.scripts.by.id');
        Route::post('/saveGeneralQuestion', 'RCare\Ccm\Http\Controllers\CcmController@generalQuestion')->name('saveGeneralQuestion');
        Route::post('/saveEmrSummary', 'RCare\Ccm\Http\Controllers\CcmController@emrSummary')->name('saveEmrSummary');

        // Route::post('/selectFollowUp', 'RCare\Ccm\Http\Controllers\CcmController@selectFollowUp')->name('selectFollowUp');
        //Route::get('/getFollowUpTaskNotes/{patientId}/{moduleId}/{emrSelectId}/tasknotes', 'RCare\Ccm\Http\Controllers\CcmController@getFollowupTaskNotes')->name('getFollowUpTaskNotes');
        Route::get("/patient-followup-task/{patientId}/{moduleId}/followuplist", "RCare\Ccm\Http\Controllers\CcmController@getFollowupTaskListData")->name("ajax.followup.task.list");
        Route::post('/completeIncompleteTask', 'RCare\Ccm\Http\Controllers\CcmController@changeTodoStatusFlag');
        Route::get('/getFollowupListData-edit/{id}/{patientId}/followupnotespopulate', 'RCare\Ccm\Http\Controllers\CcmController@populateFollowupNotes')->name('populate.followupnotes');
        Route::post('/saveFollowupListData-edit', 'RCare\Ccm\Http\Controllers\CcmController@SaveFollowupEditData')->name('save.followup.edit.data');

        ///////////////////////////////////side current and perious and careplan Sidebar notes visible
        Route::get('/current-month-status/{patient_id}/{module_id}/currentstatus', 'RCare\Ccm\Http\Controllers\CcmController@currentMonthStatus')->name('current_month_status');
        Route::get('/previous-month-status/{patient_id}/{module_id}/{month}/{year}/previousstatus', 'RCare\Ccm\Http\Controllers\CcmController@previousMonthStatus')->name('previouse_month_status');
        Route::get('/careplan-status/{patient_id}/{module_id}/careplanstatus', 'RCare\Ccm\Http\Controllers\CcmController@patientCarePlanStatus')->name('patient_careplan_status');
        Route::get('/patient-relationship-building/{patient_id}/patient_relationship_building', 'RCare\Ccm\Http\Controllers\CcmController@patientRelationshipBuilding')->name('patient_relationship_building');
        Route::get('delete-callwrapup-notes/{id}', 'RCare\Ccm\Http\Controllers\CcmController@DeleteCallWrapupNotes');
        Route::get('/patient-relationship-building/{patient_id}/relation_patient_relationship_building', 'RCare\Ccm\Http\Controllers\CcmController@getRelationQuestion')->name('relation_patient_relationship_building');
        Route::get('/previous-month-calendar/{patient_id}/{module_id}/previousstatus', 'RCare\Ccm\Http\Controllers\CcmController@PatientPreviousMonthCalender')->name('previous_month_calendar');
        ///////////////////////New Care Plan Development/////////////////////////////
        //Radio button
        Route::get('/ajax/populateCarePlanDevelopmentrelationship/{patientId}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@populateRelationshipData')->name("ajax.populate.careplandevelopment.form");
        //populate

        Route::get('/care-plan-development-patients-search/{id}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@listCarePlanDevelopmentPatientsSearch')->name('care.plan.development.patients.search');
        Route::get('/care-plan-development/{id}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@fetchCarePlanDevelopmentPatientDetails')->name('care.plan.development.patient.details');
        Route::get('/ajax/populateCarePlanDevelopmentForm/{patientId}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@populateCarePlanDevelopmentData')->name("ajax.populate.careplandevelopment.form");
        Route::post('/delete-care-plan', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@deleteCarePlan');
        Route::get('/get_diagnosis_all_codes/{conditionId}/get_diagnosis_all_codes', 'RCare\Ccm\Http\Controllers\CcmController@getDiagnosisAllCodes')->name('get.diagnosis.all.codes');

        //Patient Family Data
        Route::post('/care-plan-development-family-spouse', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@savePatientpersonalfamilyData')->name('care.plan.development.family.spouse');
        Route::post('/care-plan-development-family-emergency-contact', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@savePatientpersonalfamilyData')->name('care.plan.development.family.emergency.contact');
        Route::post('/care-plan-development-family-care-giver', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@savePatientpersonalfamilyData')->name('care.plan.development.family.care.giver');
        Route::post('/care-plan-development-family-patient-data', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@savePatientData')->name('care.plan.development.family.patient.data');
        //Review
        Route::get('/get-all-family_patient-by-id/{id}/{relation}/familypatient', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getPatientFamilyById')->name('get.all.family.patient.by.id');
        Route::post('/get-delete-family_patient-by-id/{id}/familypatient', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@deletePatientFamilyById')->name('get.delete.family.patient.by.id');
        Route::get('care-plan-development-siblinglist/{id}/{tab_name}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getSiblingList')->name('care.plan.development.review.siblinglist');
        Route::post('/care-plan-development-review-relation', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@savePatientrelativeData')->name('care.plan.development.review.relation');
        Route::post('/saveEmrSummary', 'RCare\Ccm\Http\Controllers\CcmController@emrSummary')->name('saveEmrSummary');

        //Services Data
        // Route::post('/care-plan-development-service-dme', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveHealthServices')->name('care.plan.development.service.dme');
        // Route::post('/care-plan-development-service-home-health', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveHealthServices')->name('care.plan.development.service.home.health');
        // Route::post('/care-plan-development-service-dialysis', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveHealthServices')->name('care.plan.development.service.dialysis');
        // Route::post('/care-plan-development-service-therapy', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveHealthServices')->name('care.plan.development.service.therapy');
        // Route::post('/care-plan-development-service-social', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveHealthServices')->name('care.plan.development.service.social');
        // Route::post('/care-plan-development-service-medical-supplies', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveHealthServices')->name('care.plan.development.service.medical.supplies');
        // Route::post('/care-plan-development-service-other', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveHealthServices')->name('care.plan.development.service.other');
        Route::post('/care-plan-development-services', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveHealthServices')->name('care.plan.development.services');

        //Medication

        Route::get('/get-medications-name/{med_name}/existmed_name', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@check_exist_med_name')->name('get.medications.name');
        Route::post('/care-plan-development-medications', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@savePatientmedicationData')->name('care.plan.development.medications');
        Route::get('/care-plan-development-medications-medicationslist/{id}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@Medications_list')->name('care.plan.development.medications.medicationslist');
        Route::get('/care-plan-development-medications-medicationslist/{id}/{component_name}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@Medications_list')->name('care.plan.development.medications.medicationslist');
        Route::get('/get-all-medications_patient-by-id/{id}/medicationspatient', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getPatientMedicationsById')->name('get.all.medications.patient.by.id');
        Route::get('/get-selected-medications_patient-by-id/{patientId}/{med_id}/selectedmedicationspatient', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getSelectedMedicationsPatientById')->name('get.selectedmedications.patient.by.id');
        Route::post('/delete-medications_patient-by-id', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@deletePatientMedicationsById')->name('delete.medications.patient.by.id');

        //NumberTracking
        Route::post('/care-plan-development-numbertracking-vitals', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@savePatientvitalData')->name('care.plan.development.numbertracking.vitals');
        Route::get('/get-patient-vital-by-id/{id}/patient-vital', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getPatientVitalsById')->name('get.all.vitals.patient.by.id');
        Route::post('/delete-patient-vital-by-id', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@deletePatientVitalsById')->name('delete.patient.vitals.by.id');

        Route::post('/care-plan-development-numbertracking-labs', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@savePatientLabData')->name('care.plan.development.numbertracking.labs');

        Route::post('/care-plan-development-numbertracking-imaging', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@savePatientImagingData')->name('care.plan.development.numbertracking.imaging');
        Route::get('/get-patient-imaging-by-id/{id}/patient-imaging', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getPatientImagingById')->name('get.all.imaging.patient.by.id');
        Route::post('/delete-patient-imaging-by-id', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@deletePatientImagingById')->name('delete.patient.imaging.by.id');

        Route::post('/care-plan-development-numbertracking-healthdata', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@savePatientHealthData')->name('care.plan.development.numbertracking.healthdata');
        Route::get('/care-plan-development-health-healthlist/{patientid}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getHealthData')->name('care.plan.development.health.healthlist');
        Route::get('/get-patient-healthdata-by-id/{id}/patient-healthdata', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getPatientHealthDataById')->name('get.all.healthdata.patient.by.id');
        Route::post('/delete-patient-healthdata-by-id', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@deletePatientHealthDataById')->name('delete.patient.healthdata.by.id');

        //providers
        // Route::get('/carePlanDevelopment/{typeId}/practice', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@fetchProviderList')->name('care.plan.development.providers');
        Route::get('/get-all-proivder-specialist_patient-by-id/{id}/providerspecialistpatient', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getPatientProviderSpecialistById')->name('get.all.providerspecialist.patient.by.id');
        Route::post('/get-delete-proivder-specialist_patient-by-id/{id}/providerspecialistpatient', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@deletePatientProviderSpecialistById')->name('get.delete.providerspecialist.patient.by.id');
        Route::get('/care-plan-development-provider-specilist-list/{id}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@Provider_Specilist_list')->name('care.plan.development.provider.providerspecilistlist');
        // Route::post('/care-plan-development-provider-pcp', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@savePatientprovidersData')->name('care.plan.development.provider.pcp');
        // Route::post('/care-plan-development-provider-specialist', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@savePatientprovidersData')->name('care.plan.development.provider.specialist');//savePatientprovidersSpecilistData
        // Route::post('/care-plan-development-provider-vision', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@savePatientprovidersData')->name('care.plan.development.provider.vision');
        // Route::post('/care-plan-development-provider-dentist', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@savePatientprovidersData')->name('care.plan.development.provider.dentist');
        Route::post('/care-plan-development-provider', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@savePatientprovidersData')->name('care.plan.development.provider');
        //Diagnosis
        Route::get('/care-plan-development-diagnosis-diagnosislist/{id}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@diagnosis_code_list')->name('care.plan.development.diagnosis.diagnosislist');
        Route::get('/get-all-code-by-id/{id}/{patient_id}/diagnosis', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getCodesById')->name('get.all.code.by.id');
        Route::get('/get-all-diagnosis_patient-by-id/{id}/diagnosispatient', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getPatientDiagnosisCodesById')->name('get.all.diagnosis.patient.by.id');
        Route::post('/care-plan-development-diagnosis-save', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@savePatientdiagnosisData')->name('care.plan.development.diagnosis.save');
        Route::get('/diagnosis-select/{id}/{patientid}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@populatePatientDiagnosis')->name('get.diagnosis.code');
        //review-pets
        Route::post('/care-plan-development-review-pet', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@savePetData')->name('care.plan.development.review.pet');
        Route::get('care-plan-development-review-petlist/{id}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getPetList')->name('care.plan.development.review.petlist');
        //review-travel
        Route::post('/care-plan-development-review-travel', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveTravelData')->name('care.plan.development.review.travel');
        Route::get('care-plan-development-review-travellist/{id}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getTravelList')->name('care.plan.development.review.travellist');

        //hobbies
        Route::post('/care-plan-development-review-hobbies', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveHobbiesData')->name('care.plan.development.review.hobbies');
        Route::get('care-plan-development-review-hobbieslist/{id}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getHobbiesList')->name('care.plan.development.review.hobbieslist');
        // Route::get('/care-plan-template-select/{id}/{patientid}', 'RCare\Ccm\Http\Controllers\CcmController@populateCarePlanTemplateData')->name('get.cp.code');
        // Route::get('/care-plan-template-add/{id}/{patientid}', 'RCare\Ccm\Http\Controllers\CcmController@addCarePlanTemplateData')->name('get.add.code');
        // Route::get('/care-plan-template-edit/{id}/{patientid}', 'RCare\Ccm\Http\Controllers\CcmController@editCarePlanTemplateData')->name('get.edit.code');
        // edit & delete hobbies,pet,travel

        Route::get('/get-all-pet_patient-by-id/{id}/petpatient', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getPatientPetById')->name('get.all.pet.patient.by.id');
        Route::get('/get-all-hobbie_patient-by-id/{id}/hobbiepatient', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getPatientHobbieById')->name('get.all.hobbie.patient.by.id');
        Route::get('/get-all-travel_patient-by-id/{id}/travelpatient', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getPatientTravelById')->name('get.all.travel.patient.by.id');
        Route::post('/get-delete-pet_patient-by-id/{id}/petpatient', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@deletePatientPetById')->name('get.delete.pet.patient.by.id');
        Route::post('/get-delete-hobbie_patient-by-id/{id}/hobbiepatient', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@deletePatientHobbieById')->name('get.delete.hobbie.patient.by.id');
        Route::post('/get-delete-travel_patient-by-id/{id}/travelpatient', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@deletePatientTravelById')->name('get.delete.travel.patient.by.id');

        // Route::post('/care-plan-data-save', 'RCare\Ccm\Http\Controllers\CcmController@saveCarePlanData')->name('care.plan.data.save');

        // Print Patient Care Plan
        // Route::get('/patient-care-plan/{id}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@patientCarePlan')->name('patient.care.plan');
        // Route::get('/cpd-patient-care-plan/{id}', 'RCare\Ccm\Http\Controllers\CPDController@cpdpatientCarePlan')->name('cpd.patient.care.plan');

        // Route::get('pdfview/{id}',array('as'=>'pdfview','uses'=>'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@pdfview'));
        // Route::get('/create-modify-patient-care-plan/{patientid}/{id}', 'RCare\Ccm\Http\Controllers\CPDController@CreateModifyPatientCarePlan')->name('create.modify.patient.care.plan');
        // Route::get('cpdpdfview/{id}',array('as'=>'pdfview','uses'=>'RCare\Ccm\Http\Controllers\CPDController@cpdpdfview'));
        // Route::get('/patient-care-plan/{id}', 'RCare\Ccm\Http\Controllers\CcmController@patientenrollmentdeatils')->name('patient.care.plan');
        Route::get('/patient-care-plan-list/{id}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@patientCarePlanList')->name('patient.care.plan.list');

        Route::post("/addnewcode", "RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanDevelopmentController@addnewcode")->name("add.new.code");
        Route::post("/delete-diagnosis", "RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@deleteCarePlan")->name("delete.diagnosis");
        // Route::post("/code-availabel", "RCare\Ccm\Http\Controllers\CcmController@checkCodeAvailabel")->name("code.availabel");
        Route::post("/lab-param", "RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getLabParamRange")->name("lab.param");
        Route::post("/finalize-cpd", "RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@finalizeCpd")->name("finalize.cpd");

        Route::post("/additional-device-email", "RCare\Ccm\Http\Controllers\CcmController@additionalDeviceEmail")->name("additional.device.email");
        Route::get('/get-message-history/{id}', 'RCare\Ccm\Http\Controllers\CcmController@listMessageHistory')->name('get.message.history');
        // report
        // Route::get('/monthly-report/patients', 'RCare\Ccm\Http\Controllers\ReportController@listMonthalyReportPatients')->name('monthly.report.patients');
        // Route::post('/monthly-report/patients-search/{practice_id}/{physician_id}/{module_id}', 'RCare\Ccm\Http\Controllers\ReportController@listMonthalyReportPatientsSearch')->name('monthly.report.patients.search');
        // Route::post('/manually-adjust-time', 'RCare\System\Http\Controllers\CommonFunctionController@manuallyAdjustTime')->name('manually.adjust.time');

        Route::get('/daily-report/patients', 'RCare\Ccm\Http\Controllers\ReportController@PatientDailyReport')->name('daily.report.patients');
        Route::get('/daily-report/search/{practiceid}/{providerid}/{module}/{date}/{time}', 'RCare\Ccm\Http\Controllers\ReportController@DailyReportPatientsSearch')->name('daily.report.search');

        Route::get('/care-manager-report', 'RCare\Ccm\Http\Controllers\ReportController@CareManagerReport')->name('care.manager.report');
        Route::get('/care-manager-report/search/{practiceid}/{providerid}/{module}/{time}/{formdate}/{todate}', 'RCare\Ccm\Http\Controllers\ReportController@CareManagerReportSearch')->name('care.manager.report.search');
        Route::post('/billupdate', 'RCare\Ccm\Http\Controllers\ReportController@CMBillUpdate')->name('bill.update');

        //No Allergies Data
        Route::get('/care-plan-development-allergies-allergylist/{id}/{allergytype}/count_allergy', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@count_Allergies_Inside_Table')->name('care.plan.development.allergies.allergylist');

        //Allergies Data
        // Route::post('/care-plan-development-allergy-food', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveAllergy')->name('care.plan.development.allergy.food');
        // Route::post('/care-plan-development-allergy-drug', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveAllergy')->name('care.plan.development.allergy.drug');
        // Route::post('/care-plan-development-allergy-enviromental', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveAllergy')->name('care.plan.development.allergy.enviromental');
        // Route::post('/care-plan-development-allergy-insect', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveAllergy')->name('care.plan.development.allergy.insect');
        // Route::post('/care-plan-development-allergy-latex', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveAllergy')->name('care.plan.development.allergy.latex');
        // Route::post('/care-plan-development-allergy-petrelated', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveAllergy')->name('care.plan.development.allergy.petrelated');
        // Route::post('/care-plan-development-allergy-other', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveAllergy')->name('care.plan.development.allergy.other');

        Route::post('/save-allergy-data', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@saveAllergy')->name('save.allergy.data');

        //CPD-patient-data
        // Route::get('/care-plan-development-allergies-allergyotherlist/{id}/{allergytype}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@Allergies_Other_list')->name('care.plan.development.allergies.allergyotherlist');
        // Route::get('/care-plan-development-allergies-druglist/{id}/{allergytype}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@Allergies_Other_list')->name('care.plan.development.allergies.druglist');
        // Route::get('/care-plan-development-allergies-foodlist/{id}/{allergytype}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@Allergies_Other_list')->name('care.plan.development.allergies.foodlist');
        // Route::get('/care-plan-development-allergies-insectlist/{id}/{allergytype}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@Allergies_Other_list')->name('care.plan.development.allergies.insectlist');
        // Route::get('/care-plan-development-allergies-latexlist/{id}/{allergytype}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@Allergies_Other_list')->name('care.plan.development.allergies.latexlist');
        // Route::get('/care-plan-development-allergies-petrelatedlist/{id}/{allergytype}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@Allergies_Other_list')->name('care.plan.development.allergies.petrelatedlist');
        // Route::get('/care-plan-development-allergies-latexlist/{id}/{allergytype}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@Allergies_Other_list')->name('care.plan.development.allergies.latexlist');
        // Route::get('/care-plan-development-allergies-enviromentallist/{id}/{allergytype}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@Allergies_Other_list')->name('care.plan.development.allergies.enviromentallist');
        Route::get('/allergies/{id}/{allergytype}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getAllergies')->name('get.allergies');

        Route::get('/get-allergies-other/{id}/{allergytype}/allergiespatient', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getAllergiesOther')->name('get.allergies.other');

        //Route::get('/care-plan-development-services-list/{id}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@Services_list')->name('care.plan.development.services.list');
        Route::get('/get-services/{id}/servicespatient', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getServices')->name('get.services');

        Route::get('/care-plan-development-services-list/{id}/{servicetype}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@Services_list')->name('care.plan.development.services.list');

        Route::get('/care-plan-development-provider-providerspecilistreviewlist/{id}/{servicetype}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@provider')->name('care.plan.development.provider.providerspecilistreviewlist');

        Route::get('/care-plan-development-services-review-otherlist/{id}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@ServicesReview_Other_list')->name('care.plan.development.services.review.otherlist');

        Route::post("/CheckCompletedCheckbox", "RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getCompletedPatientData")->name("CheckCompletedCheckbox");
        Route::post('/care-plan-call-callwrapup', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@SaveCallWrapUp')->name('care.plan.call.callwrapup');
        Route::post('/patient-first-review', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@SaveFiestReviewData')->name('patient.first.review');

        Route::post("/delete-services", "RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@deleteServices")->name("delete.services");

        Route::post("/delete-allergies", "RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@deleteAllergies")->name("delete.allergies");

        Route::get('/care-plan-development-labs-labslist/{patientid}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getLabData')->name('care.plan.development.labs.labslist');

        Route::get('/care-plan-development-imaging-imaginglist/{patientid}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getImagingData')->name('care.plan.development.imaging.imaginglist');

        Route::get('/care-plan-development-health-healthlist/{patientid}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getHealthData')->name('care.plan.development.health.healthlist');

        Route::get('/care-plan-development-vital-vitallist/{patientid}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@getVitalData')->name('care.plan.development.vital.vitallist');


        // Route::get('/care-plan-development-populateLabs/{patientid}/{labdate}/{labid}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@PopulateLabsData')->name('care.plan.development.populateLabs');
        Route::get('/care-plan-development-populateLabs/{patientid}/{labdate}/{labid}/{labdateexist}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@PopulateLabsData')->name('care.plan.development.populateLabs');

        Route::post('/delete-lab', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@deletePatientlab')->name('delete.lab');



        //Route::get('/monthly-monitoring/listSpMonthlyMonitoringPatientsSearch/{id}', 'RCare\Ccm\Http\Controllers\CcmController@listSpMonthlyMonitoringPatientsSearch')->name('listSpMonthlyMonitoringPatientsSearch');


        //print care plan modified by pranali on 18March2021
        // Route::get('/cpd-patient-care-plan/{id}', 'RCare\Ccm\Http\Controllers\PrintCarePlanController@patientCarePlan')->name('cpd.patient.care.plan');
        Route::get('/{sub_module}/patient-care-plan/{id}', 'RCare\Ccm\Http\Controllers\PrintCarePlanController@patientCarePlan')->name('patient.care.plan');
        Route::get('/{sub_module}/generate-docx/{id}', 'RCare\Ccm\Http\Controllers\PrintCarePlanController@patientCarePlan')->name('patient.care.plan.docs');
        Route::get('/monthly-monitoring/call-wrap-up-word/{id}', 'RCare\Ccm\Http\Controllers\CcmController@downloadAbleFile')->name('monthly.monitoring.call.wrap.up.word');

        // Route::get('cpdpdfview/{id}',array('as'=>'pdfview','uses'=>'RCare\Ccm\Http\Controllers\PrintCarePlanController@pdfview'));
        // Route::get('pdfview/{id}',array('as'=>'pdfview','uses'=>'RCare\Ccm\Http\Controllers\PrintCarePlanController@pdfview'));

        Route::get('/ajax/copy-previous-month-data-to-this-month/{patient_id}/{module_id}/previous-month-data', 'RCare\System\Http\Controllers\CommonFunctionController@copyPreviousMonthDataToThisMonth')->name('previous.month.data');

        Route::get('/review-data-modal-view/{id}/{module_id}/{submodule_id}/{billable}/{content}', function ($id, $module_id, $submodule_id, $billable, $content) {
            return view('Ccm::care-plan-development.care-plan-development-sub-steps.review-data-modal', ['id' => $id, 'module_id' => $module_id, 'submodule_id' => $submodule_id, 'billable' => $billable, 'content' => $content]);
        });
        Route::get('/ajax/populateCarePlanDevelopmentForm/{patientId}/{form}', 'RCare\Ccm\Http\Controllers\CarePlanDevelopmentController@populateFormVizCarePlanDevelopmentData')->name("ajax.populate.care.plan.development.form");
    });
});
