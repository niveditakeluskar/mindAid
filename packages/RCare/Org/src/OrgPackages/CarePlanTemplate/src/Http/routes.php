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
            Route::get("/care-plan-template", function(){
                return view('CarePlanTemplate::care-plan-template-list');
            })->name("care_plan_template");
        });
        Route::get("/care-plan-template-add", "RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@addCarePlanTemplate")->name("care_plan_template_add");
        Route::get("/care-plan-template-edit/{id}", "RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@editCarePlanTemplate")->name("diagnosis_code_edit");
        Route::get("/care-plan-template-list", "RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@CarePlanTemplateList")->name("care_plan_template_list");
        Route::post('/ajax/submit-care-plan-template','RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@saveCarePlanTemplate')->name("ajax.save.careplantemplate");
        Route::get('/ajax/populateCarePlanTemaplateForm/{patientId}','RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@populateCarePlanTemplateData')->name("ajax.populate.care.plan.template.data");
        Route::get('/delete-careplan/{id}', 'RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@deleteCareplan')->name('delete.careplan'); 
        Route::get("/getcode/{id}", "RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@getcode")->name("get.code");
        Route::post("/code-availabel", "RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@checkCodeAvailabel")->name("code.availabel"); 

        Route::get('/care-plan-template-pdf/{id}', 'RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@CarePlanTemplatePdf')->name('care.plan.template.pdf');
        Route::get('/editdiagnosis/{id}', 'RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@PopulateDiagnosis')->name('editdiagnosis');
        Route::post('/ajax/save-diagnosis-careplan','RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@UpdateDiagnosis')->name("ajax.save.diagnosis.careplan");
    });
});

// Authenticated user only routes
//  Route::group( ['middleware' => 'auth' ], function() {
// Route::middleware(["auth","roleAccess", "web"])->group(function () {
// 	Route::prefix('org')->group(function () {
//         //User Role CRUD
//         // Route::get("/care-plan-template", "RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@index")->name("care_plan_template");
//         Route::get("/care-plan-template", function(){
//             return view('CarePlanTemplate::care-plan-template-list');
//         })->name("care_plan_template");

//     });
// }); 

// Route::middleware(["auth", "web"])->group(function () {
// 	Route::prefix('org')->group(function () {
//         Route::get("/care-plan-template-add", "RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@addCarePlanTemplate")->name("care_plan_template_add");
//         Route::get("/care-plan-template-edit/{id}", "RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@editCarePlanTemplate")->name("diagnosis_code_edit");
//         Route::get("/care-plan-template-list", "RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@CarePlanTemplateList")->name("care_plan_template_list");
//         Route::post('/ajax/submit-care-plan-template','RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@saveCarePlanTemplate')->name("ajax.save.careplantemplate");
//         Route::get('/ajax/populateCarePlanTemaplateForm/{patientId}','RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@populateCarePlanTemplateData')->name("ajax.populate.care.plan.template.data");
//         Route::get('/delete-careplan/{id}', 'RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@deleteCareplan')->name('delete.careplan'); 
//         Route::get("/getcode/{id}", "RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@getcode")->name("get.code");
//         Route::post("/code-availabel", "RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@checkCodeAvailabel")->name("code.availabel"); 

//          Route::get('/care-plan-template-pdf/{id}', 'RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@CarePlanTemplatePdf')->name('care.plan.template.pdf');

//           Route::get('/editdiagnosis/{id}', 'RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@PopulateDiagnosis')->name('editdiagnosis');

//            Route::post('/ajax/save-diagnosis-careplan','RCare\Org\OrgPackages\CarePlanTemplate\src\Http\Controllers\CarePlanTemplateController@UpdateDiagnosis')->name("ajax.save.diagnosis.careplan");
         
//     });
// }); 
