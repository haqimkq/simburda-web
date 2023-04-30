<?php

namespace Database\Factories;

use App\Helpers\IDGenerator;
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
        return [
            'user_id' => User::where('role', 'PURCHASING')->get()->random()->id,
            'kode_purchasing' => IDGenerator::generateID(Purchasing::class,'kode_purchasing',5,'PG')
        ];
    }
}
