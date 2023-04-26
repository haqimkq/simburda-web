<?php

namespace Database\Factories;

use App\Helpers\Location;
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
}
