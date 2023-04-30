<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\BarangHabisPakai;
use App\Models\Gudang;
use App\Models\Menangani;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\Pengembalian;
use App\Models\PengembalianDetail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamanDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $id = fake()->uuid();
        $peminjaman = Peminjaman::get()->latest();
        $barang = NULL;
        $peminjaman_detail = 'Not Null';
        while($peminjaman_detail != NULL){
            $barang = Barang::where('gudang_id', $peminjaman->gudang_id)->get()->random();
            $peminjaman_detail = PeminjamanDetail::
                where('peminjaman_id', $peminjaman->id)
                ->where('barang_id', $barang->id)
                ->get();
        }
        $satuan = NULL;
        $jumlah = NULL;
        $status = NULL;

        if($barang->jenis == 'TIDAK_HABIS_PAKAI') {
            $satuan = 'Unit';
            $jumlah = 1;
            if($peminjaman->status == "MENUNGGU_PENGIRIMAN" || $peminjaman->status == "SEDANG_DIKIRIM" || $peminjaman->status == "DIPINJAM"){
                Barang::where('id', $barang->id)->update(['peminjaman_id' => $peminjaman->id]);
                $status = "DIGUNAKAN";
            }else if($peminjaman->status == "SELESAI"){
                $status = "DIKEMBALIKAN";
                Barang::where('id', $barang->id)->update(['peminjaman_id' => NULL]);
                $pengembalian = Pengembalian::get()->latest();
                PengembalianDetail::factory()->state([
                    "barang_id" => $barang->id,
                    "pengembalian_id" => $pengembalian->id,
                    "jumlah_satuan" => $jumlah . ' ' . $satuan,
                ])->create();
            }else if($peminjaman->status == "MENUNGGU_AKSES"){
                $status = "MENUNGGU_AKSES";
            }
        }else{
            $barang_habis_pakai = BarangHabisPakai::where('barang_id', $barang->id)->get();
            $satuan = $barang_habis_pakai->satuan;
            $jumlah = fake()->randomNumber($barang_habis_pakai->jumlah);
            if($peminjaman->status == "MENUNGGU_PENGIRIMAN" || $peminjaman->status == "SEDANG_DIKIRIM" || $peminjaman->status == "DIPINJAM"){
                $status = "DIGUNAKAN";
            }else if($peminjaman->status == "SELESAI"){
                $status = "DIKEMBALIKAN";
            }else if($peminjaman->status == "MENUNGGU_AKSES"){
                $status = "MENUNGGU_AKSES";
            }
        }
        $jumlah_satuan = $jumlah . ' ' . $satuan;
        return [
            'id' => $id,
            'barang_id' => $barang->id,
            'peminjaman_proyek_lain_id' => NULL,
            'status' => $status,
            'peminjaman_id' => $peminjaman->id,
            'jumlah_satuan' => $jumlah_satuan
        ];
    }
}
