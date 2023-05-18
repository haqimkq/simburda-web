<?php

namespace App\Models;

use App\Helpers\Date;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meminjam extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function barang(){
        return $this->belongsto(Barang::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'supervisor_id','id');
    }

    public function proyek(){
        return $this->belongsTo(Proyek::class, 'proyek_id', 'id');
    }

    public function suratJalan(){
        return $this->belongsTo(SuratJalan::class, 'surat_jalan_id', 'id');
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function getTglPeminjamanAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function getTglBerakhirAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
}
