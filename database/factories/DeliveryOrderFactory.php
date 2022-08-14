<?php

namespace Database\Factories;

use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\delivery_order>
 */
class DeliveryOrderFactory extends Factory
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
            'purchasing_id' => User::where('role', 'like', 'purchasing')->get()->random()->id,
            'kendaraan_id' => Kendaraan::all()->random()->id,
            'longitude' => fake()->longitude(-106.9,-106),
            'latitude' => fake()->latitude(-6.9,-6),
            'untuk_perusahaan' => fake()->word(),
            'untuk_perhatian' => fake()->name(),
            'perihal' => fake()->word()
        ];
    }
}
