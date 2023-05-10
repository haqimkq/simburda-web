<?php

namespace App\Models;

use App\Helpers\Date;
use App\Helpers\IDGenerator;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peminjaman extends Model
{
    use Uuids;
    use SoftDeletes;
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'peminjamans';

    public function sjPengirimanGP(){
        if($this->tipe == 'GUDANG_PROYEK') return $this->hasOne(SjPengirimanGp::class);
    }
    public function sjPengirimanPP(){
        if($this->tipe == 'PROYEK_PROYEK'){
            return $this->hasOne(SjPengirimanPp::class, 'peminjaman_tujuan_id');
        }else{
            return $this->hasOne(SjPengirimanPp::class, 'peminjaman_asal_id');
        }
    }
    public function barang(){
        return $this->hasMany(Barang::class);
    }
    public function peminjamanDetail(){
        return $this->hasMany(PeminjamanDetail::class);
    }
    public function aksesBarang(){
        return $this->hasOne(AksesBarang::class);
    }
    public function pengembalian(){
        return $this->hasMany(Pengembalian::class);
    }
    public function gudang(){
        return $this->belongsTo(Gudang::class);
    }
    public function menangani(){
        return $this->belongsTo(Menangani::class);
    }

    public static function generateKodePeminjaman($tipe, $client, $supervisor){
        $clientAcronym = IDGenerator::getAcronym($client);
        $supervisorAcronym = IDGenerator::getAcronym($supervisor);
        $romanMonth = IDGenerator::numberToRoman(Date::getMonthNumber());
        $year = Date::getYearNumber();
        $prefix = "$clientAcronym/$supervisorAcronym/$romanMonth/$year";
        $typePrefix = NULL;
        if($tipe == "PROYEK_PROYEK"){
            $typePrefix = "SEND_PP";
        }else{
            $typePrefix = "SEND_GP";
        }
        return IDGenerator::generateID(new static,'kode_peminjaman',5,"$typePrefix/$prefix");
    }
}
