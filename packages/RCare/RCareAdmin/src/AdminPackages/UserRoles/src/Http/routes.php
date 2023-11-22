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
Route::middleware(["auth", "web"])->group(function () {
// Route::middleware(["web"])->group(function () {

Route::prefix('admin')->group(function () {

    //User Role CRUD
    Route::get("/roles", "RCare\RCareAdmin\AdminPackages\UserRoles\src\Http\Controllers\UserRolesController@index")->name("roles");
    Route::post("/ajax/role_save", "RCare\RCareAdmin\AdminPackages\UserRoles\src\Http\Controllers\UserRolesController@createUsersRoles")->name("ajax.role.save");
    Route::get("/ajax/role_populate", 'RCare\RCareAdmin\AdminPackages\UserRoles\src\Http\Controllers\UserRolesController@populate')->name("ajax.role.populate");
    Route::post('/update-user-role','RCare\RCareAdmin\AdminPackages\UserRoles\src\Http\Controllers\UserRolesController@updateUserRole')->name('update_user_role');


    // Route::get('/ajax/rCare/edituserRoles/{id}/edit', 'RCare\RCareAdmin\AdminPackages\UserRoles\src\Http\Controllers\UserRolesController@editRoles');
    Route::get("/user-role-list", "RCare\RCareAdmin\AdminPackages\UserRoles\src\Http\Controllers\UserRolesController@usersRolesList")->name("users_roles_list");
    Route::get('/changeRoleStatus/{id}', 'RCare\RCareAdmin\AdminPackages\UserRoles\src\Http\Controllers\UserRolesController@changeRoleStatus');
    Route::post('/ajax/rCare/deleteRole/{id}/delete','RCare\RCareAdmin\AdminPackages\UserRoles\src\Http\Controllers\UserRolesController@deleteRole');

});
});