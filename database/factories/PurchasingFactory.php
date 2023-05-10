<?php

namespace Database\Factories;

use App\Models\Purchasing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchasingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $kode_purchasing = Purchasing::generateKodePurchasing();
        return [
            'user_id' => User::where('role', 'PURCHASING')->get()->random()->id,
            'kode_purchasing' => $kode_purchasing,
            'created_at' => fake()->dateTimeBetween('-2 years', 'now')
        ];
    }
}
