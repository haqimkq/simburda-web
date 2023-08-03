<?php

namespace App\Models;

use App\Helpers\Date;
use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];
    public function barangTidakHabisPakai(){
        return $this->hasOne(BarangTidakHabisPakai::class);
    }
    public function barangHabisPakai(){
        return $this->hasOne(BarangHabisPakai::class);
    }

    public function barangMorp(): MorphTo{
        return $this->morphTo();
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%');
        });
        $query->when($filters['filter'] ?? false, function($query, $filter) {
        //    return $query->where(function($query) use ($filter) {
            if($filter == 'tidak habis pakai')
                    return $query->where('jenis', '=', 'TIDAK_HABIS_PAKAI');
            if($filter == 'habis pakai')
                return $query->where('jenis', '=', 'HABIS_PAKAI');
            // });
        });
        $query->when($filters['filter-gudang'] ?? false, function($query, $filter) {
                if($filter != 'semua gudang')
                    return $query->where('gudang_id', '=', $filter);
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
}
