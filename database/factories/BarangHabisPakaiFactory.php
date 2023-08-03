<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $jumlah = fake()->numberBetween(10, 140);
        // $barang = Barang::where('jenis', 'HABIS_PAKAI')->doesntHave('barangHabisPakai')->get()->random();
        return [
            'id' => fake()->uuid(),
            'ukuran' => $ukuran,
            'satuan' => $satuan,
            'jumlah' => $jumlah,
            'barang_id' => Barang::factory()->state(['jenis' => 'HABIS_PAKAI']),
        ];
    }
}
