<?php

namespace App\Models;

use App\Helpers\Date;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtdSjVerification extends Model
{
    use HasFactory;
    protected $primaryKey = null;
    public $incrementing = false;
    protected $guarded = [];

    public function ttdVerification(){
        return $this->belongsTo(TtdVerification::class);
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
}
