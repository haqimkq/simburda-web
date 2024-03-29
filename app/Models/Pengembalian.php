<?php

namespace App\Models;

use App\Helpers\IDGenerator;
use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengembalian extends Model
{
    use Uuids;
    use SoftDeletes;
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];
    public function peminjaman(){
        return $this->belongsTo(Peminjaman::class);
    }
    public function pengembalianDetail(){
        return $this->hasMany(PengembalianDetail::class);
    }
    public function sjPengembalian(){
        return $this->hasOne(SjPengembalian::class);
    }
    public static function getAllBarang($pengembalian_id){
        $result = collect();
        $pengembalian = self::where('id',$pengembalian_id)->first();
        foreach($pengembalian->pengembalianDetail as $pd){
            $barang = collect();
            $barang['id'] = $pd->barang->barang->id;
            $barang['gambar'] = $pd->barang->barang->gambar;
            $barang['nama'] = $pd->barang->barang->nama;
            $barang['merk'] = $pd->barang->barang->merk;
            $barang['nomor_seri'] = $pd->barang->nomor_seri;
            $result->push($barang);
        }
        return $result;
    }
    public static function generateKodePengembalian($client, $supervisor){
        $clientAcronym = IDGenerator::getAcronym($client);
        $supervisorAcronym = IDGenerator::getAcronym($supervisor);
        $romanMonth = IDGenerator::numberToRoman(Date::getMonthNumber());
        $year = Date::getYearNumber();
        $prefix = "$clientAcronym/$supervisorAcronym/$romanMonth/$year";
        $typePrefix = "RTRN";
        return IDGenerator::generateID(new static,'kode_pengembalian',5,"$typePrefix/$prefix");
    }
    public static function updateStatus($id, $status){
        self::where('id', $id)->update(['status' => $status]);
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
}
