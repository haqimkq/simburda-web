<?php

use App\Http\Controllers\Api\DeliveryOrderController;
use App\Http\Controllers\Api\KendaraanController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\SuratJalanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| User API Routes
|--------------------------------------------------------------------------
*/

    // Token has both "check-status" and "place-orders" abilities...
    // middleware(['auth:sanctum', 'abilities:check-status,place-orders']);

    // Token has the "check-status" or "place-orders" ability...
    // middleware(['auth:sanctum', 'ability:check-status,place-orders']);

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
    Route::middleware(['admin-setmanager-supervisor-admingudang-logistic'])->group(function(){
        Route::get('surat-jalan/all', [SuratJalanController::class, 'getAllSuratJalanByUser']);
        Route::get('surat-jalan/active', [SuratJalanController::class, 'getSomeActiveSuratJalanByUser']);
        Route::get('surat-jalan/active/count', [SuratJalanController::class, 'getCountActiveSuratJalanByUser']);
        Route::get('surat-jalan/all/dalam-perjalanan', [SuratJalanController::class, 'getAllSuratJalanDalamPerjalananByUser']);
        Route::get('surat-jalan/{id}', [SuratJalanController::class, 'getSuratJalanById']);
    });
    Route::middleware(['logistic'])->group(function(){
        Route::get('kendaraan/logistic', [KendaraanController::class, 'getKendaraanByLogistic']);
    });
    Route::middleware(['admin-purchasing-admingudang-logistic'])->group(function(){
        Route::get('delivery-order/all', [DeliveryOrderController::class, 'getAllDeliveryOrderByUser']);
        Route::get('delivery-order/active', [DeliveryOrderController::class, 'getSomeActiveDeliveryOrderByUser']);
        Route::get('delivery-order/active/count', [DeliveryOrderController::class, 'getCountActiveDeliveryOrderByUser']);
        Route::get('delivery-order/all/dalam-perjalanan', [DeliveryOrderController::class, 'getAllDeliveryOrderDalamPerjalananByUser']);
        Route::get('delivery-order/{id}', [DeliveryOrderController::class, 'getDeliveryOrderById']);
    });
});

Route::post('login', [UserController::class, 'login']);
Route::post('forget-password',[UserController::class, 'forgetPassword']);
Route::post('register', [UserController::class, 'register']);

Route::get('province', [ProvinceController::class, 'getProvince']);
Route::get('city/{city}', [ProvinceController::class, 'getCityByProvince']);



