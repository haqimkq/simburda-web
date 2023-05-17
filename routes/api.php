<?php
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
});

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);


