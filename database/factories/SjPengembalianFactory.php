<?php

namespace Database\Factories;

use App\Enum\PeminjamanTipe;
use App\Enum\SuratJalanTipe;
use App\Models\Pengembalian;
use App\Models\SjPengembalian;
use App\Models\SuratJalan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SjPengembalianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // 'surat_jalan_id' => SuratJalan::where('tipe','PENGIRIMAN_GUDANG_PROYEK')->get()->random()->id,
            'surat_jalan_id' => SuratJalan::factory(),
            'pengembalian_id' => Pengembalian::factory(),
        ];
    }
    public function configure(){
        return $this->afterCreating(function (SjPengembalian $sjPengembalian) {
            $peminjaman = $sjPengembalian->pengembalian->peminjaman;
            $proyek = $peminjaman->menangani->proyek;
            $user = $peminjaman->menangani->user;
            if($peminjaman->tipe == PeminjamanTipe::GUDANG_PROYEK->value){
                $admin_gudang_id = User::where('role','ADMIN_GUDANG')->get()->random()->id;
                SuratJalan::find($sjPengembalian->suratJalan->id)->update([
                    'tipe' => SuratJalanTipe::PENGEMBALIAN->value,
                    'admin_gudang_id' => $admin_gudang_id,
                    'kode_surat' => SuratJalan::generateKodeSurat(SuratJalanTipe::PENGEMBALIAN->value, $proyek->client, $user->nama, $peminjaman->created_at),
                    'updated_at' => $peminjaman->created_at,
                    'created_at' => $peminjaman->created_at,
                ]);
                $sjPengembalian->update([
                    'updated_at' => $peminjaman->created_at,
                    'created_at' => $peminjaman->created_at,
                ]);
            }
        });
    }
}
