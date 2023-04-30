<?php

namespace Database\Factories;

use App\Models\PeminjamanDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengembalian>
 */
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
        $status = fake()->randomElement(['MENUNGGU_PENGEMBALIAN', 'SEDANG_DIKEMBALIKAN', 'SELESAI']);
        $peminjaman_detail = PeminjamanDetail::where('status', 'DIKEMBALIKAN')->get()->random();
        return [
            'id' => $id,
            'status' => $status,
            'peminjaman_id' => $peminjaman_detail->peminjaman_id,
        ];
    }
}
