<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Menangani;
use App\Models\Proyek;
use Exception;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    public function proyekYangDiKerjakan(Request $request){
        try {
            $json = [];
            $user = $request->user();
            $datas = $user->proyeks;
            foreach($datas as $data){
                $mengerjakan = [];
                $menanganis = Menangani::where('proyek_id', $data->id)->get();
                foreach ($menanganis as $menangani){
                    array_push($mengerjakan, $menangani->user);
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
                    "mengerjakan" => $mengerjakan
                ];
                array_push($json, $detail);
            }
            return ResponseFormatter::success('data', $json, 'Get Data');
        } catch (Exception $error) {
            return ResponseFormatter::error('Masalah Server : '.$error->getMessage());
        }
    }
}
