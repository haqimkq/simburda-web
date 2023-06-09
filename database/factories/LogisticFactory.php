<?php

namespace Database\Factories;

use App\Models\Logistic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'user_id' => User::where('role', 'LOGISTIC')->get()->random()->id,
            'latitude' => fake()->latitude(-6.2,-6.1),
            'longitude' => fake()->longitude(106.7,106.8),
            'kode_logistic' => Logistic::generateLogisticCode(),
            'created_at' => fake()->dateTimeBetween('-2 years', 'now')
        ];
    }
}
