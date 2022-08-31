<?php

namespace Database\Factories;

use App\Helpers\Location;
use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\surat_jalan>
 */
class SuratJalanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $lat_tujuan = fake()->latitude(-6.2,-6.1);
        $lon_tujuan = fake()->longitude(106.7,106.8);
        // $address_tujuan = Location::getAddress($lat_tujuan, $lon_tujuan);
        $lat_asal = fake()->latitude(-6.2,-6.1);
        $lon_asal = fake()->longitude(106.7,106.8);
        // $address_asal = Location::getAddress($lat_asal, $lon_asal);
        return [
            'id' => fake()->uuid(),
            'logistic_id' => User::where('role', 'like', 'logistic')->get()->random()->id,
            'kendaraan_id' => Kendaraan::all()->random()->id,
            'latitude_tujuan' => $lat_tujuan,
            'latitude_asal' => $lat_asal,
            'longitude_tujuan' => $lon_tujuan,
            'longitude_asal' => $lon_asal,
            'alamat_tujuan' => fake()->streetAddress(),
            'alamat_asal' => fake()->streetAddress(),
        ];
    }
}
