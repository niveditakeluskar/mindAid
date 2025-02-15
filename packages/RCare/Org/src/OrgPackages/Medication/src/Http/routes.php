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
            // //Route::get("/org-medication", "RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@index1")->name("org_medication");
        //    Route::get("/org-surgery", function(){
        //         return view('Medication::medications_list'); 
        //     })->name("org_surgery");  
                Route::get("/org-surgery", function(){
                        return view('Medication::surgery-main');
                    })->name("org_surgery");  
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
        //category
        Route::get("/org-medication-category-list", "RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@CategoryList")->name("org_medication_category_list");
        Route::post("create-org-category", "RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@createCategory")->name("create_org_category");
        Route::post('update-org-category','RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@updatecategory')->name('update_org_category');
        // subcategory
        Route::get("/org-medication-subcategory-list", "RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@SubCategoryList")->name("org_medication_subcategory_list");
        Route::post("create-org-subcategory", "RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@createSubCategory")->name("create_org_subcategory");
        Route::post('update-org-subcategory','RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@updateSubcategory')->name('update_org_subcategory');
    
    });
});
