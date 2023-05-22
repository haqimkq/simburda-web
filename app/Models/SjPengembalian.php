<?php

namespace App\Models;

use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SjPengembalian extends Model
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
    public function pengembalian(){
        return $this->belongsTo(Pengembalian::class);
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
        $request->validate([
            'pengembalian_id' => [
                'required',
                'exists:pengembalians,id',
                Rule::unique('sj_pengembalian', 'pengembalian_id')->ignore($request->surat_jalan_id, 'surat_jalan_id'),
            ]
        ]);
        if($surat_jalan_created){
            $request->validate([
                'surat_jalan_id' => 'required|exists:surat_jalans,id',
            ]);
        }
    }
    public static function createData(Request $request, $create = true){
        if($create) return self::create([
            'pengembalian_id' => $request->pengembalian_id,
            'surat_jalan_id' => $request->surat_jalan_id,
        ]);
    }
    public static function updateData(Request $request){
        self::updateKodeSurat($request);
        self::where('surat_jalan_id', $request->surat_jalan_id)->update([
            'pengembalian_id' => $request->pengembalian_id,
            'surat_jalan_id' => $request->surat_jalan_id,
        ]);
        return self::where('surat_jalan_id', $request->surat_jalan_id)->first();
    }
    public static function updateKodeSurat(Request $request){
        $peminjaman = Pengembalian::find($request->pengembalian_id)->peminjaman;
        $supervisor = Peminjaman::getSupervisor($peminjaman->id)->nama;
        $client = Peminjaman::getProyek($peminjaman->id)->client;
        SuratJalan::where('id', $request->surat_jalan_id)->update(['kode_surat'=>SuratJalan::generateKodeSurat($request->tipe, $client, $supervisor)]);
    }
}
