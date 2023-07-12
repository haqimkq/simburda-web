<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kreait\Firebase\Factory;
use Illuminate\Http\Request;

class ProvincesFirebase extends Model
{
    use HasFactory;

    public static $provinceFirebase = 'provinsi/';
    public static function getProvince(){
        self::getDatabase()->getReference(self::$provinceFirebase)->getValue();
    }
    public function getCityByProvince(Request $request){
        self::getDatabase()->getReference(self::$provinceFirebase.$request->provinsi)->getValue();
    }
    public static function getDatabase(){
        $firebase = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(env("FIREBASE_DATABASE_URL"));
        return $firebase->createDatabase();
    }
}
