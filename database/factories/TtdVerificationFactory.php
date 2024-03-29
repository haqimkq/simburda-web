<?php

namespace Database\Factories;

use App\Models\SuratJalan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TtdVerificationFactory extends Factory
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
            'keterangan' => fake()->text(),
            'tipe' => fake()->randomElement(['SURAT_JALAN','DELIVERY_ORDER']),
            'sebagai' => fake()->randomElement(['PEMBUAT','PEMBERI', 'PENERIMA', 'PENGIRIM']),
        ];
    }
}
