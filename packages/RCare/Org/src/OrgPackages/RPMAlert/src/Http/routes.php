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
            
        });
        Route::get("/rpm-alert", "RCare\Org\OrgPackages\RPMAlert\src\Http\Controllers\RPMAlertController@index")->name("rpm_alert");      
        Route::get("/rpm-alert-list/{fromdate}/{todate}", "RCare\Org\OrgPackages\RPMAlert\src\Http\Controllers\RPMAlertController@RPMAlertList")->name("rpm_alert_list");       
        Route::post('/delete-devices/{id}', 'RCare\Org\OrgPackages\Devices\src\Http\Controllers\DevicesController@deleteDevices')->name('delete.devices'); 

 	});
});

