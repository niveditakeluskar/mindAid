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
            Route::get("/deactivation-reasons", function(){
                return view('DeactivationReasons::deactivation-reasons');
            })->name("deactivation-reasons"); 
        });
        Route::post("/add-reasons", "RCare\Org\OrgPackages\DeactivationReasons\src\Http\Controllers\DeactivationReasonsController@AddDeactivationReasons")->name("add-reasons");
        Route::get("/reasons_list","RCare\Org\OrgPackages\DeactivationReasons\src\Http\Controllers\DeactivationReasonsController@getDeactivationReasonsListData")->name("list-reasons");
        Route::get('ajax/DeactivationReasons_populate/{id}/populate', 'RCare\Org\OrgPackages\DeactivationReasons\src\Http\Controllers\DeactivationReasonsController@populateDeactivationReasons')->name("ajax.deactivationReasons.populate");
        Route::post('/delete-DeactivationReasons/{id}', 'RCare\Org\OrgPackages\DeactivationReasons\src\Http\Controllers\DeactivationReasonsController@deleteDeactivationReasons')->name('delete.DeactivationReasons');  

 	});
});



