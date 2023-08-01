<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['throttle:5,1', 'guest']], function () {
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::post('/send-password-reset-link', [UserController::class, 'sendPasswordResetLink']);
    Route::put('/reset-password', [UserController::class, 'resetPassword']);
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/user', [UserController::class, 'authUser']);
    Route::get('/users-options', [UserController::class, 'usersOptions']);
    Route::put('/user', [UserController::class, 'update']);
    Route::post('/logout', [AuthenticationController::class, 'logout']);

    Route::get('todos', [TaskController::class, 'todos']);
    Route::post('task/update-status/{task}', [TaskController::class, 'updateStatus']);
    Route::apiResource('tasks', TaskController::class);
});


