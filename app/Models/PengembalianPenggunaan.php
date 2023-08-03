<?php

namespace App\Models;

use App\Helpers\IDGenerator;
use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengembalianPenggunaan extends Model
{
    use Uuids;
    use SoftDeletes;
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];
    public function penggunaan(){
        return $this->belongsTo(Penggunaan::class);
    }
    public function pengembalianPenggunaanDetail(){
        return $this->hasMany(PengembalianPenggunaanDetail::class,'pengembalian_bahan_id');
    }
    public function sjPengembalianPenggunaan(){
        return $this->hasOne(SjPengembalian::class,'pengembalian_penggunaan_id');
    }
    public static function getAllBarang($pengembalianPenggunaan_id){
        $result = collect();
        $pengembalianPenggunaan = self::where('id',$pengembalianPenggunaan_id)->first();
        foreach($pengembalianPenggunaan->pengembalianPenggunaanDetail as $pd){
            $barang = collect();
            $barang['id'] = $pd->id;
            $barang['gambar'] = $pd->barang->barang->gambar;
            $barang['nama'] = $pd->barang->barang->nama;
            $barang['merk'] = $pd->barang->barang->merk;
            $barang['jumlah_satuan'] = $pd->barang->jumlah_satuan;
            $barang['ukuran'] = $pd->barang->barangHabisPakai->ukuran;
            $result->push($barang);
        }
        return $result;
    }
    public static function generateKodePengembalian($client, $supervisor, $date=null){
        $clientAcronym = IDGenerator::getAcronym($client);
        $supervisorAcronym = IDGenerator::getAcronym($supervisor);
        $romanMonth = IDGenerator::numberToRoman(Date::getMonthNumber($date));
        $year = Date::getYearNumber($date);
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
