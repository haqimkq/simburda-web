<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengembalianPenggunaan extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];
}
