<?php
Route::prefix('org')->group(function () {
    Route::middleware(["auth", "web"])->group(function () { 
        Route::middleware(["roleAccess"])->group(function () {
           Route::get("/office-list", "RCare\Org\OrgPackages\Offices\src\Http\Controllers\OfficeController@officeList")->name("office_list");
        }); 
        Route::post('/ajax/submitOffice','RCare\Org\OrgPackages\Offices\src\Http\Controllers\OfficeController@saveOffice')->name("ajax.save.office");
        Route::get('/ajax/populateOfficeForm/{id}','RCare\Org\OrgPackages\Offices\src\Http\Controllers\OfficeController@populateOfficeData')->name("ajax.populate.office.data");
        Route::post('/delete-office/{id}', 'RCare\Org\OrgPackages\Offices\src\Http\Controllers\OfficeController@deleteOffice')->name('delete.office'); 
 	});
});
?>