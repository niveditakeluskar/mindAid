<?php

/*
|--------------------------------------------------------------------------
| RCare / Messaging Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::prefix('messaging')->group(function () { 
    Route::middleware(["auth", "web"])->group(function () { 
        Route::middleware(["roleAccess"])->group(function () { 
            Route::get('/message-log', function(){
                return view('Messaging::chat');
            })->name('message.log');
        });
        Route::get('/message-log/search/{practicesgrpid}/{practice}/{caremanager}/{module}/{fromdate}/{todate}/{status}', 'RCare\Messaging\Http\Controllers\MessageLogController@MessageLog')->name('message.log.search');

        Route::post('/view-message', 'RCare\Messaging\Http\Controllers\MessageLogController@ViewMessage')->name('view.message');
        Route::post('/resend-message', 'RCare\Messaging\Http\Controllers\MessageLogController@resendMessage')->name('resend.message');
        Route::post('/get-message', 'RCare\Messaging\Http\Controllers\MessageLogController@getMessage')->name('get.message');
        Route::post('/get-message-history', 'RCare\Messaging\Http\Controllers\MessageLogController@getMessageHistory')->name('get.message.history');
        Route::post('/send-message', 'RCare\Messaging\Http\Controllers\MessageLogController@sendMessage')->name('send.message');
        Route::get('/get-message-count', 'RCare\Messaging\Http\Controllers\MessageLogController@getMessageCount')->name('get.message.count');
        Route::get('/get-user-list/{val}/{id}', 'RCare\Messaging\Http\Controllers\MessageLogController@getUserList')->name('get.user.list');
        Route::get('/get-user-concent/{val}', 'RCare\Messaging\Http\Controllers\MessageLogController@getContactDetail')->name('get.user.concent');
        Route::post('/update-concent-data', 'RCare\Messaging\Http\Controllers\MessageLogController@updateDetalis')->name('update.concent.data');
    });
});