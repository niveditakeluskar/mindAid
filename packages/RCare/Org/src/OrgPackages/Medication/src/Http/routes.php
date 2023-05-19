<?php

/*
|--------------------------------------------------------------------------
| RCare / Org / src / OrgPackages / Medication / Routes
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
            //Route::get("/org-medication", "RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@index1")->name("org_medication");
           Route::get("/org-medication", function(){
                return view('Medication::medications_list');
            })->name("org_medication");
        });
        Route::get("/org-medication-list", "RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@MedicationList")->name("org_medication_list");
        Route::post("/create-org-medication", "RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@addMedication")->name("create_org_medication");
        Route::post('editMedication', 'RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@editMedication')->name("editMedication");
        Route::post('/medicationStatus/{id}', 'RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@changeMedicationStatus');
        Route::post('update-org-medication','RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@updateMedication')->name('update_org_medication');
        // Route::post('/ajax/rCare/deleteRole/{id}/delete','RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@deleteRole');
        Route::get('ajax/medication_populate/{id}/populate', 'RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@populateMedication')->name("ajax.medication.populate");
        Route::get('ajax/medication/list', 'RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@getActiveMedicationList')->name("ajax.medication.list");
        // Route::get('ajax/medication/list', 'RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@getMedicationName')->name("ajax.medication.exist");
 	});
});
/*
// Authenticated user only routes
Route::middleware(["auth","roleAccess", "web"])->group(function () {
	//Auth::routes();
	Route::prefix('org')->group(function () {
        Route::get("/org-medication", "RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@index1")->name("org_medication");
        
    });
});

Route::middleware(["auth", "web"])->group(function () {
	//Auth::routes();
	Route::prefix('org')->group(function () {
        Route::get("/org-medication-list", "RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@MedicationList")->name("org_medication_list");
        Route::post("/create-org-medication", "RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@addMedication")->name("create_org_medication");
        Route::post('editMedication', 'RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@editMedication')->name("editMedication");
        Route::post('/medicationStatus/{id}', 'RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@changeMedicationStatus');
        Route::post('update-org-medication','RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@updateMedication')->name('update_org_medication');
        // Route::post('/ajax/rCare/deleteRole/{id}/delete','RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@deleteRole');
        Route::get('ajax/medication_populate/{id}/populate', 'RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@populateMedication')->name("ajax.medication.populate");
        Route::get('ajax/medication/list', 'RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@getActiveMedicationList')->name("ajax.medication.list");
        Route::get('ajax/medication/list', 'RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@getMedicationName')->name("ajax.medication.exist");
    });
});*/