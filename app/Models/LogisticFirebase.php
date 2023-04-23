<?php

namespace App\Models;

use App\Http\Requests\LogisticFirebaseRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class LogisticFirebase extends Model
{
    public static $firebaseDatabase;
    public static $logisticFirebase = 'logistic/';
    public function __construct(Database $firebaseDatabase)
    {
        $this->firebaseDatabase = $firebaseDatabase;
    }

    public static function getData(Request $request){
        self::$firebaseDatabase->getReference(self::$logisticFirebase.$request->userId)->getValue();
    }
    public static function setData(LogisticFirebaseRequest $request){
        $setData = [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];
        self::$firebaseDatabase->getReference(self::$logisticFirebase.$request->userId)->set($setData);
    }
    public static function updateData(LogisticFirebaseRequest $request){
        $updateData = [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];
        self::$firebaseDatabase->getReference(self::$logisticFirebase.$request->userId)->update($updateData);
    }
}