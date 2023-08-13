<?php

namespace App\Models;

use App\Enum\DeliveryOrderStatus;
use App\Helpers\Date;
use App\Helpers\IDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class DeliveryOrder extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $hidden = [
        'deleted_at',
    ];

    public function logistic(){
        return $this->belongsTo(User::class, 'logistic_id');
    }
    public function purchasing(){
        return $this->belongsTo(User::class,'purchasing_id');
    }
    public function adminGudang(){
        return $this->belongsTo(User::class,'admin_gudang_id');
    }
    public function kendaraan(){
        return $this->belongsTo(Kendaraan::class);
    }
    public function preOrder(){
        return $this->hasMany(PreOrder::class);
    }
    public function perusahaan(){
        return $this->belongsTo(Perusahaan::class);
    }
    public function gudang(){
        return $this->belongsTo(Gudang::class);
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function getTglPengambilanAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }
    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('kode_do', 'like', '%' . $search . '%');
        });
        $query->when($filters['filter'] ?? false, function($query, $filter) {
            if($filter == 'selesai')
                return $query->where('status', 'SELESAI');
            if($filter == 'driver dalam perjalanan')
                return $query->where('status', 'DRIVER_DALAM_PERJALANAN');
            if($filter == 'menunggu konfirmasi driver')
                return $query->where('status', 'MENUNGGU_KONFIRMASI_DRIVER');
        });
        $query->when(!isset($filters['orderBy']), function($query){
            return $query->orderBy('created_at', 'DESC');
        });
        $query->when(!isset($filters['datestart']), function($query){
            return $query->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime("-3 years")),date('Y-m-d 23:59:59')]);
        });
        $query->when($filters['orderBy'] ?? false, function($query, $orderBy) {
            if($orderBy == 'terbaru') return $query->orderBy('created_at', 'DESC');
            if($orderBy == 'terlama') return $query->orderBy('created_at', 'ASC');
        });
        $query->when($filters['datestart'] ?? false, function($query, $datestart) use ($filters){
            $date_start = $datestart." 00:00:00";
            $query->when($filters['dateend'] ?? false, function($query, $dateend) use ($date_start) {
                $date_end = $dateend." 23:59:59";
                return $query->whereBetween('created_at', [$date_start, $date_end]);
            });
        });
    }
    public static function generateKodeDO($nama_perusahaan, $tgl_pengambilan){
        $perusahaanAlias = IDGenerator::getAcronym($nama_perusahaan);
        $romanMonth = IDGenerator::numberToRoman(Date::getMonthNumber($tgl_pengambilan));
        $prefix = "DO/BC-" . $perusahaanAlias . "/" . $romanMonth . "/" . Date::getYearNumber();
        return IDGenerator::generateID(new static, 'kode_do', 5, $prefix);
    }
    public static function createData(Request $request){
        self::validateCreate($request);
        $do = self::create([
            'untuk_perhatian' => $request->untuk_perhatian,
            'perihal' => $request->perihal,
            'tgl_pengambilan' => $request->tgl_pengambilan,
            'purchasing_id' => $request->purchasing_id,
            'gudang_id' => $request->gudang_id,
            'perusahaan_id' => $request->perusahaan_id,
        ]);
        return $do;
    }
    public static function updateData(Request $request){
        self::validateUpdate($request);
        $do = self::where('id', $request->delivery_order_id)->update([
            'untuk_perhatian' => $request->untuk_perhatian,
            'perihal' => $request->perihal,
            'tgl_pengambilan' => $request->tgl_pengambilan,
            'purchasing_id' => $request->purchasing_id,
            'gudang_id' => $request->gudang_id,
            'perusahaan_id' => $request->perusahaan_id,
        ]);
        return $do;
    }
    public static function setTtd($id, $purhcasing_id){
        self::where('id',$id)->update([
            'ttd' => TtdVerification::createTtdDoVerification($purhcasing_id),
        ]);
    }
    public static function validateCreate(Request $request){
        $request->validate([
            'untuk_perhatian' => 'required',
            'perihal' => 'required',
            'tgl_pengambilan' => 'required|date|date_format:Y-m-d',
            'purchasing_id' => 'required|exists:users,id',
            'gudang_id' => 'required|exists:gudangs,id',
            'perusahaan_id' => 'required|exists:perusahaans,id',
        ]);
        $ttd = User::getTTD($request->purchasing_id);
        if($ttd!=null){
            $request->merge(['ttd' => $ttd]);
        }
        $request->validate([
            'ttd' => 'required',
        ]);
    }
    public static function markCompleteDeliveryOrder($id){
        self::where('id',$id);
    }
    public static function getCountActiveDeliveryOrderByUser($user_id){
        $user = User::find($user_id);
        $data = self::getAllDeliveryOrderByUser(true, $user, 'active', 1);
        $result = $data['count'];
        return $result;
    }
    public static function validateUpdate(Request $request){
        $request->validate([
            'delivery_order_id' => 'required|exists:delivery_orders,id',
        ]);
        self::validateCreate($request);
    }
    public static function getLokasiAsalTujuan($delivery_order_id){
        $lokasi = collect();
        $lokasiAsal = collect();
        $lokasiTujuan = collect();
        $do = self::findOrFail($delivery_order_id);
        $lokasiAsal['nama'] = $do->gudang->nama;
        $lokasiAsal['foto'] = $do->gudang->gambar;
        $lokasiAsal['alamat'] = $do->gudang->alamat;
        $lokasiAsal['coordinate'] = $do->gudang->latitude . "|" . $do->gudang->longitude;
        
        $lokasiTujuan['nama'] = $do->perusahaan->nama;
        $lokasiTujuan['foto'] = $do->perusahaan->gambar;
        $lokasiTujuan['alamat'] = $do->perusahaan->alamat;
        $lokasiTujuan['coordinate'] = $do->perusahaan->latitude . "|" . $do->perusahaan->longitude;

        $lokasi['lokasi_asal'] = $lokasiAsal;
        $lokasi['lokasi_tujuan'] = $lokasiTujuan;
        return $lokasi;
    }
    public static function getAllPreOrder($delivery_order_id){
        $do = self::findOrFail($delivery_order_id);
        $result = collect();
        $preOrder = $do->preOrder;
        foreach($preOrder as $pd){
            $material=array();
            $material['id'] = $pd->id;
            $material['kode_po'] = $pd->kode_po;
            $material['nama_material'] = $pd->nama_material;
            $material['satuan'] = $pd->satuan;
            $material['ukuran'] = $pd->ukuran;
            $material['jumlah'] = $pd->jumlah;
            $material['keterangan'] = $pd->keterangan;
            $result->push($material);
        }
        return $result;
    }
    public static function getAllDeliveryOrderDalamPerjalananByAdmin(){
        return self::where('status', DeliveryOrderStatus::DRIVER_DALAM_PERJALANAN->value)->get();
    }
    public static function getAllDeliveryOrderDalamPerjalananByAdminGudang($adminGudangId){
        return self::where('status', DeliveryOrderStatus::DRIVER_DALAM_PERJALANAN->value)->where('admin_gudang_id', $adminGudangId)->get();;
    }
    public static function getAllDeliveryOrderDalamPerjalananByLogistic($logisticId){
        return self::where('status', DeliveryOrderStatus::DRIVER_DALAM_PERJALANAN->value)->where('logistic_id', $logisticId)->get();
    }
    public static function getAllDeliveryOrderDalamPerjalananByPurchasing($purchasingId){
        return self::where('status', DeliveryOrderStatus::DRIVER_DALAM_PERJALANAN->value)->where('purchasing_id', $purchasingId)->get();
    }
    public static function getAllDeliveryOrderByUser($with_total,$user,$status,$size=10, $date_start=null, $date_end=null, $srch=null){
        $result = collect();
        if($status=='active') $response=self::where('status', '!=', 'SELESAI')->orderBy('status', 'ASC');
        else $response=self::where('status', $status);
        if($date_start!=null && $date_end!=null) $response->whereBetween('updated_at', [$date_start, $date_end]);
        $response->where('kode_do', 'LIKE', "%$srch%");

        if($user->role == 'ADMIN') {
            if($size!='all'){
                $delivery_order = $response->orderBy('created_at')->paginate($size)->withQueryString();foreach($delivery_order as $do){
                    $result->push(self::getSimpleDataDeliveryOrderByUser($do));
                }
            }else if($status == DeliveryOrderStatus::DRIVER_DALAM_PERJALANAN->value && $size=='all'){
                foreach(self::getAllDeliveryOrderDalamPerjalananByAdmin() as $do){
                    $result->push(self::getSimpleDataDeliveryOrderByUser($do));
                }
            }
        }else if($user->role == 'ADMIN_GUDANG') {
            if($size!='all'){
                $delivery_order = $response->where(function ($query) use ($user, $status, $srch, $date_end, $date_start) {
                    $query->where('admin_gudang_id', null)->where('gudang_id', $user->adminGudang->gudang_id);
                    if($status=='active') $query->where('status', '!=', 'SELESAI');
                    $query->where('kode_do', 'LIKE', "%$srch%");
                    if($date_start!=null && $date_end!=null) $query->whereBetween('updated_at', [$date_start, $date_end]);
                })->orWhere(function ($query) use ($user, $status, $srch, $date_end, $date_start) {
                    $query->where('admin_gudang_id', $user->id)->where('gudang_id', $user->adminGudang->gudang_id);
                    if($status=='active') $query->where('status', '!=', 'SELESAI');
                    $query->where('kode_do', 'LIKE', "%$srch%");
                    if($date_start!=null && $date_end!=null) $query->whereBetween('updated_at', [$date_start, $date_end]);
                })->orderBy('created_at')->paginate($size)->withQueryString();
                foreach($delivery_order as $do){
                    $result->push(self::getSimpleDataDeliveryOrderByUser($do));
                }
            }else if($status == DeliveryOrderStatus::DRIVER_DALAM_PERJALANAN->value && $size=='all'){
                foreach(self::getAllDeliveryOrderDalamPerjalananByAdminGudang($user->id) as $do){
                    $result->push(self::getSimpleDataDeliveryOrderByUser($do));
                }
            }
        }else if($user->role == 'LOGISTIC') {
            if($size!='all'){
                $delivery_order = $response->where('logistic_id', $user->id)->orderBy('created_at')->paginate($size)->withQueryString();
                foreach($delivery_order as $do){
                    $result->push(self::getSimpleDataDeliveryOrderByUser($do));
                }
            }else if($status == DeliveryOrderStatus::DRIVER_DALAM_PERJALANAN->value && $size=='all'){
                foreach(self::getAllDeliveryOrderDalamPerjalananByLogistic($user->id) as $do){
                    $result->push(self::getSimpleDataDeliveryOrderByUser($do));
                }
            }
        }else if($user->role == 'PURCHASING'){
            if($size!='all'){
                $delivery_order =$response->where('purchasing_id', $user->id)->orderBy('created_at')->paginate($size)->withQueryString();
                foreach($delivery_order as $do){
                    $result->push(self::getSimpleDataDeliveryOrderByUser($do));
                }
            }else if($status == DeliveryOrderStatus::DRIVER_DALAM_PERJALANAN->value && $size=='all'){
                foreach(self::getAllDeliveryOrderDalamPerjalananByPurchasing($user->id) as $do){
                    $result->push(self::getSimpleDataDeliveryOrderByUser($do));
                }
            }
        }
        $final_result = collect();
        $final_result['delivery_order'] = $result;
        if($with_total) $final_result['count'] = $delivery_order->total();
        return $final_result;
    }
    public static function getSimpleDataDeliveryOrderByUser($do){
        $data = collect();
        $lokasi = self::getLokasiAsalTujuan($do->id);
        $data['id'] = $do->id;
        $data['kode_do'] = $do->kode_do;
        $data['status'] = $do->status;
        $data['updated_at'] = $do->updated_at;
        $data['nama_purchasing'] = $do->purchasing->nama;
        $data['foto_purchasing'] = $do->purchasing->foto;
        $data['nama_admin_gudang'] = ($do->adminGudang) ? $do->adminGudang->nama : null;
        $data['foto_admin_gudang'] = ($do->adminGudang) ? $do->adminGudang->foto : null;
        $data['nama_driver'] = ($do->logistic) ? $do->logistic->nama : null;
        $data['foto_driver'] = ($do->logistic) ? $do->logistic->foto : null;
        $data['nama_tempat_asal'] = $lokasi['lokasi_asal']['nama'];
        $data['alamat_tempat_asal'] = $lokasi['lokasi_asal']['alamat'];
        $data['coordinate_tempat_asal'] = $lokasi['lokasi_asal']['coordinate'];
        $data['nama_tempat_tujuan'] = $lokasi['lokasi_tujuan']['nama'];
        $data['alamat_tempat_tujuan'] = $lokasi['lokasi_tujuan']['alamat'];
        $data['coordinate_tempat_tujuan'] = $lokasi['lokasi_tujuan']['coordinate'];
        return $data;
    }
}
