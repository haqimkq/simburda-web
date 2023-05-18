<?php

namespace App\Models;

use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class SjPengirimanGp extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id'];
    public function suratJalan(){
        return $this->belongsTo(SuratJalan::class);
    }
    public function peminjamanTujuan(){
        return $this->belongsTo(Peminjaman::class);
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
            'peminjaman_id' => 'required|exists:peminjamans,id',
        ]);
        if($surat_jalan_created){
            $request->validate([
                'surat_jalan_id' => 'required|exists:surat_jalans,id',
            ]);
        }
    }
    public static function createData(Request $request, $create = true){
        self::validateCreate($request);
        $supervisor = Peminjaman::getSupervisor($request->peminjaman_id)->nama;
        $client = Peminjaman::getProyek($request->peminjaman_id)->client;
        SuratJalan::where('id', $request->surat_jalan_id)->update(['kode_surat'=>SuratJalan::generateKodeSurat($request->tipe, $client, $supervisor)]);
        if($create) return self::create([
            'peminjaman_id' => $request->peminjaman_id,
            'surat_jalan_id' => $request->surat_jalan_id,
        ]);
        else return self::make([
            'peminjaman_id' => $request->peminjaman_id,
            'surat_jalan_id' => $request->surat_jalan_id,
        ]);
    }
}
