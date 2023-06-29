<?php

namespace Database\Factories;

use App\Helpers\IDGenerator;
use App\Models\AdminGudang;
use App\Models\Kendaraan;
use App\Models\Peminjaman;
use App\Models\PeminjamanGp;
use App\Models\PeminjamanPp;
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
        $logistic_id = User::where('role', 'LOGISTIC')->get()->random()->id;
        $kendaraan_id = Kendaraan::get()->random()->id;
        $adminGudang = User::where('role', 'ADMIN_GUDANG')->get()->random()->id;
        $foto_bukti = $status=='SELESAI' ? 'https://picsum.photos/640/640?random='.mt_rand(1,92392) : NULL;
        if($status == 'SELESAI'){
            // Kendaraan::where('id', $kendaraan)->update([
            //     'logistic_id' => NULL
            // ]);
        }else{
            $kendaraan = Kendaraan::where('logistic_id', $logistic_id)->first();
            if($kendaraan!=null) $kendaraan_id = $kendaraan->id;
            else{
                Kendaraan::where('id', $kendaraan_id)->update([
                    'logistic_id' => $logistic_id
                ]);
            }
        }
        $kode_surat = "SuratJalan";
        return [
            'id' => fake()->uuid(),
            'logistic_id' => $logistic_id,
            'kendaraan_id' => $kendaraan_id,
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
    public function selesaiSj()
    {
        return $this->state(function (array $attributes) {
            $status = 'SELESAI';
            $foto_bukti = 'https://picsum.photos/640/640?random='.mt_rand(1,92392);
            // Kendaraan::where('id', $attributes['kendaraan_id'])->update([
            //     'logistic_id' => NULL
            // ]);
            $ttdAdminGudang = TtdVerification::create([
                "id" => fake()->uuid(),
                "user_id" => $attributes['admin_gudang_id'],
                'tipe' => "SURAT_JALAN",
                'sebagai' => "PEMBERI",
            ]);
            $ttdDriver = TtdVerification::create([
                "id" => fake()->uuid(),
                "user_id" => $attributes['logistic_id'],
                'tipe' => "SURAT_JALAN",
                'sebagai' => "PEMBERI",
            ]);
            $ttdSupervisor = TtdVerification::create([
                "id" => fake()->uuid(),
                "user_id" => User::get()->random()->id,
                'tipe' => "SURAT_JALAN",
                'sebagai' => "PEMBERI",
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
    public function menungguSj()
    {
        return $this->state(function (array $attributes) {
            $status = 'MENUNGGU_KONFIRMASI_DRIVER';
            $ttdDriver = NULL;
            $ttdSupervisor = NULL;
            $foto_bukti = NULL;
            $ttdAdminGudang = TtdVerification::create([
                "id" => fake()->uuid(),
                "user_id" => $attributes['admin_gudang_id'],
                'tipe' => "SURAT_JALAN",
                'sebagai' => "PEMBERI",
            ]);
            // Kendaraan::where('logistic_id', $attributes['logistic_id'])->update(['logistic_id'=>null]);
            // Kendaraan::where('id', $attributes['kendaraan_id'])->update([
            //     'logistic_id' => $attributes['logistic_id']
            // ]);
            return [
                'status' => $status,
                'ttd_admin' => $ttdAdminGudang,
                'ttd_driver' => $ttdDriver,
                'ttd_supervisor' => $ttdSupervisor,
                'foto_bukti' => $foto_bukti,
            ];
        });
    }
    public function dalamPerjalananSj()
    {
        return $this->state(function (array $attributes) {
            $status = 'DRIVER_DALAM_PERJALANAN';
            $ttdAdminGudang = TtdVerification::create([
                "id" => fake()->uuid(),
                "user_id" => $attributes['admin_gudang_id'],
                'tipe' => "SURAT_JALAN",
                'sebagai' => "PEMBERI",
            ]);
            $ttdDriver = TtdVerification::create([
                "id" => fake()->uuid(),
                "user_id" => $attributes['logistic_id'],
                'tipe' => "SURAT_JALAN",
                'sebagai' => "PEMBERI",
            ]);
            $ttdSupervisor = NULL;
            $foto_bukti = NULL;
            // Kendaraan::where('logistic_id', $attributes['logistic_id'])->update(['logistic_id'=>null]);
            // Kendaraan::where('id', $attributes['kendaraan_id'])->update([
            //     'logistic_id' => $attributes['logistic_id']
            // ]);
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
