<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supervisor extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $guarded = ['id'];
    protected $primaryKey = null;
    public $incrementing = false;
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function proyek(){
        return $this->belongsToMany(Proyek::class,'menanganis','supervisor_id','proyek_id');
    }
}
