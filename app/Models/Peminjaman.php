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
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

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
    public static function doesntHaveSjPengirimanGpByAdminGudang(){
        return self::where('tipe', PeminjamanTipe::GUDANG_PROYEK->value)
        ->where('status',PeminjamanStatus::MENUNGGU_SURAT_JALAN->value)
        ->doesntHave('peminjamanGp.sjPengirimanGp')->get();
    }
    public static function doesntHaveSjPengirimanPpByAdminGudang(){
        return self::where('tipe', PeminjamanTipe::PROYEK_PROYEK->value)
        ->where('status',PeminjamanStatus::MENUNGGU_SURAT_JALAN->value)
        ->doesntHave('peminjamanPp.sjPengirimanPp')->get();
    }
    public static function getMenanganiUser($id){
        return self::find($id)->menangani->user;
    }
    public static function getAllBarang($peminjaman_id){
        $result = collect();
        $peminjaman = self::where('id',$peminjaman_id)->first();
        foreach($peminjaman->peminjamanDetail as $pd){
            if($pd->aksesBarang->disetujui_sm && $pd->aksesBarang->disetujui_admin){
                $barang = collect();
                $barang['id'] = $pd->id;
                $barang['gambar'] = $pd->barang->barang->gambar;
                $barang['nama'] = $pd->barang->barang->nama;
                $barang['merk'] = $pd->barang->barang->merk;
                $barang['nomor_seri'] = $pd->barang->nomor_seri;
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
    public static function generateKodePeminjaman($tipe, $client, $supervisor, $date=null){
        $clientAcronym = IDGenerator::getAcronym($client);
        $supervisorAcronym = IDGenerator::getAcronym($supervisor);
        $romanMonth = IDGenerator::numberToRoman(Date::getMonthNumber($date));
        $year = Date::getYearNumber($date);
        $prefix = "$clientAcronym/$supervisorAcronym/$romanMonth/$year";
        $typePrefix = NULL;
        if($tipe == "PROYEK_PROYEK"){
            $typePrefix = "BRWPP";
        }else{
            $typePrefix = "BRWGP";
        }
        return IDGenerator::generateID(new static,'kode_peminjaman',5,"$typePrefix/$prefix");
    }
    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('kode_peminjaman', 'like', '%' . $search . '%');
        });
        $query->when($filters['filter'] ?? false, function($query, $filter) {
            return $query->where(function($query) use ($filter) {
                if($filter == 'menunggu akses')
                    return $query->where('status', 'MENUNGGU_AKSES');
                if($filter == 'menunggu surat jalan')
                    return $query->where('status', 'MENUNGGU_SURAT_JALAN');
                if($filter == 'menunggu pengiriman')
                    return $query->where('status', 'MENUNGGU_PENGIRIMAN');
                if($filter == 'sedang dikirim')
                    return $query->where('status', 'SEDANG_DIKIRIM');
                if($filter == 'dipinjam')
                    return $query->where('status', 'DIPINJAM');
                if($filter == 'selesai')
                    return $query->where('status', 'SELESAI');
            });
        });
        $query->when(!isset($filters['orderBy']), function($query){
            return $query->orderBy('created_at', 'DESC');
        });
        $query->when(!isset($filters['datestart']), function($query){
            return $query->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime("-3 years")),date('Y-m-d 23:59:59')]);
        });
        $query->when($filters['orderBy'] ?? false, function($query, $orderBy) {
            if($orderBy == 'terbaru') return $query->orderBy('created_at', 'DESC');
            if($orderBy == 'terlama') return $query->orderBy('created_at', 'ASC');
        });
        $query->when($filters['datestart'] ?? false, function($query, $datestart) use ($filters){
            $date_start = $datestart." 00:00:00";
            $query->when($filters['dateend'] ?? false, function($query, $dateend) use ($date_start) {
                $date_end = $dateend." 23:59:59";
                return $query->whereBetween('created_at', [$date_start, $date_end]);
            });
        });
    }
    public function scopeProyek($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->whereRelation('menangani.proyek','nama_proyek', 'like', '%' . $search . '%');
        });
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function getTglPeminjamanAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function getTglBerakhirAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function getRemainingDaysAttribute(){
        date_default_timezone_set('Asia/Jakarta');
        if ($this->tgl_berakhir) {
            $remaining_days = Carbon::now()->diffInDays(Carbon::parse($this->tgl_berakhir));
        } else {
            $remaining_days = 0;
        }
        return $remaining_days;
    }
}
