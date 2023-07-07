<?php

namespace App\Models;

use App\Enum\SuratJalanStatus;
use App\Enum\SuratJalanTipe;
use App\Enum\UserRole;
use App\Helpers\Date;
use App\Helpers\IDGenerator;
use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

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
        if($tipe == SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value){
            $typePrefix = "SJPP";
        }else if($tipe == SuratJalanTipe::PENGIRIMAN_GUDANG_PROYEK->value){
            $typePrefix = "SJGP";
        }else{
            $typePrefix = "SJPG";
        }
        return IDGenerator::generateID(self::class,'kode_surat',5,"$typePrefix/$prefix");
    }
    public static function createData(Request $request){
        self::validateCreate($request);
        $sj = self::create([
            'admin_gudang_id' => $request->admin_gudang_id,
            'logistic_id' => $request->logistic_id,
            'kendaraan_id' => $request->kendaraan_id,
            'tipe' => $request->tipe,
        ]);
        return $sj;
    }
    public static function updateData(Request $request){
        self::validateUpdate($request);
        $sj = self::where('id', $request->surat_jalan_id)->update([
            'admin_gudang_id' => $request->admin_gudang_id,
            'logistic_id' => $request->logistic_id,
            'kendaraan_id' => $request->kendaraan_id,
            'tipe' => $request->tipe,
        ]);
        return $sj;
    }
    public static function setTtdAdmin($id, $admin_gudang_id){
        self::where('id',$id)->update([
            'ttd_admin' => TtdVerification::createTtdSJVerification($admin_gudang_id,$id),
        ]);
    }
    public static function validateCreate(Request $request){
        $request->validate([
            'admin_gudang_id' => 'required|exists:users,id',
            'logistic_id' => 'required|exists:users,id',
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'tipe' => [
                'required',
                new Enum(SuratJalanTipe::class),
            ],
        ]);
        $ttd = User::getTTD($request->admin_gudang_id);
        if($ttd!=null){
            $request->merge(['ttd_admin' => $ttd]);
        }
        $request->validate([
            'ttd_admin' => 'required',
        ]);
        if($request->tipe==SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value){
            SjPengirimanPp::validateCreate($request,false);
        }else if($request->tipe==SuratJalanTipe::PENGIRIMAN_GUDANG_PROYEK->value){
            SjPengirimanGp::validateCreate($request,false);
        }else{
            SjPengembalian::validateCreate($request,false);
        }
    }
    public static function markCompleteSuratJalan($id){
        self::where('id',$id);
    }
    public static function getCountActiveSuratJalanByUser($user_id, SuratJalanTipe $tipe = null){
        $user = User::find($user_id);
        if($tipe==null){
            $sj_pengiriman_gp = self::getAllSuratJalanByUser(true, $user, SuratJalanTipe::PENGIRIMAN_GUDANG_PROYEK->value, 'active', 1);
            $sj_pengiriman_pp = self::getAllSuratJalanByUser(true, $user, SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value, 'active', 1);
            $sj_pengembalian = self::getAllSuratJalanByUser(true, $user, SuratJalanTipe::PENGEMBALIAN->value, 'active', 1);
            $result = $sj_pengiriman_gp['count'] + $sj_pengiriman_pp['count'] + $sj_pengembalian['count'];
        }else{
            $data = self::getAllSuratJalanByUser(true, $user, $tipe->value, 'active', 1);
            $result = $data['count'];
        }
        return $result;
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
        $sj = self::findOrFail($surat_jalan_id);
        if($sj->sjPengirimanGp !=null){
            $lokasiAsal['nama'] = $sj->sjPengirimanGp->peminjamanGp->gudang->nama;
            $lokasiAsal['foto'] = $sj->sjPengirimanGp->peminjamanGp->gudang->gambar;
            $lokasiAsal['alamat'] = $sj->sjPengirimanGp->peminjamanGp->gudang->alamat;
            $lokasiAsal['coordinate'] = $sj->sjPengirimanGp->peminjamanGp->gudang->latitude . "|" . $sj->sjPengirimanGp->peminjamanGp->gudang->longitude;
            
            $lokasiTujuan['nama'] = $sj->sjPengirimanGp->peminjamanGp->peminjaman->menangani->proyek->nama_proyek;
            $lokasiTujuan['foto'] = $sj->sjPengirimanGp->peminjamanGp->peminjaman->menangani->proyek->foto;
            $lokasiTujuan['alamat'] = $sj->sjPengirimanGp->peminjamanGp->peminjaman->menangani->proyek->alamat;
            $lokasiTujuan['coordinate'] = $sj->sjPengirimanGp->peminjamanGp->peminjaman->menangani->proyek->latitude . "|" . $sj->sjPengirimanGp->peminjamanGp->peminjaman->menangani->proyek->longitude;
            
        }else if($sj->sjPengembalian!=null){
            $lokasiAsal['nama'] = $sj->sjPengembalian->pengembalian->peminjaman->menangani->proyek->nama_proyek;
            $lokasiAsal['foto'] = $sj->sjPengembalian->pengembalian->peminjaman->menangani->proyek->foto;
            $lokasiAsal['alamat'] = $sj->sjPengembalian->pengembalian->peminjaman->menangani->proyek->alamat;
            $lokasiAsal['coordinate'] = $sj->sjPengembalian->pengembalian->peminjaman->menangani->proyek->latitude . "|" . $sj->sjPengembalian->pengembalian->peminjaman->menangani->proyek->longitude;
            
            $lokasiTujuan['nama'] = $sj->sjPengembalian->pengembalian->peminjaman->peminjamanGp->gudang->nama;
            $lokasiTujuan['foto'] = $sj->sjPengembalian->pengembalian->peminjaman->peminjamanGp->gudang->gambar;
            $lokasiTujuan['alamat'] = $sj->sjPengembalian->pengembalian->peminjaman->peminjamanGp->gudang->alamat;
            $lokasiTujuan['coordinate'] = $sj->sjPengembalian->pengembalian->peminjaman->peminjamanGp->gudang->latitude . "|" . $sj->sjPengembalian->pengembalian->peminjaman->peminjamanGp->gudang->longitude;
        }else if($sj->sjPengirimanPp!=null){
            $lokasiAsal['nama'] = $sj->sjPengirimanPp->peminjamanPp->peminjaman->peminjamanPp->peminjamanAsal->menangani->proyek->nama_proyek;
            $lokasiAsal['foto'] = $sj->sjPengirimanPp->peminjamanPp->peminjaman->peminjamanPp->peminjamanAsal->menangani->proyek->foto;
            $lokasiAsal['alamat'] = $sj->sjPengirimanPp->peminjamanPp->peminjaman->peminjamanPp->peminjamanAsal->menangani->proyek->alamat;
            $lokasiAsal['coordinate'] = $sj->sjPengirimanPp->peminjamanPp->peminjaman->peminjamanPp->peminjamanAsal->menangani->proyek->latitude . "|" . $sj->sjPengirimanPp->peminjamanPp->peminjaman->peminjamanPp->peminjamanAsal->menangani->proyek->longitude;
            
            $lokasiTujuan['nama'] = $sj->sjPengirimanPp->peminjamanPp->peminjaman->menangani->proyek->nama_proyek;
            $lokasiTujuan['foto'] = $sj->sjPengirimanPp->peminjamanPp->peminjaman->menangani->proyek->foto;
            $lokasiTujuan['alamat'] = $sj->sjPengirimanPp->peminjamanPp->peminjaman->menangani->proyek->alamat;
            $lokasiTujuan['coordinate'] = $sj->sjPengirimanPp->peminjamanPp->peminjaman->menangani->proyek->latitude . "|" . $sj->sjPengirimanPp->peminjamanPp->peminjaman->menangani->proyek->longitude;
        }

        $lokasi['lokasi_asal'] = $lokasiAsal;
        $lokasi['lokasi_tujuan'] = $lokasiTujuan;
        return $lokasi;
    }
    public static function getProjectManager($surat_jalan_id){
        $sj = self::findOrFail($surat_jalan_id);
        if($sj->tipe == SuratJalanTipe::PENGIRIMAN_GUDANG_PROYEK->value){
            return $sj->sjPengirimanGp->peminjamanGp->peminjaman->menangani->proyek->projectManager;
        }else if($sj->tipe == SuratJalanTipe::PENGEMBALIAN->value){
            return $sj->sjPengembalian->pengembalian->peminjaman->menangani->proyek->projectManager;
        }else if($sj->tipe == SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value){
            return $sj->sjPengirimanPp->peminjamanPp->peminjaman->menangani->proyek->projectManager;
        }
    }
    public static function getSupervisor($surat_jalan_id, $sv_peminjam = false){
        $sj = self::findOrFail($surat_jalan_id);
        if($sj->tipe == SuratJalanTipe::PENGIRIMAN_GUDANG_PROYEK->value){
            return $sj->sjPengirimanGp->peminjamanGp->peminjaman->menangani->supervisor;
        }else if($sj->tipe == SuratJalanTipe::PENGEMBALIAN->value){
            return $sj->sjPengembalian->pengembalian->peminjaman->menangani->supervisor;
        }else if($sj->tipe == SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value){
            if($sv_peminjam == false) return $sj->sjPengirimanPp->peminjamanPp->peminjaman->menangani->supervisor;
            else return $sj->sjPengirimanPp->peminjamanPp->peminjaman->peminjamanPp->peminjamanAsal->menangani->supervisor;
        }
    }
    public static function getAllBarang($surat_jalan_id){
        $sj = self::findOrFail($surat_jalan_id);
        $result = collect();
        if($sj->tipe == SuratJalanTipe::PENGIRIMAN_GUDANG_PROYEK->value){
            $result['barang_habis_pakai'] = Peminjaman::getAllBarang($sj->sjPengirimanGp->peminjamanGp->peminjaman->id, 'HABIS_PAKAI');
            $result['barang_tidak_habis_pakai'] = Peminjaman::getAllBarang($sj->sjPengirimanGp->peminjamanGp->peminjaman->id, 'TIDAK_HABIS_PAKAI');
        }else if($sj->tipe == SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value){
            $result['barang_habis_pakai'] = Peminjaman::getAllBarang($sj->sjPengirimanPp->peminjamanPp->peminjaman->id, 'HABIS_PAKAI');
            $result['barang_tidak_habis_pakai'] = Peminjaman::getAllBarang($sj->sjPengirimanPp->peminjamanPp->peminjaman->id, 'TIDAK_HABIS_PAKAI');
        }else if($sj->tipe == SuratJalanTipe::PENGEMBALIAN->value){
            $result['barang_habis_pakai'] = Pengembalian::getAllBarang($sj->sjPengembalian->pengembalian->id, 'HABIS_PAKAI');
            $result['barang_tidak_habis_pakai'] = Pengembalian::getAllBarang($sj->sjPengembalian->pengembalian->id, 'TIDAK_HABIS_PAKAI');
        }
        return $result;
    }
    public static function getAllSuratJalanDalamPerjalananByAdmin($admin_id,$tipeRelasi){
        $response = self::where('status', SuratJalanStatus::DRIVER_DALAM_PERJALANAN->value);
        $surat_jalan = $response->has($tipeRelasi)->get();
        return $surat_jalan;
    }
    public static function getAllSuratJalanDalamPerjalananByAdminGudang($adminGudangId,$tipeRelasi){
        $response = self::where('status', SuratJalanStatus::DRIVER_DALAM_PERJALANAN->value);
        $surat_jalan = $response->has($tipeRelasi)->where('admin_gudang_id', $adminGudangId)->get();
        return $surat_jalan;
    }
    public static function getAllSuratJalanDalamPerjalananByLogistic($logisticId,$tipeRelasi){
        $response = self::where('status', SuratJalanStatus::DRIVER_DALAM_PERJALANAN->value);
        $surat_jalan = $response->has($tipeRelasi)->where('logistic_id', $logisticId)->get();
        return $surat_jalan;
    }
    public static function getAllSuratJalanDalamPerjalananBySupervisor($supervisorId,$tipeRelasi){
        $response = self::where('status', SuratJalanStatus::DRIVER_DALAM_PERJALANAN->value);
        if($tipeRelasi=='sjPengirimanGp'){
            $surat_jalan = $response->has('sjPengirimanGp')->whereRelation('sjPengirimanGp.peminjamanGp.peminjaman.menangani.supervisor', 'id', $supervisorId)->get();
        }else if($tipeRelasi=='sjPengirimanPp'){
            $surat_jalan = $response->has('sjPengirimanPp')->whereRelation('sjPengirimanPp.peminjamanPp.peminjaman.menangani.supervisor', 'id', $supervisorId)->get();
        }else if($tipeRelasi=='sjPengembalian'){
            $surat_jalan = $response->has('sjPengembalian')->whereRelation('sjPengembalian.pengembalian.peminjaman.menangani.supervisor', 'id', $supervisorId)->get();
        }
        return $surat_jalan;
    }
    public static function getAllSuratJalanDalamPerjalananByPM($pmId,$tipeRelasi){
        $response = self::where('status', SuratJalanStatus::DRIVER_DALAM_PERJALANAN->value);
        if($tipeRelasi=='sjPengirimanGp'){
            $surat_jalan = $response->has('sjPengirimanGp')->whereRelation('sjPengirimanGp.peminjamanGp.peminjaman.menangani.proyek.projectManager', 'id', $pmId)->get();
        }else if($tipeRelasi=='sjPengirimanPp'){
            $surat_jalan = $response->has('sjPengirimanPp')->whereRelation('sjPengirimanPp.peminjamanPp.peminjaman.menangani.proyek.projectManager', 'id', $pmId)->get();
        }else if($tipeRelasi=='sjPengembalian'){
            $surat_jalan = $response->has('sjPengembalian')->whereRelation('sjPengembalian.pengembalian.peminjaman.menangani.proyek.projectManager', 'id', $pmId)->get();
        }
        return $surat_jalan;
    }
    public static function getAllSuratJalanByUser($with_total,$user,$tipe,$status,$size=10, $date_start=null, $date_end=null, $srch=null){
        $result = collect();
        // $date_start = $date_s ?? date('Y-m-d 00:00:00', strtotime("-1 month"));
        // $date_end = $date_e ?? date("Y-m-d 23:59:59");
        if($tipe == SuratJalanTipe::PENGIRIMAN_GUDANG_PROYEK->value) $tipeRelasi = 'sjPengirimanGp';
        else if($tipe == SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value) $tipeRelasi = 'sjPengirimanPp';
        else if($tipe == SuratJalanTipe::PENGEMBALIAN->value) $tipeRelasi = 'sjPengembalian';

        if($tipe!='all') {
            $response= self::where('tipe', $tipe);
            if($status=='active') $response->where('status', '!=', 'SELESAI')->orderBy('status', 'ASC');
            else $response->where('status', $status);
            
            if($date_start!=null && $date_end!=null) $response->whereBetween('updated_at', [$date_start, $date_end]);
            $response->where('kode_surat', 'LIKE', "%$srch%");
        }

        if($user->role == 'ADMIN') {
            if($tipe != 'all'){
                $surat_jalan = ($size!='all') ? 
                $response->has($tipeRelasi)->orderBy('created_at')->paginate($size)->withQueryString()
                : $response->has($tipeRelasi)->orderBy('created_at')->get();
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
            }else if($tipe=='all' && $status == SuratJalanStatus::DRIVER_DALAM_PERJALANAN->value){
                foreach(self::getAllSuratJalanDalamPerjalananByAdmin($user->id,'sjPengirimanGp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
                foreach(self::getAllSuratJalanDalamPerjalananByAdmin($user->id,'sjPengirimanPp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
                foreach(self::getAllSuratJalanDalamPerjalananByAdmin($user->id,'sjPengembalian') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
            }
        }else if($user->role == 'ADMIN_GUDANG') {
            if($tipe != 'all'){
                $surat_jalan = ($size!='all') ? 
                $response->has($tipeRelasi)->where('admin_gudang_id', $user->id)->orderBy('created_at')->paginate($size)->withQueryString()
                : $response->has($tipeRelasi)->where('admin_gudang_id', $user->id)->orderBy('created_at')->get();
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
            }else if($tipe=='all' && $status == SuratJalanStatus::DRIVER_DALAM_PERJALANAN->value){
                foreach(self::getAllSuratJalanDalamPerjalananByAdminGudang($user->id,'sjPengirimanGp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
                foreach(self::getAllSuratJalanDalamPerjalananByAdminGudang($user->id,'sjPengirimanPp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
                foreach(self::getAllSuratJalanDalamPerjalananByAdminGudang($user->id,'sjPengembalian') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
            }
        }else if($user->role == 'LOGISTIC') {
            if($tipe != 'all'){
                $surat_jalan = ($size!='all') ? 
                $response->has($tipeRelasi)->where('logistic_id', $user->id)->orderBy('created_at')->paginate($size)->withQueryString()
                : $response->has($tipeRelasi)->where('logistic_id', $user->id)->orderBy('created_at')->get();
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
            }else if($tipe=='all' && $status == SuratJalanStatus::DRIVER_DALAM_PERJALANAN->value){
                foreach(self::getAllSuratJalanDalamPerjalananByLogistic($user->id,'sjPengembalian') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
                foreach(self::getAllSuratJalanDalamPerjalananByLogistic($user->id,'sjPengirimanGp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
                foreach(self::getAllSuratJalanDalamPerjalananByLogistic($user->id,'sjPengirimanPp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
            }
        }else if($user->role == 'SUPERVISOR'){
            if($tipe == SuratJalanTipe::PENGIRIMAN_GUDANG_PROYEK->value){
                $surat_jalan = ($size!='all') ? 
                $response->has($tipeRelasi)->whereRelation('sjPengirimanGp.peminjamanGp.peminjaman.menangani.supervisor', 'id', $user->id)->orderBy('created_at')->paginate($size)->withQueryString()
                : $response->has($tipeRelasi)->whereRelation('sjPengirimanGp.peminjamanGp.peminjaman.menangani.supervisor', 'id', $user->id)->orderBy('created_at')->get();
                
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
            }else if($tipe == SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value){
                $surat_jalan = ($size!='all') ? 
                $response->has($tipeRelasi)->whereRelation('sjPengirimanPp.peminjamanPp.peminjaman.menangani.supervisor', 'id', $user->id)->orderBy('created_at')->paginate($size)->withQueryString()
                : $response->has($tipeRelasi)->whereRelation('sjPengirimanPp.peminjamanPp.peminjaman.menangani.supervisor', 'id', $user->id)->orderBy('created_at')->get();
                
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
            }else if($tipe==SuratJalanTipe::PENGEMBALIAN->value){
                $surat_jalan = ($size!='all') ? 
                $response->has($tipeRelasi)->whereRelation('sjPengembalian.pengembalian.peminjaman.menangani.supervisor', 'id', $user->id)->orderBy('created_at')->paginate($size)->withQueryString()
                : $response->has($tipeRelasi)->whereRelation('sjPengembalian.pengembalian.peminjaman.menangani.supervisor', 'id', $user->id)->orderBy('created_at')->get();
                
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
            }else if($tipe=='all' && $status == SuratJalanStatus::DRIVER_DALAM_PERJALANAN->value){
                foreach(self::getAllSuratJalanDalamPerjalananBySupervisor($user->id,'sjPengembalian') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
                foreach(self::getAllSuratJalanDalamPerjalananBySupervisor($user->id,'sjPengirimanPp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
                foreach(self::getAllSuratJalanDalamPerjalananBySupervisor($user->id,'sjPengirimanGp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
            }
        }else if($user->role == 'PROJECT_MANAGER'){
            if($tipe == SuratJalanTipe::PENGIRIMAN_GUDANG_PROYEK->value){
                $surat_jalan = ($size!='all') ? 
                $response->has($tipeRelasi)->whereRelation('sjPengirimanGp.peminjamanGp.peminjaman.menangani.proyek.projectManager', 'id', $user->id)->orderBy('created_at')->paginate($size)->withQueryString()
                : $response->has($tipeRelasi)->whereRelation('sjPengirimanGp.peminjamanGp.peminjaman.menangani.proyek.projectManager', 'id', $user->id)->orderBy('created_at')->get();
                
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
            }else if($tipe == SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value){
                $surat_jalan = ($size!='all') ? 
                $response->has($tipeRelasi)->whereRelation('sjPengirimanPp.peminjamanPp.peminjaman.menangani.proyek.projectManager', 'id', $user->id)->orderBy('created_at')->paginate($size)->withQueryString()
                : $response->has($tipeRelasi)->whereRelation('sjPengirimanPp.peminjamanPp.peminjaman.menangani.proyek.projectManager', 'id', $user->id)->orderBy('created_at')->get();
                
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
            }else if($tipe==SuratJalanTipe::PENGEMBALIAN->value){
                $surat_jalan = ($size!='all') ? 
                $response->has($tipeRelasi)->whereRelation('sjPengembalian.pengembalian.peminjaman.menangani.proyek.projectManager', 'id', $user->id)->orderBy('created_at')->paginate($size)->withQueryString()
                : $response->has($tipeRelasi)->whereRelation('sjPengembalian.pengembalian.peminjaman.menangani.proyek.projectManager', 'id', $user->id)->orderBy('created_at')->get();
                
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
            }else if($tipe=='all' && $status == SuratJalanStatus::DRIVER_DALAM_PERJALANAN->value){
                foreach(self::getAllSuratJalanDalamPerjalananBySupervisor($user->id,'sjPengirimanGp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
                foreach(self::getAllSuratJalanDalamPerjalananBySupervisor($user->id,'sjPengirimanPp') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
                foreach(self::getAllSuratJalanDalamPerjalananBySupervisor($user->id,'sjPengembalian') as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($sj));
                }
            }
        }
        $final_result = collect();
        $final_result['surat_jalan'] = $result;
        if($with_total) $final_result['count'] = $surat_jalan->total();
        return $final_result;
    }
    public static function getSimpleDataSuratJalanByUser($sj){
        $data = collect();
        $projectManager = self::getProjectManager($sj->id);
        $supervisor = self::getSupervisor($sj->id);
        $supervisor_peminjam = ($sj->tipe == SuratJalanTipe::PENGIRIMAN_PROYEK_PROYEK->value) ? self::getSupervisor($sj->id, true) : null;
        $lokasi = self::getLokasiAsalTujuan($sj->id);

        $data['id'] = $sj->id;
        $data['kode_surat'] = $sj->kode_surat;
        $data['status'] = $sj->status;
        $data['tipe'] = $sj->tipe;
        $data['updated_at'] = $sj->updated_at;
        $data['nama_project_manager'] = $projectManager->nama;
        $data['foto_project_manager'] = $projectManager->foto;
        $data['nama_admin_gudang'] = $sj->adminGudang->nama;
        $data['foto_admin_gudang'] = $sj->adminGudang->foto;
        $data['nama_driver'] = $sj->logistic->nama;
        $data['foto_driver'] = $sj->logistic->foto;
        $data['nama_supervisor'] = $supervisor->nama;
        $data['foto_supervisor'] = $supervisor->foto;
        $data['nama_supervisor_peminjam'] = ($supervisor_peminjam) ? $supervisor_peminjam->nama : null;
        $data['foto_supervisor_peminjam'] = ($supervisor_peminjam) ? $supervisor_peminjam->foto : null;
        $data['nama_tempat_asal'] = $lokasi['lokasi_asal']['nama'];
        $data['alamat_tempat_asal'] = $lokasi['lokasi_asal']['alamat'];
        $data['coordinate_tempat_asal'] = $lokasi['lokasi_asal']['coordinate'];
        $data['nama_tempat_tujuan'] = $lokasi['lokasi_tujuan']['nama'];
        $data['alamat_tempat_tujuan'] = $lokasi['lokasi_tujuan']['alamat'];
        $data['coordinate_tempat_tujuan'] = $lokasi['lokasi_tujuan']['coordinate'];
        return $data;
    }

}