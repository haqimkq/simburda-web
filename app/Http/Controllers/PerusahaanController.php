<?php

namespace App\Http\Controllers;

use App\Models\AksesBarang;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerusahaanController extends Controller
{
    public function index(){
        $authUser = Auth::user();
        $countUndefinedAkses = AksesBarang::countUndefinedAkses();
        return view('perusahaan.index',[
            'authUser' => $authUser,
            'countUndefinedAkses' => $countUndefinedAkses,
            'perusahaans' => Perusahaan::all()
        ]    
        );
    }
}
