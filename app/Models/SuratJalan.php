<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratJalan extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;
    
    protected $guarded = ['id'];

    public function kendaraan(){
        return $this->belongsTo(Kendaraan::class);
    }

    public function logistic(){
        return $this->belongsTo(User::class, 'logistic_id');
    }

    public function meminjam(){
        return $this->belongsTo(Meminjam::class);
    }
}
