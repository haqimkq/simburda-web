<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function index(){
        return view('auth.register');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'companycode' => 'required|string',
                'no_hp' => 'required|numeric|min:11'
            ],[
                'email.required' => 'Email wajib diisi',
                'nama.required' => 'Nama wajib diisi',
                'password.required' => 'Password wajib diisi',
                'no_hp.min' => 'Nomor hp minimal 11 digit',
                'companycode.required' => 'Kode Perusahaan wajib diisi',
                'email.unique' => 'Email sudah terdaftar',
                'no_hp.numeric' => 'Nomor hp berupa angka',
            ]
        );
        if ($validator->fails()) {
            return redirect('register')
                ->withErrors($validator)
                ->with('registerError', 'Gagal Daftar!')
                ->withInput();
        }
        $companyHash = Hash::make(env('COMPANY_CODE','SimBurdaWeb'));
        $isSame = Hash::check($request['companycode'], $companyHash);
        if(!$isSame){
            return redirect('register')
                ->with('registerError', 'Register Gagal!')
                ->withInput();
        }
        $request['password'] = Hash::make($request['password']);
        
        User::create($request->all());

        return redirect('login')->with('registerSuccess', 'Akun Berhasil Terdaftar, Silahkan Login!');
    }
}
