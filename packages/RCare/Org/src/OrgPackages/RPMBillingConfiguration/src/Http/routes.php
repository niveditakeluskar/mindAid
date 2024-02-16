<?php
Route::prefix('org')->group(function () {
    Route::middleware(["auth", "web"])->group(function () { 
        Route::middleware(["roleAccess"])->group(function () {
            // Route::get("/rpm-billing-configuration",function(){ return view('RPMBillingConfiguration::rpmbilling.rpmbilling');  })->name('rpm-billing-configuration');  
            Route::get("/rpm-billing-configuration", function(){
                return view('RPMBillingConfiguration::rpmbilling.rpmbilling');
            })->name("rpm.billing.configuration");  
        });    
        Route::post("/create_rpm_billing", "RCare\Org\OrgPackages\RPMBillingConfiguration\src\Http\Controllers\RPMBillingController@saveRPMBillingConfiguration")->name("create_rpm_billing");  
        Route::get('ajax/rpm_billing_populate/populate', 'RCare\Org\OrgPackages\RPMBillingConfiguration\src\Http\Controllers\RPMBillingController@populateRPMBillingConfiguration')->name("ajax.rpmbilling.populate");
 	});
});
?>