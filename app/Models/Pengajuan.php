<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengajuan extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function adminGudang(){
        return $this->belongsToMany(User::class,'admin_gudang_id','id');
    }

    public function projectManager(){
        return $this->belongsToMany(User::class,'project_manager_id','id');
    }
}
