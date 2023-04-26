<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menangani extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function supervisor(){
        return $this->belongsTo(User::class, 'supervisor_id');
    }
    public function proyek(){
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }
}
