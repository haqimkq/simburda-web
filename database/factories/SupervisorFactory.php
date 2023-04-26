<?php

namespace Database\Factories;

use App\Helpers\IDGenerator;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supervisor>
 */
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
            'user_id' => User::factory(),
            'kode_sv' => IDGenerator::generateID(Supervisor::class,'kode_sv',5,'SV')
        ];
    }
}