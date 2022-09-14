<?php

use App\Http\Controllers\AksesBarangController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DeliveryOrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SignatureController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'index')->name('login')->middleware('guest');
    Route::post('login', 'authenticate')->name('authenticate');
    Route::post('logout', 'logout')->name('logout')->middleware('auth');
});
Route::controller(RegisterController::class)->group(function () {
    Route::get('register', 'index')->name('register')->middleware('guest');
    Route::post('register', 'store')->name('register-user')->middleware('guest');
});
Route::middleware(['auth'])->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('home', 'index')->name('home');
    });
    Route::middleware(['admin-admingudang'])->controller(BarangController::class)->group(function () {
        Route::get('barang', 'index')->name('barang');
        Route::get('barang/tambah', 'create')->name('barang.create');
        Route::post('barang/store', 'store')->name('barang.store');
        Route::get('barang/detail/{id}', 'show')->name('barang.show');
        Route::post('barang/tambah-seri-baru/{nama}', 'tambahSeriBaru')->name('barang.tambahSeriBaru');
        Route::get('barang/seri/{nama}', 'showNamaBarang')->name('barang.seri');
        Route::get('barang/edit/{id}', 'edit')->name('barang.edit');
        Route::post('barang/delete/{id}', 'destroy')->name('barang.destroy');
    });
    Route::middleware(['admin'])->controller(PenggunaController::class)->group(function () {
        Route::get('pengguna', 'index')->name('pengguna');
        Route::get('pengguna/tambah', 'create')->name('pengguna.create');
        Route::post('pengguna/store', 'store')->name('pengguna.store');
        Route::get('pengguna/edit/{id}', 'edit')->name('pengguna.edit');
        Route::get('/signature', 'signature')->name('pengguna.signature');
        Route::post('pengguna/update/{id}', 'update')->name('pengguna.update');
        Route::get('pengguna/detail/{id}', 'show')->name('pengguna.show');
        Route::post('pengguna/delete/{id}', 'destroy')->name('pengguna.destroy');
    });
    Route::controller(SignatureController::class)->group(function () {
        Route::get('signature', 'index')->name('signature');
        Route::post('signature/store', 'store')->name('signature.store');
    });
    Route::middleware(['admin-admingudang'])->controller(KendaraanController::class)->group(function () {
        Route::get('kendaraan', 'index')->name('kendaraan');
        Route::get('kendaraan/tambah', 'create')->name('kendaraan.create');
        Route::post('kendaraan/store', 'store')->name('kendaraan.store');
        Route::get('kendaraan/detail/{id}', 'show')->name('kendaraan.show');
        Route::get('kendaraan/edit/{id}', 'edit')->name('kendaraan.edit');
        Route::post('kendaraan/delete/{id}', 'destroy')->name('kendaraan.destroy');
    });
    Route::middleware(['admin-projectmanager'])->controller(ProyekController::class)->group(function () {
        Route::get('proyek', 'index')->name('proyek');
        Route::get('proyek/tambah', 'create')->name('proyek.create');
        Route::post('proyek/store', 'store')->name('proyek.store');
        Route::get('proyek/detail/{id}', 'show')->name('proyek.show');
        Route::get('proyek/edit/{id}', 'edit')->name('proyek.edit');
        Route::post('proyek/delete/{id}', 'destroy')->name('proyek.destroy');
        Route::get('selectProyekManager', 'selectProyekManager')->name('selectProyekManager');
    });
    Route::middleware(['admin-purchasing-admingudang-logistic'])->controller(DeliveryOrderController::class)->group(function () {
        Route::get('delivery-order', 'index')->name('delivery-order');
        Route::get('delivery-order/tambah', 'create')->name('delivery-order.create');
        Route::post('delivery-order/store', 'store')->name('delivery-order.store');
        Route::get('delivery-order/detail/{id}', 'show')->name('delivery-order.show');
        Route::get('delivery-order/cetak/{id}', 'cetak')->name('delivery-order.cetak');
        Route::get('delivery-order/download-pdf/{id}', 'downloadPDF')->name('delivery-order.downloadPDF');
        Route::get('delivery-order/edit/{id}', 'edit')->name('delivery-order.edit');
        Route::post('delivery-order/delete/{id}', 'destroy')->name('delivery-order.destroy');
    });
    Route::middleware(['admin-projectmanager-supervisor-admingudang'])->controller(AksesBarangController::class)->group(function () {
        Route::get('akses-barang', 'index')->name('akses-barang');
        Route::get('akses-barang/tambah', 'create')->name('akses-barang.create');
        Route::post('akses-barang/store', 'store')->name('akses-barang.store');
        Route::get('akses-barang/detail/{id}', 'show')->name('akses-barang.show');
        Route::get('akses-barang/edit/{id}', 'edit')->name('akses-barang.edit');
        Route::post('akses-barang/delete/{id}', 'destroy')->name('akses-barang.destroy');
    });
});
