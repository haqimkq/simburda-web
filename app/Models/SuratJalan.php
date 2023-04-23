<?php

namespace App\Models;

use App\Helpers\Date;
use App\Helpers\IDGenerator;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratJalan extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function kendaraan(){
        return $this->belongsTo(Kendaraan::class);
    }

    public function logistic(){
        return $this->belongsTo(User::class, 'logistic_id');
    }

    public function adminGudang(){
        return $this->belongsTo(User::class, 'admin_gudang_id');
    }
    
    public function sjPengirimanPP(){
        return $this->hasOne(SjPengirimanPp::class);
    }

    public function sjPengirimanGP(){
        return $this->hasOne(SjPengirimanGp::class);
    }

    public function sjPengembalian(){
        return $this->hasOne(SjPengembalian::class);
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('nama_proyek', 'like', '%' . $search . '%');
        });
        $query->when($filters['filter'] ?? false, function($query, $filter) {
        //    return $query->where(function($query) use ($filter) {
            if($filter != 'semua status'){
                if($filter == 'selesai')
                    return $query->where('selesai', true);
                if($filter == 'masih berlangsung')
                    return $query->where('selesai', false);
            }
            // });
        });
        $query->when(!isset($filters['orderBy']), function($query){
            return $query->orderBy('created_at', 'DESC');
        });
        $query->when($filters['orderBy'] ?? false, function($query, $orderBy) {
            if($orderBy == 'terbaru') return $query->orderBy('created_at', 'DESC');
            if($orderBy == 'terlama') return $query->orderBy('created_at', 'ASC');
            if($orderBy == 'jumlah tersedikit') return $query->orderBy('jumlah', 'ASC');
            if($orderBy == 'jumlah terbanyak') return $query->orderBy('jumlah', 'DESC');
        });
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateFormatter($date, 'ddd, D MMM YYYY');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Date::dateFormatter($date, 'ddd, D MMM YYYY');
    }
    public function getTglSelesaiAttribute($date)
    {
        if($date)
        return Date::dateFormatter($date, 'ddd, D MMM YYYY');
    }

    public static function generateKodeSurat($tipe){
        if($tipe == "PENGIRIMAN_PROYEK_PROYEK"){
            return IDGenerator::generateID(SuratJalan::class, 'kode_surat', 5, 'SJPP');
        }else if($tipe == "PENGIRIMAN_GUDANG_PROYEK"){
            return IDGenerator::generateID(SuratJalan::class, 'kode_surat', 5, 'SJGP');
        }else{
            return IDGenerator::generateID(SuratJalan::class, 'kode_surat', 5, 'SJPG');
        }
    }

}
