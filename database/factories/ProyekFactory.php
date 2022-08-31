<?php

namespace Database\Factories;

use App\Helpers\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proyek>
 */
class ProyekFactory extends Factory
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
        // $address = Location::getAddress($lat, $lon);
        $date = fake()->dateTimeBetween('-2 years', 'now');
        return [
            'id' => fake()->uuid(),
            'proyek_manager_id' => User::where('role', 'like', 'project manager')->get()->random()->id,
            'nama_proyek' => fake()->word(),
            'alamat' => fake()->streetAddress(),
            'latitude' => $lat,
            'longitude' => $lon,
            'selesai' => fake()->boolean(50),
            'created_at'=> $date,
            'updated_at'=> $date,
        ];
    }
}
