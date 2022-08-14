<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
class Meminjam extends Model
{
    use Uuids;
    use HasFactory;

    protected $guarded = ['id'];

    public function barang(){
        return $this->belongsto(Barang::class, 'barang_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'supervisor_id','id');
    }

    public function proyek(){
        return $this->belongsTo(Proyek::class, 'proyek_id', 'id');
    }
}
