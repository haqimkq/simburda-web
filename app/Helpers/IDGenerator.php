<?php
namespace App\Helpers;

use App\Models\DeliveryOrder;
use App\Models\Peminjaman;
use App\Models\Penggunaan;
use App\Models\Pengembalian;
use App\Models\PengembalianPenggunaan;
use App\Models\PreOrder;
use App\Models\SuratJalan;
use App\Models\TtdVerification;
class IDGenerator
{
    // public static function generateID($model, $trow, $length, $prefix){
    //     $data = $model::orderBy('created_at','desc')->first();
    //     if(!$data){
    //         $ori_length = $length;
    //         $last_number = '';
    //     }else{
    //       # Get Last Code without prefix
    //         $code = substr($data->$trow, strlen($prefix)+1);
    //       # Get Integer Last Number of code
    //         $actual_last_number = (int)$code*1;
    //       # Increase Last Number of code
    //         $increment_last_number = ($actual_last_number)+1;
    //         $last_number_length = strlen($increment_last_number);
    //         $ori_length = $length - $last_number_length;
    //         $last_number = $increment_last_number;
    //     }
    //     $zeros = "";
    //     for($i=0;$i<$ori_length;$i++){
    //         $zeros.="0";
    //     }
    //     return "$prefix/$zeros$last_number";
    // }
    public static function generateID($model, $trow, $length, $suffix){
        $data = $model::orderBy('created_at','desc')->first();
        if(!$data){
            $ori_length = $length;
            $last_number = '';
        }else{
            # Get first code without suffix
            $code  = explode('/',$data->$trow)[0];
            # Get Integer Last Number of code
            $actual_last_number = (int)$code*1;
            # Increase Last Number of code
            $increment_last_number = ($actual_last_number)+1;
            $last_number_length = strlen($increment_last_number);
            $ori_length = $length - $last_number_length;
            $last_number = $increment_last_number;
        }
        $zeros = "";
        for($i=0;$i<$ori_length;$i++){
            $zeros.="0";
        }
        return "$zeros$last_number/$suffix";
    }
    public static function getAcronym($words){
        // Delimit by multiple spaces, hyphen, underscore, comma, and dot
        $words = preg_split("/[\s.,_-]+/", $words);
        $acronym = "";
        foreach ($words as $w) {
            if(strcasecmp($w, 'PT') == 0 || 
                strcasecmp($w, 'CV') == 0) continue;
            $acronym .= substr($w, 0, 1);
        }
        return strtoupper($acronym);
    }
    public static function numberToRoman($number) {
        $map = array('X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
    public static function reorderAll(){
        self::reorder_code(SuratJalan::class, 'kode_surat');
        self::reorder_code(Peminjaman::class, 'kode_peminjaman');
        self::reorder_code(Penggunaan::class, 'kode_penggunaan');
        self::reorder_code(Pengembalian::class, 'kode_pengembalian');
        self::reorder_code(PengembalianPenggunaan::class, 'kode_pengembalian');
        self::reorder_code(DeliveryOrder::class, 'kode_do');
        self::reorder_code(PreOrder::class, 'kode_po');
    }
    public static function reorder_code($model, $column_name){
        $datas = $model::orderBy('created_at', 'asc')->get();
        foreach ($datas as $key=>$data){
            # Get first code without suffix
            $code  = explode('/',$data->$column_name)[0];
            $length = 5;
            $suffix = substr($data->$column_name,strlen($code));
            # Increase Last Number of code
            $increment_last_number = ($key)+1;
            $last_number_length = strlen($increment_last_number);
            if($key==0){
                $ori_length = $length-1;
                $last_number = '1';
            }else{
                $ori_length = $length - $last_number_length;
                $last_number = $increment_last_number;
            }
            $zeros = "";
            for($i=0;$i<$ori_length;$i++){
                $zeros.="0";
            }
            $newCode = "$zeros$last_number$suffix";
            $model::where('id', $data->id)->update([$column_name => $newCode]);
            if($column_name == 'kode_surat'){
                $sj = SuratJalan::find($data->id);
                TtdVerification::updateTtdSjVerificationFromSuratJalan($sj);
            }
        }
    }
}
?>
