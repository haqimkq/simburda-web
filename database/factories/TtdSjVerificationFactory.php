<?php

namespace Database\Factories;

use App\Models\SuratJalan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TtdSjVerification>
 */
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
            'id' => fake()->uuid(),
            'user_id' => User::get()->random()->id,
            'sebagai' => fake()->randomElement(['PENERIMA', 'PENGIRIM', 'PEMBERI']),
            'keterangan' => fake()->text(),
        ];
    }
}
