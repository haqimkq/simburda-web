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
    protected $table = 'penggunaan_gps';
    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];

    public function sjPengirimanGp(){
        return $this->hasOne(SjPengirimanGp::class, 'penggunaan_id');
    }
    public function penggunaan(){
        return $this->belongsTo(Penggunaan::class);
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
