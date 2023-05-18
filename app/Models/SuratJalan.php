<?php

namespace App\Models;

use App\Helpers\Date;
use App\Helpers\IDGenerator;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SuratJalan extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function kendaraan(){
        return $this->belongsTo(Kendaraan::class);
    }

    public function logistic(){
        return $this->belongsTo(User::class, 'logistic_id');
    }

    public function adminGudang(){
        return $this->belongsTo(User::class, 'admin_gudang_id');
    }
    
    public function sjPengirimanPP(){
        return $this->hasOne(SjPengirimanPp::class);
    }

    public function sjPengirimanGP(){
        return $this->hasOne(SjPengirimanGp::class);
    }

    public function sjPengembalian(){
        return $this->hasOne(SjPengembalian::class);
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
    }

    public static function generateKodeSurat($tipe, $client, $supervisor){
        $clientAcronym = IDGenerator::getAcronym($client);
        $supervisorAcronym = IDGenerator::getAcronym($supervisor);
        $romanMonth = IDGenerator::numberToRoman(Date::getMonthNumber());
        $year = Date::getYearNumber();
        $prefix = "$clientAcronym/$supervisorAcronym/$romanMonth/$year";
        $typePrefix = NULL;
        if($tipe == "PENGIRIMAN_PROYEK_PROYEK"){
            $typePrefix = "SJPP";
        }else if($tipe == "PENGIRIMAN_GUDANG_PROYEK"){
            $typePrefix = "SJGP";
        }else{
            $typePrefix = "SJPG";
        }
        return IDGenerator::generateID(SuratJalan::class,'kode_surat',5,"$typePrefix/$prefix");
    }
    public static function createData(Request $request, $create = true){
        self::validateCreate($request);
        if($create) return self::create([
            'admin_gudang_id' => $request->admin_gudang_id,
            'logistic_id' => $request->logistic_id,
            'kendaraan_id' => $request->kendaraan_id,
            'ttd_admin' => $request->ttd_admin,
            'tipe' => $request->tipe,
        ]);
        else return self::make([
            'admin_gudang_id' => $request->admin_gudang_id,
            'logistic_id' => $request->logistic_id,
            'ttd_admin' => $request->ttd_admin,
            'kendaraan_id' => $request->kendaraan_id,
            'tipe' => $request->tipe,
        ]);
    }
    public static function validateCreate(Request $request){
        $request->validate([
            'admin_gudang_id' => 'required|exists:users,id',
            'logistic_id' => 'required|exists:users,id',
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'tipe' => ['required', Rule::in(['PENGIRIMAN_GUDANG_PROYEK', 'PENGIRIMAN_PROYEK_PROYEK', 'PENGEMBALIAN'])],
        ]);
        $request->merge(['ttd_admin' => User::getTTD($request->admin_gudang_id)]);
        $request->validate([
            'ttd_admin' => 'required',
        ]);
        if($request->tipe=='PENGIRIMAN_PROYEK_PROYEK'){
            SjPengirimanPp::validateCreate($request,false);
        }else if($request->tipe=='PENGIRIMAN_GUDANG_PROYEK'){
            SjPengirimanGp::validateCreate($request,false);
        }else{
            SjPengembalian::validateCreate($request,false);
        }
    }

}
