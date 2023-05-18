<?php

namespace App\Models;

use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SjPengirimanPp extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id'];
    public function suratJalan(){
        return $this->belongsTo(SuratJalan::class);
    }
    public function peminjamanTujuan(){
        return $this->belongsTo(Peminjaman::class, 'peminjaman_tujuan_id');
    }
    public function peminjamanAsal(){
        return $this->belongsTo(Peminjaman::class, 'peminjaman_asal_id');
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }

    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }

    public static function validateCreate(Request $request, $surat_jalan_created=true){
        $request->validate([
            'peminjaman_asal_id' => 'required|exists:peminjamans,id',
            'peminjaman_tujuan_id' => 'required|exists:peminjamans,id',
        ]);
        if($surat_jalan_created){
            $request->validate([
                'surat_jalan_id' => 'required|exists:surat_jalans,id',
            ]);
        }
    }
    public static function createData(Request $request, $create = true){
        $supervisor = Peminjaman::getSupervisor($request->peminjaman_tujuan_id)->nama;
        $client = Peminjaman::getProyek($request->peminjaman_tujuan_id)->client;
        SuratJalan::where('id', $request->surat_jalan_id)->update(['kode_surat'=>SuratJalan::generateKodeSurat($request->tipe, $client, $supervisor)]);
        if($create) return self::create([
            'peminjaman_asal_id' => $request->peminjaman_asal_id,
            'peminjaman_tujuan_id' => $request->peminjaman_tujuan_id,
            'surat_jalan_id' => $request->surat_jalan_id,
        ]);
        else return self::make([
            'peminjaman_asal_id' => $request->peminjaman_asal_id,
            'peminjaman_tujuan_id' => $request->peminjaman_tujuan_id,
            'surat_jalan_id' => $request->surat_jalan_id,
        ]);
    }
    
}
