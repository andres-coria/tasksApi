<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\taskController;
use App\Http\Controllers\Api\userController;


Route::post('/register', [userController::class, 'store']);

Route::post('/login', [userController::class, 'login']);

Route::get('/users', [userController::class, 'index'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks', [taskController::class, 'index']);
    Route::post('/tasks', [taskController::class, 'store']);
    Route::get('/tasks/{id}', [taskController::class, 'show']);
    Route::put('/tasks/{id}', [taskController::class, 'update']);
    Route::delete('/tasks/{id}', [taskController::class, 'destroy']);
});
