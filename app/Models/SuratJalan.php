<?php

namespace App\Models;

use App\Helpers\Date;
use App\Helpers\IDGenerator;
use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SuratJalan extends Model
{
    use Uuids;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = [
        'deleted_at',
    ];

    public function kendaraan(){
        return $this->belongsTo(Kendaraan::class);
    }

    public function logistic(){
        return $this->belongsTo(User::class, 'logistic_id');
    }

    public function adminGudang(){
        return $this->belongsTo(User::class, 'admin_gudang_id');
    }
    
    public function sjPengirimanPp(){
        return $this->hasOne(SjPengirimanPp::class);
    }

    public function sjPengirimanGp(){
        return $this->hasOne(SjPengirimanGp::class);
    }

    public function sjPengembalian(){
        return $this->hasOne(SjPengembalian::class);
    }
    public function ttdSjAdmin(){
        return $this->belongsTo(TtdVerification::class,'ttd_admin');
    }
    public function ttdSjSupervisor(){
        return $this->belongsTo(TtdVerification::class,'ttd_supervisor');
    }
    public function ttdSjDriver(){
        return $this->belongsTo(TtdVerification::class,'ttd_driver');
    }
    public function getCreatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }

    public function getUpdatedAtAttribute($date)
    {
        return Date::dateToMillisecond($date);
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('nama_proyek', 'like', '%' . $search . '%');
        });
        $query->when($filters['filter'] ?? false, function($query, $filter) {
        //    return $query->where(function($query) use ($filter) {
            if($filter != 'semua status'){
                if($filter == 'selesai')
                    return $query->where('selesai', true);
                if($filter == 'masih berlangsung')
                    return $query->where('selesai', false);
            }
            // });
        });
        $query->when(!isset($filters['orderBy']), function($query){
            return $query->orderBy('created_at', 'DESC');
        });
        $query->when($filters['orderBy'] ?? false, function($query, $orderBy) {
            if($orderBy == 'terbaru') return $query->orderBy('created_at', 'DESC');
            if($orderBy == 'terlama') return $query->orderBy('created_at', 'ASC');
            if($orderBy == 'jumlah tersedikit') return $query->orderBy('jumlah', 'ASC');
            if($orderBy == 'jumlah terbanyak') return $query->orderBy('jumlah', 'DESC');
        });
    }

    public static function generateKodeSurat($tipe, $client, $supervisor){
        $clientAcronym = IDGenerator::getAcronym($client);
        $supervisorAcronym = IDGenerator::getAcronym($supervisor);
        $romanMonth = IDGenerator::numberToRoman(Date::getMonthNumber());
        $year = Date::getYearNumber();
        $prefix = "$clientAcronym/$supervisorAcronym/$romanMonth/$year";
        $typePrefix = NULL;
        if($tipe == "PENGIRIMAN_PROYEK_PROYEK"){
            $typePrefix = "SJPP";
        }else if($tipe == "PENGIRIMAN_GUDANG_PROYEK"){
            $typePrefix = "SJGP";
        }else{
            $typePrefix = "SJPG";
        }
        return IDGenerator::generateID(self::class,'kode_surat',5,"$typePrefix/$prefix");
    }
    public static function createData(Request $request){
        self::validateCreate($request);
        return self::create([
            'admin_gudang_id' => $request->admin_gudang_id,
            'logistic_id' => $request->logistic_id,
            'kendaraan_id' => $request->kendaraan_id,
            'ttd_admin' => $request->ttd_admin,
            'tipe' => $request->tipe,
        ]);
    }
    public static function updateData(Request $request){
        self::validateUpdate($request);
        self::where('id', $request->surat_jalan_id)->update([
            'admin_gudang_id' => $request->admin_gudang_id,
            'logistic_id' => $request->logistic_id,
            'kendaraan_id' => $request->kendaraan_id,
            'ttd_admin' => $request->ttd_admin,
            'tipe' => $request->tipe,
        ]);
        return self::where('id', $request->surat_jalan_id)->first();
    }
    public static function validateCreate(Request $request){
        $request->validate([
            'admin_gudang_id' => 'required|exists:users,id',
            'logistic_id' => 'required|exists:users,id',
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'tipe' => 'required|in:PENGIRIMAN_GUDANG_PROYEK,PENGIRIMAN_PROYEK_PROYEK,PENGEMBALIAN',
        ]);
        $request->merge(['ttd_admin' => User::getTTD($request->admin_gudang_id)]);
        $request->validate([
            'ttd_admin' => 'required',
        ]);
        if($request->tipe=='PENGIRIMAN_PROYEK_PROYEK'){
            SjPengirimanPp::validateCreate($request,false);
        }else if($request->tipe=='PENGIRIMAN_GUDANG_PROYEK'){
            SjPengirimanGp::validateCreate($request,false);
        }else{
            SjPengembalian::validateCreate($request,false);
        }
    }
    public static function markCompleteSuratJalan($id){
        self::where('id',$id);
    }
    public static function validateUpdate(Request $request){
        $request->validate([
            'surat_jalan_id' => 'required|exists:surat_jalans,id',
        ]);
        self::validateCreate($request);
    }
    public static function getLokasiAsalTujuan($surat_jalan_id){
        $lokasi = collect();
        $lokasiAsal = collect();
        $lokasiTujuan = collect();
        $sj = self::find($surat_jalan_id);
        if($sj->sjPengirimanGp !=null){
            $lokasiAsal['nama'] = $sj->sjPengirimanGp->peminjaman->gudang->nama;
            $lokasiAsal['alamat'] = $sj->sjPengirimanGp->peminjaman->gudang->alamat;
            $lokasiAsal['coordinate'] = $sj->sjPengirimanGp->peminjaman->gudang->latitude . "|" . $sj->sjPengirimanGp->peminjaman->gudang->longitude;
            
            $lokasiTujuan['nama'] = $sj->sjPengirimanGp->peminjaman->menangani->proyek->nama_proyek;
            $lokasiTujuan['alamat'] = $sj->sjPengirimanGp->peminjaman->menangani->proyek->alamat;
            $lokasiTujuan['coordinate'] = $sj->sjPengirimanGp->peminjaman->menangani->proyek->latitude . "|" . $sj->sjPengirimanGp->peminjaman->menangani->proyek->longitude;
            
        }else if($sj->sjPengembalian!=null){
            $lokasiAsal['nama'] = $sj->sjPengembalian->pengembalian->peminjaman->menangani->proyek->nama_proyek;
            $lokasiAsal['alamat'] = $sj->sjPengembalian->pengembalian->peminjaman->menangani->proyek->alamat;
            $lokasiAsal['coordinate'] = $sj->sjPengembalian->pengembalian->peminjaman->menangani->proyek->latitude . "|" . $sj->sjPengembalian->pengembalian->peminjaman->menangani->proyek->longitude;
            
            $lokasiTujuan['nama'] = $sj->sjPengembalian->pengembalian->peminjaman->gudang->nama;
            $lokasiTujuan['alamat'] = $sj->sjPengembalian->pengembalian->peminjaman->gudang->alamat;
            $lokasiTujuan['coordinate'] = $sj->sjPengembalian->pengembalian->peminjaman->gudang->latitude . "|" . $sj->sjPengembalian->pengembalian->peminjaman->gudang->longitude;
        }else if($sj->sjPengirimanPp!=null){
            $lokasiAsal['nama'] = $sj->sjPengirimanPp->peminjaman->peminjamanPp->peminjamanAsal->menangani->proyek->nama_proyek;
            $lokasiAsal['alamat'] = $sj->sjPengirimanPp->peminjaman->peminjamanPp->peminjamanAsal->menangani->proyek->alamat;
            $lokasiAsal['coordinate'] = $sj->sjPengirimanPp->peminjaman->peminjamanPp->peminjamanAsal->menangani->proyek->latitude . "|" . $sj->sjPengirimanPp->peminjaman->peminjamanPp->peminjamanAsal->menangani->proyek->longitude;
            
            $lokasiTujuan['nama'] = $sj->sjPengirimanPp->peminjaman->menangani->proyek->nama_proyek;
            $lokasiTujuan['alamat'] = $sj->sjPengirimanPp->peminjaman->menangani->proyek->alamat;
            $lokasiTujuan['coordinate'] = $sj->sjPengirimanPp->peminjaman->menangani->proyek->latitude . "|" . $sj->sjPengirimanPp->peminjaman->menangani->proyek->longitude;
        }

        $lokasi['lokasi_asal'] = $lokasiAsal;
        $lokasi['lokasi_tujuan'] = $lokasiTujuan;
        return $lokasi;
    }
    public static function getAllSuratJalanDalamPerjalananByAdminGudang($adminGudangId,$tipeRelasi){
        $response = self::where('status', 'DRIVER_DALAM_PERJALANAN');
        $surat_jalan = $response->has($tipeRelasi)->where('admin_gudang_id', $adminGudangId)->get();
        return $surat_jalan;
    }
    public static function getAllSuratJalanDalamPerjalananByLogistic($logisticId,$tipeRelasi){
        $response = self::where('status', 'DRIVER_DALAM_PERJALANAN');
        $surat_jalan = $response->has($tipeRelasi)->where('logistic_id', $logisticId)->get();
        return $surat_jalan;
    }
    public static function getAllSuratJalanDalamPerjalananBySupervisor($supervisorId,$tipeRelasi){
        $response = self::where('status', 'DRIVER_DALAM_PERJALANAN');
        if($tipeRelasi=='sjPengirimanGp'){
            $surat_jalan = $response->has('sjPengirimanGp')->whereRelation('sjPengirimanGp.peminjaman.menangani.supervisor', 'id', $supervisorId)->get();
        }else if($tipeRelasi=='sjPengirimanPp'){
            $surat_jalan = $response->has('sjPengirimanPp')->whereRelation('sjPengirimanPp.peminjaman.menangani.supervisor', 'id', $supervisorId)->get();
        }else if($tipeRelasi=='sjPengembalian'){
            $surat_jalan = $response->has('sjPengembalian')->whereRelation('sjPengembalian.pengembalian.peminjaman.menangani.supervisor', 'id', $supervisorId)->get();
        }
        return $surat_jalan;
    }
    public static function getAllSuratJalanDalamPerjalananByPM($pmId,$tipeRelasi){
        $response = self::where('status', 'DRIVER_DALAM_PERJALANAN');
        if($tipeRelasi=='sjPengirimanGp'){
            $surat_jalan = $response->has('sjPengirimanGp')->whereRelation('sjPengirimanGp.peminjaman.menangani.proyek.projectManager', 'id', $pmId)->get();
        }else if($tipeRelasi=='sjPengirimanPp'){
            $surat_jalan = $response->has('sjPengirimanPp')->whereRelation('sjPengirimanPp.peminjaman.menangani.proyek.projectManager', 'id', $pmId)->get();
        }else if($tipeRelasi=='sjPengembalian'){
            $surat_jalan = $response->has('sjPengembalian')->whereRelation('sjPengembalian.pengembalian.peminjaman.menangani.proyek.projectManager', 'id', $pmId)->get();
        }
        return $surat_jalan;
    }
    public static function getAllSuratJalanByUser($with_total,$user,$tipe,$status,$size=10, $date_start=null, $date_end=null, $srch=null){
        $result = collect();
        // $date_start = $date_s ?? date('Y-m-d 00:00:00', strtotime("-1 month"));
        // $date_end = $date_e ?? date("Y-m-d 23:59:59");
        if($tipe == 'PENGIRIMAN_GUDANG_PROYEK') $tipeRelasi = 'sjPengirimanGp';
        else if($tipe == 'PENGIRIMAN_PROYEK_PROYEK') $tipeRelasi = 'sjPengirimanPp';
        else if($tipe == 'PENGEMBALIAN') $tipeRelasi = 'sjPengembalian';

        if($tipe!='all') {
            $response= self::where('tipe', $tipe);
            if($status=='active') $response->where('status', '!=', 'SELESAI');
            else $response->where('status', $status);
            
            if($date_start!=null && $date_end!=null) $response->whereBetween('updated_at', [$date_start, $date_end]);
            $response->where('kode_surat', 'LIKE', "%$srch%");
        }

        if($user->role == 'ADMIN_GUDANG') {
            if($tipe != 'all'){
                $surat_jalan = ($size!='all') ? 
                $response->has($tipeRelasi)->where('admin_gudang_id', $user->id)->paginate($size)->withQueryString()
                : $response->has($tipeRelasi)->where('admin_gudang_id', $user->id)->get();
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, $tipeRelasi));
                }
            }else if($tipe=='all' && $status == 'DRIVER_DALAM_PERJALANAN'){
                foreach(self::getAllSuratJalanDalamPerjalananByAdminGudang($user->id,'sjPengirimanGp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, 'sjPengirimanGp'));
                }
                foreach(self::getAllSuratJalanDalamPerjalananByAdminGudang($user->id,'sjPengirimanPp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, 'sjPengirimanPp'));
                }
                foreach(self::getAllSuratJalanDalamPerjalananByAdminGudang($user->id,'sjPengembalian') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, 'sjPengembalian'));
                }
            }
        }else if($user->role == 'LOGISTIC') {
            if($tipe != 'all'){
                $surat_jalan = ($size!='all') ? 
                $response->has($tipeRelasi)->where('logistic_id', $user->id)->paginate($size)->withQueryString()
                : $response->has($tipeRelasi)->where('logistic_id', $user->id)->get();
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, $tipeRelasi));
                }
            }else if($tipe=='all' && $status == 'DRIVER_DALAM_PERJALANAN'){
                foreach(self::getAllSuratJalanDalamPerjalananByLogistic($user->id,'sjPengembalian') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, 'sjPengembalian'));
                }
                foreach(self::getAllSuratJalanDalamPerjalananByLogistic($user->id,'sjPengirimanGp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, 'sjPengirimanGp'));
                }
                foreach(self::getAllSuratJalanDalamPerjalananByLogistic($user->id,'sjPengirimanPp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, 'sjPengirimanPp'));
                }
            }
        }else if($user->role == 'SUPERVISOR'){
            if($tipe == 'PENGIRIMAN_GUDANG_PROYEK'){
                $surat_jalan = ($size!='all') ? 
                $response->has($tipeRelasi)->whereRelation('sjPengirimanGp.peminjaman.menangani.supervisor', 'id', $user->id)->paginate($size)->withQueryString()
                : $response->has($tipeRelasi)->whereRelation('sjPengirimanGp.peminjaman.menangani.supervisor', 'id', $user->id)->get();
                
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, $tipeRelasi));
                }
            }else if($tipe == 'PENGIRIMAN_PROYEK_PROYEK'){
                $surat_jalan = ($size!='all') ? 
                $response->has($tipeRelasi)->whereRelation('sjPengirimanPp.peminjaman.menangani.supervisor', 'id', $user->id)->paginate($size)->withQueryString()
                : $response->has($tipeRelasi)->whereRelation('sjPengirimanPp.peminjaman.menangani.supervisor', 'id', $user->id)->get();
                
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, $tipeRelasi));
                }
            }else if($tipe=='PENGEMBALIAN'){
                $surat_jalan = ($size!='all') ? 
                $response->has($tipeRelasi)->whereRelation('sjPengembalian.pengembalian.peminjaman.menangani.supervisor', 'id', $user->id)->paginate($size)->withQueryString()
                : $response->has($tipeRelasi)->whereRelation('sjPengembalian.pengembalian.peminjaman.menangani.supervisor', 'id', $user->id)->get();
                
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, $tipeRelasi));
                }
            }else if($tipe=='all' && $status == 'DRIVER_DALAM_PERJALANAN'){
                foreach(self::getAllSuratJalanDalamPerjalananBySupervisor($user->id,'sjPengembalian') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, 'sjPengembalian'));
                }
                foreach(self::getAllSuratJalanDalamPerjalananBySupervisor($user->id,'sjPengirimanPp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, 'sjPengirimanPp'));
                }
                foreach(self::getAllSuratJalanDalamPerjalananBySupervisor($user->id,'sjPengirimanGp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, 'sjPengirimanGp'));
                }
            }
        }else if($user->role == 'PROJECT_MANAGER'){
            if($tipe == 'PENGIRIMAN_GUDANG_PROYEK'){
                $surat_jalan = ($size!='all') ? 
                $response->has($tipeRelasi)->whereRelation('sjPengirimanGp.peminjaman.menangani.proyek.projectManager', 'id', $user->id)->paginate($size)->withQueryString()
                : $response->has($tipeRelasi)->whereRelation('sjPengirimanGp.peminjaman.menangani.proyek.projectManager', 'id', $user->id)->get();
                
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, $tipeRelasi));
                }
            }else if($tipe == 'PENGIRIMAN_PROYEK_PROYEK'){
                $surat_jalan = ($size!='all') ? 
                $response->has($tipeRelasi)->whereRelation('sjPengirimanPp.peminjaman.menangani.proyek.projectManager', 'id', $user->id)->paginate($size)->withQueryString()
                : $response->has($tipeRelasi)->whereRelation('sjPengirimanPp.peminjaman.menangani.proyek.projectManager', 'id', $user->id)->get();
                
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, $tipeRelasi));
                }
            }else if($tipe=='PENGEMBALIAN'){
                $surat_jalan = ($size!='all') ? 
                $response->has($tipeRelasi)->whereRelation('sjPengembalian.pengembalian.peminjaman.menangani.proyek.projectManager', 'id', $user->id)->paginate($size)->withQueryString()
                : $response->has($tipeRelasi)->whereRelation('sjPengembalian.pengembalian.peminjaman.menangani.proyek.projectManager', 'id', $user->id)->get();
                
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, $tipeRelasi));
                }
            }else if($tipe=='all' && $status == 'DRIVER_DALAM_PERJALANAN'){
                foreach(self::getAllSuratJalanDalamPerjalananBySupervisor($user->id,'sjPengirimanGp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, 'sjPengirimanGp'));
                }
                foreach(self::getAllSuratJalanDalamPerjalananBySupervisor($user->id,'sjPengirimanPp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, 'sjPengirimanPp'));
                }
                foreach(self::getAllSuratJalanDalamPerjalananBySupervisor($user->id,'sjPengembalian') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj, 'sjPengembalian'));
                }
            }
        }
        $final_result = collect();
        $final_result['surat_jalan'] = $result;
        if($with_total) $final_result['count'] = $surat_jalan->total();
        return $final_result;
    }
    public static function getSimpleDataSuratJalanByUser($sj, $tipe){
        $data = collect();
        $nama_project_manager = ($tipe != 'sjPengembalian') ? $sj->$tipe->peminjaman->menangani->proyek->projectManager->nama : $sj->$tipe->pengembalian->peminjaman->menangani->proyek->projectManager->nama;
        $foto_project_manager = ($tipe != 'sjPengembalian') ? $sj->$tipe->peminjaman->menangani->proyek->projectManager->foto: $sj->$tipe->pengembalian->peminjaman->menangani->proyek->projectManager->foto;
        $nama_supervisor = ($tipe != 'sjPengembalian') ? $sj->$tipe->peminjaman->menangani->supervisor->nama : $sj->$tipe->pengembalian->peminjaman->menangani->supervisor->nama;
        $foto_supervisor = ($tipe != 'sjPengembalian') ? $sj->$tipe->peminjaman->menangani->supervisor->foto : $sj->$tipe->pengembalian->peminjaman->menangani->supervisor->foto;
        $nama_admin_gudang = ($tipe != 'sjPengembalian') ? $sj->$tipe->peminjaman->menangani->proyek->projectManager->nama : $sj->$tipe->pengembalian->peminjaman->menangani->proyek->projectManager->nama;
        $foto_admin_gudang = ($tipe != 'sjPengembalian') ? $sj->$tipe->peminjaman->menangani->proyek->projectManager->foto : $sj->$tipe->pengembalian->peminjaman->menangani->proyek->projectManager->foto;
        $foto_driver = $sj->logistic->foto;
        $nama_driver = $sj->logistic->nama;
        
        $lokasi = self::getLokasiAsalTujuan($sj->id);

        $data['id'] = $sj->id;
        $data['kode_surat'] = $sj->kode_surat;
        $data['status'] = $sj->status;
        $data['tipe'] = $sj->tipe;
        $data['updated_at'] = $sj->updated_at;
        $data['nama_project_manager'] = $nama_project_manager;
        $data['foto_project_manager'] = $foto_project_manager;
        $data['nama_admin_gudang'] = $nama_admin_gudang;
        $data['foto_admin_gudang'] = $foto_admin_gudang;
        $data['nama_driver'] = $nama_driver;
        $data['foto_driver'] = $foto_driver;
        $data['nama_supervisor'] = $nama_supervisor;
        $data['foto_supervisor'] = $foto_supervisor; 
        $data['nama_tempat_asal'] = $lokasi['lokasi_asal']['nama'];
        $data['alamat_tempat_asal'] = $lokasi['lokasi_asal']['alamat'];
        $data['coordinate_tempat_asal'] = $lokasi['lokasi_asal']['coordinate'];
        $data['nama_tempat_tujuan'] = $lokasi['lokasi_tujuan']['nama'];
        $data['alamat_tempat_tujuan'] = $lokasi['lokasi_tujuan']['alamat'];
        $data['coordinate_tempat_tujuan'] = $lokasi['lokasi_tujuan']['coordinate'];
        return $data;
    }

}