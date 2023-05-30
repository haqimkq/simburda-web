<?php

use App\Http\Controllers\Api\SuratJalanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| User API Routes
|--------------------------------------------------------------------------
*/


Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('user/profile', [UserController::class, 'updateProfile']);
    Route::post('user/photo', [UserController::class, 'uploadPhoto']);
    Route::post('user/device-token', [UserController::class, 'setDeviceToken']);
    Route::post('user/ttd', [UserController::class, 'uploadTTD']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('currentAccessToken', [UserController::class, 'currentAccessToken']);
    Route::get('user/ttd', [UserController::class, 'getTtd']);

    Route::middleware(['admin-admingudang'])->group(function () {
        Route::post('surat-jalan', [SuratJalanController::class, 'create']);
        Route::put('surat-jalan/{surat_jalan_id}', [SuratJalanController::class, 'update']);
    });
    Route::middleware(['admin-projectmanager-supervisor-admingudang-logistic'])->group(function(){
        Route::get('surat-jalan/all', [SuratJalanController::class, 'getAllSuratJalanByUser']);
        Route::get('surat-jalan/{id}', [SuratJalanController::class, 'getSuratJalanById']);
    });
});

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);



