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

class TtdDoVerification extends Model
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
    public function ttd(){
        return $this->hasOne(DeliveryOrder::class,'ttd');
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }

    public static function generateKeterangan($user_id, $kode, $perusahaan, $gudang, $perihal, $untuk_perhatian){
        $user = User::find($user_id);
        $roleLower = ucwords(strtolower(str_replace("_"," ",$user->role)));
        $result = "$user->nama|$roleLower|$perihal|$kode|$perusahaan|$untuk_perhatian|$gudang";
        return $result;
    }

    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }

    public static function getFile($do_verification_id){
        $filePath = public_path()."/storage/assets/ttd-do-verification/$do_verification_id.jpg";
        // if(!file_exists($filePath)){
        $do_verification = TtdDoVerification::find($do_verification_id);
        $ttd = public_path('storage/'.$do_verification->user->ttd);
        $qrValue = (env('APP_ENV') == 'local') ? env('NGROK_URL') : env('APP_URL');

        $qrcode = QrCode::size(400)->format('png')->errorCorrection('H')->generate("$qrValue/signature/verified/$do_verification_id");
        $img_canvas = ImageManager::canvas(850,450);

        $filePath = public_path()."/storage/assets/ttd-do-verification/$do_verification_id.jpg";
        $output_file = "assets/ttd-do-verification/$do_verification_id.jpg";
        Storage::disk('public')->put($output_file, $qrcode);
        $img_canvas->insert(ImageManager::make($filePath), 'center', 199, 0); // move second image 400 px from left
        $img_canvas->insert(ImageManager::make($ttd)->resize(400, null), 'left',);
        $img_canvas->save($filePath, 100);
        // }
        return $filePath;
    }
}
