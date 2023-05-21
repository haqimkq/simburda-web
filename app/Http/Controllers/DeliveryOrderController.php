<?php

namespace App\Http\Controllers;

use App\Models\AksesBarang;
use App\Models\DeliveryOrder;
use App\Models\Kendaraan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class DeliveryOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authUser = Auth::user();
        $countUndefinedAkses = AksesBarang::countUndefinedAkses();
        if($authUser->role=='PURCHASING'){
            $deliveryOrders = DeliveryOrder::where('purchasing_id',$authUser->id)->filter(request(['search','orderBy','filter']))->paginate(12)->withQueryString();
        }else if ($authUser->role=='ADMIN_GUDANG'){
            $deliveryOrders = DeliveryOrder::where('admin_gudang_id',$authUser->id)->orWhere('admin_gudang_id', null)->filter(request(['search','orderBy','filter']))->paginate(12)->withQueryString();
        }else{
            $deliveryOrders = DeliveryOrder::filter(request(['search','orderBy','filter']))->paginate(12)->withQueryString();
        }
        return view('deliveryorder.index',[
            'deliveryOrders' => $deliveryOrders,
            'authUser' => $authUser,
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
        $authUser = Auth::user();
        $deliveryorder = DeliveryOrder::where("id", $id)->first();
        return view("deliveryorder.detail", [
            "authUser" => $authUser,
            "deliveryorder" => $deliveryorder
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

    public function cetak($id)
    {
        $deliveryOrder = DeliveryOrder::where('id', $id)->first();
        if (!Gate::allows('cetak-download-do', $deliveryOrder)) {
            abort(403);
        }
        $ttdPath = ($deliveryOrder->purchasing->ttd) ? Storage::url($deliveryOrder->purchasing->ttd) : NULL;
        return view('deliveryorder.cetak',[
            "deliveryOrder" => $deliveryOrder,
            "ttdPath" => $ttdPath
        ]);
    }
    public function downloadPDF($id)
    {
        
        $deliveryOrder = DeliveryOrder::where('id', $id)->first();
        if (!Gate::allows('cetak-download-do', $deliveryOrder)) {
            abort(403);
        }
        $ttdPath = ($deliveryOrder->purchasing->ttd) ? Storage::url($deliveryOrder->purchasing->ttd) : NULL;
        $pdf = FacadePdf::loadView('deliveryorder.downloadPDF', [
            "deliveryOrder" => $deliveryOrder,
            "ttdPath" =>$ttdPath
        ])->setOption(['defaultFont' => 'Poppins']);
        return $pdf->download('Memo-'.$deliveryOrder->kode_do.'.pdf');
    }

}
