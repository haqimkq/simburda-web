<?php

namespace Database\Factories;

use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\surat_jalan>
 */
class SuratJalanFactory extends Factory
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
            'logistic_id' => User::where('role', 'like', 'logistic')->get()->random()->id,
            'kendaraan_id' => Kendaraan::all()->random()->id,
            'latitude_tujuan' => fake()->latitude(-6.9,-6),
            'latitude_asal' => fake()->latitude(-6.9,-6),
            'longitude_tujuan' => fake()->longitude(-106.9,-106),
            'longitude_asal' => fake()->longitude(-106.9,-106),
            'alamat_tujuan' => fake()->word(),
            'alamat_asal' => fake()->word(),
        ];
    }
}
