<?php

namespace App\Models;

use App\Helpers\Date;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Kendaraan extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];

    public function deliveryOrders(){
        return $this->hasMany(DeliveryOrder::class);
    }
    public function suratJalans(){
        return $this->hasMany(SuratJalan::class);
    }
    public function user(){
        return $this->hasOne(User::class, 'id', 'logistic_id');
    }
    public function gudang(){
        return $this->belongsTo(Gudang::class);
    }
    public static function getKendaraanByLogistic($logistic_id){
        $kendaraan = self::where('logistic_id',$logistic_id)->first();
        $result = collect();
        if($kendaraan!=null){
            $result = [
                "id" => $kendaraan->id,
                "jenis" => $kendaraan->jenis,
                "merk" => $kendaraan->merk,
                "plat_nomor" => $kendaraan->plat_nomor,
                "logistic_id" => $kendaraan->plat_nomor,
                "gambar" => $kendaraan->gambar,
                "id_gudang" => $kendaraan->gudang->id,
                "coordinate_gudang" => $kendaraan->gudang->latitude."|".$kendaraan->gudang->longitude,
                "nama_gudang" => $kendaraan->gudang->nama,
                "alamat_gudang" => $kendaraan->gudang->alamat,
                "gambar_gudang" => $kendaraan->gudang->gambar,
            ];
        }
        return $result;
    }
    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('merk', 'like', '%' . $search . '%');
        });
        $query->when($filters['filter'] ?? false, function($query, $filter) {
        //    return $query->where(function($query) use ($filter) {
            if($filter != 'semua jenis')
                return $query->where('jenis', '=', $filter);
            // });
        });
        $query->when(!isset($filters['orderBy']), function($query){
            return $query->orderBy('created_at', 'DESC');
        });
        $query->when($filters['orderBy'] ?? false, function($query, $orderBy) {
            if($orderBy == 'terbaru') return $query->orderBy('created_at', 'DESC');
            if($orderBy == 'terlama') return $query->orderBy('created_at', 'ASC');
        });
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public static function setLogistic(Request $request){
        self::validateUpdateLogistic($request);
        self::where('logistic_id', $request->logistic_id)->update(['logistic_id'=>null]);
        self::where('id', $request->kendaraan_id)->update(['logistic_id'=>$request->logistic_id]);
    }
    public static function validateUpdateLogistic(Request $request){
        $request->validate([
            'logistic_id' => 'required|exists:logistics,user_id',
            'kendaraan_id' => 'required|exists:kendaraans,id',
        ]);
    }
}
