<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\BarangHabisPakai;
use App\Models\BarangTidakHabisPakai;
use App\Models\Menangani;
use App\Models\PeminjamanDetail;
use Illuminate\Http\Request;
use Exception;
use GuzzleHttp\Psr7\Response;

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
                    'nama_proyek' => $data->peminjaman->menangani->proyek->nama_proyek,
                    'kode_peminjaman' => $data->peminjaman->kode_peminjaman,
                    'tipe' => $data->peminjaman->tipe,
                    'tgl_peminjaman' => $data->peminjaman->getRemainingDaysAttribute()
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
                    'gudang' => $barang->gudang->nama ?? "",
                ];
                array_push($json, $detail);
            }
            return ResponseFormatter::success('data', $json, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    public function scanQrCode(Request $request){
        try{    
            $barang = BarangTidakHabisPakai::where('id',$request->barang_id)->first();
            if($barang->status == 'DIPESAN'){
                $authUser = $request->user();
                $proyek = $barang->peminjaman->menangani->proyek;
                $menanganis = Menangani::where('proyek_id',$proyek->id)->get();
                foreach ($menanganis as $menangani){
                    if($menangani->user_id == $authUser->id){
                        //update Tanggung jawab
                        PeminjamanDetail::where('barang_id',$barang->id)->where('peminjaman_id',$barang->peminjaman_id)->update(['status' =>'DIGUNAKAN','penanggung_jawab_id' => $authUser->id]);
                        //update status
                        BarangTidakHabisPakai::where('id',$barang->id)->update(['status' => 'DIPINJAM']);
                        return ResponseFormatter::success('','','Barang Berhasil Diterima');
                    }
                }
                return ResponseFormatter::error('Tidak Memiliki Akses');
            } else{
                return ResponseFormatter::error('Barang Belum Diminta');
            }
        }catch(Exception $error){
            return ResponseFormatter::error('Server Error : '.$error->getMessage());
        }
    }

    public function updateStatusBarang(Request $request){
        try{    
            PeminjamanDetail::whereRelation('peminjaman.menangani','proyek_id',$request->proyek_id)->where('barang_id',$request->barang_id)->update(['status' => $request->status]);
            return ResponseFormatter::success(null,null,'Status Barang Berhasil Diperbaharui');
        }catch(Exception $error){
            return ResponseFormatter::error('Server Error : '.$error->getMessage());
        }
    }
}
?>