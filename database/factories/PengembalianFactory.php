<?php

namespace Database\Factories;

use App\Models\PeminjamanDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengembalianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $id = fake()->uuid();
        $kode_pengembalian = 
        $status = fake()->randomElement(['MENUNGGU_PENGEMBALIAN', 'SEDANG_DIKEMBALIKAN', 'SELESAI']);
        $peminjaman_detail = PeminjamanDetail::where('status', 'DIKEMBALIKAN')->get()->random();
        return [
            'id' => $id,
            'status' => $status,
            'kode_pengembalian' => $kode_pengembalian,
            'peminjaman_id' => $peminjaman_detail->peminjaman_id,
        ];
    }
}
