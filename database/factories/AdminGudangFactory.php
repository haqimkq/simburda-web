<?php

namespace Database\Factories;

use App\Models\AdminGudang;
use App\Models\Gudang;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminGudangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::where('role', 'ADMIN_GUDANG')->get()->random()->id,
            'gudang_id' => Gudang::get()->random()->id,
            'kode_ag' => AdminGudang::generateKodeAG(),
            'created_at' => fake()->dateTimeBetween('-2 years', 'now')
        ];
    }
}
