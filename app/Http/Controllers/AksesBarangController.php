<?php

namespace App\Http\Controllers;

use App\Models\AksesBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AksesBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $authUser = Auth::user();

        $countUndefinedAkses = AksesBarang::countUndefinedAkses();

        if($authUser->role == 'project manager' ){
            $aksesBarangs = AksesBarang::select('*', 'akses_barangs.id as id')->join('meminjams', 'akses_barangs.meminjam_id', '=', 'meminjams.id')
                ->join('barangs', 'meminjams.barang_id', '=', 'barangs.id')
                ->join('users', 'meminjams.supervisor_id', '=', 'users.id')
                ->join('proyeks', 'meminjams.proyek_id', '=', 'proyeks.id')
                ->where('proyeks.proyek_manager_id',$authUser->id)
                ->orderBy('proyeks.created_at','DESC')
                ->orderBy('meminjams.created_at')
                ->filter(request(['search', 'filter', 'orderBy']))
                ->paginate(40)
                ->withQueryString();
        } else if($authUser->role == 'supervisor'){
            $aksesBarangs = AksesBarang::select('*', 'akses_barangs.id as id')->join('meminjams', 'akses_barangs.meminjam_id', '=', 'meminjams.id')
                ->join('barangs', 'meminjams.barang_id', '=', 'barangs.id')
                ->join('users', 'meminjams.supervisor_id', '=', 'users.id')
                ->join('proyeks', 'meminjams.proyek_id', '=', 'proyeks.id')
                ->orderBy('proyeks.created_at','DESC')
                ->where('meminjams.supervisor_id',$authUser->id)
                ->orderBy('meminjams.created_at')
                ->filter(request(['search', 'filter', 'orderBy']))
                ->paginate(40)
                ->withQueryString();
        }else{
            $aksesBarangs = AksesBarang::select('*', 'akses_barangs.id as id')->join('meminjams', 'akses_barangs.meminjam_id', '=', 'meminjams.id')
                ->join('barangs', 'meminjams.barang_id', '=', 'barangs.id')
                ->join('users', 'meminjams.supervisor_id', '=', 'users.id')
                ->join('proyeks', 'meminjams.proyek_id', '=', 'proyeks.id')
                ->orderBy('proyeks.created_at','DESC')
                ->orderBy('meminjams.created_at')
                ->filter(request(['search', 'filter', 'orderBy']))
                ->paginate(40)
                ->withQueryString();
        }
        return view('aksesbarang.index',[
            'authUser' => $authUser,
            'aksesBarangs' => $aksesBarangs,
            'countUndefinedAkses' => $countUndefinedAkses,
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
        $authUserRole = Auth::user()->role;
        if($authUserRole == 'admin' && $request->akses == 'setujui'){
            foreach($request->id as $idAksesBarang){
                $aksesBarang=AksesBarang::find($idAksesBarang);
                $aksesBarang->disetujui_admin = true;
                $aksesBarang->disetujui_pm = true;
                $aksesBarang->save();
            }
        }
        if($authUserRole == 'project manager' && $request->akses == 'setujui'){
            foreach($request->id as $idAksesBarang){
                $aksesBarang=AksesBarang::find($idAksesBarang);
                $aksesBarang->disetujui_pm = true;
                $aksesBarang->save();
            }
        }
        if($authUserRole == 'admin gudang' && $request->akses == 'setujui'){
            foreach($request->id as $idAksesBarang){
                $aksesBarang=AksesBarang::find($idAksesBarang);
                $aksesBarang->disetujui_admin = true;
                $aksesBarang->save();
            }
        }
        if($authUserRole == 'admin' && $request->akses == 'tolak'){
            foreach($request->id as $idAksesBarang){
                $aksesBarang=AksesBarang::find($idAksesBarang);
                $aksesBarang->disetujui_admin = false;
                $aksesBarang->disetujui_pm = false;
                $aksesBarang->save();
            }
        }
        if($authUserRole == 'project manager' && $request->akses == 'tolak'){
            foreach($request->id as $idAksesBarang){
                $aksesBarang=AksesBarang::find($idAksesBarang);
                $aksesBarang->disetujui_pm = false;
                $aksesBarang->save();
            }
        }
        if($authUserRole == 'admin gudang' && $request->akses == 'tolak'){
            foreach($request->id as $idAksesBarang){
                $aksesBarang=AksesBarang::find($idAksesBarang);
                $aksesBarang->disetujui_pm = false;
                $aksesBarang->save();
            }
        }
        $aksesGiven = ($request->akses == 'setujui') ? "Menyetujui" : "Menolak";
        return redirect()->back()->with('successUpdateAkses', 'Berhasil '. $aksesGiven. ' '. count($request->id). ' Permintaan Akses Barang');
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
}
