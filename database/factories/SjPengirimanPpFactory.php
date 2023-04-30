<?php

namespace Database\Factories;

use App\Models\Peminjaman;
use App\Models\SuratJalan;
use Illuminate\Database\Eloquent\Factories\Factory;

class SjPengirimanPpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => fake()->uuid(),
            'surat_jalan_id' => SuratJalan::factory()->state(['tipe'=>'PENGIRIMAN_GUDANG_PROYEK']),
            'peminjaman_asal_id' => Peminjaman::factory()->state(['tipe'=>'GUDANG_PROYEK']),
            'peminjaman_tujuan_id' => Peminjaman::factory()->state(['tipe'=>'PROYEK_PROYEK']),
        ];
    }
}
