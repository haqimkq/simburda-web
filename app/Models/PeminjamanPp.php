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
    protected $primaryKey = null;
    public $incrementing = false;
    protected $guarded = [];
    protected $hidden = [
        'deleted_at',
    ];
    public function peminjaman(){
        return $this->belongsTo(Peminjaman::class);
    }
    public function peminjamanAsal(){
        return $this->belongsTo(Peminjaman::class, 'peminjaman_asal_id');
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
