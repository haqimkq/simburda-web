<?php

namespace App\Models;

use App\Helpers\Date;
use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proyek extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function supervisors(){
        return $this->belongsToMany(User::class,'menanganis','proyek_id','supervisor_id');
    }

    public function projectManager(){
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public static function filterBetweenDate($start_date, $end_date){
        $dateS = new Carbon($start_date);
        $dateE = new Carbon($end_date);
        return Proyek::whereBetween('created_at', [$dateS->format('Y-m-d')." 00:00:00", $dateE->format('Y-m-d')." 23:59:59"])->get();
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('nama_proyek', 'like', '%' . $search . '%');
        });
        $query->when($filters['filter'] ?? false, function($query, $filter) {
        //    return $query->where(function($query) use ($filter) {
            if($filter != 'semua status'){
                if($filter == 'selesai')
                    return $query->where('selesai', true);
                if($filter == 'masih berlangsung')
                    return $query->where('selesai', false);
            }
            // });
        });
        $query->when(!isset($filters['orderBy']), function($query){
            return $query->orderBy('created_at', 'DESC');
        });
        $query->when($filters['orderBy'] ?? false, function($query, $orderBy) {
            if($orderBy == 'terbaru') return $query->orderBy('created_at', 'DESC');
            if($orderBy == 'terlama') return $query->orderBy('created_at', 'ASC');
            if($orderBy == 'jumlah tersedikit') return $query->orderBy('jumlah', 'ASC');
            if($orderBy == 'jumlah terbanyak') return $query->orderBy('jumlah', 'DESC');
        });
        $query->when($filters['date'] ?? false, function($query, $date) {
            return $query->whereBetween('nama_proyek', 'like', '%' . $date . '%');
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
    public function getTglSelesaiAttribute($date)
    {
        if($date) return Date::dateToMillisecond($date);
    }
}
