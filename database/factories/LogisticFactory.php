<?php

namespace Database\Factories;

use App\Helpers\IDGenerator;
use App\Models\Logistic;
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
            'logistic_id' => User::factory(),
            'latitude' => fake()->latitude(-6.2,-6.1),
            'longitude' => fake()->longitude(106.7,106.8),
            'kode_logistic' => IDGenerator::generateID(Logistic::class,'kode_logistic',4, 'LOG')
        ];
    }
}
