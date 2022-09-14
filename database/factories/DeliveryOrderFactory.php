<?php

namespace Database\Factories;

use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\delivery_order>
 */
class DeliveryOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // $diambil = fake()->optional()->boolean();
        // $adminGudangHasSetLogistic = fake()->boolean();
        // $logistic = ((($diambil && isset($diambil)) || ((!$diambil && isset($diambil))) && $adminGudangHasSetLogistic)) ? User::where('role', 'like', 'logistic')->get()->random()->id : NULL;
        // $kendaraan = ((($diambil && isset($diambil)) || ((!$diambil && isset($diambil))) && $adminGudangHasSetLogistic)) ? Kendaraan::all()->random()->id : NULL;
        $diambil = fake()->optional()->boolean();
        $logistic = (($diambil && isset($diambil)) || (!$diambil && isset($diambil))) ? User::where('role', 'like', 'logistic')->get()->random()->id : NULL;
        $kendaraan = (($diambil && isset($diambil)) || (!$diambil && isset($diambil))) ? Kendaraan::all()->random()->id : NULL;
        return [
            'id' => fake()->uuid(),
            'kode_delivery' => fake()->word(),
            'diambil' => $diambil,
            'purchasing_id' => User::where('role', 'like', 'purchasing')->get()->random()->id,
            'logistic_id' => $logistic,
            'kendaraan_id' => $kendaraan,
            'latitude' => fake()->latitude(-6.2,-6.1),
            'longitude' => fake()->longitude(106.7,106.8),
            'untuk_perusahaan' => fake()->word(),
            'untuk_perhatian' => fake()->name(),
            'perihal' => fake()->word()
        ];
    }
}
