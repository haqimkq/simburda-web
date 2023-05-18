<?php

namespace App\Models;

use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Null_;

class AksesBarang extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function peminjaman(){
        return $this->belongsTo(Peminjaman::class);
    }

    public function projectManager(){
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function adminGudang(){
        return $this->belongsTo(User::class, 'admin_gudang_id');
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('nama_proyek', 'like', '%' . $search . '%');
        });
        $authUserRole = Auth::user()->role;
        $query->when($filters['filter'] ?? false, function($query, $filter) use ($authUserRole) {
        //    return $query->where(function($query) use ($filter) {
            if($filter != 'semua-akses'){
                if($filter == 'disetujui-admin')
                    return $query->where('disetujui_admin', true);
                if($filter == 'disetujui-pm')
                    return $query->where('disetujui_pm', true);
                if($filter == 'ditolak-admin')
                    return $query->where('disetujui_admin', false);
                if($filter == 'ditolak-pm')
                    return $query->where('disetujui_pm', false);
                if($filter == 'disetujui-admin-dan-pm')
                    return $query->where('disetujui_pm', true)->where('disetujui_admin', true);
                if($filter == 'akses-belum-ditentukan-admin')
                    return $query->where('disetujui_admin', NULL);
                if($filter == 'akses-belum-ditentukan-pm')
                    return $query->where('disetujui_pm', NULL);
                if($filter == 'akses-belum-ditentukan' && ($authUserRole == 'admin' || $authUserRole == 'supervisor'))
                    return $query->where('disetujui_admin', NULL)->where('disetujui_pm', NULL);
                if($filter == 'akses-belum-ditentukan' && $authUserRole == 'admin gudang')
                    return $query->where('disetujui_admin', NULL);
                if($filter == 'akses-belum-ditentukan' && $authUserRole == 'project manager')
                    return $query->where('disetujui_pm', NULL);
            }
            // });
        });
        $query->when(!isset($filters['orderBy']), function($query){
            return $query->orderBy('akses_barangs.created_at', 'DESC');
        });
        $query->when(!isset($filters['filter']) && ($authUserRole == 'admin' || $authUserRole == 'supervisor'), function($query){
            return $query->where('disetujui_admin', NULL)->where('disetujui_pm', NULL);
        });
        $query->when(!isset($filters['filter']) && $authUserRole == 'project manager', function($query){
            return $query->where('disetujui_pm', NULL);
        });
        $query->when(!isset($filters['filter']) && $authUserRole == 'admin gudang', function($query){
            return $query->where('disetujui_admin', NULL);
        });
        $query->when($filters['orderBy'] ?? false, function($query, $orderBy) {
            if($orderBy == 'terbaru') return $query->orderBy('akses_barangs.created_at', 'DESC');
            if($orderBy == 'terlama') return $query->orderBy('akses_barangs.created_at', 'ASC');
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

    public static function countUndefinedAkses() {
        $authUser = Auth::user();
        if($authUser->role=='admin'){
            return self::where('disetujui_admin', NULL)->orWhere('disetujui_pm',NULL)->count();
        }else if($authUser->role=='project manager'){
            return self::whereHas('meminjam.proyek', fn($q) => $q->where('proyek_manager_id',$authUser->id))->where('disetujui_pm',NULL)->count();
        }else if($authUser->role=='admin gudang'){
            return self::where('disetujui_admin',NULL)->count();
        }
    }
}
