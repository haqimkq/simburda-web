<?php

namespace Database\Factories;

use App\Helpers\IDGenerator;
use App\Helpers\Date;
use App\Models\DeliveryOrder;
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
        $delivery_order = DeliveryOrder::latest();
        $perusahaanAlias = IDGenerator::getAcronym($delivery_order->perusahaan->nama);
        $romanMonth = IDGenerator::numberToRoman(Date::getMonthNumber());
        $prefix = "PO/BC-" . $perusahaanAlias . "/" . $romanMonth . "/" . Date::getYearNumber();
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
