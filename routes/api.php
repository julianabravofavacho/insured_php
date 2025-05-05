<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InsuredController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::delete('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum',);

Route::apiResource('users', UserController::class)->middleware('auth:sanctum');
Route::apiResource('insureds', InsuredController::class)->middleware('auth:sanctum');
