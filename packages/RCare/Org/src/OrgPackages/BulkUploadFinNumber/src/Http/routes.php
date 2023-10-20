<?php

/*
|--------------------------------------------------------------------------
| RCare / Org/ OrgPackages / Bulk Upload fin number Routes
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
			Route::get('/bulkupload-fin-number', 'RCare\Org\OrgPackages\BulkUploadFinNumber\src\Http\Controllers\FinNumberBulkUploadController@index')->name('fin_number');
		}); 	
            Route::post('/upload-fin-number','RCare\Org\OrgPackages\BulkUploadFinNumber\src\Http\Controllers\FinNumberBulkUploadController@fileUpload')->name('fileUpload');
            
	});
    
});
