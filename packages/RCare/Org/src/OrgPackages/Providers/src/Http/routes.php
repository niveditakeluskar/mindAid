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

// Authenticated user only routes
Route::middleware(["auth","roleAccess", "web"])->group(function () {
    //Auth::routes();
    Route::prefix('org')->group(function () {
        //User Role CRUD
        Route::get("/org-provider", function(){
                return view('Providers::provider-main');
            })->name("org_provider");  
    });
});


Route::middleware(["auth", "web"])->group(function () {
    //Auth::routes();
    Route::prefix('org')->group(function () {
        Route::post("/provider/subtypeProviders", "RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@getsubProviders")->name("provider_subtypeProviders");
        Route::get("/org-providers-list", "RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@providerList")->name("org_providers_list");
        Route::post("create-org-provider", "RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@createprovider")->name("create_org_provider");
        Route::post('update-org-provider','RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@updateprovider')->name('update_org_provider');
        Route::get('/changeProviderstatus/{id}', 'RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@changeProviderstatus');
        // Route::get('ajax/editProviders/{id}/edit', 'RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@editProviders');
        // Route::post('/ajax/rCare/deleteRole/{id}/delete','RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@deleteRole');
        Route::get('/ajax/provider_populate/{id}/providerpopulate','RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@providerpopulate')->name('ajax.provider.populate');
        // provider type
        Route::get("/org-providers-type-list", "RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@providerTypeList")->name("org_providers_type_list");
        Route::post("create-org-providertype", "RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@createprovidertype")->name("create_org_providertype");
        Route::post('update-org-providertype','RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@updateprovidertype')->name('update_org_providertype');
        Route::get('/ajax/provider_type_populate/{id}/providertypepopulate','RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@providertypepopulate')->name('ajax.providertype.populate');
        Route::get('/changeProvidertypestatus/{id}', 'RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@changeProvidertypestatus');
        //Provider Sub type
        Route::get("/org-providers-subtype-list", "RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@providerSubTypeList")->name("org_providers_subtype_list");
        Route::post("create-org-providersubtype", "RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@createprovidersubtype")->name("create_org_provider_subtype");
        Route::post('update-org-providersubtype','RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@updateprovidersubtype')->name('update_org_provider_subtype');
        Route::get('/ajax/provider_subtype_populate/{id}/providersubtypepopulate','RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@providersubtypepopulate')->name('ajax.providersubtype.populate');
        Route::get('/changeProvidersubtypestatus/{id}', 'RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@changeProvidersubtypestatus');

         Route::post("create-org-providerspeciality", "RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@createproviderspeciality")->name("create_org_providerspeciality");

         Route::post('update-org-providerspeciality','RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@updateproviderspeciality')->name('update_org_providerspeciality');

          Route::get("/org-providers-speciality-list", "RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@SpecialistList")->name("org_providers_speciality_list");

         Route::get('/ajax/provider_speciality_populate/{id}/specialitypopulate','RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@specialitypopulate')->name('ajax.speciality.populate');

          Route::get('/changeSpecialitystatus/{id}', 'RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@changeSpecialitystatus');

           Route::get('ajax/provider/list/{practice_id}', 'RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@getActivePracticeProvidersList')->name("ajax.provider.list");


        //    Route::get('ajax/provider/list/{practice_id}', 'RCare\Org\OrgPackages\Providers\src\Http\Controllers\ProvidersController@getActivePracticeProvidersList')->name("ajax.provider.list");
    });
});