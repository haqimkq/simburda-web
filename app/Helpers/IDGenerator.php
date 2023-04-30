<?php
namespace App\Helpers;

class IDGenerator
{
    public static function generateID($model, $trow, $length = 4, $prefix){
        $data = $model::orderBy('id','desc')->first();
        if(!$data){
            $ori_length = $length;
            $last_number = '';
        }else{
          # Get Last Code without prefix
            $code = substr($data->$trow, strlen($prefix)+1);
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
        return $prefix.'/'.$zeros.$last_number;
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
        return $acronym;
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
}
?>
