<?php

namespace Database\Factories;

use App\Enum\PeminjamanStatus;
use App\Enum\PeminjamanTipe;
use App\Enum\SuratJalanTipe;
use App\Models\Gudang;
use App\Models\Peminjaman;
use App\Models\PeminjamanGp;
use App\Models\PeminjamanPp;
use App\Models\SjPengembalian;
use App\Models\SjPengirimanGp;
use App\Models\SjPengirimanPp;
use App\Models\SuratJalan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PeminjamanGp>
 */
class PeminjamanGpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'gudang_id' => Gudang::get()->random()->id,
            'peminjaman_id' => Peminjaman::factory(),
        ];
    }
    public function withPeminjaman(){
        return $this->state(function (array $attributes, Peminjaman $peminjaman){
            return [
                'id' => fake()->uuid(),
                'gudang_id' => Gudang::get()->random()->id,
                'peminjaman_id' => $peminjaman->id,
                'created_at' => $peminjaman->created_at,
                'updated_at' => $peminjaman->updated_at,
            ];
        });
    }
}
