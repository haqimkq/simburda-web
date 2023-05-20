<?php

namespace App\Models;

use App\Helpers\Date;
use App\Helpers\IDGenerator;
use App\Traits\Uuids;
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
        return IDGenerator::generateID(SuratJalan::class,'kode_surat',5,"$typePrefix/$prefix");
    }
    public static function createData(Request $request, $create = true){
        self::validateCreate($request);
        if($create) return self::create([
            'admin_gudang_id' => $request->admin_gudang_id,
            'logistic_id' => $request->logistic_id,
            'kendaraan_id' => $request->kendaraan_id,
            'ttd_admin' => $request->ttd_admin,
            'tipe' => $request->tipe,
        ]);
        else return self::make([
            'admin_gudang_id' => $request->admin_gudang_id,
            'logistic_id' => $request->logistic_id,
            'ttd_admin' => $request->ttd_admin,
            'kendaraan_id' => $request->kendaraan_id,
            'tipe' => $request->tipe,
        ]);
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

    public static function getAllSuratJalanByUser($user,$tipe, $status, $size = 5){
        $result = collect();
        if($user->role == 'ADMIN_GUDANG') {
            $surat_jalan = SuratJalan::where('admin_gudang_id', $user->id)->where('tipe', $tipe)->where('status', $status)->paginate($size)->withQueryString();
            if($tipe == 'PENGIRIMAN_GUDANG_PROYEK'){
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($user->role, $sj, 'sjPengirimanGp', 'peminjaman'));
                }
            }else if($tipe == 'PENGIRIMAN_PROYEK_PROYEK'){
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($user->role, $sj, 'sjPengirimanPp', 'peminjamanAsal'));
                }
            }else{
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($user->role, $sj, 'sjPengembalian', 'peminjaman'));
                }
            }
        }else if($user->role == 'SUPERVISOR'){
            if($tipe == 'PENGIRIMAN_GUDANG_PROYEK'){
                $surat_jalan = SuratJalan::whereRelation('sjPengirimanGp.peminjaman.menangani.supervisor', 'id', $user->id)->where('status', $status)->paginate($size)->withQueryString();
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($user->role, $sj, 'sjPengirimanGp', 'peminjaman'));
                }
            }else if($tipe == 'PENGIRIMAN_PROYEK_PROYEK'){
                $surat_jalan = SuratJalan::whereRelation('sjPengirimanPp.peminjaman.menangani.supervisor', 'id', $user->id)->where('status', $status)->paginate($size)->withQueryString();
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($user->role, $sj, 'sjPengirimanPp', 'peminjamanAsal'));
                }
            }else{
                $surat_jalan = SuratJalan::whereRelation('sjPengembalian.pengembalian.peminjaman.menangani.supervisor', 'id', $user->id)->where('status', $status)->paginate($size)->withQueryString();
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($user->role, $sj, 'sjPengembalian', 'peminjaman'));
                }
            }
        }else if($user->role == 'PROJECT_MANAGER'){
            if($tipe == 'PENGIRIMAN_GUDANG_PROYEK'){
                $surat_jalan = SuratJalan::whereRelation('sjPengirimanGp.peminjaman.menangani.proyek.projectManager', 'id', $user->id)->where('status', $status)->paginate($size)->withQueryString();
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($user->role, $sj, 'sjPengirimanGp', 'peminjaman'));
                }
            }else if($tipe == 'PENGIRIMAN_PROYEK_PROYEK'){
                $surat_jalan = SuratJalan::whereRelation('sjPengirimanPp.peminjaman.menangani.proyek.projectManager', 'id', $user->id)->where('status', $status)->paginate($size)->withQueryString();
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($user->role, $sj, 'sjPengirimanPp', 'peminjamanAsal'));
                }
            }else{
                $surat_jalan = SuratJalan::whereRelation('sjPengembalian.pengembalian.peminjaman.menangani.proyek.projectManager', 'id', $user->id)->where('status', $status)->paginate($size)->withQueryString();
                foreach($surat_jalan as $sj){
                    $result->push(self::getSimpleDataSuratJalanByUser($user->role, $sj, 'sjPengembalian', 'peminjaman'));
                }
            }
        }
        return $result;
    }
    public static function getSimpleDataSuratJalanByUser($user_role,$sj, $tipe, $relasiPeminjamanAsal){
        $data = collect();
        $nama_project_manager = ($tipe != 'sjPengembalian') ? $sj->$tipe->peminjaman->menangani->proyek->projectManager->nama : $sj->$tipe->pengembalian->peminjaman->menangani->proyek->projectManager->nama;
        $foto_project_manager = ($tipe != 'sjPengembalian') ? $sj->$tipe->peminjaman->menangani->proyek->projectManager->foto: $sj->$tipe->pengembalian->peminjaman->menangani->proyek->projectManager->foto;
        $nama_supervisor = ($tipe != 'sjPengembalian') ? $sj->$tipe->peminjaman->menangani->supervisor->nama : $sj->$tipe->pengembalian->peminjaman->menangani->supervisor->nama;
        $foto_supervisor = ($tipe != 'sjPengembalian') ? $sj->$tipe->peminjaman->menangani->supervisor->foto : $sj->$tipe->pengembalian->peminjaman->menangani->supervisor->foto;
        $nama_admin_gudang = ($tipe != 'sjPengembalian') ? $sj->$tipe->peminjaman->menangani->proyek->projectManager->nama : $sj->$tipe->pengembalian->peminjaman->menangani->proyek->projectManager->nama;
        $foto_admin_gudang = ($tipe != 'sjPengembalian') ? $sj->$tipe->peminjaman->menangani->proyek->projectManager->foto : $sj->$tipe->pengembalian->peminjaman->menangani->proyek->projectManager->foto;
        $foto_driver = $sj->logistic->foto;
        $nama_driver = $sj->logistic->nama;
        
        $nama_tempat_asal = ($tipe != 'sjPengembalian') ? $sj->$tipe->$relasiPeminjamanAsal->gudang->nama : $sj->$tipe->pengembalian->$relasiPeminjamanAsal->gudang->nama;
        $alamat_tempat_asal = ($tipe != 'sjPengembalian') ? $sj->$tipe->$relasiPeminjamanAsal->gudang->alamat : $sj->$tipe->pengembalian->$relasiPeminjamanAsal->gudang->alamat;
        $coordinate_tempat_asal = ($tipe != 'sjPengembalian') ? $sj->$tipe->$relasiPeminjamanAsal->gudang->latitude . "|" . $sj->$tipe->$relasiPeminjamanAsal->gudang->longitude : $sj->$tipe->pengembalian->$relasiPeminjamanAsal->gudang->latitude . "|" . $sj->$tipe->pengembalian->$relasiPeminjamanAsal->gudang->longitude;

        $nama_tempat_tujuan = ($tipe != 'sjPengembalian') ? $sj->$tipe->peminjaman->menangani->proyek->nama_proyek : $sj->$tipe->pengembalian->peminjaman->menangani->proyek->nama_proyek;
        $alamat_tempat_tujuan = ($tipe != 'sjPengembalian') ? $sj->$tipe->peminjaman->menangani->proyek->alamat : $sj->$tipe->pengembalian->peminjaman->menangani->proyek->alamat;
        $coordinate_tempat_tujuan = ($tipe != 'sjPengembalian') ? $sj->$tipe->peminjaman->menangani->proyek->latitude . "|" . $sj->$tipe->peminjaman->menangani->proyek->longitude : $sj->$tipe->pengembalian->peminjaman->menangani->proyek->latitude . "|" . $sj->$tipe->pengembalian->peminjaman->menangani->proyek->longitude ;
        
        $data['id'] = $sj->id;
        $data['kode_surat'] = $sj->kode_surat;
        $data['status'] = $sj->status;
        $data['updated_at'] = $sj->updated_at;
        $data['nama_project_manager'] = ($user_role == 'SUPERVISOR') ? $nama_project_manager : null;
        $data['foto_project_manager'] = ($user_role == 'SUPERVISOR') ? $foto_project_manager : null;
        $data['nama_admin_gudang'] = ($user_role == 'PROJECT_MANAGER') ? $nama_admin_gudang : null;
        $data['foto_admin_gudang'] = ($user_role == 'PROJECT_MANAGER') ? $foto_admin_gudang : null;
        $data['nama_driver'] = $nama_driver;
        $data['foto_driver'] = $foto_driver;
        $data['nama_supervisor'] = $nama_supervisor;
        $data['foto_supervisor'] = $foto_supervisor;
        $data['nama_tempat_asal'] = ($tipe!='sjPengembalian') ? $nama_tempat_asal : $nama_tempat_tujuan;
        $data['alamat_tempat_asal'] = ($tipe!='sjPengembalian') ? $alamat_tempat_asal : $alamat_tempat_tujuan;
        $data['coordinate_tempat_asal'] = ($tipe!='sjPengembalian') ? $coordinate_tempat_asal : $coordinate_tempat_tujuan;
        $data['nama_tempat_tujuan'] = ($tipe!='sjPengembalian') ? $nama_tempat_tujuan : $nama_tempat_asal;
        $data['alamat_tempat_tujuan'] = ($tipe!='sjPengembalian') ? $alamat_tempat_tujuan : $alamat_tempat_asal;
        $data['coordinate_tempat_tujuan'] = ($tipe!='sjPengembalian') ? $coordinate_tempat_tujuan: $coordinate_tempat_asal;
        
        return $data; 
    }

}