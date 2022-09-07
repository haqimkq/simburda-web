<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menangani extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['supervisor_id','proyek_id'];
}
