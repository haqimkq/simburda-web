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
  public static function parseMilliseconds($milli, $format='dddd, D MMM YYYY H:mm'){
    return Carbon::createFromTimestampMs($milli)
    ->setTimezone(session('timezone'))
    ->locale('id')->IsoFormat($format);
  }
  public static function dateToMillisecond($date){
    return Carbon::parse($date)->getTimestampMs();
  }
  public static function getMonthNumber($datee=null){
    $getDate = explode("-", self::getDateString($datee));
    return $getDate[1];
  }
  public static function getYearNumber($datee=null){
    $getDate = explode("-", self::getDateString($datee));
    return $getDate[0];
  }
  public static function getDayNumber($datee=null){
    $getDate = explode("-", self::getDateString($datee));
    return $getDate[3];
  }
  public static function getDateString($datee=null){
    $explodeDate = explode(" ", self::getDateTimeString($datee));
    return $explodeDate[0];
  }
  public static function getTimeString($datee=null){
    $explodeDate = explode(" ", self::getDateTimeString($datee));
    return $explodeDate[1];
  }
  public static function getDateTimeString($datee=null){
    $date = Carbon::parse($datee) ?? Carbon::now();
    $dateTimeToString = $date->toDateTimeString();
    return $dateTimeToString;
  }
}
?>
