<?php

/*
|--------------------------------------------------------------------------
| RCare / Task Management Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| 
*/
Route::middleware(["auth", "web"])->group(function () {
    Route::prefix('task-management')->group(function () { 
        //to-do-list 
        Route::get("/patient-to-do/{patientId}/{moduleId}/list", "RCare\TaskManagement\Http\Controllers\TaskManagementController@getToDoListData")->name("ajax.to.do.list");

        //Patient-Allocation
        Route::get('/patients', 'RCare\TaskManagement\Http\Controllers\PatientAllocationController@listPatients')->name('patients');
        Route::get("/patient-list", "RCare\TaskManagement\Http\Controllers\PatientAllocationController@getPatientListData")->name("patient.list");
        
        // Route::get("/work-list", "RCare\TaskManagement\Http\Controllers\PatientAllocationController@getUserListData")->name("work.list");
        // Route::get("/work-listall/{practice_id}/{patient_id}/{module_id}/{timeoption}/{time}/{activedeactivestatus}", "RCare\TaskManagement\Http\Controllers\PatientAllocationController@getUserListDataAll")->name("work.list.all");          
        // // added by priya 24/11/2020
        // Route::post("/patient-activity","RCare\TaskManagement\Http\Controllers\PatientAllocationController@savePatientActivity")->name("save.patient.activity");
         Route::get("/activity_time/{id}/{practice_id}","RCare\TaskManagement\Http\Controllers\PatientAllocationController@get_activitytime")->name("get.activitytime");

        Route::get('/patients-search/{practice_id}/{caremanager_id}/patient_search', 'RCare\TaskManagement\Http\Controllers\PatientAllocationController@listPatientsSearch')->name('patients.search');
	    Route::get('/patient-details/{id}', 'RCare\TaskManagement\Http\Controllers\PatientAllocationController@fetchPatientDetails')->name('patient.details');
        Route::get('/ajax/caremanager/{practiceId}/caremanagerlist', 'RCare\TaskManagement\Http\Controllers\PatientAllocationController@caremanagerlist')->name("ajax.caremanager.list");
        Route::get('/ajax/practice/{practice}/{caremanagerId}/patient', 'RCare\TaskManagement\Http\Controllers\PatientAllocationController@practicePatients')->name("ajax.practice.patient");
        Route::post('/task-management-user-form', 'RCare\TaskManagement\Http\Controllers\PatientAllocationController@SavePatientUser')->name('task.management.user');

        Route::get('/patients-assignment/nonassignedpatients/{practice}', 'RCare\TaskManagement\Http\Controllers\TaskManagementController@Nonassignedpatients')->name('patients.nonassigned');
        Route::get('/patients-assignment/assignedpatients/{practice}', 'RCare\TaskManagement\Http\Controllers\TaskManagementController@Assignedpatientstable')->name('patients.assigned');
        Route::get('/patients-assignment/cmlist/{practice}', 'RCare\TaskManagement\Http\Controllers\TaskManagementController@Cmlist')->name('cmlist');
        Route::get('/patients-assignment/allpatients/{practice}', 'RCare\TaskManagement\Http\Controllers\TaskManagementController@Allpatientstable')->name('allpatients');

        Route::get('/getpatients-assignment/totalpatients', function(){//Updated by -pranali on 22Oct2020
            return view('TaskManagement::components.gettotalpatients'); 
        })->name('patients.assignment.totalpatients');
        Route::get('/getpatients-assignment/totalcaremanager', function(){//Updated by -pranali on 22Oct2020
            return view('TaskManagement::components.gettotalcaremanagers');
        })->name('patients.assignment.totalcaremanager');
        Route::get('/getpatients-assignment/totalassignedpatient', function(){//Updated by -pranali on 22Oct2020
            return view('TaskManagement::components.gettotalassignedpatients');
        })->name('patients.assignment.totalassignedpatient');
        Route::get('/getpatients-assignment/totalnonassignedpatient', function(){//Updated by -pranali on 22Oct2020
            return view('TaskManagement::components.gettotalnonassignedpatients');
        })->name('patients.assignment.totalnonassignedpatient');
    });
});