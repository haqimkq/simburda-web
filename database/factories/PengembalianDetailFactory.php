<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\BarangHabisPakai;
use App\Models\PeminjamanDetail;
use App\Models\Pengembalian;
use App\Models\PengembalianDetail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Builder;

class PengembalianDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $id = fake()->uuid();
        $pengembalian = Pengembalian::latest();
        $peminjaman_detail = PeminjamanDetail::where('status', 'DIKEMBALIKAN')->all()->random();
        $satuan = NULL;
        $jumlah = NULL;
        $barang = Barang::where('id', $peminjaman_detail->barang_id)->whereDoesntHave('pengembalianDetail', function (Builder $query) use ($pengembalian){
            $query->where('pengembalian_id', $pengembalian->id);
        })->all()->random();
        if($barang->jenis == 'TIDAK_HABIS_PAKAI') {
            $satuan = 'Unit';
            $jumlah = 1;
        }else{
            $barang_habis_pakai = BarangHabisPakai::where('barang_id', $barang->id)->get();
            $satuan = $barang_habis_pakai->satuan;
            $jumlah = fake()->randomNumber($barang_habis_pakai->jumlah);
        }
        $jumlah_satuan = $jumlah . ' ' . $satuan;
        return [
            'id' => $id,
            'barang_id' => $barang->id,
            'pengembalian_id' => $pengembalian->id,
            'jumlah_satuan' => $jumlah_satuan
        ];
    }
}
