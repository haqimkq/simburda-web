<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SjPengirimanPp extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id'];

    public function suratJalan(){
        return $this->belongsTo(SuratJalan::class);
    }
    public function peminjamanTujuan(){
        return $this->belongsTo(Peminjaman::class, 'peminjaman_tujuan_id');
    }
    public function peminjamanAsal(){
        return $this->belongsTo(Peminjaman::class, 'peminjaman_asal_id');
    }
}
