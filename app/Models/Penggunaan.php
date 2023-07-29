<?php

namespace App\Models;

use App\Enum\PenggunaanStatus;
use App\Enum\PenggunaanDetailStatus;
use App\Enum\PenggunaanTipe;
use App\Traits\Uuids;
use App\Helpers\Date;
use App\Helpers\IDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penggunaan extends Model
{
    use Uuids;
    use SoftDeletes;
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'penggunaans';
    protected $hidden = [
        'deleted_at',
    ];

    public function penggunaanDetail(){
        return $this->hasMany(PenggunaanDetail::class);
    }
    public function aksesBarang(){
        return $this->hasOne(AksesBarang::class);
    }
    public function penggunaanPp(){
        return $this->hasOne(PenggunaanPp::class);
    }
    public function penggunaanGp(){
        return $this->hasOne(PenggunaanGp::class);
    }
    
    public function pengembalianPenggunaan(){
        return $this->hasMany(PengembalianPenggunaan::class);
    }
    public function menangani(){
        return $this->belongsTo(Menangani::class);
    }
    public static function getProyek($id){
        return self::find($id)->menangani->proyek;
    }
    public static function doesntHaveSjPengirimanGpByAdminGudang(){
        return self::where('tipe', PenggunaanTipe::GUDANG_PROYEK->value)
        ->where('status',PenggunaanStatus::MENUNGGU_SURAT_JALAN->value)
        ->doesntHave('penggunaanGp.sjPengirimanGp')->get();
    }
    public static function doesntHaveSjPengirimanPpByAdminGudang(){
        return self::where('tipe', PenggunaanTipe::PROYEK_PROYEK->value)
        ->where('status',PenggunaanStatus::MENUNGGU_SURAT_JALAN->value)
        ->doesntHave('penggunaanPp.sjPengirimanPp')->get();
    }
    public static function getMenanganiUser($id){
        return self::find($id)->menangani->user;
    }
    public static function getAllBarang($penggunaan_id){
        $result = collect();
        $penggunaan = self::where('id',$penggunaan_id)->first();
        foreach($penggunaan->penggunaanDetail as $pd){
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
    public static function updateStatus($id, $status, $penggunaan_detail_status=null){
        $penggunaan_detail_status = (null) ? PenggunaanDetailStatus::MENUNGGU_AKSES->value : $penggunaan_detail_status;
        self::where('id', $id)->update(['status' => $status]);
        PenggunaanDetail::where('penggunaan_id', $id)->update(['status', $penggunaan_detail_status]);
    }
    public static function generateKodePenggunaan($tipe, $client, $nama_supervisor, $dateMillis=null){
        $clientAcronym = IDGenerator::getAcronym($client);
        $supervisorAcronym = IDGenerator::getAcronym($nama_supervisor);
        $romanMonth = IDGenerator::numberToRoman(Date::getMonthNumber($dateMillis));
        $year = Date::getYearNumber($dateMillis);
        $prefix = "$clientAcronym/$supervisorAcronym/$romanMonth/$year";
        $typePrefix = NULL;
        if($tipe == "PROYEK_PROYEK"){
            $typePrefix = "USEPP";
        }else{
            $typePrefix = "USEGP";
        }
        return IDGenerator::generateID(new static,'kode_penggunaan',5,"$typePrefix/$prefix");
    }
}
