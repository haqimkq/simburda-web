<?php

namespace Database\Factories;

use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class KendaraanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $randomImage = 'https://picsum.photos/640/640?random='.mt_rand(1,92392);
        $jenis = fake()->randomElement(['MOTOR','MOBIL','TRUCK','TRONTON']);
        $logisticId = User::where('role', 'like', 'logistic')->get()->random()->id;
        $logisticNoKendaraan = Kendaraan::where('logistic_id', $logisticId)->doesntExist();
        $logistic_id = $logisticNoKendaraan ? $logisticId : NULL;
        return [
            'id' => fake()->uuid(),
            'logistic_id' => $logistic_id,
            'jenis' => $jenis,
            'merk' => Fake()->word(),
            'plat_nomor' => Fake()->word(),
            // 'gambar' => fake()->imageUrl(360, 360, 'vehicle', true, $jenis, true),
            'gambar' => $randomImage,
        ];
    }
}
