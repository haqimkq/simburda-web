<?php

namespace Database\Factories;

use App\Models\Gudang;
use App\Models\Menangani;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $tgl_peminjaman = fake()->dateTimeBetween('-2 weeks', 'now');
        $tgl_berakhir = fake()->dateTimeBetween('-1 weeks', '+2 weeks');
        
        $now = Carbon::now();
        $start_date = Carbon::parse($tgl_peminjaman);
        $end_date = Carbon::parse($tgl_berakhir);

        $tipe = fake()->randomElement(['GUDANG_PROYEK','PROYEK_PROYEK']);
        $status = fake()->randomElement(['MENUNGGU_AKSES','AKSES_DITOLAK', 'MENUNGGU_PENGIRIMAN', 'SEDANG_DIKIRIM', 'DIPINJAM', 'SELESAI']);
        return [
            'id' => fake()->uuid(),
            'gudang_id' => Gudang::factory(),
            'menangani_id' => Menangani::factory(),
            'tipe' => $tipe,
            'status' => $status,
            'tgl_peminjaman' => $tgl_peminjaman,
            'tgl_berakhir' => $tgl_berakhir
        ];
    }
}
