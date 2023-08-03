<?php
namespace App\Helpers;

class Location{
  public static function getAddress($latitude, $longitude){
    $url = "https://maps.google.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=AIzaSyCJyDp4TLGUigRfo4YN46dXcWOPRqLD0gQ&sensor=true";

    // send http request
    $geocode = file_get_contents($url);
    $json = json_decode($geocode);
    $address = $json->results[0]->formatted_address;
    return $address;
  }
}

?>
