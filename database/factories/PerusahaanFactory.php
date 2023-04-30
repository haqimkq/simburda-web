<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Perusahaan>
 */
class PerusahaanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $lat = fake()->latitude(-6.2,-6.1);
        $lon = fake()->longitude(106.7,106.8);
        $randomImage = 'https://picsum.photos/640/640?random='.mt_rand(1,92392);
        return [
            'id' => fake()->uuid(),
            'nama' => fake()->words(3, true),
            'alamat' => fake()->streetAddress(),
            'kota' => fake()->city(),
            'provinsi' => fake()->state(),
            'latitude' => $lat,
            'longitude' => $lon,
            'gambar' => $randomImage
        ];
    }
}
