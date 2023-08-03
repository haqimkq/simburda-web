<?php

namespace App\Models;

use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeminjamanDetail extends Model
{
    use Uuids;
    use SoftDeletes;
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];
    public function barang(){
        return $this->belongsTo(BarangTidakHabisPakai::class, 'barang_id');
    }
    public function peminjamanProyekLain(){
        return $this->belongsTo(PeminjamanPp::class, 'peminjaman_proyek_lain_id');
    }
    public function aksesBarang(){
        return $this->hasOne(AksesBarang::class);
    }
    public function peminjaman(){
        return $this->belongsTo(Peminjaman::class);
    }
    public function penanggungJawab(){
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
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
