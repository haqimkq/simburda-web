<?php

use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\DeliveryOrderController;
use App\Http\Controllers\Api\GudangController;
use App\Http\Controllers\Api\KendaraanController;
use App\Http\Controllers\Api\PeminjamanController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\ProyekController;
use App\Http\Controllers\Api\SuratJalanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Models\Proyek;

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
    Route::get('user/detail', [UserController::class, 'getDetailUser']);
    Route::post('user/update/password', [UserController::class, 'updatePassword']);
    Route::post('user/update/pin', [UserController::class, 'updatePIN']);


    Route::middleware(['role:ADMIN_GUDANG'])->group(function () {
        Route::post('surat-jalan', [SuratJalanController::class, 'create']);
        Route::put('surat-jalan/{surat_jalan_id}', [SuratJalanController::class, 'update']);
    });
    Route::middleware(['role:SITE_MANAGER,SUPERVISOR,ADMIN_GUDANG,LOGISTIC'])->group(function(){
        Route::get('surat-jalan/all', [SuratJalanController::class, 'getAllSuratJalanByUser']);
        Route::get('surat-jalan/active', [SuratJalanController::class, 'getSomeActiveSuratJalanByUser']);
        Route::get('surat-jalan/active/count', [SuratJalanController::class, 'getCountActiveSuratJalanByUser']);
        Route::get('surat-jalan/all/dalam-perjalanan', [SuratJalanController::class, 'getAllSuratJalanDalamPerjalananByUser']);
        Route::get('surat-jalan/{id}', [SuratJalanController::class, 'getSuratJalanById']);
    });
    Route::middleware(['role:LOGISTIC'])->group(function(){
        Route::get('kendaraan/logistic', [KendaraanController::class, 'getKendaraanByLogistic']);
    });
    Route::middleware(['role:SITE_MANAGER'])->group(function(){
        Route::post('proyek/store', [ProyekController::class, 'storeProyek']);
        Route::post('proyek/update/{id}', [ProyekController::class, 'updateProyek']);
        Route::post('aksesBarang/giveAccess/{id}', [BarangController::class, 'giveAksesPeminjamanSm']);
        Route::post('proyek/menangani/{id}', [ProyekController::class, 'addMenanganiProyek']);
        Route::get('proyek/delete/{id}', [ProyekController::class, 'deleteProyek']);
    });
    Route::middleware(['role:SITE_MANAGER,PROJECT_MANAGER'])->group(function(){
        Route::get('proyek/yang-dibuat', [ProyekController::class, 'proyekYangDibuat']);
    });
    Route::middleware(['role:SITE_MANAGER,SUPERVISOR'])->group(function(){
        Route::get('barang/tanggung-jawab', [BarangController::class, 'getBarangTanggungJawab']);
        Route::post('barang/scanQrCode', [BarangController::class, 'scanQrCode']);
        Route::get('barang/tidak-habis-pakai', [BarangController::class, 'getBarangTidakHabisPakai']);
        Route::get('barang/habis-pakai', [BarangController::class, 'barangHabisPakai']);
        Route::get('barang/tidak-habis-pakai/tersedia', [BarangController::class, 'barangTidakHabisPakaiTersedia']);
        Route::get('proyek/yang-dikerjakan', [ProyekController::class, 'proyekYangDiKerjakan']);
        Route::get('proyek/all', [ProyekController::class, 'proyekYangMempunyaiPeminjaman']);
        Route::get('barang/by-gudang/{id}', [BarangController::class, 'barangTidakHabisPakaiByGudang']);
        Route::get('barang/by-kode-peminjaman/{id}', [BarangController::class, 'barangTidakHabisPakaiByKodePeminjaman']);
        Route::get('proyek/{id}/kode-peminjaman', [BarangController::class, 'getKodePeminjamanByProyek']);
        Route::post('peminjaman/store', [PeminjamanController::class, 'storePeminjaman']);
        Route::get('peminjaman', [PeminjamanController::class, 'getPermintaanPeminjamanByUser']);
        Route::get('peminjaman/destroy/{peminjaman}', [PeminjamanController::class, 'destroyPeminjaman']);
        Route::get('gudang/all', [GudangController::class, 'allGudang']);
        Route::get('barang/tanggung-jawab/{peminjamanDetail}', [BarangController::class, 'getDetailPeminjaman']);
        // Route::get('barang/by-proyek/{proyek}', [BarangController::class, 'getBarangByProyek']);
        Route::get('barang/dipesan/aksesBarang', [BarangController::class, 'getAksesBarangByUser']);
        Route::get('barang/request/aksesBarang', [BarangController::class, 'getRequestAksesBarang']);
        Route::get('user/supervisorAndSiteManager', [UserController::class, 'getSupervisorAndSiteManager']);
    });
    Route::middleware(['role:SITE_MANAGER,SUPERVISOR,ADMIN_GUDANG,LOGISTIC,PURCHASING'])->group(function(){
        Route::get('barang/{barang}', [BarangController::class, 'getDetailBarang']);
    });
    Route::middleware(['role:PURCHASING,ADMIN_GUDANG,LOGISTIC'])->group(function(){
        Route::get('delivery-order/all', [DeliveryOrderController::class, 'getAllDeliveryOrderByUser']);
        Route::get('delivery-order/active', [DeliveryOrderController::class, 'getSomeActiveDeliveryOrderByUser']);
        Route::get('delivery-order/active/count', [DeliveryOrderController::class, 'getCountActiveDeliveryOrderByUser']);
        Route::get('delivery-order/all/dalam-perjalanan', [DeliveryOrderController::class, 'getAllDeliveryOrderDalamPerjalananByUser']);
        Route::get('delivery-order/{id}', [DeliveryOrderController::class, 'getDeliveryOrderById']);
    });
});

Route::post('login', [UserController::class, 'login']);
Route::post('loginPIN', [UserController::class, 'loginPIN']);
Route::post('forget-password',[UserController::class, 'forgetPassword']);
Route::post('register', [UserController::class, 'register']);

Route::get('province', [ProvinceController::class, 'getProvince']);
Route::get('city/{city}', [ProvinceController::class, 'getCityByProvince']);



