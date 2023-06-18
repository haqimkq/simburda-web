<?php

namespace Database\Factories;

use App\Models\PeminjamanDetail;
use App\Models\Pengembalian;
use App\Models\PengembalianDetail;
use App\Models\SjPengembalian;
use App\Models\SuratJalan;
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
        $status = fake()->randomElement(['MENUNGGU_SURAT_JALAN','MENUNGGU_PENGEMBALIAN', 'SEDANG_DIKEMBALIKAN', 'SELESAI']);
        // $peminjaman_detail = PeminjamanDetail::where('status', 'DIKEMBALIKAN')->get()->random();
        $peminjaman_detail = PeminjamanDetail::first();
        $kode_pengembalian = Pengembalian::generateKodePengembalian($peminjaman_detail->peminjaman->menangani->proyek->client, $peminjaman_detail->peminjaman->menangani->supervisor->nama);
        return [
            'id' => $id,
            'status' => $status,
            'kode_pengembalian' => $kode_pengembalian,
            'peminjaman_id' => $peminjaman_detail->peminjaman_id,
        ];
    }
}
