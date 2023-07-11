<?php

namespace App\Http\Controllers;

use App\Models\AksesBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AksesBarangController extends Controller
{
    /**
     * Display a view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        //
        $authUser = Auth::user();

        $countUndefinedAkses = AksesBarang::countUndefinedAkses();

        if($authUser->role == 'SET_MANAGER' ){
            $aksesBarangs = AksesBarang::
                with(['peminjamanDetail.peminjaman.menangani.proyek' => function ($q){
                        $q->orderBy('created_at','DESC');
                }, 'peminjamanDetail.peminjaman' => function ($q){
                        $q->orderBy('created_at');
                }, 'peminjamanDetail'])
                ->whereRelation('peminjamanDetail.peminjaman.menangani.proyek.setManager', 'id', $authUser->id)  
                ->filter(request(['search', 'filter', 'orderBy']))
                ->paginate(40)
                ->withQueryString();
        } else if($authUser->role == 'SUPERVISOR') {
            $aksesBarangs = AksesBarang::
                with(['peminjamanDetail.peminjaman.menangani.proyek' => function ($q){
                        $q->orderBy('created_at','DESC');
                }, 'peminjamanDetail.peminjaman' => function ($q){
                        $q->orderBy('created_at');
                }, 'peminjamanDetail'])
                ->whereRelation('peminjamanDetail.peminjaman.menangani.supervisor', 'id', $authUser->id) 
                ->filter(request(['search', 'filter', 'orderBy']))
                ->paginate(40)
                ->withQueryString();
        }else{
            $aksesBarangs = AksesBarang::
                with(['peminjamanDetail.peminjaman.menangani.proyek' => function ($q){
                        $q->orderBy('created_at','DESC');
                }, 'peminjamanDetail.peminjaman' => function ($q){
                        $q->orderBy('created_at');
                }, 'peminjamanDetail'])
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
        if($authUserRole == 'ADMIN' && $request->akses == 'setujui'){
            foreach($request->id as $idAksesBarang){
                $aksesBarang=AksesBarang::find($idAksesBarang);
                $aksesBarang->disetujui_admin = true;
                $aksesBarang->disetujui_pm = true;
                $aksesBarang->save();
            }
        }
        if($authUserRole == 'SET_MANAGER' && $request->akses == 'setujui'){
            foreach($request->id as $idAksesBarang){
                $aksesBarang=AksesBarang::find($idAksesBarang);
                $aksesBarang->disetujui_pm = true;
                $aksesBarang->save();
            }
        }
        if($authUserRole == 'ADMIN_GUDANG' && $request->akses == 'setujui'){
            foreach($request->id as $idAksesBarang){
                $aksesBarang=AksesBarang::find($idAksesBarang);
                $aksesBarang->disetujui_admin = true;
                $aksesBarang->save();
            }
        }
        if($authUserRole == 'ADMIN' && $request->akses == 'tolak'){
            foreach($request->id as $idAksesBarang){
                $aksesBarang=AksesBarang::find($idAksesBarang);
                $aksesBarang->disetujui_admin = false;
                $aksesBarang->disetujui_pm = false;
                $aksesBarang->save();
            }
        }
        if($authUserRole == 'SET_MANAGER' && $request->akses == 'tolak'){
            foreach($request->id as $idAksesBarang){
                $aksesBarang=AksesBarang::find($idAksesBarang);
                $aksesBarang->disetujui_pm = false;
                $aksesBarang->save();
            }   
        }
        if($authUserRole == 'ADMIN_GUDANG' && $request->akses == 'tolak'){
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
     * Display a view.
     *
     * @return \Illuminate\View\View
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
