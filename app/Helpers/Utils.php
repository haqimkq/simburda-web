<?php
namespace App\Helpers;

class Utils{
  public static function getStorageUrl($link){
    $url = (env('APP_ENV') == 'local') ? env('NGROK_URL') : env('APP_URL');
    return "$url/storage/$link";
  }
}
?>
