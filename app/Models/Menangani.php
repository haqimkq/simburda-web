<?php

namespace App\Models;

use App\Helpers\Date;
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
    protected $hidden = [
        'deleted_at',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function proyek(){
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }
    public function peminjaman(){
        return $this->hasMany(Peminjaman::class);
    }
    public function pembelian(){
        return $this->hasMany(Pembelian::class);
    }
    public static function getProyekIdFromUser($user_id){
        $menanganis = Self::where('user_id',$user_id)->get(['proyek_id'])->all();
        $proyeks_id = array();
        foreach($menanganis as $m){
            array_push($proyeks_id,$m->proyek_id);
        }
        return $proyeks_id;
    }
    public static function getMenanganiIdFromUser($user_id){
        $menanganis = Self::where('user_id',$user_id)->get(['id'])->all();
        $menangani_id = array();
        foreach($menanganis as $m){
            array_push($menangani_id,$m->id);
        }
        return $menangani_id;

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
