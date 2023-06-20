<?php

namespace App\Models;

use App\Enum\PeminjamanStatus;
use App\Enum\PeminjamanTipe;
use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SjPengirimanPp extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $primaryKey = null;
    public $incrementing = false;
    protected $guarded = [];
    protected $hidden = [
        'deleted_at',
    ];
    public function suratJalan(){
        return $this->belongsTo(SuratJalan::class);
    }
    public function peminjaman(){
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }
    public function ttdSupervisorPeminjam(){
        return $this->belongsTo(TtdVerification::class, 'ttd_supervisor_peminjam');
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
        if($surat_jalan_created){
            $request->validate([
                'surat_jalan_id' => 'required|exists:surat_jalans,id',
            ]);
            $request->validate([
                'peminjaman_id' => [
                    'required',
                    'exists:peminjamans,id',
                    Rule::unique('sj_pengiriman_pps', 'peminjaman_id')->ignore($request->surat_jalan_id, 'surat_jalan_id'),
                ]
            ]);
        }else{
            $request->validate([
                'peminjaman_id' => [
                    'required',
                    Rule::exists('peminjamans', 'id')
                    ->where('status', PeminjamanStatus::MENUNGGU_SURAT_JALAN->value)
                    ->where('tipe', PeminjamanTipe::PROYEK_PROYEK->value),
                ]
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
        $sj = self::where('surat_jalan_id', $request->surat_jalan_id)->update([
            'peminjaman_id' => $request->peminjaman_id,
            'surat_jalan_id' => $request->surat_jalan_id,
        ]);
        return $sj;
    }
    public static function updateKodeSurat(Request $request){
        $supervisor = Peminjaman::getSupervisor($request->peminjaman_id)->nama;
        $client = Peminjaman::getProyek($request->peminjaman_id)->client;
        SuratJalan::where('id', $request->surat_jalan_id)->update(['kode_surat'=>SuratJalan::generateKodeSurat($request->tipe, $client, $supervisor)]);
    }
}
