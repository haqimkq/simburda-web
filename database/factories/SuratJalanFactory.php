<?php

namespace Database\Factories;

use App\Helpers\IDGenerator;
use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SuratJalanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $tipe = fake()->randomElement(['PENGIRIMAN_GUDANG_PROYEK', 'PENGEMBALIAN']);
        $status = fake()->randomElement(['MENUNGGU_KONFIRMASI_DRIVER','DRIVER_DALAM_PERJALANAN', 'SELESAI']);
        $logistic = User::where('role', 'like', 'LOGISTIC')->get()->random()->id;
        $kendaraan = Kendaraan::get()->random()->id;
        $adminGudang = User::where('role', 'like', 'ADMIN_GUDANG')->get()->random()->id;
        $ttdAdminGudang = fake()->imageUrl(640, 480, 'admin', true);
        $ttdDriver = ($status!='MENUNGGU_KONFIRMASI_DRIVER') ? fake()->imageUrl(640, 480, 'driver', true) : NULL;
        $ttdSupervisor = $status=='SELESAI' ? fake()->imageUrl(640, 480, 'supervisor', true) : NULL;
        $foto_bukti = $status=='SELESAI' ? 'https://picsum.photos/640/640?random='.mt_rand(1,92392) : NULL;
        if($status == 'SELESAI'){
            Kendaraan::where('id', $kendaraan->id)->update([
                'logistic_id' => NULL
            ]);
        }else{
            Kendaraan::where('id', $kendaraan->id)->update([
                'logistic_id' => $logistic
            ]);
        }
        $kode_surat = NULL;
        if($tipe=='PENGIRIMAN_GUDANG_PROYEK'){
            $kode_surat=IDGenerator::generateID(SuratJalan::class,'kode_surat',5,'SJGP');
        }else{
            $kode_surat=IDGenerator::generateID(SuratJalan::class,'kode_surat',5,'SJPG');
        }
        return [
            'id' => fake()->uuid(),
            'logistic_id' => $logistic,
            'kendaraan_id' => $kendaraan,
            'admin_gudang_id' => $adminGudang,
            'status' => $status,
            'tipe' => $tipe,
            'kode_surat' => $kode_surat,
            'ttd_admin' => $ttdAdminGudang,
            'ttd_driver' => $ttdDriver,
            'ttd_penerima' => $ttdSupervisor,
            'foto_bukti' => $foto_bukti,
        ];
    }

    /**
     * Indicate that the model's code should be for pengiriman proyek proyek.
     *
     * @return static
     */
    public function sjPengirimanPP()
    {
        return $this->state(function (array $attributes) {
            return [
                'kode_surat' => IDGenerator::generateID(SuratJalan::class,'kode_surat',5,'SJPP'),
            ];
        });
    }
    /**
     * Indicate that the model's code should be for pengiriman gudang proyek.
     *
     * @return static
     */
    public function sjPengirimanGP()
    {
        return $this->state(function (array $attributes) {
            return [
                'kode_surat' => IDGenerator::generateID(SuratJalan::class,'kode_surat',5,'SJGP'),
            ];
        });
    }
    /**
     * Indicate that the model's code should be for pengembalian gudang proyek.
     *
     * @return static
     */
    public function sjPengembalian()
    {
        return $this->state(function (array $attributes) {
            return [
                'kode_surat' => IDGenerator::generateID(SuratJalan::class,'kode_surat',5,'SJPG'),
            ];
        });
    }
}
