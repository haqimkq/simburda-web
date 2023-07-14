<?php

namespace App\Models;

use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as ImageManager;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class TtdVerification extends Model
{
    use HasFactory;
    use Uuids;
    protected $guarded = ['id'];
    protected $table = 'ttd_verifications';

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
    public function ttdSjPenanggungJawab(){
        return $this->hasOne(SuratJalan::class,'ttd_tgg_jwb');
    }
    public function ttdSjPenanggungJawabPeminjam(){
        return $this->hasOne(SjPengirimanPp::class,'ttd_tgg_jwb');
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public static function createTtdSJVerification($user_id, $surat_jalan_id){
        $user = User::find($user_id);
        $sj = SuratJalan::find($surat_jalan_id);
        $ttd_verification = TtdVerification::create([
            "user_id" => $user_id,
            'tipe' => "SURAT_JALAN",
            'sebagai' => self::setSebagaiTtdSjVerification($user, $sj),
        ]);
        return $ttd_verification->id;
    }
    public static function createTtdDoVerification($user_id){
        $ttd_verification = TtdVerification::create([
            "user_id" => $user_id,
            'tipe' => "DELIVERY_ORDER",
            'sebagai' => 'PEMBUAT'
        ]);
        return $ttd_verification->id;
    }
    public static function updateTtdSjVerificationFromSuratJalan($sj){
        if($sj->sjPengirimanGp!=null && $sj->ttd_tgg_jwb!=null){
            $user = $sj->sjPengirimanGp->peminjamanGp->peminjaman->menangani->user;
        }else if($sj->sjPengembalian!=null){
            $user = $sj->sjPengembalian->pengembalian->peminjaman->menangani->user;
        }else if($sj->sjPengirimanPp!=null){
            $user_peminjam = $sj->sjPengirimanPp->peminjamanPp->peminjaman->menangani->user;
            $user = $sj->sjPengirimanPp->peminjamanPp->peminjamanAsal->menangani->user;
        }
        if($sj->ttd_admin!=null){
            TtdVerification::find($sj->ttd_admin)->update([
                'user_id' => $sj->adminGudang->id,
                'sebagai' => self::setSebagaiTtdSjVerification($sj->adminGudang, $sj)
            ]);
        }
        if($sj->ttd_driver!=null)
            TtdVerification::where('id', $sj->ttd_driver)->update([
                'user_id' => $sj->logistic->id
            ]);
        if($sj->ttd_tgg_jwb!=null){
            TtdVerification::where('id', $sj->ttd_tgg_jwb)->update([
                'user_id' => $user->id,
                'sebagai' => self::setSebagaiTtdSjVerification($user, $sj)
            ]);
        }
        if($sj->sjPengirimanPp!=null){
            if($sj->sjPengirimanPp->ttd_tgg_jwb!=null){
                TtdVerification::where('id', $sj->sjPengirimanPp->ttd_tgg_jwb)->update([
                    'user_id' => $user_peminjam->id,
                    'sebagai' => self::setSebagaiTtdSjVerification($user, $sj)
                ]);
            }
        }
    }
    public static function setSebagaiTtdSjVerification($user, $sj){
        if($user->role=='LOGISTIC'){
            $sebagai = "PENGIRIM";
        }else if($user->role=='SUPERVISOR' || $user->role=='SET_MANAGER'){
            if($sj->sjPengirimanGp!=null){
                $sebagai = "PENERIMA";
            }else if($sj->sjPengembalian!=null){
                $sebagai = "PEMBERI";
            }else if($sj->sjPengirimanPp!=null){
                $check_user=$sj->sjPengirimanPp->peminjamanPp->peminjaman->menangani->user;
                if($check_user->id == $user->id) $sebagai = "PENERIMA";
                else $sebagai = "PEMBERI";
            }
        }else if($user->role=='ADMIN_GUDANG'){
            if($sj->sjPengirimanGp !=null){
                $sebagai = "PEMBERI";
            }else if($sj->sjPengembalian !=null){
                $sebagai = "PENERIMA";
            }else if($sj->sjPengirimanPp!=null){
                $sebagai = "PEMBUAT";
            }
        }
        return $sebagai;
    }
    public static function getSuratJalan($ttd_verification_id){
        $ttd_verification = TtdVerification::find($ttd_verification_id);
        if($ttd_verification->ttdSjAdmin != null){
            $sj = $ttd_verification->ttdSjAdmin;
        }else if($ttd_verification->ttdSjDriver != null){
            $sj = $ttd_verification->ttdSjDriver;
        }else if($ttd_verification->ttdSjPenanggungJawab != null){
            $sj = $ttd_verification->ttdSjPenanggungJawab;
        }
        return $sj;
    }
    public static function getKeteranganSuratJalan($ttd_verification_id){
        $ttd_verification = self::find($ttd_verification_id);
        $sj = self::getSuratJalan($ttd_verification_id);
        $lokasi = SuratJalan::getLokasiAsalTujuan($sj->id);
        $tipe = $sj->tipe;
        $kode_surat = $sj->kode_surat;

        $ttd = $ttd_verification->user->ttd; 
        $sebagai = $ttd_verification->sebagai;
        $sebagaiLower = ucfirst(strtolower($sebagai));
        $roleLower = ucwords(strtolower(str_replace("_"," ",$ttd_verification->user->role)));
        $tipeLower = ucwords(strtolower(str_replace("_"," ",$tipe)));
        
        $result = [
            "nama" => $ttd_verification->user->nama,
            "ttd" => $ttd,
            "role" => $roleLower,
            "tipe" => $tipeLower,
            "kode_surat" => $kode_surat,
            "sebagai" => $sebagaiLower,
            "asal" => $lokasi['lokasi_asal']['nama'],
            "tujuan" => $lokasi['lokasi_tujuan']['nama'],
        ];
        return $result;
    }
    public static function getKeteranganDo($ttd_verification_id){
        $ttd_verification = self::find($ttd_verification_id);
        if($ttd_verification->ttdDo != null){
            $kode_do = $ttd_verification->ttdDo->kode_do;
            $perihal = $ttd_verification->ttdDo->perihal;
            $gudang = $ttd_verification->ttdDo->gudang->nama;
            $perusahaan = $ttd_verification->ttdDo->perusahaan->nama;
            $untuk_perhatian = $ttd_verification->ttdDo->untuk_perhatian;
        }
        $roleLower = ucwords(strtolower(str_replace("_"," ",$ttd_verification->user->role)));
        $result = [
            "nama" => $ttd_verification->user->nama,
            "ttd" => $ttd_verification->user->ttd,
            "role" => $roleLower,
            "kode" => $kode_do,
            "perihal" => $perihal,
            "gudang" => $gudang,
            "perusahaan" => $perusahaan,
            "untuk_perhatian" => $untuk_perhatian,
        ];
        return $result;
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
