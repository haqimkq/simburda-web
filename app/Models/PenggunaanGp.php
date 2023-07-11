<?php

namespace App\Models;

use App\Traits\Uuids;
use App\Helpers\Date;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenggunaanGp extends Model
{
    use Uuids;
    use SoftDeletes;
    use HasFactory;
    protected $table = 'peminjaman_gps';
    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];

    public function sjPenggunaanGp(){
        return $this->hasOne(SjPenggunaanGp::class, 'penggunaan_id');
    }
    // public function peminjaman(){
    //     return $this->belongsTo(Peminjaman::class);
    // }

    public function penggunaan(){
        return $this->morphOne(Penggunaan::class, 'penggunaan');
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
}
