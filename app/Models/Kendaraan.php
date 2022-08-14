<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
class Kendaraan extends Model
{
    use Uuids;
    use HasFactory;

    protected $guarded = ['id'];

    public function deliveryOrder(){
        return $this->hasMany(DeliveryOrder::class);
    }
}
