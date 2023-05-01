<?php

namespace App\Models;

use App\Helpers\Date;
use App\Helpers\IDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreOrder extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function deliveryOrder(){
        return $this->belongsTo(DeliveryOrder::class);
    }

    public static function generateKodePO($nama_perusahaan){
        $perusahaanAlias = IDGenerator::getAcronym($nama_perusahaan);
        $romanMonth = IDGenerator::numberToRoman(Date::getMonthNumber());
        $prefix = "PO/BC-" . $perusahaanAlias . "/" . $romanMonth . "/" . Date::getYearNumber();
        return IDGenerator::generateID(PreOrder::class, 'kode_po', 5, $prefix);
    }
}
