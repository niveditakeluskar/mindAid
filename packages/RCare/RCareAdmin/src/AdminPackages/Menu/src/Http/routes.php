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
Route::middleware(["auth", "web"])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get("/menu", "RCare\RCareAdmin\AdminPackages\Menu\src\Http\Controllers\MenuController@index")->name("displayMenus");
        Route::post('/createMenu', 'RCare\RCareAdmin\AdminPackages\Menu\src\Http\Controllers\MenuController@createMenu')->name('createMenu'); 
        Route::get('/fetchMenu', 'RCare\RCareAdmin\AdminPackages\Menu\src\Http\Controllers\MenuController@fetchMenu')->name("fetchMenu");
        Route::get('ajax/rCare/editMenu/{id}/edit', 'RCare\RCareAdmin\AdminPackages\Menu\src\Http\Controllers\MenuController@editMenu');
        Route::post('ajax/rCare/update-menu','RCare\RCareAdmin\AdminPackages\Menu\src\Http\Controllers\MenuController@updateMenu')->name('update_menu');
        Route::post('ajax/rCare/deleteMenu/{menu_id}/delete','RCare\RCareAdmin\AdminPackages\Menu\src\Http\Controllers\MenuController@deleteMenu');
	});
}); 