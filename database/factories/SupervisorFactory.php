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
            'kode_sv' => IDGenerator::generateID(Supervisor::class,'kode_sv',5,'SV')
        ];
    }
}
