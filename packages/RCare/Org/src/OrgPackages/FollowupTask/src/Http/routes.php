<?php

use RCare\Org\OrgPackages\FollowupTask\src\Models\FollowupTask;
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
            Route::get("/followup-task", function () {
                return view('FollowupTask::followupTask');
            })->name("followup-task");
        });
        Route::post("/add-task", "RCare\Org\OrgPackages\FollowupTask\src\Http\Controllers\FollowupTaskController@AddFollowupTask")->name("add-task");
        Route::get("/task_list", "RCare\Org\OrgPackages\FollowupTask\src\Http\Controllers\FollowupTaskController@getFollowupTaskListData")->name("list-task");
        Route::get('ajax/FollowupTask_populate/{id}/populate', 'RCare\Org\OrgPackages\FollowupTask\src\Http\Controllers\FollowupTaskController@populateFollowupTask')->name("ajax.followupTask.populate");
        Route::post('/delete-FollowupTask/{id}', 'RCare\Org\OrgPackages\FollowupTask\src\Http\Controllers\FollowupTaskController@deleteFollowupTask')->name('delete.FollowupTask');
        Route::get('/get_future_followup_task', function () {
            $abc        = session()->get("user_type");
            $tasks      = $abc == 1 ? FollowupTask::activeTask() : FollowupTask::activeTask();
            return response()->json($tasks);
        });
    });
});
