<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProvincesFirebase;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function getProvince(){
        $province = ProvincesFirebase::getProvince();
        return response()->json($province);
    }
}
