<?php

namespace Database\Factories;

use App\Models\SuratJalan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TtdSjVerificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'sebagai' => fake()->randomElement(['PENERIMA', 'PENGIRIM', 'PEMBERI']),
        ];
    }
}
