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
                //licensemanagement
                Route::get('/add-license/{id}', 'RCare\RCareAdmin\AdminPackages\License\src\Http\Controllers\LicenseController@addLicense')->name('add_license');
                Route::get('/license', 'RCare\RCareAdmin\AdminPackages\License\src\Http\Controllers\LicenseController@index')->name('license');
                Route::get('/view-license/{id}', 'RCare\RCareAdmin\AdminPackages\License\src\Http\Controllers\LicenseController@reviewLicense')->name('view_license');

                Route::get('/license', 'RCare\RCareAdmin\AdminPackages\License\src\Http\Controllers\LicenseController@index')->name('license');
                Route::POST('/create-license', 'RCare\RCareAdmin\AdminPackages\License\src\Http\Controllers\LicenseController@createLicense')->name('create_license');
                Route::get('/edit-license/{id}', 'RCare\RCareAdmin\AdminPackages\License\src\Http\Controllers\LicenseController@LicenseEdit')->name("edit_license");
                Route::POST('/updateLicense/{id}', 'RCare\RCareAdmin\AdminPackages\License\src\Http\Controllers\LicenseController@updateLicense')->name('updateLicense');

                Route::get('/licenseList/', 'RCare\RCareAdmin\AdminPackages\License\src\Http\Controllers\LicenseController@fetchLicense')->name('licenseList');
                Route::get('/changeLicenseStatus/{org_id}', 'RCare\RCareAdmin\AdminPackages\License\src\Http\Controllers\LicenseController@changeLicenseStatus');
                /* Route::get('view-records','LicenseController@fetchLicenseall');*/
                Route::POST('dynamicLicense','RCare\RCareAdmin\AdminPackages\License\src\Http\Controllers\LicenseController@dynamicLicense')->name('dynamicLicense');
       });
});