<?php

namespace Database\Factories;

use App\Helpers\IDGenerator;
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
        $delivery_order = DeliveryOrder::latest();
        // Delimit by multiple spaces, hyphen, underscore, comma, and dot
        $perusahaanAlias = preg_split("/[\s.,_-]+/", $delivery_order->perusahaan->nama);
        $date = fake()->now();
        $prefix = "PO-" . $perusahaanAlias;
        return [
            'id' => fake()->uuid(),
            'delivery_order_id' => $delivery_order->id,
            'nama_material' => fake()->words(2, true),
            'kode_po' => IDGenerator::generateID(PreOrder::class, 'kode_po', 5, $prefix),
            'satuan' => fake()->word(),
            'keterangan' => fake()->word(),
            'jumlah' => fake()->randomNumber(15),
            'ukuran' => fake()->words(2, true),
        ];
    }
}
