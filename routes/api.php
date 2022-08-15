<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskAttachmentController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskTagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group(['prefix' => 'auth', 'namespace' => 'Api'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('logout',    [AuthController::class, 'logout']);
    });
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('tasks/upload-file/{task}',  [TaskAttachmentController::class, 'uploadFile']);


    Route::get('file-download/{path}',  [TaskAttachmentController::class, 'downloadFile']);

    Route::put('tasks/toggle-archived/{task}',  [TaskController::class, 'toggleArchived']);
    Route::put('tasks/update-status/{task}',  [TaskController::class, 'updateTaskStatus']);
    Route::put('tasks/update-due-date/{task}',  [TaskController::class, 'updateDueDate']);
    Route::post('tasks/list',  [TaskController::class, 'list']);
    Route::apiResource('tasks/tags',  TaskTagController::class);
    Route::apiResource('tasks',  TaskController::class);
});
