<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\BarangHabisPakai;
use App\Models\BarangTidakHabisPakai;
use App\Models\PeminjamanDetail;
use App\Models\Pengembalian;
use App\Models\PengembalianDetail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Builder;

class PengembalianDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $id = fake()->uuid();
        $pengembalian = Pengembalian::latest()->first();
        $barang = BarangTidakHabisPakai::get()->random();
        return [
            'id' => $id,
            'barang_id' => $barang->id,
            'pengembalian_id' => $pengembalian->id,
        ];
    }
}
