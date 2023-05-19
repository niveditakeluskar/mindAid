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
    //Auth::routes();
    Route::prefix('org')->group(function () { 
        //User Role CRUD
		Route::middleware(["roleAccess"])->group(function () {
			Route::get("/org-physician", "RCare\Org\OrgPackages\Physicians\src\Http\Controllers\PhysiciansController@index")->name("org_physician");
		});
        
        Route::get("/org-physicians-list", "RCare\Org\OrgPackages\Physicians\src\Http\Controllers\PhysiciansController@physicianList")->name("org_physicians_list");
        Route::post("create-org-physician", "RCare\Org\OrgPackages\Physicians\src\Http\Controllers\PhysiciansController@createphysician")->name("create_org_physician");
        Route::get('ajax/editPhysicians/{id}/edit', 'RCare\Org\OrgPackages\Physicians\src\Http\Controllers\PhysiciansController@editPhysicians');
        Route::get('/changePhysicianstatus/{id}', 'RCare\Org\OrgPackages\Physicians\src\Http\Controllers\PhysiciansController@changePhysicianstatus');
        Route::post('update-org-physician','RCare\Org\OrgPackages\Physicians\src\Http\Controllers\PhysiciansController@updatephysician')->name('update_org_physician');
        Route::post('/ajax/rCare/deleteRole/{id}/delete','RCare\Org\OrgPackages\Physicians\src\Http\Controllers\PhysiciansController@deleteRole');
        Route::get("/ajax/practice/{practice}/physicians", "RCare\Org\OrgPackages\Physicians\src\Http\Controllers\PhysiciansController@physician")->name("ajax.practice.physicians");
        Route::get("/ajax/provider/list/{practiceId}/Pcpphysicians", "RCare\Org\OrgPackages\Physicians\src\Http\Controllers\PhysiciansController@PcpPhysician")->name("ajax.practice.pcpphysicians");
        Route::get("/ajax/practice/{practice}/Providerphysicians", "RCare\Org\OrgPackages\Physicians\src\Http\Controllers\PhysiciansController@ProviderPopulatePhysician")->name("ajax.practice.providerphysicians");
        Route::get("/ajax/practice/provider/{practice}/physicians", "RCare\Org\OrgPackages\Physicians\src\Http\Controllers\PhysiciansController@ProviderPhysician")->name("ajax.practice.provider.providerphysicians");
    
    });
});