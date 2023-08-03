<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Exception;
use App\Models\Gudang;

class GudangController extends Controller
{
    public function allGudang(){
        try{
            $data = Gudang::all();
            return ResponseFormatter::success('data', $data, 'Data Berhasil Didapat');
        }catch (Exception $error) {
            return ResponseFormatter::error("Server Error:". $error->getMessage());
        }
    }
}
