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
                Route::post('/ajax_upload/action', 'OrgController@action')->name('ajaxupload.action');
                Route::get('/rcareorgs', 'RCare\RCareAdmin\AdminPackages\Organization\src\Http\Controllers\OrgController@index')->name('rcareorgs'); 
                Route::POST('/create-org', 'RCare\RCareAdmin\AdminPackages\Organization\src\Http\Controllers\OrgController@createOrgs')->name('create_org');
                Route::get('/addorg', 'RCare\RCareAdmin\AdminPackages\Organization\src\Http\Controllers\OrgController@addOrg')->name('addorg'); 
                Route::get("/org-list", "RCare\RCareAdmin\AdminPackages\Organization\src\Http\Controllers\OrgController@fetchRcareOrgs")->name("org_list_data");
                //for id 
                Route::get('/edit/{id}', 'RCare\RCareAdmin\AdminPackages\Organization\src\Http\Controllers\OrgController@rcareOrgsedit')->name("edit");
                Route::POST('/edit-org/{id}','RCare\RCareAdmin\AdminPackages\Organization\src\Http\Controllers\OrgController@updateRcareOrg')->name('edit_org');
                Route::get('/changeOrgStatus/{id}', 'RCare\RCareAdmin\AdminPackages\Organization\src\Http\Controllers\OrgController@changeOrgStatus');
	});
});       