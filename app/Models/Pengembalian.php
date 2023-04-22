<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengembalian extends Model
{
    use Uuids;
    use SoftDeletes;
    use HasFactory;
    protected $guarded = ['id'];
    public function peminjaman(){
        return $this->belongsTo(Peminjaman::class);
    }
    public function pengembalianDetail(){
        return $this->hasMany(PengembalianDetail::class);
    }
}
