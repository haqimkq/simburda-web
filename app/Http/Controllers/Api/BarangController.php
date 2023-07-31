<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\AksesBarang;
use App\Models\Barang;
use App\Models\BarangHabisPakai;
use App\Models\BarangTidakHabisPakai;
use App\Models\Menangani;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use Illuminate\Http\Request;
use Exception;


class BarangController extends Controller
{

    public function getBarangTanggungJawab(Request $request){
        try {
            $user = $request->user();
            $datas = PeminjamanDetail::where('penanggung_jawab_id', $user->id)->where(function($query){
                $query->where('status','DIGUNAKAN')->orWhere('status','TIDAK_DIGUNAKAN');
            })->get();
            $json = [];
            foreach ($datas as $data){
                $barang = $data->barang->barang;
                $barangA = [
                    'id' => $barang->id,
                    'merk' => $barang->merk,
                    'gambar' => $barang->gambar,
                    'detail' => $barang->detail,
                    'gudang' => $barang->gudang->nama ?? "",
                    'kondisi' => $data->barang->kondisi,
                    'nomor_seri' => $data->barang->nomor_seri,
                    'keterangan' => $data->barang->keterangan
                ];
                $pinjamanA = [
                    'id' => $data->peminjaman->id,
                    'nama_proyek' => $data->peminjaman->menangani->proyek->nama_proyek,
                    'kode_peminjaman' => $data->peminjaman->kode_peminjaman,
                    'tipe' => $data->peminjaman->tipe,
                    'tgl_peminjaman' => $data->peminjaman->getRemainingDaysAttribute()
                ];
                $detail = [
                    'id' => $data->id,
                    'status' => $data->status,
                    'barang' => $barangA,
                    'penanggung_jawab' => $data->penanggungJawab,
                    'peminjaman' => $pinjamanA
                ];
                array_push($json,$detail);
            }
            return ResponseFormatter::success('data', $json, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    public function getKodePeminjamanByProyek($id){
        try {
            $datas = Peminjaman::whereRelation('menangani', 'proyek_id', $id)->get();
            $json = [];
            foreach ($datas as $data){
                $pinjaman = [
                    'id' => $data->id,
                    'nama_proyek' => $data->menangani->proyek->nama_proyek,
                    'kode_peminjaman' => $data->kode_peminjaman,
                    'tipe' => $data->tipe,
                    'tgl_peminjaman' => $data->getRemainingDaysAttribute()
                ];
                array_push($json,$pinjaman);
            }
            return ResponseFormatter::success('data', $json, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    public function getDetailBarang(Barang $barang){
        try {
            $detail = [
                'id' => $barang->id,
                'nama' => $barang->nama,
                'merk' => $barang->merk,
                'gambar' => $barang->gambar,
                'detail' => $barang->detail,
                'gudang' => $barang->gudang->nama ?? ""
            ];
            if($barang->jenis == 'TIDAK_HABIS_PAKAI'){
                $detail['nomor_seri'] = $barang->barangTidakHabisPakai->nomor_seri;
                $detail['kondisi'] =  $barang->barangTidakHabisPakai->kondisi;
                $detail['keterangan'] =  $barang->barangTidakHabisPakai->keterangan;
            }else{
                $detail['jumlah'] = $barang->barangHabisPakai->jumlah;
                $detail['ukuran'] = $barang->barangHabisPakai->ukuran;
                $detail['satuan'] = $barang->barangHabisPakai->satuan;
            }
            return ResponseFormatter::success('data', $detail, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    public function getBarangTidakHabisPakai(){
        try {
            $json = [];
            $datas = BarangTidakHabisPakai::all();
            foreach($datas as $data){
                $barang = $data->barang;
                $detail = [
                    'id' => $barang->id,
                    'nomor_seri' => $data->nomor_seri,
                    'kondisi' => $data->kondisi,
                    'keterangan' => $data->keterangan,
                    'nama' => $barang->nama,
                    'merk' => $barang->merk,
                    'gambar' => $barang->gambar,
                    'detail' => $barang->detail,
                    'gudang' => $barang->gudang->nama ?? ""
                ];
                array_push($json, $detail);
            }
            return ResponseFormatter::success('data', $json, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    public function barangHabisPakai(){
        try {
            $json = [];
            $datas = BarangHabisPakai::all();
            foreach($datas as $data){
                $barang = $data->barang;
                $detail = [
                    'id' => $barang->id,
                    'jumlah' => $data->jumlah,
                    'satuan' => $data->satuan,
                    'ukuran' => $data->ukuran,
                    'nama' => $barang->nama,
                    'merk' => $barang->merk,
                    'gambar' => $barang->gambar,
                    'detail' => $barang->detail,
                    'gudang' => $barang->gudang->nama ?? ""
                ];
                array_push($json, $detail);
            }
            return ResponseFormatter::success('data', $json, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    public function barangTidakHabisPakaiByGudang($id){
        try {
            $json = [];
            $datas = BarangHabisPakai::whereRelation('barang','gudang_id',$id)->get();
            foreach($datas as $data){
                $barang = $data->barang;
                $detail = [
                    'id' => $barang->id,
                    'nomor_seri' => $data->nomor_seri,
                    'kondisi' => $data->kondisi,
                    'keterangan' => $data->keterangan,
                    'nama' => $barang->nama,
                    'merk' => $barang->merk,
                    'gambar' => $barang->gambar,
                    'detail' => $barang->detail,
                    'gudang' => $barang->gudang->nama ?? ""
                ];
                array_push($json, $detail);
            }
            return ResponseFormatter::success('data', $json, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }
    public function barangTidakHabisPakaiByKodePeminjaman($id){
        try {
            $json = [];
            $peminjaman = Peminjaman::where('id', $id)->first();
            foreach($peminjaman->peminjamanDetail as $data){
                $barang = $data->barang->barang;
                $tidakHabisPakai = $data->barang;
                $detail = [
                    'id' => $tidakHabisPakai->id,
                    'nomor_seri' => $tidakHabisPakai->nomor_seri,
                    'kondisi' => $tidakHabisPakai->kondisi,
                    'keterangan' => $tidakHabisPakai->keterangan,
                    'nama' => $barang->nama,
                    'merk' => $barang->merk,
                    'gambar' => $barang->gambar,
                    'detail' => $barang->detail,
                    'gudang' => $barang->gudang->nama ?? ""
                ];
                array_push($json, $detail);
            }
            return ResponseFormatter::success('data', $json, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    public function barangTidakHabisPakaiTersedia(Request $request){
        try {
            $json = [];
            $datas = BarangTidakHabisPakai::whereNull('peminjaman_id')->orWhereHas('peminjamanDetail', function($query){
                $query->where('status','TIDAK_DIGUNAKAN');
            })->search(request(['search']))->get();
            foreach($datas as $data){
                $barang = $data->barang;
                $detail = [
                    'id' => $barang->id,
                    'nomor_seri' => $data->nomor_seri,
                    'kondisi' => $data->kondisi,
                    'keterangan' => $data->keterangan,
                    'nama' => $barang->nama,
                    'merk' => $barang->merk,
                    'gambar' => $barang->gambar,
                    'detail' => $barang->detail,
                    'gudang' => $barang->gudang->nama ?? "",
                ];
                array_push($json, $detail);
            }
            return ResponseFormatter::success('data', $json, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    public function scanQrCode(Request $request){
        try{    
            $barang = BarangTidakHabisPakai::where('id',$request->barang_id)->first();
            if($barang->status == 'DIPESAN'){
                $authUser = $request->user();
                $proyek = $barang->peminjaman->menangani->proyek;
                $menanganis = Menangani::where('proyek_id',$proyek->id)->get();
                foreach ($menanganis as $menangani){
                    if($menangani->user_id == $authUser->id){
                        $peminjaman = PeminjamanDetail::where('barang_id',$barang->id)->where('peminjaman_id',$barang->peminjaman_id)->first();
                        //cek apakah tlah disetujui admin gudang dan site manager
                        if($peminjaman->aksesBarang->disetujui_sm && $peminjaman->aksesBarang->disetujui_admin){
                            //update Tanggung jawab
                            PeminjamanDetail::where('barang_id',$barang->id)->where('peminjaman_id',$barang->peminjaman_id)->update(['status' =>'DIGUNAKAN','penanggung_jawab_id' => $authUser->id]);
                            //update status
                            BarangTidakHabisPakai::where('id',$barang->id)->update(['status' => 'DIPINJAM']);
                            return ResponseFormatter::success('','','Barang Berhasil Diterima');
                        }
                    }
                }
                return ResponseFormatter::error('Tidak Memiliki Akses');
            } else{
                return ResponseFormatter::error('Barang Belum Diminta');
            }
        }catch(Exception $error){
            return ResponseFormatter::error('Server Error : '.$error->getMessage());
        }
    }

    public function updateStatusBarang(Request $request){
        try{    
            PeminjamanDetail::whereRelation('peminjaman.menangani','proyek_id',$request->proyek_id)->where('barang_id',$request->barang_id)->update(['status' => $request->status]);
            return ResponseFormatter::success(null,null,'Status Barang Berhasil Diperbaharui');
        }catch(Exception $error){
            return ResponseFormatter::error('Server Error : '.$error->getMessage());
        }
    }

    public function getRequestAksesBarang(Request $request){
        try {
            $json = [];
            $user = $request->user();
            if($user->role == "SITE_MANAGER"){
                $menanganis = $user->menanganiProyek;
                foreach($menanganis as $menangani){
                    // $peminjamans = $menangani->peminjaman;
                    $peminjamans = Peminjaman::where('menangani_id', $menangani->id)->proyek(request(['search']))->get();
                    // if($peminjamans){
                    //     dd($peminjamans);
                    // }
                    foreach($peminjamans as $peminjaman){
                        $peminjamanDetails = $peminjaman->peminjamanDetail;
                        foreach($peminjamanDetails as $peminjamanDetail){
                            $barang = $peminjamanDetail->barang->barang;
                            $aksesBarang = $peminjamanDetail->aksesBarang;
                            if(!$aksesBarang->disetujui_sm){
                                $barangA = [
                                    'id' => $barang->id,
                                    'merk' => $barang->merk,
                                    'gambar' => $barang->gambar,
                                    'detail' => $barang->detail,
                                    'gudang' => $barang->gudang->nama ?? "",
                                    'kondisi' => $peminjamanDetail->barang->kondisi,
                                    'nomor_seri' => $peminjamanDetail->barang->nomor_seri,
                                    'keterangan' => $peminjamanDetail->barang->keterangan
                                ];
                                $pinjamanA = [
                                    'id' => $peminjamanDetail->peminjaman->id,
                                    'nama_proyek' => $peminjamanDetail->peminjaman->menangani->proyek->nama_proyek,
                                    'kode_peminjaman' => $peminjamanDetail->peminjaman->kode_peminjaman,
                                    'tipe' => $peminjamanDetail->peminjaman->tipe,
                                    'tgl_peminjaman' => $peminjamanDetail->peminjaman->getRemainingDaysAttribute()
                                ];
                                $detail = [
                                    'id' => $peminjamanDetail->id,
                                    'status' => $peminjamanDetail->status,
                                    'barang' => $barangA,
                                    'penanggung_jawab' => $peminjamanDetail->penanggungJawab,
                                    'peminjaman' => $pinjamanA
                                ];
                                $aksesBarang = [
                                    'id' => $aksesBarang->id,
                                    'site_manager_access' => $aksesBarang->disetujui_sm,
                                    'admin_gudang_access' => $aksesBarang->disetujui_admin,
                                    'ket_admin_gudang' => $aksesBarang->keterangan_admin,
                                    'ket_site_manager' => $aksesBarang->keterangan_sm,
                                    'site_manager' => $aksesBarang->siteManager,
                                    'admin_gudang' => $aksesBarang->adminGudang,
                                    'peminjaman' => $detail
                                ];
                                array_push($json,$aksesBarang );
                            }
                        }
                    }
                }
                return ResponseFormatter::success('data', $json, 'Get Data');
            }else{
                return ResponseFormatter::error('Role bukan Site Manager');
            }
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    public function giveAksesPeminjamanSm(Request $request, $id){
        try{
            if($request->user()->role == "SITE_MANAGER"){
                AksesBarang::where('id', $id)->update(['disetujui_sm' => $request->access, 'site_manager_id' => $request->user()->id]);
                return ResponseFormatter::success(null, null, 'Berhasil Memperbarui Data');
            } else if ($request->user()->role == "ADMIN_GUDANG"){
                AksesBarang::where('id', $id)->update(['disetujui_admin' => $request->access, 'admin_gudang_id' => $request->user()->id]);
                return ResponseFormatter::success(null, null, 'Berhasil Memperbarui Data');
            } else {
                return ResponseFormatter::error('Tidak Memiliki Akses');
            }
        }catch(Exception $error){
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    
    public function getAksesBarangByUser(Request $request){
        try {
            $json = [];
            $peminjamans = Peminjaman::whereRelation('menangani','user_id',$request->user()->id)->get();
            foreach($peminjamans as $peminjaman){
                $peminjamanDetails = $peminjaman->peminjamanDetail;
                foreach($peminjamanDetails as $peminjamanDetail){
                    $barang = $peminjamanDetail->barang->barang;
                    $barangA = [
                        'id' => $barang->id,
                        'merk' => $barang->merk,
                        'gambar' => $barang->gambar,
                        'detail' => $barang->detail,
                        'gudang' => $barang->gudang->nama ?? "",
                        'kondisi' => $peminjamanDetail->barang->kondisi,
                        'nomor_seri' => $peminjamanDetail->barang->nomor_seri,
                        'keterangan' => $peminjamanDetail->barang->keterangan
                    ];
                    $pinjamanA = [
                        'id' => $peminjamanDetail->peminjaman->id,
                        'nama_proyek' => $peminjamanDetail->peminjaman->menangani->proyek->nama_proyek,
                        'kode_peminjaman' => $peminjamanDetail->peminjaman->kode_peminjaman,
                        'tipe' => $peminjamanDetail->peminjaman->tipe,
                        'tgl_peminjaman' => $peminjamanDetail->peminjaman->getRemainingDaysAttribute()
                    ];
                    $detail = [
                        'id' => $peminjamanDetail->id,
                        'status' => $peminjamanDetail->status,
                        'barang' => $barangA,
                        'penanggung_jawab' => $peminjamanDetail->penanggungJawab,
                        'peminjaman' => $pinjamanA
                    ];
                    $aksesBarang = $peminjamanDetail->aksesBarang;
                    $aksesBarang = [
                        'id' => $aksesBarang->id,
                        'site_manager_access' => $aksesBarang->disetujui_sm,
                        'admin_gudang_access' => $aksesBarang->disetujui_admin,
                        'ket_admin_gudang' => $aksesBarang->keterangan_admin,
                        'ket_site_manager' => $aksesBarang->keterangan_sm,
                        'site_manager' => $aksesBarang->siteManager,
                        'admin_gudang' => $aksesBarang->adminGudang,
                        'peminjaman' => $detail
                    ];
                    array_push($json,$aksesBarang );
                } 
            }
            return ResponseFormatter::success('data', $json, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    public function getDetailPeminjaman(PeminjamanDetail $peminjamanDetail){
        try {
                $barang = $peminjamanDetail->barang->barang;
                $barangA = [
                    'id' => $barang->id,
                    'merk' => $barang->merk,
                    'gambar' => $barang->gambar,
                    'detail' => $barang->detail,
                    'gudang' => $barang->gudang->nama ?? "",
                    'kondisi' => $peminjamanDetail->barang->kondisi,
                    'nomor_seri' => $peminjamanDetail->barang->nomor_seri,
                    'keterangan' => $peminjamanDetail->barang->keterangan
                ];
                $pinjamanA = [
                    'id' => $peminjamanDetail->peminjaman->id,
                    'nama_proyek' => $peminjamanDetail->peminjaman->menangani->proyek->nama_proyek,
                    'kode_peminjaman' => $peminjamanDetail->peminjaman->kode_peminjaman,
                    'tipe' => $peminjamanDetail->peminjaman->tipe,
                    'tgl_peminjaman' => $peminjamanDetail->peminjaman->getRemainingDaysAttribute()
                ];
                $detail = [
                    'id' => $peminjamanDetail->id,
                    'status' => $peminjamanDetail->status,
                    'barang' => $barangA,
                    'penanggung_jawab' => $peminjamanDetail->penanggungJawab,
                    'peminjaman' => $pinjamanA
                ];
            return ResponseFormatter::success('data', $detail, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }
}
?>