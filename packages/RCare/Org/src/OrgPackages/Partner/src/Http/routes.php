<?php

/*
|--------------------------------------------------------------------------
| RCare / Org / src / OrgPackages / Medication / Routes
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
          
           // Route::get("/partner", function(){
           //      return view('Partner::partner_list');
           //  })->name("partner");
        });

         Route::get("/partner", function(){
                return view('Partner::partner_list');
            })->name("partner");
         
        Route::get("/partner-list", "RCare\Org\OrgPackages\Partner\src\Http\Controllers\PartnerController@PartnerList")->name("partner_list");
        Route::post("/create-partner", "RCare\Org\OrgPackages\Partner\src\Http\Controllers\PartnerController@addPartner")->name("create_partner");
        Route::post('editMedication', 'RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@editMedication')->name("editMedication");
        Route::post('/PartnerStatus/{id}', 'RCare\Org\OrgPackages\Partner\src\Http\Controllers\PartnerController@changePartnerStatus');
        Route::post('update-partner','RCare\Org\OrgPackages\Partner\src\Http\Controllers\PartnerController@updatePartner')->name('update_partner');
        Route::get('ajax/partner_populate/{id}/populate', 'RCare\Org\OrgPackages\Partner\src\Http\Controllers\PartnerController@populatePartner')->name("ajax.partner.populate");
      
 	});
});

