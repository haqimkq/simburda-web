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
    protected $table = 'penggunaan_pps';
    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];
    public function sjPenggunaanPp(){
        return $this->belongsTo(SjPenggunaanPp::class, 'penggunaan_id');
    }
    public function penggunaanAsal(){
        return $this->belongsTo(Penggunaan::class, 'penggunaan_asal_id');
    }
    public function penggunaan(){
        return $this->belongsTo(Penggunaan::class);
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
