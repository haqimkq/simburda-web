<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
class Logistic extends Model
{
    use Uuids;
    use HasFactory;

    // protected $primaryKey = null;
    // public $incrementing = false;
    protected $guarded = ['id'];

    public function user(){
        return $this->hasOne(User::class, 'id','logistic_id');
    }
}
