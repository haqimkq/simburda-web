<?php

namespace Database\Factories;

use App\Models\Meminjam;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\aksesBarang>
 */
class AksesBarangFactory extends Factory
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
            'meminjam_id' => Meminjam::all()->random()->id,
            'disetujui_admin' => fake()->optional()->boolean(50),
            'disetujui_pm' => fake()->optional()->boolean(50),
        ];
    }
}
