<?php

/*
|--------------------------------------------------------------------------
| RCare / Org/ OrgPackages / Users Routes
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
			Route::get("/org-user", function(){
                return view('Users::user-list');
            })->name("org_user");
		});
		// Route::POST('/create-users', 'RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@createRcareusers')->name('create_Users'); 	
		Route::post('/ajax/UseruploadImage','RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@userProfileimage');
		Route::get("/org-users", "RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@index")->name("org_user");
		Route::get("/org-user-list", "RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@usersList")->name("org_users_list");
        Route::get("/ajax/user_populate", 'RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@populate')->name("ajax.user.populate");
		Route::post('/ajax/updateUser','RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@updateUser')->name('update_User');
		Route::post('/changeUserStatus/{id}', 'RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@changeUserStatus');
		Route::post('/updateUserlevelMfa', 'RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@updateUserlevelMfa')->name("user.mfa.update");
		Route::post('/changeBlockUnblockStatus/{id}', 'RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@changeBlockUnblockStatus');
		Route::get("/ajax/report-to", 'RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@reportTo')->name("ajax.user.reportto");
		Route::post("/ajax/user/update-details", "RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@updateUsers")->name("ajax.user.details.update");
		Route::post("/ajax/user/update-password", "RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@updateUserPassword")->name("ajax.user.password.update");
		Route::get("/ajax/linked-practices/{id}", "RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@linkedUserPractices")->name("ajax.user.linked.practices");
		Route::post("/ajax/link-practices", "RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@linkUserPractices")->name("ajax.user.link.practices");
		Route::get("/com", "RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@viewcom")->name("com");
		Route::post("SaveComp", "RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@SaveComp")->name("SaveComp");
		Route::get('/ajax/login-as-developer/{id}', 'RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@LoginAsDeveloper')->name('create_Users');
		
		Route::get('/ajax/theme-dark', 'RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@DarkTheme')->name('dark-mode'); 
		
	});
	Route::POST('/create-users', 'RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@createRcareusers')->name('create_Users'); 
});
/*
// Authenticated user only routes
Route::middleware(["auth","roleAccess", "web"])->group(function () {
	Route::prefix('org')->group(function () {
		//Users CRUD
		Route::get("/org-users", "RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@index")->name("org_user");
		
	});
}); 
Route::middleware(["auth", "web"])->group(function () {
	Route::prefix('org')->group(function () {
		Route::POST('/create-users', 'RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@createRcareusers')->name('create_Users'); 	
		Route::post('/ajax/UseruploadImage','RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@userProfileimage');
		Route::get("/org-users", "RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@index")->name("org_user");
		Route::get("/org-user-list", "RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@usersList")->name("org_users_list");
        Route::get("/ajax/user_populate", 'RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@populate')->name("ajax.user.populate");
		Route::post('/ajax/updateUser','RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@updateUser')->name('update_User');
		Route::post('/changeUserStatus/{id}', 'RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@changeUserStatus');
		Route::get("/ajax/report-to", 'RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@reportTo')->name("ajax.user.reportto");
		Route::post("/ajax/user/update-details", "RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@updateUsers")->name("ajax.user.details.update");
		Route::post("/ajax/user/update-password", "RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@updateUserPassword")->name("ajax.user.password.update");
		Route::get("/ajax/linked-practices/{id}", "RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@linkedUserPractices")->name("ajax.user.linked.practices");
		Route::post("/ajax/link-practices", "RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@linkUserPractices")->name("ajax.user.link.practices");
	});
}); */