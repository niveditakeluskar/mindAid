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
            Route::get("/stages", "RCare\Org\OrgPackages\Stages\src\Http\Controllers\StagesController@index"); 
        });
        Route::post("/createStage", "RCare\Org\OrgPackages\Stages\src\Http\Controllers\StagesController@createStage")->name('create_stage'); 
        Route::get('/stageList', 'RCare\Org\OrgPackages\Stages\src\Http\Controllers\StagesController@fetchStage')->name("stageList");
        Route::get('ajax/editStage/{id}/edit', 'RCare\Org\OrgPackages\Stages\src\Http\Controllers\StagesController@editStage');
        Route::post('ajax/updateStage','RCare\Org\OrgPackages\Stages\src\Http\Controllers\StagesController@updateStage')->name('updateStage');
        Route::post('ajax/deleteStage/{stage_id}/delete','RCare\Org\OrgPackages\Stages\src\Http\Controllers\StagesController@deleteStage');
        Route::get("/ajax/stages/{id}/stages", "RCare\Org\OrgPackages\Stages\src\Http\Controllers\StagesController@subModuleStages")->name("ajax.submodules.stages");
 	});
});
/*
// Authenticated user only routes
Route::middleware(["auth","roleAccess", "web"])->group(function () {
    Route::prefix('org')->group(function () {
        //User Role CRUD
        //Route::get("/org-user-roles", "RCare\Org\OrgPackages\Roles\src\Http\Controllers\UserRolesController@index")->name("org_user_roles");
        Route::get("/stages", "RCare\Org\OrgPackages\Stages\src\Http\Controllers\StagesController@index"); 
        
   });
}); 

Route::middleware(["auth", "web"])->group(function () {
    Route::prefix('org')->group(function () {
        Route::post("/createStage", "RCare\Org\OrgPackages\Stages\src\Http\Controllers\StagesController@createStage")->name('create_stage'); 
        Route::get('/stageList', 'RCare\Org\OrgPackages\Stages\src\Http\Controllers\StagesController@fetchStage')->name("stageList");
        Route::get('ajax/editStage/{id}/edit', 'RCare\Org\OrgPackages\Stages\src\Http\Controllers\StagesController@editStage');
        Route::post('ajax/updateStage','RCare\Org\OrgPackages\Stages\src\Http\Controllers\StagesController@updateStage')->name('updateStage');
        Route::post('ajax/deleteStage/{stage_id}/delete','RCare\Org\OrgPackages\Stages\src\Http\Controllers\StagesController@deleteStage');
        Route::get("/ajax/stages/{id}/stages", "RCare\Org\OrgPackages\Stages\src\Http\Controllers\StagesController@subModuleStages")->name("ajax.submodules.stages");
    });
}); */