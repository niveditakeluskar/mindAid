<?php
Route::prefix('org')->group(function () {
    Route::middleware(["auth", "web"])->group(function () {  
        Route::middleware(["roleAccess"])->group(function () {
            Route::get("/responsibility-list", "RCare\Org\OrgPackages\Responsibility\src\Http\Controllers\ResponsibilityController@responsibilityList")->name("responsibility_list");
        });
        Route::get("/responsibility-list", "RCare\Org\OrgPackages\Responsibility\src\Http\Controllers\ResponsibilityController@responsibilityList")->name("responsibility_list");        
        Route::post('/ajax/submitResponsibility','RCare\Org\OrgPackages\Responsibility\src\Http\Controllers\ResponsibilityController@saveResponsibility')->name("ajax.save.responsibility");
        Route::get('/ajax/populateResponsibilityForm/{id}','RCare\Org\OrgPackages\Responsibility\src\Http\Controllers\ResponsibilityController@populateResponsibilityData')->name("ajax.populate.responsibility.data");
        Route::post('/delete-responsibility/{id}', 'RCare\Org\OrgPackages\Responsibility\src\Http\Controllers\ResponsibilityController@deleteResponsibility')->name('delete.responsibility'); 
 	});
});
?>