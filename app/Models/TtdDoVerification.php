<?php

namespace App\Models;

use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

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
}
