<?php

use RCare\Patients\Models\Patients;
use RCare\System\Support\Form;
use RCare\Org\OrgPackages\Users\src\Models\Users;
use RCare\Org\OrgPackages\Providers\src\Models\Providers;

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
        Route::get('/patients', 'RCare\Patients\Http\Controllers\PatientController@index');
    });
});
// Authenticated user only routes
Route::middleware(["auth", "web"])->group(function () {
    Route::prefix('patients')->group(function () { 
        Route::get('/patients-list', 'RCare\Patients\Http\Controllers\PatientController@fetchPatients')->name('patients_list'); 
        Route::get('/patientdetails/{id}', 'RCare\Patients\Http\Controllers\PatientController@fetching')->name('patient.details');   
        Route::post('/patient-surgery', 'RCare\Patients\Http\Controllers\PatientController@savePatientSurgeryData')->name("save.patient.surgeryData");
       
        Route::post('/ajax/submitRegistration', 'RCare\Patients\Http\Controllers\PatientController@patientRegistration')->name("ajax.patient.registration");
        
        Route::get('/questions/{id}', 'RCare\Patients\Http\Controllers\PatientController@fetchQuestions')->name('questions'); 
        Route::get('/questions-render/{id}', 'RCare\Patients\Http\Controllers\PatientController@fetchTreeQuestions')->name('questions.render'); 
        Route::get('/patient-relationship-questionnaire/{patient_id}/{module_id}/{component_id}/patient-relationship-questionnaire', 'RCare\Patients\Http\Controllers\PatientController@fetchPatientRelationshipQuestionnaires')->name('patient_relationship_questionnaire');
        Route::post('/monthly-monitoring-call-relationship', 'RCare\Patients\Http\Controllers\PatientController@SaveCallRelationship')->name('monthly.monitoring.call.relationship');
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
