<?php

namespace Database\Factories;

use App\Helpers\IDGenerator;
use App\Models\Kendaraan;
use App\Models\SuratJalan;
use App\Models\TtdSjVerification;
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
        $ttdAdminGudang = fake()->imageUrl(640, 480, 'admin', true);
        $ttdDriver = ($status!='MENUNGGU_KONFIRMASI_DRIVER') ? fake()->imageUrl(640, 480, 'driver', true) : NULL;
        $ttdSupervisor = $status=='SELESAI' ? fake()->imageUrl(640, 480, 'supervisor', true) : NULL;
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
            $ttdAdminGudang = TtdSjVerification::create([
                // 'keterangan' => TtdSjVerification::generateKeterangan($sj->id,'ADMIN_GUDANG'),
                "id" => fake()->uuid(),
                "user_id" => $attributes['admin_gudang_id'],
                'sebagai' => "PEMBERI",
                'keterangan' => ""
            ]);
            $ttdDriver = TtdSjVerification::create([
                "id" => fake()->uuid(),
                "user_id" => $attributes['logistic_id'],
                'sebagai' => "PEMBERI",
                // 'keterangan' => TtdSjVerification::generateKeterangan($sj->id,'LOGISTIC'),
                'keterangan' => ""
            ]);
            $ttdSupervisor = TtdSjVerification::create([
                "id" => fake()->uuid(),
                "user_id" => User::get()->random()->id,
                'sebagai' => "PEMBERI",
                // 'keterangan' => TtdSjVerification::generateKeterangan($sj->id,'SUPERVISOR')
                'keterangan' => ""
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
            $ttdAdminGudang = TtdSjVerification::create([
                "id" => fake()->uuid(),
                "user_id" => $attributes['admin_gudang_id'],
                // 'keterangan' => TtdSjVerification::generateKeterangan($sj->id,'ADMIN_GUDANG'),
                'sebagai' => "PEMBERI",
                'keterangan' => "",
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
            $ttdAdminGudang = TtdSjVerification::create([
                "id" => fake()->uuid(),
                "user_id" => $attributes['admin_gudang_id'],
                // 'keterangan' => TtdSjVerification::generateKeterangan($sj->id,'ADMIN_GUDANG'),
                'keterangan' => "",
                'sebagai' => "PEMBERI",
            ]);
            $ttdDriver = TtdSjVerification::create([
                "id" => fake()->uuid(),
                "user_id" => $attributes['logistic_id'],
                // 'keterangan' => TtdSjVerification::generateKeterangan($sj->id,'LOGISTIC'),
                'sebagai' => "PENGIRIM",
                'keterangan' => "",
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
