<?php

namespace App\Models;

use App\Helpers\Date;
use App\Helpers\IDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryOrder extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function logistic(){
        return $this->belongsTo(User::class, 'logistic_id');
    }
    public function adminGudang(){
        return $this->belongsTo(User::class, 'admin_gudang_id');
    }
    public function purchasing(){
        return $this->belongsTo(User::class,'purchasing_id');
    }
    public function kendaraan(){
        return $this->belongsTo(Kendaraan::class);
    }
    public function preOrder(){
        return $this->hasMany(PreOrder::class);
    }
    public function perusahaan(){
        return $this->belongsTo(Perusahaan::class);
    }
    public function gudang(){
        return $this->belongsTo(Gudang::class);
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('kode_do', 'like', '%' . $search . '%');
        });
        $query->when($filters['filter'] ?? false, function($query, $filter) {
        //    return $query->where(function($query) use ($filter) {
            if($filter != 'semua status'){
                if($filter == 'sudah diambil')
                    return $query->where('status', 'Selesai');
                if($filter == 'dalam perjalanan')
                    return $query->where('status', 'Driver dalam perjalanan');
                if($filter == 'belum diambil')
                    return $query->where('status', 'Menunggu konfirmasi driver');
            }
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
    public static function generateKodeDO($nama_perusahaan, $tgl_pengambilan){
        $perusahaanAlias = IDGenerator::getAcronym($nama_perusahaan);
        $romanMonth = IDGenerator::numberToRoman(Date::getMonthNumber($tgl_pengambilan));
        $prefix = "DO/BC-" . $perusahaanAlias . "/" . $romanMonth . "/" . Date::getYearNumber();
        return IDGenerator::generateID(new static, 'kode_do', 5, $prefix);
    }
}
