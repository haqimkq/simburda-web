<?php

namespace App\Models;

use App\Enum\PeminjamanStatus;
use App\Enum\PeminjamanTipe;
use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
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
    public function peminjamanPp(){
        return $this->belongsTo(PeminjamanPp::class, 'peminjaman_id');
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
            $old_peminjaman_pp_id = SuratJalan::find($request->surat_jalan_id)->sjPengirimanPp->peminjamanPp->id;
            if($old_peminjaman_pp_id!=$request->peminjaman_id) self::validate($request);
        }else{
            self::validate($request);
        }
    }
    public static function validate(Request $request){
        $request->validate([
            'peminjaman_id' => [
                'required',
                Rule::unique('sj_pengiriman_pps', 'peminjaman_id'),
                Rule::exists('peminjaman_pps', 'id')
            ]
        ]);
        $request->merge(['peminjamanId' => PeminjamanPp::find($request->peminjaman_id)->peminjaman->id]);
        $request->validate([
            'peminjamanId' => [
                'required',
                Rule::exists('peminjamans', 'id')
                ->where('status', PeminjamanStatus::MENUNGGU_SURAT_JALAN->value)
                ->where('tipe', PeminjamanTipe::PROYEK_PROYEK->value),
            ]
        ]);
    }
    public static function createData(Request $request){
        self::validateCreate($request);
        self::updateKodeSurat($request);
        SuratJalan::setTtdAdmin($request->surat_jalan_id, $request->admin_gudang_id);
        Peminjaman::updateStatus($request->peminjamanId, PeminjamanStatus::MENUNGGU_PENGIRIMAN->value);
        return self::create([
            'peminjaman_id' => $request->peminjaman_id,
            'surat_jalan_id' => $request->surat_jalan_id,
        ]);
    }
    public static function updateData(Request $request){
        $old_peminjaman_id = SuratJalan::find($request->surat_jalan_id)->sjPengirimanPp->peminjamanPp->peminjaman->id;
        if($old_peminjaman_id!=$request->peminjamanId){
            self::updateKodeSurat($request);
            Peminjaman::updateStatus($old_peminjaman_id, PeminjamanStatus::MENUNGGU_SURAT_JALAN->value);
            Peminjaman::updateStatus($request->peminjamanId, PeminjamanStatus::MENUNGGU_PENGIRIMAN->value);
            self::where('surat_jalan_id', $request->surat_jalan_id)->update([
                'peminjaman_id' => $request->peminjaman_id,
            ]);
        }
    }
    public static function updateKodeSurat(Request $request){
        $supervisor = Peminjaman::getMenanganiUser($request->peminjamanId)->nama;
        $client = Peminjaman::getProyek($request->peminjamanId)->client;
        SuratJalan::where('id', $request->surat_jalan_id)->update(['kode_surat'=>SuratJalan::generateKodeSurat($request->tipe, $client, $supervisor)]);
    }
}
