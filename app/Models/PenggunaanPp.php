<?php

namespace App\Models;

use App\Traits\Uuids;
use App\Helpers\Date;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenggunaanPp extends Model
{
    use Uuids;
    use SoftDeletes;
    use HasFactory;
    protected $table = 'peminjaman_pps';
    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];
    public function penggunaanPp(){
        return $this->belongsTo(SjPenggunaanPp::class, 'penggunaan_id');
    }
    // public function peminjamanAsal(){
    //     return $this->belongsTo(Peminjaman::class, 'peminjaman_asal_id');
    // }
    public function peminjaman(){
        return $this->morphOne(Penggunaan::class, 'penggunaan');
    }
    public function sjPengirimanPp(){
        return $this->hasOne(SjPengirimanPp::class, 'peminjaman_id');
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
