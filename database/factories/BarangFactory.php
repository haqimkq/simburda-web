<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\BarangHabisPakai;
use App\Models\BarangTidakHabisPakai;
use App\Models\Gudang;
use Illuminate\Database\Eloquent\Factories\Factory;
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
    public function habisPakai(){
        return $this->state(function(array $attributes){
            return [
                'jenis' => 'HABIS_PAKAI'
            ];
        })->has(BarangHabisPakai::factory(), 'barangHabisPakai');
    }
    public function tidakHabisPakai(){
        return $this->state(function(array $attributes){
            return [
                'jenis' => 'TIDAK_HABIS_PAKAI'
            ];
        })->has(BarangTidakHabisPakai::factory(), 'barangTidakHabisPakai');
    }
}
