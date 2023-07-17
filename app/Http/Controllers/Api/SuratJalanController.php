<?php

namespace App\Http\Controllers\Api;

use App\Enum\SuratJalanStatus;
use App\Enum\SuratJalanTipe;
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
use App\Models\TtdVerification;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class SuratJalanController extends Controller
{
    public function create(Request $request){
        try {
            $sj=SuratJalan::createData($request);
            $request->merge(['surat_jalan_id' => $sj->id]);
            
            if($sj->tipe==SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value){
                SjPengirimanPp::createData($request);
                $sjCreate = 'Pengiriman Proyek-Proyek';
            }else if($sj->tipe==SuratJalanTipe::PENGIRIMAN_GUDANG_PROYEK->value){
                SjPengirimanGp::createData($request);
                $sjCreate = 'Pengiriman Gudang-Proyek';
            }else{
                SjPengembalian::createData($request);
                $sjCreate = 'Pengembalian Proyek-Gudang';
            }
            Kendaraan::setLogistic($request);
            return ResponseFormatter::success("surat_jalan_id", $sj->id, "Berhasil Menambahkan Surat Jalan $sjCreate");
        } catch (Exception $error) {
            return ResponseFormatter::error("Gagal Menambahkan Surat Jalan: ". $error->getMessage());
        }
    }

    public function update(Request $request){
        try {
            $surat_jalan_id = $request->route('surat_jalan_id');
            $request->merge(['surat_jalan_id' => $surat_jalan_id]);
            $sj=SuratJalan::updateData($request);
            if($sj->tipe==SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value){
                SjPengirimanPp::updateData($request);
                $sjUpdate = 'Pengiriman Proyek-Proyek';
            }else if($sj->tipe==SuratJalanTipe::PENGIRIMAN_GUDANG_PROYEK->value){
                SjPengirimanGp::updateData($request);
                $sjUpdate = 'Pengiriman Gudang-Proyek';
            }else{
                SjPengembalian::updateData($request);
                $sjUpdate = 'Pengembalian Proyek-Gudang';
            }
            Kendaraan::setLogistic($request);
            return ResponseFormatter::success("surat_jalan_id", $sj->id, "Berhasil Mengupdate Surat Jalan $sjUpdate");
        } catch (Exception $error) {
            return ResponseFormatter::error("Gagal Mengupdate Surat Jalan: ". $error);
        }
    }

    public function getAllSuratJalanByUser(Request $request){
        try{
            $user = $request->user();
            
            $request->validate([
                'tipe' => [
                    'required',
                    new Enum(SuratJalanTipe::class),
                ],
                'status' => [
                    'required',
                    new Enum(SuratJalanStatus::class),
                ]
            ]);
            $status = $request->query('status');
            $size = $request->query('size') ?? 10;
            if($request->query('date_start') || $request->query('date_end')){
                $request->validate([
                    'date_start' => [
                        'required',
                        'date_format:Y-M-D'
                    ],
                    'date_end' => [
                        'required',
                        'date_format:Y-M-D',
                    ]
                ]);
            }
            $date_start = ($request->query('date_start')) ? date($request->query('date_start') . " 00:00:00") : null;
            $date_end = ($request->query('date_end')) ? date($request->query('date_end') . " 23:59:59") : null;
            
            $tipe = $request->query('tipe');
            $search = $request->query('search') ?? null;

            $response = SuratJalan::getAllSuratJalanByUser(false, $user, $tipe, $status, $size, $date_start, $date_end, $search);

            $message = ($response['surat_jalan']->isEmpty()) ? 'Tidak ada surat jalan' : 'Berhasil Mendapatkan Surat Jalan';
            return ResponseFormatter::success('surat_jalan', $response['surat_jalan'],$message);
        }catch(Exception $e){
            return ResponseFormatter::error("Gagal Mendapatkan Surat Jalan: ". $e->getMessage());
        }
    }
    public function getAllSuratJalanDalamPerjalananByUser(Request $request){
        try{
            $user = $request->user();
            $response = SuratJalan::getAllSuratJalanByUser(false, $user, 'all', 'DRIVER_DALAM_PERJALANAN', 'all');

            $message = ($response['surat_jalan']->isEmpty()) ? 'Tidak ada surat jalan dalam perjalanan' : 'Berhasil Mendapatkan Surat Jalan Dalam Perjalanan';
            return ResponseFormatter::success('surat_jalan', $response['surat_jalan'],$message);
        }catch(Exception $e){
            return ResponseFormatter::error("Gagal Mendapatkan Surat Jalan Dalam Perjalanan: ". $e->getMessage());
        }
    }
    public function getCountActiveSuratJalanByUser(Request $request){
        try{
            $user = $request->user();
            $response = SuratJalan::getCountActiveSuratJalanByUser($user->id);
            $message = ($response==0) ? 'Tidak ada surat jalan' : 'Berhasil Mendapatkan Jumlah Surat Jalan Aktif';
            return ResponseFormatter::success('total_active', $response,$message);
        }catch(Exception $e){
            return ResponseFormatter::error("Gagal Mendapatkan Surat Jalan: ". $e->getMessage());
        }
    }
    public function getSomeActiveSuratJalanByUser(Request $request){
        try{
            $user = $request->user();
            $request->validate([
                'tipe' => [
                    'required',
                    new Enum(SuratJalanTipe::class),
                ],
            ]);
            $size = $request->query('size') ?? 5;
            $tipe = $request->query('tipe');
            $response = SuratJalan::getAllSuratJalanByUser(true, $user, $tipe, 'active', $size);
            $message = ($response['surat_jalan']->isEmpty()) ? 'Tidak ada surat jalan' : 'Berhasil Mendapatkan Surat Jalan';
            return ResponseFormatter::success('data', $response,$message);
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

            $response = SuratJalan::find($request->id);
            $tipe = $response->tipe;
            $barang = SuratJalan::getAllBarang($response->id);
            $penanggung_jawab_peminjam = ($tipe == SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value) 
                ? SuratJalan::getMenanganiUser($response->id, true) : null;
            $ttd_penanggung_jawab_peminjam = ($tipe == SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value) 
                ? $response->sjPengirimanPp->ttdPenanggungJawab->user->ttd : null;
            $site_manager = SuratJalan::getSetManager($response->id);
            $menangani_user = SuratJalan::getMenanganiUser($response->id);
            $penanggung_jawab = SuratJalan::getPenanggungJawab($response->id);
            
            $admin_gudang = [
                'nama' => $response->adminGudang->nama,
                'no_hp' => $response->adminGudang->no_hp,
                'foto' => $response->adminGudang->foto,
            ];

            $kendaraan = [
                'merk' => $response->kendaraan->merk,
                'plat_nomor' => $response->kendaraan->plat_nomor,
                'gambar' => $response->kendaraan->gambar,
                'jenis' => $response->kendaraan->jenis,
            ];

            $logistic = [
                'id' => $response->logistic->id,
                'nama' => $response->logistic->nama,
                'no_hp' => $response->logistic->no_hp,
                'foto' => $response->logistic->foto,
            ];
            
            $menanganiUser = ($menangani_user) ? [
                'nama' => $menangani_user->nama,
                'no_hp' => $menangani_user->no_hp,
                'role' => $menangani_user->role,
                'foto' => $menangani_user->foto,
            ] : null;
            
            $penanggungJawab = ($penanggung_jawab) ? [
                'nama' => $penanggung_jawab->nama,
                'no_hp' => $penanggung_jawab->no_hp,
                'role' => $penanggung_jawab->role,
                'foto' => $penanggung_jawab->foto,
            ] : null;
            
            $penanggungJawabPeminjam = ($penanggung_jawab_peminjam) ? [
                'nama' => $penanggung_jawab_peminjam->nama,
                'no_hp' => $penanggung_jawab_peminjam->no_hp,
                'role' => $penanggung_jawab_peminjam->role,
                'foto' => $penanggung_jawab_peminjam->foto,
            ] : null;
            
            $sm = [
                'nama' => $site_manager->nama,
                'foto' => $site_manager->foto,
                'role' => $site_manager->role,
                'no_hp' => $site_manager->no_hp,
            ];
            $lokasi = SuratJalan::getLokasiAsalTujuan($response->id);
            $surat_jalan = collect([
                'id' => $response->id,
                'kode_surat' => $response->kode_surat,
                'ttd_admin' => $response->ttdSjAdmin->user->ttd,
                'ttd_driver' => $response->ttdSjDriver->user->ttd,
                'ttd_penanggung_jawab' => $response->ttdSjPenanggungJawab->user->ttd,
                'ttd_penanggung_jawab_peminjam' => $ttd_penanggung_jawab_peminjam,
                'foto_bukti' => $response->foto_bukti,
                'tipe' => $response->tipe,
                'status' => $response->status,
                'updated_at' => $response->updated_at,
                'created_at' => $response->created_at,
                'kendaraan' => $kendaraan,
                'admin_gudang' => $admin_gudang,
                'logistic' => $logistic,
                'menangani_user' => $menanganiUser,
                'penanggung_jawab' => $penanggungJawab,
                'penanggung_jawab_peminjam' => $penanggungJawabPeminjam,
                'site_manager' => $sm,
                'tempat_asal' => $lokasi['lokasi_asal'],
                'tempat_tujuan' => $lokasi['lokasi_tujuan'],
                'barang_habis_pakai' => $barang['barang_habis_pakai'],
                'barang_tidak_habis_pakai' => $barang['barang_tidak_habis_pakai'],
            ]);
            $message = ($surat_jalan->isEmpty()) ? 'Tidak ada surat jalan' : 'Berhasil Mendapatkan Surat Jalan';
            return ResponseFormatter::success('surat_jalan', $surat_jalan,$message);
        }catch(Exception $e){
            return ResponseFormatter::error("Gagal Mendapatkan Surat Jalan: ". $e->getMessage());
        }
    }

    public function markCompleteSuratJalan(Request $request){
        try{
            $user = $request->user();
            
            $request->validate([
                'id' => 'required|exists:surat_jalans,id'
            ]);

            $surat_jalan = SuratJalan::find($request->id);
            if($surat_jalan->tipe == SuratJalanTipe::PENGIRIMAN_GUDANG_PROYEK->value){

            }
            $response = collect();
            $message = ($response->isEmpty()) ? 'Tidak ada surat jalan' : 'Berhasil Mendapatkan Surat Jalan';
            return ResponseFormatter::success('surat_jalan', $response,$message);
        }catch(Exception $e){
            return ResponseFormatter::error("Gagal Mendapatkan Surat Jalan: ". $e->getMessage());
        }
    }
    

}
