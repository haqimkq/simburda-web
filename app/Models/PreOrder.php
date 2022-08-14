<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
class PreOrder extends Model
{
    use Uuids;
    use HasFactory;

    protected $guarded = ['id'];

    public function deliveryOrder(){
        return $this->belongsTo(DeliveryOrder::class);
    }
}
