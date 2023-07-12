<?php

namespace App\Http\Controllers;

use App\Models\AksesBarang;
use App\Models\Perusahaan;
use App\Models\ProvincesFirebase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'provinces' => $province->keys()
        ]);
    }

    public function store(Request $request){
        $validate = $request->validate([
            'nama' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'gambar' => 'nullable',
            'alamat' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if($request->file('gambar')){
            $validate['gambar'] = $request->file('gambar')->store('assets/perusahaan', 'public');
        }

        $perusahaan = Perusahaan::create($validate);

        return redirect('perusahaan')->with('createPerusahaaanSuccess','Berhasil Menambah Perusahaan ('. $perusahaan->nama.')');
    }

    public function edit(Perusahaan $perusahaan){    
        $province = collect(ProvincesFirebase::getProvince());
        return view('perusahaan.edit',[
            'provinces' => $province->keys(),
            'perusahaan' => $perusahaan
        ]);
    }

    public function update(Request $request, Perusahaan $perusahaan){
        $validate = $request->validate([
            'nama' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'gambar' => 'nullable',
            'alamat' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if($request->file('gambar')){
            if($request->oldImage){
                Storage::delete($request->oldImage);
            }
            $validate['gambar'] = $request->file('gambar')->store('assets/perusahaan', 'public');
        }

        Perusahaan::where('id',$perusahaan->id)->update($validate);

        return redirect('perusahaan')->with('createPerusahaaanSuccess','Berhasil Merubah Perusahaan ('. $perusahaan->nama.')');
    }

    public function destroy(Perusahaan $perusahaan){
        if($perusahaan->gambar){
            Storage::delete($perusahaan->gambar);
        }
        Perusahaan::destroy($perusahaan->id);

        return redirect('perusahaan')->with('deletePerusahaaanSuccess','Berhasil Menghapus Perusahaan ('. $perusahaan->nama.')');
    }
}
