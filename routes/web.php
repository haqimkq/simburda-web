<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

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
    Route::get('/login', 'index')->name('login')->middleware('guest');
    Route::post('/login', 'authenticate')->name('authenticate');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
});
Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'index')->name('register')->middleware('guest');
    Route::post('/register', 'store')->name('register-user')->middleware('guest');
});
Route::middleware(['auth'])->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/home', 'index')->name('home');
    });
});
