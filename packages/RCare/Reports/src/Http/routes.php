 <?php

/*
|--------------------------------------------------------------------------
| RCare / Reports Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::prefix('reports')->group(function () { 

Route::get('/time-logs-report', function(){
                return view('Reports::time-logs-report.time-logs-list');
            })->name('time.logs.report');

    Route::get('/time-logs-report/{patient}/{practiceid}/{emr}/{caremanagerid}/{module}/{sub_module}/{fromdate}/{todate}/{activedeactivestatus}', 'RCare\Reports\Http\Controllers\TimeLogsReportController@timeLogsReportSearch')
    ->name('time.logs.report.search');
    
    Route::get('/task-status-report', function(){
        return view('Reports::task-status-report.task-status-list');
    })->name('task.status.report');

    
    Route::get('/rpm-enrolled-patient-report', function(){
        return view('Reports::rpm-enrolled-report.rpm-enrolled-report');
    })->name('rpm.enrolled.patient.report');
    

        
    Route::middleware(["auth", "web"])->group(function () { 
        Route::middleware(["roleAccess"])->group(function () {
            // Route::get('/daily_reports','RCare\Reports\Http\Controllers\ReportController@index')->name('daily.reports');
            // Route::post('reports','RCare\Reports\Http\Controllers\ReportController@index');

             
            // Route::get('/to-do-list-report', function(){
            //     return view('Reports::to-do-list-report.to-do-list-report');
            // })->name('to-do-list-report');
			//call additional services ashwini

            Route::get('/rpm-enrolled-patient-report', function(){
                return view('Reports::rpm-enrolled-report.rpm-enrolled-report');
            })->name('rpm.enrolled.patient.report');
            
            Route::get('/call-and-additional-services-practicewise-count-report', function(){
                return view('Reports::call-additional-ccm-note.call-additional-services-report');
            })->name('call.additional.ccm.call.report'); 

			 Route::get('/questionnaire', 'RCare\Reports\Http\Controllers\QuestionaireReportController@QuestionaireReport')->name('Questionaire-report');
			 Route::get('/patient-questionnaire', 'RCare\Reports\Http\Controllers\PatientQuestionaireReportController@PatientQuestionaireReport')->name('Patient-Questionaire-report');
			 
             Route::get('/Clinical-insight', function(){
                return view('Reports::initial-report.initial-report');
            })->name('initial.report'); 

            Route::get('/rpm-billing-report', function(){
                return view('Reports::rpm-billing-report.rpm-billing-report');
            })->name('rpm.billing.report');
			
			Route::get('/patient-vitals-report', function(){
                return view('Reports::patient-vitals-report.patient-vitals-report');
            })->name('patientvital.report');
            
            Route::get('/monthly-account-performance-report', function(){
                return view('Reports::monthly-account-performance-report.monthly-account-performance-report');
            })->name('monthly.account.performance.report');

            Route::get('/care-manager-daily-productivity-report', function(){
                return view('Reports::caremanager-daily-productivity-report.caremanager-daily-productivity-report');
            })->name('caremanager.daily.productivity.report');

            Route::get('/manually-adjust-timer-report', function(){
                return view('Reports::manual-adjust-timer-report.manually-adjust-timer-report-list');
            })->name('manually.adjust.timer.report');

            Route::get('/cpd-report', function(){
                return view('Reports::cpd-report');
            })->name('cpd.report');

            Route::get('/login-logs-report', function(){
                return view('Reports::login-report');
            })->name('login.report');

            Route::get('/care-manager-monthly-report', 'RCare\Reports\Http\Controllers\CareManagerMonthlyReportController@listCareManagerMonthlyReportPatients')->name('care.manager.monthly.report');
            //todolist--ash
            Route::get('/task-management', 'RCare\Reports\Http\Controllers\ToDoListReportController@todolistReport')->name('to-do-list-report');
            //Route::post('/task-management-to-do-list', 'RCare\Reports\Http\Controllers\ToDoListReportController@SaveToDoList')->name('task.management.todolist');
			
			Route::get('/verify-code', 'RCare\Reports\Http\Controllers\Verifyicd10CodeReportController@verifycodeReport')->name('verify-code-report');

            
			Route::get("/callactivityServicelist", "RCare\Reports\Http\Controllers\CallActivityServicesReportController@callActivityServiceListsReport")->name("callactivityServicelist"); 

            Route::get('/daily-report', 'RCare\Reports\Http\Controllers\DailyBillableReportController@PatientDailyReport')->name('daily.report'); 
            Route::get('/monthly-billing-report', 'RCare\Reports\Http\Controllers\MonthlyBillableReportController@PatientMonthlyBillingReport')->name('monthly.billing.report'); 
            Route::get('/enrollment-tracking-report', 'RCare\Reports\Http\Controllers\EnrollmentTrackingReportController@PatientEnrollReport')->name('enrollment.tracking.report');               
            // enrollment-tracking-report/group
            Route::get('/enrollment-tracking-report/practicegroup', 'RCare\Reports\Http\Controllers\EnrollmentTrackingReportController@PracticeGroupEnrollmentTrackingList')->name('enrollment.tracking.report.practicegroup'); 
            Route::get('/care-manager-report', 'RCare\Reports\Http\Controllers\CMBillingStatusReport@CareManagerReport')->name('care.manager.report');
            Route::get('/enrollment-report', function(){
                return view('Reports::enrollment-report.enrollment-report');
            })->name('enrollment.report');
            Route::get('/provider-performance-report', 'RCare\Reports\Http\Controllers\ProviderPerformanceController@assignedPatients')->name('patients.assignment');

            Route::get('/device-data-report', function(){
                return view('Reports::device-data-reports.device-data-report');
            })->name('device.data.report');

            //call-status-report  7th sept 2022
            Route::get('/call-status-report', 'RCare\Reports\Http\Controllers\CallStatusReportController@PatientCallStatusReport')->name('call.status.report');

            //rpm-status-report 20th march 2023
            Route::get('/rpm-daily-status-report', 'RCare\Reports\Http\Controllers\RpmStatusReportController@PatientRpmStatusReport')->name('rpm.daily.status.report');
        });
        
        // //TaskStatusReport
        // Route::get('/task-status-report-search/{caremanagerid}/{practicesgrp}/{practiceid}/{patient}/{taskstatus}
        // /{fromdate}/{activedeactivestatus}',
        // 'RCare\Reports\Http\Controllers\TaskStatusReportController@TaskStatusReportSearch')->name('task.status.search.report'); 
        Route::post('/shipiing-save', 'RCare\Reports\Http\Controllers\RpmEnrolledReportController@shippingdetailssave')->name('ajax.save.shipping'); 
        Route::post('/shipiing-device', 'RCare\Reports\Http\Controllers\RpmEnrolledReportController@devicedetailssave')->name('ajax.save.device');  
        Route::post('/shipping-statuschange', 'RCare\Reports\Http\Controllers\RpmEnrolledReportController@changeStatusShipping')->name('save.status');  
        // Route::get('ajax/shippingreports_populate/{id}/populate', 'RCare\Reports\Http\Controllers\RpmEnrolledReportController@populateshipping')->name("ajax.shipping.populate");
        Route::get('ajax/shippingreports_populate/{patinet_id}/{device_code}/populate', 'RCare\Reports\Http\Controllers\RpmEnrolledReportController@populateshippingdevicewise')->name("ajax.shipping.populate");
        
        Route::get("/ajax/patientdevice/{patientid}/pateintdevice", "RCare\Reports\Http\Controllers\RpmEnrolledReportController@patientdevicelist")->name("ajax.patient.device");
        
       
        Route::get('/rpmenrolledpatientlist/{practices}/{patient}/{shipping_status}/{fromdate}/{todate}', 'RCare\Reports\Http\Controllers\RpmEnrolledReportController@RpmEnrolledReportSearch')->name('rpm.enrolled.search.report');  
        Route::get('/devicelist-rpmenrolled/{rowid}', 'RCare\Reports\Http\Controllers\RpmEnrolledReportController@getdeviceslist')->name('devices_list');
        Route::get('/shippinglist/{id}/{shipping_status}','RCare\Reports\Http\Controllers\RpmEnrolledReportController@getshippinglist')->name('shipping_list');
        Route::post('/delete-devices/{id}', 'RCare\Reports\Http\Controllers\RpmEnrolledReportController@actiondevice')->name('devices_list');
 


        Route::post('/shipiing-save', 'RCare\Reports\Http\Controllers\RpmEnrolledReportController@shippingdetailssave')->name('ajax.save.shipping'); 
        Route::post('/shipiing-device', 'RCare\Reports\Http\Controllers\RpmEnrolledReportController@devicedetailssave')->name('ajax.save.device');  
        Route::post('/shipping-statuschange', 'RCare\Reports\Http\Controllers\RpmEnrolledReportController@changeStatusShipping')->name('save.status');  
        // Route::get('ajax/shippingreports_populate/{id}/populate', 'RCare\Reports\Http\Controllers\RpmEnrolledReportController@populateshipping')->name("ajax.shipping.populate");
        Route::get('ajax/shippingreports_populate/{patinet_id}/{device_code}/populate', 'RCare\Reports\Http\Controllers\RpmEnrolledReportController@populateshippingdevicewise')->name("ajax.shipping.populate");
        
        Route::get("/ajax/patientdevice/{patientid}/pateintdevice", "RCare\Reports\Http\Controllers\RpmEnrolledReportController@patientdevicelist")->name("ajax.patient.device");
        
       
        Route::get('/rpmenrolledpatientlist/{practices}/{patient}/{shipping_status}/{fromdate}/{todate}', 'RCare\Reports\Http\Controllers\RpmEnrolledReportController@RpmEnrolledReportSearch')->name('rpm.enrolled.search.report');  
        Route::get('/devicelist-rpmenrolled/{rowid}', 'RCare\Reports\Http\Controllers\RpmEnrolledReportController@getdeviceslist')->name('devices_list');
        Route::get('/shippinglist/{id}/{shipping_status}','RCare\Reports\Http\Controllers\RpmEnrolledReportController@getshippinglist')->name('shipping_list');
        Route::post('/delete-devices/{id}', 'RCare\Reports\Http\Controllers\RpmEnrolledReportController@actiondevice')->name('devices_list');
 

		Route::get('/questionaire_list/search/{practicesgrp}/{practice}/{provider}/{fromdate1}/{todate1}/{genquestionselection}', 'RCare\Reports\Http\Controllers\QuestionaireReportController@QuestionaireReportSearch')->name('Questionaire.search.report');
		Route::get('/patient_questionaire_list/search/{practice}/{patient}/{fromdate1}/{todate1}/{genquestionselection}', 'RCare\Reports\Http\Controllers\PatientQuestionaireReportController@PatientQuestionaireReportSearch')->name('Patient.Questionaire.search.report');
		
		Route::get('/patient-vitals-report-search/{practicegrpid}/{practiceid}/{patient}/{fromdate1}/{todate1}','RCare\Reports\Http\Controllers\PatientVitalsReportController@vitalsReportSearch')->name('patient.vitals.search.report'); 
		
	   //Clinical insight
        Route::get('/clinicalreportsearch/{practicesgrp}/{practices}/{provider}/{fromdate1}/{todate1}',
         'RCare\Reports\Http\Controllers\ClinicalReportController@ClinicalReportSearch')->name('initial.search.report');
		 
		Route::get('/consolidate-monthly-billing-report/search/{practicesgrpid}/{practiceid}/{providerid}/{module}/{monthly}/{monthlyto}/{activedeactivestatus}/{callstatus}/{onlycode}', 'RCare\Reports\Http\Controllers\ConsolidateBillingReportController@ConsolidateMonthlyBilllingReportPatientsSearch')->name('consolidate.monthly.billing.report.search');
		
		Route::get('/noreadingslastthreedaysInRPM-patients-details/{practiceid}', 'RCare\Reports\Http\Controllers\RpmStatusReportController@PatientNoReadingsReport')->name('rpm.no.readings');

        //ashwini a rpm daily status
        Route::get('/total-rpm-patients-details', function(){
            return view('Reports::rpm-status-report.enrolled-in-rpm-list');
        })->name('total.rpm.patients.details');   

		 Route::get('/noreadingslastthreedaysInRPM/search/{practice}', 'RCare\Reports\Http\Controllers\RpmStatusReportController@noReadingslastthreedaysInRPMData')->name('noreadingsInRPM.search');

        Route::get('/newenrolled-patients-details', function(){
            return view('Reports::rpm-status-report.newly-enrolled-in-rpm-list');
        })->name('total.newly.rpm.patients.details');      

        Route::get('/newlyenrolledInRPM/search/{practice}', 'RCare\Reports\Http\Controllers\RpmStatusReportController@getNewlyEnrolledInRPMPatientData')->name('newlyenrolledInRPM.search');
        Route::get('/enrolledInRPM/search/{practice}', 'RCare\Reports\Http\Controllers\RpmStatusReportController@getEnrolledInRPMPatientData')->name('enrolledInRPM.search');

        Route::post('/rpm-patient-summary', 'RCare\Reports\Http\Controllers\RpmStatusReportController@getPatientRPMData')->name('rpm.patient.summary');
        //ashwini a rpm daily status

     
        Route::get('/enrollment-tracking-report/search/{monthly}', 'RCare\Reports\Http\Controllers\EnrollmentTrackingReportController@PatientEnrollmentTrackingList')->name('enrollment.tracking.list'); 
        // Route::get('/parent-enrollment-tracking-report/search/{monthly}', 'RCare\Reports\Http\Controllers\EnrollmentTrackingReportController@PracticeGroupEnrollmentTrackingList')->name('practicegroup.enrollment.tracking.list');     

         //TaskStatusReport  
         Route::get('/task-status-report-search/{caremanagerid}/{practicesgrp}/{practiceid}/{patient}/{taskstatus}/{fromdate}/{todate}/{activedeactivestatus}/{score}/{timeoption}/{time}',
        'RCare\Reports\Http\Controllers\TaskStatusReportController@TaskStatusReportSearch')->name('task.status.search.report'); 
        
        Route::get('/login-logs-report/{fromdate}/{users}', 'RCare\Reports\Http\Controllers\LoginReportController@LoginLogsReportSearch')->name('login.search.report');

        //RPM billing report
        Route::get('/rpm-billing-report/{patient}/{practice}/{fromdate}/{todate}/{moduleid}/{provider}/{practicesgrp}/{activedeactivestatus}/{callstatus}', 'RCare\Reports\Http\Controllers\RpmBillingReportController@RpmBillingReportSearch')->name('rpm.billing.search.report');  
        
        //time-logs-reports-search
        Route::get('/time-logs-report/search', 'RCare\Reports\Http\Controllers\TimeLogsReportController@timeLogsReportSearch')->name('time.logs.report.search');     
        //Daily Productivity Report   
        Route::get('/productivity-daily-report/search/{practicesgrpid}/{practice}/{care_manager_id}/{fromdate}/{todate}/{activedeactivestatus}', 'RCare\Reports\Http\Controllers\ProductivityReportController@ProductivityDailyReportSearch')->name('productivity.daily.report.search'); 
		
        Route::get('/productivity-daily-report', function(){
            return view('Reports::productivity-report.productivity-daily-report');
        })->name('productivity.daily.report'); 
        
        //productivity billable
        Route::get('/productivity-daily-billable-patients', function(){
            return view('Reports::productivity-report.sub-steps-daily-productivity-report.billable-patient-list');
        })->name('productivity.daily.billable.patients');
        Route::get('/productivity-daily-billable-patients/{practice}/{care_manager_id}/{modules}/{fromdate}/{todate}', 'RCare\Reports\Http\Controllers\ProductivityReportController@ProductivityDailyBillablePatients')->name('productivity.daily.billable.patients');
        //productivity billable practice
        Route::get('/productivity-daily-billable-practices', function(){
            return view('Reports::productivity-report.sub-steps-daily-productivity-report.practice-billable-patient-list');
        })->name('productivity.daily.billable.practices'); 
        Route::get('/productivity-daily-practice-billable-patients/{fdate}/{tdate}/{care_manager_id}/{practice_id}/{module_id}', 'RCare\Reports\Http\Controllers\ProductivityReportController@ProductivityDailyPracticeBillablePatients')->name('productivity.daily.practice.billable.patients');
        //productivity practices
        Route::get('/productivity-practice', function(){ 
            return view('Reports::productivity-report.sub-steps-daily-productivity-report.practice-list');
        })->name('productivity.practice');
        Route::get('/productivity-practice/{practice}', 'RCare\Reports\Http\Controllers\ProductivityReportController@ProductivityPractice')->name('productivity.practice'); 
        //patient in productivity
        
		Route::get('/patient-in-daily-productivity-report-search/{fdate}/{tdate}/{care_manager_id}/{practice_id}/{pstatus}', 'RCare\Reports\Http\Controllers\ProductivityReportController@PatientInDailyProductivityReportSearch')->name('patient.in.daily.productivity.report.search');
        
		
		Route::get('/patient-in-daily-productivity-reports/{fdate}/{tdate}/{care_manager_id}/{practice_id}/{pstatus}', 'RCare\Reports\Http\Controllers\ProductivityReportController@PatientInDailyProductivityReport')->name('patient.in.daily.productivity.report');
		
        //productivity worked on
        Route::get('/productivity-daily-patient-worked-on/{practice}/{care_manager_id}/{modules}/{fromdate}/{todate}', 'RCare\Reports\Http\Controllers\ProductivityReportController@ProductivityDailyPatientsWorkedOn')->name('productivity.daily.patient.worked.on');
        // Route::get('/productivity-daily-patient-workedon', 'RCare\Reports\Http\Controllers\ProductivityReportController@patientDailyWorkedon')->name('productivity.daily.patientsworkedon.view');
        //productivity total patients
        Route::get('/productivity-daily-patient-worked-on', function(){
            return view('Reports::productivity-report.sub-steps-daily-productivity-report.patient-worked-on-list');
        })->name('productivity.daily.patientsworkedon.view');
        Route::get('/productivity-patients/{practice}', 'RCare\Reports\Http\Controllers\ProductivityReportController@ProductivityPatients')->name('productivity.patient');
        Route::get('/productivity-total-patients', function(){
            return view('Reports::productivity-report.sub-steps-daily-productivity-report.patients-list');
        })->name('productivity.total.patients');
        // productivity caremanager
        Route::get('/productivity-caremanager/{caremanager}', 'RCare\Reports\Http\Controllers\ProductivityReportController@ProductivityCaremanager')->name('productivity.caremanager');
        Route::get('/productivity-caremanager', function(){
            return view('Reports::productivity-report.sub-steps-daily-productivity-report.caremanager-list');
        })->name('productivity.caremanager');         
        //Productivity summary  
        Route::post('/patientproductivity-daily-summary', 'RCare\Reports\Http\Controllers\ProductivityReportController@ProductivityDailySummary')->name('ajax.patientproductivity.daily.summary');   
        Route::get('/monthly-report/search/{practicesgrpid}/{practice}/{provider}/{modules}/{monthly}/{activedeactivestatus}', 'RCare\Reports\Http\Controllers\ManuallyAdjustTimerReportController@listMonthlyReportPatientsSearch')->name('monthly.report.search');
        Route::post('/manually-adjust-time', 'RCare\System\Http\Controllers\CommonFunctionController@manuallyAdjustTime')->name('manually.adjust.time');
        Route::get('/care-manager-monthly-report/search/{practicegrpid}/{practice}/{caremanager}/{modules}/{monthly}/{monthlyto}/{activedeactivestatus}', 'RCare\Reports\Http\Controllers\CareManagerMonthlyReportController@listCareManagerMonthlyReportPatientsSearch')->name('care.manager.monthly.report.search');
         //todolist report
        Route::get('/task-management/search/{practice}/{patient}/{caremanagerid}/{taskstatus}/{activedeactivestatus}', 'RCare\Reports\Http\Controllers\ToDoListReportController@ToDoListReportSearch')->name('to.do.list.search.report'); 
        Route::get('/daily-report/search/{practicesgrpid}/{practiceid}/{providerid}/{module}/{fromdate}/{todate}/{time}/{timeoption}/{activedeactivestatus}', 'RCare\Reports\Http\Controllers\DailyBillableReportController@DailyReportPatientsSearch')->name('daily.report.search'); 

        Route::get('/consolidate-monthly-billing-report/search/{practicesgrpid}/{practiceid}/{providerid}/{module}/{monthly}/{monthlyto}/{activedeactivestatus}/{callstatus}', 'RCare\Reports\Http\Controllers\ConsolidateBillingReportController@ConsolidateMonthlyBilllingReportPatientsSearch')->name('consolidate.monthly.billing.report.search');
        Route::get('/monthly-billing-report/search/{practicesgrpid}/{practiceid}/{providerid}/{module}/{monthly}/{monthlyto}/{activedeactivestatus}/{callstatus}', 'RCare\Reports\Http\Controllers\MonthlyBillableReportController@MonthlyBilllingReportPatientsSearch')->name('monthly.billing.report.search');
        Route::get('/care-manager-report/search/{practicesgrpid}/{practiceid}/{providerid}/{module}/{time}/{care_manager_id}/{fromdate}/{todate}/{timeoption}/{activedeactivestatus}', 'RCare\Reports\Http\Controllers\CMBillingStatusReport@CareManagerReportSearch')->name('care.manager.report.search');
        Route::post('/billupdate', 'RCare\Reports\Http\Controllers\CMBillingStatusReport@CMBillUpdate')->name('bill.update');
        Route::get('/enrollment-report/search/{practicesgrpid}/{practiceid}/{care_manager_id}/{fromdate}/{todate}/{module}/{provider}/{activedeactivestatus}', 'RCare\Reports\Http\Controllers\EnrollmentReportController@EnrollmentReportSearch')->name('enrollment.report.search');
        // Route::post('/patient-summary', 'RCare\Reports\Http\Controllers\ReportController@getPatientEnrolledData')->name('patient.summary');
        Route::post('/patient-summary', 'RCare\Reports\Http\Controllers\EnrollmentReportController@getPatientEnrolledData')->name('patient.summary');
        
        Route::post('/monthly-patient-summary', 'RCare\Reports\Http\Controllers\MonthlyBillableReportController@getPatientData')->name('patient.summary');  
        Route::get('/totalbillablepatients/search/{practice}', 'RCare\Reports\Http\Controllers\MonthlyBillableReportController@TotalBillablePatientsSearch')->name('patient.totalbillable');
        Route::get('/totalnonbillablepatients/search/{practice}', 'RCare\Reports\Http\Controllers\MonthlyBillableReportController@TotalNonBillablePatientSearch')->name('patient.nontotalbillable'); 
        Route::get('/totalbillablepatientsrpm/search/{practice}', 'RCare\Reports\Http\Controllers\MonthlyBillableReportController@TotalBillablePatientRPMSearch')->name('patient.nontotalbillablerpm'); 
        

        //ashwini mali call and additional service report
        Route::get('/countcallAdditionalServiceListSearch/{practicesgrp}/{practices}/{provider}/{fromdate1}/{todate1}',
         'RCare\Reports\Http\Controllers\CallActivityPractiseWiseCountReportController@CAPWCReportSearch')->name('call.Activity.Service.search.report');
        
         Route::get("/callActivityServiceListSearch/{practices}/{provider}/{practicesgrp}/{patient}/{fromdate1}/{todate1}", 
         "RCare\Reports\Http\Controllers\CallActivityPractiseWiseCountReportController@callActivityServiceListSearch")->name("callActivityServiceListSearch");


        
        // Route::get('/non-enrolled/search/{practice}', 'RCare\Reports\Http\Controllers\MonthlyBillableReportController@getBillablePatientData')->name('billable.search');
        // Route::get("/callActivityServiceListSearch/{practices}/{patient}/{fromdate}/{todate}", "RCare\Reports\Http\Controllers\CallActivityServicesReportController@callActivityServiceListSearch")->name("callActivityServiceListSearch");	
		
        Route::get('/non-enrolled/search/{practice}', 'RCare\Reports\Http\Controllers\EnrollmentReportController@getNonEnrolledPatientData')->name('non.enrolled.search');
        Route::get('/enrolled/search/{practice}/{activedeactivestatus}', 'RCare\Reports\Http\Controllers\EnrollmentReportController@getEnrolledPatientData')->name('enrolled.search');
        Route::get('/totalpatients/search/{practice}', 'RCare\Reports\Http\Controllers\EnrollmentReportController@getTotalPatientData')->name('totalpatients.search');
        Route::get('/enrolledInCCM/search/{practice}/{activedeactivestatus}', 'RCare\Reports\Http\Controllers\EnrollmentReportController@getEnrolledInCCMPatientData')->name('enrolledInCCM.search');
        Route::get('/enrolledInRPM/search/{practice}/{activedeactivestatus}', 'RCare\Reports\Http\Controllers\EnrollmentReportController@getEnrolledInRPMPatientData')->name('enrolledInRPM.search');
        // Route::get('/total-patients-details', 'RCare\Reports\Http\Controllers\ReportController@viewTotalPatients')->name('total.patients.details');
        
		Route::get('/rpm-daily-status-report/search/{practice}/{fromdate}', 'RCare\Reports\Http\Controllers\RpmStatusReportController@PatientRpmStatusReportSearch')->name('rpm.daily.status.report.search');


        Route::get('/billablepatients-details', function(){
            return view('Reports::monthly-biling-report.billable-patients'); 
        })->name('total.billable.patients.details');

        Route::get('/nonbillablepatients-details', function(){
            return view('Reports::monthly-biling-report.nonbillable-patients'); 
        })->name('total.nonbillable.patients.details');

        Route::get('/billablepatientsrpm-details', function(){
            return view('Reports::monthly-biling-report.billable-patients-rpm');   
        })->name('total.billable.patients.rpm.details');

        Route::get('/nonbillablepatientsrpm-details', function(){
            return view('Reports::monthly-biling-report.billable-patients-rpm'); 
        })->name('total.nonbillable.patients.rpm.details'); 



        Route::get('/total-patients-details', function(){
            return view('Reports::enrollment-report.sub-steps-enrollment-report.total-patients-list');
        })->name('total.patients.details');

        // Route::get('/total-patients', function(){
        //     return view('Reports::enrollment-report.sub-steps-enrollment-report.total-patients-list');
        // })->name('total.patients');

        // Route::get('/enrolled-patients-details', 'RCare\Reports\Http\Controllers\ReportController@viewEnrolledPatients')->name('enrolled.patients.details');
        Route::get('/enrolled-patients-details', function(){
            return view('Reports::enrollment-report.sub-steps-enrollment-report.enrolled-list');
        })->name('enrolled.patients.details');
        // Route::get('/non-enrolled-patients-details', 'RCare\Reports\Http\Controllers\ReportController@viewNonEnrolledPatients')->name('non.enrolled.patients.details');
        Route::get('/non-enrolled-patients-details', function(){
            return view('Reports::enrollment-report.sub-steps-enrollment-report.non-enrolled-list');
        })->name('non.enrolled.patients.details');
        // Route::get('/enrolled-In-CCM-details', 'RCare\Reports\Http\Controllers\ReportController@viewEnrolledInCCMPatients')->name('enrolled.In.CCM.details');
        Route::get('/enrolled-In-CCM-details', function(){
            return view('Reports::enrollment-report.sub-steps-enrollment-report.enrolled-in-ccm-list');
        })->name('enrolled.In.CCM.details');
        // Route::get('/enrolled-In-RPM-details', 'RCare\Reports\Http\Controllers\ReportController@viewEnrolledInRPMPatients')->name('enrolled.In.RPM.details');
        Route::get('/enrolled-In-RPM-details', function(){
            return view('Reports::enrollment-report.sub-steps-enrollment-report.enrolled-in-rpm-list');
        })->name('enrolled.In.RPM.details');
        // Route::get('/provider-performance-report', function(){
        //    return view('Reports::provider-performance-report');
        // })->name('provider.performance.report');
        Route::get('/fortesting-dynamic-column', function(){
        return view('Reports::patients-monthly-billing-report-fortesting');
        })->name('fortesting.dynamic.column');
        Route::get('/provider-performance-report-search/{practicesgrpid}/{practice}/{provider}/{activedeactivestatus}', 'RCare\Reports\Http\Controllers\ProviderPerformanceController@getProviderPerformanceReport')->name('provider.performance.report.search');
         // Route::get('/provider-performance-patient-details-in-ccm', function(){
         //    return view('Reports::sub-steps-provider-performance-report.patients-details-in-ccm-list');
         // })->name('provider.performance.patient.details.in.ccm');
        Route::get('/provider-performance-patient-details-in-ccm/{practiceid}/{providerid}', 'RCare\Reports\Http\Controllers\ProviderPerformanceController@getPPPatientsDetailsInCCM')->name('provider.performance.patient.details.in.ccm');
        Route::get('/provider-performance-patient-details-in-rpm/{practiceid}/{providerid}', 'RCare\Reports\Http\Controllers\ProviderPerformanceController@getPPPatientsDetailsInRPM')->name('provider.performance.patient.details.in.rpm');
        Route::get('/get-data-patient-details-in-ccm/{practiceid}/{providerid}', 'RCare\Reports\Http\Controllers\ProviderPerformanceController@getPPPatientDetailsInCCMData')->name('get.data.patient.details.in.ccm');
        Route::get('/get-data-patient-details-in-rpm/{practiceid}/{providerid}', 'RCare\Reports\Http\Controllers\ProviderPerformanceController@getPPPatientDetailsInRPMData')->name('get.data.patient.details.in.rpm');

         Route::get('/additional-activities-report', function(){
            return view('Reports::additional-activity-report.Additional-Activities-Report');
        })->name('Additional.Activities.Report');
    
         Route::get('/care-manager-logged-minute-productivity-report  ', function(){
            return view('Reports::caremanager-logged-minute-productivity.productivity-caremanager-logged-minute');
        })->name('CareManager.LoggedMinute.Report'); 
        
        Route::get('/care-manager-performance-report  ', function(){
            return view('Reports::caremanager-performance-report.caremanager-performance');
        })->name('CareManager.performance.Report');
        
        Route::get('/care-manager-performance-report/search/{caremanagerid}/{fromdate}/{todate}', 'RCare\Reports\Http\Controllers\CareManagerPerformanceController@CaremanagerPerformanceReportSearch')->name('caramanager.performance.report.search');

        Route::get('/export-file', function(){
            return view('Reports::additional-activity-report.exportdata');
        })->name('export.file');
         Route::get('/exportdata', 'RCare\Reports\Http\Controllers\AdditionalAcitvitiesReportController@exportdata')->name('exportdata');
      Route::get('/additinal-activities-report/search/{practicesgrpid}/{practiceid}/{care_manager_id}/{fromdate}/{todate}/{activedeactivestatus}/{activityid}', 'RCare\Reports\Http\Controllers\AdditionalAcitvitiesReportController@AcitvitiesReportSearch')->name('additinal.activities.report.search'); 
      
      //logged minute
      Route::get('/care-manager-logged-minute-productivity-report/search/{practiceid}/{fromdate}', 'RCare\Reports\Http\Controllers\CaremanagerLoggedMinuteProductivityReport@CaremanagerLoggedMinuteProductivityReportSearch')->name('caramanager.logged.minute.report.search');


      Route::get('/additinal-activities-details/{patientid}/{fromdate}/{todate}', 'RCare\Reports\Http\Controllers\AdditionalAcitvitiesReportController@AcitvitiesReportDetails')->name('additinal.activities.details');
     Route::get('/monthly-account-personal-report/search/{practicesgrpid}/{practice}/{modules}/{from_month}/{to_month}','RCare\Reports\Http\Controllers\MonthlyAccountPerfomanceReport@listMonthlyAccountPerformanceReport')->name('monthly.account.personal.report.search');
     Route::get('/care-manager-daily-productivity-report/search/{caremanager}/{date}', 'RCare\Reports\Http\Controllers\CaremanagerDailyProductivityReport@listCaremanagerDailyProductivityReport')->name('caremanag er.daily.productivity.report.search');
    
    Route::get('/cpd-report/search/{practicesgrpid}/{practice}/{billable}/{careplanstatuas}/{fromdate}/{todate}/{activedeactivestatus}', 'RCare\Reports\Http\Controllers\CPDReportController@CPDReportSerch')->name('cpd.report.search');
    
     Route::get('/device-data-report/search/{patientid}/{month}/{devices}', 'RCare\Reports\Http\Controllers\DeviceDataReportController@DDReportSerch')->name('device.data.report.search');
     
     Route::get('/insurance-report', function(){
            return view('Reports::insurance-report');
        })->name('insurance.report');  
     Route::get('/insurance-report/search/{insurance}', 'RCare\Reports\Http\Controllers\InsuranceController@getInsuranceReport')->name('insurance.report.search');

     Route::get('/graphreadingnew/{practice_id}/{patient_id}/{month}/graphchart', 'RCare\Reports\Http\Controllers\DeviceDataReportController@graphreadReadings')->name('readingnew'); 
     Route::get('/graphreadinghart/{practice_id}/{patient_id}/{month}/graphchart', 'RCare\Reports\Http\Controllers\DeviceDataReportController@graphHart')->name('readinghart'); 
     Route::get('/graphreadingbp/{practice_id}/{patient_id}/{month}/graphchart', 'RCare\Reports\Http\Controllers\DeviceDataReportController@graphBP')->name('readingbp'); 
     Route::get('/graphreadingwt/{practice_id}/{patient_id}/{month}/graphchart', 'RCare\Reports\Http\Controllers\DeviceDataReportController@grapWt')->name('readingwt'); 
     Route::get('/graphreadingtemp/{practice_id}/{patient_id}/{month}/graphchart', 'RCare\Reports\Http\Controllers\DeviceDataReportController@graphTemp')->name('readingtemp'); 
     Route::get('/graphreadinggul/{practice_id}/{patient_id}/{month}/graphchart', 'RCare\Reports\Http\Controllers\DeviceDataReportController@graphGul')->name('readinggul'); 
     Route::get('/graphreadingspiro/{practice_id}/{patient_id}/{month}/graphchart', 'RCare\Reports\Http\Controllers\DeviceDataReportController@graphSpiro')->name('readingspiro'); 

      
     Route::get('preview', 'RCare\Reports\Http\Controllers\DeviceDataReportController@preview');
     Route::get('htmltopdfview', 'RCare\Reports\Http\Controllers\DeviceDataReportController@htmltopdfview')->name('htmltopdfview'); 
     
      Route::get('/getassigndevice/{pid}', 'RCare\Reports\Http\Controllers\DeviceDataReportController@getAssignDevice')->name('getassigndevice');
    
     
     Route::get('/new-enrolled-patients-details', function(){
        return view('Reports::monthly-account-performance-report.total-new-enroll');
    })->name('new.enrolled.patients.details');
    Route::get('/newenrolled/search/{practice}', 'RCare\Reports\Http\Controllers\MonthlyAccountPerfomanceReport@getnewEnrolledPatientData')->name('newenrolled.search');
    });
    Route::get('/device-data-report/{pid}', function(){
                return view('Reports::device-data-reports.device-data-report');
            })->name('device.data.report');
    Route::post('/task-management-statuschange', 'RCare\Reports\Http\Controllers\ToDoListReportController@changeStatusToDoList')->name('status-to-do-list-report'); 
	
	 Route::get('/verify-code/search/{practices}/{diagnosis}', 'RCare\Reports\Http\Controllers\Verifyicd10CodeReportController@verifysearchReport')->name('verify.code'); 
        Route::get('/verify-code1/search/{practices}/{diagnosis}', 'RCare\Reports\Http\Controllers\Verifyicd10CodeReportController@verifysearchReport1')->name('verify.code1'); 
        Route::POST('/verify-request','RCare\Reports\Http\Controllers\Verifyicd10CodeReportController@updateverifycode')->name('view.verify');
        Route::get('/verify-code-child/search/{diagnosis}/{code}/{practicid}', 'RCare\Reports\Http\Controllers\Verifyicd10CodeReportController@verifyChildSearchReport')->name('verify.code.child.search'); 
        Route::get('/verify-code-child1/search/{diagnosis}/{code}/{practicid}', 'RCare\Reports\Http\Controllers\Verifyicd10CodeReportController@verifyChildSearchReport1')->name('verify.code.child.search'); 
        
        Route::get("/callactivityServicelist", "RCare\Reports\Http\Controllers\CallActivityServicesReportController@callActivityServiceListsReport")->name("callactivityServicelist");         
        Route::get("/callActivityServiceListSearch/{practices}/{patient}/{fromdate}/{todate}", "RCare\Reports\Http\Controllers\CallActivityServicesReportController@callActivityServiceListSearch")->name("callActivityServiceListSearch");


    //call-status-report-search 7th sept 2022
    Route::get('/call-status-report/search/{practicesgrpid}/{practiceid}/{providerid}/{module}/{activedeactivestatus}/{timeperiod}', 'RCare\Reports\Http\Controllers\CallStatusReportController@PatientCallStatusReportSearch')->name('call.status.report.search'); 
    Route::get('/consolidate-monthly-billing-report', 'RCare\Reports\Http\Controllers\ConsolidateBillingReportController@PatientMonthlyBillingReport')->name('consolidate.monthly.billing.report'); 
   
});