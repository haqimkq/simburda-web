<?php

namespace App\Http\Controllers;

use App\Models\TtdDoVerification;
use App\Models\TtdSjVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManagerStatic as ImageManager;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SignatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userAuth=Auth::user();

        return view('pengguna.signature',[
            "userAuth" => $userAuth,
            "randomId" => fake()->uuid()
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userAuth=Auth::user();
        $encoded_image = explode(",", $request->signature)[1];
        $decoded_image = base64_decode($encoded_image);
        $fileName = "assets/ttd/".$userAuth->id.".png";
        User::where('id', $userAuth->id)->update(['ttd'=>$fileName]);
        Storage::disk('public')->put($fileName, $decoded_image);
        return redirect()->back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    public function verified($id)
    {
        
        $file = public_path("storage/assets/ttd/$id");
        return response()->file($file);
    }
    public function viewTTDSuratJalan($id)
    {
        $sj_verification = TtdSjVerification::find($id);
        if ($sj_verification == null) {
            abort(404);
        }
        $filePath = TtdSjVerification::getFile($id);
        return response()->file($filePath);
    }
    public function viewTTDDeliveryOrder($id)
    {
        $do_verification = TtdDoVerification::find($id);
        if ($do_verification == null) {
            abort(404);
        }
        $filePath = TtdDoVerification::getFile($id);
        return response()->file($filePath);
    }

    public function verifiedTTDSuratJalan($id)
    {
        $do_verification = TtdDoVerification::find($id);
        if ($do_verification == null) {
            abort(404);
        }
        $data = explode("|", $do_verification->keterangan); 
        return view('signature.verif',[
            "sjVerif" => $do_verification,
            "nama" => $data[0],
            "role" => $data[1],
            "tipe" => $data[2],
            "kode_surat" => $data[3],
            "sebagai" => $data[4],
            "asal" => $data[5],
            "tujuan" => $data[6],
        ]);
    }
    public function verifiedTTDDeliveryOrder($id)
    {
        $do_verification = TtdDoVerification::find($id);
        if ($do_verification == null) {
            abort(404);
        }
        $data = explode("|", $do_verification->keterangan); 
        return view('signature.verifDo',[
            "doVerif" => $do_verification,
            "nama" => $data[0],
            "role" => $data[1],
            "perihal" => $data[2],
            "kode" => $data[3],
            "perusahaan" => $data[4],
            "untuk_perhatian" => $data[5],
            "gudang" => $data[6],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
