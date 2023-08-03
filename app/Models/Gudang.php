<?php

namespace App\Models;

use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gudang extends Model
{
    use Uuids;
    use SoftDeletes;
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];

    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%');
        });
        $query->when($filters['filter'] ?? false, function($query, $filter) {
            if($filter != 'semua provinsi')
                return $query->where('provinsi', $filter);
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
    public static function getActiveDeliveryOrder($id){
        return DeliveryOrder::where('gudang_id',$id)->where('status', '!=', 'SELESAI')->get()->count();
    }
    public static function getActiveSjGp($id){
        $sjPengirimanGp = SjPengirimanGp::whereRelation('peminjamanGp', 'gudang_id', $id)->whereRelation('suratJalan','status','!=','SELESAI')->get()->count();
        return $sjPengirimanGp;
    }
    public static function getActiveSjPg($id){
        $sjPengembalian = SjPengembalian::whereRelation('pengembalian.peminjaman.peminjamanGp', 'gudang_id', $id)->whereRelation('suratJalan','status','!=','SELESAI')->get()->count();
        return $sjPengembalian;
    }
}
