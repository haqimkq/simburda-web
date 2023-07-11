<?php

namespace Database\Factories;

use App\Models\PengembalianPenggunaan;
use App\Models\Penggunaan;
use App\Models\PenggunaanDetail;
use App\Models\SjPengembalianPenggunaan;
use App\Models\SuratJalan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PengembalianPenggunaan>
 */
class PengembalianPenggunaanFactory extends Factory
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
        $penggunaan_detail = PenggunaanDetail::first();
        $kode_pengembalian = PengembalianPenggunaan::generateKodePengembalian($penggunaan_detail->peminjaman->menangani->proyek->client, $penggunaan_detail->peminjaman->menangani->user->nama);
        return [
            'id' => $id,
            'status' => $status,
            'kode_pengembalian' => $kode_pengembalian,
            'penggunaan_id' => $penggunaan_detail->penggunaan_id,
        ];
    }

    public function selesai(){
        return $this->state(function (array $attributes, Penggunaan $penggunaan){
            return [
                'penggunaan_id' => $penggunaan->id,
                'status' => 'SELESAI',
                'updated_at' => $penggunaan->created_at,
                'created_at' => $penggunaan->created_at
            ];
        })->has(
            SjPengembalianPenggunaan::factory()->state(function (array $attributes, PengembalianPenggunaan $pengembalian) {
                $sj = SuratJalan::factory()->selesaiSj()->create();
                return [
                    'id' => fake()->uuid(),
                    'pengembalian_penggunaan_id' => $pengembalian->id,
                    'updated_at' => $pengembalian->penggunaan->created_at,    
                    'created_at' => $pengembalian->penggunaan->created_at,
                    'surat_jalan_id' => $sj->id,
                ];
            })
            // ->for(SuratJalan::factory()->selesaiSj())
        , 'sjPengembalianPenggunaan');
    }
    public function menungguPengembalian(){
        return $this->state(function (array $attributes, Penggunaan $penggunaan){
            return [
                'penggunaan_id' => $penggunaan->id,
                'status' => 'MENUNGGU_PENGEMBALIAN',
                'updated_at' => $penggunaan->created_at,
                'created_at' => $penggunaan->created_at
            ];
        })->has(
            SjPengembalianPenggunaan::factory()->state(function (array $attributes, PengembalianPenggunaan $pengembalian) {
                $sj = SuratJalan::factory()->menungguSj()->create();
                return [
                    'id' => fake()->uuid(),
                    'pengembalian_penggunaan_id' => $pengembalian->id,
                    'updated_at' => $pengembalian->penggunaan->created_at,    
                    'created_at' => $pengembalian->penggunaan->created_at,
                    'surat_jalan_id' => $sj->id,
                ];
            })
            // ->for(SuratJalan::factory()->menungguSj())
        , 'sjPengembalianPenggunaan');
    }
    public function sedangDikembalikan(){
        return $this->state(function (array $attributes, Penggunaan $penggunaan){
            return [
                'penggunaan_id' => $penggunaan->id,
                'status' => 'SEDANG_DIKEMBALIKAN',
                'updated_at' => $penggunaan->created_at,
                'created_at' => $penggunaan->created_at
            ];
        })->has(
            SjPengembalianPenggunaan::factory()->state(function (array $attributes, PengembalianPenggunaan $pengembalian) {
                $sj = SuratJalan::factory()->dalamPerjalananSj()->create();
                return [
                    'id' => fake()->uuid(),
                    'pengembalian_penggunaan_id' => $pengembalian->id,
                    'updated_at' => $pengembalian->penggunaan->created_at,    
                    'created_at' => $pengembalian->penggunaan->created_at,
                    'surat_jalan_id' => $sj->id,
                ];
            })
            // ->for(SuratJalan::factory()->dalamPerjalananSj())
        , 'sjPengembalianPenggunaan');
    }
    public function menungguSuratJalan(){
        return $this->state(function (array $attributes, Penggunaan $penggunaan){
            return [
                'penggunaan_id' => $penggunaan->id,
                'status' => 'MENUNGGU_SURAT_JALAN',
                'updated_at' => $penggunaan->created_at,
                'created_at' => $penggunaan->created_at
            ];
        });
    }
}
