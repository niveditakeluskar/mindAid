<?php

/*
|--------------------------------------------------------------------------
| RCare / RCareAdmin / AdminPackages / Services Routes
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
    //services
    Route::get("/services", "RCare\RCareAdmin\AdminPackages\Services\src\Http\Controllers\ServicesController@index")->name("services");
    Route::post('/createServices', 'RCare\RCareAdmin\AdminPackages\Services\src\Http\Controllers\ServicesController@createServices')->name('create-services'); 

    Route::get('/ajax/rCare/fetchServices', 'RCare\RCareAdmin\AdminPackages\Services\src\Http\Controllers\ServicesController@fetchServices')->name("fetch_services");
    
    Route::get('/ajax/rCare/editServices/{id}/edit', 'RCare\RCareAdmin\AdminPackages\Services\src\Http\Controllers\ServicesController@editServices');
    Route::post('/ajax/rCare/updateServices','RCare\RCareAdmin\AdminPackages\Services\src\Http\Controllers\ServicesController@updateServices')->name('updateServices');
    Route::post('/ajax/rCare/deleteServices/{service_id}/delete','RCare\RCareAdmin\AdminPackages\Services\src\Http\Controllers\ServicesController@deleteServices');
    Route::get('/changeServiceStatus/{id}', 'RCare\RCareAdmin\AdminPackages\Services\src\Http\Controllers\ServicesController@changeServiceStatus');
});
});