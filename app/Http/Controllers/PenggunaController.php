<?php

namespace App\Http\Controllers;

use App\Models\AksesBarang;
use App\Models\Logistic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('ADMIN');
        $countUndefinedAkses = AksesBarang::countUndefinedAkses();
        $user = User::filter(request(['search', 'filter', 'orderBy']))->paginate(12)->withQueryString();
        $authUser = Auth::user();
        return view('pengguna.index',[
            'countUndefinedAkses' => $countUndefinedAkses,
            'allUser' => $user,
            'authUser' => $authUser
            
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pengguna.create');
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
            'email' => 'required|email|unique:users,email',
            'foto' => 'nullable|file',
            'no_hp' => 'required|numeric',
            'role' => 'required',
            'password' => 'required'
        ]);

        $validate['password'] = bcrypt($validate['password']);

        User::create($validate);

        return redirect()->route("pengguna");
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
        $user = User::find($id);
        return view('pengguna.edit', [
            "user" => $user
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
        $user = User::find($id);
        $user->role = $request->role;
        $user->nama = $request->nama;
        if($request->foto){
            $extFormat = $request->file('foto')->getClientOriginalExtension();
            $fileName = $user->nama.".".$extFormat;
            $user->foto = $request->file('foto')->storeAS('assets/pengguna',$fileName,'public');
        }
        if($request->role=='logistic'){
            Logistic::firstOrCreate(['logistic_id' => $id,]);
        }
        $user->update();
        return redirect('/pengguna')->with(
            "updatePenggunaSuccess", "Berhasil Memperbarui Pengguna (".$user->nama.")"
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        User::destroy($id);
        return redirect('/pengguna')->with('deletePenggunaSuccess','Berhasil Menghapus Pengguna ('.$user->nama.')');
    }
}
