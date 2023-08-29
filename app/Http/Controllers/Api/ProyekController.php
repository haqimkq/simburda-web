<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Menangani;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\Proyek;
use Exception;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    public function updateProyek(Request $request, $id){
        try{
            $data = json_decode($request->proyek, true);
            // $data = $request->all();
            if($request->file('foto')){
                $data['foto'] = $request->file('foto')->store('assets/proyek', 'public');
            }
            Proyek::where('id', $id)->update($data);
            return ResponseFormatter::success(null, null, 'Store Data success');
        }catch(Exception $error){
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    public function addMenanganiProyek(Request $request, $id){
        try{
            if($request['supervisor'] != null){
                foreach($request['supervisor'] as $mengerjakan){
                    Menangani::create(['user_id'=> $mengerjakan,'proyek_id' => $id]);
                }
            }
            return ResponseFormatter::success(null, null, 'Store Data success');
        }catch(Exception $error){
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    } 

    public function deleteProyek($id){
        try{
            Proyek::destroy($id);
            return ResponseFormatter::success(null, null, 'Delete Data success');
        }catch(Exception $error){
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    } 

    public function storeProyek(Request $request){
        try{
            $data = json_decode($request->proyek, true);
            $userAuth = $request->user();
            // $validate = $data->validate([
            //     'nama_proyek' => 'required|string',
            //     'alamat' => 'required|string',
            //     'latitude' => 'required|numeric',
            //     'longitude' => 'required|numeric',
            //     'provinsi' => 'required',
            //     'kota' => 'required',
            //     'foto' => 'nullable',
            // ],[
            //         'nama_proyek.required' => 'Nama Proyek wajib diisi',
            //         'alamat.required' => 'Alamat wajib diisi',
            //         'latitude.required' => 'Latitude wajib diisi',
            //         'longitude.required' => 'Longitude wajib diisi',
            //         ]
            //     );
            //add Menangani
            $data['site_manager_id'] = $userAuth->id;
            if($request->file('foto')){
                $data['foto'] = $request->file('foto')->store('assets/proyek', 'public');
            }
            $proyek = Proyek::create($data);
            if($data['supervisor'] != null){
                foreach($data['supervisor'] as $mengerjakan){
                    Menangani::create(['user_id'=> $mengerjakan,'proyek_id' => $proyek->id]);
                }
            }
            return ResponseFormatter::success(null, null, 'Store Data success');
        }catch(Exception $error){
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    public function proyekYangMempunyaiPeminjaman(){
        try {
            $json = [];
            $datas = Proyek::all();
            foreach($datas as $data){
                $peminjaman = Peminjaman::whereRelation('menangani.proyek','proyek_id',$data->id)->get()->isNotEmpty();
                if($peminjaman){
                    $mengerjakan = [];
                    $pinjaman = [];
                    $menanganis = Menangani::where('proyek_id', $data->id)->get();
                    foreach ($menanganis as $menangani){
                        array_push($mengerjakan, $menangani->user);
                        $user = $menangani->user;
                        $peminjamanDetails = PeminjamanDetail::where('penanggung_jawab_id',$user->id)->whereRelation('peminjaman.menangani','proyek_id', $data->id)->get();
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
                            array_push($pinjaman,$detail);
                        }
                    }
                    $detail = [
                        "id" => $data->id,
                        "nama_proyek" => $data->nama_proyek,
                        "foto" => $data->foto,
                        "alamat" => $data->alamat,
                        "client" => $data->client,
                        "provinsi" => $data->provinsi,
                        "kota" => $data->kota,
                        "latitude" => $data->latitude,
                        "longitude" => $data->longitude,
                        "selesai" => $data->selesai,
                        "created_at" => $data->created_at,
                        "updated_at" => $data->updated_at,
                        "tgl_selesai" => $data->tgl_selesai,
                        "pembuat" => $data->siteManager,
                        "mengerjakan" => $mengerjakan,
                        'pinjaman' => $pinjaman
                    ];
                    array_push($json, $detail);
                }
            }
            return ResponseFormatter::success('data', $json, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }

    public function proyekYangDiKerjakan(Request $request){
        try {
            $json = [];
            $user = $request->user();
            $datas = $user->proyeks;
            foreach($datas as $data){
                $mengerjakan = [];
                $pinjaman = [];
                $menanganis = Menangani::where('proyek_id', $data->id)->get();
                foreach ($menanganis as $menangani){
                    array_push($mengerjakan, $menangani->user);
                    $user = $menangani->user;
                    $peminjamanDetails = PeminjamanDetail::where('penanggung_jawab_id',$user->id)->whereRelation('peminjaman.menangani','proyek_id', $data->id)->get();
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
                        array_push($pinjaman,$detail);
                    }
                }
                $detail = [
                    "id" => $data->id,
                    "nama_proyek" => $data->nama_proyek,
                    "foto" => $data->foto,
                    "alamat" => $data->alamat,
                    "client" => $data->client,
                    "provinsi" => $data->provinsi,
                    "kota" => $data->kota,
                    "latitude" => $data->latitude,
                    "longitude" => $data->longitude,
                    "selesai" => $data->selesai,
                    "created_at" => $data->created_at,
                    "updated_at" => $data->updated_at,
                    "tgl_selesai" => $data->tgl_selesai,
                    "pembuat" => $data->siteManager,
                    "mengerjakan" => $mengerjakan,
                    'pinjaman' => $pinjaman
                ];
                array_push($json, $detail);
            }
            return ResponseFormatter::success('data', $json, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }



    public function proyekYangDibuat(Request $request){
        try {
            $json = [];
            $user = $request->user();
            if($user->role == 'PROJECT_MANAGER'){
                $datas = Proyek::filter(request(['search','filter']))->get();
            }else{
                $datas = Proyek::where('site_manager_id', $user->id)->filter(request(['search','filter']))->get();
            }
            foreach($datas as $data){
                $mengerjakan = [];
                $pinjaman = [];
                $menanganis = Menangani::where('proyek_id', $data->id)->get();
                foreach ($menanganis as $menangani){
                    array_push($mengerjakan, $menangani->user);
                    $user = $menangani->user;
                    $peminjamanDetails = PeminjamanDetail::where('penanggung_jawab_id',$user->id)->whereRelation('peminjaman.menangani','proyek_id', $data->id)->get();
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
                        array_push($pinjaman,$detail);
                    }
                }
                $detail = [
                    "id" => $data->id,
                    "nama_proyek" => $data->nama_proyek,
                    "foto" => $data->foto,
                    "alamat" => $data->alamat,
                    "client" => $data->client,
                    "provinsi" => $data->provinsi,
                    "kota" => $data->kota,
                    "latitude" => $data->latitude,
                    "longitude" => $data->longitude,
                    "selesai" => $data->selesai,
                    "created_at" => $data->created_at,
                    "updated_at" => $data->updated_at,
                    "tgl_selesai" => $data->tgl_selesai,
                    "pembuat" => $data->siteManager,
                    "mengerjakan" => $mengerjakan,
                    'pinjaman' => $pinjaman
                ];
                array_push($json, $detail);
            }
            return ResponseFormatter::success('data', $json, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }
}
