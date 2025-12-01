<?php

use App\Http\Controllers\API\Testcontroller;   
Use App\Http\Controllers\API\ProjectController;
Use App\Http\Controllers\API\TaskController;
Use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/test',[Testcontroller::class,'index']);  

Route::apiResource('projects', ProjectController::class)->middleware('auth:sanctum');

Route::apiResource('tasks', TaskController::class)->middleware('auth:sanctum');

