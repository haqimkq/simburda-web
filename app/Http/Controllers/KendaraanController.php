<?php

namespace App\Http\Controllers;

use App\Models\AksesBarang;
use App\Models\Gudang;
use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KendaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kendaraan = Kendaraan::with('user', 'user.logistic')->filter(request(['search', 'filter', 'orderBy']))->paginate(12)->withQueryString();
        $countUndefinedAkses = AksesBarang::countUndefinedAkses();
        $authUser = Auth::user();
        return view('kendaraan.index',[
            'countUndefinedAkses' => $countUndefinedAkses,
            'allKendaraan' => $kendaraan,
            'authUser' => $authUser
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kendaraan.create',[
            'gudangs' => Gudang::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'jenis' => 'required',
            'merk' => 'required',
            'plat_nomor' => 'required',
            'gambar' => 'nullable',
            'gudang_id' => 'nullable',
        ]);

        if($request->file('gambar')){
                $validate['gambar'] = $request->file('gambar')->store('assets/kendaraan', 'public');
        }

        $kendaraan = Kendaraan::create($validate);

        return redirect()->route('kendaraan')->with('createKendaraanSuccess','Berhasil Menambahkan Kendaraan ('.$kendaraan->merk.')');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\kendaraan  $kendaraan
     * @return \Illuminate\Http\Response
     */
    public function show(kendaraan $kendaraan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\kendaraan  $kendaraan
     * @return \Illuminate\Http\Response
     */
    public function edit(Kendaraan $kendaraan)
    {
        return view('kendaraan.edit',[
            'gudangs' => Gudang::all(),
            'kendaraan' => $kendaraan
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatekendaraanRequest  $request
     * @param  \App\Models\kendaraan  $kendaraan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, kendaraan $kendaraan)
    {
        $validate = $request->validate([
            'jenis' => 'required',
            'merk' => 'required',
            'plat_nomor' => 'required',
            'gambar' => 'nullable',
            'gudang_id' => 'nullable',
        ]);

        if($request->file('gambar')){
            if($request->oldImage){
                Storage::delete($request->oldImage);
            }
            $validate['gambar'] = $request->file('gambar')->store('assets/kendaraan', 'public');
        }

        Kendaraan::where('id',$kendaraan->id)->update($validate);

        return redirect()->route('kendaraan')->with('createKendaraanSuccess','Berhasil Merubah Data Kendaraan ('.$kendaraan->merk.')');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\kendaraan  $kendaraan
     * @return \Illuminate\Http\Response
     */
    public function destroy(kendaraan $kendaraan)
    {
        if($kendaraan->gambar){
            Storage::delete($kendaraan->gambar);
        }
        Kendaraan::destroy($kendaraan->id);

        return redirect()->route('kendaraan')->with('deleteKendaraanSuccess', 'Berhasil Menghapus Kendaraan ('.$kendaraan->merk.')');
    }
    public function getKendaraanByLogistic($logistic_id){
        return Kendaraan::getKendaraanByLogistic($logistic_id);
    }
}