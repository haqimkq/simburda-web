<?php

namespace App\Http\Controllers;

use App\Models\AksesBarang;
use App\Models\Barang;
use App\Models\BarangTidakHabisPakai;
use App\Models\Gudang;
use App\Models\Menangani;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\PeminjamanGp;
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
        $allProyek = Proyek::all();
        $authUser = Auth::user();
        if($authUser->role == "ADMIN"){
            $proyeks = Proyek::all();
        }else{
            $menanganis = Menangani::where("user_id",Auth::id())->get();
            foreach($menanganis as $menangani){
                array_push($proyeks, $menangani->proyek);
            }
        }
        $datas = BarangTidakHabisPakai::whereNull('peminjaman_id')->orWhereHas('peminjamanDetail', function($query){
            $query->where('status','TIDAK_DIGUNAKAN');
        })->get(); 
        $gudangs = Gudang::all();
        return view('peminjaman.create',[
            'proyeks' => $proyeks,
            'barangs' => $datas,
            'gudangs' => $gudangs,
            'allProyek' => $allProyek
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
        if($request->tipe == 'GUDANG_PROYEK'){
            $validate = $request->validate([
                'proyek_id' => 'required',
                'tgl_peminjaman' => 'required',
                'tgl_berakhir' => 'required',
                'gudang_id' => 'required',
                'tipe' => 'required',
                'barang' => 'required'
            ]);
        }
        //membuat peminjaman
        // =>generate kode peminjaman
        $proyek = Proyek::where('id',$request->proyek_id)->first();
        $validate['kode_peminjaman'] = Peminjaman::generateKodePeminjaman($request->tipe,$proyek->client,Auth::user()->nama);
        $menangani = Menangani::where('proyek_id',$request->proyek_id)->where('user_id',Auth::id())->first();
        $validate['menangani_id'] = $menangani->id;
        $peminjaman = Peminjaman::create($validate);
        //Membuat Pembagian Peminjaman
        if($request->tipe == 'GUDANG_PROYEK'){
            $peminjamanGp['gudang_id'] = $request->gudang_id;
            $peminjamanGp['peminjaman_id'] = $peminjaman->id;
            PeminjamanGp::create($peminjamanGp);
        }else{
            $peminjamanPp['peminjaman_asal_id'] = $request->proyek_asal_id;
            $peminjamanPp['peminjaman_id'] = $peminjaman->id;
        }
        //membuat peminjaman detail
        foreach($request->barang as $barang){
            $peminjamanDetailData['barang_id']=$barang;
            $peminjamanDetailData['peminjaman_id']=$peminjaman->id;
            $peminjamanDetail = PeminjamanDetail::create($peminjamanDetailData);
            //membuat akses barang
            $aksesBarang['peminjaman_detail_id'] = $peminjamanDetail->id;
            AksesBarang::create($aksesBarang);
        }

        echo "Berhasil";

        // return redirect('peminjaman')->with('succes')
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
        $json=[];
        $barangs = Barang::where('jenis','TIDAK_HABIS_PAKAI')->where('gudang_id',$gudang->id)->get();
        foreach($barangs as $barang){
            $detail = [
                'id' => $barang->barangTidakHabisPakai->id,
                'gambar' => $barang->gambar,
                'nama' => $barang->nama,
                'detail' => $barang->detail,
                'jenis' => $barang->jenis,
                'kondisi' => $barang->barangTidakHabisPakai->kondisi
            ];
            array_push($json, $detail);
        }
        return response()->json($json);
    }

    public function getBarangByProyek(Proyek $proyek){
        $json=[];
        $barangs = BarangTidakHabisPakai::whereRelation('peminjaman.menangani.proyek','id',$proyek->id)->whereRelation('peminjamanDetail','status','TIDAK_DIGUNAKAN')->get();
        foreach($barangs as $barang){
            $detail = [
                'id' => $barang->barangTidakHabisPakai->id,
                'gambar' => $barang->gambar,
                'nama' => $barang->nama,
                'detail' => $barang->detail,
                'jenis' => $barang->jenis,
                'kondisi' => $barang->barangTidakHabisPakai->kondisi
            ];
            array_push($json, $detail);
        }
        return response()->json($json);
    }
}
?>