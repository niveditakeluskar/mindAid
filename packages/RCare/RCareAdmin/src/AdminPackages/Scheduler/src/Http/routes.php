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
    Route::get('/scheduler', 'RCare\RCareAdmin\AdminPackages\Scheduler\src\Http\Controllers\SchedulerController@index')->name('scheduler');  
    Route::post('/save-scheduler', 'RCare\RCareAdmin\AdminPackages\Scheduler\src\Http\Controllers\SchedulerController@saveScheduler')->name('save.scheduler'); 
    Route::get('/scheduler-list', 'RCare\RCareAdmin\AdminPackages\Scheduler\src\Http\Controllers\SchedulerController@SchedulerList')->name('scheduler.list'); 
    Route::get('/ajax/populateSchedulerForm/{schedulerid}','RCare\RCareAdmin\AdminPackages\Scheduler\src\Http\Controllers\SchedulerController@populateSchedulerData')->name("ajax.populate.scheduler.data");
    Route::post("/update-scheduler", "RCare\RCareAdmin\AdminPackages\Scheduler\src\Http\Controllers\SchedulerController@updateScheduler")->name("update.scheduler");
    // Route::get('ajax/rCare/editUser/{id}/edit', 'RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@edit');
    // Route::post('admin-update-User','RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@updateUser')->name('admin_update_User');

    //  Route::post('admin-update-Name','RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@updateUserName')->name('admin_update_Name');
    // Route::get("/user-list", "RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@fetchUser")->name("user_list");
    // //Route::post('/ajax/rCare/deleteUser/{id}/delete','RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@deletUser');
    // Route::get('change-User-Status/{id}', 'RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@changeUserStatus');
    });
});