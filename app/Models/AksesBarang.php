<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AksesBarang extends Model
{
    use Uuids;
    use HasFactory;

    protected $guarded = ['id'];

    public function meminjam(){
        return $this->belongsTo(Meminjam::class);
    }
}
