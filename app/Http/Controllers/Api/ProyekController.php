<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Proyek;
use Exception;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    public function proyekYangDiKerjakan(Request $request){
        try {
            $json = [];
            $user = $request->user();
            $datas = $user->proyeks;
            return ResponseFormatter::success('data', $datas, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }
}
