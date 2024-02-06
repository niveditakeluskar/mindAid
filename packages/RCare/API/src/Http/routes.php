<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use RCare\API\Http\Controllers\ApiUserController;
use RCare\API\Http\Controllers\VoipWebHookController;
/*
|--------------------------------------------------------------------------
| RCare / API
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


    Route::prefix('API')->group(function () {
        Route::get('/prantest', function(){
            return "TEST this is tested by pranali";
        });
        Route::get('/order-form', function(){
                return view('API::device-order');
        })->name('order.form');
		
		Route::post('voip_webhook', [VoipWebHookController::class, 'voipwebhookHandler']);        
        Route::get('/test', 'RCare\API\Http\Controllers\APIController@test')->name('test');
        Route::post('/data_from_device', 'RCare\API\Http\Controllers\MioDeviceController@test_mio_webhook_observation')->name('test_mio_webhook_data_from_device'); 
        Route::post('/data_from_device/{id}', 'RCare\API\Http\Controllers\MioDeviceController@mio_webhook_observation')->name('mio_webhook_data_from_device'); 
        Route::post('/process_mio_webhook_observation', 'RCare\API\Http\Controllers\MioDeviceController@process_mio_webhook_observation')->name('process_mio_webhook_observation'); 
        Route::post('/savethresholdreadingofmioWebhook', 'RCare\API\Http\Controllers\MioDeviceController@saveThresholdReadingOfMioWebhook')->name('save_threshold_reading_of_miowebhook'); 
		
		Route::put('/order', 'RCare\API\Http\Controllers\APIController@updateOrder');
		Route::get('/patient-order-list', 'RCare\API\Http\Controllers\APIController@OrderList');
		Route::get('/order_list', 'RCare\API\Http\Controllers\APIController@getOrderList')->name('order_list');
		Route::get('/partner-form', 'RCare\API\Http\Controllers\APIController@PartnerForm')->name('partner.form');
        Route::post('/add-partner-form', 'RCare\API\Http\Controllers\APIController@InsertPartner')->name('add.partner.form');
		
		Route::post('/tellihealth_webhook_data', 'RCare\API\Http\Controllers\TellihealthAPIController@tellihealth_webhook_observation')
        ->name('tellihealth_webhook_data');
		
       
        Route::get('/tellihealth_process_webhook_observation', 'RCare\API\Http\Controllers\TellihealthAPIController@process_webhook_observation')
        ->name('tellihealth_process_webhook_observation');

        Route::post('/test', 'RCare\API\Http\Controllers\APIController@testJsonArr')->name('testJson');
        Route::post('/orders', 'RCare\API\Http\Controllers\APIController@orders')->name('orders');
        Route::post('/observations', 'RCare\API\Http\Controllers\APIController@observations')->name('observations');
        Route::post('/alerts', 'RCare\API\Http\Controllers\APIController@alerts')->name('alerts');
         Route::get('/testurl', 'RCare\API\Http\Controllers\ECGAPIController@testcurlfunction')->name('order_list');
        // Route::get("/ecg-get-device-alert", "RCare\API\Http\Controllers\ECGAPIController@GetDeviceAlertdata")->name("ecg.get.device.alert");
        Route::post('/sms/reply', 'RCare\API\Http\Controllers\MessageReplyController@smsreply')->name('sms.reply');
        //   Route::get('/updaterpmlist', 'RCare\API\Http\Controllers\ECGAPIController@updateRPMPatientAlert')->name('updaterpmlist');
        
       
    });

     //Route::post("/add-order", "RCare\API\Http\Controllers\APIController@PatientOrderPost")->name("test.order.post");
     Route::post("/get-authorization", "RCare\API\Http\Controllers\ECGAPIController@getAuthorization")->name("get.authorization");
     Route::post("/add-reading", "RCare\API\Http\Controllers\APIController@PatientReadingPost")->name("patient.reading.post");
     Route::post("/add-order", "RCare\API\Http\Controllers\APIController@PatientDevices")->name("patient.reading.post");
     Route::post("/ecg-place-order", "RCare\API\Http\Controllers\ECGAPIController@PlaceOrder")->name("ecg.place.order");
     Route::get("/ecg-place-refreshtoken", "RCare\API\Http\Controllers\ECGAPIController@RefreshToken")->name("ecg.place.refreshtoken");
     Route::get("/ecg-get-device-alert", "RCare\API\Http\Controllers\ECGAPIController@GetDeviceAlertdata")->name("ecg.get.device.alert");
     Route::get("/ecg-get-office-details", "RCare\API\Http\Controllers\ECGAPIController@GetOfficeDetails")->name("ecg.get.office.details");
     Route::get("/ecg-get-observation-details", "RCare\API\Http\Controllers\ECGAPIController@GetOfficeDetails")->name("ecg.get.observation.details");  
     Route::get('/add-devicelist-observation','RCare\API\Http\Controllers\ECGAPIController@saveDevicedataObservationLog')->name('device.observation.add');
     Route::get('/add-observationlist-observation','RCare\API\Http\Controllers\ECGAPIController@saveObservationdataObservationLog')->name('observationdata.observation.add');  
     Route::get('/add-rpmreadings','RCare\API\Http\Controllers\ECGAPIController@saveRPMReadings')->name('observationdata.rpmreadings.add');   
     Route::get('/devicealert-observation','RCare\API\Http\Controllers\ECGAPIController@saveAlertDeviceid')->name('device.alert.observation');     
     Route::get('/save-patient-threshold','RCare\API\Http\Controllers\ECGAPIController@savePatientThreshold')->name('save.patient.threshold');  
     Route::get('/save-group-threshold','RCare\API\Http\Controllers\ECGAPIController@saveGroupThreshold')->name('save.group.threshold'); 

     Route::get("/save-order-log", "RCare\API\Http\Controllers\ECGAPIController@saveOrderLog")->name("save.order.log");
     Route::get("/update-rpm-alert", "RCare\API\Http\Controllers\ECGAPIController@updateRPMPatientAlert")->name("update.rpm.patientalert");
      Route::get("/add-rpm-otheralert", "RCare\API\Http\Controllers\ECGAPIController@otherAlerts")->name("add.rpm.otheralerts");  
      
?>