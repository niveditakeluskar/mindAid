       <?php

/*
|--------------------------------------------------------------------------
| RCare / RCareAdmin / AdminPackages / Services Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authenticated user only routes
Route::middleware(["auth", "web"])->group(function () {
    //services
	Route::prefix('admin')->group(function () {
        Route::get("/assign-services", "RCare\RCareAdmin\AdminPackages\AccessMetrics\src\Http\Controllers\AccessMetricesController@index")->name("assign-services");
        Route::get("/usersRolesAssign", "RCare\RCareAdmin\AdminPackages\AccessMetrics\src\Http\Controllers\AccessMetricesController@usersRolesAssign")->name("usersRolesAssign");
        Route::post("AssignRoles", "RCare\RCareAdmin\AdminPackages\AccessMetrics\src\Http\Controllers\AccessMetricesController@AssignRoles")->name('AssignRoles');
    });
});