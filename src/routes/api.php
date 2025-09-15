<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/domains/top', [UserController::class, 'topDomains']);
    Route::apiResource('users', UserController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/login', [AuthController::class, 'login']);

