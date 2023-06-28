<?php

namespace Database\Factories;

use App\Models\Gudang;
use App\Models\Peminjaman;
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
                'gudang_id' => Gudang::get()->random()->id,
                'peminjaman_id' => $peminjaman->id,
            ];
        });
    }
}
