<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth')->prefix('v1')->group(function () {
    Route::post('/project/add', 'Project\ProjectApiController@addProject');
    Route::post('/project/{id}/edit', 'Project\ProjectApiController@editProject');
    Route::get('/project/list', 'Project\ProjectApiController@getList');
    Route::get('/project/{id}/detail', 'Project\ProjectApiController@getDetail');
    Route::get('/project/{id}/task-stat', 'Project\ProjectApiController@getTaskStatList');
    Route::get('/project/{id}/scheduled-tasks', 'Project\ProjectApiController@getScheduledTaskList');
    Route::get('/project/task-count-list', 'Project\ProjectApiController@getTaskCountList');
    Route::post('/project/{projectId}/task/add', 'TaskApiController@addTask');
    Route::get('/project/{projectId}/task/list', 'TaskApiController@getTaskList');
});

Route::middleware('auth')->prefix('v1')->group(function () {
    Route::get('/task/total-completed-task-count', 'TaskApiController@getTotalCompletedTaskCount');
    Route::get('/task/near-deadline-list', 'TaskApiController@getNearDeadlineList');
    Route::get('/task/in-progress-list', 'TaskApiController@getInProgressList');
    Route::post('/task/bulk-date-update', 'TaskApiController@bulkUpdateDate');
    Route::post('/task/bulk-state-update', 'TaskApiController@bulkUpdateState');
    Route::get('/task/{id}', 'TaskApiController@getTask');
    Route::post('/task/{id}', 'TaskApiController@updateTask');
    Route::post('/task/{id}/log/add', 'TaskApiController@addTaskLog');
});
