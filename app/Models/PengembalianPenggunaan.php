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
        return $this->hasMany(PengembalianPenggunaanDetail::class);
    }
    public function sjPengembalianPenggunaan(){
        return $this->hasOne(SjPengembalianPenggunaan::class);
    }
    public static function getAllBarang($pengembalian_id, $tipe_barang=null){
        $result = collect();
        $pengembalian = self::where('id',$pengembalian_id)->first();
        foreach($pengembalian->pengembalianDetail as $pd){
            $barang = collect();
            if($tipe_barang!=null){
                if($pd->barang->jenis == $tipe_barang) {
                    $barang['id'] = $pd->barang->id;
                    $barang['gambar'] = $pd->barang->gambar;
                    $barang['nama'] = $pd->barang->nama;
                    $barang['merk'] = $pd->barang->merk;
                    $barang['jumlah_satuan'] = $pd->jumlah_satuan;
                    if($tipe_barang == 'TIDAK_HABIS_PAKAI') $barang['nomor_seri'] = $pd->barang->barangTidakHabisPakai->nomor_seri;
                    if($tipe_barang == 'HABIS_PAKAI') $barang['ukuran'] = $pd->barang->barangHabisPakai->ukuran;
                    $result->push($barang);
                }
            }else{
                $barang['id'] = $pd->barang->id;
                $barang['gambar'] = $pd->barang->gambar;
                $barang['nama'] = $pd->barang->nama;
                $barang['merk'] = $pd->barang->merk;
                $barang['jumlah_satuan'] = $pd->jumlah_satuan;
                if($tipe_barang == 'TIDAK_HABIS_PAKAI') $barang['nomor_seri'] = $pd->barang->barangTidakHabisPakai->nomor_seri;
                if($tipe_barang == 'HABIS_PAKAI') $barang['ukuran'] = $pd->barang->barangHabisPakai->ukuran;
                $result->push($barang);
            }
        }
        return $result;
    }
    public static function generateKodePengembalian($client, $supervisor){
        $clientAcronym = IDGenerator::getAcronym($client);
        $supervisorAcronym = IDGenerator::getAcronym($supervisor);
        $romanMonth = IDGenerator::numberToRoman(Date::getMonthNumber());
        $year = Date::getYearNumber();
        $prefix = "$clientAcronym/$supervisorAcronym/$romanMonth/$year";
        $typePrefix = "RETURN";
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
