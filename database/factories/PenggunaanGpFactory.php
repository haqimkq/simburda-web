<?php

namespace Database\Factories;

use App\Models\Gudang;
use App\Models\Penggunaan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penggunaan_gp>
 */
class PenggunaanGpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'gudang_id' => Gudang::get()->random()->id,
            'penggunaan_id' => Penggunaan::factory(),
        ];
    }
    public function withPenggunaan(){
        return $this->state(function (array $attributes, Penggunaan $penggunaan){
            return [
                'id' => fake()->uuid(),
                'gudang_id' => Gudang::get()->random()->id,
                'penggunaan_id' => $penggunaan->id,
                'created_at' => $penggunaan->created_at,
                'updated_at' => $penggunaan->updated_at,
            ];
        });
    }
}
