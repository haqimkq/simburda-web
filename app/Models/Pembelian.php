<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembelian extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];

    public function menangani(){
        return $this->belongsTo(Menangani::class,'menangani_id');
    }

    public function menyetujui(){
        return $this->belongsTo(User::class,'site_manager_id');
    }
}
