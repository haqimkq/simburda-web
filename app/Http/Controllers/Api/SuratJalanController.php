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
                return ResponseFormatter::success('data', [
                    'surat_jalan' => $sj,
                    'sj_pengiriman_pp' => $sjCombine,
                ], 'Berhasil Menambah Surat Jalan Pengiriman Proyek-Proyek');
            }else if($sj->tipe=='PENGIRIMAN_GUDANG_PROYEK'){
                $sjCombine = SjPengirimanGp::createData($request);
                Kendaraan::setLogistic($request);
                return ResponseFormatter::success('data', [
                    'surat_jalan' => $sj,
                    'sj_pengiriman_gp' => $sjCombine,
                ], 'Berhasil Menambah Surat Jalan Pengiriman Gudang-Proyek');
            }else{
                $sjCombine=SjPengembalian::createData($request);
                Kendaraan::setLogistic($request);
                return ResponseFormatter::success('data', [
                    'surat_jalan' => $sj,
                    'sj_pengembalian' => $sjCombine,
                ], 'Berhasil Menambah Surat Jalan Pengembalian Proyek-Gudang');
            }
        } catch (Exception $error) {
            return ResponseFormatter::error("Gagal Menambahkan Surat Jalan: ". $error->getMessage());
        }
    }
}
