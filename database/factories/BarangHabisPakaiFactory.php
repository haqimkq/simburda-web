<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BarangHabisPakai>
 */
class BarangHabisPakaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $satuan = fake()->randomElement(['Meter', 'Kilogram', 'Box', 'Lembar', 'Karung', 'Batang']);
        $ukuran = fake()->words(2, true);
        $jumlah = fake()->randomNumber(222);
        $barang = Barang::where('jenis', 'HABIS_PAKAI')->latest();
        return [
            'ukuran' => $ukuran,
            'satuan' => $satuan,
            'jumlah' => $jumlah,
            'barang_id' => $barang->id,
        ];
    }
}
