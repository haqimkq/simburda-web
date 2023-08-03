<?php

namespace Database\Factories;

use App\Models\BarangHabisPakai;
use App\Models\PengembalianPenggunaan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PengembalianPenggunaanDetail>
 */
class PengembalianPenggunaanDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $id = fake()->uuid();
        $pengembalian = PengembalianPenggunaan::latest()->first();
        $barang_habis_pakai = BarangHabisPakai::get()->random();
        $satuan = $barang_habis_pakai->satuan;
        $jumlah = fake()->numberBetween($barang_habis_pakai->jumlah);
        $jumlah_satuan = $jumlah . ' ' . $satuan;
        return [
            'id' => $id,
            'barang_id' => $barang_habis_pakai->id,
            'pengembalian_id' => $pengembalian->id,
            'jumlah_satuan' => $jumlah_satuan
        ];
    }
}
