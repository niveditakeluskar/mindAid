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
            // Route::get("/activity", function(){
            //     return view('Activity::activity');
            // })->name("activity");  
            Route::get("/activity","RCare\Org\OrgPackages\Activity\src\Http\Controllers\ActivityController@index")->name("activity");
        });

    Route::post("/add-activity", "RCare\Org\OrgPackages\Activity\src\Http\Controllers\ActivityController@AddActivity")->name("add-activity");
    Route::get("/activity_list","RCare\Org\OrgPackages\Activity\src\Http\Controllers\ActivityController@getActivityListData")->name("activity_list");
    Route::get('/ajax/populateActivityForm/{activityid}','RCare\Org\OrgPackages\Activity\src\Http\Controllers\ActivityController@populateActivityData')->name("ajax.populate.activity.data");
    Route::post("/updateactivity", "RCare\Org\OrgPackages\Activity\src\Http\Controllers\ActivityController@updateActivity")->name("updateactivity");
    Route::post('/activityStatus/{id}', 'RCare\Org\OrgPackages\Activity\src\Http\Controllers\ActivityController@changeActivityStatus'); 
    Route::get('/updateactivitysequence', 'RCare\Org\OrgPackages\Activity\src\Http\Controllers\ActivityController@UpdateActivitySequenceInline')->name('update.activity.sequences.inline');
    
 	});
});



