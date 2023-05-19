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

    //Users CRUD
    Route::get('/test', 'RCare\RCareAdmin\AdminPackages\Test\src\Http\Controllers\TestController@index')->name('test');
    Route::get("/test-list", "RCare\RCareAdmin\AdminPackages\Test\src\Http\Controllers\TestController@fetchTest")->name("list_test");
    Route::get('/test-add', 'RCare\RCareAdmin\AdminPackages\Test\src\Http\Controllers\TestController@testAdd')->name('test-add');
    Route::post("/ajax/test_save", 'RCare\RCareAdmin\AdminPackages\Test\src\Http\Controllers\TestController@createRcareusers')->name("ajax.test.save");
    Route::get("/ajax/test_populate", 'RCare\RCareAdmin\AdminPackages\Test\src\Http\Controllers\TestController@populate')->name("ajax.test.populate");
    // Route::get("/ajax/test_populate", function(){
    //         return "hello";
    // })->name("ajax.test.populate");
    Route::get('/auto-populate', 'RCare\RCareAdmin\AdminPackages\test\src\Http\Controllers\TestController@autoPopulateExample')->name('auto-populate');

    // Route::get("/auto-populate", "RCare\RCareAdmin\AdminPackages\test\src\Http\Controllers\TestController@autoPopulateExample")->name("auto-populate");

    // Route::POST('/edit-user', 'RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@editRcareUser')->name('edituser');
    // Route::get('/ajax/rCare/editUser/{id}/edit', 'RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@edit');
    // Route::post('/ajax/rCare/updateUser','RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@updateUser')->name('updateUser');
    // Route::post('/ajax/rCare/deleteUser/{id}/delete','RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@deletUser');
    // Route::get('/changeUserStatus/{id}', 'RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@changeUserStatus');

});