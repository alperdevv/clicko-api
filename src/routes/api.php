<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

Route::get('users/{id}', [UserController::class, 'show']);
Route::get('/users/index', [UserController::class, 'index']);
Route::get('/users/domains/top', [UserController::class, 'topDomains']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']); 

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});
