<?php

/*
|--------------------------------------------------------------------------
| RCare / Org / OrgPackages / Stage Code Routes
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
            Route::get('/step', 'RCare\Org\OrgPackages\StageCodes\src\Http\Controllers\StageCodesController@fetchStageCode')->name("stage-codes");
        });
        Route::post("/createStageCode", "RCare\Org\OrgPackages\StageCodes\src\Http\Controllers\StageCodesController@createStageCode")->name('create_stage_code'); 
        Route::get('ajax/editStageCode/{id}/edit', 'RCare\Org\OrgPackages\StageCodes\src\Http\Controllers\StageCodesController@editStageCode');
        Route::post('ajax/updateStageCode','RCare\Org\OrgPackages\StageCodes\src\Http\Controllers\StageCodesController@updateStageCode')->name('updateStageCode');
        Route::post('ajax/deleteStageCode/{stageCode_id}/delete','RCare\Org\OrgPackages\StageCodes\src\Http\Controllers\StageCodesController@deleteStageCode');
        Route::get("/ajax/stage_codes/{id}/stage_codes", "RCare\Org\OrgPackages\StageCodes\src\Http\Controllers\StageCodesController@stageCodes")->name("ajax.stages.stagecodes");
        Route::get("/ajax/submodule/{id}/stages", "RCare\Org\OrgPackages\StageCodes\src\Http\Controllers\StageCodesController@stages")->name("ajax.submodule.stages");
 	});
});
/*
// Authenticated user only routes
Route::middleware(["auth","roleAccess", "web"])->group(function () {
    Route::prefix('org')->group(function () {
        //User Role CRUD
        // Route::get("/stage-codes", "RCare\Org\OrgPackages\StageCodes\src\Http\Controllers\StageCodesController@index");
        
        Route::get('/step', 'RCare\Org\OrgPackages\StageCodes\src\Http\Controllers\StageCodesController@fetchStageCode')->name("stage-codes");
        
   });
}); 

Route::middleware(["auth", "web"])->group(function () {
    Route::prefix('org')->group(function () {
        Route::post("/createStageCode", "RCare\Org\OrgPackages\StageCodes\src\Http\Controllers\StageCodesController@createStageCode")->name('create_stage_code'); 
        Route::get('ajax/editStageCode/{id}/edit', 'RCare\Org\OrgPackages\StageCodes\src\Http\Controllers\StageCodesController@editStageCode');
        Route::post('ajax/updateStageCode','RCare\Org\OrgPackages\StageCodes\src\Http\Controllers\StageCodesController@updateStageCode')->name('updateStageCode');
        Route::post('ajax/deleteStageCode/{stageCode_id}/delete','RCare\Org\OrgPackages\StageCodes\src\Http\Controllers\StageCodesController@deleteStageCode');
        Route::get("/ajax/stage_codes/{id}/stage_codes", "RCare\Org\OrgPackages\StageCodes\src\Http\Controllers\StageCodesController@stageCodes")->name("ajax.stages.stagecodes");
        Route::get("/ajax/submodule/{id}/stages", "RCare\Org\OrgPackages\StageCodes\src\Http\Controllers\StageCodesController@stages")->name("ajax.submodule.stages");
    });
}); */