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
            Route::get("/holiday", function(){
                return view('Holiday::holiday');
            })->name("holiday");
        });
        Route::post('/ajax/submitHoliday','RCare\Org\OrgPackages\Holiday\src\Http\Controllers\holidayController@saveHoliday')->name("ajax.save.holiday");
        Route::get("/holiday-list", "RCare\Org\OrgPackages\Holiday\src\Http\Controllers\holidayController@HolidayList")->name("holidays_list");
        Route::get('/ajax/populateForm/{patientId}','RCare\Org\OrgPackages\Holiday\src\Http\Controllers\holidayController@populateHolidayData')->name("ajax.populate.holiday.data");
        Route::get("/holiday-edit/{id}", "RCare\Org\OrgPackages\Holiday\src\Http\Controllers\holidayController@editHoliday")->name("holiday_edit");
        Route::post('/delete-holiday/{id}', 'RCare\Org\OrgPackages\Holiday\src\Http\Controllers\holidayController@deleteHoliday')->name('delete.holiday'); 
    });
    // Route::get("/holiday", function(){
    //     return view('Holiday::holiday');
    // })->name("holiday");
    // Route::post('/ajax/submitHoliday','RCare\Org\OrgPackages\Holiday\src\Http\Controllers\holidayController@saveHoliday')->name("ajax.save.holiday");
    // Route::get("/holiday-list", "RCare\Org\OrgPackages\Holiday\src\Http\Controllers\holidayController@HolidayList")->name("holidays_list");
    // Route::get('/ajax/populateForm/{patientId}','RCare\Org\OrgPackages\Holiday\src\Http\Controllers\holidayController@populateHolidayData')->name("ajax.populate.holiday.data");
    // Route::get("/holiday-edit/{id}", "RCare\Org\OrgPackages\Holiday\src\Http\Controllers\holidayController@editHoliday")->name("holiday_edit");
    // Route::post('/delete-holiday/{id}', 'RCare\Org\OrgPackages\Holiday\src\Http\Controllers\holidayController@deleteHoliday')->name('delete.holiday'); 
});
