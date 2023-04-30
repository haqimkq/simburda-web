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
        $status = fake()->randomElement(['MENUNGGU_KONFIRMASI_ADMIN_GUDANG', 'MENUNGGU_KONFIRMASI_DRIVER','DRIVER_DALAM_PERJALANAN', 'SELESAI']);
        $logistic = ($status!='MENUNGGU_KONFIRMASI_ADMIN_GUDANG') ? User::where('role', 'LOGISTIC')->get()->random()->id : NULL;
        $kendaraan = ($status!='MENUNGGU_KONFIRMASI_ADMIN_GUDANG') ? Kendaraan::get()->random()->id : NULL;
        $tgl_pengambilan = NULL;
        if($status == 'SELESAI'){
            $tgl_pengambilan = fake()->dateTimeBetween('now', '+5 days');
        }
        return [
            'id' => fake()->uuid(),
            'kode_delivery' => fake()->word(),
            'status' => $status,
            'purchasing_id' => User::where('role', 'PURCHASING')->get()->random()->id,
            'logistic_id' => $logistic,
            'kendaraan_id' => $kendaraan,
            'latitude' => fake()->latitude(-6.2,-6.1),
            'longitude' => fake()->longitude(106.7,106.8),
            'untuk_perusahaan' => fake()->word(),
            'untuk_perhatian' => fake()->name(),
            'tgl_pengambilan' => $tgl_pengambilan,
            'perihal' => 'Delivery Order'
        ];
    }
}
