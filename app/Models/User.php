<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\Uuids;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    use Uuids;
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function proyek(){
        return $this->belongsToMany(Proyek::class, 'menanganis','supervisor_id','projek_id');
    }

    public function mengawasi(){
        return $this->hasMany(Proyek::class, 'proyek_manager_id', 'id');
    }

    public function deliveryOrder(){
        return $this->hasMany(DeliveryOrder::class,'logistic_id','id');
    }
    
    public function logistic(){
        return $this->hasOne(Logistic::class,'logistic_id','id');
    }

    public function mengajukan(){
        return $this->hasMany(DeliveryOrder::class,'purchasing_id','id');
    }

    public function meminjam(){
        return $this->hasMany(Meminjam::class, 'supervisor_id','id');
    }

    public function memiliki(){
        return $this->hasMany(SuratJalan::class, 'logistic_id','id');
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%');
        });
        $query->when($filters['filter'] ?? false, function($query, $filter) {
            //    return $query->where(function($query) use ($filter) {
                if($filter != 'semua role')
                return $query->where('role', '=', $filter);
                // });
            });
        $query->when(!isset($filters['orderBy']), function($query){
            return $query->orderBy('created_at', 'DESC');
        });
        $query->when($filters['orderBy'] ?? false, function($query, $orderBy) {
            if($orderBy == 'terbaru') return $query->orderBy('created_at', 'DESC');
            if($orderBy == 'terlama') return $query->orderBy('created_at', 'ASC');
            if($orderBy == 'role') return $query->orderBy('role');
        });
    }
}
