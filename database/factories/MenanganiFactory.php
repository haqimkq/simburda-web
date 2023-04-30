<?php

namespace Database\Factories;

use App\Models\Proyek;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenanganiFactory extends Factory
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
            'supervisor_id' => User::where('role', 'SUPERVISOR')->get()->random()->id,
            'proyek_id' => Proyek::get()->random()->id,
        ];
    }
}
