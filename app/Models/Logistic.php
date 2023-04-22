<?php

namespace App\Models;

use App\Helpers\IDGenerator;
use App\Http\Requests\LogisticFirebaseRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Logistic extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;
    protected $primaryKey = null;
    public $incrementing = false;
    public function user(){
        return $this->belongsTo(User::class, 'logistic_id','id');
    }

    public static function generateLogisticCode(){
        return IDGenerator::generateID(Logistic::class, 'kode_logistic', 4, 'LOG');
    }

    public static function createDBWithRDB(Request $request){
        $requestFirebase = LogisticFirebaseRequest::createFrom($request);
        self::create([
            'userId' => $requestFirebase->userId,
            'latitude' => $requestFirebase->latitude,
            'longitude' => $requestFirebase->latitude,
            'kode_logistic' => self::generateLogisticCode(),
        ]);
        LogisticFirebase::setData($requestFirebase);
    }
    public static function updateDBWithRDB(Request $request){
        self::where('userId', $request->userId)->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);
        $requestFirebase = LogisticFirebaseRequest::createFrom($request);
        LogisticFirebase::updateData($requestFirebase);
    }

}
