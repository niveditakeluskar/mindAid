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
            Route::get("/labs", function(){
                return view('Labs::labs-list');
            })->name("labs");
        });
        Route::get("/labs-edit/{id}", "RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@editLabs")->name("labs_edit");
        Route::get("/labs-list", "RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@LabsList")->name("labs_list");
       // Route::post('/ajax/submitLabs','RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@saveLabs')->name("ajax.save.labs");
        Route::post('/ajax/submitLabs','RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@saveLabs')->name("submit_lab");

        Route::get('/ajax/populateLabsForm/{patientId}','RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@populateLabsData')->name("ajax.populate.labs.data");
        Route::post("/existlab", "RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@existLab")->name("existlab");

        Route::post('/labStatus/{id}', 'RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@changeLabStatus');
        // Route::post('/delete-lab/{id}', 'RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@deleteLab')->name('delete.lab'); 
 	});
});

/*
// Authenticated user only routes
//  Route::group( ['middleware' => 'auth' ], function() {
Route::middleware(["auth","roleAccess", "web"])->group(function () {
	Route::prefix('org')->group(function () {
        //User Role CRUD
        Route::get("/labs", "RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@index")->name("labs");
        // Route::get("/labs-add", "RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@addLabs")->name("labs_add");
        
       
    });
});

Route::middleware(["auth", "web"])->group(function () {
	Route::prefix('org')->group(function () {
        Route::get("/labs-edit/{id}", "RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@editLabs")->name("labs_edit");
        Route::get("/labs-list", "RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@LabsList")->name("labs_list");
       // Route::post('/ajax/submitLabs','RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@saveLabs')->name("ajax.save.labs");
        Route::post('/ajax/submitLabs','RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@saveLabs')->name("submit_lab");

        Route::get('/ajax/populateLabsForm/{patientId}','RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@populateLabsData')->name("ajax.populate.labs.data");
        Route::post("/existlab", "RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@existLab")->name("existlab");

        Route::post('/labStatus/{id}', 'RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@changeLabStatus');
        // Route::post('/delete-lab/{id}', 'RCare\Org\OrgPackages\Labs\src\Http\Controllers\LabsController@deleteLab')->name('delete.lab'); 
    });
});*/