<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Date;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\SjPengembalian;
use App\Models\SjPengirimanGp;
use App\Models\SjPengirimanPp;
use App\Models\SuratJalan;
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
                $sjCombine = SjPengirimanPp::createData($request);
                Kendaraan::setLogistic($request);
                // return ResponseFormatter::success('data', [
                //     'surat_jalan' => $sj,
                //     'sj_pengiriman_pp' => $sjCombine,
                // ], 'Berhasil Menambah Surat Jalan Pengiriman Proyek-Proyek');
                return ResponseFormatter::success(null, null, 'Berhasil Menambah Surat Jalan Pengiriman Proyek-Proyek');
            }else if($sj->tipe=='PENGIRIMAN_GUDANG_PROYEK'){
                $sjCombine = SjPengirimanGp::createData($request);
                Kendaraan::setLogistic($request);
                // return ResponseFormatter::success('data', [
                //     'surat_jalan' => $sj,
                //     'sj_pengiriman_gp' => $sjCombine,
                // ], 'Berhasil Menambah Surat Jalan Pengiriman Gudang-Proyek');
                return ResponseFormatter::success(null, null, 'Berhasil Menambah Surat Jalan Pengiriman Gudang-Proyek');
            }else{
                $sjCombine=SjPengembalian::createData($request);
                Kendaraan::setLogistic($request);
                // return ResponseFormatter::success('data', [
                //     'surat_jalan' => $sj,
                //     'sj_pengembalian' => $sjCombine,
                // ], 'Berhasil Menambah Surat Jalan Pengembalian Proyek-Gudang');
                return ResponseFormatter::success(null, null, 'Berhasil Menambah Surat Jalan Pengembalian Proyek-Gudang');
            }
        } catch (Exception $error) {
            return ResponseFormatter::error("Gagal Menambahkan Surat Jalan: ". $error->getMessage());
        }
    }

    public function getAllSuratJalanByAdminGudang(Request $request){
        try{
            $admin_gudang_id = $request->route('admin_gudang_id');
            
            $request->merge(['admin_gudang_id' => $admin_gudang_id]);
            $request->validate([
                'admin_gudang_id' => 'required|exists:admin_gudangs,user_id',
                'tipe' => 'required|in:PENGIRIMAN_GUDANG_PROYEK,PENGIRIMAN_PROYEK_PROYEK,PENGEMBALIAN',
                'status' => 'required|in:MENUNGGU_KONFIRMASI_DRIVER,DRIVER_DALAM_PERJALANAN,SELESAI',
            ]);
            $status = $request->query('status');
            $date_start = $request->query('date_start');
            $date_end = $request->query('date_end');
            $order = $request->query('order');
            $tipe = $request->query('tipe');

            $response = SuratJalan::where('admin_gudang_id', $admin_gudang_id)->where('tipe', $tipe)->where('status', $status);
            if(isset($date_start) && isset($date_end)){
                $response->whereBetween('updated_at', [$date_start, $date_end]);
            }else{
                $response->whereBetween('updated_at', [Carbon::now()->subMonth(), Carbon::now()]);
            }

            // check tipe
            if($tipe == 'PENGIRIMAN_GUDANG_PROYEK'){
                $response->with('sjPengirimanGp');
            }else if($tipe == 'PENGIRIMAN_PROYEK_PROYEK'){
                $response->with('sjPengirimanPp');
            }else{
                $response->with('sjPengembalian');
            }
            $message = ($response->get()->isEmpty()) ? 'Tidak ada surat jalan' : 'Berhasil Mendapatkan Surat Jalan';
            return ResponseFormatter::success('surat_jalan', $response->get(),$message);
        }catch(Exception $e){
            return ResponseFormatter::error("Gagal Mendapatkan Surat Jalan: ". $e->getMessage());
        }
    }
}
