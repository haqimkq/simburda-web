<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangTidakHabisPakai;
use App\Models\Gudang;
use App\Models\Menangani;
use App\Models\Peminjaman;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('peminjaman.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $proyeks = [];
        $authUser = Auth::user();
        if($authUser->role = "ADMIN"){
            $proyeks = Proyek::all();
        }else{
            $menanganis = Menangani::where("user_id",Auth::id())->get();
            foreach($menanganis as $menangani){
                array_push($proyek, $menangani->proyek);
            }
        }
        $datas = BarangTidakHabisPakai::whereNull('peminjaman_id')->orWhereHas('peminjamanDetail', function($query){
            $query->where('status','TIDAK_DIGUNAKAN');
        })->get(); 
        $gudangs = Gudang::all();
        return view('peminjaman.create',[
            'proyeks' => $proyeks,
            'barangs' => $datas,
            'gudangs' => $gudangs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'proyek' => 'required',
            'tgl_peminjaman' => 'required',
            'tgl_berakhir' => 'required',
            'barang' => 'required'
        ],[
            'proyek.required' => 'Proyek wajib diisi',
            'tgl_peminjaman.required' => 'Tanggal Peminjaman Wajib diisi',
            'tgl_berakhir.required' => 'Tanggal Berakhir Wajib diisi',
            'barang.required' => 'Barang Wajib diisi'
        ]);
        //membuat peminjaman
        // =>generate kode peminjaman
        // $validate['kode_peminjaman'] = Peminjaman::generateKodePeminjaman();
        Peminjaman::create($validate);
        //membuat peminjaman detail
        //membuat akses barang
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('peminjaman.detail');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('peminjaman.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getBarangByGudang(Gudang $gudang){
        return response()->json(Barang::where('jenis','TIDAK_HABIS_PAKAI')->where('gudang_id',$gudang->id)->get());
    }
}
?>