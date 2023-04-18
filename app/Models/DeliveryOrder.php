<?php

namespace App\Models;

use App\Helpers\Date;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryOrder extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function user(){
        return $this->hasOne(User::class, 'id','logistic_id');
    }

    public function purchasing(){
        return $this->hasOne(User::class,'id', 'purchasing_id');
    }

    public function preOrder(){
        return $this->hasMany(PreOrder::class);
    }

    public function kendaraan(){
        return $this->belongsTo(Kendaraan::class);
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('kode_delivery', 'like', '%' . $search . '%');
        });
        $query->when($filters['filter'] ?? false, function($query, $filter) {
        //    return $query->where(function($query) use ($filter) {
            if($filter != 'semua status'){
                if($filter == 'sudah diambil')
                    return $query->where('status', 'Selesai');
                if($filter == 'dalam perjalanan')
                    return $query->where('status', 'Driver dalam perjalanan');
                if($filter == 'belum diambil')
                    return $query->where('status', 'Menunggu konfirmasi driver');
            }
            // });
        });
        $query->when(!isset($filters['orderBy']), function($query){
            return $query->orderBy('created_at', 'DESC');
        });
        $query->when($filters['orderBy'] ?? false, function($query, $orderBy) {
            if($orderBy == 'terbaru') return $query->orderBy('created_at', 'DESC');
            if($orderBy == 'terlama') return $query->orderBy('created_at', 'ASC');
        });
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateFormatter($date, 'ddd, D MMM YYYY');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Date::dateFormatter($date, 'ddd, D MMM YYYY');
    }
}
