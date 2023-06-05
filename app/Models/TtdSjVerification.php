<?php

namespace App\Models;

use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as ImageManager;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
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
    public static function validateCreate(Request $request, $surat_jalan_created = true){
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'keterangan' => 'required',
            'sebagai' => 'required|in:PENERIMA,PENGIRIM,PEMBERI'
        ]);
    }
    public static function createData(Request $request){
        self::validateCreate($request);
        return self::create([
            'user_id' => $request->user_id,
            'keterangan' => $request->keterangan,
        ]);
    }
    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }

    public static function getFile($sj_verification_id){
        $filePath = public_path()."/storage/assets/ttd-sj-verification/$sj_verification_id.jpg";
        // if(!file_exists($filePath)){
        $sj_verification = self::find($sj_verification_id);
        $ttd = public_path('storage/'.$sj_verification->user->ttd);
        $qrValue = (env('APP_ENV') == 'local') ? env('NGROK_URL') : env('APP_URL');

        $qrcode = QrCode::size(400)->format('png')->errorCorrection('H')->generate("$qrValue/signature/verified/$sj_verification_id");
        $img_canvas = ImageManager::canvas(850,450);

        $filePath = public_path()."/storage/assets/ttd-sj-verification/$sj_verification_id.jpg";
        $output_file = "assets/ttd-sj-verification/$sj_verification_id.jpg";
        Storage::disk('public')->put($output_file, $qrcode);
        $img_canvas->insert(ImageManager::make($filePath), 'center', 199, 0); // move second image 400 px from left
        $img_canvas->insert(ImageManager::make($ttd)->resize(400, null), 'left',);
        $img_canvas->save($filePath, 100);
        // }
        return $filePath;
    }
}
