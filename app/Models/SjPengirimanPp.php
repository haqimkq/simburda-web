<?php

namespace App\Models;

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
    use Uuids;
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];
    public function suratJalan(){
        return $this->belongsTo(SuratJalan::class);
    }
    public function peminjaman(){
        return $this->belongsTo(Peminjaman::class, 'peminjaman_tujuan_id');
    }
    public function ttdSupervisorPeminjam(){
        return $this->belongsTo(TtdSjVerification::class, 'ttd_supervisor_peminjam');
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
            'peminjaman_asal_id' => [
                'required',
                'exists:peminjamans,id',
            ],
            'peminjaman_tujuan_id' => [
                'required',
                'exists:peminjamans,id',
                Rule::unique('sj_pengiriman_pps', 'peminjaman_tujuan_id')->ignore($request->surat_jalan_id, 'surat_jalan_id'),
            ]
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
            'peminjaman_asal_id' => $request->peminjaman_asal_id,
            'peminjaman_tujuan_id' => $request->peminjaman_tujuan_id,
            'surat_jalan_id' => $request->surat_jalan_id,
        ]);
    }
    public static function updateData(Request $request){
        self::updateKodeSurat($request);
        self::where('surat_jalan_id', $request->surat_jalan_id)->update([
            'peminjaman_asal_id' => $request->peminjaman_asal_id,
            'peminjaman_tujuan_id' => $request->peminjaman_tujuan_id,
            'surat_jalan_id' => $request->surat_jalan_id,
        ]);
        return self::where('surat_jalan_id', $request->surat_jalan_id)->first();
    }
    public static function updateKodeSurat(Request $request){
        $supervisor = Peminjaman::getSupervisor($request->peminjaman_tujuan_id)->nama;
        $client = Peminjaman::getProyek($request->peminjaman_tujuan_id)->client;
        SuratJalan::where('id', $request->surat_jalan_id)->update(['kode_surat'=>SuratJalan::generateKodeSurat($request->tipe, $client, $supervisor)]);
    }
}
