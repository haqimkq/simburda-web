<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProvincesFirebase;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function getProvince(){
        $city = collect(ProvincesFirebase::getProvince());
        return $city->keys();
    }

    public function getCityByProvince($city){
        $city = ProvincesFirebase::getCityByProvince(new Request(['provinsi' => $city]));
        return response()->json($city);
    }
}
