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
  public static function getMonthNumber(){
    $getDate = explode("-", self::getDateString());
    return $getDate[1];
  }
  public static function getYearNumber(){
    $getDate = explode("-", self::getDateString());
    return $getDate[0];
  }
  public static function getDayNumber(){
    $getDate = explode("-", self::getDateString());
    return $getDate[3];
  }
  public static function getDateString(){
    $explodeDate = explode(" ", self::getDateTimeString());
    return $explodeDate[0];
  }
  public static function getTimeString(){
    $explodeDate = explode(" ", self::getDateTimeString());
    return $explodeDate[1];
  }
  public static function getDateTimeString(){
    $date = Carbon::now();
    $dateTimeToString = $date->toDateTimeString();
    return $dateTimeToString;
  }
}
?>
