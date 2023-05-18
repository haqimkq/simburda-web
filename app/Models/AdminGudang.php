<?php

namespace App\Models;

use App\Helpers\IDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminGudang extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $primaryKey = null;
    public $incrementing = false;
    protected $guarded = [];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function gudang(){
        return $this->belongsTo(Gudang::class);
    }

    public function suratJalan(){
        return $this->hasMany(SuratJalan::class,'admin_gudang_id','user_id');
    }
    public function aksesBarang(){
        return $this->hasMany(AksesBarang::class,'admin_gudang_id','user_id');
    }
    public function deliveryOrder(){
        return $this->hasMany(DeliveryOrder::class,'admin_gudang_id','user_id');
    }

    public static function generateKodeAG(){
        return IDGenerator::generateID(new static,'kode_ag',5,'AG');
    }
}
