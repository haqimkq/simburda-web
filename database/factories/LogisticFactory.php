<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\logistic>
 */
class LogisticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'logistic_id' => User::where('role', 'like', 'logistic')->get()->random()->id,
            'longitude' => fake()->latitude(-6.9,-6),
            'latitude' => fake()->longitude(-106.9,-106),
        ];
    }
}
