<?php

namespace Database\Factories;

use App\Helpers\Location;
use App\Models\BarangHabisPakai;
use App\Models\BarangTidakHabisPakai;
use App\Models\Gudang;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $id = fake()->uuid();
        $jenis = fake()->randomElement(['HABIS_PAKAI', 'TIDAK_HABIS_PAKAI']);
        $merk = fake()->words(2, true);
        $name = fake()->words(2, true);
        $randomImage = 'https://picsum.photos/640/640?random='.mt_rand(1,92392);
        $gudang_id = Gudang::get()->random()->id;

        if($jenis == 'HABIS_PAKAI'){
            BarangHabisPakai::factory()->state([
                'barang_id' => $id
            ])->create();
        }else{
            BarangTidakHabisPakai::factory()->state([
                'barang_id' => $id
            ])->create();
        }
        return [
            'id' => $id,
            'gudang_id' => $gudang_id,
            'nama' => $name,
            'merk' => $merk,
            'jenis' => $jenis,
            'gambar' => $randomImage,
            'detail' => fake()->text(100)
        ];
    }
    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */

     public function barangHabisPakai($satuan, $ukuran)
     {
         return $this->state(function (array $attributes) {
             return [
                 'email_verified_at' => null,
             ];
         });
     }
}
