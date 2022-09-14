<?php

namespace App\Http\Controllers;

use App\Models\AksesBarang;
use App\Models\Proyek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        return view('proyek.create');
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
        if($userAuth->role == 'admin') {
            $validator = Validator::make($request->all(), [
                'nama_proyek' => 'required|string',
                'proyek_manager_id' => 'required|string',
                'alamat' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ],[
                'nama_proyek.required' => 'Nama Proyek wajib diisi',
                'proyek_manager_id.required' => 'Proyek Manager wajib diisi',
                'alamat.required' => 'Alamat wajib diisi',
                'latitude.required' => 'Latitude wajib diisi',
                'longitude.required' => 'Longitude wajib diisi',
                ]
            );
        }else {
            $validator = Validator::make($request->all(), [
                'nama_proyek' => 'required|string',
                'alamat' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ],[
                    'nama_proyek.required' => 'Nama Proyek wajib diisi',
                    'alamat.required' => 'Alamat wajib diisi',
                    'latitude.required' => 'Latitude wajib diisi',
                    'longitude.required' => 'Longitude wajib diisi',
                    ]
                );
            }
            
            if ($validator->fails()) {
                return redirect('proyek/tambah')
                ->withErrors($validator)
                ->with('createProyekFailed', 'Gagal Menambah Proyek!')
                ->withInput();
            }
            $proyekManagerId = $userAuth->role == 'project manager' ? $userAuth->id : $request->proyek_manager_id;
            
            $proyek = Proyek::create([
                'nama_proyek' => $request->nama_proyek,
                'alamat' => $request->alamat,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'proyek_manager_id' => $proyekManagerId,
            ]
        );
        return redirect('proyek')->with('createProyekSuccess', 'Berhasil Menambah Proyek'. $proyek->nama_proyek);
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
        $proyek = Proyek::find($id);
        Proyek::destroy($id);
        return redirect('proyek')->with('deleteProyekSuccess', 'Berhasil Menghapus Proyek '.$proyek->nama_proyek);
    }

    public function selectProyekManager(Request $request)
    {
    	$proyekManager = [];
        $search = $request->q;
        $proyekManager = User::select("id", "nama")
                ->where('nama', 'LIKE', "%$search%")
                ->where('role', 'project manager')
                ->get();
        return response()->json($proyekManager);
    }

}
