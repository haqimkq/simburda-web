<?php

use App\Models\User;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//get User by Name
Route::get('/user/{name}', function($name){
    // if(User::where('nama', $name)->get()){
    //     $data['status_message'] = 'success';
    //     $data['status_code'] = 200;
    // }else{
    //     $data['status_message'] = 'Invalid Username: You must be granted a valid Username.';
    //     $data['status_code'] = 404;
    // }
    $data['datas'] = User::where('nama', $name)->get();
    return User::where('nama', $name)->get();
});

//get stuff by user name
Route::get('/user/{name}/stuff',function($name){
    $barang = [];
    $meminjams = User::where('nama', $name)->first()->meminjam;
    foreach($meminjams as $meminjam){
        array_push($barang, Barang::where('id', $meminjam->barang_id)->first());
    }
    return $barang;
});

//get all stuff
Route::get('/stuffs',function(){
    return Barang::all();
});

//get projet of user
Route::get('/user/{name}/proyek',function($name){
    User::where('nama',$name)->first()->proyek;
});

//check stuff permission
Route::get('/stuff/{id}/permission', function($id){
    Barang::where('id',$id)->meminjam->user;
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('user/update', [UserController::class, 'updateProfile']);
    Route::post('user/photo', [UserController::class, 'updatePhoto']);
    Route::post('logout', [UserController::class, 'logout']);
});

Route::post('login', [UserController::class, 'login']);
