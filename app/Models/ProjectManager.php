<?php

namespace App\Models;

use App\Helpers\Date;
use App\Helpers\IDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectManager extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $primaryKey = null;
    public $incrementing = false;
    protected $guarded = [];
    protected $hidden = [
        'deleted_at',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function proyek(){
        return $this->hasMany(Proyek::class,'project_manager_id','user_id');
    }
    public function aksesBarang(){
        return $this->hasMany(AksesBarang::class,'project_manager_id','user_id');
    }
    public static function generateKodePM(){
        return IDGenerator::generateID(new static,'kode_pm',5,'PM');
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
