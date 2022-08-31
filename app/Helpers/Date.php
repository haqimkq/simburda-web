<?php
namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Date{
  public static function dateFormatter(
    $date, $format='dddd, D MMM YYYY H:mm'
  ){
    return Carbon::createFromFormat('Y-m-d H:i:s', $date)
        ->setTimezone(session('timezone'))
        ->locale('id')->isoFormat($format);
  }
  public static function dateToMillisecond($date){
    return Carbon::parse($date)->getTimestampMs();
  }
}
?>
