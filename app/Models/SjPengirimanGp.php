<?php

namespace App\Models;

use App\Enum\PeminjamanDetailStatus;
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

class SjPengirimanGp extends Model
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
    public function peminjamanGp(){
        return $this->belongsTo(PeminjamanGp::class, 'peminjaman_id');
    }
    public function penggunaanGp(){
        return $this->belongsTo(PenggunaanGp::class, 'penggunaan_id');
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
            $old_peminjaman_gp_id = SuratJalan::find($request->surat_jalan_id)->sjPengirimanGp->peminjamanGp->id;
            if($old_peminjaman_gp_id!=$request->peminjaman_id) self::validate($request);
        }else{
            self::validate($request);
        }
    }
    public static function validate(Request $request){
        $request->validate([
            'peminjaman_id' => [
                'required',
                'exists:peminjamans,id',
                Rule::unique('sj_pengiriman_gps', 'peminjaman_id'),
                Rule::exists('peminjaman_gps', 'id')
            ]
        ]);
        $request->merge(['peminjamanId' => PeminjamanGp::find($request->peminjaman_id)->peminjaman->id]);
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
        $sj = self::create([
            'peminjaman_id' => $request->peminjaman_id,
            'surat_jalan_id' => $request->surat_jalan_id,
        ]);
        SuratJalan::setTtdAdmin($request->surat_jalan_id, $request->admin_gudang_id);
        Peminjaman::updateStatus($request->peminjamanId, PeminjamanStatus::MENUNGGU_PENGIRIMAN->value);
        return $sj;
    }
    public static function updateData(Request $request){
        $old_peminjaman_id = SuratJalan::find($request->surat_jalan_id)->sjPengirimanGp->peminjamanGp->peminjaman->id;
        if($old_peminjaman_id!==$request->peminjamanId){
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
