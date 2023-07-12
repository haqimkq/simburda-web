<?php

namespace App\Models;

use App\Enum\PenggunaanStatus;
use App\Enum\PenggunaanTipe;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Date;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class SjPenggunaanGp extends Model
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
            $old_penggunaan_gp_id = SuratJalan::find($request->surat_jalan_id)->sjPengirimanGp->penggunaanGp->id;
            if($old_penggunaan_gp_id!=$request->penggunaan_id) self::validate($request);
        }else{
            self::validate($request);
        }
    }
    public static function validate(Request $request){
        $request->validate([
            'penggunaan_id' => [
                'required',
                'exists:penggunaans,id',
                Rule::unique('sj_pengiriman_gps', 'penggunaan_id'),
                Rule::exists('penggunaan_gps', 'id')
            ]
        ]);
        $request->merge(['penggunaanId' => PenggunaanGp::find($request->penggunaan_id)->penggunaan->id]);
        $request->validate([
            'penggunaanId' => [
                'required',
                Rule::exists('penggunaans', 'id')
                ->where('status', PenggunaanStatus::MENUNGGU_SURAT_JALAN->value)
                ->where('tipe', PenggunaanTipe::PROYEK_PROYEK->value),
            ]
        ]);
    }
    public static function createData(Request $request){
        self::validateCreate($request);
        self::updateKodeSurat($request);
        $sj = self::create([
            'penggunaan_id' => $request->penggunaan_id,
            'surat_jalan_id' => $request->surat_jalan_id,
        ]);
        SuratJalan::setTtdAdmin($request->surat_jalan_id, $request->admin_gudang_id);
        Penggunaan::updateStatus($request->penggunaanId, PenggunaanStatus::MENUNGGU_PENGIRIMAN->value);
        return $sj;
    }
    public static function updateData(Request $request){
        $old_penggunaan_id = SuratJalan::find($request->surat_jalan_id)->sjPengirimanGp->penggunaanGp->penggunaan->id;
        if($old_penggunaan_id!==$request->penggunaanId){
            self::updateKodeSurat($request);
            Penggunaan::updateStatus($old_penggunaan_id, PenggunaanStatus::MENUNGGU_SURAT_JALAN->value);
            Penggunaan::updateStatus($request->penggunaanId, PenggunaanStatus::MENUNGGU_PENGIRIMAN->value);
            
            self::where('surat_jalan_id', $request->surat_jalan_id)->update([
                'penggunaan_id' => $request->penggunaan_id,
            ]);
        }
    }
    public static function updateKodeSurat(Request $request){
        $supervisor = Penggunaan::getMenanganiUser($request->penggunaanId)->nama;
        $client = Penggunaan::getProyek($request->penggunaanId)->client;
        SuratJalan::where('id', $request->surat_jalan_id)->update(['kode_surat'=>SuratJalan::generateKodeSurat($request->tipe, $client, $supervisor)]);
    }
}
