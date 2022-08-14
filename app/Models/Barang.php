<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use Uuids;
    use HasFactory;

    protected $guarded = ['id'];

    public function meminjam(){
        return $this->hasOne(Meminjam::class);
    }
}
