<?php

namespace App\Http\Controllers;

use App\Models\AksesBarang;
use App\Models\Perusahaan;
use App\Models\ProvincesFirebase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerusahaanController extends Controller
{
    public function index(){
        $authUser = Auth::user();
        $countUndefinedAkses = AksesBarang::countUndefinedAkses();
        $perusahaan = Perusahaan::filter(request(['search', 'filter', 'orderBy']))->paginate(12)->withQueryString();
        $provinsis = Perusahaan::groupBy('provinsi')->get('provinsi')->all();
        $countUndefinedAkses = AksesBarang::countUndefinedAkses();
        return view('perusahaan.index',[
            'authUser' => $authUser,
            'countUndefinedAkses' => $countUndefinedAkses,
            'provinsis' => $provinsis,
            'perusahaans' => $perusahaan
        ]);
    }

    public function create(){
        $province = collect(ProvincesFirebase::getProvince());
        return view('perusahaan.create',[
            'provinces' => $province
        ]);
    }
}
