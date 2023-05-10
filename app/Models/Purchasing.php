<?php

namespace App\Models;

use App\Helpers\IDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchasing extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $primaryKey = null;
    public $incrementing = false;
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function deliveryOrder(){
        return $this->hasMany(DeliveryOrder::class,'purchasing_id','user_id');
    }
    public static function generateKodePurchasing(){
        return IDGenerator::generateID(new static,'kode_purchasing',5,'PG');
    }
}
