<?php

namespace App\Http\Controllers;

use App\Models\TtdVerification;
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
        $do_verification = TtdVerification::find($id);
        if ($do_verification == null) {
            abort(404);
        }
        $filePath = TtdVerification::getFile($id);
        return response()->file($filePath);
    }

    public function verifiedTTDSuratJalan($id)
    {
        $do_verification = TtdVerification::find($id);
        if ($do_verification == null) {
            abort(404);
        }
        $data = TtdVerification::getKeteranganSuratJalan($id);

        return view('signature.verif',[
            "sjVerif" => $do_verification,
            "nama" => $data['nama'],
            "role" => $data['role'],
            "tipe" => $data['tipe'],
            "kode_surat" => $data['kode_surat'],
            "sebagai" => $data['sebagai'],
            "asal" => $data['asal'],
            "tujuan" => $data['tujuan'],
        ]);
    }
    public function verifiedTTDDeliveryOrder($id)
    {
        $do_verification = TtdVerification::find($id);
        if ($do_verification == null) {
            abort(404);
        }
        $data = TtdVerification::getKeteranganDo($id);
        return view('signature.verifDo',[
            "doVerif" => $do_verification,
            "nama" => $data['nama'],
            "role" => $data['role'],
            "perihal" => $data['perihal'],
            "kode" => $data['kode'],
            "perusahaan" => $data['perusahaan'],
            "untuk_perhatian" => $data['untuk_perhatian'],
            "gudang" => $data['gudang'],
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
