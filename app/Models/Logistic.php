<?php

namespace App\Models;

use App\Helpers\Date;
use App\Helpers\IDGenerator;
use App\Http\Requests\LogisticFirebaseRequest;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class Logistic extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public static function generateLogisticCode(){
        return IDGenerator::generateID(Logistic::class, 'kode_logistic', 5, 'LOG');
    }

    public static function createDBWithRDB(Request $request){
        User::createLogistic($request);
        LogisticFirebase::setData($request);
    }
    public static function updateCoordinate(Request $request){
        self::where('user_id', $request->user_id)->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);
        LogisticFirebase::updateData($request);
    }
    public static function deleteRDB(){
        LogisticFirebase::deleteAllData();
    }
    
    public static function getKendaraan(Request $request){
        $request->validate([
            'user_id' => 'required'
        ]);
        return Kendaraan::where('logistic_id', $request->user_id)->first();
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
}
