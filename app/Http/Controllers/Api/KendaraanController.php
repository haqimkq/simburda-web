<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Exception;

class KendaraanController extends Controller
{
    public function getKendaraanByLogistic(Request $request)
    {
        try{
            $user = $request->user();
            
            $response = Kendaraan::getKendaraanByLogistic($user->id);

            $message = ($response->isEmpty()) ? 'Tidak ada kendaraan' : 'Berhasil Mendapatkan Kendaraan';
            $result = ($response->isEmpty()) ? null : $response;
            return ResponseFormatter::success('kendaraaan',$result,$message);
        }catch(Exception $e){
            return ResponseFormatter::error("Gagal Mendapatkan Kendaraan: ". $e->getMessage());
        }
    }
}
