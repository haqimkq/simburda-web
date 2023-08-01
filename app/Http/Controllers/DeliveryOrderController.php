<?php

namespace App\Http\Controllers;

use App\Helpers\Date;
use App\Models\AksesBarang;
use App\Models\DeliveryOrder;
use App\Models\Gudang;
use App\Models\Kendaraan;
use App\Models\Perusahaan;
use App\Models\PreOrder;
use App\Models\TtdVerification;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
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
            $deliveryOrders = DeliveryOrder::where('purchasing_id',$authUser->id)->filter(request(['search','orderBy','filter', 'datestart','dateend']))->paginate(12)->withQueryString();
        }else if ($authUser->role=='ADMIN_GUDANG'){
            $deliveryOrders = DeliveryOrder::where('admin_gudang_id',$authUser->id)->orWhere('admin_gudang_id', null)->filter(request(['search','orderBy','filter','datestart','dateend']))->paginate(12)->withQueryString();
        }else{
            $deliveryOrders = DeliveryOrder::filter(request(['search','orderBy','filter','datestart','dateend']))->paginate(12)->withQueryString();
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
    public function createStepOne(Request $request)
    {
        $deliveryOrder = $request->session()->get('deliveryOrder');
        return view('deliveryorder.create',[
            "gudangs" => Gudang::get(),
            "deliveryOrder" => $deliveryOrder,
            "purchasings" => (Auth::user()->role == 'ADMIN') ? User::where('role','PURCHASING')->get() : Auth::user(),
            "perusahaans" => Perusahaan::get(),
            "logistics" => User::where('role','LOGISTIC')->get(),
            "kendaraans" => Kendaraan::where('logistic_id',null)->get(),
        ]);
    }
    public function createStepTwo(Request $request)
    {
        $deliveryOrder = $request->session()->get('deliveryOrder');
        
        return view('deliveryorder.preorder.create',[
            "deliveryOrder" => $deliveryOrder,
        
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCreateStepOne(Request $request)
    {
        // dd($request);
        $validate = $request->validate([
            'gudang_id' => 'required|exists:gudangs,id',
            'perusahaan_id' => 'required|exists:perusahaans,id',
            'logistic_id' => 'required|exists:users,id',
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'purchasing_id' => 'required|exists:users,id',
            'perihal' => 'required',
            'untuk_perhatian' => 'required',
            'tgl_pengambilan' => 'required',
        ]);
        $validate['tgl_pengambilan'] = Carbon::parse($validate['tgl_pengambilan']);
        $validate['kode_do'] = DeliveryOrder::generateKodeDO(
            Perusahaan::find($validate['perusahaan_id'])->nama,
            Date::dateToMillisecond($validate['tgl_pengambilan']));
        
        $deliveryOrder = new DeliveryOrder();
        $deliveryOrder->fill($validate);
        $request->session()->put('deliveryOrder', $deliveryOrder);
        return redirect()->route('delivery-order.createStepTwo');
    }
    public function storeCreateStepTwo(Request $request)
    {
        $validate = $request->validate([
            'preorder.*.nama_material' => 'required',
            'preorder.*.satuan' => 'required',
            'preorder.*.ukuran' => 'required',
            'preorder.*.jumlah' => 'required',
            'preorder.*.keterangan' => 'sometimes|nullable',
        ]);
        $do = $request->session()->get('deliveryOrder');
        $ttd = TtdVerification::create([
            'user_id' => $do->purchasing_id,
            'tipe' => "DELIVERY_ORDER",
            'sebagai' => "PEMBUAT"
        ]);
        $do->kode_do = DeliveryOrder::generateKodeDO($do->perusahaan->nama, $do->tgl_pengambilan);
        Kendaraan::where('id', $do->kendaraan_id)->update(['logistic_id'=>$do->logistic_id]);
        $do->ttd = $ttd->id;
        $do->save();
        foreach($validate['preorder'] as $key => $data){
            $validate['preorder'][$key]['delivery_order_id'] = $do->id;
            $validate['preorder'][$key]['kode_po'] = PreOrder::generateKodePO($do->perusahaan->nama,$do->tgl_pengambilan);
            PreOrder::create($validate['preorder'][$key]);
        }
        // PreOrder::insert($validate['preorder']);
        $request->session()->forget('deliveryOrder');
        return redirect()->route('delivery-order');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deliveryOrder = DeliveryOrder::where('id', $id)->first();
        // if (!Gate::allows('cetak-download-do', $deliveryOrder)) {
        //     abort(403);
        // }
        $ttdPath = ($deliveryOrder->ttd) ? TtdVerification::getQrCodeFile($deliveryOrder->ttd) : NULL;
        return view('deliveryorder.cetak',[
            "authUser" => Auth::user(),
            "deliveryOrder" => $deliveryOrder,
            "ttdPath" => $ttdPath
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

    public function downloadPDF($id)
    {
        
        $deliveryOrder = DeliveryOrder::where('id', $id)->first();
        if (!Gate::allows('cetak-download-do', $deliveryOrder)) {
            abort(403);
        }
        $ttdPath = ($deliveryOrder->ttd) ? TtdVerification::getQrCodeFile($deliveryOrder->ttd) : NULL;
        $pdf = FacadePdf::loadView('deliveryorder.downloadPDF', [
            "deliveryOrder" => $deliveryOrder,
            "ttdPath" =>$ttdPath
        ])->setOption(['defaultFont' => 'Poppins']);
        return $pdf->download('Memo-'.$deliveryOrder->kode_do.'.pdf');
    }

}
