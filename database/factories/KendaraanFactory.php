<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\kendaraan>
 */
class KendaraanFactory extends Factory
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
            'jenis' => Fake()->word(),
            'merk' => Fake()->word(),
            'kapasitas' => Fake()->word(),
            'plat_nomor' => Fake()->word()
        ];
    }
}
