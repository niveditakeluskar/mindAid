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
    Route::prefix('admin')->group(function () {
    //Users CRUD
    Route::get('/users', 'RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@index')->name('users'); 
    Route::POST('/create-users', 'RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@createRcareusers')->name('create_user'); 
    Route::POST('edit-user', 'RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@editRcareUser')->name('edituser');
    Route::get('ajax/rCare/editUser/{id}/edit', 'RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@edit');
    Route::post('admin-update-User','RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@updateUser')->name('admin_update_User');

     Route::post('admin-update-Name','RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@updateUserName')->name('admin_update_Name');
    Route::get("/user-list", "RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@fetchUser")->name("user_list");
    //Route::post('/ajax/rCare/deleteUser/{id}/delete','RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@deletUser');
    Route::get('change-User-Status/{id}', 'RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@changeUserStatus');
    });
});