<?php

namespace App\Models;

use App\Enum\PeminjamanDetailStatus;
use App\Enum\PeminjamanStatus;
use App\Enum\PeminjamanTipe;
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
    protected $hidden = [
        'deleted_at',
    ];

    public function peminjamanDetail(){
        return $this->hasMany(PeminjamanDetail::class);
    }
    public function aksesBarang(){
        return $this->hasOne(AksesBarang::class);
    }
    public function peminjamanPp(){
        return $this->hasOne(PeminjamanPp::class);
    }
    public function peminjamanGp(){
        return $this->hasOne(PeminjamanGp::class);
    }
    public function pengembalian(){
        return $this->hasMany(Pengembalian::class);
    }
    public function menangani(){
        return $this->belongsTo(Menangani::class);
    }
    public static function getProyek($id){
        return self::find($id)->menangani->proyek;
    }
    public static function doesntHaveSjPengirimanGpByAdminGudang($admin_gudang_id){
        $user = User::find($admin_gudang_id);
        return self::where('tipe', PeminjamanTipe::GUDANG_PROYEK->value)
        ->where('status',PeminjamanStatus::MENUNGGU_SURAT_JALAN->value)
        ->doesntHave('peminjamanGp.sjPengirimanGp')->get();
    }
    public static function doesntHaveSjPengirimanPpByAdminGudang($admin_gudang_id){
        $user = User::find($admin_gudang_id);
        return self::where('tipe', PeminjamanTipe::PROYEK_PROYEK->value)
        ->where('status',PeminjamanStatus::MENUNGGU_SURAT_JALAN->value)
        ->doesntHave('peminjamanPp.sjPengirimanPp')->get();
    }
    public static function getSupervisor($id){
        return self::find($id)->menangani->supervisor;
    }
    public static function getAllBarang($peminjaman_id, $tipe_barang=null){
        $result = collect();
        $peminjaman = self::where('id',$peminjaman_id)->first();
        foreach($peminjaman->peminjamanDetail as $pd){
            $barang=collect();
            if($tipe_barang!=null){
                if($pd->barang->jenis == $tipe_barang) {
                    $barang['id'] = $pd->barang->id;
                    $barang['gambar'] = $pd->barang->gambar;
                    $barang['nama'] = $pd->barang->nama;
                    $barang['merk'] = $pd->barang->merk;
                    $barang['jumlah_satuan'] = $pd->jumlah_satuan;
                    if($tipe_barang == 'HABIS_PAKAI') $barang['ukuran'] = $pd->barang->barangHabisPakai->ukuran;
                    if($tipe_barang == 'TIDAK_HABIS_PAKAI') $barang['nomor_seri'] = $pd->barang->barangTidakHabisPakai->nomor_seri;
                    $result->push($barang);
                }
            }else{
                $barang['id'] = $pd->barang->id;
                $barang['gambar'] = $pd->barang->gambar;
                $barang['nama'] = $pd->barang->nama;
                $barang['merk'] = $pd->barang->merk;
                $barang['jumlah_satuan'] = $pd->jumlah_satuan;
                if($tipe_barang == 'HABIS_PAKAI') $barang['ukuran'] = $pd->barang->barangHabisPakai->ukuran;
                if($tipe_barang == 'TIDAK_HABIS_PAKAI') $barang['nomor_seri'] = $pd->barang->barangTidakHabisPakai->nomor_seri;
                $result->push($barang);
            }
        }
        return $result;
    }
    public static function updateStatus($id, $status, $peminjaman_detail_status=null){
        $peminjaman_detail_status = (null) ? PeminjamanDetailStatus::MENUNGGU_AKSES->value : $peminjaman_detail_status;
        self::where('id', $id)->update(['status' => $status]);
        PeminjamanDetail::where('peminjaman_id', $id)->update(['status', $peminjaman_detail_status]);
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
    // public function getCreatedAtAttribute($date)
    // {
    //     return Date::dateToMillisecond($date);
    // }

    // public function getUpdatedAtAttribute($date)
    // {
    //     return Date::dateToMillisecond($date);
    // }
    public function getTglPeminjamanAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }

    public function getTglBerakhirAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
}
