<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Date;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\SjPengembalian;
use App\Models\SjPengirimanGp;
use App\Models\SjPengirimanPp;
use App\Models\SuratJalan;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class SuratJalanController extends Controller
{
    public function create(Request $request){
        try {
            $sj=SuratJalan::createData($request);
            $request->merge(['surat_jalan_id' => $sj->id]);
            if($sj->tipe=='PENGIRIMAN_PROYEK_PROYEK'){
                SjPengirimanPp::createData($request);
                Kendaraan::setLogistic($request);
                return ResponseFormatter::success(null, null, 'Berhasil Menambah Surat Jalan Pengiriman Proyek-Proyek');
            }else if($sj->tipe=='PENGIRIMAN_GUDANG_PROYEK'){
                SjPengirimanGp::createData($request);
                Kendaraan::setLogistic($request);
                return ResponseFormatter::success(null, null, 'Berhasil Menambah Surat Jalan Pengiriman Gudang-Proyek');
            }else{
                SjPengembalian::createData($request);
                Kendaraan::setLogistic($request);
                return ResponseFormatter::success(null, null, 'Berhasil Menambah Surat Jalan Pengembalian Proyek-Gudang');
            }
        } catch (Exception $error) {
            return ResponseFormatter::error("Gagal Menambahkan Surat Jalan: ". $error->getMessage());
        }
    }

    public function getAllSuratJalanByUser(Request $request){
        try{
            $user = $request->user();
            
            $request->validate([
                'tipe' => 'required|in:PENGIRIMAN_GUDANG_PROYEK,PENGIRIMAN_PROYEK_PROYEK,PENGEMBALIAN',
                'status' => 'required|in:MENUNGGU_KONFIRMASI_DRIVER,DRIVER_DALAM_PERJALANAN,SELESAI',
            ]);
            $status = $request->query('status');
            $size = $request->query('size') ?? 5;
            $date_start = date($request->query('date_start'). " 00:00:00");
            $date_end = date($request->query('date_end') . " 23:59:59");
            $tipe = $request->query('tipe');

            $response = SuratJalan::getAllSuratJalanByUser($user, $tipe, $status, $size);
            
            if(isset($date_start) && isset($date_end)){
                $response->whereBetween('updated_at', [$date_start, $date_end]);
            }else{
                $response->whereBetween('updated_at', [Carbon::now()->subMonth(), Carbon::now()]);
            }
            $message = ($response->isEmpty()) ? 'Tidak ada surat jalan' : 'Berhasil Mendapatkan Surat Jalan';
            return ResponseFormatter::success('surat_jalan', $response,$message);
        }catch(Exception $e){
            return ResponseFormatter::error("Gagal Mendapatkan Surat Jalan: ". $e->getMessage());
        }
    }
    public function getSuratJalanById(Request $request){
        try{
            $surat_jalan_id = $request->route('id');
            
            $request->merge(['id' => $surat_jalan_id]);
            $request->validate([
                'id' => 'required|exists:surat_jalans,id',
            ]);

            $response = SuratJalan::where('id', $surat_jalan_id)->first();
            $tipe = $response->tipe;
            if($tipe == 'PENGIRIMAN_GUDANG_PROYEK'){
                $barang_habis_pakai = Peminjaman::getAllBarang($response->sjPengirimanGp->peminjaman->id, 'HABIS_PAKAI');
                $barang_tidak_habis_pakai = Peminjaman::getAllBarang($response->sjPengirimanGp->peminjaman->id, 'TIDAK_HABIS_PAKAI');
                $sj_combine = 'sjPengirimanGp';
            }else if($tipe == 'PENGIRIMAN_PROYEK_PROYEK'){
                $barang_habis_pakai = Peminjaman::getAllBarang($response->sjPengirimanPp->peminjaman->id, 'HABIS_PAKAI');
                $barang_tidak_habis_pakai = Peminjaman::getAllBarang($response->sjPengirimanPp->peminjaman->id, 'TIDAK_HABIS_PAKAI');
                $sj_combine = 'sjPengirimanPp';
            }else{
                $barang_habis_pakai = Pengembalian::getAllBarang($response->sjPengembalian->pengembalian->id, 'HABIS_PAKAI');
                $barang_tidak_habis_pakai = Pengembalian::getAllBarang($response->sjPengembalian->pengembalian->id, 'TIDAK_HABIS_PAKAI');
                $sj_combine = 'sjPengembalian';
            }
            $proyek = ($sj_combine!='sjPengembalian') ?$response->$sj_combine->peminjaman->menangani->proyek : $response->$sj_combine->pengembalian->peminjaman->menangani->proyek;

            $coordinate_proyek_tujuan = ($sj_combine != 'sjPengembalian') ? $response->$sj_combine->peminjaman->menangani->proyek->latitude . "|" . $response->$sj_combine->peminjaman->menangani->proyek->longitude : $response->$sj_combine->pengembalian->peminjaman->menangani->proyek->latitude . "|" . $response->$sj_combine->pengembalian->peminjaman->menangani->proyek->longitude;

            $coordinate_gudang = ($sj_combine != 'sjPengembalian') ? $response->$sj_combine->peminjaman->gudang->latitude . "|" . $response->$sj_combine->peminjaman->gudang->longitude : $response->$sj_combine->pengembalian->peminjaman->gudang->latitude . "|" . $response->$sj_combine->pengembalian->peminjaman->gudang->longitude;

            $nama_proyek_asal = null;
            $coordinate_proyek_asal = null;
            $alamat_proyek_asal = null;
            $foto_proyek_asal = null;

            if($tipe=='PENGIRIMAN_PROYEK_PROYEK'){
                $alamat_proyek_asal = $response->$sj_combine->peminjamanAsal->menangani->proyek->alamat;
                $foto_proyek_asal = $response->$sj_combine->peminjamanAsal->menangani->proyek->foto;
                $nama_proyek_asal = $response->$sj_combine->peminjamanAsal->menangani->proyek->nama_proyek;
                $coordinate_proyek_asal = $response->$sj_combine->peminjamanAsal->menangani->proyek->latitude . "|" . $response->$sj_combine->peminjamanAsal->menangani->proyek->longitude;
            }else if($tipe=='PENGIRIMAN_GUDANG_PROYEK'){
                $alamat_gudang = $response->$sj_combine->peminjaman->gudang->alamat;
                $foto_gudang = $response->$sj_combine->peminjaman->gudang->gambar;
                $nama_gudang = $response->$sj_combine->peminjaman->gudang->nama;
            }else{
                $alamat_gudang = $response->$sj_combine->pengembalian->peminjaman->gudang->alamat;
                $foto_gudang = $response->$sj_combine->pengembalian->peminjaman->gudang->gambar;
                $nama_gudang = $response->$sj_combine->pengembalian->peminjaman->gudang->nama;
            }
            $project_manager = ($sj_combine != 'sjPengembalian') ? $response->$sj_combine->peminjaman->menangani->proyek->projectManager : $response->$sj_combine->pengembalian->peminjaman->menangani->proyek->projectManager;
            $supervisor = ($sj_combine != 'sjPengembalian') ? $response->$sj_combine->peminjaman->menangani->supervisor : $response->$sj_combine->pengembalian->peminjaman->menangani->supervisor;
            $surat_jalan = [
                'id' => $response->id,
                'kode_surat' => $response->kode_surat,
                'ttd_admin' => $response->ttd_admin,
                'ttd_driver' => $response->ttd_driver,
                'ttd_penerima' => $response->ttd_penerima,
                'foto_bukti' => $response->foto_bukti,
                'tipe' => $response->tipe,
                'status' => $response->status,
                'created_at' => $response->created_at,
                'updated_at' => $response->updated_at,
                'nama_admin_gudang' => $response->adminGudang->nama,
                'no_hp_admin_gudang' => $response->adminGudang->no_hp,
                'foto_admin_gudang' => $response->adminGudang->foto,
                'nama_driver' => $response->logistic->nama,
                'no_hp_driver' => $response->logistic->no_hp,
                'foto_driver' => $response->logistic->foto,
                'nama_supervisor' => $supervisor->nama,
                'no_hp_supervisor' => $supervisor->no_hp,
                'foto_supervisor' => $supervisor->foto,
                'nama_project_manager' => $project_manager->nama,
                'foto_project_manager' => $project_manager->foto,
                'nama_gudang' => $nama_gudang,
                'foto_gudang' => $foto_gudang,
                'alamat_gudang' => $alamat_gudang,
                'coordinate_gudang' => $coordinate_gudang,
                'nama_proyek_tujuan' => $proyek->nama_proyek,
                'alamat_proyek_tujuan' => $proyek->alamat,
                'foto_proyek_tujuan' => $proyek->foto,
                'coordinate_proyek_tujuan' => $coordinate_proyek_tujuan,
                'nama_proyek_asal' => $nama_proyek_asal,
                'alamat_proyek_asal' => $alamat_proyek_asal,
                'foto_proyek_asal' => $foto_proyek_asal,
                'coordinate_proyek_asal' => $coordinate_proyek_asal,
                'barang_habis_pakai' => $barang_habis_pakai,
                'barang_tidak_habis_pakai' => $barang_tidak_habis_pakai,
            ];
            $message = ($response->get()->isEmpty()) ? 'Tidak ada surat jalan' : 'Berhasil Mendapatkan Surat Jalan';
            return ResponseFormatter::success('surat_jalan', $surat_jalan,$message);
        }catch(Exception $e){
            return ResponseFormatter::error("Gagal Mendapatkan Surat Jalan: ". $e->getMessage());
        }
    }

    

}
