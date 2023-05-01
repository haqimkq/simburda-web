<?php

namespace Database\Factories;

use App\Models\Pengembalian;
use App\Models\SuratJalan;
use Illuminate\Database\Eloquent\Factories\Factory;

class SjPengembalianFactory extends Factory
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
            'surat_jalan_id' => SuratJalan::where('tipe','PENGIRIMAN_GUDANG_PROYEK')->get()->random()->id,
            'peminjaman_id' => Pengembalian::get()->random()->id
        ];
    }
}
