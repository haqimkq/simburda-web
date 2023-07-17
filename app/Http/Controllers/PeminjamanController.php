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
use App\Models\PeminjamanPp;
use App\Models\Proyek;
use Carbon\Carbon;
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
        $authUser = Auth::user();
        $countUndefinedAkses = AksesBarang::countUndefinedAkses();
        if($authUser->role=='SUPERVISOR'){
            $peminjamans = Peminjaman::whereRelation('menangani.user','id',$authUser->id)->filter(request(['search','orderBy','filter', 'datestart','dateend']))->paginate(12)->withQueryString();
        }else if ($authUser->role=='ADMIN_GUDANG'||$authUser->role=='ADMIN'||$authUser->role=='PROJECT_MANAGER'){
            $peminjamans = Peminjaman::filter(request(['search','orderBy','filter','datestart','dateend']))->paginate(12)->withQueryString();
        }else{
            $proyeks_id = Menangani::getProyekIdFromUser($authUser->id);
            $peminjamans = Peminjaman::whereHas('menangani', fn($q) => $q->whereIn('proyek_id', $proyeks_id))->filter(request(['search','orderBy','filter','datestart','dateend']))->paginate(12)->withQueryString();
        }
        return view('peminjaman.index',[
            'peminjamans' => $peminjamans,
            'authUser' => $authUser,
            'countUndefinedAkses' => $countUndefinedAkses,
        ]);
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
            $proyeks = Proyek::where('selesai',false)->get();
        }else{
            $menanganis = Menangani::where("user_id",Auth::id())->get();
            foreach($menanganis as $menangani){
                if(!$menangani->proyek->selesai){
                    array_push($proyeks, $menangani->proyek);
                }
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
        }else{
            $validate = $request->validate([
                'proyek_id' => 'required',
                'tgl_peminjaman' => 'required',
                'tgl_berakhir' => 'required',
                'peminjaman_asal_id' => 'required',
                'tipe' => 'required',
                'barang' => 'required'
            ]);
        }
        //membuat peminjaman
        // =>generate kode peminjaman
        $validate['tgl_peminjaman'] = Carbon::parse($validate['tgl_peminjaman']);
        $validate['tgl_berakhir'] = Carbon::parse($validate['tgl_berakhir']);
        $proyek = Proyek::where('id',$request->proyek_id)->first();
        $validate['kode_peminjaman'] = Peminjaman::generateKodePeminjaman($request->tipe,$proyek->client,Auth::user()->nama);
        $menangani = Menangani::where('proyek_id',$request->proyek_id)->where('user_id',Auth::id())->first();
        $validate['menangani_id'] = $menangani->id;
        $peminjaman = Peminjaman::create($validate);
        $peminjamanPP = '';
        //Membuat Pembagian Peminjaman
        if($request->tipe == 'GUDANG_PROYEK'){
            $peminjamanGp['gudang_id'] = $request->gudang_id;
            $peminjamanGp['peminjaman_id'] = $peminjaman->id;
            PeminjamanGp::create($peminjamanGp);
        }else{
            //get id peminjaman asal
            $peminjamanPp['peminjaman_asal_id'] = $request->peminjaman_asal_id;
            $peminjamanPp['peminjaman_id'] = $peminjaman->id;
            $peminjamanPP = PeminjamanPp::create($peminjamanPp);
        }
        //membuat peminjaman detail
        foreach($request->barang as $barang){
            //memperbaharui data barang asal
            if($request->tipe =='PROYEK_PROYEK'){
                PeminjamanDetail::where('peminjaman_id', $request->peminjaman_asal_id)->where('barang_id',$barang)->update(['peminjaman_proyek_lain_id'=>$peminjamanPP->id]);
            }
            //membuat peminjaman Detail
            $peminjamanDetailData['barang_id']=$barang;
            $peminjamanDetailData['peminjaman_id']=$peminjaman->id;
            $peminjamanDetail = PeminjamanDetail::create($peminjamanDetailData);
            //membuat akses barang
            $aksesBarang['peminjaman_detail_id'] = $peminjamanDetail->id;
            AksesBarang::create($aksesBarang);
            //merubah status pada barang tidak Habis Pakai
            BarangTidakHabisPakai::where('id', $barang)->update(['status' => 'DIPESAN','peminjaman_id'=>$peminjaman->id]);
        }

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
        $barangs = Barang::where('jenis','TIDAK_HABIS_PAKAI')->whereRelation('barangTidakHabisPakai','peminjaman_id',null)->whereRelation('barangTidakHabisPakai','status',"TERSEDIA")->where('gudang_id',$gudang->id)->get();
        foreach($barangs as $barang){
            $detail = [
                'id' => $barang->barangTidakHabisPakai->id,
                'gambar' => $barang->gambar,
                'nama' => $barang->nama,
                'detail' => $barang->detail,
                'nomor_seri' => $barang->barangTidakHabisPakai->nomor_seri,
                'merk' => $barang->merk,
                'jenis' => $barang->jenis,
                'kondisi' => $barang->barangTidakHabisPakai->kondisi
            ];
            array_push($json, $detail);
        }
        return response()->json($json);
    }

    public function selectKodePeminjaman(Request $request){
        $kodePeminjaman = [];
        $search = $request->q;
        $kodePeminjaman = Peminjaman::select("id", "kode_peminjaman")
                ->whereRelation('menangani', 'proyek_id', $request->proyek_id)
                ->where('kode_peminjaman', 'LIKE', "%$search%")
                ->get();
        return response()->json($kodePeminjaman);
    }

    public function getBarangByKodePeminjaman($kode_peminjaman){
        $json=[];
        $peminjaman = Peminjaman::where('id', $kode_peminjaman)->first();
        foreach($peminjaman->peminjamanDetail as $detailBarang){
            $barang = $detailBarang->barang;
            $detail = [
                'id' => $barang->id,
                'gambar' => $barang->barang->gambar,
                'nama' => $barang->barang->nama,
                'detail' => $barang->barang->detail,
                'jenis' => $barang->barang->jenis,
                'nomor_seri' => $barang->nomor_seri,
                'merk' => $barang->barang->merk,
                'kondisi' => $barang->kondisi,
                'peminjaman_id' => $barang->peminjaman_id
            ];
            array_push($json, $detail);
        }
        return response()->json($json);
    }
}
?>