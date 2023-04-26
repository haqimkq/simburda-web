<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengembalianDetail extends Model
{
    use Uuids;
    use SoftDeletes;
    use HasFactory;
    protected $guarded = ['id'];
    public function barang(){
        return $this->belongsTo(Barang::class);
    }
    public function pengembalian(){
        return $this->belongsTo(Pengembalian::class);
    }
}