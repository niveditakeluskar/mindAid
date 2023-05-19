<?php

/*
|--------------------------------------------------------------------------
| RCare / RCareAdmin / AdminPackages / Role Routes
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
    //Role CRUD
    Route::get("/roles", "RCare\RCareAdmin\AdminPackages\Role\src\Http\Controllers\RoleController@index")->name("roles");
    Route::post("/UsersrolesCreate", "RCare\RCareAdmin\AdminPackages\Role\src\Http\Controllers\RoleController@createUsersRoles")->name("UsersrolesCreate");
    Route::get("/usersRolesList", "RCare\RCareAdmin\AdminPackages\Role\src\Http\Controllers\RoleController@usersRolesList")->name("usersRolesList");
    Route::get('/ajax/rCare/edituserRoles/{id}/edit', 'RCare\RCareAdmin\AdminPackages\Role\src\Http\Controllers\RoleController@editRoles');
    Route::get('/changeRoleStatus/{id}', 'RCare\RCareAdmin\AdminPackages\Role\src\Http\Controllers\RoleController@changeRoleStatus');
    Route::post('updateUserRole','RCare\RCareAdmin\AdminPackages\Role\src\Http\Controllers\RoleController@updateUserRole')->name('updateUserRole');
    Route::post('/ajax/rCare/deleteRole/{id}/delete','RCare\RCareAdmin\AdminPackages\Role\src\Http\Controllers\RoleController@deleteRole');
		});
});