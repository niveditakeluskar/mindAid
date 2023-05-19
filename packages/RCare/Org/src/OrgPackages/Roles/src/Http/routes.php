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
            Route::get("/org-user-roles", "RCare\Org\OrgPackages\Roles\src\Http\Controllers\UserRolesController@index")->name("org_user_roles");
        });
        Route::get("/org-role-list", "RCare\Org\OrgPackages\Roles\src\Http\Controllers\UserRolesController@usersRolesList")->name("org_roles_list");
        Route::post("/create-org-role", "RCare\Org\OrgPackages\Roles\src\Http\Controllers\UserRolesController@createUsersRoles")->name("create_org_role");
        Route::get('ajax/edituserRoles/{id}/edit', 'RCare\Org\OrgPackages\Roles\src\Http\Controllers\UserRolesController@editRoles');
        Route::post('/changeRoleStatus/{id}', 'RCare\Org\OrgPackages\Roles\src\Http\Controllers\UserRolesController@changeRoleStatus');
        Route::post('update-org-role','RCare\Org\OrgPackages\Roles\src\Http\Controllers\UserRolesController@updateUserRole')->name('update_org_role');
        Route::post('/ajax/rCare/deleteRole/{id}/delete','RCare\Org\OrgPackages\Roles\src\Http\Controllers\UserRolesController@deleteRole');
 	});
});

// Authenticated user only routes
//  Route::group( ['middleware' => 'auth' ], function() {
    /*
Route::middleware(["auth","roleAccess", "web"])->group(function () {
	Route::prefix('org')->group(function () {
        //User Role CRUD
        Route::get("/org-user-roles", "RCare\Org\OrgPackages\Roles\src\Http\Controllers\UserRolesController@index")->name("org_user_roles");
        Route::get("/org-role-list", "RCare\Org\OrgPackages\Roles\src\Http\Controllers\UserRolesController@usersRolesList")->name("org_roles_list");
        Route::post("/create-org-role", "RCare\Org\OrgPackages\Roles\src\Http\Controllers\UserRolesController@createUsersRoles")->name("create_org_role");
        Route::get('ajax/edituserRoles/{id}/edit', 'RCare\Org\OrgPackages\Roles\src\Http\Controllers\UserRolesController@editRoles');
        Route::post('/changeRoleStatus/{id}', 'RCare\Org\OrgPackages\Roles\src\Http\Controllers\UserRolesController@changeRoleStatus');
        Route::post('update-org-role','RCare\Org\OrgPackages\Roles\src\Http\Controllers\UserRolesController@updateUserRole')->name('update_org_role');
        Route::post('/ajax/rCare/deleteRole/{id}/delete','RCare\Org\OrgPackages\Roles\src\Http\Controllers\UserRolesController@deleteRole');
    });
});*/