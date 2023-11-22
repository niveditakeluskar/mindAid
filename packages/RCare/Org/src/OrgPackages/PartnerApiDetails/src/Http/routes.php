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

         Route::get("/partnerapidetails", function(){
                return view('PartnerApiDetails::partner_api_details'); 
                })->name("partnerapidetails");

        Route::get("/partnerapi-list", "RCare\Org\OrgPackages\PartnerApiDetails\src\Http\Controllers\PartnerApiDetailsController@PartnerApiList")->name("partner_api_list");

        Route::post("/create-partner-api", "RCare\Org\OrgPackages\PartnerApiDetails\src\Http\Controllers\PartnerApiDetailsController@addPartnerApi")->name("create-partner-api");

        // Route::post('editMedication', 'RCare\Org\OrgPackages\Medication\src\Http\Controllers\MedicationController@editMedication')->name("editMedication");

        Route::post('/PartnerStatus/{id}', 'RCare\Org\OrgPackages\PartnerApiDetails\src\Http\Controllers\PartnerApiDetailsController@changePartnerStatus');

        Route::post('update-partner-api','RCare\Org\OrgPackages\PartnerApiDetails\src\Http\Controllers\PartnerApiDetailsController@updatePartnerApi')->name('update-partner-api');
        
        Route::get('ajax/partner_api_populate/{id}/populate', 'RCare\Org\OrgPackages\PartnerApiDetails\src\Http\Controllers\PartnerApiDetailsController@populatePartnerApi')->name("ajax.partnerapi.populate");
      
 	});
});

