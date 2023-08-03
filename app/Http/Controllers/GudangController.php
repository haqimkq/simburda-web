<?php

namespace App\Http\Controllers;

use App\Models\AksesBarang;
use App\Models\Gudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProvincesFirebase;
use Illuminate\Support\Facades\Storage;

class GudangController extends Controller
{
    public function index()
    {
        $authUser = Auth::user();
        $gudangs = Gudang::filter(request(['search', 'filter', 'orderBy']))
                ->paginate(10)
                ->withQueryString();
        $provinsis = Gudang::groupBy('provinsi')->get('provinsi')->all();
        $countUndefinedAkses = AksesBarang::countUndefinedAkses();
        return view('gudang.index',[
            'authUser' => $authUser,
            'gudangs' => $gudangs,
            'provinsis' => $provinsis,
            'countUndefinedAkses' => $countUndefinedAkses
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $province = collect(ProvincesFirebase::getProvince());
        return view('gudang.create',[
            'provinces' => $province->keys()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'gambar' => 'nullable',
            'alamat' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if($request->file('gambar')){
            $validate['gambar'] = $request->file('gambar')->store('assets/gudang', 'public');
        }

        $gudang = Gudang::create($validate);

        return redirect('gudang')->with('createGudangSuccess','Berhasil Menambah Gudang ('. $gudang->nama.')');
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
    public function edit(Gudang $gudang)
    {
        $province = collect(ProvincesFirebase::getProvince());
        return view('gudang.edit',[
            'provinces' => $province->keys(),
            'gudang' => $gudang
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gudang $gudang)
    {
        $validate = $request->validate([
            'nama' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'gambar' => 'nullable',
            'alamat' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if($request->file('gambar')){
            if($request->oldImage){
                Storage::delete($request->oldImage);
            }
            $validate['gambar'] = $request->file('gambar')->store('assets/gudang', 'public');
        }

        Gudang::where('id',$gudang->id)->update($validate);

        return redirect('gudang')->with('createGudangSuccess','Berhasil Merubah Gudang ('. $gudang->nama.')');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gudang $gudang)
    {
        if($gudang->gambar){
            Storage::delete($gudang->gambar);
        }
        gudang::destroy($gudang->id);

        return redirect('gudang')->with('deletePerusahaaanSuccess','Berhasil Menghapus Gudang ('. $gudang->nama.')');
    }
}
