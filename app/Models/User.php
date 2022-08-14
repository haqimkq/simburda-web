<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\Uuids;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Uuids;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'no_hp',
        'pin',
        'foto'
    ];

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

    public function logistic(){
        return $this->hasMany(DeliveryOrder::class,'logistic_id','id');
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
}
