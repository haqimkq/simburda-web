<?php

namespace App\Models;

use App\Enum\PenggunaanStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Helpers\Date;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SjPengembalianPenggunaan extends Model
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
    public function pengembalianPenggunaan(){
        return $this->belongsTo(PengembalianPenggunaan::class, 'pengembalian_penggunaan_id');
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }

    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public static function validateCreate(Request $request, $surat_jalan_created = true){
        if($surat_jalan_created){
            $request->validate([
                'surat_jalan_id' => 'required|exists:surat_jalans,id',
            ]);
            $old_pengembalian_penggunaan_id = SuratJalan::find($request->surat_jalan_id)->sjPengembalianPenggunaan->pengembalianPenggunaan->id;
            if($old_pengembalian_penggunaan_id!=$request->pengembalian_penggunaan_id) self::validate($request);
        }else{
            self::validate($request);
        }
    }
    public static function validate(Request $request){
        $request->validate([
            'pengembalian_penggunaan_id' => [
                'required',
                Rule::unique('sj_pengembalian', 'pengembalian_penggunaan_id'),
                Rule::exists('pengembalians', 'id')->where('status', PenggunaanStatus::MENUNGGU_SURAT_JALAN->value),
            ]
        ]);
    }
    public static function createData(Request $request){
        SuratJalan::setTtdAdmin($request->surat_jalan_id, $request->admin_gudang_id);
        PengembalianPenggunaan::updateStatus($request->pengembalian_penggunaan_id, PenggunaanStatus::MENUNGGU_PENGEMBALIAN->value);
        return self::create([
            'pengembalian_penggunaan_id' => $request->pengembalian_penggunaan_id,
            'surat_jalan_id' => $request->surat_jalan_id,
        ]);
    }
    public static function updateData(Request $request){
        $old_pengembalian_penggunaan_id = SuratJalan::find($request->surat_jalan_id)->sjPengembalianPenggunaan->pengembalianPenggunaan->id;
        if($old_pengembalian_penggunaan_id!=$request->pengembalian_penggunaan_id){
            self::updateKodeSurat($request);
            PengembalianPenggunaan::find($old_pengembalian_penggunaan_id)->update(['status'=>PenggunaanStatus::MENUNGGU_SURAT_JALAN->value]);
            PengembalianPenggunaan::find($request->pengembalian_penggunaan_id)->update(['status'=>PenggunaanStatus::MENUNGGU_PENGEMBALIAN->value]);
            self::where('surat_jalan_id', $request->surat_jalan_id)->update([
                'pengembalian_penggunaan_id' => $request->pengembalian_penggunaan_id,
            ]);
        }
    }
    public static function updateKodeSurat(Request $request){
        $pengembalian = PengembalianPenggunaan::find($request->pengembalian_penggunaan_id);
        $supervisor = Penggunaan::getMenanganiUser($pengembalian->penggunaan->id)->nama;
        $client = Penggunaan::getProyek($pengembalian->penggunaan->id)->client;
        SuratJalan::where('id', $request->surat_jalan_id)->update(['kode_surat'=>SuratJalan::generateKodeSurat($request->tipe, $client, $supervisor)]);
    }
}
