<?php

namespace App\Models;

use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangHabisPakai extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];
    public function barang(){
        return $this->belongsTo(Barang::class);
    }
    public function penggunaanDetail(){
        return $this->hasMany(PenggunaanDetail::class,'barang_id');
    }
    public function pengembalianPenggunaanDetail(){
        return $this->hasMany(PengembalianPenggunaanDetail::class,'barang_id');
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
            return $query->whereRelation('barang','merk', 'like', '%' . $search . '%');
        });
        $query->when($filters['filter'] ?? false, function($query, $filter) {
        //    return $query->where(function($query) use ($filter) {
            if($filter == 'dipinjam')
                    return $query->where('peminjaman_id', '!=', null);
            if($filter == 'digudang')
                return $query->where('peminjaman_id', '=', null);
            // });
        });
    }
}
