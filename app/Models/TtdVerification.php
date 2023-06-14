<?php

namespace App\Models;

use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as ImageManager;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TtdVerification extends Model
{
    use HasFactory;
    use Uuids;
    protected $guarded = ['id'];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function ttdDo(){
        return $this->hasOne(DeliveryOrder::class,'ttd');
    }
    public function ttdSjAdmin(){
        return $this->hasOne(SuratJalan::class,'ttd_admin');
    }
    public function ttdSjDriver(){
        return $this->hasOne(SuratJalan::class,'ttd_driver');
    }
    public function ttdSjSupervisor(){
        return $this->hasOne(SuratJalan::class,'ttd_supervisor');
    }
    public function ttdSjSupervisorPeminjam(){
        return $this->hasOne(SjPengirimanPp::class,'ttd_supervisor_peminjam');
    }
    public function ttdSjVerification(){
        return $this->hasOne(TtdSjVerification::class);
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public static function generateKeteranganDeliveryOrder($user_id, $kode, $perusahaan, $gudang, $perihal, $untuk_perhatian){
        $user = User::find($user_id);
        $roleLower = ucwords(strtolower(str_replace("_"," ",$user->role)));
        $result = "$user->nama|$roleLower|$perihal|$kode|$perusahaan|$untuk_perhatian|$gudang";
        return $result;
    }
    public static function generateKeteranganSuratJalan($surat_jalan_id, $role=null, $newCode=null){
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
        }else if($sj->tipe == 'PENGEMBALIAN' && $sj->sjPengembalian!=null){
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
    public static function getFile($id){
        $filePath = public_path()."/storage/assets/ttd-verification/$id.png";
        // if(!file_exists($filePath)){
        $ttd_verification = self::find($id);
        $ttd = public_path('storage/'.$ttd_verification->user->ttd);
        $qrValue = (env('APP_ENV') == 'local') ? env('NGROK_URL') : env('APP_URL');
        $qrcode = ($ttd_verification->tipe == 'SURAT_JALAN') 
        ? QrCode::size(400)->format('png')->errorCorrection('H')->generate("$qrValue/signature/verified-sj/$id") 
        : QrCode::size(400)->format('png')->errorCorrection('H')->generate("$qrValue/signature/verified-do/$id");
        $img_canvas = ImageManager::canvas(850,450, 'rgba(0, 0, 0, 0)');
        $output_file = "assets/ttd-verification/$id.png";
        Storage::disk('public')->put($output_file, $qrcode);
        $img_canvas->insert(ImageManager::make($filePath), 'center', 199, 0); // move second image 400 px from left
        $img_canvas->insert(ImageManager::make($ttd)->resize(400, null), 'left',);
        $img_canvas->save($filePath, 100);
        // }
        return $filePath;
    }

    public static function getQrCodeFile($id){
        $filePath = public_path()."/storage/assets/ttd-verification/$id.png";
        // if(!file_exists($filePath)){
        $ttd_verification = self::find($id);
        $qrValue = (env('APP_ENV') == 'local') ? env('NGROK_URL') : env('APP_URL');
        $qrcode = ($ttd_verification->tipe == 'SURAT_JALAN') 
        ? QrCode::size(400)->format('png')->errorCorrection('H')->generate("$qrValue/signature/verified-sj/$id") 
        : QrCode::size(400)->format('png')->errorCorrection('H')->generate("$qrValue/signature/verified-do/$id");
        $output_file = "assets/ttd-verification/$id.png";
        Storage::disk('public')->put($output_file, $qrcode);
        return $filePath;
    }
}
