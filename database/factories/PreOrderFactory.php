<?php

namespace Database\Factories;

use App\Models\DeliveryOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\pre_order>
 */
class PreOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => fake()->uuid(),
            'delivery_order_id' => DeliveryOrder::all()->random()->id,
            'nama_material' => fake()->word(),
            'satuan' => fake()->word(),
            'keterangan' => fake()->word(),
            'jumlah' => 5,
            'ukuran' => 25
        ];
    }
}
