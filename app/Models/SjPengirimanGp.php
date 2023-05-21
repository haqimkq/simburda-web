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
    public function peminjaman(){
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
    public static function validateCreate(Request $request, $surat_jalan_created=true, $isCreate=true){
        ($isCreate) ?
            $request->validate([
                'peminjaman_id' => 'required|exists:peminjamans,id|unique:sj_pengiriman_gps,peminjaman_id',
            ]) :
            $request->validate([
                'peminjaman_id' => 'required|exists:peminjamans,id',
            ]);
        if($surat_jalan_created){
            $request->validate([
                'surat_jalan_id' => 'required|exists:surat_jalans,id',
            ]);
        }
    }
    public static function createData(Request $request){
        self::validateCreate($request);
        self::updateKodeSurat($request);
        return self::create([
            'peminjaman_id' => $request->peminjaman_id,
            'surat_jalan_id' => $request->surat_jalan_id,
        ]);
    }
    public static function updateData(Request $request){
        self::updateKodeSurat($request);
        self::where('surat_jalan_id', $request->surat_jalan_id)->update([
            'peminjaman_id' => $request->peminjaman_id,
        ]);
        return self::where('surat_jalan_id', $request->surat_jalan_id)->first();
    }
    public static function updateKodeSurat(Request $request){
        $supervisor = Peminjaman::getSupervisor($request->peminjaman_id)->nama;
        $client = Peminjaman::getProyek($request->peminjaman_id)->client;
        SuratJalan::where('id', $request->surat_jalan_id)->update(['kode_surat'=>SuratJalan::generateKodeSurat($request->tipe, $client, $supervisor)]);
    }
}
