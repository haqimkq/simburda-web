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
                $sjCreate = 'Pengiriman Proyek-Proyek';
            }else if($sj->tipe=='PENGIRIMAN_GUDANG_PROYEK'){
                SjPengirimanGp::createData($request);
                $sjCreate = 'Pengiriman Gudang-Proyek';
            }else{
                SjPengembalian::createData($request);
                $sjCreate = 'Pengembalian Proyek-Gudang';
            }
            Kendaraan::setLogistic($request);
            return ResponseFormatter::success('surat_jalan_id', $sj->id, "Berhasil Menambahkan Surat Jalan $sjCreate");
        } catch (Exception $error) {
            return ResponseFormatter::error("Gagal Menambahkan Surat Jalan: ". $error->getMessage());
        }
    }

    public function update(Request $request){
        try {
            $surat_jalan_id = $request->route('surat_jalan_id');
            $request->merge(['surat_jalan_id' => $surat_jalan_id]);
            $sj=SuratJalan::updateData($request);
            if($sj->tipe=='PENGIRIMAN_PROYEK_PROYEK'){
                SjPengirimanPp::updateData($request);
                $sjCreate = 'Pengiriman Proyek-Proyek';
            }else if($sj->tipe=='PENGIRIMAN_GUDANG_PROYEK'){
                SjPengirimanGp::updateData($request);
                $sjCreate = 'Pengiriman Gudang-Proyek';
            }else{
                SjPengembalian::updateData($request);
                $sjCreate = 'Pengembalian Proyek-Gudang';
            }
            Kendaraan::setLogistic($request);
            return ResponseFormatter::success('surat_jalan_id', $sj->id, "Berhasil Mengupdate Surat Jalan $sjCreate");
        } catch (Exception $error) {
            return ResponseFormatter::error("Gagal Mengupdate Surat Jalan: ". $error);
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
            $date_start = ($request->query('date_start')) ? date($request->query('date_start') . " 00:00:00") : null;
            $date_end = ($request->query('date_end')) ? date($request->query('date_end') . " 23:59:59") : null;
            
            $tipe = $request->query('tipe');
            $search = $request->query('search') ?? null;

            $response = SuratJalan::getAllSuratJalanByUser($user, $tipe, $status, $size, $date_start, $date_end, $search);

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
            $nama_gudang = null;

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
            $admin_gudang = ($response->adminGudang) ? [
                'nama' => $response->adminGudang->nama,
                'no_hp' => $response->adminGudang->no_hp,
                'foto' => $response->adminGudang->foto,
            ] : null;

            $kendaraan = ($response->kendaraan) ? [
                'merk' => $response->kendaraan->merk,
                'plat_nomor' => $response->kendaraan->plat_nomor,
                'jenis' => $response->kendaraan->jenis,
            ] : null;

            $logistic = ($response->logistic) ? [
                'nama' => $response->logistic->nama,
                'no_hp' => $response->logistic->no_hp,
                'foto' => $response->logistic->foto,
            ] : null;
            
            $sv = ($supervisor) ? [
                'nama' => $supervisor->nama,
                'no_hp' => $supervisor->no_hp,
                'foto' => $supervisor->foto,
            ] : null;
            
            $pm = ($project_manager) ? [
                'nama' => $project_manager->nama,
                'foto' => $project_manager->foto,
                'no_hp' => $project_manager->no_hp,
            ] : null;
            
            $gd = ($nama_gudang) ? [
                'nama' => $nama_gudang,
                'foto' => $foto_gudang,
                'alamat' => $alamat_gudang,
                'coordinate' => $coordinate_gudang,
            ] : null;

            $proyek_asal = ($nama_proyek_asal) ? [
                'nama' => $nama_proyek_asal,
                'alamat' => $alamat_proyek_asal,
                'foto' => $foto_proyek_asal,
                'coordinate' => $coordinate_proyek_asal,
            ] : null;
            
            $proyek_tujuan = ($proyek) ? [
                'nama' => $proyek->nama_proyek,
                'alamat' => $proyek->alamat,
                'foto' => $proyek->foto,
                'coordinate' => $coordinate_proyek_tujuan,
            ] : null;
            
            $surat_jalan = [
                'id' => $response->id,
                'kode_surat' => $response->kode_surat,
                'ttd_admin' => $response->ttd_admin,
                'ttd_driver' => $response->ttd_driver,
                'ttd_penerima' => $response->ttd_penerima,
                'foto_bukti' => $response->foto_bukti,
                'tipe' => $response->tipe,
                'status' => $response->status,
                'updated_at' => $response->updated_at,
                'created_at' => $response->created_at,
                'kendaraan' => $kendaraan,
                'admin_gudang' => $admin_gudang,
                'logistic' => $logistic,
                'supervisor' => $sv,
                'project_manager' => $pm,
                'gudang' => $gd,
                'proyek_tujuan' => $proyek_tujuan,
                'proyek_asal' => $proyek_asal,
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
