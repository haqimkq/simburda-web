<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\BarangTidakHabisPakai;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BarangTidakHabisPakaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $nomor_seri = 1;
        $kondisi = fake()->randomElement(['BARU', 'BEKAS']);
        $keterangan = fake()->randomElement(['OK', 'Butuh Service']);
        return [
            'id' => fake()->uuid(),
            'keterangan' => $keterangan,
            'kondisi' => $kondisi,
            'nomor_seri' => $nomor_seri,
            'barang_id' => Barang::factory()->state(['jenis' => 'TIDAK_HABIS_PAKAI']),
            
        ];
    }
}
