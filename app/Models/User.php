<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\Date;
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
    protected $guarded = ['id', 'role'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'pin',
        'password',
        'remember_token',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function logistic(){
        return $this->hasOne(Logistic::class);
    }
    public function projectManager(){
        return $this->hasOne(ProjectManager::class);
    }
    public function isAdmin()
    {
        if($this->role === 'ADMIN'){ 
            return true; 
        } else { 
            return false; 
        }
    }
    public function hasRole($role)
    {
        if($this->role === $role){ 
            return true; 
        } else { 
            return false; 
        }
    }
    public function supervisor(){
        return $this->hasOne(Supervisor::class);
    }
    public function purchasing(){
        return $this->hasOne(Purchasing::class);
    }
    public function adminGudang(){
        return $this->hasOne(AdminGudang::class);
    }
    public function proyeks(){
        return $this->belongsToMany(Proyek::class,'menanganis','user_id','proyek_id');
    }
    public function menanganiProyek(){
        return $this->hasMany(Menangani::class,'user_id');
    }
    public function proyekSetManager(){
        return $this->hasMany(Proyek::class,'set_manager_id');
    }
    public function kendaraan(){
        return $this->hasOne(Kendaraan::class,'logistic_id');
    }
    public function suratJalanLogistic(){
        return $this->hasMany(SuratJalan::class,'logistic_id');
    }
    public function suratJalanAdminGudang(){
        return $this->hasMany(SuratJalan::class,'admin_gudang_id');
    }
    public function aksesBarangAdminGudang(){
        return $this->hasMany(AksesBarang::class,'admin_gudang_id');
    }
    public function aksesBarangSiteManager(){
        return $this->hasMany(AksesBarang::class,'site_manager_id');
    }
    public function deliveryOrderAdminGudang(){
        return $this->hasMany(DeliveryOrder::class,'admin_gudang_id');
    }
    public function deliveryOrderLogistic(){
        return $this->hasMany(DeliveryOrder::class,'logistic_id');
    }
    public function activeDeliveryOrderLogistic(){
        return $this->hasMany(DeliveryOrder::class,'logistic_id')->where('status','!=','SELESAI');
    }
    public function activeSJGPLogistic(){
        return $this->hasMany(SuratJalan::class,'logistic_id')->where('tipe','PENGIRIMAN_GUDANG_PROYEK')->where('status','!=','SELESAI');
    }
    public function activeSJPPLogistic(){
        return $this->hasMany(SuratJalan::class,'logistic_id')->where('tipe','PENGIRIMAN_PROYEK_PROYEK')->where('status','!=','SELESAI');
    }
    public function activeSJPGLogistic(){
        return $this->hasMany(SuratJalan::class,'logistic_id')->where('tipe','PENGEMBALIAN')->where('status','!=','SELESAI');
    }
    public function deliveryOrderPurchasing(){
        return $this->hasMany(DeliveryOrder::class,'purchasing_id');
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }

    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%');
        });
        $query->when($filters['filter'] ?? false, function($query, $filter) {
            //    return $query->where(function($query) use ($filter) {
                if($filter == 'project manager')
                    return $query->where('role', 'PROJECT_MANAGER');
                else if($filter == 'site manager')
                    return $query->where('role', 'SITE_MANAGER');
                else if($filter == 'admin gudang')
                    return $query->where('role', 'ADMIN_GUDANG');
                else if($filter == 'admin')
                    return $query->where('role', 'ADMIN');
                else if($filter == 'logistic')
                    return $query->where('role', 'LOGISTIC');
                else if($filter == 'supervisor')
                    return $query->where('role', 'SUPERVISOR');
                else if($filter == 'purchasing')
                    return $query->where('role', 'PURCHASING');
                else if($filter == 'user')
                    return $query->where('role', 'USER');
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

    public static function getTTD($user_id){
        return self::where('id', $user_id)->first()->ttd;
    }
    public static function createLogistic(Request $request){
        $request['role'] = "LOGISTIC";
        $user = self::createUser($request);
        self::validateCreateUser($request);
        Logistic::create([
            'user_id' => $user->user_id,
            'kode_logistic' => self::generateLogisticCode(),
        ]);
    }

    public static function updateLogistic(Request $request){
        self::validateCreateUser($request);
        $user=self::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => $request->password,
            'no_hp' => $request->no_hp,
            'role' => 'LOGISTIC',
        ]);
        Logistic::create([
            'user_id' => $user->user_id,
            'kode_logistic' => self::generateLogisticCode(),
        ]);
    }

     public static function validateCreateUser(Request $request){
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'no_hp' => 'required|numeric|min:11'
        ]);
    }
    public static function createUser(Request $request){
        $user=self::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => $request->password,
            'no_hp' => $request->no_hp,
            'role' => $request->role,
        ]);
        return $user;
    }
    public static function validateChangeRole(Request $request){
        $request->validate([
            'id' => 'required',
            'role' => 'required',
        ]);
    }
    public static function validateChangePin(Request $request){
        $request->validate([
            'id' => 'required',
            'pin' => 'required',
        ]);
    }
    public static function validateChangePassword(Request $request){
        $request->validate([
            'id' => 'required',
            'password' => 'required',
        ]);
    }
    public static function validateChangePhoto(Request $request){
        $request->validate([
            'foto' => 'required|image|max:2048',
        ]);
    }
    public static function validateChangeProfile(Request $request){
        $request->validate([
            'nama' => 'required',
            'email' => 'required|unique:users',
            'no_hp' => 'required',
        ]);
    }
}
