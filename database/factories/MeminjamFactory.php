<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\Proyek;
use App\Models\SuratJalan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meminjam>
 */
class MeminjamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'supervisor_id' => User::where('role', 'like', 'supervisor')->get()->random()->id,
            'barang_id' => Barang::all()->random()->id,
            'proyek_id' => Proyek::all()->random()->id,
            'surat_jalan_id' => SuratJalan::all()->random()->id,
            'jumlah' => mt_rand(1,5),
        ];
    }
}
