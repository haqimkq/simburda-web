<?php

namespace App\Models;

use App\Enum\PengembalianStatus;
use App\Helpers\Date;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SjPengembalian extends Model
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
    public function pengembalian(){
        return $this->belongsTo(Pengembalian::class);
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
            $old_pengembalian_id = SuratJalan::find($request->surat_jalan_id)->sjPengembalian->pengembalian->id;
            if($old_pengembalian_id!=$request->pengembalian_id) self::validate($request);
        }else{
            self::validate($request);
        }
    }
    public static function validate(Request $request){
        $request->validate([
            'pengembalian_id' => [
                'required',
                Rule::unique('sj_pengembalian', 'pengembalian_id'),
                Rule::exists('pengembalians', 'id')->where('status', PengembalianStatus::MENUNGGU_SURAT_JALAN->value),
            ]
        ]);
    }
    public static function createData(Request $request){
        SuratJalan::setTtdAdmin($request->surat_jalan_id, $request->admin_gudang_id);
        Pengembalian::updateStatus($request->pengembalian_id, PengembalianStatus::MENUNGGU_PEGEMBALIAN->value);
        return self::create([
            'pengembalian_id' => $request->pengembalian_id,
            'surat_jalan_id' => $request->surat_jalan_id,
        ]);
    }
    public static function updateData(Request $request){
        $old_pengembalian_id = SuratJalan::find($request->surat_jalan_id)->sjPengembalian->pengembalian->id;
        if($old_pengembalian_id!=$request->pengembalian_id){
            self::updateKodeSurat($request);
            Pengembalian::find($old_pengembalian_id)->update(['status'=>PengembalianStatus::MENUNGGU_SURAT_JALAN->value]);
            Pengembalian::find($request->pengembalian_id)->update(['status'=>PengembalianStatus::MENUNGGU_PEGEMBALIAN->value]);
            self::where('surat_jalan_id', $request->surat_jalan_id)->update([
                'pengembalian_id' => $request->pengembalian_id,
            ]);
        }
    }
    public static function updateKodeSurat(Request $request){
        $pengembalian = Pengembalian::find($request->pengembalian_id);
        $supervisor = Peminjaman::getMenanganiUser($pengembalian->peminjaman->id)->nama;
        $client = Peminjaman::getProyek($pengembalian->peminjaman->id)->client;
        SuratJalan::where('id', $request->surat_jalan_id)->update(['kode_surat'=>SuratJalan::generateKodeSurat($request->tipe, $client, $supervisor)]);
    }
}
