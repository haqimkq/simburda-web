<?php

namespace App\Models;

use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class TtdSjVerification extends Model
{
    use HasFactory;
    use Uuids;

    protected $guarded = ['id'];
    public static $jenisSuratJalanPengirimanPP = "Surat Jalan Pengiriman Proyek-Proyek";
    public static $jenisSuratJalanPengirimanGP = "Surat Jalan Pengiriman Gudang-Proyek";
    public static $jenisSuratJalanPengembalian = "Surat Jalan Pengembalian";
    public static $jenisDeliveryOrder = "Delivery Order";
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function ttdAdmin(){
        return $this->hasOne(SuratJalan::class,'ttd_admin');
    }
    public function ttdDriver(){
        return $this->hasOne(SuratJalan::class,'ttd_driver');
    }
    public function ttdSupervisor(){
        return $this->hasOne(SuratJalan::class,'ttd_supervisor');
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public static function createTempDirectory(){
        $temporaryDirectory = (new TemporaryDirectory())->name('ttd-verification')->force()->create();
         
        // $image->move('storage/custom_folder/', $image_new_name);
        // C:\WebDev\SimburdaWeb\public\storage\assets\ttd-sj-verification
        return $temporaryDirectory->path('0152536f-4d51-3dfd-8b01-4d187e77ba3d.jpg');
    }

    public static function generateKeterangan($surat_jalan_id, $role=null, $newCode=null){
        $sj = SuratJalan::where('id', $surat_jalan_id)->first();
        $user = null;
        $sebagai = null;
        $asal = null;
        $tujuan = null;
        $kode_surat = $newCode ?? $sj->kode_surat;
        if($role=='LOGISTIC'){
            $user = $sj->logistic;
            $sebagai = "PENGIRIM";
        }else if($role=='SUPERVISOR'){
            if($sj->sjPengirimanGp!=null){
                $user=$sj->sjPengirimanGp->peminjaman->menangani->supervisor;
                $sebagai = "PENERIMA";
            }else if($sj->sjPengembalian!=null){
                $user=$sj->sjPengembalian->pengembalian->peminjaman->menangani->supervisor;
                $sebagai = "PEMBERI";
            }
        }else if($role=='ADMIN_GUDANG'){
            $user = $sj->adminGudang;
            if($sj->has('sjPengirimanGp')){
                $sebagai = "PEMBERI";
            }else if($sj->has('sjPengembalian')){
                $sebagai = "PENERIMA";
            }
        }
        if($sj->tipe == 'PENGIRIMAN_GUDANG_PROYEK' && $sj->sjPengirimanGp !=null){
            $asal = $sj->sjPengirimanGp->peminjaman->gudang->nama;
            $tujuan = $sj->sjPengirimanGp->peminjaman->menangani->proyek->nama_proyek;
        }else if($sj->tipe == 'PENGEMBALIAN' && $sj->sjPengirimanGp!=null){
            $asal = $sj->sjPengembalian->pengembalian->peminjaman->menangani->proyek->nama_proyek;
            $tujuan = $sj->sjPengembalian->pengembalian->peminjaman->gudang->nama;
        }
        $sebagaiLower = ucfirst(strtolower($sebagai));
        $roleLower = ucwords(strtolower(str_replace("_"," ",$user->role)));
        $tipe = ucwords(strtolower(str_replace("_"," ",$sj->tipe)));
        $result = "$user->nama|$roleLower|$tipe|$kode_surat|$sebagaiLower|$asal|$tujuan";
        return $result;
        // Ahmad Lutfi [LOGISTIC] telah menandatangani Pengiriman Gudang Proyek [00033/SJGP/MAP/PKO/V/2023] sebagai pengirim. Lokasi asal: Gudang Jakarta 1 Lokasi tujuan: Pembuatan Kantin Karyawan PIK Avenue Mall


    }

    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
}
