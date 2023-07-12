<?php

namespace App\Http\Controllers;

use App\Models\AksesBarang;
use App\Models\LogisticFirebase;
use App\Models\ProvincesFirebase;
use App\Models\Proyek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\validate;

class ProyekController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countUndefinedAkses = AksesBarang::countUndefinedAkses();
        $proyek = Proyek::filter(request(['search','orderBy','filter']))->paginate(12)->withQueryString();
        $authUser = Auth::user();
        return view('proyek.index',[
            'countUndefinedAkses' => $countUndefinedAkses,
            'proyeks' => $proyek,
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
        $province = collect(ProvincesFirebase::getProvince()) ;
        return view('proyek.create',[
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
        $userAuth = Auth::user();
        if($userAuth->role == 'ADMIN') {
            $validate = $request->validate([
                'nama_proyek' => 'required',
                'set_manager_id' => 'required',
                'provinsi' => 'required',
                'kota' => 'required',
                'gambar' => 'nullable',
                'alamat' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);
            
        }else {
            $validate = $request->validate([
                'nama_proyek' => 'required|string',
                'alamat' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'provinsi' => 'required',
                'kota' => 'required',
                'gambar' => 'nullable',
            ],[
                    'nama_proyek.required' => 'Nama Proyek wajib diisi',
                    'alamat.required' => 'Alamat wajib diisi',
                    'latitude.required' => 'Latitude wajib diisi',
                    'longitude.required' => 'Longitude wajib diisi',
                    ]
                );
            $validate['set_manager_id'] = $userAuth->id;
        }
        if($request->file('gambar')){
            $validate['gambar'] = $request->file('gambar')->store('assets/proyek', 'public');
        }
        $proyek = Proyek::create($validate);
        return redirect('proyek')->with('createProyekSuccess', 'Berhasil Menambah Proyek ('. $proyek->nama_proyek.')');
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
    public function edit(Proyek $proyek)
    {
        $province = collect(ProvincesFirebase::getProvince());
        return view('proyek.edit',[
            'proyek' => $proyek,
            'provinces' => $province->keys()
        ]);
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
        $userAuth = Auth::user();
        if($userAuth->role == 'ADMIN') {
            $validate = $request->validate([
                'nama_proyek' => 'required',
                'set_manager_id' => 'nullable',
                'provinsi' => 'required',
                'kota' => 'required',
                'gambar' => 'nullable',
                'alamat' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);
            
        }else {
            $validate = $request->validate([
                'nama_proyek' => 'required|string',
                'alamat' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'provinsi' => 'required',
                'kota' => 'required',
                'gambar' => 'nullable',
            ],[
                    'nama_proyek.required' => 'Nama Proyek wajib diisi',
                    'alamat.required' => 'Alamat wajib diisi',
                    'latitude.required' => 'Latitude wajib diisi',
                    'longitude.required' => 'Longitude wajib diisi',
                    ]
                );
            $validate['set_manager_id'] = $userAuth->id;
        }
        if($request->file('gambar')){
            if($request->oldImage){
                Storage::delete($request->oldImage);
            }
            $validate['gambar'] = $request->file('gambar')->store('assets/proyek', 'public');
        }
        Proyek::where('id',$id)->update($validate);
        return redirect('proyek')->with('createProyekSuccess', 'Berhasil Merubah Proyek ('. $request->nama_proyek.')');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $proyek = Proyek::find($id);
        Proyek::destroy($id);
        return redirect('proyek')->with('deleteProyekSuccess', 'Berhasil Menghapus Proyek '.$proyek->nama_proyek);
    }

    public function selectSetManager(Request $request)
    {
    	$setManager = [];
        $search = $request->q;
        $setManager = User::select("id", "nama")
                ->where('nama', 'LIKE', "%$search%")
                ->where('role', 'SET_MANAGER')
                ->get();
        return response()->json($setManager);
    }

}
