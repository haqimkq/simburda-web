<?php

namespace App\Models;

use App\Helpers\Date;
use App\Helpers\IDGenerator;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeminjamanPp extends Model
{
    use Uuids;
    use SoftDeletes;
    use HasFactory;
    protected $table = 'peminjaman_pps';
    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];
    public function peminjamanPp(){
        return $this->belongsTo(PeminjamanPp::class, 'peminjaman_id');
    }
    public function peminjamanAsal(){
        return $this->belongsTo(Peminjaman::class, 'peminjaman_asal_id');
    }
    // public function peminjaman(){
    //     return $this->morphOne(Peminjaman::class, 'peminjaman');
    // }
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
