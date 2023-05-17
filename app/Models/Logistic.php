<?php

namespace App\Models;

use App\Helpers\IDGenerator;
use App\Http\Requests\LogisticFirebaseRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class Logistic extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $primaryKey = null;
    public $incrementing = false;
    public function user(){
        return $this->belongsTo(User::class, 'logistic_id','id');
    }

    public static function generateLogisticCode(){
        return IDGenerator::generateID(Logistic::class, 'kode_logistic', 5, 'LOG');
    }

    public static function createDBWithRDB(Request $request){
        $requestFirebase = LogisticFirebaseRequest::createFrom($request);
        self::create([
            'user_id' => $requestFirebase->user_id,
            'latitude' => $requestFirebase->latitude,
            'longitude' => $requestFirebase->latitude,
            'kode_logistic' => self::generateLogisticCode(),
        ]);
        LogisticFirebase::setData($requestFirebase);
    }
    public static function updateDBWithRDB(Request $request){
        self::where('user_id', $request->user_id)->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);
        LogisticFirebase::updateData($request);
    }
    public static function deleteRDB(){
        LogisticFirebase::deleteAllData();
    }
}
