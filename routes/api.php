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
    Route::put('user/update-profile', [UserController::class, 'updateProfile']);
    Route::post('user/update-photo', [UserController::class, 'updatePhoto']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('currentAccessToken', [UserController::class, 'currentAccessToken']);

    Route::middleware(['admin-admingudang'])->group(function () {
        Route::post('surat-jalan', [SuratJalanController::class, 'create']);
        Route::get('surat-jalan/admin-gudang/{admin_gudang_id}', [SuratJalanController::class, 'getAllSuratJalanByAdminGudang']);
    });
});

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);



