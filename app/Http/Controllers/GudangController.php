<?php

namespace App\Http\Controllers;

use App\Models\AksesBarang;
use App\Models\Gudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('gudang.create',[
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
        $authUserRole = Auth::user()->role;
        return redirect()->back()->with('successUpdateAkses', '');
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
