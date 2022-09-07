<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logistic extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    // protected $primaryKey = null;
    // public $incrementing = false;
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class, 'logistic_id','id');
    }
}
