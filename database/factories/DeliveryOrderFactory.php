<?php

namespace Database\Factories;

use App\Helpers\Date;
use App\Helpers\IDGenerator;
use App\Models\AdminGudang;
use App\Models\DeliveryOrder;
use App\Models\Gudang;
use App\Models\Kendaraan;
use App\Models\Perusahaan;
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
        $status = fake()->randomElement(['MENUNGGU_KONFIRMASI_ADMIN_GUDANG', 'MENUNGGU_KONFIRMASI_DRIVER','DRIVER_DALAM_PERJALANAN', 'SELESAI']);
        $logistic_id = ($status!='MENUNGGU_KONFIRMASI_ADMIN_GUDANG') ? User::where('role', 'LOGISTIC')->get()->random()->id : NULL;
        $kendaraan_id = ($status!='MENUNGGU_KONFIRMASI_ADMIN_GUDANG') ? Kendaraan::get()->random()->id : NULL;
        $tgl_pengambilan = fake()->dateTimeBetween('-3 weeks', 'now');
        $perusahaan = Perusahaan::get()->random();
        $gudang = Gudang::get()->random();
        $admin_gudang = AdminGudang::where('gudang_id', $gudang->id)->get()->random();

        if($status == 'SELESAI' && $kendaraan_id != NULL){
            Kendaraan::where('id', $kendaraan_id)->update([
                'logistic_id' => NULL
            ]);
        }else if($status != 'SELESAI' && $kendaraan_id != NULL){
            Kendaraan::where('id', $kendaraan_id)->update([
                'logistic_id' => $logistic_id
            ]);
        }
        return [
            'id' => fake()->uuid(),
            'kode_do' => DeliveryOrder::generateKodeDO($perusahaan->nama, $tgl_pengambilan),
            'status' => $status,
            'purchasing_id' => User::where('role', 'PURCHASING')->get()->random()->id,
            'perusahaan_id' => $perusahaan->id,
            'logistic_id' => $logistic_id,
            'kendaraan_id' => $kendaraan_id,
            'gudang_id' => $gudang->id,
            'admin_gudang_id' => $admin_gudang->id,
            'untuk_perhatian' => fake()->name(),
            'tgl_pengambilan' => $tgl_pengambilan,
            'perihal' => 'Delivery Order',
            'created_at' => $tgl_pengambilan
        ];
    }
}
