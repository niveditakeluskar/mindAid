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
    Route::prefix('org')->group(function () {
    //Users CRUD
    Route::get('/rpm-vitals-protocol',function(){
          return view('Protocol::rpm-vitals-protocol');
    })->name('rpmvitalsprotocol');
 
     Route::POST('save-file-upload', 'RCare\Org\OrgPackages\Protocol\src\Http\Controllers\ProtocolController@fileUploadPost')->name('save.file.upload');

     Route::get("/rpm-vital-document-list", "RCare\Org\OrgPackages\Protocol\src\Http\Controllers\ProtocolController@getVitalsDocumentList")->name("rpm.vital.document.list");

       Route::post('/vitalsprotocolStatus/{id}/{deviceid}', 'RCare\Org\OrgPackages\Protocol\src\Http\Controllers\ProtocolController@changeVitalsProtocolStatus');
   
    });
});