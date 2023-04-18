<?php

namespace Database\Factories;

use App\Helpers\Location;
use App\Models\Kendaraan;
use App\Models\Proyek;
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
        // $lat_tujuan = fake()->latitude(-6.2,-6.1);
        // $lon_tujuan = fake()->longitude(106.7,106.8);
        $lat_asal = fake()->latitude(-6.2,-6.1);
        $lon_asal = fake()->longitude(106.7,106.8);

        $proyek = Proyek::all()->random();
        $lat_tujuan = $proyek->latitude;
        $lon_tujuan = $proyek->longitude;
        $alamat_tujuan = $proyek->alamat;

        $status = fake()->randomElement(['Menunggu konfirmasi driver','Driver dalam perjalanan', 'Selesai']);
        $logistic = User::where('role', 'like', 'logistic')->get()->random()->id;
        $kendaraan = Kendaraan::all()->random()->id;
        $ttdAdminGudang = fake()->imageUrl(640, 480, 'admin', true);
        $ttdDriver = ($status!='Menunggu konfirmasi driver') ? fake()->imageUrl(640, 480, 'driver', true) : NULL;
        $ttdSupervisor = $status=='Selesai' ? fake()->imageUrl(640, 480, 'supervisor', true) : NULL;
        $foto_bukti = $status=='Selesai' ? 'https://picsum.photos/640/640?random='.mt_rand(1,92392) : NULL;
        return [
            'id' => fake()->uuid(),
            'logistic_id' => $logistic,
            'kendaraan_id' => $kendaraan,
            'latitude_tujuan' => $lat_tujuan,
            'latitude_asal' => $lat_asal,
            'longitude_tujuan' => $lon_tujuan,
            'longitude_asal' => $lon_asal,
            'status' => $status,
            'alamat_tujuan' => $alamat_tujuan,
            'alamat_asal' => fake()->streetAddress(),
            'ttd_admin' => $ttdAdminGudang,
            'ttd_driver' => $ttdDriver,
            'ttd_penerima' => $ttdSupervisor,
            'foto_bukti' => $foto_bukti,
        ];
    }
}
