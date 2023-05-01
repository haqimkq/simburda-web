<?php

namespace Database\Factories;

use App\Helpers\IDGenerator;
use App\Helpers\Date;
use App\Models\DeliveryOrder;
use App\Models\PreOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $delivery_order = DeliveryOrder::with('perusahaan')->latest();
        $kode_po = PreOrder::generateKodePO($delivery_order->perusahaan->nama);
        return [
            'id' => fake()->uuid(),
            'delivery_order_id' => $delivery_order->id,
            'nama_material' => fake()->words(2, true),
            'kode_po' => $kode_po,
            'satuan' => fake()->word(),
            'keterangan' => fake()->word(),
            'jumlah' => fake()->randomNumber(15),
            'ukuran' => fake()->words(2, true),
        ];
    }
}
