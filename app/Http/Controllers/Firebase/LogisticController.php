<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use App\Models\AksesBarang;
use App\Models\Logistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Contract\Database;

class LogisticController extends Controller
{
    protected $logisticModel;
    public function __construct(Database $database)
    {
        $this->logisticModel = new Logistic();
    }
    public function index(){
        $refTableName = 'logistic';
        $data = $this->database->getReference($refTableName)->getValue();
        $user = Auth::user();
        $countUndefinedAkses = AksesBarang::countUndefinedAkses();
        return view('firebase.index',[
            'data' => $data,
            'authUser' => $user,
            'countUndefinedAkses' => $countUndefinedAkses,
        ]);
    }
    public function create(){
        $refTableName = 'logistic/04e534ae-dc37-3469-aa03-e0743a5d88a7';
        $setData = [
            'latitude' => -6.8291021,
            'longitude' => 108.2384758,
        ];
        $this->database->getReference($refTableName)->update($setData);
    }
    public function store(Request $request){
        $refTableName = 'logistic';
        $postData = [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];
        $postRef = $this->database->getReference($refTableName)->push($postData);
    }

    public function setLogisticData(Request $request){
        $request->validate([
            'userId' => ['required|uuid'],
            'latitude' => ['required|numeric'],
            'longitude' => ['required|numeric']
        ]);
        $setData = [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];
        $this->database->getReference($this->logisticTable.$request->userId)->set($setData);
    }

    public function updateLogisticData(Request $request){
        Logistic->updateLogisticData()
    }
}
