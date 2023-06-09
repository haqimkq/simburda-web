<?php

namespace Database\Factories;

use App\Helpers\IDGenerator;
use App\Models\Kendaraan;
use App\Models\SuratJalan;
use App\Models\TtdSjVerification;
use App\Models\TtdVerification;
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
        $logistic = User::where('role', 'LOGISTIC')->get()->random()->id;
        $kendaraan = Kendaraan::get()->random()->id;
        $adminGudang = User::where('role', 'ADMIN_GUDANG')->get()->random()->id;
        $foto_bukti = $status=='SELESAI' ? 'https://picsum.photos/640/640?random='.mt_rand(1,92392) : NULL;
        if($status == 'SELESAI'){
            Kendaraan::where('id', $kendaraan)->update([
                'logistic_id' => NULL
            ]);
        }else{
            Kendaraan::where('id', $kendaraan)->update([
                'logistic_id' => $logistic
            ]);
        }
        $kode_surat = "SuratJalan";
        return [
            'id' => fake()->uuid(),
            'logistic_id' => $logistic,
            'kendaraan_id' => $kendaraan,
            'admin_gudang_id' => $adminGudang,
            'status' => $status,
            'tipe' => $tipe,
            'kode_surat' => $kode_surat,
            'ttd_admin' => null,
            'ttd_driver' => null,
            'ttd_supervisor' => null,
            'foto_bukti' => $foto_bukti,
        ];
    }
    public function selesai()
    {
        return $this->state(function (array $attributes) {
            $status = 'SELESAI';
            $foto_bukti = 'https://picsum.photos/640/640?random='.mt_rand(1,92392);
            Kendaraan::where('id', $attributes['kendaraan_id'])->update([
                'logistic_id' => NULL
            ]);
            $ttdAdminGudang = TtdVerification::create([
                "id" => fake()->uuid(),
                "user_id" => $attributes['admin_gudang_id'],
                'keterangan' => "",
                'tipe' => "SURAT_JALAN"
            ]);
            TtdSjVerification::create([
                "ttd_verification_id" => $ttdAdminGudang->id,
                'sebagai' => "PEMBERI",
            ]);
            $ttdDriver = TtdVerification::create([
                "id" => fake()->uuid(),
                "user_id" => $attributes['logistic_id'],
                'keterangan' => "",
                'tipe' => "SURAT_JALAN"
            ]);
            TtdSjVerification::create([
                "ttd_verification_id" => $ttdDriver->id,
                'sebagai' => "PENGIRIM",
            ]);
            $ttdSupervisor = TtdVerification::create([
                "id" => fake()->uuid(),
                "user_id" => User::get()->random()->id,
                'keterangan' => "",
                'tipe' => "SURAT_JALAN"
            ]);
            TtdSjVerification::create([
                "ttd_verification_id" => $ttdSupervisor->id,
                'sebagai' => "PENERIMA",
            ]);
            return [
                'status' => $status,
                'ttd_admin' => $ttdAdminGudang,
                'ttd_driver' => $ttdDriver,
                'ttd_supervisor' => $ttdSupervisor,
                'foto_bukti' => $foto_bukti,
            ];
        });
    }
    public function menunggu()
    {
        return $this->state(function (array $attributes) {
            $status = 'MENUNGGU_KONFIRMASI_DRIVER';
            $ttdDriver = NULL;
            $ttdSupervisor = NULL;
            $foto_bukti = NULL;
            $ttdAdminGudang = TtdVerification::create([
                "id" => fake()->uuid(),
                "user_id" => $attributes['admin_gudang_id'],
                'keterangan' => "",
                'tipe' => "SURAT_JALAN",
            ]);
            TtdSjVerification::create([
                "ttd_verification_id" => $ttdAdminGudang->id,
                'sebagai' => "PEMBERI",
            ]);
            Kendaraan::where('id', $attributes['kendaraan_id'])->update([
                'logistic_id' => $attributes['logistic_id']
            ]);
            return [
                'status' => $status,
                'ttd_admin' => $ttdAdminGudang,
                'ttd_driver' => $ttdDriver,
                'ttd_supervisor' => $ttdSupervisor,
                'foto_bukti' => $foto_bukti,
            ];
        });
    }
    public function dalamPerjalanan()
    {
        return $this->state(function (array $attributes) {
            $status = 'DRIVER_DALAM_PERJALANAN';
            $ttdAdminGudang = TtdVerification::create([
                "id" => fake()->uuid(),
                "user_id" => $attributes['admin_gudang_id'],
                'keterangan' => "",
                'tipe' => "SURAT_JALAN",
            ]);
            TtdSjVerification::create([
                "ttd_verification_id" => $ttdAdminGudang->id,
                'sebagai' => "PEMBERI",
            ]);
            $ttdDriver = TtdVerification::create([
                "id" => fake()->uuid(),
                "user_id" => $attributes['logistic_id'],
                'keterangan' => "",
                'tipe' => "SURAT_JALAN",
            ]);
            TtdSjVerification::create([
                "ttd_verification_id" => $ttdDriver->id,
                'sebagai' => "PENGIRIM",
            ]);
            $ttdSupervisor = NULL;
            $foto_bukti = NULL;
            Kendaraan::where('id', $attributes['kendaraan_id'])->update([
                'logistic_id' => $attributes['logistic_id']
            ]);
            return [
                'status' => $status,
                'ttd_admin' => $ttdAdminGudang,
                'ttd_driver' => $ttdDriver,
                'ttd_supervisor' => $ttdSupervisor,
                'foto_bukti' => $foto_bukti,
            ];
        });
    }
}
