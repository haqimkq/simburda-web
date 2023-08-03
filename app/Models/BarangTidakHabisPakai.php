<?php

namespace App\Models;

use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangTidakHabisPakai extends Model
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
    public function peminjaman(){
        return $this->belongsTo(Peminjaman::class);
    }
    public function peminjamanDetail(){
        return $this->hasMany(PeminjamanDetail::class,'barang_id');
    }
    public function pengembalianDetail(){
        return $this->hasMany(PengembalianDetail::class,'barang_id');
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }

    public function scopeSearch($query, array $search){
        $query->when($search['search'] ?? false, function($query, $search) {
            return $query->whereHas('barang', function($query) use($search){
                $query->where('merk', 'like', '%' . $search . '%');
            });
        });
    }
}
