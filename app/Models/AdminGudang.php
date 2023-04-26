<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminGudang extends Model
{
    use Uuids;
    use SoftDeletes;
    use HasFactory;
    protected $guarded = ['id'];
    protected $primaryKey = null;
    public $incrementing = false;
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
}