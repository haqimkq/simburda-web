<?php

use App\Models\User;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Models\AksesBarang;
use App\Models\Meminjam;
use App\Models\Menangani;
use App\Models\Pengajuan;
use App\Models\Proyek;
use App\Models\SuratJalan;

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
/*
|--------------------------------------------------------------------------
| Supervisor API Routes
|--------------------------------------------------------------------------
*/

//get User by Name
Route::get('/user/{name}', function($name){
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
    return User::where('nama',$name)->first()->proyek;
});

//check stuff permission
Route::get('/stuff/{id}/permission', function($id){
    return Barang::where('id',$id)->first()->meminjam->user;
});

//post stuff condition
Route::post('/stuff/{id}', function($id, Request $request){
    $validateData = $request->validate([
        'bagus' => 'required',
        'kondisi' => 'required_if:bagus,false'
    ]);

    return Barang::where('id',$id)->update($validateData);
});

/*
|--------------------------------------------------------------------------
| project Manager API Routes
|--------------------------------------------------------------------------
*/

//get permision request
Route::get('/projectManager/{id}/permissions', function($id){
    $permissions = [];
    $borrows = [];
    $supervises = User::where('id',$id)->first()->mengawasi;
    //mengambil data peminjaman
    foreach($supervises as $supervise){
        foreach(Meminjam::where('proyek_id',$supervise->id)->get() as $borrow)
        array_push($borrows, $borrow);
    }
    // mengambil akses barang
    foreach ($borrows as $borrow){
        foreach(AksesBarang::where('meminjam_id',$borrow->id)->where('disetujui_pm',null)->get() as $permission){
            array_push($permissions,$permission);
        }
    }
    return $permissions;
});

//post project
Route::post('/proyek',function(Request $request){
    $validateData = $request->validate([
        'proyek_manager_id' => 'required',
        'nama_proyek' => 'required',
        'alamat' => 'required',
        'latitude' => 'required',
        'longitude' => 'required'
    ]);

    return Proyek::create($validateData);
});

//Get Pengajuan
Route::get('/pengajuanPembelian',function(){
    return Pengajuan::where('disetujui',null)->get();
});

//post menangani
Route::post('/proyek/{id}/menangani',function($id ,Request $request){
    $validateData = $request->validate([
        'supervisor_id' => 'required|integer'
    ]);
    
    $validateData['proyek_id'] = (int)$id;
    return Menangani::create($validateData);
});

/*
|--------------------------------------------------------------------------
| User API Routes
|--------------------------------------------------------------------------
*/


Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('user/update', [UserController::class, 'updateProfile']);
    Route::post('user/update-photo', [UserController::class, 'updatePhoto']);
    Route::post('logout', [UserController::class, 'logout']);
});

Route::post('login', [UserController::class, 'login']);
