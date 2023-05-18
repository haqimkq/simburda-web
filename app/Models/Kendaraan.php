<?php

namespace App\Models;

use App\Helpers\Date;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Kendaraan extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function deliveryOrders(){
        return $this->hasMany(DeliveryOrder::class);
    }
    public function suratJalans(){
        return $this->hasMany(SuratJalan::class);
    }
    public function user(){
        return $this->hasOne(User::class, 'id', 'logistic_id');
    }
    public function gudang(){
        return $this->hasOne(Gudang::class);
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('merk', 'like', '%' . $search . '%');
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
        return Date::dateToMillisecond($date);
    }
    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public static function setLogistic(Request $request){
        self::validateUpdateLogistic($request);
        self::where('id', $request->kendaraan_id)->update(['logistic_id'=>$request->logistic_id]);
    }
    public static function validateUpdateLogistic(Request $request){
        $request->validate([
            'logistic_id' => 'required|exists:logistics,user_id',
            'kendaraan_id' => 'required|exists:kendaraans,id',
        ]);
    }
}
