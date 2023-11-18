<?php

/*
|--------------------------------------------------------------------------
| RCare / Core Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//logout
Route::get("/logout", "RCare\System\Http\Controllers\Auth\LoginController@logout")->name("logout");
Route::middleware("web")->group(function () {

	Route::get("/", function () {
	    // if(Auth::check()) {
	    //     return redirect('/dashboard');
	    // } else {
	        return redirect('rcare-login');
	    // }
	})->name("rcare-login");
   
   //Route::view('rcare-login', 'System::auth.login')->name('rcare-login');
    
	route::get("access-denied", function(){
            return view("Theme::access-denied");
    }); 
    
    route::get("login-access-denied", function(){
        return view("System::login-access-denied");
    });

    route::get("login-otp", function(){
        return view("System::login-otp");
    });
	
	//check MFA Status
    Route::get("/system/get-mfa-status/{msg_id}/mfa-msg-status", "RCare\System\Http\Controllers\Auth\LoginController@checkMfaTextstatus")->name("mfa-msg-status");
	
    Route::post('/login-otp/2fapost',"RCare\System\Http\Controllers\Auth\LoginController@otpVerify")->name('2fapost');
    Route::post('/login-otp/resend',"RCare\System\Http\Controllers\Auth\LoginController@resend")->name('resend');
    Route::post('/login-otp/resend-another-method',"RCare\System\Http\Controllers\Auth\LoginController@resendAnotherMethod")->name('resend-another-method');


    Route::get("rcare-login", "RCare\System\Http\Controllers\Auth\LoginController@index")->name("rcare-login");
    Route::post("/rcare-login", "RCare\System\Http\Controllers\Auth\LoginController@login");
    //ask otp code on login screen
    Route::post("/rcare-login-verification", "RCare\System\Http\Controllers\Auth\LoginController@loginVerify");
    Route::post("/rcare-login-with-otp", "RCare\System\Http\Controllers\Auth\LoginController@loginWithOtp");
    
    Route::post("/rcare-login-without-otp", "RCare\System\Http\Controllers\Auth\LoginController@loginWithoutOtp");
    
    // Password reset link request routes... priyasingh95161@gmail.com
    Route::get('password_requestform', 'RCare\System\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password_requestform');
    Route::post('resetpassword_otp', 'RCare\System\Http\Controllers\Auth\ForgotPasswordController@AuthenticatUser')->name('resetpassword_otp');
    Route::post('password_mail', 'RCare\System\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password_mail');
    Route::get('password/reset', function(){
        return view('System::passwords.reset');
    })->name('password.request');
    // Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.request');
    Route::post('/password_update', 'RCare\System\Http\Controllers\Auth\ResetPasswordController@updatePassword')->name('password_update');
    
    Route::middleware("auth")->group(function () {
        
        //manually record time
        Route::post("/system/log-time/time", "RCare\System\Http\Controllers\CommonFunctionController@recordTimeSpentManually")->name("manually-log-time");
        Route::get("/system/get-updated-time/{patientID}/{billable}/{moduleId}/total-time", "RCare\System\Http\Controllers\CommonFunctionController@recordUpdatedTime")->name("update-log-time");
        Route::get("/system/get-total-billable-nonbillable-time/{patientID}/{moduleId}/total-time", "RCare\System\Http\Controllers\CommonFunctionController@getTotalBillableAndNonBillableTime")->name("total-billable-non-billable-time");
        Route::get("/system/get-finalize-date/{patientID}/{moduleId}/finalize-date", "RCare\System\Http\Controllers\CommonFunctionController@finalizeDate")->name("finalize-date");
        Route::get("/system/get-session-logout-time-with-popup-time", "RCare\System\Http\Controllers\CommonFunctionController@getSessionLogoutTimeWithPopupTime")->name("session-logout-time-with-popup-time");

        Route::get("/system/get-total-time/{patientID}/{moduleId}/{startTime}/total-time", "RCare\System\Http\Controllers\CommonFunctionController@getTotalTime")->name("total-time");
        Route::get("/system/get-landing-time", "RCare\System\Http\Controllers\CommonFunctionController@getLandingTime")->name("landing-time");
       
        Route::post("/system/log-timer-button-action/time", "RCare\System\Http\Controllers\CommonFunctionController@logTimerButtonAction")->name("log-timer-button-action"); //23Jan2022
        Route::middleware("roleAccess")->group(function () {
            Route::get("task-management/work-list", "RCare\TaskManagement\Http\Controllers\PatientAllocationController@getUserListData")->name("work.list");
            Route::get("admin/dashboard", "RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@dashboard")->name("dashboard"); 
            //RenLogin
            Route::get("/org-user-list", "RCare\Org\OrgPackages\Users\src\Http\Controllers\UserController@usersList")->name("org_users_list");

            Route::post("/print-table", "RCare\System\Http\Controllers\TablePrintingController@index")->name("print-table");

            route::get("org/org-tcm", function(){
                return view("Theme::under-construction");
            });

            route::get("org/org-tcm", function(){
                return view("Theme::under-construction");
            });

            route::get("org/org-ccm", function(){
                return view("Theme::under-construction");
            });

            route::get("awv", function(){
                return view("Theme::under-construction");
            });

            route::get("Administrator", function(){
                return view("Theme::under-construction");
            });
            
            route::get("script-readouts", function(){
                return view("Theme::under-construction");
            });
            

            route::get("org/org-rpm", function(){
                return view("Theme::under-construction");
            });

            route::get("rcareorgs", function(){
                return view("Theme::under-construction");
            });

            route::get("rcareorgs", function(){
                return view("Theme::under-construction");
            });
        });
    });
});