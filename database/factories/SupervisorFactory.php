<?php

namespace Database\Factories;

use App\Helpers\IDGenerator;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupervisorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::where('role', 'SUPERVISOR')->get()->random()->id,
            'kode_sv' => Supervisor::generateKodeSupervisor(),
            'created_at' => fake()->dateTimeBetween('-2 years', 'now')
        ];
    }
}
