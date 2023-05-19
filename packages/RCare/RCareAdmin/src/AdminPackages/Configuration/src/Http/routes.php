<?php

/*
|--------------------------------------------------------------------------
| RCare / RCareAdmin / AdminPackages / Menu Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(["auth","roleAccess", "web"])->group(function () {
    Route::prefix('configuration')->group(function () { 
        Route::get("/configuration", "RCare\RCareAdmin\AdminPackages\Configuration\src\Http\Controllers\ConfigurationController@index")->name("displayConfiguration");

    });
});
   
Route::middleware(["auth", "web"])->group(function () {
Route::prefix('configuration')->group(function () {
    

    Route::post('/createConfiguration', 'RCare\RCareAdmin\AdminPackages\Configuration\src\Http\Controllers\ConfigurationController@createConfiguration')->name('createConfiguration'); 

    Route::get('/fetchConfiguration', 'RCare\RCareAdmin\AdminPackages\Configuration\src\Http\Controllers\ConfigurationController@fetchConfiguration')->name("fetchConfiguration");
    
   Route::get('ajax/rCare/editConfiguration/{id}/edit', 'RCare\RCareAdmin\AdminPackages\Configuration\src\Http\Controllers\ConfigurationController@editConfiguration');

   Route::post('ajax/rCare/changeConfigurationStatus/{id}/update','RCare\RCareAdmin\AdminPackages\Configuration\src\Http\Controllers\ConfigurationController@changeConfigurationStatus');
  
    // Route::post('ajax/rCare/update-menu','RCare\RCareAdmin\AdminPackages\Menu\src\Http\Controllers\MenuController@updateMenu')->name('update_menu');
    // Route::post('ajax/rCare/deleteMenu/{menu_id}/delete','RCare\RCareAdmin\AdminPackages\Menu\src\Http\Controllers\MenuController@deleteMenu');
	});


});
