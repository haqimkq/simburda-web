<?php

namespace App\Models;

use App\Helpers\IDGenerator;
use App\Helpers\Date;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengembalian extends Model
{
    use Uuids;
    use SoftDeletes;
    use HasFactory;
    protected $guarded = ['id'];
    public function peminjaman(){
        return $this->belongsTo(Peminjaman::class);
    }
    public function pengembalianDetail(){
        return $this->hasMany(PengembalianDetail::class);
    }
    public function sjPengembalian(){
        return $this->hasOne(SjPengembalian::class);
    }
    public static function generateKodePengembalian($client, $supervisor){
        $clientAcronym = IDGenerator::getAcronym($client);
        $supervisorAcronym = IDGenerator::getAcronym($supervisor);
        $romanMonth = IDGenerator::numberToRoman(Date::getMonthNumber());
        $year = Date::getYearNumber();
        $prefix = "$clientAcronym/$supervisorAcronym/$romanMonth/$year";
        $typePrefix = "RETURN";
        return IDGenerator::generateID(new static,'kode_pengembalian',5,"$typePrefix/$prefix");
    }
}
