<?php

namespace App\Models;

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
    public function barang(){
        return $this->belongsTo(Barang::class);
    }
    public function peminjamanProyekLain(){
        return $this->belongsTo(Peminjaman::class, 'peminjaman_proyek_lain_id');
    }
    public function peminjaman(){
        return $this->belongsTo(Peminjaman::class);
    }
}
