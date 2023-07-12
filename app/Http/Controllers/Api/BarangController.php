<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\BarangHabisPakai;
use App\Models\BarangTidakHabisPakai;
use App\Models\PeminjamanDetail;
use Illuminate\Http\Request;
use Exception;

class BarangController extends Controller
{
    public function getBarangTanggungJawab(Request $request){
        try {
            $user = $request->user();
            $datas = PeminjamanDetail::where('penanggung_jawab_id', $user->id)->where(function($query){
                $query->where('status','DIGUNAKAN')->orWhere('status','TIDAK_DIGUNAKAN');
            })->get();
            $json = [];
            foreach ($datas as $data){
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
                    'id' => $data->peminjaman->id,
                    'project' => $data->peminjaman->menangani->proyek->nama_proyek,
                    'kode_peminjaman' => $data->peminjaman->kode_peminjaman,
                    'tipe' => $data->peminjaman->tipe,
                    'tgl_peminjaman' => $data->peminjaman->tgl_peminjaman
                ];
                $detail = [
                    'id' => $data->id,
                    'status' => $data->id,
                    'barang' => $barangA,
                    'penanggung_jawab' => $data->penanggungJawab,
                    'peminjaman' => $pinjamanA
                ];
                array_push($json,$detail);
            }
            return ResponseFormatter::success('data', $json, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    public function getBarangTidakHabisPakai(){
        try {
            $json = [];
            $datas = BarangTidakHabisPakai::all();
            foreach($datas as $data){
                $barang = $data->barang;
                $detail = [
                    'id' => $barang->id,
                    'nomor_seri' => $data->nomor_seri,
                    'kondisi' => $data->kondisi,
                    'keterangan' => $data->keterangan,
                    'nama' => $barang->nama,
                    'merk' => $barang->merk,
                    'gambar' => $barang->gambar,
                    'detail' => $barang->detail,
                    'gudang' => $barang->gudang->nama ?? ""
                ];
                array_push($json, $detail);
            }
            return ResponseFormatter::success('data', $json, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    public function barangHabisPakai(){
        try {
            $json = [];
            $datas = BarangHabisPakai::all();
            foreach($datas as $data){
                $barang = $data->barang;
                $detail = [
                    'id' => $barang->id,
                    'jumlah' => $data->jumlah,
                    'satuan' => $data->satuan,
                    'ukuran' => $data->ukuran,
                    'nama' => $barang->nama,
                    'merk' => $barang->merk,
                    'gambar' => $barang->gambar,
                    'detail' => $barang->detail,
                    'gudang' => $barang->gudang->nama ?? ""
                ];
                array_push($json, $detail);
            }
            return ResponseFormatter::success('data', $json, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    public function barangTidakHabisPakaiTersedia(Request $request){
        try {
            $json = [];
            $user = $request->user();
            $datas = BarangTidakHabisPakai::whereNull('peminjaman_id')->orWhereHas('peminjamanDetail', function($query){
                $query->where('status','TIDAK_DIGUNAKAN');
            })->get();
            foreach($datas as $data){
                $barang = $data->barang;
                $detail = [
                    'id' => $barang->id,
                    'nomor_seri' => $data->nomor_seri,
                    'kondisi' => $data->kondisi,
                    'keterangan' => $data->keterangan,
                    'nama' => $barang->nama,
                    'merk' => $barang->merk,
                    'gambar' => $barang->gambar,
                    'detail' => $barang->detail,
                    'gudang' => $barang->gudang->nama ?? ""
                ];
                array_push($json, $detail);
            }
            return ResponseFormatter::success('data', $json, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }
}
?>