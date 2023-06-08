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

    public static $jenisSuratJalanPengirimanPP = "Surat Jalan Pengiriman Proyek-Proyek";
    public static $jenisSuratJalanPengirimanGP = "Surat Jalan Pengiriman Gudang-Proyek";
    public static $jenisSuratJalanPengembalian = "Surat Jalan Pengembalian";
    public static $jenisDeliveryOrder = "Delivery Order";
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

    public static function generateKeteranganDeliveryOrder($user_id, $kode, $perusahaan, $gudang, $perihal, $untuk_perhatian){
        $user = User::find($user_id);
        $roleLower = ucwords(strtolower(str_replace("_"," ",$user->role)));
        $result = "$user->nama|$roleLower|$perihal|$kode|$perusahaan|$untuk_perhatian|$gudang";
        return $result;
    }

    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }

    public static function getFile($id){
        $filePath = public_path()."/storage/assets/ttd-verification/$id.jpg";
        // if(!file_exists($filePath)){
        $ttd_verification = self::find($id);
        $ttd = public_path('storage/'.$ttd_verification->user->ttd);
        $qrValue = (env('APP_ENV') == 'local') ? env('NGROK_URL') : env('APP_URL');

        $qrcode = QrCode::size(400)->format('png')->errorCorrection('H')->generate("$qrValue/signature/verified/$id");
        $img_canvas = ImageManager::canvas(850,450);

        $filePath = public_path()."/storage/assets/ttd-verification/$id.jpg";
        $output_file = "assets/ttd-verification/$id.jpg";
        Storage::disk('public')->put($output_file, $qrcode);
        $img_canvas->insert(ImageManager::make($filePath), 'center', 199, 0); // move second image 400 px from left
        $img_canvas->insert(ImageManager::make($ttd)->resize(400, null), 'left',);
        $img_canvas->save($filePath, 100);
        // }
        return $filePath;
    }
}
