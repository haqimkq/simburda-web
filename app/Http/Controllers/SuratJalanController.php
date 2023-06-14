<?php

namespace App\Http\Controllers;

use App\Models\AksesBarang;
use App\Models\SuratJalan;
use App\Models\TtdVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class SuratJalanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countUndefinedAkses = AksesBarang::countUndefinedAkses();
        $suratjalan = SuratJalan::filter(request(['search','orderBy','filter']))->paginate(12)->withQueryString();
        $authUser = Auth::user();
        return view('suratjalan.index',[
            'countUndefinedAkses' => $countUndefinedAkses,
            'suratjalans' => $suratjalan,
            'authUser' => $authUser,
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
        //
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

    public function cetak($id)
    {
        $suratJalan = SuratJalan::where('id', $id)->first();
        // if (!Gate::allows('cetak-download-sj', $suratJalan)) {
        //     abort(403);
        // }
        $ttdPath = ($suratJalan->ttd_admin) ? TtdVerification::getQrCodeFile($suratJalan->ttd_admin) : NULL;
        $ttdPathSupervisor = ($suratJalan->ttd_supervisor) ? TtdVerification::getQrCodeFile($suratJalan->ttd_supervisor) : NULL;
        $ttdPathSupervisor2 = ($suratJalan->sjPengembalian!=null && $suratJalan->ttd_supervisor_peminjam!=null) ? TtdVerification::getQrCodeFile($suratJalan->ttd_supervisor) : NULL;
        $ttdPathLogistic = ($suratJalan->ttd_driver) ? TtdVerification::getQrCodeFile($suratJalan->ttd_driver) : NULL;
        return view('suratjalan.cetak',[
            "suratJalan" => $suratJalan,
            "ttdPath" => $ttdPath,
            "ttdPathSupervisor" => $ttdPathSupervisor,
            "ttdPathSupervisor2" => $ttdPathSupervisor2,
            "ttdPathLogistic" => $ttdPathLogistic
        ]);
    }
    public function downloadPDF($id)
    {
        
        $suratJalan = SuratJalan::where('id', $id)->first();
        // if (!Gate::allows('cetak-download-sj', $suratJalan)) {
        //     abort(403);
        // }
        $ttdPath = ($suratJalan->ttd_admin) ? TtdVerification::getQrCodeFile($suratJalan->ttd_admin) : NULL;
        $ttdPathSupervisor = ($suratJalan->ttd_supervisor) ? TtdVerification::getQrCodeFile($suratJalan->ttd_supervisor) : NULL;
        $ttdPathSupervisor2 = ($suratJalan->sjPengembalian!=null && $suratJalan->ttd_supervisor_peminjam!=null) ? TtdVerification::getQrCodeFile($suratJalan->ttd_supervisor) : NULL;
        $ttdPathLogistic = ($suratJalan->ttd_driver) ? TtdVerification::getQrCodeFile($suratJalan->ttd_driver) : NULL;
        $pdf = FacadePdf::loadView('suratjalan.downloadPDF', [
            "suratJalan" => $suratJalan,
            "ttdPath" => $ttdPath,
            "ttdPathSupervisor" => $ttdPathSupervisor,
            "ttdPathSupervisor2" => $ttdPathSupervisor2,
            "ttdPathLogistic" => $ttdPathLogistic
        ])->setOption(['defaultFont' => 'Poppins']);
        return $pdf->download('Memo-'.$suratJalan->kode_surat.'.pdf');
    }
}
