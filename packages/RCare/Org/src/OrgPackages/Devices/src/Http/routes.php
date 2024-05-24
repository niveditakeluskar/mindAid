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
        Route::get("/devices", "RCare\Org\OrgPackages\Devices\src\Http\Controllers\DevicesController@index")->name("devices");
        Route::get("/devices-add", "RCare\Org\OrgPackages\Devices\src\Http\Controllers\DevicesController@addDevices")->name("devices_add");
        Route::get("/devices-edit/{id}", "RCare\Org\OrgPackages\Devices\src\Http\Controllers\DevicesController@editDevices")->name("devices_edit");
        Route::get("/devices-list", "RCare\Org\OrgPackages\Devices\src\Http\Controllers\DevicesController@DevicesList")->name("devices_list");
        Route::post('/ajax/submitDevices','RCare\Org\OrgPackages\Devices\src\Http\Controllers\DevicesController@saveDevices')->name("ajax.save.devices");
        Route::get('/ajax/populateDeviceForm/{patientId}','RCare\Org\OrgPackages\Devices\src\Http\Controllers\DevicesController@populateDevicesData')->name("ajax.populate.devices.data");
        Route::post('/delete-devices/{id}', 'RCare\Org\OrgPackages\Devices\src\Http\Controllers\DevicesController@deleteDevices')->name('delete.devices'); 
 	});
});

// Authenticated user only routes
//  Route::group( ['middleware' => 'auth' ], function() {
/*Route::middleware(["auth","roleAccess", "web"])->group(function () {
	Route::prefix('org')->group(function () {
        //User Role CRUD
        Route::get("/devices", "RCare\Org\OrgPackages\Devices\src\Http\Controllers\DevicesController@index")->name("devices");
        Route::get("/devices-add", "RCare\Org\OrgPackages\Devices\src\Http\Controllers\DevicesController@addDevices")->name("devices_add");
        Route::get("/devices-edit/{id}", "RCare\Org\OrgPackages\Devices\src\Http\Controllers\DevicesController@editDevices")->name("devices_edit");
        Route::get("/devices-list", "RCare\Org\OrgPackages\Devices\src\Http\Controllers\DevicesController@DevicesList")->name("devices_list");
        Route::post('/ajax/submitDevices','RCare\Org\OrgPackages\Devices\src\Http\Controllers\DevicesController@saveDevices')->name("ajax.save.devices");
        Route::get('/ajax/populateDeviceForm/{patientId}','RCare\Org\OrgPackages\Devices\src\Http\Controllers\DevicesController@populateDevicesData')->name("ajax.populate.devices.data");
        Route::post('/delete-devices/{id}', 'RCare\Org\OrgPackages\Devices\src\Http\Controllers\DevicesController@deleteDevices')->name('delete.devices'); 
    });
});*/