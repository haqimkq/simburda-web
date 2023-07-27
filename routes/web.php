<?php

use App\Http\Controllers\AksesBarangController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DeliveryOrderController;
use App\Http\Controllers\Firebase\LogisticController;
use App\Http\Controllers\GudangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\SuratJalanController;
use App\Http\Controllers\PerusahaanController;

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
    Route::controller(LogisticController::class)->group(function () {
        Route::get('firebase/create', 'create')->name('firebase.create');
        Route::get('firebase/store', 'store')->name('firebase.store');
        Route::get('firebase', 'index')->name('firebase');
    });
    Route::controller(HomeController::class)->group(function () {
        Route::get('home', 'index')->name('home');
    });
    Route::middleware(['role:ADMIN_GUDANG,PURCHASING'])->controller(BarangController::class)->group(function () {
        Route::get('barang', 'index')->name('barang');
        Route::get('barang/tambah', 'create')->name('barang.create');
        Route::post('barang/store', 'store')->name('barang.store');
        Route::get('barang/detail/{barang}', 'show')->name('barang.show');
        Route::post('barang/tambah-seri-baru/{nama}', 'tambahSeriBaru')->name('barang.tambahSeriBaru');
        Route::get('barang/seri/{nama}', 'showNamaBarang')->name('barang.seri');
        Route::get('barang/edit/{barang}', 'edit')->name('barang.edit');
        Route::post('barang/delete/{id}', 'destroy')->name('barang.destroy');
        Route::post('barang/update/{id}', 'update')->name('barang.update');
    });
    Route::middleware((['role:ADMIN_GUDANG,PURCHASING']))->controller(PerusahaanController::class)->group(function (){
        Route::get('perusahaan', 'index')->name('perusahaan');
        Route::get('perusahaan/tambah', 'create')->name('perusahaan.create');
        Route::post('perusahaan/store', 'store')->name('perusahaan.store');
        Route::get('perusahaan/edit/{perusahaan}', 'edit')->name('perusahaan.edit');
        Route::post('perusahaan/update/{perusahaan}', 'update')->name('perusahaan.update');
        Route::get('perusahaan/detail/{id}', 'show')->name('perusahaan.show');
        Route::post('perusahaan/delete/{perusahaan}', 'destroy')->name('perusahaan.destroy');
    });
    Route::middleware(['role:ADMIN_GUDANG,SUPERVISOR,SITE_MANAGER,PROJECT_MANAGER'])->controller(PeminjamanController::class)->group(function(){
        Route::get('peminjaman', 'index')->name('peminjaman');
        Route::get('peminjaman/tambah', 'create')->name('peminjaman.create');
        Route::post('peminjaman/store', 'store')->name('peminjaman.store');
        Route::get('peminjaman/edit/{peminjaman}', 'edit')->name('peminjaman.edit');
        Route::post('peminjaman/update/{peminjaman}', 'update')->name('peminjaman.update');
        Route::get('peminjaman/detail/{id}', 'show')->name('peminjaman.show');
        Route::post('peminjaman/delete/{peminjaman}', 'destroy')->name('peminjaman.destroy');
        Route::get('peminjaman/tambah/barangByGudang/{gudang}', 'getBarangByGudang');
        Route::get('peminjaman/tambah/barangByProyek/{proyek}', 'getBarangByProyek');
        Route::get('peminjaman/tambah/barangByKodePeminjaman/{kode_peminjaman}', 'getBarangByKodePeminjaman');
        Route::get('selectKodePeminjaman', 'selectKodePeminjaman')->name('selectKodePeminjaman');
    });
    Route::middleware(['role:ADMIN_GUDANG,SUPERVISOR,SITE_MANAGER,PROJECT_MANAGER'])->controller(PengembalianController::class)->group(function(){
        Route::get('pengembalian', 'index')->name('pengembalian');
        Route::get('pengembalian/tambah', 'create')->name('pengembalian.create');
        Route::post('pengembalian/store', 'store')->name('pengembalian.store');
        Route::get('pengembalian/edit/{pengembalian}', 'edit')->name('pengembalian.edit');
        Route::post('pengembalian/update/{pengembalian}', 'update')->name('pengembalian.update');
        Route::get('pengembalian/detail/{id}', 'show')->name('pengembalian.show');
        Route::post('pengembalian/delete/{pengembalian}', 'destroy')->name('pengembalian.destroy');
    });
    Route::middleware(['role:ADMIN'])->controller(PenggunaController::class)->group(function () {
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
        Route::get('signature/verified-sj/{id}', 'verifiedTTDSuratJalan')->name('signature.verifiedTTDSuratJalan');
        Route::get('signature/verified-sj/view/{id}', 'viewTTDSuratJalan')->name('signature.viewTTDSuratJalan');
        Route::get('signature/verified-do/{id}', 'verifiedTTDDeliveryOrder')->name('signature.verifiedTTDDeliveryOrder');
        Route::get('signature/verified-do/view/{id}', 'viewTTDDeliveryOrder')->name('signature.viewTTDDeliveryOrder');
    });
    Route::middleware(['role:ADMIN_GUDANG,PURCHASING'])->controller(KendaraanController::class)->group(function () {
        Route::get('kendaraan', 'index')->name('kendaraan');
        Route::get('kendaraan/tambah', 'create')->name('kendaraan.create');
        Route::post('kendaraan/store', 'store')->name('kendaraan.store');
        Route::get('kendaraan/detail/{id}', 'show')->name('kendaraan.show');
        Route::get('kendaraan/edit/{kendaraan}', 'edit')->name('kendaraan.edit');
        Route::post('kendaraan/delete/{kendaraan}', 'destroy')->name('kendaraan.destroy');
        Route::post('kendaraan/update/{kendaraan}', 'update')->name('kendaraan.update');
        Route::get('kendaraan/logistic/{logistic_id}', 'getKendaraanByLogistic')->name('kendaraan.getKendaraanByLogistic');
        
    });
    Route::middleware(['role:ADMIN_GUDANG,PURHASING'])->controller(GudangController::class)->group(function () {
        Route::get('gudang', 'index')->name('gudang');
        Route::get('gudang/tambah', 'create')->name('gudang.create');
        Route::post('gudang/store', 'store')->name('gudang.store');
        Route::get('gudang/detail/{gudang}', 'show')->name('gudang.show');
        Route::get('gudang/edit/{gudang}', 'edit')->name('gudang.edit');
        Route::post('gudang/delete/{gudang}', 'destroy')->name('gudang.destroy');
        Route::post('gudang/update/{gudang}', 'update')->name('gudang.update');
    });
    Route::middleware(['role:ADMIN_GUDANG,SITE_MANAGER,SUPERVISOR,PROJECT_MANAGER'])->controller(ProyekController::class)->group(function () {
        Route::get('proyek', 'index')->name('proyek');
        Route::get('proyek/detail/{id}', 'show')->name('proyek.show');
    });
    Route::middleware(['role:SITE_MANAGER'])->controller(ProyekController::class)->group(function () {
        Route::get('proyek/tambah', 'create')->name('proyek.create');
        Route::post('proyek/store', 'store')->name('proyek.store');
        Route::get('proyek/detail/{id}', 'show')->name('proyek.show');
        Route::get('proyek/edit/{proyek}', 'edit')->name('proyek.edit');
        Route::post('proyek/delete/{id}', 'destroy')->name('proyek.destroy');
        Route::post('proyek/update/{id}', 'update')->name('proyek.update');
        Route::get('selectSetManager', 'selectSetManager')->name('selectSetManager');
    });
    Route::middleware(['role:PURCHASING'])->controller(DeliveryOrderController::class)->group(function () {
        Route::get('delivery-order/tambah', 'createStepOne')->name('delivery-order.createStepOne');
        Route::post('delivery-order/tambah', 'storeCreateStepOne')->name('delivery-order.storeCreateStepOne');
        Route::get('delivery-order/tambah-pre-order', 'createStepTwo')->name('delivery-order.createStepTwo');
        Route::post('delivery-order/tambah-pre-order', 'storeCreateStepTwo')->name('delivery-order.storeCreateStepTwo');
        Route::get('delivery-order/edit/{id}', 'edit')->name('delivery-order.edit');
        Route::post('delivery-order/delete/{id}', 'destroy')->name('delivery-order.destroy');
    });
    Route::middleware(['role:ADMIN_GUDANG,PURCHASING,LOGISTIC'])->controller(DeliveryOrderController::class)->group(function () {
        Route::get('delivery-order', 'index')->name('delivery-order');
        Route::get('delivery-order/detail/{id}', 'show')->name('delivery-order.show');
        Route::get('delivery-order/cetak/{id}', 'cetak')->name('delivery-order.cetak');
        Route::get('delivery-order/download-pdf/{id}', 'downloadPDF')->name('delivery-order.downloadPDF');
    });
    Route::middleware(['role:ADMIN_GUDANG,SITE_MANAGER'])->controller(AksesBarangController::class)->group(function () {
        Route::get('akses-barang', 'index')->name('akses-barang');
        Route::get('akses-barang/tambah', 'create')->name('akses-barang.create');
        Route::post('akses-barang/store', 'store')->name('akses-barang.store');
        Route::get('akses-barang/detail/{id}', 'show')->name('akses-barang.show');
        Route::get('akses-barang/edit/{id}', 'edit')->name('akses-barang.edit');
        Route::post('akses-barang/delete/{id}', 'destroy')->name('akses-barang.destroy');
    });
    Route::middleware(['role:ADMIN_GUDANG,LOGISTIC,SITE_MANAGER,PROJECT_MANAGER,SUPERVISOR'])->controller(SuratJalanController::class)->group(function () {
        Route::get('surat-jalan', 'index')->name('surat-jalan');
        Route::get('surat-jalan/detail/{id}', 'show')->name('surat-jalan.show');
        Route::get('surat-jalan/download-pdf/{id}', 'downloadPDF')->name('surat-jalan.downloadPDF');
        Route::get('surat-jalan/cetak/{id}', 'cetak')->name('surat-jalan.cetak');
    });
    Route::middleware(['role:ADMIN_GUDANG'])->controller(SuratJalanController::class)->group(function () {
        Route::get('surat-jalan/tambah', 'create')->name('surat-jalan.create');
        Route::post('surat-jalan/store', 'store')->name('surat-jalan.store');
        Route::get('surat-jalan/download-pdf/{id}', 'downloadPDF')->name('surat-jalan.downloadPDF');
        Route::get('surat-jalan/cetak/{id}', 'cetak')->name('surat-jalan.cetak');    
    });
});

Route::get('reset-password/{token}/{email}', [UserController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [UserController::class, 'submitResetPasswordForm'])->name('reset.password.post');