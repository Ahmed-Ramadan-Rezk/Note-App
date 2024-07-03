<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\NoteController;
use App\Http\Controllers\Api\V1\AuthController;

// User Authentication Api Routes
Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
});

// Note Api Routes
Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::apiResource('notes', NoteController::class)->middleware('auth:sanctum');
});
