<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $jenis = fake()->randomElement(['habis pakai', 'tidak habis pakai']);

        return [
            'id' => fake()->uuid(),
            'qrcode' => fake()->ean13(),
            'nama' => fake()->name(),
            'jenis' => $jenis,
            'gambar' => fake()->word(),
            'jumlah' => mt_rand(1,5),
            'alamat' => fake()->address(),
            'longitude' => fake()->longitude(-106.9,-106),
            'latitude' => fake()->latitude(-6.9,-6),
            'berat' => fake()->word(),
            'detail' => fake()->text(100),
            'excerpt' => fake()->sentence()
        ];
    }
}
