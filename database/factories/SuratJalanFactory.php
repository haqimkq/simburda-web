<?php

namespace Database\Factories;

use App\Helpers\Location;
use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use phpDocumentor\Reflection\Types\Null_;

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
        $adminGudangHasSetLogistic = fake()->boolean();
        $logistic = ($adminGudangHasSetLogistic) ? User::where('role', 'like', 'logistic')->get()->random()->id : NULL;
        $kendaraan = ($adminGudangHasSetLogistic) ? Kendaraan::all()->random()->id : NULL;
        $diterima = ($adminGudangHasSetLogistic) ? fake()->boolean() : NULL;
        $ttdAdminGudang = $adminGudangHasSetLogistic ? fake()->imageUrl(640, 480, 'admin', true) : NULL;
        $ttdDriver = (isset($diterima)) ? fake()->imageUrl(640, 480, 'driver', true) : NULL;
        $ttdSupervisor = $diterima ? fake()->imageUrl(640, 480, 'supervisor', true) : NULL;
        $foto_bukti = $diterima ? 'https://picsum.photos/640/640?random='.mt_rand(1,92392) : NULL;
        return [
            'id' => fake()->uuid(),
            'logistic_id' => $logistic,
            'kendaraan_id' => $kendaraan,
            'latitude_tujuan' => $lat_tujuan,
            'latitude_asal' => $lat_asal,
            'longitude_tujuan' => $lon_tujuan,
            'longitude_asal' => $lon_asal,
            'diterima' => $diterima,
            'alamat_tujuan' => fake()->streetAddress(),
            'alamat_asal' => fake()->streetAddress(),
            'ttd_admin' => $ttdAdminGudang,
            'ttd_driver' => $ttdDriver,
            'ttd_penerima' => $ttdSupervisor,
            'foto_bukti' => $foto_bukti,
        ];
    }
}
