<?php

/*
|--------------------------------------------------------------------------
| RCare / RCareAdmin / AdminPackages / User Roles Routes
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
            Route::get("/org-practice", function(){
                return view('Practices::practice-main');
            })->name("org_practice");   
        });
        Route::post("/subtypeProviders", "RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@getsubProviders")->name("subtypeProviders");
        Route::get("/org-practices-list", "RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@PracticeList")->name("org_practices_list");
        Route::get("/org-practices-group-list", "RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@PracticeGroupList")->name("org_practices_group_list");
        Route::post("/create-org-practice", "RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@addpractice")->name("create_org_practice");
        Route::post("/create-org-practice-group", "RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@addPracticeGroup")->name("create_org_practice_group");
        // Route::post('editPractices', 'RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@editPractices')->name("editPractices");
        
        Route::get('/changePracticeStatus/{id}', 'RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@changePracticeStatus');
        Route::get('/changePracticeGrpStatus/{id}', 'RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@changePracticeGrpStatus');
        
        Route::post("/create-threshold-practice", "RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@addpracticethreshold")->name("create_threshold_practice");
        Route::post("/create-threshold-org", "RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@addorgthreshold")->name("create_threshold_org");
    
        // Route::post('update-org-practice','RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@updatePractice')->name('update_org_practice');
        // Route::post('/ajax/rCare/deleteRole/{id}/delete','RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@deleteRole');
        // Route::Post('/ajax/rCare/deleteProviderName/{id}/deletprovidername','RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@deleteProviderName');
        
        Route::get('ajax/practice_populate/{id}/populate', 'RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@populate')->name("ajax.practice.populate");
        Route::get('ajax/practice_group_populate/{id}/grp_prac_populate', 'RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@populategrppractice')->name("ajax.practice.populate");
        Route::get('ajax/practice_threshold_populate/{id}/populate', 'RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@populatepracticethreshold')->name("ajax.threshold.populate");
        Route::get('ajax/org_threshold_populate/{id}/populate', 'RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@populateorgthreshold')->name("ajax.threshold.populate");

        Route::post('/ajax/uploadImage','RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@AddPracticeLogo');
        Route::get("/ajax/caremanager/{caremanager}/practice","RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@practice")->name("ajax.caremanager.practice");

        Route::get("/ajax/practicegrp/{practice}/practice", "RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@practicegrpRelatedPractice")->name("ajax.practicegrp.practice");
        
        Route::get('ajax/practice/all-list', 'RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@getActivePracticeList')->name("ajax.provider.list");

        //upload docs
         Route::post('/uploadFile','RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@uploadDocsFile')->name('uploadFile');
         Route::get('/changeDocumentStatus/{id}', 'RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@changeDocumentStatus');

        //Route::post('/uploadFile','RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@uploadDocsFiledata')->name('uploadFile');
         Route::get('ajax/document/list','RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@getActiveOtherDocumentList')->name("ajax.document.list");

        Route::get("/org-docs-list", "RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@DocumentList")->name("org_docs_list");

        Route::get('ajax/org_document_populate/{id}/populate', 'RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@populatedocument')->name("ajax.document.populate");

 	});
}); 
/*
// Authenticated user only routes
Route::middleware(["auth","roleAccess", "web"])->group(function () {
	//Auth::routes();
	Route::prefix('org')->group(function () {
        
        Route::get("/org-practice", "RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@index")->name("org_practice");
        
    });
});

Route::middleware(["auth", "web"])->group(function () {
	//Auth::routes();
	Route::prefix('org')->group(function () {
        Route::post("/subtypeProviders", "RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@getsubProviders")->name("subtypeProviders");
        Route::get("/org-practices-list", "RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@PracticeList")->name("org_practices_list");
        Route::post("/create-org-practice", "RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@addpractice")->name("create_org_practice");
        Route::post('editPractices', 'RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@editPractices')->name("editPractices");
        Route::get('/changePracticeStatus/{id}', 'RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@changePracticeStatus');
        Route::post('update-org-practice','RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@updatePractice')->name('update_org_practice');
        Route::post('/ajax/rCare/deleteRole/{id}/delete','RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@deleteRole');
        Route::Post('/ajax/rCare/deleteProviderName/{id}/deletprovidername','RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@deleteProviderName');
        Route::get('ajax/practice_populate/{id}/populate', 'RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@populate')->name("ajax.practice.populate");
        Route::post('/ajax/uploadImage','RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@AddPracticeLogo');
        Route::get("/ajax/caremanager/{caremanager}/practice", "RCare\Org\OrgPackages\Practices\src\Http\Controllers\PracticesController@practice")->name("ajax.caremanager.practice");
    });
});*/