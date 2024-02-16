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

// Authenticated user only routes
// //Route::middleware(["auth", "web"])->group(function () {
// 	Route::prefix('org')->group(function () {
//     //User Role CRUD
//     //Route::get("/org-user-roles", "RCare\Org\OrgPackages\Roles\src\Http\Controllers\UserRolesController@index")->name("org_user_roles");
//     Route::get("/org-menus", "RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@index")->name("org_menus");
//     Route::post('/create-menu', 'RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@createMenu')->name('create_menu'); 
//     Route::get('/org-menu-list', 'RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@fetchMenu')->name("org_menu_list");
//     Route::get('ajax/editMenu/{id}/edit', 'RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@editMenu');
//     Route::post('/ajax/updateMenu','RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@updateMenu')->name('updateMenu');
//     Route::post('ajax/deleteMenu/{menu_id}/delete','RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@deleteMenu');
//     Route::post('GetComponent', 'RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@GetComponent')->name('GetComponent'); 
//    // });
// }); 
Route::prefix('org')->group(function () {
  Route::middleware(["auth", "web"])->group(function () {
      Route::middleware(["roleAccess"])->group(function () {
        Route::get("/org-menus", "RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@index")->name("org_menus");
      });
      Route::post('/create-menu', 'RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@createMenu')->name('create_menu'); 
      Route::post("/ajax/menu_save", "RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@createMenu")->name("ajax.menu.save");
      Route::get('/org-menu-list', 'RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@fetchMenu')->name("org_menu_list");
      Route::get("/ajax/menu_populate", 'RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@populate')->name("ajax.menu.populate");
       Route::get('ajax/editMenu/{id}/edit', 'RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@editMenu');
      Route::post('ajax/updateMenu','RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@updateMenu')->name('updateMenu');
      Route::post('ajax/deleteMenu/{menu_id}/delete','RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@deleteMenu');
      Route::post('GetComponent', 'RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@GetComponent')->name('GetComponent'); 
        Route::post('/menuStatus/{id}', 'RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@changeMenuStatus');
 });
});
/*
// Authenticated user only routes
Route::middleware(["auth","roleAccess", "web"])->group(function () {
    Route::prefix('org')->group(function () {
        //User Role CRUD
        //Route::get("/org-user-roles", "RCare\Org\OrgPackages\Roles\src\Http\Controllers\UserRolesController@index")->name("org_user_roles");
        Route::get("/org-menus", "RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@index")->name("org_menus");
        
   });
 }); 

 Route::middleware(["auth", "web"])->group(function () {
  Route::prefix('org')->group(function () {
    Route::post('/create-menu', 'RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@createMenu')->name('create_menu'); 
        Route::post("/ajax/menu_save", "RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@createMenu")->name("ajax.menu.save");
        Route::get('/org-menu-list', 'RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@fetchMenu')->name("org_menu_list");
        Route::get("/ajax/menu_populate", 'RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@populate')->name("ajax.menu.populate");
         Route::get('ajax/editMenu/{id}/edit', 'RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@editMenu');
        Route::post('ajax/updateMenu','RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@updateMenu')->name('updateMenu');
        Route::post('ajax/deleteMenu/{menu_id}/delete','RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@deleteMenu');
        Route::post('GetComponent', 'RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@GetComponent')->name('GetComponent'); 
          Route::post('/menuStatus/{id}', 'RCare\Org\OrgPackages\Menus\src\Http\Controllers\MenuController@changeMenuStatus');

  });
}); */
