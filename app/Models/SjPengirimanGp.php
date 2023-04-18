<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SjPengirimanGp extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    public function suratJalan(){
        return $this->belongsTo(SuratJalan::class);
    }
    public function peminjamanTujuan(){
        return $this->belongsTo(Peminjaman::class);
    }
}
