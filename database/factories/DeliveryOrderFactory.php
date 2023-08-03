<?php

namespace Database\Factories;

use App\Helpers\Date;
use App\Helpers\IDGenerator;
use App\Models\AdminGudang;
use App\Models\DeliveryOrder;
use App\Models\Gudang;
use App\Models\Kendaraan;
use App\Models\Perusahaan;
use App\Models\TtdVerification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $status = fake()->randomElement(['MENUNGGU_KONFIRMASI_DRIVER','DRIVER_DALAM_PERJALANAN', 'SELESAI']);
        $logistic_id = User::where('role', 'LOGISTIC')->get()->random()->id;
        $kendaraan_id = Kendaraan::get()->random()->id;
        $tgl_pengambilan = fake()->dateTimeBetween('-3 weeks', 'now');
        $perusahaan = Perusahaan::get()->random();
        $gudang = Gudang::get()->random();
        $admin_gudang_id = ($status == 'SELESAI') ? User::where('role', 'ADMIN_GUDANG')->get()->random()->id : null;
        $purchasing = User::where('role', 'PURCHASING')->get()->random();
        if($status != 'SELESAI' && $kendaraan_id != NULL){
            $kendaraan = Kendaraan::where('logistic_id', $logistic_id)->first();
            if($kendaraan!=null) $kendaraan_id = $kendaraan->id;
            else{
                Kendaraan::where('id', $kendaraan_id)->update([
                    'logistic_id' => $logistic_id
                ]);
            }
        }
        $untuk_perhatian = fake()->name();
        $ttd = TtdVerification::create([
            'user_id' => $purchasing->id,
            'tipe' => "DELIVERY_ORDER",
            'sebagai' => "PEMBUAT"
        ]);
        return [
            'id' => fake()->uuid(),
            'kode_do' => DeliveryOrder::generateKodeDO($perusahaan->nama, Date::dateToMillisecond($tgl_pengambilan)),
            'status' => $status,
            'purchasing_id' => $purchasing->id,
            'perusahaan_id' => $perusahaan->id,
            'foto_bukti' => ($status == 'SELESAI') ? 'https://picsum.photos/640/640?random='.mt_rand(1,92392) : null,
            'logistic_id' => $logistic_id,
            'kendaraan_id' => $kendaraan_id,
            'gudang_id' => $gudang->id,
            'admin_gudang_id' => $admin_gudang_id,
            'untuk_perhatian' => $untuk_perhatian,
            'tgl_pengambilan' => $tgl_pengambilan,
            'perihal' => 'Delivery Order',
            'ttd' => $ttd->id,
            'created_at' => $tgl_pengambilan
        ];
    }
}
