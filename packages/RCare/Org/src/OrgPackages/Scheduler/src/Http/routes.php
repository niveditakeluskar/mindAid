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

    Route::get('/scheduleradddeducttime/{id}', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@addDeductTime')->name('scheduler');
    
    
    Route::get('/scheduler', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@index')->name('scheduler');
    Route::get('/reportScheduler', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@index')->name('scheduler');  

    
    Route::post('/patient-summary', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@getReportSchedulePatientData')->name('patient.summary');
    
    Route::post('/save-scheduler', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@saveScheduler')->name('save.scheduler'); 
    Route::get('/scheduler-list', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@SchedulerList')->name('scheduler.list');

    Route::get('/Reportscheduler-list', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@ReportSchedulerList')->name('Reportscheduler.list'); 

    Route::get('/save-report-scheduler', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@saveReportScheduler')->name('save.report-scheduler'); 
    
    Route::get('/ajax/populateSchedulerForm/{schedulerid}','RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@populateSchedulerData')->name("ajax.populate.scheduler.data");
    Route::get('/ajax/populateActivitySchedulerForm/{activityid}','RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@populateActivityData')->name("ajax.populate.activity.data"); 
    
    Route::post("/update-scheduler", "RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@updateScheduler")->name("update.scheduler"); 
    Route::post('/schedulerStatus/{id}', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@changeSchedulerStatus'); 

    Route::get('/schedulerlog', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@schdulerlogIndex')->name('schedulerlog'); 
    Route::get('/schedulerlog-list', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@SchedulerlogList')->name('schedulerlog.list');   
    Route::get('/schedulerlog-report/search/{activity}/{practicesgrp}/{practices}/{module_id}/{startdate}/{dateofexecution}/{executionstatus}/{operation}', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@SchedulerlogList')->name('schedulerlogsearch.list');

    Route::post('/save-report-scheduler', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@saveReportScheduler')->name('save.report.scheduler'); 
     
    Route::get('/ajax/reportscheduler_populate/{id}/populate', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@populateReportSchedulerData')->name('reportSchedulerpopulate'); 
    
    Route::post('/reportscheduler-samefrequency-check/{id}', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@reportSchedulersameFrequencyCheck')->name('reportscheduler.samefrequency.check'); 
   
  
    Route::post('/reportscheduler-deactive-old/{id}', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@reportschedulerDeactiveOld')->name('reportscheduler.deactive.old'); 

    // Route::post('admin-update-User','RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@updateUser')->name('admin_update_User');

    //  Route::post('admin-update-Name','RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@updateUserName')->name('admin_update_Name');
    // Route::get("/user-list", "RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@fetchUser")->name("user_list");
    // //Route::post('/ajax/rCare/deleteUser/{id}/delete','RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@deletUser');
    // Route::get('change-User-Status/{id}', 'RCare\RCareAdmin\AdminPackages\Users\src\Http\Controllers\UserController@changeUserStatus');

    Route::get('/patients-list/allpatients/{practice}', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@Allpatientstablelist')->name('allpatients');
    Route::get('/patients-list/activepatient/{practice}', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@Activepateintlist')->name('active.patientlist');
    Route::get('/patients-list/assignedtaskpatients/{practice}', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@Assignedpatientstablekist')->name('patients.assignedtask');
    Route::get('/patients-list/nonassignedtaskpatients/{practice}', 'RCare\Org\OrgPackages\Scheduler\src\Http\Controllers\SchedulerController@Nonassignedpatientslist')->name('patients.nonassignedtask');

     Route::get('/getpatients-list/totalpatients', function(){
            return view('Scheduler::component.getpatientlist'); //TaskManagement::components.gettotalpatients
        })->name('reportscheduler.totalpatientslist');
        Route::get('/getpatients-list/totalactivepatient', function(){
            return view('Scheduler::component.getactivepatientlist');
        })->name('reportscheduler.totalactivepatient');
        Route::get('/getpatients-list/totalassignedtaskpatient', function(){
            return view('Scheduler::component.getAssigntaskpatientlist');
        })->name('reportscheduler.totalassignedtaskpatient');
        Route::get('/getpatients-list/totalnonassignedtaskpatient', function(){
            return view('Scheduler::component.getnonasssigntaskpatientlist');
        })->name('reportscheduler.totalnonassignedtaskpatient');
    });
});
