<?php

namespace App\Models;

use App\Helpers\Date;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
class Kendaraan extends Model
{
    use Uuids;
    use HasFactory;

    protected $guarded = ['id'];

    public function deliveryOrder(){
        return $this->hasMany(DeliveryOrder::class);
    }
    public function suratJalan(){
        return $this->hasMany(SuratJalan::class);
    }
    public function logistic(){
        return $this->hasOne(SuratJalan::class);
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%');
        });
        $query->when($filters['filter'] ?? false, function($query, $filter) {
        //    return $query->where(function($query) use ($filter) {
            if($filter != 'semua jenis')
                return $query->where('jenis', '=', $filter);
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
