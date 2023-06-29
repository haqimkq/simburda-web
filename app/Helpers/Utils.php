<?php
namespace App\Helpers;

class Utils{
  public static function getStorageUrl($link){
    $url = (env('APP_ENV') == 'local') ? env('NGROK_URL') : env('APP_URL');
    return "$url/storage/$link";
  }
  public static function underscoreToNormal($string){
    return ucwords(strtolower(str_replace("_"," ",$string)));
  }
}
?>
