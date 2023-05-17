<?php

namespace App\Models;

use App\Http\Requests\LogisticFirebaseRequest;
use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Kreait\Firebase\Factory;

class LogisticFirebase
{
    public static $logisticFirebase = 'logistic/';
    public function getData(Request $request){
        self::getDatabase()->getReference(self::$logisticFirebase.$request->user_id)->getValue();
    }
    public static function setData(Request $request){
        $setData = [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];
        self::getDatabase()->getReference(self::$logisticFirebase.$request->user_id)->set($setData);
    }
    public static function updateData(Request $request){
        $updateData = [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];
        self::getDatabase()->getReference(self::$logisticFirebase.$request->user_id)->update($updateData);
    }
    public static function deleteAllData(){
        self::getDatabase()->getReference(self::$logisticFirebase)->remove();
    }
    public static function getDatabase(){
        $firebase = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(env("FIREBASE_DATABASE_URL"));
        return $firebase->createDatabase();
    }
}