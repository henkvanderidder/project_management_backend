<?php

use App\Http\Controllers\API\Testcontroller;   
Use App\Http\Controllers\API\ProjectController;     
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test',[Testcontroller::class,'index']);  

Route::apiResource('projects', ProjectController::class);