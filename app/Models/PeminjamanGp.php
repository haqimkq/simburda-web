<?php

namespace App\Models;

use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeminjamanGp extends Model
{
    use Uuids;
    use SoftDeletes;
    use HasFactory;
    protected $table = 'peminjaman_gps';
    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];

    public function sjPengirimanGp(){
        return $this->hasOne(SjPengirimanGp::class, 'peminjaman_id');
    }
    // public function peminjaman(){
    //     return $this->belongsTo(Peminjaman::class);
    // }

    public function peminjaman(){
        return $this->morphOne(Peminjaman::class, 'peminjaman');
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
