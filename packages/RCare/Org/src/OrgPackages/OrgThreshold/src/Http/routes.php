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
            Route::get("/group-threshold", function(){
                return view('Threshold::group-threshold');
            })->name("group_threshold");   
        });
        Route::post("/create-threshold-group", "RCare\Org\OrgPackages\Threshold\src\Http\Controllers\ThresholdController@addgroupthreshold")->name("create_threshold_group");
        Route::get('ajax/group_threshold_populate/populate', 'RCare\Org\OrgPackages\Threshold\src\Http\Controllers\ThresholdController@populatethreshold')->name("ajax.threshold.populate");
    });
}); 
