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
            Route::get("/org-module", function(){
                return view('Modules::module-list'); 
            })->name("org_module");
            Route::get("/org-component", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@componentindex")->name("org_component");
            Route::get("/assign-module", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@AccessMatrix")->name("assign_module");
        });
        
        Route::get("/ajax/modules/{name}/moduleId", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@getModuleId")->name("get.moduleId");
        
        Route::post("/add-org-module", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@AddModule")->name("add_module");
        Route::post("/add-org-component", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@AddComponent")->name("add_component");

        Route::get("/module-list", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@moduleList")->name("module_list");
        Route::get("/component-list", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@componentList")->name("component_list");
    
    Route::get('ajax/editModule/{id}/edit', 'RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@editModule');
    Route::post('update-module','RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@updateModule')->name('update_module');

    Route::get('ajax/editComponent/{id}/edit', 'RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@editComponent');
    Route::post('update-component','RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@updateComponents')->name('update_component');

    Route::post('/changeModuleStatus/{id}', 'RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@changeModuleStatus');
    Route::post('/changeComponentStatus/{id}', 'RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@changeComponentStatus'); 

    Route::post("AssignRolesComponent", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@AssignModule")->name('AssignRolesComponent');
    Route::get("/ajax/sub-module/{id}/sub-module", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@moduleSubModules")->name("ajax.module.submodules");
    

 	});
});
/*
// Authenticated user only routes
Route::middleware(["auth","roleAccess", "web"])->group(function () {
	Route::prefix('org')->group(function () {
    Route::get("/org-module", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@index")->name("org_module");
    Route::get("/org-component", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@componentindex")->name("org_component");
    Route::get("/assign-module", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@AccessMatrix")->name("assign_module");
    });
}); 

Route::middleware(["auth","roleAccess", "web"])->group(function () {
	Route::prefix('org')->group(function () {

    Route::post("/add-org-module", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@AddModule")->name("add_module");
    Route::post("/add-org-component", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@AddComponent")->name("add_component");

    Route::get("/module-list", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@moduleList")->name("module_list");
    Route::get("/component-list", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@componentList")->name("component_list");
    
    Route::get('ajax/editModule/{id}/edit', 'RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@editModule');
    Route::post('update-module','RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@updateModule')->name('update_module');

    Route::get('ajax/editComponent/{id}/edit', 'RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@editComponent');
    Route::post('update-component','RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@updateComponents')->name('update_component');

    Route::post('/changeModuleStatus/{id}', 'RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@changeModuleStatus');
    Route::post('/changeComponentStatus/{id}', 'RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@changeComponentStatus'); 

    Route::post("AssignRolesComponent", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@AssignModule")->name('AssignRolesComponent');
    Route::get("/ajax/sub-module/{id}/sub-module", "RCare\Org\OrgPackages\Modules\src\Http\Controllers\ModuleController@moduleSubModules")->name("ajax.module.submodules");
   
    });
}); */