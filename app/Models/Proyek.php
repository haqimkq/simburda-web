<?php

namespace App\Models;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    use Uuids;
    use HasFactory;

    protected $guarded = ['id'];

    public function supervisor(){
        return $this->belongsToMany(User::class, 'menanganis','projek_id','supervisor_id');
    }

    public function proyekManager(){
        return $this->hasOne(User::class, 'id',  'proyek_manager_id');
    }
}
