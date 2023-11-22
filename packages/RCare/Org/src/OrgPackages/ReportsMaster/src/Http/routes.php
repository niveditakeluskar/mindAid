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
           //      return view('ReportsMaster::partner_list');
           //  })->name("partner");
        });

         Route::get("/reports-master", function(){
                return view('ReportsMaster::reports_master_list');
            })->name("reports-master");
        Route::get("/reports-list", "RCare\Org\OrgPackages\ReportsMaster\src\Http\Controllers\ReportsMasterController@ReportsMasterList")->name("reports-list");
        Route::post("/create-reports", "RCare\Org\OrgPackages\ReportsMaster\src\Http\Controllers\ReportsMasterController@addReportsMaster")->name("create-reports");
        Route::post('/ReportsStatus/{id}', 'RCare\Org\OrgPackages\ReportsMaster\src\Http\Controllers\ReportsMasterController@changeReportsMasterStatus');
        Route::post('update-partner','RCare\Org\OrgPackages\ReportsMaster\src\Http\Controllers\ReportsMasterController@updateReportsMaster')->name('update_partner');
        Route::get('ajax/reports_populate/{id}/populate', 'RCare\Org\OrgPackages\ReportsMaster\src\Http\Controllers\ReportsMasterController@populateReportsMaster')->name("ajax.reports.populate");
      
 	});
});

