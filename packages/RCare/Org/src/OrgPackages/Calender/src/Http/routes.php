<?php

/*
|--------------------------------------------------------------------------
| RCare / RCareAdmin / AdminPackages / Users Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authenticated user only routes
 Route::middleware(["auth", "web"])->group(function () {      
    Route::prefix('org')->group(function () {     
    //Users CRUD
    Route::get('/calender/{patientId}/{moduleId}/cal', 'RCare\Org\OrgPackages\Calender\src\Http\Controllers\CalenderController@index')->name('calender');
    // Route::get('/googlecalender','RCare\Org\OrgPackages\Calender\src\Http\Controllers\GetCalenderController@index')->name('googlecalender');
    // Route::get('/oauth', ['as' => 'oauthCallback', 'uses' => 'RCare\Org\OrgPackages\Calender\src\Http\Controllers\GetCalenderController@oauth']);
    Route::get('/getsidecaldata','RCare\Org\OrgPackages\Calender\src\Http\Controllers\CalenderController@getDataInSidebarCalender');
    Route::get('/getcaldata/{patientId}/{moduleId}/cal','RCare\Org\OrgPackages\Calender\src\Http\Controllers\CalenderController@getDataCalender');

    Route::post('/updatecal','RCare\Org\OrgPackages\Calender\src\Http\Controllers\CalenderController@updateDataCalender');
    Route::post('/addcal','RCare\Org\OrgPackages\Calender\src\Http\Controllers\CalenderController@addDataCalender');

	});
});