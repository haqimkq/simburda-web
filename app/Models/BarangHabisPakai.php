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

    public function barang():MorphOne{
        return $this->morphOne(Barang::class, 'barang');
    }

    // public function barang(){
    //     return $this->belongsTo(Barang::class);
    // }
    public function penggunaanDetail(){
        return $this->hasMany(PenggunaanDetail::class);
    }
    public function pengembalianPenggunaanDetail(){
        return $this->hasMany(PengembalianPenggunaanDetail::class);
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
