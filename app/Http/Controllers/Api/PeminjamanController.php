<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PeminjamanGp;
use App\Models\PeminjamanPp;
use App\Models\Proyek;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ResponseFormatter;
use App\Models\BarangTidakHabisPakai;
use App\Models\Menangani;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\AksesBarang;
use Exception;

class PeminjamanController extends Controller
{
    public function storePeminjaman(Request $request){
        try {
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
            return ResponseFormatter::success(null, null, 'success');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    public function getPermintaanPeminjamanByUser(Request $request){
        try {
            $user = $request->user();
            $json = [];
            $peminjamans = Peminjaman::whereRelation('menangani','user_id',$user->id)->get();
            foreach($peminjamans as $peminjaman){
                $peminjamanDetail = [];
                foreach ($peminjaman->peminjamanDetail as $data){
                    $barang = $data->barang->barang;
                    $barangA = [
                        'id' => $barang->id,
                        'merk' => $barang->merk,
                        'gambar' => $barang->gambar,
                        'detail' => $barang->detail,
                        'gudang' => $barang->gudang->nama ?? "",
                        'kondisi' => $data->barang->kondisi,
                        'nomor_seri' => $data->barang->nomor_seri,
                        'keterangan' => $data->barang->keterangan
                    ];
                    $pinjamanA = [
                        'id' => $peminjaman->id,
                        'nama_proyek' => $peminjaman->menangani->proyek->nama_proyek,
                        'kode_peminjaman' => $peminjaman->kode_peminjaman,
                        'tipe' => $peminjaman->tipe,
                        'tgl_peminjaman' => $peminjaman->getRemainingDaysAttribute()
                    ];
                    $detail = [
                        'id' => $data->id,
                        'status' => $data->status,
                        'barang' => $barangA,
                        'penanggung_jawab' => $data->penanggungJawab,
                        'peminjaman' => $pinjamanA
                    ];
                    array_push($json,$detail);
                }
            }
            return ResponseFormatter::success('data', $json, 'Success Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }
}
