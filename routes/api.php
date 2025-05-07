<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InsuredController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('Authentication/login', [AuthController::class, 'login']);
Route::delete('Authentication/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum',);

Route::apiResource('User', UserController::class)->middleware('auth:sanctum');
Route::apiResource('Insured', InsuredController::class)->middleware('auth:sanctum');
