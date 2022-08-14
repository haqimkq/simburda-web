<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proyek>
 */
class ProyekFactory extends Factory
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
            'proyek_manager_id' => User::where('role', 'like', 'project manager')->get()->random()->id,
            'nama_proyek' => fake()->word(),
            'alamat' => fake()->word(),
            'latitude' => fake()->latitude(-6.9,-6),
            'longitude' => fake()->longitude(-106.9,-106),
        ];
    }
}
