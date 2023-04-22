<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perusahaan extends Model
{
    use Uuids;
    use SoftDeletes;
    use HasFactory;
    protected $guarded = ['id'];

    public function deliveryOrder(){
        return $this->hasMany(DeliveryOrder::class);
    }
}
