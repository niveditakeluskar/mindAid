<?php

/*
|--------------------------------------------------------------------------
| RCare / RCareAdmin / AdminPackages / Menu Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::middleware(["auth","roleAccess", "web"])->group(function () {
//     Route::prefix('org')->group(function () {  
//         Route::get("/org-domainfeatures", "RCare\Org\OrgPackages\DomainFeatures\src\Http\Controllers\DomainFeaturesController@index")->name("domainfeatures"); 

//     });
// });
   
Route::middleware(["auth", "web"])->group(function () {
Route::prefix('org')->group(function () {
       Route::get("/org-domainfeatures", "RCare\Org\OrgPackages\DomainFeatures\src\Http\Controllers\DomainFeaturesController@index")->name("domainfeatures"); 

    Route::post('/createDomainFeatures', 'RCare\Org\OrgPackages\DomainFeatures\src\Http\Controllers\DomainFeaturesController@createDomainFeatures')->name('createDomainFeatures'); 

    Route::get('/fetchDomainFeatures', 'RCare\Org\OrgPackages\DomainFeatures\src\Http\Controllers\DomainFeaturesController@fetchDomainFeatures')->name("fetchDomainFeatures"); 
    
   Route::get('editDomainFeatures/{id}/edit', 'RCare\Org\OrgPackages\DomainFeatures\src\Http\Controllers\DomainFeaturesController@editDomainFeatures');

   Route::post('ajax/rCare/changeDomainFeaturesStatus/{id}/update','RCare\Org\OrgPackages\DomainFeatures\src\Http\Controllers\DomainFeaturesController@changeDomainFeaturesStatus');
  
    // Route::post('ajax/rCare/update-menu','RCare\Org\OrgPackages\Menu\src\Http\Controllers\MenuController@updateMenu')->name('update_menu');
    // Route::post('ajax/rCare/deleteMenu/{menu_id}/delete','RCare\Org\OrgPackages\Menu\src\Http\Controllers\MenuController@deleteMenu');
	});


});
