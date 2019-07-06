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
    Route::get('/project/list', 'Project\ProjectApiController@getList');
    Route::get('/project/{id}/detail', 'Project\ProjectApiController@getDetail');
    Route::get('/project/{id}/task-stat', 'Project\ProjectApiController@getTaskStatList');
});
