<?php

namespace App\Http\Controllers\Api;

use App\Enum\DeliveryOrderStatus;
use App\Enum\DeliveryOrderTipe;
use App\Helpers\Date;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\SjPengembalian;
use App\Models\SjPengirimanGp;
use App\Models\SjPengirimanPp;
use App\Models\DeliveryOrder;
use App\Models\TtdVerification;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class DeliveryOrderController extends Controller
{
    public function create(Request $request){
        try {
            $sj=DeliveryOrder::createData($request);
            $request->merge(['delivery_order_id' => $sj->id]);
            // Kendaraan::setLogistic($request);
            return ResponseFormatter::success("delivery_order_id", $sj->id, "Berhasil Menambahkan delivery order");
        } catch (Exception $error) {
            return ResponseFormatter::error("Gagal Menambahkan delivery order: ". $error->getMessage());
        }
    }
    public function update(Request $request){
        try {
            $delivery_order_id = $request->route('delivery_order_id');
            $request->merge(['delivery_order_id' => $delivery_order_id]);
            $sj=DeliveryOrder::updateData($request);
            Kendaraan::setLogistic($request);
            return ResponseFormatter::success("delivery_order_id", $sj->id, "Berhasil Mengupdate delivery order");
        } catch (Exception $error) {
            return ResponseFormatter::error("Gagal Mengupdate delivery order: ". $error);
        }
    }
    public function getAllDeliveryOrderByUser(Request $request){
        try{
            $user = $request->user();
            
            $request->validate([
                'status' => [
                    'required',
                    new Enum(DeliveryOrderStatus::class),
                ]
            ]);
            $status = $request->query('status');
            $size = $request->query('size') ?? 10;
            if($request->query('date_start') || $request->query('date_end')){
                $request->validate([
                    'date_start' => [
                        'required',
                        'date_format:Y-m-d'
                    ],
                    'date_end' => [
                        'required',
                        'date_format:Y-m-d',
                    ]
                ]);
            }
            $date_start = ($request->query('date_start')) ? date($request->query('date_start') . " 00:00:00") : null;
            $date_end = ($request->query('date_end')) ? date($request->query('date_end') . " 23:59:59") : null;
            $search = $request->query('search') ?? null;

            $response = DeliveryOrder::getAllDeliveryOrderByUser(false, $user, $status, $size, $date_start, $date_end, $search);

            $message = ($response['delivery_order']->isEmpty()) ? 'Tidak ada delivery order' : 'Berhasil Mendapatkan delivery order';
            return ResponseFormatter::success('delivery_order', $response['delivery_order'],$message);
        }catch(Exception $e){
            return ResponseFormatter::error("Gagal Mendapatkan delivery order: ". $e->getMessage());
        }
    }
    public function getAllDeliveryOrderDalamPerjalananByUser(Request $request){
        try{
            $user = $request->user();
            $response = DeliveryOrder::getAllDeliveryOrderByUser(false, $user, 'DRIVER_DALAM_PERJALANAN', 'all');

            $message = ($response['delivery_order']->isEmpty()) ? 'Tidak ada delivery order dalam perjalanan' : 'Berhasil Mendapatkan delivery order Dalam Perjalanan';
            return ResponseFormatter::success('delivery_order', $response['delivery_order'],$message);
        }catch(Exception $e){
            return ResponseFormatter::error("Gagal Mendapatkan delivery order Dalam Perjalanan: ". $e->getMessage());
        }
    }
    public function getCountActiveDeliveryOrderByUser(Request $request){
        try{
            $user = $request->user();
            $response = DeliveryOrder::getCountActiveDeliveryOrderByUser($user->id);
            $message = ($response==0) ? 'Tidak ada delivery order' : 'Berhasil Mendapatkan Jumlah delivery order Aktif';
            return ResponseFormatter::success('total_active', $response,$message);
        }catch(Exception $e){
            return ResponseFormatter::error("Gagal Mendapatkan delivery order: ". $e->getMessage());
        }
    }
    public function getSomeActiveDeliveryOrderByUser(Request $request){
        try{
            $user = $request->user();
            $size = $request->query('size') ?? 5;
            $response = DeliveryOrder::getAllDeliveryOrderByUser(true, $user, 'active', $size);
            $message = ($response['delivery_order']->isEmpty()) ? 'Tidak ada delivery order' : 'Berhasil Mendapatkan delivery order';
            return ResponseFormatter::success('data', $response,$message);
        }catch(Exception $e){
            return ResponseFormatter::error("Gagal Mendapatkan delivery order: ". $e->getMessage());
        }
    }
    public function getDeliveryOrderById(Request $request){
        try{
            $delivery_order_id = $request->route('id');
            
            $request->merge(['id' => $delivery_order_id]);
            $request->validate([
                'id' => 'required|exists:delivery_orders,id',
            ]);

            $response = DeliveryOrder::find($request->id);
            $material = DeliveryOrder::getAllPreOrder($response->id);
            
            $admin_gudang = ($response->adminGudang) ? [
                'nama' => $response->adminGudang->nama,
                'no_hp' => $response->adminGudang->no_hp,
                'foto' => $response->adminGudang->foto,
            ] : null;

            $purchasing = [
                'nama' => $response->purchasing->nama,
                'no_hp' => $response->purchasing->no_hp,
                'foto' => $response->purchasing->foto,
            ];

            $kendaraan = ($response->kendaraan) ? [
                'merk' => $response->kendaraan->merk,
                'plat_nomor' => $response->kendaraan->plat_nomor,
                'gambar' => $response->kendaraan->gambar,
                'jenis' => $response->kendaraan->jenis,
            ] : null;

            $logistic = ($response->logistic) ? [
                'id' => $response->logistic->id,
                'nama' => $response->logistic->nama,
                'no_hp' => $response->logistic->no_hp,
                'foto' => $response->logistic->foto,
            ] : null;
            
            $lokasi = DeliveryOrder::getLokasiAsalTujuan($response->id);
            $delivery_order = collect([
                'id' => $response->id,
                'kode_do' => $response->kode_do,
                'ttd' => $response->ttd,
                'foto_bukti' => $response->foto_bukti,
                'status' => $response->status,
                'untuk_perhatian' => $response->untuk_perhatian,
                'perihal' => $response->perihal,
                'updated_at' => $response->updated_at,
                'created_at' => $response->created_at, 
                'tgl_pengambilan' => $response->tgl_pengambilan, 
                'kendaraan' => $kendaraan,
                'admin_gudang' => $admin_gudang,
                'purchasing' => $purchasing,
                'logistic' => $logistic,
                'tempat_asal' => $lokasi['lokasi_asal'],
                'tempat_tujuan' => $lokasi['lokasi_tujuan'],
                'pre_order' => $material,
            ]);
            $message = ($delivery_order->isEmpty()) ? 'Tidak ada delivery order' : 'Berhasil Mendapatkan delivery order';
            return ResponseFormatter::success('delivery_order', $delivery_order,$message);
        }catch(Exception $e){
            return ResponseFormatter::error("Gagal Mendapatkan delivery order: ". $e->getMessage());
        }
    }

    public function markCompleteDeliveryOrder(Request $request){
        try{
            $user = $request->user();
            
            $request->validate([
                'id' => 'required|exists:delivery_orders,id'
            ]);
            $deliveryOrder = DeliveryOrder::find($request->id);
            $deliveryOrder->status = 'SELESAI';
            $deliveryOrder->admin_gudang_id = $user->id;
            $deliveryOrder->update();
            $response = collect($deliveryOrder);
            $message = ($response->isEmpty()) ? 'Tidak ada delivery order' : 'Berhasil Menandai Selesai Delivery Order';
            return ResponseFormatter::success('delivery_order', $response,$message);
        }catch(Exception $e){
            return ResponseFormatter::error("Gagal Menandai Selesai Delivery Order: ". $e->getMessage());
        }
    }
    

}
