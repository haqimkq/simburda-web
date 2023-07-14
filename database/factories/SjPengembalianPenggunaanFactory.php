<?php

namespace Database\Factories;

use App\Enum\PenggunaanTipe;
use App\Enum\SuratJalanTipe;
use App\Models\Pengembalian;
use App\Models\SjPengembalian;
use App\Models\SuratJalan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SjPengembalianPenggunaanFactory extends Factory
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
            $penggunaan = $sjPengembalian->Pengembalian->penggunaan;
            $proyek = $penggunaan->menangani->proyek;
            $user = $penggunaan->menangani->user;
            if($penggunaan->tipe == PenggunaanTipe::GUDANG_PROYEK->value){
                $penggunaanGp = $sjPengembalian->Pengembalian->penggunaan->penggunaanGp;
                $admin_gudang_id = User::where('role','ADMIN_GUDANG')->get()->random()->id;
                SuratJalan::find($sjPengembalian->suratJalan->id)->update([
                    'tipe' => SuratJalanTipe::PENGEMBALIAN_PENGGUNAAN->value,
                    'admin_gudang_id' => $admin_gudang_id,
                    'kode_surat' => SuratJalan::generateKodeSurat(SuratJalanTipe::PENGEMBALIAN_PENGGUNAAN->value, $proyek->client, $user->nama),
                    'updated_at' => $penggunaan->created_at,
                    'created_at' => $penggunaan->created_at,
                ]);
                $sjPengembalian->update([
                    'updated_at' => $penggunaan->created_at,
                    'created_at' => $penggunaan->created_at,
                ]);
            }
        });
    }
}
