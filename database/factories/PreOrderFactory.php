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
        $delivery_order = DeliveryOrder::latest()->first();
        $satuan = fake()->randomElement(['Meter', 'Kilogram', 'Box', 'Lembar', 'Karung', 'Batang']);
        $kode_po = PreOrder::generateKodePO($delivery_order->perusahaan->nama,$delivery_order->tgl_pengambilan);
        return [
            'id' => fake()->uuid(),
            'delivery_order_id' => $delivery_order->id,
            'nama_material' => fake()->words(2, true),
            'kode_po' => $kode_po,
            'satuan' => $satuan,
            'keterangan' => fake()->word(),
            'jumlah' => fake()->numberBetween(1,40),
            'ukuran' => fake()->words(2, true),
            'created_at' => fake()->dateTimeBetween('-3 weeks', 'now')
        ];
    }
}
