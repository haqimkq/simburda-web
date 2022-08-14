<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
class DeliveryOrder extends Model
{
    use Uuids;
    use HasFactory;

    protected $guarded = ['id'];

    public function logistic(){
        return $this->hasOne(User::class, 'id','logistic_id');
    }

    public function purchasing(){
        return $this->hasOne(User::class,'id', 'purchasing_id');
    }

    public function preOrder(){
        return $this->hasMany(PreOrder::class);
    }

    public function kendaraan(){
        return $this->belongsTo(Kendaraan::class);
    }
}
