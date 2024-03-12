<?php

/*
|--------------------------------------------------------------------------
| RCare / RCareAdmin / AdminPackages / User Roles Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('org')->group(function () {
    Route::middleware(["auth", "web"])->group(function () {
        Route::middleware(["roleAccess"])->group(function () {
            Route::get("/diagnosis-code", function(){
                return view('Diagnosis::diagnosis-list');
            })->name("diagnosis_code");
        });
        Route::get("/diagnosis-code-add", "RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@addDiagnosis")->name("diagnosis_code_add");
        Route::get("/diagnosis-code-edit/{id}", "RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@editDiagnosis")->name("diagnosis_code_edit");
        Route::get("/diagnosis-code-list", "RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@DiagnosisList")->name("diagnosis_code_list");
        Route::post('/ajax/submitDiagnosis','RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@saveDiagnosis')->name("ajax.save.diagnosis");
        Route::get('/ajax/populateForm/{patientId}','RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@populateDiagnosisData')->name("ajax.populate.diagnosis.data");
        Route::post('/delete-diagnosis/{id}', 'RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@deleteDiagnosis')->name('delete.diagnosis'); 

        Route::post('/condition-exist', 'RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@CheckConditionExist')->name('condition.exist');
        Route::get('/ajax/diagnosis/{id}/{patientid}/{condition_name}/{code}/editpatientdiagnosisId', 'RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@getDiagnoData')->name('ajax.diagnosis.editpatientdiagnosisid');
        Route::get('/ajax/diagnosis/{id}/patientdiagnosiscount', 'RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@getDiagnoDataCount')->name('ajax.diagnosis.count');
        Route::get('/ajax/diagnosis/{patientid}/patientdiagnosiscountforbubble', 'RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@getDistinctDiagnosisCodesCountForBubble')->name('ajax.diagnosis.count');

    
    });
});
/*
// Authenticated user only routes
//  Route::group( ['middleware' => 'auth' ], function() {
Route::middleware(["auth","roleAccess", "web"])->group(function () {
	Route::prefix('org')->group(function () {
        //User Role CRUD
        Route::get("/diagnosis-code", "RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@index")->name("diagnosis_code");
       
       
    });
});

Route::middleware(["auth", "web"])->group(function () {
	Route::prefix('org')->group(function () {
        Route::get("/diagnosis-code-add", "RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@addDiagnosis")->name("diagnosis_code_add");
        Route::get("/diagnosis-code-edit/{id}", "RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@editDiagnosis")->name("diagnosis_code_edit");
        Route::get("/diagnosis-code-list", "RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@DiagnosisList")->name("diagnosis_code_list");
        Route::post('/ajax/submitDiagnosis','RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@saveDiagnosis')->name("ajax.save.diagnosis");
        Route::get('/ajax/populateForm/{patientId}','RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@populateDiagnosisData')->name("ajax.populate.diagnosis.data");
        Route::post('/delete-diagnosis/{id}', 'RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@deleteDiagnosis')->name('delete.diagnosis'); 

         Route::post('/condition-exist', 'RCare\Org\OrgPackages\Diagnosis\src\Http\Controllers\DiagnosisController@CheckConditionExist')->name('condition.exist');

        });
    });*/
          